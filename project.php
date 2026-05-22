<?php

/**
 * Registriert den Webhook bei Telegram (einmalig nach Deploy ausführen).
 * URL kommt aus config.php → webhook_url
 */

$config = require __DIR__ . '/config.php';
$botId = $config['bot_id'];

$ch = curl_init('https://api.telegram.org/bot' . $botId . '/setWebhook');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$param = [
    'url' => $config['webhook_url'],
];

curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($param));

$result = curl_exec($ch);
curl_close($ch);

echo $result . "\n<hr />OK";
