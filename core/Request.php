<?php

namespace Core;

use Core\Route;

class Request
{
    /**
     * Load Route Files
     */
    public function handle()
    {
        include App::ROUTES_DIR . 'routes.php';
        $this->loadRoutes();
    }

    /**
     * Load Route Objects
     */
    private function loadRoutes()
    {
        $routes = Route::getRoutes();
        if(!count($routes)) {
            return $this->noRoutesFound();   
        }

        $dispatcher = \FastRoute\cachedDispatcher(function(\FastRoute\RouteCollector $r) use ($routes) {
            foreach($routes as $route) {
                $r->addRoute($route['httpMethod'], $route['route'], $route['handler']);
            }
        }, [
            'cacheFile' => App::FAST_ROUTE_CACHE_DIR, /* required */
            'cacheDisabled' => env('ROUTE_CACHE_ENABLED', false) === false, /* optional, enabled by default */
        ]);
        unset($routes);
        
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];
        
        // Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);
        
        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
        $this->parseRequest($routeInfo);
    }

    /**
     * Run the handler for FastRoute dispatch result
     */
    public function parseRequest(array $routeInfo)
    {
        switch ($routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                (new View())->setResponseStatus(404)->render('errors/404');
                break;
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                (new View())->setResponseStatus(405)->render('errors/405', $allowedMethods);
                break;
            case \FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                
                if($handler instanceof \Closure) {
                    return $handler($vars);
                }

                $handler = explode('@', $handler);
                if(count($handler) !== 2) {
                    return (new View())->setResponseStatus(500)->render('errors/500', ['msg' => 'Invalid Route']);
                }

                [$controller, $method] = $handler;
                $className = 'App\\Controllers\\' . trim(str_replace('/', '\\', $controller), '\\');

                if(!class_exists($className)) {
                    return (new View())->setResponseStatus(500)->render('errors/500', ['msg' => 'Controller not found']);
                }

                $obj = new $className();
                if(!is_callable([$obj, $method])) {
                    return (new View())->setResponseStatus(500)->render('errors/500', ['msg' => 'Method not found']);
                }

                // if has layout
                if(defined("{$className}::LAYOUT")) {
                    View::layout($obj::LAYOUT);
                }
                $obj->$method(...$vars);
            break;
        }
    }

    private function noRoutesFound()
    {
        return (new View())->setResponseStatus(500)->render('errors/500', ['msg' => 'No routes found']);
    }

    public function getParams()
    {
        return $_GET;
    }

    public function postParams()
    {
        return $_POST;
    }

    public function requestParams()
    {
        return $_REQUEST;
    }

    public function appEnd()
    {
        if(env('DEBUG_MODE', false) === true) {
            define('FAST_MVC_END', microtime(true));

            $execution_time = (FAST_MVC_END - FAST_MVC_START);
            (new View())->view('debug/bar', [
                'execution_time' => " It takes ".$execution_time." seconds to execute the script",
                'mem_usage' => 'Peak memory usage: ' . memory_get_peak_usage() / 1024 / 1024 . ' MBs',
            ]);
        }
    }
}