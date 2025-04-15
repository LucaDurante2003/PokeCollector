<?php
session_start();
require_once '../includes/config.php';

$nome = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

if (empty($nome) || empty($email) || empty($password) || empty($confirm_password)) {
    $_SESSION['error'] = 'Compila tutti i campi.';
    header('Location: ../register.php');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Email non valida.';
    header('Location: ../register.php');
    exit;
}

if ($password !== $confirm_password) {
    $_SESSION['error'] = 'Le password non corrispondono.';
    header('Location: ../register.php');
    exit;
}
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        throw new Exception("Connessione fallita");
    }

    $stmt = $conn->prepare("SELECT id FROM utenti WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['error'] = 'Esiste già un account con questa email. Prova a fare il login o usa un\'email diversa.';
        $stmt->close();
        $conn->close();
        header('Location: ../register.php');
        exit;
    }
    $stmt->close();

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO utenti (nome, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nome, $email, $password_hash);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Registrazione completata! Ora puoi accedere.';
        header('Location: ../login.php');
    } else {
        $_SESSION['error'] = 'Errore durante la registrazione.';
        header('Location: ../register.php');
    }

    $stmt->close();
    $conn->close();
} catch (mysqli_sql_exception $e) {
    header('Location: ../error.php');
    exit;
} catch (Exception $e) {
    header('Location: ../error.php');
    exit;
}
?>