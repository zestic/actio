<?php

declare(strict_types=1);

namespace Actio;

use Actio\Entity\DataPoint;
use Actio\Factory\DataPointFactory;
use JsonSerializable;

class Actio
{
    private static HandlerInterface $handler;

    public static function setHandler(HandlerInterface $handler): void
    {
        self::$handler = $handler;
    }

    /**
     * @param array<string, mixed>|JsonSerializable|string $activity
     * @param array<string, mixed>|JsonSerializable|string $actor
     * @param array<string, mixed>|JsonSerializable|string $target
     * @param array<string, mixed>|null $context
     */
    public static function record(
        array|JsonSerializable|string $activity,
        array|JsonSerializable|string $actor,
        array|JsonSerializable|string $target,
        ?string $summary = null,
        ?array $context = null,
        ?string $level = null,
    ): void {
        $dataPoint = (new DataPointFactory())
            ->createFromRecord(
                $activity,
                $actor,
                $target,
                $summary,
                $context,
                $level
            );
        self::save($dataPoint);
    }

    public static function save(DataPoint $dataPoint): bool
    {
        return self::$handler->save($dataPoint);
    }
}
