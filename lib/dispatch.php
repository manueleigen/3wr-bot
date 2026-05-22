<?php

/**
 * Lädt und führt Command-Handler aus.
 * door_http.php wird separat behandelt (HTTP ohne Telegram-Message).
 */

/** Liest GET-Parameter aus der aktuellen Request-URL. */
function parseQueryArray(): array
{
    $url = $_SERVER['REQUEST_URI'] ?? '';
    $queryStr = parse_url($url, PHP_URL_QUERY);
    $queryArray = [];
    if ($queryStr) {
        parse_str($queryStr, $queryArray);
    }
    return $queryArray;
}

/** Alle PHP-Dateien in commands/ außer door_http (alphabetisch). */
function loadCommandHandlers(): array
{
    $files = glob(__DIR__ . '/../commands/*.php') ?: [];
    $files = array_values(array_filter($files, static function (string $path): bool {
        return basename($path) !== 'door_http.php';
    }));
    sort($files);
    return $files;
}

/**
 * Führt jeden Command-Handler aus.
 * Handler prüfen selbst $ctx['command_arr'] und Session-Flags.
 */
function runCommandHandlers(array $ctx): void
{
    foreach (loadCommandHandlers() as $handler) {
        require $handler;
    }
}

/** Tür-HTTP vor dem Message-Parsing (siehe bot.php). */
function handleDoorHttpQuery(array $config, array $queryArray): void
{
    if (empty($queryArray['door'])) {
        return;
    }

    $ctx = [
        'config' => $config,
        'query_array' => $queryArray,
        'bot_id' => $config['bot_id'],
        'chat_id' => $config['chat_id_admin'],
    ];

    require __DIR__ . '/../commands/door_http.php';
}
