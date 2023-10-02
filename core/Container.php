<?php

namespace Core;

/**
 * Register of DiC/IoC container
 */
class Container
{
    protected $bindings = [];
    protected $singletons = [];

    protected $instances = [];

    public function bind($abstract, $concrete)
    {
        $this->bindings[$abstract] = $concrete;
    }

    public function singleton($abstract, $concrete)
    {
        $this->singletons[$abstract] = $concrete;
    }
    
    public function make($abstract, $parameters = [])
    {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }
        
        if (isset($this->bindings[$abstract])) {
            $concrete = $this->bindings[$abstract];
        } elseif (isset($this->singletons[$abstract])) {
            $concrete = $this->singletons[$abstract];
        } else {
            throw new \Exception("Can't find {$abstract} in container");
        }
        
        if ($concrete instanceof \Closure) {
            $object = $concrete($this, $parameters);
        } else {
            $object = new $concrete(...$parameters);
        }
        
        if (isset($this->singletons[$abstract])) {
            $this->instances[$abstract] = $object;
        }
        
        return $object;
    }
}
