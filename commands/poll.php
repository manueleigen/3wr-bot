<?php

/**
 * Datums-Umfragen: /poll, /umfrage, /plenum
 * Syntax z. B.: /poll Titel 0-7 18:00 20:00 (Telegram-Limit: max. 10 Optionen pro Poll)
 */

$cmd = $ctx['command_arr'][0] ?? '';

if (!in_array($cmd, ['/poll', '/umfrage', '/plenum'], true)) {
    return;
}

date_default_timezone_set('Europe/Berlin');

$today = date_create(date('d.m.Y'));
$offset = $cmd === '/plenum' ? 1 : 0;

if ($cmd === '/plenum') {
    $question = 'Plenum';
} else {
    $question = $ctx['command_arr'][1 - $offset] ?? 'Umfrage';
}

if (!empty($ctx['command_arr'][2 - $offset])) {
    $fromTo = explode('-', $ctx['command_arr'][2 - $offset]);
    $startTime = (int) $fromTo[0];
    $endTime = (int) ($fromTo[1] ?? $fromTo[0]);
} else {
    $startTime = 0;
    $endTime = 7;
}

if (!empty($ctx['command_arr'][3 - $offset])) {
    $unit = $ctx['command_arr'][3 - $offset];
    if ($unit === 'wochen') {
        $startTime *= 7;
        $endTime *= 7;
    }
}

$dateTimes = [];
if (count($ctx['command_arr']) > (4 - $offset)) {
    for ($h = (4 - $offset); $h < count($ctx['command_arr']); $h++) {
        $dateTimes[] = $ctx['command_arr'][$h];
    }
}

$options = [];

if ($startTime !== 0) {
    date_add($today, date_interval_create_from_date_string($startTime . ' days'));
}

$totalDays = $endTime - $startTime;
for ($i = 0; $i < $totalDays; $i++) {
    $dateOut = date_format($today, 'l') . ', ' . date_format($today, 'd.m.');

    if (count($dateTimes) > 0) {
        foreach ($dateTimes as $time) {
            $options[] = $dateOut . ' ' . $time;
        }
    } else {
        $options[] = $dateOut;
    }

    date_add($today, date_interval_create_from_date_string('1 days'));
}

for ($i = 0; $i < count($options); $i++) {
    if ($i !== 0 && ($i === count($options) - 1 || ($i % 10 === 0))) {
        $range = intval((($i - 1) / 10)) * 10;
        $currentOptions = array_slice($options, $range, 10);
        sendPolll(
            $ctx['bot_id'],
            $ctx['chat_id'],
            $question,
            $currentOptions,
            $ctx['config']['chat_id_admin']
        );
    }
}
