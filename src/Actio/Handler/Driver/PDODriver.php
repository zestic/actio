<?php
declare(strict_types=1);

namespace Actio\Handler\Driver;

use PDO;

abstract class PDODriver
{
    private ?PDO $connection = null;

    abstract protected function createDsn(): string;
    abstract protected function getUsername(): string;
    abstract protected function getPassword(): string;
    abstract protected function getOptions(): array;
    abstract public function createTable(): bool;

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
