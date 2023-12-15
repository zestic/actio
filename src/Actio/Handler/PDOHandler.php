<?php

declare(strict_types=1);

namespace Actio\Handler;

use Actio\Entity\DataPoint;
use Actio\Handler\Driver\PDODriverInterface;
use Actio\HandlerInterface;
use PDO;
use PDOException;

class PDOHandler implements HandlerInterface
{
    private PDO $db;
    private string $table;

    public function __construct(
        private PDODriverInterface $driver,
    ) {
        $this->db = $this->driver->db();
        $this->table = $this->driver->table();
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
