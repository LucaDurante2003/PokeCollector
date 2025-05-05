<?php
session_start();
require_once '../includes/config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        throw new Exception("Connessione fallita");
    }

    $id_utente = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT id_carta FROM collezioni WHERE id_utente = ?");
    $stmt->bind_param("i", $id_utente);
    $stmt->execute();
    $result = $stmt->get_result();

    $ids = [];
    while ($row = $result->fetch_assoc()) {
        $ids[] = $row['id_carta'];
    }

    echo json_encode($ids);

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