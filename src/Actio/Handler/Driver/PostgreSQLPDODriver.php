<?php
declare(strict_types=1);

namespace Actio\Handler\Driver;

use PDO;

class PostgreSQLPDODriver extends PDODriver
{
    protected function createDsn(): string
    {
        $host = getenv('ACTIO_PG_HOST') ?: 'localhost';
        $dbName = getenv('ACTIO_PG_DB_NAME') ?: 'test';
        return "pgsql:host={$host};dbname={$dbName}";
    }

    protected function getUsername(): string
    {
        return getenv('ACTIO_PG_USERNAME') ?: 'test';
    }

    protected function getPassword(): string
    {
        return getenv('ACTIO_PG_PASSWORD') ?: 'password1';
    }

    /**
     * @return array<int, mixed>
     */
    protected function getOptions(): array
    {
        return [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
    }

    public function createTable(): bool|int
    {
        $schema = getenv('ACTIO_PG_SCHEMA') ?: 'actio_test';
        $sql = "CREATE TABLE IF NOT EXISTS {$schema}.actio_data_points (
            id SERIAL PRIMARY KEY,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            level VARCHAR(10) NOT NULL,
            message TEXT NOT NULL,
            context JSONB
        )";

        return $this->db()->exec($sql) !== false;
    }
}
