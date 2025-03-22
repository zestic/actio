<?php
declare(strict_types=1);

namespace Actio\Handler\Driver;

use PDO;

class PostgresPDODriver implements PDODriverInterface
{
    private PDO $db;
    private string $table;

    public function __construct()
    {
        $dbname = getenv('ACTIO_POSTGRES_DB_NAME');
        if ($dbname === false) {
            throw new \RuntimeException('ACTIO_POSTGRES_DB_NAME environment variable is not set');
        }

        $host = getenv('ACTIO_POSTGRES_HOST');
        if ($host === false) {
            throw new \RuntimeException('ACTIO_POSTGRES_HOST environment variable is not set');
        }

        $password = getenv('ACTIO_POSTGRES_PASSWORD');
        if ($password === false) {
            throw new \RuntimeException('ACTIO_POSTGRES_PASSWORD environment variable is not set');
        }

        $username = getenv('ACTIO_POSTGRES_USERNAME');
        if ($username === false) {
            throw new \RuntimeException('ACTIO_POSTGRES_USERNAME environment variable is not set');
        }

        $port = getenv('ACTIO_POSTGRES_PORT') ?: '5432';
        $dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";
        $this->db = new PDO($dsn, $username, $password);
        $table = getenv('ACTIO_POSTGRES_TABLE');
        if (!$table) {
            $table = 'actio_data_points';
        }
        $this->table = $table;
    }

    public function createTable(): bool
    {
        $sql = <<<SQL
CREATE TABLE IF NOT EXISTS {$this->table} (
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

        return false !== $this->db->exec($sql);
    }

    public function db(): PDO
    {
        return $this->db;
    }

    public function table(): string
    {
        return $this->table;
    }
}