<?php
session_start();
include_once 'includes/header.php';
include_once 'includes/config.php';

function checkDatabaseConnection() {
    try {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
        
        if ($conn->connect_error) {
            return false;
        }
        
        return true;
    } catch (Exception $e) {
        return false;
    }
}

if (checkDatabaseConnection()) {
    header('Location: index.php');
    exit;
}
?>
<body class="d-flex flex-column min-vh-100">
<div class="flex-column main-content text-center">
    <img src="assets/img/logo.png" alt="Logo PokéCollector" class="logo-img mb-4">
    <h2 class="text-orange-title mb-3">Stiamo cercando di ristabilire la connessione al database. Se il problema persiste, prova a ricaricare la pagina.</h2>
</div>

<footer class="footer">
    <p>&copy;PokéCollector - Progetto del corso Tecnologie e Sistemi Web A.A. 2024/2025 - Sapienza Università di Roma.</p>
</footer>
<script src = 'assets/js/check_connection.js'></script>
</body>