<?php

/** Sendet Debug-Infos an den Admin-Chat, wenn $debug['active'] true ist. */
function debugLog($msg, $debug)
{
    if($debug["active"] ){
        sendMessage($debug["bot_id"], $debug["chat_id"], $msg, false);
    }
}