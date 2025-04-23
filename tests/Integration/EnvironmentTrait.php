<?php

namespace Tests\Integration;

use RuntimeException;

trait EnvironmentTrait
{
    public function loadEnvironment(): void
    {
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
            $this->setDefaultEnvironment();
        }
    }

    protected function setDefaultEnvironment(): void
    {
        // MySQL defaults
        putenv('ACTIO_MYSQL_HOST=localhost');
        putenv('ACTIO_MYSQL_DB_NAME=actio_test');
        putenv('ACTIO_MYSQL_USERNAME=test');
        putenv('ACTIO_MYSQL_PASSWORD=password1');
        putenv('ACTIO_MYSQL_TABLE=actio_data_points');

        // PostgreSQL defaults
        putenv('ACTIO_PG_HOST=localhost');
        putenv('ACTIO_PG_DB_NAME=actio_test');
        putenv('ACTIO_PG_USERNAME=test');
        putenv('ACTIO_PG_PASSWORD=password1');
        putenv('ACTIO_PG_PORT=5432');
        putenv('ACTIO_PG_SCHEMA=actio_test');
        putenv('ACTIO_PG_TABLE=actio_data_points');
    }
}
