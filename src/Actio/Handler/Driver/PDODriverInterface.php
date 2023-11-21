<?php

declare(strict_types=1);

namespace Actio\Handler\Driver;

use Actio\Entity\DataPoint;

interface PDODriverInterface
{
    public function createTable(): void;

    public function save(DataPoint $dataPoint): bool;
}
