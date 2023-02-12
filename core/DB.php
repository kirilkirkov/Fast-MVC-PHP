<?php

namespace Core;

use Core\QueryBuilder\QueryBuilder;

/**
 * Database Configuration
 * 
 * Singleton Implementation
 */
class DB
{
    /**
     * The Singleton's instance is stored in a static field. This field is an
     * array, because we'll allow our Singleton to have subclasses. Each item in
     * this array will be an instance of a specific Singleton's subclass. You'll
     * see how this works in a moment.
     */
    private static $instance = null;

    private $conn;

    /**
     * The Singleton's constructor should always be private to prevent direct
     * construction calls with the `new` operator.
     */
    protected function __construct()
    {
        $dbDriver = env('DB_DRIVER');
        $serverName = env('DB_HOST');
        $dbCharset = env('DB_CHARSET', 'utf8');
        $dbName = env('DB_NAME');
        $dbUser = env('DB_USER');
        $dbPass = env('DB_PASS');

        try {
            $this->conn = new \PDO("{$dbDriver}:host={$serverName};dbname={$dbName}", $dbUser, $dbPass);
            // set the PDO error mode to exception
            $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("SET CHARACTER SET '" . $dbCharset . "'");
        } catch(\PDOException $e) {
            if(env('DEBUG_MODE', false) === true) {
                echo "Connection failed: " . $e->getMessage();
            }
        }
    }

    /**
     * Get Database Instance
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * Get query builder
     */
    public function query()
    {
        return new QueryBuilder();
    }

    /**
     * Execute prepare statement
     */
    public function execute($query, array $params, $type = 'all')
    {
        $q = $this->conn->prepare($query);

        $qKey = sha1($this->getRawQuery($params, $q->queryString));
        if(Cache::get($qKey, App::PDO_CACHE_DIR)) {
            return Cache::get($qKey);
        }

        $q->execute($params);
        
        if($type === 'all') {
            $result = $q->fetchAll(\PDO::FETCH_ASSOC);
            Cache::set($qKey, $result);
            return $result;
        }
        $result =  $q->fetch(\PDO::FETCH_ASSOC);
        Cache::set($qKey, $result, App::PDO_CACHE_DIR);
        return $result;
    }

    /**
     * Execute prepare statement with fetch
     */
    public function first($query, array $params)
    {
        return $this->execute($query, $params, 'first');
    }

    /**
     * Execute prepare statement with fetchAll
     */
    public function get($query, array $params)
    {
        return $this->execute($query, $params, 'all');
    }

    private function getRawQuery(array $params, string $queryString)
    {
        foreach ($params as $k => $v) {
            $params[':'.$k] = $v;
            unset($params[$k]);
        }
        return str_replace(array_keys($params), array_values($params), $queryString);
    }
}