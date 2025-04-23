<?php
declare(strict_types=1);

namespace Actio\Handler\Driver;

use PDO;

class PostgresPDODriver extends PDODriver implements PDODriverInterface
{
    private string $schema;

    protected function createDsn(): string
    {
        $host = getenv('ACTIO_PG_HOST');
        if (!$host) {
            throw new \RuntimeException('ACTIO_PG_HOST environment variable is not set');
        }

        $dbname = getenv('ACTIO_PG_DB_NAME');
        if (!$dbname) {
            throw new \RuntimeException('ACTIO_PG_DB_NAME environment variable is not set');
        }

        $port = getenv('ACTIO_PG_PORT') ?: '5432';
        return "pgsql:host={$host};port={$port};dbname={$dbname}";
    }

    protected function getUsername(): string
    {
        $username = getenv('ACTIO_PG_USERNAME');
        if (!$username) {
            throw new \RuntimeException('ACTIO_PG_USERNAME environment variable is not set');
        }
        return $username;
    }

    protected function getPassword(): string
    {
        $password = getenv('ACTIO_PG_PASSWORD');
        if (!$password) {
            throw new \RuntimeException('ACTIO_PG_PASSWORD environment variable is not set');
        }
        return $password;
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

    public function createTable(): bool
    {
        $schema = getenv('ACTIO_PG_SCHEMA');
        if (!$schema) {
            $schema = 'public';
        }
        $this->schema = $schema;

        // Try to create schema if it doesn't exist
        try {
            $this->db()->exec("CREATE SCHEMA IF NOT EXISTS {$this->schema}");
        } catch (\PDOException $e) {
            // Ignore schema creation errors, assume schema exists
        }

        $sql = <<<SQL
CREATE TABLE IF NOT EXISTS {$this->schema}.{$this->table()} (
    id SERIAL PRIMARY KEY,
    activity JSONB NOT NULL,
    activity_type VARCHAR(255) NOT NULL,
    actor JSONB NOT NULL,
    actor_type VARCHAR(255) NOT NULL,
    context JSONB DEFAULT NULL,
    date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    level VARCHAR(50) CHECK (level IN ('emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug')) DEFAULT NULL,
    summary VARCHAR(255) DEFAULT NULL,
    target JSONB NOT NULL,
    target_type VARCHAR(255) NOT NULL
);
SQL;

        $result = $this->db()->exec($sql);
        return $result !== false;
    }

    public function table(): string
    {
        return getenv('ACTIO_PG_TABLE') ?: 'actio_data_points';
    }
}