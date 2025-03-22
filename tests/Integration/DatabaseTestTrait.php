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
            $lines = explode("\n", $envContent);
            foreach ($lines as $line) {
                if (empty($line) || str_starts_with($line, '#')) {
                    continue;
                }
                [$key, $value] = explode('=', $line, 2);
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
        $rootDsn = 'mysql:host=' . getenv('ACTIO_MYSQL_HOST');
        $connection = new PDO($rootDsn, getenv('ACTIO_MYSQL_USERNAME'), getenv('ACTIO_MYSQL_PASSWORD'));
        $dbName = getenv('ACTIO_MYSQL_DB_NAME');
        $connection->exec("CREATE DATABASE IF NOT EXISTS {$dbName}");
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();
    }
}
