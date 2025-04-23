<?php

namespace Tests\Integration;

use PDO;
use RuntimeException;

trait PostgresDatabaseTrait
{
    use EnvironmentTrait;

    protected static ?PDO $postgresConnection = null;

    public static function setUpPostgresDatabase(): void
    {
        // Create PostgreSQL test database if it doesn't exist
        $host = getenv('ACTIO_PG_HOST');
        if ($host === false) {
            throw new RuntimeException('ACTIO_PG_HOST environment variable is not set');
        }

        $username = getenv('ACTIO_PG_USERNAME');
        if ($username === false) {
            throw new RuntimeException('ACTIO_PG_USERNAME environment variable is not set');
        }

        $password = getenv('ACTIO_PG_PASSWORD');
        if ($password === false) {
            throw new RuntimeException('ACTIO_PG_PASSWORD environment variable is not set');
        }

        $dbName = getenv('ACTIO_PG_DB_NAME');
        if ($dbName === false) {
            throw new RuntimeException('ACTIO_PG_DB_NAME environment variable is not set');
        }

        $port = getenv('ACTIO_PG_PORT') ?: '5432';

        $attempts = 3;
        $lastError = null;
        while ($attempts > 0) {
            try {
                $pgRootDsn = "pgsql:host={$host};port={$port};dbname=postgres";
                $pgConnection = new PDO($pgRootDsn, $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_TIMEOUT => 3,
                ]);
                // Try to create database, but don't fail if we can't
                try {
                    $pgConnection->exec("CREATE DATABASE {$dbName}");
                } catch (\PDOException $e) {
                    // Ignore errors, assume database exists
                }
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
            throw new RuntimeException('Failed to connect to PostgreSQL after 3 attempts: ' . $lastError->getMessage(), 0, $lastError);
        } elseif ($attempts === 0) {
            throw new RuntimeException('Failed to connect to PostgreSQL after 3 attempts');
        }
    }
}
