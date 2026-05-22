<?php

/**
 * Einfaches Lesen/Schreiben von Status-Dateien (Tür, Freigaben).
 * $writeMode: z. B. "w" (überschreiben) oder FILE_APPEND-kompatibel über file_put_contents in Commands.
 */

function writeFileHandler($file, $data, $writeMode)
{
    $handle = fopen($file,$writeMode);
    fwrite($handle, $data);
    fclose($handle);
}

function readFileHandler($file)
{
    $handle = fopen($file, "r") or die("Unable to open file!");
    $data = fread($handle,filesize($file));
    fclose($handle);
    return $data;
}

