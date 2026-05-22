<?php

/**
 * Vorlage für config.php – kopieren nach config.php und Werte eintragen.
 * Alle Secrets und Chat-IDs gehören ausschließlich in config.php (nicht in commands/ oder functions/).
 */
return [
    // Telegram API
    'bot_id' => 'TELEGRAM_BOT_TOKEN',
    'bot_username' => 'dein_bot_username',

    // Tür-HTTP-API (?door=…)
    'door_key' => 'GEHEIMER_TUER_SCHLÜSSEL',

    // Chat-IDs
    'chat_id_admin' => 'ADMIN_CHAT_ID',
    'chat_id_ev' => 'VEREINSGRUPPE_CHAT_ID',
    'chat_id_all' => 'ALLE_GRUPPE_CHAT_ID',

    // URLs
    'webhook_url' => 'https://example.de/TelBot/bot.php',
    'public_base_url' => 'https://example.de/TelBot',

    // Bot-Antworten (/wlan, /konto, …)
    'content' => [
        'wlan' => 'WLAN_PASSWORT_ODER_PSK',
        'mail' => "mail@example.de\n",
        'website' => 'https://www.example.de/',
        'konto' => "Kontodaten hier\n",
        'adresse' => "Adresse hier\n",
        'insta_login' => 'instagram_login',
        'insta_password' => 'instagram_passwort',
        'satzung_file' => 'Satzung.pdf',
    ],

    'log_dir' => dirname(__DIR__, 2) . '/tellogs',
    'dates_file' => __DIR__ . '/dates.log',

    'debug' => [
        'active' => false,
    ],
    'env' => 'prod',
];
