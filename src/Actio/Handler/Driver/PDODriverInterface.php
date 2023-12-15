<?php

declare(strict_types=1);

namespace Actio\Handler\Driver;

use Actio\Entity\DataPoint;

interface PDODriverInterface
{
    public function createTable(): bool;

    public function save(DataPoint $dataPoint): bool;
}
