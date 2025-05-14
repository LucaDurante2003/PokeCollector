<?php
// Avvia la sessione
session_start();
require_once '../includes/config.php';
// Recupera e pulisce i dati inviati dal form
$nome = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
// Verifica che tutti i campi siano compilati
if (empty($nome) || empty($email) || empty($password) || empty($confirm_password)) {
    $_SESSION['error_register'] = 'Compila tutti i campi.';
    header('Location: ../register.php');
    exit;
}
// Verifica formato dell'email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error_register'] = 'Email non valida.';
    header('Location: ../register.php');
    exit;
}
// Verifica corrispondenza tra le due password
if ($password !== $confirm_password) {
    $_SESSION['error_register'] = 'Le password non corrispondono.';
    header('Location: ../register.php');
    exit;
}
try {
    // Connessione al database
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        throw new Exception("Connessione fallita");
    }
    // Verifica se esiste già un utente con la stessa email
    $stmt = $conn->prepare("SELECT id FROM utenti WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['error_register'] = 'Esiste già un account con questa email. Prova a fare il login o usa un\'email diversa.';
        $stmt->close();
        $conn->close();
        header('Location: ../register.php');
        exit;
    }
    $stmt->close();
    // Hash della password per sicurezza
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    // Inserisce il nuovo utente nel database
    $stmt = $conn->prepare("INSERT INTO utenti (nome, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nome, $email, $password_hash);

    if ($stmt->execute()) {
        // Registrazione riuscita: mostra messaggio di successo nella pagina di login
        $_SESSION['success_register'] = 'Registrazione completata! Ora puoi accedere.';
        header('Location: ../login.php');
    } else {
        // Errore nell'inserimento
        $_SESSION['error_register'] = 'Errore durante la registrazione.';
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