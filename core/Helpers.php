<?php
use Core\App;

// Helpers
if (!function_exists('env')) {
    /**
     * Read a value from env.php. The file is loaded only once per request.
     */
    function env(string $key, mixed $default = ''): mixed
    {
        static $env = null;
        $env ??= include App::ENV_FILE;

        return $env[$key] ?? $default;
    }
}

if (!function_exists('e')) {
    /**
     * Escape a value for safe HTML output: <?= e($value) ?>
     */
    function e(string|int|float|null $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
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

if (!function_exists('truncateText')) {
    function truncateText(string $text, $length = 200) {
        return mb_strlen($text) > $length ? mb_substr($text, 0, $length)."..." : $text;
    }
}

if (!function_exists('description')) {
    function description(string $text) {
        return truncateText(str_replace("\r", '', str_replace("\n", '', trim(strip_tags($text)))), 200);
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
