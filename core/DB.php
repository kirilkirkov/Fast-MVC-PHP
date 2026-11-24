<?php

namespace Core;

use Core\QueryBuilder\QueryBuilder;

/**
 * Database (singleton)
 *
 * Connection is opened lazily on the first real query,
 * so requests served entirely from the PDO cache never connect to the database.
 */
class DB
{
    private static ?self $instance = null;

    private ?\PDO $conn = null;

    /**
     * Use DB::getInstance()
     */
    protected function __construct()
    {
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
     * Execute SELECT prepared statement (cached in cache/pdo when PDO_CACHE_ENABLED)
     */
    public function execute($query, array $params, $type = 'all')
    {
        $query = (string) $query;
        $cacheKey = hash('xxh128', $type . '|' . $query . '|' . serialize($params));

        $cached = Cache::get($cacheKey, App::PDO_CACHE_DIR);
        if($cached !== false) {
            return $cached;
        }

        $statement = $this->connection()->prepare($query);
        $statement->execute($params);

        $result = $type === 'all' ? $statement->fetchAll() : $statement->fetch();
        Cache::set($cacheKey, $result, App::PDO_CACHE_DIR);

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

    /**
     * Execute INSERT / UPDATE / DELETE prepared statement (never cached)
     * Returns number of affected rows
     */
    public function statement($query, array $params = []): int
    {
        $statement = $this->connection()->prepare((string) $query);
        $statement->execute($params);

        return $statement->rowCount();
    }

    public function lastInsertId(): string|false
    {
        return $this->connection()->lastInsertId();
    }

    private function connection(): \PDO
    {
        if($this->conn !== null) {
            return $this->conn;
        }

        $dbDriver = env('DB_DRIVER');
        $dsn = "{$dbDriver}:host=" . env('DB_HOST') . ';dbname=' . env('DB_NAME');

        // charset in DSN instead of extra "SET CHARACTER SET" query on every connect
        if($dbDriver === 'mysql') {
            $dsn .= ';charset=' . env('DB_CHARSET', 'utf8mb4');
        }

        $this->conn = new \PDO($dsn, env('DB_USER'), env('DB_PASS'), [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        ]);

        return $this->conn;
    }
}
