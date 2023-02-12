<?php

/**
 * @param $type - null for all or specify
 */
function clearCache($type = null)
{
    $type = $type == null ? '**' : $type;
    $files = glob(__DIR__ . "/../../cache/{$type}/*");
    foreach($files as $file){ // iterate files
        if(is_file($file)) {
            unlink($file);
        }
    }
}