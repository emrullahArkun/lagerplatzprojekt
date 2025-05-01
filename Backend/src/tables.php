<?php
function erstelleTabellen($konstantenTabelle, $datenIdsTabelle): void
{
    $host = 'localhost';
    $user = "postgres";
    $password = 'admin';
    $port = '5432';
    $dbname = 'postgres';
    try {
        // Verbindung aufbauen
        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
        $pdo = new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        error_log("Tabellen werden erstellt...");

        // SQL-Befehl zum Erstellen der Tabellen
        $regalKonstanteDB = "
            CREATE TABLE IF NOT EXISTS $konstantenTabelle (
                id SERIAL PRIMARY KEY,
                regalBezeichnung TEXT,
                reihen INTEGER,
                felder INTEGER,
                ebenen INTEGER,
                breite INTEGER,
                tiefe INTEGER,
                hoehe INTEGER
            );
        ";

        $regalidDB = "
            CREATE TABLE IF NOT EXISTS $datenIdsTabelle (
                id TEXT PRIMARY KEY,
                konstant_id INTEGER REFERENCES $konstantenTabelle(id)
            );
        ";

        // SQL ausführen
        $pdo->exec($regalKonstanteDB);
        $pdo->exec($regalidDB);
        error_log("Tabellen wurden erstellt!");

    } catch (PDOException $e) {
        echo "Verbindungsfehler: " . $e->getMessage();
    }
}

