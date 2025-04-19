<?php
    session_start();
    // Cancella tutte le variabili di sessione
    $_SESSION = [];
    // Distrugge la sessione
    session_destroy();
    // Reindirizza alla pagina iniziale
    header('Location: ../index.php');
    exit;
?>
