<?php
session_start();
require_once '../includes/config.php';

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    $_SESSION['error_login'] = 'Inserisci sia l\'email che la password.';
    header('Location: ../login.php');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error_login'] = 'Formato email non valido.';
    header('Location: ../login.php');
    exit;
}

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        throw new Exception("Connessione fallita");
    }

    $stmt = $conn->prepare("SELECT id, nome, password FROM utenti WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nome'];
            $_SESSION['success_login'] = 'Accesso effettuato con successo!';
            header('Location: ../dashboard.php');
            exit;
        } else {
            $_SESSION['error_login'] = 'Password errata.';
        }
    } else {
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