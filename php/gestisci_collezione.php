<?php
session_start();
header('Content-Type: application/json');

require_once '../includes/config.php';

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        throw new Exception("Connessione fallita");
    }

    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Utente non autenticato');
    }

    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'] ?? '';
    $cardId = $data['card_id'] ?? '';
    $quantity = (int)($data['quantity'] ?? 1);

    if (!in_array($action, ['add', 'remove']) || !$cardId) {
        throw new Exception('Dati mancanti o non validi');
    }

    $userId = $_SESSION['user_id'];

    // Verifica se la carta esiste già
    $stmt = $conn->prepare("SELECT quantita FROM collezioni WHERE id_utente = ? AND id_carta = ?");
    $stmt->bind_param('is', $userId, $cardId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    // Logica add/remove
    if ($action === 'add') {
        if ($row) {
            $stmt = $conn->prepare("UPDATE collezioni SET quantita = quantita + ? WHERE id_utente = ? AND id_carta = ?");
            $stmt->bind_param('iis', $quantity, $userId, $cardId);
        } else {
            $stmt = $conn->prepare("INSERT INTO collezioni (id_utente, id_carta, quantita) VALUES (?, ?, ?)");
            $stmt->bind_param('isi', $userId, $cardId, $quantity);
        }
    } else { // 'remove'
        if (!$row || $row['quantita'] < $quantity) {
            throw new Exception('Quantità non disponibile');
        }
        if ($row['quantita'] > $quantity) {
            $stmt = $conn->prepare("UPDATE collezioni SET quantita = quantita - ? WHERE id_utente = ? AND id_carta = ?");
            $stmt->bind_param('iis', $quantity, $userId, $cardId);
        } else {
            $stmt = $conn->prepare("DELETE FROM collezioni WHERE id_utente = ? AND id_carta = ?");
            $stmt->bind_param('is', $userId, $cardId);
        }
    }

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        throw new Exception('Errore database');
    }

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