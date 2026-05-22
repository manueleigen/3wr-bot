<?php

/** Telegram sendMessage – $webPagePreview: true = Link-Vorschau aus */
function sendMessage($bot_id, $chat_id, $message_text, $webPagePreview)
{
 $ch = curl_init("https://api.telegram.org/bot" . $bot_id . "/sendMessage");
 curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
 curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);

 $param = array(
  "chat_id" => $chat_id,
  "text" => $message_text,
   "disable_web_page_preview" =>  $webPagePreview
 );

 curl_setopt($ch, CURLOPT_POST, 1);
 curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($param));

 $result = curl_exec($ch);
 curl_close($ch);

 return $result;
}
?>
