<?php
function ifKeyInString($keys, $string){

    $keys = explode(",",$keys);
    $keyInString = false;
    for ($i = 0; $i < count($keys); $i++) {
        if (strpos($string, $keys[$i])) {
            $keyInString = true;
        }
    }
    return $keyInString;


}