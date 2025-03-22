<?php

declare(strict_types=1);

namespace Actio\Factory;

use Actio\Entity\DataPoint;
use JsonSerializable;

class DataPointFactory
{
    /**
     * @param array<string, mixed>|JsonSerializable|string $activity
     * @param array<string, mixed>|JsonSerializable|string $actor
     * @param array<string, mixed>|JsonSerializable|string $target
     * @param array<string, mixed>|null $context
     */
    public function createFromRecord(
        array|JsonSerializable|string $activity,
        array|JsonSerializable|string $actor,
        array|JsonSerializable|string $target,
        ?string $summary,
        ?array $context,
        ?string $level,
    ): DataPoint {
        $activity = $this->prepareForArray($activity);
        $actor = $this->prepareForArray($actor);
        $target = $this->prepareForArray($target);

        return (new DataPoint())
            ->setActivity($activity)
            ->setActor($actor)
            ->setTarget($target)
            ->setSummary($summary)
            ->setContext($context)
            ->setLevel($level)
        ;
    }

    /**
     * @param string|JsonSerializable|array<string, mixed> $value
     * @return array<string, mixed>
     */
    private function prepareForArray(string|JsonSerializable|array $value): array
    {
        if (is_string($value)) {
            return json_decode($value, true);
        }
        if (is_object($value)) {
            return $value->jsonSerialize();
        }

        return $value;
    }
}
