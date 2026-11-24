<?php

namespace Tests;

use Core\Request;
use FastRoute\Dispatcher;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/Fixtures/Shop.php';

class RoutingTest extends TestCase
{
    public function testItCallsControllerMethod(): void
    {
        $this->assertSame('shop index', $this->dispatch([Dispatcher::FOUND, 'Fixtures\Shop@index', []]));
    }

    public function testItAcceptsSlashesInControllerPath(): void
    {
        $this->assertSame('shop index', $this->dispatch([Dispatcher::FOUND, 'Fixtures/Shop@index', []]));
    }

    public function testItPassesRouteParamsByName(): void
    {
        $output = $this->dispatch([Dispatcher::FOUND, 'Fixtures\Shop@show', ['id' => '5', 'slug' => 'phone']]);

        $this->assertSame('product phone #5', $output);
    }

    public function testItCallsClosureHandler(): void
    {
        $handler = function (array $vars): void {
            echo 'hello ' . $vars['name'];
        };

        $this->assertSame('hello world', $this->dispatch([Dispatcher::FOUND, $handler, ['name' => 'world']]));
    }

    public function testItShowsErrorForMissingController(): void
    {
        $output = $this->dispatch([Dispatcher::FOUND, 'Fixtures\Missing@index', []]);

        $this->assertStringContainsString('Controller not found', $output);
    }

    public function testItShowsErrorForMissingMethod(): void
    {
        $output = $this->dispatch([Dispatcher::FOUND, 'Fixtures\Shop@missing', []]);

        $this->assertStringContainsString('Method not found', $output);
    }

    public function testItShowsErrorForHandlerWithoutMethod(): void
    {
        $output = $this->dispatch([Dispatcher::FOUND, 'Fixtures\Shop', []]);

        $this->assertStringContainsString('Invalid Route', $output);
    }

    public function testItShowsNotFoundPage(): void
    {
        $this->assertStringContainsString('404', $this->dispatch([Dispatcher::NOT_FOUND]));
    }

    public function testItShowsMethodNotAllowedPage(): void
    {
        $this->assertStringContainsString('405', $this->dispatch([Dispatcher::METHOD_NOT_ALLOWED, ['GET']]));
    }

    private function dispatch(array $routeInfo): string
    {
        ob_start();
        (new Request())->parseRequest($routeInfo);

        return (string) ob_get_clean();
    }
}
