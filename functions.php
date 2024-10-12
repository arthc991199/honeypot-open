<?php
session_start();


// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Funkcja do debugowania
function debug($message) {
    echo '<pre>' . htmlentities($message, ENT_QUOTES, 'UTF-8') . '</pre>';
    error_log($message);
}

// Pobieranie pliku CSV
// Funkcja pobierania pliku CSV z datą
if (isset($_GET['download_csv'])) {
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        $file = 'honeypot_log.csv';  // Ścieżka do pliku CSV
        
        if (file_exists($file) && is_readable($file)) {
            $date = date('Ymd_His');  // Aktualna data
            $filename = 'honeypot_log_' . $date . '.csv';  // Dynamiczna nazwa pliku
            
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Pragma: no-cache');
            header('Expires: 0');
            readfile($file);  // Odczyt i wysyłanie pliku CSV
            exit;
        } else {
            echo "Plik nie istnieje lub nie można go odczytać.";
        }
    } else {
        header('Location: login.php');
        exit;
    }
}

// Funkcja do wyświetlania CSV na stronie (dla dane.php)
if (isset($_GET['get_csv'])) {
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        $file = 'honeypot_log.csv';
        
        if (file_exists($file) && is_readable($file)) {
            header('Content-Type: text/plain');
            readfile($file);  // Odczyt pliku CSV
            exit;
        } else {
            echo "Plik nie istnieje lub nie można go odczytać.";
        }
    } else {
        header('Location: login.php');
        exit;
    }
}

// Czyszczenie bazy danych
if (isset($_POST['clear_csv'])) {
    $file = 'honeypot_log.csv';
    if (file_exists($file)) {
        file_put_contents($file, "");  // Wyczyść zawartość pliku
        debug("Plik CSV został wyczyszczony.");
    } else {
        debug("Błąd: Próba czyszczenia nieistniejącego pliku CSV.");
    }
}

// Wylogowanie użytkownika
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}
// Zapisz dane w formacie CSV z użyciem średnika
fputcsv($file_handle, $data, ';');

// Funkcja do poprawnego zapisu CSV



function clean_value($value) {
    // Usuwamy dodatkowe cudzysłowy i niepotrzebne spacje
    return trim(str_replace('"', '', $value));
}

function log_to_csv($data) {
    $file = 'honeypot_log.csv';
    $handle = fopen($file, 'a');
    
    // Przetwarzamy dane użytkownika przed zapisaniem do CSV
    $data = array_map('clean_value', $data);
    
    // Zapisujemy dane do CSV bez ręcznego dodawania cudzysłowów
    fputcsv($handle, $data, ',');
    fclose($handle);
}

// Funkcja pobierania pliku CSV tylko po zalogowaniu


?>
