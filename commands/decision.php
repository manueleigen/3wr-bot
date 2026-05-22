<?php

/**
 * Zufallsentscheidung: /?, /entscheidung, /what (öffentlich in allen Chats).
 */

$cmd = $ctx['command_arr'][0] ?? '';
$decisionCommands = ['/?', '/entscheidung', '/what', '/what?'];

if (!in_array($cmd, $decisionCommands, true)) {
    return;
}

$desNum = rand(0, 10);
$des = $desNum > 4 ? 'Ja!' : 'Nein...';
sendMessage($ctx['bot_id'], $ctx['chat_id'], $des, false);
