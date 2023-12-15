<?php

declare(strict_types=1);

namespace Actio\Handler\Driver;

use PDO;

interface PDODriverInterface
{
    public function createTable(): bool;

    public function db(): PDO;

    public function table(): string;
}
