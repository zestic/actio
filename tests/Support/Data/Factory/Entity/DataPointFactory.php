<?php
declare(strict_types=1);

namespace Tests\Support\Data\Factory\Entity;

use Actio\Actio\Entity\DataPoint;
use Tests\Support\Data\Factory\AbstractFactory;

class DataPointFactory extends AbstractFactory
{
    protected function create(array $override): DataPoint
    {
        $activity = $override['activity'] ?? $this->createActivity();
        $actor = $override['actor'] ?? $this->createActor();
        $context = $override['context'] ?? $this->createContext();
        $date = $override['date'] ?? new \DateTimeImmutable();
        $id = $override['id'] ?? null;
        $level = $override['level'] ?? $this->faker->randomElement(
            ['debug', 'info', 'notice', 'warning', 'error']
        );
        $summary = $override['summary'] ?? $this->faker->sentence();
        $target = $override['target'] ?? $this->createTarget();

        $dataPoint = (new DataPoint())
            ->setActivity($activity)
            ->setActor($actor)
            ->setContext($context)
            ->setDate($date)
            ->setId($id)
            ->setLevel($level)
            ->setSummary($summary)
            ->setTarget($target);

        return $dataPoint;
    }

    private function createActivity(): array
    {
        return [
            'type' => $this->faker->word(),
        ];
    }

    private function createActor(): array
    {
        return [
            'type' => $this->faker->word(),
        ];
    }

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

    private function createTarget(): array
    {
        return [
            'type' => $this->faker->word(),
        ];
    }
}
