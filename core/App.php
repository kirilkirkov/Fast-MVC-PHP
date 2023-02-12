<?php

namespace Core;

/**
 * App configurations
 */
class App
{
    public const ROUTES_DIR = __DIR__ . '/../routes/';
    public const CONTROLLERS_DIR = __DIR__ . '/../app/Controllers/';
    public const VIEWS_DIR = __DIR__ . '/../resources/views/';

    // cache
    public const PDO_CACHE_DIR = __DIR__ . '/../cache/pdo/';
    public const FAST_ROUTE_CACHE_DIR = __DIR__ . '/../cache/fast-route/route.cache';
    public const CUSTOM_CACHE_DIR = __DIR__ . '/../cache/custom/';

    public const _LAYOUT_CONTENT_ = '_LAYOUT_CONTENT_';
    public const ENV_FILE = __DIR__ . '/../env.php';
}