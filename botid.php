<?php

/**
 * Gibt die Bot-ID aus (nur für interne Admin-Skripte – nicht öffentlich verlinken).
 */

$config = require __DIR__ . '/config.php';
echo $config['bot_id'];
