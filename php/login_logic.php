<?php
    // Avvia la sessione
    session_start();
    require_once '../includes/config.php';
    // Recupera e pulisce i dati inviati dal form
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    // Verifica che entrambi i campi siano stati compilati
    if (empty($email) || empty($password)){
        $_SESSION['error_login'] = 'Inserisci sia l\'email che la password.';
        header('Location: ../login.php');
        exit;
    }
    // Verifica che l'email abbia un formato valido
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $_SESSION['error_login'] = 'Formato email non valido.';
        header('Location: ../login.php');
        exit;
    }

    try{
        // Connessione al database
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error){
            throw new Exception("Connessione fallita");
        }
        // Recupera utente tramite email
        $stmt = $conn->prepare("SELECT id, nome, password FROM utenti WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        // Se l'utente esiste
        if ($result->num_rows === 1){
            $user = $result->fetch_assoc();
            // Verifica che la password fornita corrisponda all'hash salvato
            if (password_verify($password, $user['password'])){
                // Imposta variabili di sessione per l'utente loggato
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nome'];
                $_SESSION['success_login'] = 'Accesso effettuato con successo!';
                // Se è selezionato "Ricordami", gestisce il token persistente
                if (!empty($_POST['checkbox'])){
                    //Genera un token sicuro
                    $token = bin2hex(random_bytes(32));
                    //Salva il token nel DB
                    $stmt = $conn->prepare("UPDATE utenti SET remember_token = ? WHERE id = ?");
                    $stmt->bind_param("si", $token, $user['id']);
                    $stmt->execute();
                    $stmt->close();
                    //Salva il token nel cookie (scade dopo 30 giorni)
                    setcookie('remember_token', $token, time() + (86400*30), '/', '', true, true);
                }

                header('Location: ../homepage.php');
                exit;
            }
            else{
                $_SESSION['error_login'] = 'Password errata.';
            }
        } 
        else{
            $_SESSION['error_login'] = 'Nessun utente trovato con questa email.';
        }

        $stmt->close();
        $conn->close();
        header('Location: ../login.php');
        exit;
    } catch (mysqli_sql_exception $e) {
        header('Location: ../error.php');
        exit;
    } catch (Exception $e) {
        header('Location: ../error.php');
        exit;
    }
?>