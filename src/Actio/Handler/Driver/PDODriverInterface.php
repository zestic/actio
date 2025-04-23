<?php

declare(strict_types=1);

namespace Actio\Handler\Driver;

use PDO;

interface PDODriverInterface
{
    /**
     * @return bool|int
     */
    public function createTable(): bool|int;

    public function db(): PDO;

    public function table(): string;
}
