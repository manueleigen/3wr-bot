<?php

/**
 * Zufälliges Passwort generieren (/pw).
 */

if (($ctx['command_arr'][0] ?? '') !== '/pw') {
    return;
}

sendMessage($ctx['bot_id'], $ctx['chat_id'], generateRandomString(15), false);
