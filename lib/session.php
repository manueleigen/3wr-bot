<?php

/**
 * Session-Rechte: Wer darf Vereinsinfos und Tür-Befehle nutzen?
 */

/** Absoluter Pfad zu einer Datei im tellogs-Verzeichnis. */
function logPath(array $config, string $filename): string
{
    return $config['log_dir'] . '/' . $filename;
}

/**
 * Erweitert $ctx um Zugriffsflags:
 *
 * - private_session: Vereinsgruppen, Admin oder Eintrag in _privateApproved.log
 * - super_private_session: nur Vereinsgruppe + Admin (z. B. /insta, Freigaben)
 */
function applySessionFlags(array &$ctx): void
{
    $config = $ctx['config'];
    $chatId = $ctx['chat_id'];

    $ctx['chat_id_admin'] = $config['chat_id_admin'];
    $ctx['chat_id_ev'] = $config['chat_id_ev'];
    $ctx['chat_id_all'] = $config['chat_id_all'];

    // Fest freigeschaltete Gruppen/Chats
    $ctx['private_session'] = in_array($chatId, [
        $config['chat_id_ev'],
        $config['chat_id_all'],
        $config['chat_id_admin'],
    ], true);

    // Zusätzlich: manuell freigegebene Privat-Chats (_privateApproved.log, Format name:chat_id)
    $approvedFile = logPath($config, '_privateApproved.log');
    if (is_readable($approvedFile)) {
        $lines = array_unique(file($approvedFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: []);
        foreach ($lines as $line) {
            $parts = explode(':', $line);
            if (isset($parts[1]) && intval($parts[1]) === intval($chatId)) {
                $ctx['private_session'] = true;
                break;
            }
        }
    }

    $ctx['super_private_session'] = in_array($chatId, [
        $config['chat_id_ev'],
        $config['chat_id_admin'],
    ], true);

    $ctx['debug'] = array_merge($config['debug'], [
        'chat_id' => $config['chat_id_admin'],
        'bot_id' => $config['bot_id'],
    ]);
}
