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

    private static $instance;
    private $iocContainer;

    private function __construct()
    {
        $this->registerServices();
        $this->dispatchKernel(); // should be last one
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Service container
     */
    public function registerServices()
    {
        $container = new \Core\Container();

        $servicesFile = __DIR__.'/../config/services.php';
        if (!file_exists($servicesFile)) {
            return;
        }
        $services = require $servicesFile;

        foreach ($services['bindings'] as $abstract => $concrete) {
            $container->bind($abstract, $concrete);
        }
        
        foreach ($services['singletons'] as $abstract => $concrete) {
            $container->singleton($abstract, $concrete);
        }

        $this->setContainer($container);
    }

    private function setContainer($container)
    {
        $this->iocContainer = $container;
    }

    public function getService($abstract, ...$parameters)
    {
        return $this->iocContainer->make($abstract, $parameters);
    }

    /**
     * Kernel
     */
    public function dispatchKernel()
    {
        $kernel = new \App\Bootstrap\Kernel();
        $kernel->dispatch();
    }
}