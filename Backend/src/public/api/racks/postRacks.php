<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../../../../vendor/autoload.php';
require __DIR__ . '/../../../tables.php';

$app = AppFactory::create();

$endpoint = '/api/racks/postRacks.php';
// Definieren der Header Bedingungen (CORS, Anfragemethoden, etc.)
$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
        // CORS Header für Server
        ->withHeader('Access-Control-Allow-Origin', '*')
        // Header für Preflight-Anfragen
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
        // Header für die erlaubten Header
        ->withHeader('Access-Control-Allow-Headers', 'Content-Type')
        // Header für den Content-Type
        ->withHeader('Content-Type', 'application/json');
});

// OPTIONS Route für Preflight-Anfragen
$app->options($endpoint, function (Request $request, Response $response) {
    return $response;
});

// Middleware für das Parsen von JSON-Daten
$app->addBodyParsingMiddleware();

// POST Route zum Empfangen der Daten
$app->post($endpoint, function (Request $request, Response $response) {
    // JSON-Daten empfangen
    $data = $request->getParsedBody();
    $regalbezeichnung = $data['regalbezeichnung'] ?? null;
    $reihen = $data['reihen'] ?? null;
    $felder = $data['felder'] ?? null;
    $ebenen = $data['ebenen'] ?? null;
    $breite = $data['breite'] ?? null;
    $tiefe = $data['tiefe'] ?? null;
    $hoehe = $data['hoehe'] ?? null;

    erstelleTabellen("regaldaten", "{$regalbezeichnung}_lagerplatznamen");

    // Konfiguration der Datenbankverbindung
    ORM::configure('pgsql:host=localhost;port=5432;dbname=postgres');
    ORM::configure('username', 'postgres');
    ORM::configure('password', 'admin');

    // Datensatz erstellen
    $main_table = ORM::forTable("regaldaten")->create();
    $main_table->regalbezeichnung = $regalbezeichnung;
    $main_table->reihen = $reihen;
    $main_table->felder = $felder;
    $main_table->ebenen = $ebenen;
    $main_table->breite = $breite;
    $main_table->tiefe = $tiefe;
    $main_table->hoehe = $hoehe;
    $main_table->save();

    // ID generieren und Datenbankeintrag erstellen
    for ($reihe = 0; $reihe < $reihen; $reihe++) {
        for ($feld = 0; $feld < $felder; $feld++) {
            for ($ebene = 0; $ebene < $ebenen; $ebene++) {
                $id = "{$regalbezeichnung}-" . formatiereEinstellig($reihe) . "-" . formatiereEinstellig($feld) . "-" . formatiereEinstellig($ebene);
                $lagerplatz = ORM::forTable(strtolower($regalbezeichnung) . '_lagerplatznamen')->create();
                $lagerplatz->id = $id;
                $lagerplatz->konstant_id = $main_table->id;
                $lagerplatz->save();
            }
        }
    }
    // Log in Terminal anzeigen
    error_log("Empfangene Daten:");
    error_log(print_r($data, true));

    $payload = json_encode([
        'status' => 'success',
        'message' => 'Daten empfangen',
        'daten' => $data
    ]);

    $response->getBody()->write($payload);
    return $response->withStatus(200);
});



// Funktion zum Formatieren von einstelligen Zahlen
function formatiereEinstellig($zahl): string
{
    if ($zahl >= 0 && $zahl <= 9) {
        return sprintf("%02d", $zahl);
    } else {
        return (string) $zahl;
    }
}
$app->run();