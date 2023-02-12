<?php

namespace Core;

/**
 * Static class to register all /routes
 */
class Route
{
    // Registered routes
    private static array $routes = [];

    /**
     * Adds a route to the collection.
     *
     * The syntax used in the $route string depends on the used route parser.
     *
     * @param string|string[] $httpMethod
     * @param string $route
     * @param mixed  $handler
     */
    public static function add($httpMethod, $route, $handler)
    {
        self::$routes[] = [
            'httpMethod' => $httpMethod,
            'route' => $route,
            'handler' => $handler,
        ];
    }

    /**
     * Return all registered routes
     */
    public static function getRoutes()
    {
        return self::$routes;
    }
}