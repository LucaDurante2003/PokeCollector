<?php
    // Avvia la sessione
    session_start();
    require_once '../includes/config.php';

    //Se l'utente è loggato, si rimuove il token dal DB
    if (isset($_SESSION['user_id'])){
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if (!$conn->connect_error){
            $stmt = $conn->prepare("UPDATE utenti SET remember_token = NULL WHERE id = ?");
            $stmt->Bind_param("i", $_SESSION['user_id']);
            $stmt->execute();
            $stmt->close();
            $conn->close();
        }
    }

    //Cancella il cookie dal browser
    setcookie('remember_token', '', time() - 3600, '/', '', true, true);
    // Cancella tutte le variabili di sessione
    $_SESSION = [];
    // Distrugge la sessione
    session_destroy();
    // Output HTML per pulizia localStorage via JavaScript
    echo <<<HTML
    <!DOCTYPE html>
    <html lang="it">
    <head>
    <meta charset="UTF-8">
    <title>Logout...</title>
    <script>
    // Elimina tutti i valori salvati nel localStorage relativi a ricerca e filtro
    function clearPokecollectorLocalStorage() {
      Object.keys(localStorage).forEach(key => {
        if (key.startsWith("searchQuery_") || key.startsWith("filterOwned_")) {
          localStorage.removeItem(key);
        }
      });
    }
    // Esegui la pulizia e poi reindirizza alla pagina iniziale
    clearPokecollectorLocalStorage();
    window.location.href = "../index.php";
    </script>
    </head>
    <body>
    <p>Logout in corso...</p>
    </body>
    </html>
    HTML;
    exit;
?>