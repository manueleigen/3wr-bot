<?php

/**
 * Terminverwaltung über dates.log (JSON): /date add, show, del, …
 */

$cmd = $ctx['command_arr'][0] ?? '';

if ($cmd !== '/date' && strpos($cmd, 'date') === false) {
    return;
}

$file = $ctx['config']['dates_file'];
$commandArr = $ctx['command_arr'];

if (strpos($cmd, 'dates') !== false || strpos($commandArr[1] ?? '', 'show') !== false) {
    showDates($file, 999, $ctx['bot_id'], $ctx['chat_id']);
}
if (strpos($cmd, 'add') !== false || ($commandArr[1] ?? '') === 'add') {
    addDate($file, $commandArr, $ctx['bot_id'], $ctx['chat_id']);
}
if (
    strpos($cmd, 'del') !== false
    || $cmd === 'deldate'
    || ($commandArr[1] ?? '') === 'del'
) {
    deleteDate($file, $commandArr, $ctx['bot_id'], $ctx['chat_id']);
}
if (($commandArr[1] ?? '') === 'file') {
    sendMessage($ctx['bot_id'], $ctx['chat_id'], showFile($file), false);
}
if (($commandArr[1] ?? '') === 'delfile') {
    delFile($file);
    sendMessage($ctx['bot_id'], $ctx['chat_id'], 'File cleared', false);
}
