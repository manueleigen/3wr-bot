<?php

/**
 * Tür per Telegram: /auf, /zu, /door open|close
 * Schreibt Status 1/0 nach tellogs/_door.log (nur bei private_session).
 */

$cmd = $ctx['command_arr'][0] ?? '';
$doorCommands = ['/door', '/auf', '/zu', '/türauf', '/türzu', '/dooropen', '/doorclose'];

if (!in_array($cmd, $doorCommands, true) || !$ctx['private_session']) {
    return;
}

$file = logPath($ctx['config'], '_door.log');
$writeMode = 'w';
$arg1 = $ctx['command_arr'][1] ?? '';

$open = $arg1 === 'open'
    || in_array($cmd, ['/türauf', '/dooropen', '/auf'], true);

$close = $arg1 === 'close'
    || in_array($cmd, ['/türzu', '/doorclose', '/zu'], true);

if ($open) {
    writeFileHandler($file, 'Status 1', $writeMode);
    readFileHandler($file);
    sendMessage($ctx['bot_id'], $ctx['chat_id'], 'Moin! :)', false);
}

if ($close) {
    writeFileHandler($file, 'Status 0', $writeMode);
    readFileHandler($file);
    sendMessage($ctx['bot_id'], $ctx['chat_id'], 'Ciao! :)', false);
}
