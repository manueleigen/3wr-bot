<?php

/**
 * Sendet eine Telegram-Umfrage (sendPoll).
 * Bei mehr als 10 Optionen ruft poll.php mehrere Polls nacheinander auf.
 */
function sendPolll($bot_id, $chat_id, $question, $options, $chat_id_me)
{
    $ch = curl_init('https://api.telegram.org/bot' . $bot_id . '/sendPoll');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $param = [
        'id' => rand(111111111, 999999999),
        'chat_id' => $chat_id,
        'question' => $question,
        'options' => json_encode($options),
        'is_anonymous' => false,
        'allows_multiple_answers' => true,
    ];

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($param));

    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}
