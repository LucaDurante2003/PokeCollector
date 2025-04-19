<?php
    session_start();
    require_once '../includes/config.php';

    if (!isset($_SESSION['user_id'])){
        header('Location: ../login.php');
        exit;
    }

    $user_id = $_SESSION['user_id'];

    $old_password = $_POST['old_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Controllo campi vuoti
    if (empty($old_password) || empty($new_password) || empty($confirm_password)){
        $_SESSION['error_pw_change'] = 'Tutti i campi sono obbligatori.';
        header('Location: ../impostazioni.php');
        exit;
    }

    // Controllo nuova password diversa dalla vecchia
    if ($old_password === $new_password){
        $_SESSION['error_pw_change'] = 'La nuova password deve essere diversa da quella attuale.';
        header('Location: ../impostazioni.php');
        exit;
    }

    // Controllo corrispondenza nuova password e conferma
    if ($new_password !== $confirm_password){
        $_SESSION['error_pw_change'] = 'Le nuove password non coincidono.';
        header('Location: ../impostazioni.php');
        exit;
    }

    try{
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) {
            throw new Exception("Errore di connessione al database");
        }

        // Verifica password attuale
        $stmt = $conn->prepare("SELECT password FROM utenti WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($stored_hash);
        $stmt->fetch();
        $stmt->close();

        if (!password_verify($old_password, $stored_hash)) {
            $_SESSION['error_pw_change'] = 'La password attuale non è corretta.';
            header('Location: ../impostazioni.php');
            exit;
        }

        // Aggiornamento password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE utenti SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashed_password, $user_id);

        if ($stmt->execute()) {
            $_SESSION['success_pw_change'] = 'Password aggiornata con successo.';
        } else {
            $_SESSION['error_pw_change'] = 'Errore durante l\'aggiornamento della password.';
        }

        $stmt->close();
        $conn->close();
        header('Location: ../index.php');
        exit;

    }catch (Exception $e){
        header('Location: ../error.php');
        exit;
    }
?>