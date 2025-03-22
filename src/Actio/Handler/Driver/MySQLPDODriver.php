<?php
declare(strict_types=1);

namespace Actio\Handler\Driver;

use PDO;

class MySQLPDODriver implements PDODriverInterface
{
    private PDO $db;
    private string $table;

    public function __construct()
    {
        $dbname = getenv('ACTIO_MYSQL_DB_NAME');
        if ($dbname === false) {
            throw new \RuntimeException('ACTIO_MYSQL_DB_NAME environment variable is not set');
        }

        $host = getenv('ACTIO_MYSQL_HOST');
        if ($host === false) {
            throw new \RuntimeException('ACTIO_MYSQL_HOST environment variable is not set');
        }

        $password = getenv('ACTIO_MYSQL_PASSWORD');
        if ($password === false) {
            throw new \RuntimeException('ACTIO_MYSQL_PASSWORD environment variable is not set');
        }

        $username = getenv('ACTIO_MYSQL_USERNAME');
        if ($username === false) {
            throw new \RuntimeException('ACTIO_MYSQL_USERNAME environment variable is not set');
        }

        $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
        $this->db = new PDO($dsn, $username, $password);
        $table = getenv('ACTIO_MYSQL_TABLE');
        if (!$table) {
            $table = 'actio_data_points';
        }
        $this->table = $table;
    }

    public function createTable(): bool
    {
        $sql = <<<SQL
CREATE TABLE IF NOT EXISTS {$this->table} (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `activity` json NOT NULL,
    `activity_type` varchar(255) NOT NULL,
    `actor` json NOT NULL,
    `actor_type` varchar(255) NOT NULL,
    `context` json DEFAULT NULL,
    `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `level` enum('emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug') DEFAULT NULL,
    `summary` varchar(255) DEFAULT NULL,
    `target` json NOT NULL,
    `target_type` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
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
