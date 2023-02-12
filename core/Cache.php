<?php

namespace Core;

class Cache
{
    public static function get(string $key, $path = App::CUSTOM_CACHE_DIR)
    {
        if(self::isCacheDisabled($path) || !file_exists($path . $key)) {
            return false;
        }

        return include $path . $key;
    }

    public static function set(string $key, $array, $path = App::CUSTOM_CACHE_DIR)
    {
        if(self::isCacheDisabled($path) || !is_array($array)) {
            return false;
        }

        $file = fopen($path . $key, "w");
        fwrite($file, '<?php '.PHP_EOL.'return '.var_export($array, true).';');
        fclose($file);
    }

    private static function isCacheDisabled(string $path)
    {
        if($path === App::CUSTOM_CACHE_DIR && env('CUSTOM_CACHE_ENABLED', false) === false
        || $path === App::PDO_CACHE_DIR && env('PDO_CACHE_ENABLED', false) === false) {
            return true;
        }
        return false;
    }
}