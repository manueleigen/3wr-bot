<?php

/**
 * Verarbeitung eingehender Telegram-Webhook-Updates.
 */

/**
 * Dekodiert Telegram-JSON und baut den Kontext für Command-Handler.
 *
 * @return array|null null wenn keine message im Update (z. B. nur Callback)
 */
function parseIncomingRequest(array $config, string $jsonRaw): ?array
{
    $jsonOut = json_decode($jsonRaw);
    if (!$jsonOut || !isset($jsonOut->message)) {
        return null;
    }

    $message = $jsonOut->message;
    $botUsername = $config['bot_username'];

    // Befehl normalisieren: klein, ohne @Botname
    $commandText = $message->text ?? '';
    $commandText = strtolower($commandText);
    $commandText = str_replace('@' . $botUsername, '', $commandText);
    $commandArr = explode(' ', trim($commandText));

    $url = $_SERVER['REQUEST_URI'] ?? '';
    $queryStr = parse_url($url, PHP_URL_QUERY);
    $queryArray = [];
    if ($queryStr) {
        parse_str($queryStr, $queryArray);
    }

    $chatType = $message->chat->type ?? 'private';
    $personName = $message->from->first_name ?? '';
    $chatName = $personName;
    $chatIsGroup = false;
    $chatIsPrivate = true;

    if ($chatType === 'group' || $chatType === 'supergroup') {
        $chatIsGroup = true;
        $chatIsPrivate = false;
        $chatName = $message->chat->title ?? $chatName;
    }

    return [
        'config' => $config,
        'json_out' => $jsonOut,
        'command_arr' => $commandArr,
        'query_array' => $queryArray,
        'bot_id' => $config['bot_id'],
        'chat_id' => (string) $message->chat->id,
        'person_name' => $personName,
        'person_id' => (string) ($message->from->id ?? ''),
        'chat_type' => $chatType,
        'chat_is_group' => $chatIsGroup,
        'chat_is_private' => $chatIsPrivate,
        'chat_name' => $chatName,
        'chat_name_normalized' => preg_replace('/[^A-Za-z0-9 ]/', '', $chatName),
    ];
}

/** Schreibt Roh-Webhook in tellogs/_bot.log (Debugging / Nachvollziehbarkeit). */
function appendBotLog(array $config, string $jsonRaw): void
{
    $logFile = $config['log_dir'] . '/_bot.log';
    $handle = fopen($logFile, 'a+');
    if ($handle) {
        fwrite($handle, $jsonRaw);
        fclose($handle);
    }
}
