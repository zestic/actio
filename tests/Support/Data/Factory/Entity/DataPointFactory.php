<?php
declare(strict_types=1);

namespace Tests\Support\Data\Factory\Entity;

use Actio\Actio\Entity\DataPoint;
use Tests\Support\Data\Factory\AbstractFactory;

class DataPointFactory extends AbstractFactory
{
    protected function create(array $override): mixed
    {
        $activity = isset($override['activity'])? $override['activity'] : $this->createActivity();
        $actor    = isset($override['actor'])   ? $override['actor']    : $this->createActor();
        $context  = isset($override['context'])? $override['context']  : $this->createContext();
        $date  = isset($override['date'])? $override['date']  : new \DateTimeImmutable();
        $id      = isset($override['id'])?? $override['id'];
        $level    = isset($override['level'])? $override['level'] : $this->faker->randomElement(['debug', 'info', 'notice', 'warning', 'error']);
        $summary  = isset($override['summary'])? $override['summary'] : $this->faker->sentence;
        $target   = isset($override['target'])? $override['target'] : $this->createTarget();

        $dataPoint = (new DataPoint())
            ->setActivity($activity)
            ->setActor($actor)
            ->setContext($context)
            ->setDate($date)
            ->setLevel($level)
            ->setSummary($summary)
            ->setTarget($target);

        if ($id !== null) {
            $dataPoint->setId($id);
        }

        return $dataPoint;
    }

    private function createActivity(): array
    {

    }

    private function createActor(): array
    {

    }

    private function createContext(): array
    {

    }

    private function createTarget(): array
    {

    }
}
