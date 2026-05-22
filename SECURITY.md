# Sicherheit

## Was nicht ins Repository gehört

Diese Dateien enthält **niemals** committen (stehen in `.gitignore`):

- `config.php` – Bot-Token, `door_key`, Chat-IDs, WLAN, Kontodaten, Insta-Zugang
- `tellogs/` – Webhook-Logs, Tür-Status, Freigabe-Listen
- `dates.log` – Termindaten des Vereins

Für GitHub nur `config.example.php` mit Platzhaltern verwenden.

## Vor dem ersten Push auf GitHub

1. **Token rotieren** ([@BotFather](https://t.me/BotFather) → `/revoke` oder neuer Token), wenn der Bot-Token jemals in Klartext in Code, Chats oder alten Commits stand.
2. **`door_key` erneuern** in `config.php` und an allen Skripten/Hardware-Anbindungen, die `?door=` nutzen.
3. **Prüfen, was Git staged:**

   ```bash
   git init
   git add -A
   git status
   ```

   `config.php` darf **nicht** unter „Changes to be committed“ erscheinen.

4. **`debug.active`** in der lokalen `config.php` für Produktion auf `false` setzen (in `config.example.php` bereits `false`).

## Server-Härtung (Produktion)

| Datei / Pfad | Risiko | Empfehlung |
|--------------|--------|------------|
| `config.php` | Enthält alle Secrets | Nicht per HTTP abrufbar (außerhalb Webroot oder per Server-Config sperren) |
| `botid.php` | Gibt `bot_id` im Klartext aus | **Nicht** öffentlich verlinken; ideal per `.htaccess`/nginx blockieren oder löschen |
| `tellogs/` | Logs, Tür-Status, Freigaben | Außerhalb des Webroots oder Zugriff verweigern |
| `bot.php?door=` | Türsteuerung per GET | Nur mit geheimem `door_key`; Key regelmäßig wechseln |

## Öffentliches Repository

Der Quellcode beschreibt **wie** Tür- und Freigabe-Mechanismen funktionieren. Das ist kein Ersatz für den geheimen `door_key` und Token – aber Angreifer können gezielt den Live-Server testen. Deshalb: starke Keys, aktuelle PHP-Version, keine Directory-Listings.

## Schwachstelle melden

Kein formales Bug-Bounty. Bei Sicherheitsproblemen bitte **nicht** öffentlich als GitHub-Issue posten, sondern den Verein direkt kontaktieren: [mail@3werkraum.de](mailto:mail@3werkraum.de).

## Checkliste nach Klon / Fork

```bash
cp config.example.php config.php
# config.php bearbeiten – echte Werte eintragen
# tellogs/ anlegen (siehe README)
php project.php   # Webhook setzen
```

Niemand sollte die `config.php` des Dreiwerkraums mit echten Vereinsdaten in ein öffentliches Repo pushen.
