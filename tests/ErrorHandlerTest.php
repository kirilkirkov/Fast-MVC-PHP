<?php

namespace Tests;

use Core\ErrorHandler;
use PHPUnit\Framework\TestCase;

class ErrorHandlerTest extends TestCase
{
    public function testItHidesExceptionDetailsWhenDebugIsOff(): void
    {
        $output = $this->render(new \RuntimeException('Secret DB password'), false);

        $this->assertStringContainsString('500 Server Error', $output);
        $this->assertStringNotContainsString('Secret DB password', $output);
    }

    public function testItShowsEscapedExceptionDetailsWhenDebugIsOn(): void
    {
        $output = $this->render(new \RuntimeException('<b>Boom</b>'), true);

        $this->assertStringContainsString('RuntimeException: &lt;b&gt;Boom&lt;/b&gt;', $output);
    }

    private function render(\Throwable $exception, bool $debug): string
    {
        ob_start();
        ErrorHandler::render($exception, $debug);

        return (string) ob_get_clean();
    }
}
