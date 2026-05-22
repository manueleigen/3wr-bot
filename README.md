# 3WR Telegram Bot (`TelBot`)

Telegram-Bot für den **Dreiwerkraum e. V.** (@dreiwerkraum_bot): Vereinsinfos, Terminumfragen, Türsteuerung über Log-Dateien und Freigabe-Workflow für private Chats.

## Voraussetzungen

- PHP mit `curl`, auf dem Webserver erreichbar unter `https://3werkraum.de/TelBot/`
- Telegram-Bot-Token (über [@BotFather](https://t.me/BotFather))
- Schreibzugriff auf das Verzeichnis `tellogs/` (eine Ebene über `TelBot/`)

## Projektstruktur

```
TelBot/
├── bot.php              # Webhook-Einstiegspunkt
├── config.php           # IDs, Token, Pfade (lokal anpassen)
├── config.example.php   # Vorlage ohne echte Secrets
├── project.php          # Webhook bei Telegram registrieren
├── botid.php            # Gibt Bot-Token aus (nur intern nutzen)
├── commands/            # Ein Datei pro Befehlsgruppe
├── lib/                 # Request-Parsing, Session, Dispatcher
└── functions/           # Telegram-API-Helfer (sendMessage, Poll, …)
```

Logs und Tür-Status liegen typischerweise in `../../tellogs/` (z. B. `_bot.log`, `_door.log`, `_privateApproved.log`).

## Konfiguration

Alle sensiblen Werte gehören **nur** in `config.php` (steht in `.gitignore`):

| Bereich | Keys |
|---------|------|
| Telegram | `bot_id`, `bot_username` |
| Tür-API | `door_key` |
| Chats | `chat_id_admin`, `chat_id_ev`, `chat_id_all` |
| URLs | `webhook_url`, `public_base_url` |
| Bot-Texte | `content` (wlan, mail, konto, adresse, insta, …) |

## Einrichtung

1. `config.example.php` nach `config.php` kopieren und alle Werte eintragen.
2. `tellogs/` anlegen und beschreibbar machen.
3. Webhook setzen: `project.php` im Browser aufrufen oder per CLI:

   ```bash
   php project.php
   ```

4. Bei Telegram mit dem Bot testen: `/start`

## Befehle (Auszug)

| Befehl | Zugriff | Beschreibung |
|--------|---------|--------------|
| `/start` | Gruppe / freigegeben | Hilfe und Übersicht |
| `/freigabe`, `/private` | alle | Freigabe für Tür & private Infos anfragen |
| `/auf`, `/zu` | freigegeben | Tür-Status in `_door.log` setzen |
| `/wlan`, `/konto`, `/adresse`, … | freigegeben / Gruppe | Vereinsdaten |
| `/poll`, `/umfrage`, `/plenum` | Gruppe | Datums-Umfragen |
| `/date` … | Gruppe | Terminliste (`dates.log`) |
| `/?`, `/entscheidung` | alle | Zufallsentscheidung |

Admin-Befehle (`/allowPerson`, `/declineGroup`, …) nur in Admin- bzw. Vereins-Chats mit `super_private_session`.

HTTP-Tür-API (Hardware/Script):

```
https://3werkraum.de/TelBot/bot.php?door=<door_key>&set=Status%201
```

## Debug

URL-Parameter `?Debug=true` schickt Request-Daten an den Admin-Chat (wenn `debug.active` in `config.php`).

## Veröffentlichen auf GitHub

Das Repo ist für Open Source vorbereitet. Vor dem ersten Push:

1. **Bot-Token und `door_key` rotieren**, falls sie früher im Klartext im Code standen ([@BotFather](https://t.me/BotFather)).
2. Repo initialisieren und prüfen, dass `config.php` **nicht** mit committed wird:

   ```bash
   git init
   git add -A
   git status    # config.php fehlt in der Liste
   git commit -m "Initial commit: 3WR TelBot"
   git remote add origin git@github.com:DEIN_USER/DEIN_REPO.git
   git push -u origin main
   ```

3. Auf dem Server: `botid.php` nicht öffentlich erreichbar machen, `tellogs/` schützen.

Details, Server-Härtung und Meldeweg: **[SECURITY.md](SECURITY.md)**.

Öffentlich vs. privat: Ein **privates** Repo reicht für Backup/Collaboration; **öffentlich** macht den Code für andere Vereine nutzbar, zeigt aber die Tür-/Freigabe-Architektur im Quelltext.

## Hinweise

- `/stunden` wird in `/start` erwähnt, ist im Code noch nicht implementiert – ggf. nachziehen oder Text anpassen.

## Lizenz

MIT License – siehe [LICENSE](LICENSE). Copyright (c) 2026 Dreiwerkraum e. V.

## Kontakt

Dreiwerkraum e. V., Berlin – [3werkraum.de](https://www.3werkraum.de/)
