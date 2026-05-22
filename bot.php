<?php

/**
 * Webhook-Einstiegspunkt für den 3WR Telegram-Bot.
 *
 * Ablauf:
 * 1. Roh-JSON von Telegram einlesen und loggen
 * 2. HTTP-Tür-Anfragen (?door=) verarbeiten (auch ohne Nachricht)
 * 3. Nachricht parsen, Session-Rechte setzen
 * 4. Alle Handler in commands/ ausführen
 */

$config = require __DIR__ . '/config.php';

require_once __DIR__ . '/lib/bootstrap.php';
require_once __DIR__ . '/lib/request.php';
require_once __DIR__ . '/lib/session.php';
require_once __DIR__ . '/lib/helpers.php';
require_once __DIR__ . '/lib/dispatch.php';

// Eingehendes Update protokollieren (tellogs/_bot.log)
$jsonRaw = file_get_contents('php://input') ?: '';
appendBotLog($config, $jsonRaw);

$queryArray = parseQueryArray();

// Tür per GET – unabhängig vom Telegram-Webhook-Body
handleDoorHttpQuery($config, $queryArray);

$parsed = parseIncomingRequest($config, $jsonRaw);
if ($parsed === null) {
    // Kein message-Objekt (z. B. reiner HTTP-Tür-Call) → fertig
    exit;
}

$ctx = $parsed;
$ctx['query_array'] = $queryArray;

// private_session / super_private_session anhand Chat-ID und Freigabe-Datei
applySessionFlags($ctx);

if (($queryArray['Debug'] ?? '') === 'true') {
    debugLog($queryArray, $ctx['debug']);
}

// Deutsche Wochentage in /poll-Umfragen
setlocale(LC_TIME, 'de_DE', 'de_DE.UTF-8');

runCommandHandlers($ctx);
