<?php

namespace Core;

use Core\Route;

class Request
{
    private const ROUTES_DIR = __DIR__ . '/../routes/';
    private const CONTROLLERS_DIR = __DIR__ . '/../app/Controllers/';

    /**
     * Load Route Files
     */
    public function handle()
    {
        if ($handle = opendir(self::ROUTES_DIR)) {
            while (false !== ($entry = readdir($handle))) {
                if(pathinfo($entry, PATHINFO_EXTENSION) !== 'php') {
                    continue;
                }

                include self::ROUTES_DIR . $entry;
            }
            closedir($handle);
        }

        $this->loadRoutes();
    }

    /**
     * Load Route Objects
     */
    private function loadRoutes()
    {
        $routes = Route::getRoutes();
        if(!count($routes)) {
            return;   
        }

        $dispatcher = \FastRoute\simpleDispatcher(function(\FastRoute\RouteCollector $r) use ($routes) {
            foreach($routes as $route) {
                $r->addRoute($route['httpMethod'], $route['route'], $route['handler']);
            }
        });
        
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];
        
        // Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);
        
        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
        switch ($routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                // ... 404 Not Found
                break;
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                // ... 405 Method Not Allowed
                break;
            case \FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];

                $handler = explode('@', $handler);
                $class = self::CONTROLLERS_DIR . trim(str_replace('\\', '/', $handler[0]), '\\') . '.php';
                
                if(file_exists($class)) {
                    include $class;
                    
                    $className = 'App\Controllers\\' . str_replace(self::CONTROLLERS_DIR, '', rtrim($class, '.php'));
                    $className = str_replace('/', '\\', $className);
                    
                    $obj = new $className();
                    if(!is_callable([$obj, $handler[1]])) {
                        // method not found!
                        echo 'method not found';
                    }
                    call_user_func_array([$obj, $handler[1]], $vars);
                } else {
                    // ... Controller Not Found
                    echo 'Controller Not Found';
                }
                break;
        }
    }
}