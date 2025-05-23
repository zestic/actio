<?php
declare(strict_types=1);

namespace Tests\Support\Data\Factory\Entity;

use Actio\Entity\DataPoint;
use DateTimeImmutable;
use Tests\Support\Data\Factory\AbstractFactory;

use function PHPUnit\Framework\arrayHasKey;

/**
 * @extends AbstractFactory<DataPoint|array<string, mixed>>
 */
class DataPointFactory extends AbstractFactory
{
    /**
     * @param array<string, mixed> $override
     * @return DataPoint|array<string, mixed>
     */
    protected function create(array $override): DataPoint|array
    {
        $asArray = isset($override['asArray']) && $override['asArray'] === true;
        $activity = $override['activity'] ?? $this->createActivity();
        $actor = $override['actor'] ?? $this->createActor();
        $context = $override['context'] ?? $this->createContext();
        $date = $this->determineDate($override);
        $id = $override['id'] ?? null;
        $level = $override['level'] ?? $this->faker->randomElement(
            ['debug', 'info', 'notice', 'warning', 'error', null]
        );
        $summary = $override['summary'] ?? $this->faker->sentence();
        $target = $override['target'] ?? $this->createTarget();

        if ($asArray) {
            return [
                'activity' => $activity,
                'actor'    => $actor,
                'context'  => $context,
                'date'     => $date,
                'id'       => $id,
                'level'    => $level,
                'summary'  => $summary,
                'target'   => $target,
            ];
        }

        return (new DataPoint())
            ->setActivity($activity)
            ->setActor($actor)
            ->setContext($context)
            ->setDate($date)
            ->setId($id)
            ->setLevel($level)
            ->setSummary($summary)
            ->setTarget($target);
    }

    /** @return array<string, string> */
    private function createActivity(): array
    {
        return [
            'type' => $this->faker->word(),
        ];
    }

    /** @return array<string, string> */
    private function createActor(): array
    {
        return [
            'type' => $this->faker->word(),
        ];
    }

    /** @return array<string, string> */
    private function createContext(): array
    {
        $items = $this->faker->numberBetween(3,6);
        $context = [];
        for ($i = 0; $i < $items; $i++) {
            $key = $this->faker->word();
            $context[$key] = $this->faker->sentence();
        }

        return $context;
    }

    /** @return array<string, string> */
    private function createTarget(): array
    {
        return [
            'type' => $this->faker->word(),
        ];
    }

    /** @param array<string, mixed> $override */
    private function determineDate(array $override): ?DateTimeImmutable
    {
        if (array_key_exists('date', $override)) {
            return $override['date'];
        }

        return new \DateTimeImmutable();
    }
}
