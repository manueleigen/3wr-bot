<?php

/**
 * Begrüßung und Befehlsübersicht (/start).
 * Ohne private_session nur Kurzhinweis auf /entscheidung.
 */

$cmd = $ctx['command_arr'][0] ?? '';

if ($cmd !== '/start') {
    return;
}

if (!$ctx['private_session']) {
    sendMessage($ctx['bot_id'], $ctx['chat_id'], "\n   /entscheidung?\n  ", false);
    return;
}

$superPrivatePart = '';
if ($ctx['super_private_session']) {
    $superPrivatePart = "\n/insta für den Instagram zugang";
}

sendMessage(
    $ctx['bot_id'],
    $ctx['chat_id'],
    '
Huhu, darf ich mich Vorstellen?! Ich bin 3WR_Bot das erste nichtmenschliche, trotzdem außerordentlich unordentliche Mitglied des Dreiwerkraums!! 🥳 Bisher sind meine Funktionen noch etwas eingeschränkt... Womit ich allerdings JETZT schon helfen kann, ist:

/entscheidung für wichtige Lebens-Entscheidungen (oder /?)' .
    $superPrivatePart . '
/wlan für den Wlan zugang

/adresse für die volle Adresse
/konto für die Kontoverbindung
/mail für die Mail-Adressen
/stunden für die Arbeitsstunden-Tabelle
/satzung für die Vereins-Satzung

Man kann dem Bot auch privat schreiben (und dort die tür auf und zu machen). so gehts:
1. chat öffnen: @' . $ctx['config']['bot_username'] . '
2. /freigabe schreiben 
3. auf freigabe warten!
4. mit /auf und /zu Tür bedienen

  ',
    false
);
