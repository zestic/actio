<?php

namespace Tests\Integration;

use PDO;
use RuntimeException;

trait DatabaseTestTrait
{
    protected static ?PDO $connection = null;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        // Load environment variables from .env.testing if it exists
        if (file_exists(__DIR__ . '/../../.env.testing')) {
            $envFile = __DIR__ . '/../../.env.testing';
            $envContent = file_get_contents($envFile);
            if ($envContent === false) {
                throw new RuntimeException('Failed to read .env.testing file');
            }
            $lines = explode("\n", $envContent);
            foreach ($lines as $line) {
                if (empty($line) || str_starts_with($line, '#')) {
                    continue;
                }
                $parts = explode('=', $line, 2);
                if (count($parts) !== 2) {
                    continue;
                }
                [$key, $value] = $parts;
                putenv(trim($key) . '=' . trim($value, '"\''));
            }
        } else {
            // Set default test environment variables
            putenv('ACTIO_MYSQL_HOST=localhost');
            putenv('ACTIO_MYSQL_DB_NAME=actio_test');
            putenv('ACTIO_MYSQL_USERNAME=test');
            putenv('ACTIO_MYSQL_PASSWORD=password1');
        }

        // Create test database if it doesn't exist
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

        $rootDsn = 'mysql:host=' . $host;
        $connection = new PDO($rootDsn, $username, $password);
        $connection->exec("CREATE DATABASE IF NOT EXISTS {$dbName}");
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();
    }
}
