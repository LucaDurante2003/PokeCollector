<?php
    session_start();
    require_once '../includes/config.php';

    header('Content-Type: application/json'); //la risposta sarà in formato json

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false]);
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $old_password = $_POST['old_password'] ?? '';

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        echo json_encode(['success' => false]);
        exit;
    }

    $stmt = $conn->prepare("SELECT password FROM utenti WHERE id = ?"); //seleziona la password dell'utente con quell'ID
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($stored_hash); //vecchio hash
    $stmt->fetch();
    $stmt->close(); //chiude la query
    $conn->close(); //chiude la connesione al database

    //Controlla se la password inserita dall'utente nel campo vecchia password è uguale a quella registrata nel database
    if (password_verify($old_password, $stored_hash)) {
        echo json_encode(['success' => true]);
    } 
    else{
        echo json_encode(['success' => false]);
    }
?>
