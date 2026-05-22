<?php

/**
 * Türsteuerung per HTTP (ohne Telegram-Nachricht).
 * Aufruf: bot.php?door=<door_key>&set=Status%201
 * Wird von lib/dispatch.php separat geladen (nicht über den Command-Glob).
 */

if (empty($ctx['query_array']['door'])) {
    return;
}

$config = $ctx['config'];
$doorKey = $config['door_key'];
$baseUrl = rtrim($config['public_base_url'] ?? '', '/');

if (($ctx['query_array']['door'] ?? '') !== $doorKey) {
    $msg = "Door was triggered, forgot Link?\n{$baseUrl}/bot.php/?door=" . $doorKey;
    sendMessage($ctx['bot_id'], $config['chat_id_admin'], $msg, false);
    return;
}

$file = logPath($config, '_door.log');

if (!empty($ctx['query_array']['set'])) {
    writeFileHandler($file, $ctx['query_array']['set'], 'w');
    print_r(readFileHandler($file));
} else {
    print_r(readFileHandler($file));
    writeFileHandler($file, '', 'w');
}
