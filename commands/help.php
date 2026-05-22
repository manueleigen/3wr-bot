<?php

/**
 * Kurzanleitung Privat-Freigabe: /help, /hilfe, /???
 */

$cmd = $ctx['command_arr'][0] ?? '';

if (
    !in_array($cmd, ['/help', '/hilfe', '/???'], true)
    || !$ctx['private_session']
) {
    return;
}

$botUser = $ctx['config']['bot_username'];
$msg = "Man kann mir auch privat schreiben (und dort die Tür auf und zu machen). So gehts:
1. Chat öffnen:
 @{$botUser}
2. /freigabe schreiben 
3. Auf Freigabe warten und los gehts!";

sendMessage($ctx['bot_id'], $ctx['chat_id'], $msg, false);
