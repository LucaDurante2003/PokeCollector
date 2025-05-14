<?php
require_once 'includes/config.php';
// Evita esecuzione ricorsiva se ci si trova già su error.php
if (basename($_SERVER['PHP_SELF']) === 'error.php') {
    return;
}

try {
    // Connessione al database
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
    if ($conn->connect_error) {
        throw new Exception("Connessione fallita");
    }
    // Verifica se il database esiste
    $db_check = $conn->query("SHOW DATABASES LIKE '" . $conn->real_escape_string(DB_NAME) . "'");
    if ($db_check->num_rows == 0) {
        // Il database non esiste, procedi alla creazione eseguendo lo script SQL

        $sql_file = 'sql/script.sql';

        if (!file_exists($sql_file)) {
            throw new Exception("File SQL mancante.");
        }

        $sql = file_get_contents($sql_file);
        if (!$conn->multi_query($sql)) {
            throw new Exception("Errore esecuzione script SQL.");
        }
         // Pulisce i risultati multipli generati dallo script
        while ($conn->more_results() && $conn->next_result()) {;}
    }
    // Flag utile per evitare ripetizioni future
    $_SESSION['db_initialized'] = true;
}catch (mysqli_sql_exception $e) {
    header('Location: error.php');
    exit;
} catch (Exception $e) {
    header('Location: error.php');
    exit;
}