<?php
//Calender
function date_sort($a, $b) {
    return strtotime($a["when"]) - strtotime($b["when"]);
}
function showFile($file) {
    $dates = file_get_contents($file);
    return $dates;
}
function getDates($file) {
    $dates = json_decode(file_get_contents($file));
    return $dates;
/*
    $dates = file_get_contents($file);
    //$dates = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "", $dates); //Doppelte Umbrüche
   //$dates = preg_replace('/\s+/', " ", $dates); //Doppelte Leerzeichen

    //Remove multiple linebreaks:
    $dates = preg_replace("/[\r\n]+/", "\n", $dates);

    //Remove empty lines:
    //$dates = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $dates);
    //$dates = preg_replace("\n\n", "\n", $dates);


    $dates = explode(",", $dates);
    $dates = array_unique($dates);



    $datesRefactored = [];
    foreach($dates as $date) {
        if(strlen($date) > 3){
            $date = explode(" ", $date);
            $current = array("when" => "", "what" => "");
            foreach($date as $component) {
                if (strtotime($component) !== false) {
                    $current["when"] = $component;
                }
                elseif($component != " " && $component != "") {
                    $current["what"] = $component;
                }
            }
            $current = array("when" => $date[0], "what" => $date[1]);
            array_push($datesRefactored, $current);
        }

    }
    usort($datesRefactored, "date_sort");
    return $datesRefactored;*/
}

function showDates($file, $dayLimit, $bot_id, $chat_id) {
    //sendMessage($bot_id, $chat_id, "showDates", false);
    $dates = getDates($file) ;
    //sendMessage($bot_id, $chat_id, count($dates), false);
    $now = strtotime("now");
    $dayNum = 86400;
    $response = "";
    foreach($dates as $date) {
        $dateNum = strtotime($date["when"]);
        if ( $dateNum > $now && $dateNum < ($now + ($dayNum * $dayLimit)) ) {
            $response = $response.$date["when"].
                ": ".$date["what"].
                "\n";
        }
    }
    sendMessage($bot_id, $chat_id, $response, false);
}

function updateDates($file) {
    $dates = getDates($file);
    file_put_contents($file, "");
    file_put_contents($file, json_encode($dates) );
    /*$updatedFile = "";
    $now = strtotime("now");
    foreach($dates as $date) {
        $dateNum = strtotime($date["when"]);
        if ($dateNum > $now) {
            $updatedFile = $updatedFile.$date["when"].
                " ".$date["what"].
                ",\n";
        }
    }
    file_put_contents($file, $updatedFile);*/
}

function deleteDate($file, $command_arr, $bot_id, $chat_id){

    $dates = getDates($file);
    $current = "";

    if (strpos($command_arr[0], "deletedate")) {
        file_put_contents($file, "");
        $removeDateIndex = floatval(preg_replace('/[^0-9.]+/', '', $command_arr[0]));
        //sendMessage($bot_id,$chat_id,$removeDateIndex, false);
        unset($dates[$removeDateIndex]);
        foreach($dates as $date) {
            $updatedFile = $updatedFile.$date["when"].
                " ".$date["what"].
                ",\n";
        }
        file_put_contents($file, $updatedFile);
        showDates($file, 999, $bot_id, $chat_id);
    } else {
        $index = 1; //ToDo:Leere zeile im Log fix
        foreach($dates as $date) {
            if (strtotime($date["when"]) !== false) {
                //$current = array(array('text' => "/".$date["when"]." ".$date["what"]." deldate", 'callback_data' => $index));
                //array_push($options, $current);
                $current = $current.$date["when"].
                    "\n".$date["what"].
                    "\n".
                    "/DeleteDate$index  ".
                    "\n".
                    "\n";
                $index++;
            }
        }
        //sendInlineKeyboard($bot_id,$chat_id,"Wähle aus", $options);
        sendMessage($bot_id, $chat_id, $current, false);
    }
}
function addDate($file, $command_arr, $bot_id, $chat_id){
    if (strtotime($command_arr[2]) === false) {
        sendMessage($bot_id, $chat_id, "❌ Bitte ein gültiges Datum hinter dem add Befehl beifügen", false);
    } else {
        if (!$command_arr[3]) {
            sendMessage($bot_id, $chat_id, "❌ Bitte eine Terminbezeichnung hinter das Datum beifügen", false);
        } else {
            sendMessage($bot_id, $chat_id, "✅ Termin hinzugefügt!
					" . $command_arr[2]. ": " . $command_arr[3] , false);
            file_put_contents($file, ",\n".$command_arr[2].
                " ".$command_arr[3], FILE_APPEND | LOCK_EX);
        }
    }
}

