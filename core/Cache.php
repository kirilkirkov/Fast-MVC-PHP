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
        if(!is_dir(App::CACHE_DIR)) {
            mkdir(App::CACHE_DIR, 0770, true);
        }

        $file = fopen(App::CACHE_DIR . $key, "w");
        fwrite($file, '<?php '.PHP_EOL.'return '.var_export($array, true).';');
        fclose($file);
    }
}