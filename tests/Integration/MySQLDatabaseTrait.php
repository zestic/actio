<?php

namespace Tests\Integration;

use PDO;
use RuntimeException;

trait MySQLDatabaseTrait
{
    use EnvironmentTrait;

    protected static ?PDO $mysqlConnection = null;

    public static function setUpMySQLDatabase(): void
    {
        // Create MySQL test database if it doesn't exist
        $host = getenv('ACTIO_MYSQL_HOST');
        if ($host === false) {
            throw new RuntimeException('ACTIO_MYSQL_HOST environment variable is not set');
        }

        $username = getenv('ACTIO_MYSQL_USERNAME');
        if ($username === false) {
            throw new RuntimeException('ACTIO_MYSQL_USERNAME environment variable is not set');
        }

        $password = getenv('ACTIO_MYSQL_PASSWORD');
        if ($password === false) {
            throw new RuntimeException('ACTIO_MYSQL_PASSWORD environment variable is not set');
        }

        $dbName = getenv('ACTIO_MYSQL_DB_NAME');
        if ($dbName === false) {
            throw new RuntimeException('ACTIO_MYSQL_DB_NAME environment variable is not set');
        }

        $attempts = 3;
        $lastError = null;
        while ($attempts > 0) {
            try {
                $mysqlRootDsn = 'mysql:host=' . $host . ';port=3306;charset=utf8mb4';
                $mysqlConnection = new PDO($mysqlRootDsn, $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_TIMEOUT => 3,
                    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
                ]);
                $mysqlConnection->exec("CREATE DATABASE IF NOT EXISTS {$dbName}");
                break;
            } catch (\PDOException $e) {
                $lastError = $e;
                $attempts--;
                if ($attempts > 0) {
                    sleep(2);
                }
            }
        }
        if ($attempts === 0 && $lastError !== null) {
            throw new RuntimeException('Failed to connect to MySQL after 3 attempts: ' . $lastError->getMessage(), 0, $lastError);
        } elseif ($attempts === 0) {
            throw new RuntimeException('Failed to connect to MySQL after 3 attempts');
        }
    }
}
