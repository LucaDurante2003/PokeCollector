<?php
    // Avvia la sessione
    session_start();
    require_once '../includes/config.php';

    // Controlla se l'utente è loggato
    if (!isset($_SESSION['user_id']) && !isset($_POST['new_password'])) {
        header('Location: ../login.php');
        exit;
    }

    $user_id = $_SESSION['user_id'] ?? null; // Se l'utente è loggato, usa il session_id, altrimenti sarà null
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $old_password = $_POST['old_password'] ?? null;  // Se è presente, il cambio password è tramite la vecchia password

    // Controllo campi vuoti
    if (empty($new_password) || empty($confirm_password)) {
        $_SESSION['error_pw_change'] = 'Tutti i campi sono obbligatori.';
        header('Location: ../impostazioni.php');
        exit;
    }

    // Controllo corrispondenza nuova password e conferma
    if ($new_password !== $confirm_password) {
        $_SESSION['error_pw_change'] = 'Le nuove password non coincidono.';
        header('Location: ../impostazioni.php');
        exit;
    }

    try {
        // Connessione al database
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) {
            throw new Exception("Connessione fallita");
        }

        // Se l'utente è loggato, deve essere verificata la vecchia password
        if ($user_id && $old_password) {
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
        }

        // Aggiornamento password (non è necessario l'old_password nel caso di reset)
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        if ($user_id){
            // Se l'utente è loggato, aggiorna la password
            $stmt = $conn->prepare("UPDATE utenti SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $hashed_password, $user_id);
        } 
        else{
            // Se l'utente non è loggato (modalità reset password), aggiorna la password usando l'email
            $email = $_POST['email'] ?? '';
            if (empty($email)) {
                $_SESSION['error_pw_change'] = 'L\'email è obbligatoria per il reset della password.';
                header('Location: ../impostazioni.php?reset_pw=1');
                exit;
            }
            // Controlla se esiste un utente con quella email
            $check_stmt = $conn->prepare("SELECT id FROM utenti WHERE email = ?");
            $check_stmt->bind_param("s", $email);
            $check_stmt->execute();
            $check_stmt->store_result();
            if ($check_stmt->num_rows === 0) {
                $_SESSION['error_pw_change'] = 'Nessun utente registrato con questa email.';
                $check_stmt->close();
                header('Location: ../impostazioni.php?reset_pw=1');
                exit;
            }
            $check_stmt->close();

            $stmt = $conn->prepare("UPDATE utenti SET password = ? WHERE email = ?");
            $stmt->bind_param("ss", $hashed_password, $email);
        }

        // Esegui l'aggiornamento
        if ($stmt->execute()){
            session_unset();
            session_destroy();

            if (isset($_COOKIE['remember_token'])){
                setcookie('remember_token', '', time()-3600, '/', '', true, true);
            }

            if($user_id){
                $null_token = null;
                $clear_stmt = $conn->prepare("UPDATE utenti SET remember_token = ? WHERE id = ?");
                $clear_stmt->bind_param("si", $null_token, $user_id);
                $clear_stmt->execute();
                $clear_stmt->close();
            }

            session_start();
            $_SESSION['success_pw_change'] = 'Password aggiornata con successo.';
        } 
        else{
            $_SESSION['error_pw_change'] = 'Errore durante l\'aggiornamento della password.';
        }

        $stmt->close();
        $conn->close();
        header('Location: ../login.php');
        exit;

    } catch (Exception $e) {
        header('Location: ../error.php');
        exit;
    } catch (mysqli_sql_exception $e) {
        header('Location: ../error.php');
        exit;
    }
?>