<?php
declare(strict_types=1);

namespace Actio\Handler\Driver;

use Actio\Entity\DataPoint;
use PDO;
use PDOException;

class MySQLPDODriver implements PDODriverInterface
{
    public PDO $db;
    public string $table;

    public function __construct()
    {
        $dbname = getenv('ACTIO_MYSQL_DB_NAME');
        $host = getenv('ACTIO_MYSQL_HOST');
        $password = getenv('ACTIO_MYSQL_PASSWORD');
        $username = getenv('ACTIO_MYSQL_USERNAME');
        $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
        $this->db = new PDO($dsn, $username, $password);
        $this->table = getenv('ACTIO_MYSQL_TABLE') ?? 'actio_data_points';
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

    public function save(DataPoint $dataPoint): bool
    {
        $sql = <<<SQL
INSERT INTO {$this->table} 
    (activity, activity_type, actor, actor_type, context, level, summary, target, target_type)
  VALUES(?,?,?,?,?,?,?,?,?);
SQL;
        $statement = $this->db->prepare($sql);
        $data = $this->prepareData($dataPoint);

        try {
            $this->db->beginTransaction();
            $statement->execute($data);
            $id = $this->db->lastInsertId();
            $this->db->commit();
        } catch(PDOException $e) {
            $this->db->rollback();
            throw $e;
        }
        $dataPoint->setId($id);

        return true;
    }

    private function prepareData(DataPoint $dataPoint): array
    {
        $data = $dataPoint->toArray();
        $data['context'] = json_encode($data['context']);
        $data['activity_type'] = $data['activity']['type'] ?? null;
        $data['activity'] = json_encode($data['activity']);
        $data['actor_type'] = $data['actor']['type'] ?? null;
        $data['actor'] = json_encode($data['actor']);
        $data['target_type'] = $data['target']['type'] ?? null;
        $data['target'] = json_encode($data['target']);
        ksort($data);

        return array_values($data);
    }
}
