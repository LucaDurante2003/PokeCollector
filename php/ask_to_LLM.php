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
// Chiave API per OpenRouter (DeepSeek)
$api_key = "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";

// Prepara il payload della richiesta per OpenRouter
$data = [
    "model" => "deepseek/deepseek-chat-v3-0324:free", // API gratuita di un modello di Deepseek con 685B parametri
    "messages" => [
        ["role" => "system", "content" => "Sei un assistente esperto di carte Pokémon. Rispondi in italiano in modo semplice e naturale, senza usare Markdown (niente asterischi, grassetti o corsivi). Ti chiami Professor Oak"],
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
    echo json_encode(["reply" => "Errore ($http_code): $response"]);
    exit;
}

// Decodifica il JSON restituito dall’LLM
$result = json_decode($response, true);

// Estrae il contenuto della risposta dell’assistente (fallback in caso di errore)
$reply = $result["choices"][0]["message"]["content"] ?? "Nessuna risposta.";

echo json_encode(["reply" => $reply]);