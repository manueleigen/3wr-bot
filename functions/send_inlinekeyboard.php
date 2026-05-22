<?php
function sendInlineKeyboard($bot_id,$chat_id,$message_text,$replyMarkup)
{
 $encodedMarkup = json_encode(array('inline_keyboard' => $replyMarkup));

 $content = array(
     'chat_id' => $chat_id,
     'reply_markup' => $encodedMarkup,
     'text' => $message_text
 );

 $url = "https://api.telegram.org/bot" . $bot_id . "/sendMessage";

 $ch = curl_init();

 curl_setopt($ch, CURLOPT_URL, $url);
 curl_setopt($ch, CURLOPT_POST, 1);
 curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($content));
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

 $result = curl_exec($ch);
 curl_close($ch);

 return $result;
}
?>
