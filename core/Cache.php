<?php

namespace Core;

class Cache
{
    /**
     * Get cached array, or false when the cache is missing or disabled
     */
    public static function get(string $key, string $path = App::CUSTOM_CACHE_DIR): array|false
    {
        if(self::isCacheDisabled($path) || !is_file($path . $key)) {
            return false;
        }

        return include $path . $key;
    }

    /**
     * Store array as php file (it's loaded through OPcache on next include)
     */
    public static function set(string $key, mixed $array, string $path = App::CUSTOM_CACHE_DIR): bool
    {
        if(self::isCacheDisabled($path) || !is_array($array)) {
            return false;
        }

        // write to temp file and rename it, so a parallel request never includes half written file
        $file = $path . $key;
        $tmpFile = $file . '.' . uniqid('', true) . '.tmp';
        file_put_contents($tmpFile, '<?php '.PHP_EOL.'return '.var_export($array, true).';');

        if(!rename($tmpFile, $file)) {
            return false;
        }

        // otherwise OPcache may keep serving the old version until it revalidates the file
        if(function_exists('opcache_invalidate')) {
            opcache_invalidate($file, true);
        }

        return true;
    }

    private static function isCacheDisabled(string $path): bool
    {
        if($path === App::CUSTOM_CACHE_DIR && env('CUSTOM_CACHE_ENABLED', false) === false
        || $path === App::PDO_CACHE_DIR && env('PDO_CACHE_ENABLED', false) === false) {
            return true;
        }
        return false;
    }
}
