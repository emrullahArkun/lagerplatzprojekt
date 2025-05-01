<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../../../../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/api/racks/getRacks.php', function (Request $request, Response $response) {
    ORM::configure('pgsql:host=localhost;port=5432;dbname=postgres');
    ORM::configure('username', 'postgres');
    ORM::configure('password', 'admin');

    $query = ORM::forTable('a_lagerplatznamen')
        ->rawQuery('SELECT d.id, k.regalbezeichnung, k.reihen, k.felder, k.ebenen, k.breite, k.tiefe, k.hoehe
                    FROM a_lagerplatznamen d
                    JOIN regaldaten k ON d.konstant_id = k.id')
        ->findArray();

    error_log("Query executed: ");
    error_log(var_export($query, true));
    $response->getBody()->write(json_encode($query));
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withStatus(200);

});

$app->run();