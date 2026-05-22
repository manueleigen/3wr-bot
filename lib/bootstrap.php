<?php

/**
 * Lädt alle Telegram-API-Helfer und Kalender-Funktionen.
 * Reihenfolge: send_* zuerst, da calender.php sendMessage nutzt.
 */

require_once __DIR__ . '/../functions/send_message.php';
require_once __DIR__ . '/../functions/send_photo.php';
require_once __DIR__ . '/../functions/send_video.php';
require_once __DIR__ . '/../functions/send_document.php';
require_once __DIR__ . '/../functions/send_inlinekeyboard.php';
require_once __DIR__ . '/../functions/send_poll.php';
require_once __DIR__ . '/../functions/if-key-of-keys-in-string.php';
require_once __DIR__ . '/../functions/file-handling.php';
require_once __DIR__ . '/../functions/debug-log.php';
require_once __DIR__ . '/../functions/calender.php';
