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
        $serverName = env('DB_HOST');
        $dbName = env('DB_NAME');
        $dbUser = env('DB_USER');
        $dbPass = env('DB_PASS');

        try {
            $this->conn = new \PDO("mysql:host={$serverName};dbname={$dbName}", $dbUser, $dbPass);
            // set the PDO error mode to exception
            $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
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

    public function query()
    {
        return new QueryBuilder();
    }

    public function execute($query, array $params, $type = 'all')
    {
        $q = $this->conn->prepare($query);
        $q->execute($params);
        
        if($type === 'all') {
            return $q->fetchAll(\PDO::FETCH_ASSOC);
        }
        return $q->fetch(\PDO::FETCH_ASSOC);
    }

    public function first($query, array $params)
    {
        return $this->execute($query, $params, 'one');
    }

    public function get($query, array $params)
    {
        return $this->execute($query, $params, 'all');
    }
}