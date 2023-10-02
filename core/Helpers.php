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
        echo '<div style="background-color:#333; padding:10px; color:yellow;">';
        foreach (func_get_args() as $arg) {
            echo '<div><pre>'; 
            var_dump($arg);
            echo '</pre></div>';
        }
        echo '</div>';
        exit();
    }
}

if (!function_exists('app')) {
    function app($method = null) {
        $app = \Core\App::getInstance();

        if ($method === null) {
            return $app;
        }

        return $app->$method();
    }
}
