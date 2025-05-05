<?php
require_once '../includes/config.php';
session_start();
header('Content-Type: application/json');

// Controllo autenticazione
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Utente non autenticato']);
    exit;
}

// Controllo parametro
if (!isset($_GET['card_id'])) {
    echo json_encode(['success' => false, 'error' => 'ID carta mancante']);
    exit;
}

$id_utente = $_SESSION['user_id'];
$id_carta = $_GET['card_id'];

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        throw new Exception("Connessione fallita");
    }
    
    $stmt = $conn->prepare("SELECT quantita FROM collezioni WHERE id_utente = ? AND id_carta = ?");
    $stmt->bind_param("is", $id_utente, $id_carta);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $response = [
        'success' => true,
        'possessed' => false,
        'copies' => 0
    ];

    if ($row = $result->fetch_assoc()) {
        $response['possessed'] = true;
        $response['copies'] = (int)$row['quantita'];
    }

    echo json_encode($response);
    $result->close();
    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    header('Location: ../error.php');
    exit;
} catch (mysqli_sql_exception $e) {
    header('Location: ../error.php');
    exit;
}
?>