<?php
// CORS Header für Server
header("Access-Control-Allow-Origin: http://localhost:8001");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// CORS Preflight Request (Überprüfen, ob man Anfragen an den Port senden darf)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// POST-Abfrage bearbeiten
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // JSON-Daten
    $daten = json_decode(file_get_contents("php://input"), true);
    // Log in Terminal anzeigen
    error_log("Empfangene Daten:");
    error_log(print_r($daten, true));

    // Informationen in Response speichern
    echo json_encode([
        'status' => 'success',
        'message' => 'Daten empfangen',
        'daten' => $daten
    ]);
} else {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Nur POST erlaubt'
    ]);
}
