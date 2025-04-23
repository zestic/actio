<?php
declare(strict_types=1);

namespace Actio\Handler\Driver;

use PDO;

abstract class PDODriver implements PDODriverInterface
{
    private ?PDO $connection = null;

    abstract protected function createDsn(): string;
    abstract protected function getUsername(): string;
    abstract protected function getPassword(): string;
    /**
     * @return array<int, mixed>
     */
    abstract protected function getOptions(): array;
    /**
     * @return bool|int
     */
    abstract public function createTable(): bool|int;

    abstract public function table(): string;

    public function db(): PDO
    {
        if ($this->connection === null) {
            $this->connection = new PDO(
                $this->createDsn(),
                $this->getUsername(),
                $this->getPassword(),
                $this->getOptions()
            );
        }

        return $this->connection;
    }
}
