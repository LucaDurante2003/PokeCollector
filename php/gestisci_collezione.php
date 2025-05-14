<?php
// Avvia la sessione
session_start();
header('Content-Type: application/json');

require_once '../includes/config.php';

try {
    // Connessione al database
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        throw new Exception("Connessione fallita");
    }
    // Verifica che l'utente sia autenticato
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Utente non autenticato');
    }
    // Recupera i dati dal corpo della richiesta (JSON)
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'] ?? '';
    $cardId = $data['card_id'] ?? '';
    $quantity = (int)($data['quantity'] ?? 1);
    // Controllo di validità dei dati
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
            // La carta esiste già: aggiorna la quantità
            $stmt = $conn->prepare("UPDATE collezioni SET quantita = quantita + ? WHERE id_utente = ? AND id_carta = ?");
            $stmt->bind_param('iis', $quantity, $userId, $cardId);
        } else {
            // La carta non esiste: inserisci una nuova riga
            $stmt = $conn->prepare("INSERT INTO collezioni (id_utente, id_carta, quantita) VALUES (?, ?, ?)");
            $stmt->bind_param('isi', $userId, $cardId, $quantity);
        }
    } else { // 'remove'
        if (!$row || $row['quantita'] < $quantity) {
            // Tentativo di rimuovere più copie di quelle possedute
            throw new Exception('Quantità non disponibile');
        }
        if ($row['quantita'] > $quantity) {
             // Rimuove una parte delle copie
            $stmt = $conn->prepare("UPDATE collezioni SET quantita = quantita - ? WHERE id_utente = ? AND id_carta = ?");
            $stmt->bind_param('iis', $quantity, $userId, $cardId);
        } else {
            // Rimuove la riga del tutto se quantità diventa 0
            $stmt = $conn->prepare("DELETE FROM collezioni WHERE id_utente = ? AND id_carta = ?");
            $stmt->bind_param('is', $userId, $cardId);
        }
    }
    // Esegue la query e restituisce risposta JSON
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