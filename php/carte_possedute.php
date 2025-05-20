<?php
    // Avvia la sessione
    session_start();
    require_once '../includes/config.php';

    header('Content-Type: application/json');
    // Se l'utente non è loggato, restituisce un array vuoto
    if (!isset($_SESSION['user_id'])) {
        echo json_encode([]);
        exit;
    }

    try {
        // Connessione al database
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) {
            throw new Exception("Connessione fallita");
        }

        $id_utente = $_SESSION['user_id'];
        $stmt = $conn->prepare("SELECT id_carta FROM collezioni WHERE id_utente = ?");
        $stmt->bind_param("i", $id_utente);
        $stmt->execute();
        $result = $stmt->get_result();
        // Raccolta degli ID delle carte possedute
        $ids = [];
        while ($row = $result->fetch_assoc()) {
            $ids[] = $row['id_carta'];
        }
        // Restituisce l'elenco degli ID in formato JSON
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