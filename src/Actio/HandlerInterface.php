<?php

declare(strict_types=1);

namespace Actio;

use Actio\Entity\DataPoint;

interface HandlerInterface
{
    public function save(DataPoint $dataPoint): bool;
}
