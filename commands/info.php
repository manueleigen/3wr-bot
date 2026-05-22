<?php

/**
 * Vereinsinfos: /wlan, /mail, /konto, /adresse, /satzung, /website, /insta
 * Texte und Zugangsdaten kommen aus config.php → content[]
 */

if (!$ctx['private_session']) {
    return;
}

$cmd = $ctx['command_arr'][0] ?? '';
$botId = $ctx['bot_id'];
$chatId = $ctx['chat_id'];
$config = $ctx['config'];
$content = $config['content'] ?? [];

if ($cmd === '/wlan' && isset($content['wlan'])) {
    sendMessage($botId, $chatId, $content['wlan'], false);
}

if ($cmd === '/mail' && isset($content['mail'])) {
    sendMessage($botId, $chatId, $content['mail'], false);
}

if ($cmd === '/satzung' && isset($content['satzung_file'])) {
    sendDocument($botId, $chatId, logPath($config, $content['satzung_file']), '');
}

if ($cmd === '/website' && isset($content['website'])) {
    sendMessage($botId, $chatId, $content['website'], true);
}

if ($cmd === '/konto' && isset($content['konto'])) {
    sendMessage($botId, $chatId, $content['konto'], false);
}

if ($cmd === '/adresse' && isset($content['adresse'])) {
    sendMessage($botId, $chatId, $content['adresse'], false);
}

if ($cmd === '/insta' && $ctx['super_private_session']) {
    if (!empty($content['insta_login'])) {
        sendMessage($botId, $chatId, $content['insta_login'], false);
    }
    if (!empty($content['insta_password'])) {
        sendMessage($botId, $chatId, $content['insta_password'], false);
    }
}
