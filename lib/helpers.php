<?php

/**
 * Hilfsfunktionen für Commands (keine Telegram-API-Aufrufe).
 */

/** Zufallspasswort für /pw (15 Zeichen, alphanumerisch + Sonderzeichen). */
function generateRandomString(int $length): string
{
    $alphanum = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $special = '~!@#$%^&*(){}[],./?';
    $characters = $alphanum . $special;
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

/** Prüft, ob chat_id in _privateApproved.log steht (Format name:chat_id). */
function isApprovedForChat(array $ctx): bool
{
    $file = logPath($ctx['config'], '_privateApproved.log');
    if (!is_readable($file)) {
        return false;
    }
    $lines = array_unique(file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: []);
    foreach ($lines as $line) {
        $parts = explode(':', $line);
        if (isset($parts[1]) && intval($parts[1]) === intval($ctx['chat_id'])) {
            return true;
        }
    }
    return false;
}
