<?php
session_start();  // Rozpoczynamy sesję

// Przykładowe dane logowania (można je zmienić na prawdziwe dane)
$correct_username = 'admin';
$correct_password = 'password123';

// Sprawdzamy, czy formularz logowania został wysłany
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];  // Pobieramy wprowadzonego użytkownika
    $password = $_POST['password'];  // Pobieramy wprowadzone hasło

    // Weryfikacja poprawności danych logowania
    if ($username === $correct_username && $password === $correct_password) {
        // Jeśli dane są poprawne, ustawiamy sesję
        $_SESSION['loggedin'] = true;
        
        // Przekierowujemy użytkownika do strony dane.html
        header('Location: dane.php');
        exit;
    } else {
        // Jeśli dane są błędne, wyświetlamy komunikat o błędzie
        $error = "Nieprawidłowa nazwa użytkownika lub hasło!";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie</title>
</head>
<body>
    <h2>Logowanie do systemu</h2>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
    <form method="post" action="login.php">
        <label for="username">Nazwa użytkownika:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Hasło:</label>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Zaloguj się">
    </form>
</body>
</html>
