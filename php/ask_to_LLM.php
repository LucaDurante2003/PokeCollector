<?php
header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);
// Verifica che sia stato inviato un messaggio valido
if (!isset($input["message"])) {
    echo json_encode(["reply" => "Messaggio non valido."]);
    exit;
}
// Estrae il messaggio dell’utente
$user_input = $input["message"];
// Estrae il nome dell’utente
$user_name = $input["user_name"] ?? "allenatore";
// Chiave API per OpenRouter (DeepSeek)
$api_key = "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";

// Prepara il payload della richiesta per OpenRouter
$data = [
    "model" => "deepseek/deepseek-chat-v3-0324:free", // API gratuita di un modello di Deepseek con 685B parametri
    "messages" => [
        ["role" => "system", "content" => "Sei un assistente esperto di carte Pokémon. Ti chiami Professor Oak. Rispondi in italiano in modo semplice, naturale e diretto. Non usare markdown, simboli narrativi, asterischi o descrizioni tra *asterischi*. Rispondi come se parlassi a voce, senza effetti teatrali. L'utente si chiama $user_name."],
        ["role" => "user", "content" => $user_input]
    ]
];

// Inizializza la richiesta CURL verso l’API di OpenRouter
$ch = curl_init("https://openrouter.ai/api/v1/chat/completions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $api_key",
    "HTTP-Referer: https://pokecollector.local",
    "X-Title: PokéCollector Assistant"
]);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

// Esegue la richiesta e riceve la risposta
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Se la risposta non è OK (200), invia un messaggio di errore al frontend
if ($http_code !== 200) {
    switch ($http_code) {
        case 401:
            $userMessage = "La tua chiave API non è valida.";
            break;
        case 429:
            $userMessage = "Hai raggiunto il limite giornaliero. Riprova domani.";
            break;
        case 500:
            $userMessage = "Il modello ha riscontrato un errore interno. Riprova tra poco.";
            break;
        default:
            $userMessage = "Errore imprevisto ($http_code).";
    }
    echo json_encode(["reply" => $userMessage]);
    exit;
}

// Decodifica il JSON restituito dall’LLM
$result = json_decode($response, true);

// Estrae il contenuto della risposta dell’assistente (fallback in caso di errore)
$reply = $result["choices"][0]["message"]["content"] ?? "Nessuna risposta.";

echo json_encode(["reply" => $reply]);