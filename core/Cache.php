<?php

namespace Core;

use Core\Route;

class Cache
{
    public static function get(string $key)
    {
        if(file_exists(App::CACHE_DIR . $key)) {
            return include App::CACHE_DIR . $key;
        }

        return false;
    }

    public static function set(string $key, array $array)
    {
        $file = fopen(App::CACHE_DIR . $key, "w");
        fwrite($file, '<?php '.PHP_EOL.'return '.var_export($array, true).';');
        fclose($file);
    }
}