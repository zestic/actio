<?php

declare(strict_types=1);

namespace Actio\Factory;

use Actio\Entity\DataPoint;

class DataPointFactory
{
    public function createFromRecord(
        array|string $activity,
        array|string $actor,
        array|string $target,
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

    private function prepareForArray(string|array $value): array
    {
        if (is_string($value)) {
            return json_decode($value, true);
        }

        return $value;
    }
}
