<?php
use Core\App;

// Helpers
if (!function_exists('env')) {
function env(string $key, $default = '')
    {
        $env = include App::ENV_FILE;
        
        if(isset($env[$key])) {
            return $env[$key];
        }

        return $default;
    }
}

if (!function_exists('dd')) {
    function dd() {
        foreach (func_get_args() as $arg) {
            var_dump($arg);
        }
        exit();
    }
}