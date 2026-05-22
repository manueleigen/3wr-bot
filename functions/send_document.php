<?php

function sendDocument($bot_id, $chat_id, $document_path, $caption = '')
{
$ch = curl_init("https://api.telegram.org/bot" . $bot_id . "/sendDocument");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$document = new CURLFile($document_path); // Use CURLFile to send a file

$param = array(
"chat_id" => $chat_id,
"document" => $document,
"caption" => $caption
);

curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $param);

$result = curl_exec($ch);
curl_close($ch);

return $result;
}


?>
