<?php
    session_start();
    require_once '../includes/config.php';

    //Controlla che l'utente sia loggato (ma in realtà è un controllo inutile)
    if (!isset($_SESSION['user_id'])){
        $_SESSION['error'] = 'Devi essere loggato per eliminare il tuo account.';
        header('Location: ../login.php');
        exit;
    }

    $user_id = $_SESSION['user_id'];

    try{
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) {
            throw new Exception("Connessione fallita");
        }

        //Elimina l'utente dal database
        $stmt = $conn->prepare("DELETE FROM utenti WHERE id = ?");
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();

            $_SESSION = []; //Pulisce la sessione
            /*Ricrea la sessione per poter usare $_SESSION['account_deleted'], che serve per vedere se l'eliminazione
            è andata bene o no, perchè se è andata bene apre una finestra modale gestita in impostazioni.php*/
            session_start();
            $_SESSION['account_deleted'] = true;

            header('Location: ../impostazioni.php');
            exit;
        } 
        else{
            $_SESSION['error'] = 'Errore durante l\'eliminazione dell\'account.';
            header('Location: ../impostazioni.php');
            exit;
        }
    }catch (Exception $e){
        header('Location: ../error.php');
    exit;
    }
?>