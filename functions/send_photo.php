<?php
function sendPhoto($bot_id,$chat_id,$photo_url)
{
 $ch = curl_init("https://api.telegram.org/bot" . $bot_id . "/sendPhoto");
 curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
 curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);

 $param = array(
  "chat_id" => $chat_id,
  "photo" => $photo_url
 );

 curl_setopt($ch, CURLOPT_POST, 1);
 curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($param));

 $result = curl_exec($ch);
 curl_close($ch);

 return $result;
}
?>
