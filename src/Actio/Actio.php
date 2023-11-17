<?php
declare(strict_types=1);

namespace Actio\Actio;

use Actio\Actio\Entity\DataPoint;

class Actio
{
    public function __construct()
    {
    }

    public static function record(
        array|string $activity,
        array|string $actor,
        array|string $target,
        ?string $summary = null,
        ?array $context = null,
        ?string $level,
    ) {
    }

    public static function save(DataPoint $dataPoint): bool
    {

    }
}
