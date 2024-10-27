<?php declare(strict_types = 1);

namespace App\Booking\DB;

class MyConnect
{
    protected $connection;
    private static $instance = null;

    private function __construct()
    {
        $dbName = getenv('MYSQL_DATABASE');
        $dbUser = getenv('MYSQL_USER');
        $dbPass = getenv('MYSQL_PASSWORD');
        
        $dsn = "mysql:host=mysql;dbname={$dbName}";

        try {
            $this->connection = new \PDO($dsn, $dbUser, $dbPass, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);    
        } catch (\PDOException $e) {
            $errorMsg = 'Database connection failed' . $e->getMessage();
            // Log messages to OS's log files
            error_log($errorMsg, 0);
            // This would normally send an email. Adding only as an example of logging messages.
            error_log($errorMsg, 1, "operator@example.com");
            die('Database connection failed.');
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new MyConnect();
        }

        return self::$instance;
    }

    private function escapedQuery(string $sql, ?array $params = null): \PDOStatement|false
    {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }

    public function escapedInsertQuery(string $sql, array $params): string|false
    {
        $this->escapedQuery($sql, $params);

        return $this->connection->lastInsertId();
    }

    public function escapedUpdateQuery(string $sql, array $params): void
    {
        $this->escapedQuery($sql, $params);
    }

    public function escapedSelectQuery(string $sql, ?array $params = null): \PDOStatement|false
    {
        return $this->escapedQuery($sql, $params);
    }
}