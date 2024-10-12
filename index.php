<?php
session_start(); // Rozpoczęcie sesji

// Włączenie wyświetlania błędów dla diagnostyki
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Funkcja do weryfikacji w HIBP (sprawdzamy zarówno login, jak i hasło)
function checkPwned($input) {
    if (!$input || $input === "brak") {
        return "brak danych"; // Jeśli brak danych, zwracamy "brak danych"
    }

    // Hashowanie SHA-1
    $hashed_input = strtoupper(sha1($input));
    $prefix = substr($hashed_input, 0, 5);  // Pierwsze 5 znaków
    $suffix = substr($hashed_input, 5);    // Reszta hash

    // Wysyłanie zapytania do HIBP
    $url = "https://api.pwnedpasswords.com/range/" . $prefix;
    $response = file_get_contents($url);

    // Sprawdzamy, czy hash istnieje w odpowiedzi
    return strpos($response, $suffix) !== false ? "w HIBP" : "brak w HIBP";
}

// Funkcja do analizy wzorców ataków (OWASP)
function analyzeOWASP($username, $password) {
    $patterns = json_decode(file_get_contents('attack_patterns.json'), true);
    $foundPatterns = [];
    
    // Analiza SQL Injection
    foreach ($patterns['sql_injection'] as $pattern) {
        if (strpos($username, $pattern) !== false || strpos($password, $pattern) !== false) {
            $foundPatterns[] = "Possible SQL Injection"; // Dodanie SQLi do listy
        }
    }

    // Analiza innych ataków (XSS, RFI itd.)
  // Funkcja do analizy innych ataków (XSS, RFI, LFI)
function analyzeOtherAttacks($username, $password) {
    $patterns = json_decode(file_get_contents('attack_patterns.json'), true);

    foreach ($patterns as $attackType => $patternList) {
        if ($attackType === 'sql_injection') continue; // Pomijamy SQLi, które już obsługujemy
        foreach ($patternList as $pattern) {
            if (strpos($username, $pattern) !== false || strpos($password, $pattern) !== false) {
                return ucfirst(str_replace('_', ' ', $attackType)); // Zwracamy wykryty atak
            }
        }
    }

    return "Clean"; // Jeśli nie znaleziono innych ataków
}


    // Zwracamy najwyższy priorytetowo wzorzec z listy
    return !empty($foundPatterns) ? $foundPatterns[0] : "Clean";
}

// Funkcja do logowania każdego wejścia
function logVisit($username = 'brak', $password = 'brak') {
    // Pobieranie danych nagłówków
    $ip = $_SERVER['REMOTE_ADDR'];
    $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'brak';
    $referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'brak';
    $accept_language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 5) : 'brak';
    $timestamp = date('Y-m-d H:i:s');
    $request_method = $_SERVER['REQUEST_METHOD'];

    // Analiza OWASP
    $owaspFlag = analyzeOWASP($username, $password);

    // Sprawdzenie HIBP dla loginu
    $hibpUsername = checkPwned($username);

    // Sprawdzenie HIBP dla hasła
    $hibpPassword = checkPwned($password);

    // Dane do logowania
    $data = [
        $timestamp,  // Timestamp
        $ip,  // IP
        $request_method,  // Request Method
        $username,  // Username
        $password,  // Password
        $user_agent,  // User Agent
        $referrer,  // Referrer
        $accept_language,  // Accept Language
        $owaspFlag,  // OWASP Technique
        $hibpUsername,  // HIBP Result for Username
        $hibpPassword  // HIBP Result for Password
    ];

    // Zapis do pliku CSV
    $file = fopen('honeypot_log.csv', 'a');
    fputcsv($file, $data);
    fclose($file);
}

// Przetwarzanie danych formularza
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? $_POST['username'] : 'brak';
    $password = isset($_POST['password']) ? $_POST['password'] : 'brak';

    // Logowanie wejścia i danych
    logVisit($username, $password);

    // Przekierowanie po zakończeniu logowania
    header('Location: index.php');
    exit;
} else {
    // Logowanie każdej wizyty bez podania danych
    logVisit();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Login</title>
</head>
<body>
    <h1>Login to Secure System</h1>
    <form action="index.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username"><br><br>
        <label for="password">Password (optional):</label>
        <input type="password" id="password" name="password"><br><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>
