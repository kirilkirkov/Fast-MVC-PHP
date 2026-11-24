<?php

namespace Core;

/**
 * Global handler for uncaught exceptions: logs them and shows the 500 page
 */
class ErrorHandler
{
    public static function register(): void
    {
        set_exception_handler([self::class, 'handle']);
    }

    public static function handle(\Throwable $exception): void
    {
        // full exception with stack trace goes to the server error log
        error_log((string) $exception);

        // drop partially rendered output (e.g. half rendered layout)
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        self::render($exception, env('DEBUG_MODE', false) === true);
    }

    /**
     * Render the 500 page. Exception details are shown only in debug mode.
     */
    public static function render(\Throwable $exception, bool $debug): void
    {
        $msg = $debug
            ? get_class($exception) . ': ' . $exception->getMessage() . ' in ' . $exception->getFile() . ':' . $exception->getLine()
            : 'Server Error';

        if (!headers_sent()) {
            http_response_code(500);
        }

        try {
            // without layout, because the exception may come from the layout itself
            (new View())->view('errors/500', ['msg' => $msg]);
        } catch (\Throwable) {
            echo '500 Server Error';
        }
    }
}
