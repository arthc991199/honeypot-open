<?php
session_start();

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
    exit;
}

function cleanValue($value) {
    return htmlentities($value, ENT_QUOTES, 'UTF-8');
}

// Funkcja do ładowania CSV
function loadCSV() {
    $file = 'honeypot_log.csv';
    if (file_exists($file) && is_readable($file)) {
        return file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    } else {
        return [];
    }
}

// Funkcja do wyświetlania statystyk
function displayStats() {
    echo "<p>Statystyki będą tutaj (placeholder na przyszłe funkcje).</p>";
}

$rows = loadCSV();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dane z Honeypot</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <h1>Dane z Honeypot</h1>

    <div class="action-buttons">
        <form method="GET" action="functions.php">
            <button type="submit" name="download_csv">Pobierz logi</button>
        </form>
        <form method="POST" action="functions.php">
            <button type="submit" name="clear_csv">Wyczyść logi</button>
        </form>
        <form method="POST" action="functions.php">
            <button type="submit" name="logout">Wyloguj się</button>
        </form>
        <button onclick="refreshData()">Odśwież dane</button>
    </div>

    <h3>Weryfikacja loginów i haseł w Have I Been Pwned (HIBP)</h3>

    <!-- Statystyki -->
    <div id="stats">
        <?php displayStats(); ?>
    </div>

    <!-- Paginacja -->
    <div id="pagination">
        <button onclick="changePage('prev')">Poprzednia strona</button>
        <span>Aktualna strona: <span id="currentPage">1</span></span>
        <button onclick="changePage('next')">Następna strona</button>
    </div>

    <!-- Tabela -->
    <div id="csvTable">
        <table>
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>IP</th>
                    <th>Request Method</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>User Agent</th>
                    <th>Fingerprint</th>
                    <th>Referrer</th>
                    <th>Accept-Language</th>
                    <th>OWASP Techniques</th>
                    <th>HIBP Username Status</th>
                    <th>HIBP Password Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($rows as $row) {
                    $columns = str_getcsv($row);
                    echo "<tr>";
                    foreach ($columns as $column) {
                        echo "<td>" . cleanValue($column) . "</td>";
                    }
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Plik skryptu ładowany na końcu, aby wszystkie elementy DOM były dostępne -->
    <script src="script.js"></script>
</body>
</html>
