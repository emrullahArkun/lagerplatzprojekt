# Lagerplatz-Tool

Webanwendung, mit der ein Benutzer Regal-Lagerplätze anlegen kann.  
Die Lagerplätze werden anhand der eingegebenen Daten berechnet und in der Datenbank gespeichert.

---

## Voraussetzungen

- **[PostgreSQL](https://www.postgresql.org/download/)** installiert und laufend
- **Node.js** (empfohlen: Version 18 oder höher)
- **npm** (wird mit Node.js installiert)
- **PHP** (für den lokalen Backend-Server)

---

## Projekt Setup

1. **Abhängigkeiten installieren**

    ```
    npm install
    ```

2. **Entwicklungsserver starten**

    ```
    npm run dev -- --port=8001
    ```

3. **Backend starten**

   Öffne ein neues Terminal und wechsle ins Backend-Verzeichnis:

    ```
    cd Backend/src/public
    php -S localhost:8000
    ```

4. **Mit PostgreSQL verbinden**

   Öffne ein weiteres Terminal und verbinde dich mit der Datenbank:

    ```
    psql -h localhost -p 5432 -U postgres -d postgres
    ```

   Gib als Passwort `admin` ein.

---
## Anwendung im Browser öffnen

Nach dem Start aller Dienste kannst du die Webanwendung im Browser unter  
**http://localhost:8001** aufrufen.

# Lagerplätze eines Lagers abrufen

Um die Lagerplätze eines bestimmten Lagers aus der Datenbank abzurufen, kannst du folgenden SQL-Query im PostgreSQL-Server ausführen. Ersetze dabei `<Name des Lagers>` 
durch den tatsächlichen Namen (Query in einer Zeile ausführen!):

```
SELECT d.id, k.regalbezeichnung, k.reihen, k.felder, k.ebenen, k.breite, k.tiefe, k.hoehe
FROM <Name des Lagers> d
JOIN regaldaten k ON d.konstant_id = k.id;
```
