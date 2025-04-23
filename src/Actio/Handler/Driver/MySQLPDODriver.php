<?php
declare(strict_types=1);

namespace Actio\Handler\Driver;

use PDO;

class MySQLPDODriver extends PDODriver implements PDODriverInterface
{

    protected function createDsn(): string
    {
        $host = getenv('ACTIO_MYSQL_HOST');
        if (!$host) {
            throw new \RuntimeException('ACTIO_MYSQL_HOST environment variable is not set');
        }

        $dbname = getenv('ACTIO_MYSQL_DB_NAME');
        if (!$dbname) {
            throw new \RuntimeException('ACTIO_MYSQL_DB_NAME environment variable is not set');
        }

        return "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
    }

    protected function getUsername(): string
    {
        $username = getenv('ACTIO_MYSQL_USERNAME');
        if (!$username) {
            throw new \RuntimeException('ACTIO_MYSQL_USERNAME environment variable is not set');
        }
        return $username;
    }

    protected function getPassword(): string
    {
        $password = getenv('ACTIO_MYSQL_PASSWORD');
        if (!$password) {
            throw new \RuntimeException('ACTIO_MYSQL_PASSWORD environment variable is not set');
        }
        return $password;
    }

    public function createTable(): bool
    {
        $sql = <<<SQL
CREATE TABLE IF NOT EXISTS {$this->table()} (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `activity` json NOT NULL,
    `activity_type` varchar(255) NOT NULL,
    `actor` json NOT NULL,
    `actor_type` varchar(255) NOT NULL,
    `context` json DEFAULT NULL,
    `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `level` varchar(50) CHECK (level IN ('emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug')) DEFAULT NULL,
    `summary` varchar(255) DEFAULT NULL,
    `target` json NOT NULL,
    `target_type` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
SQL;

        $result = $this->db()->exec($sql);
        return $result !== false;
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

    public function table(): string
    {
        return getenv('ACTIO_MYSQL_TABLE') ?: 'actio_data_points';
    }
}
