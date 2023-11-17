<?php
declare(strict_types=1);

namespace Tests\Support\Data\Factory;

use Faker\Factory;
use Faker\Generator;

abstract class AbstractFactory
{
    protected Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public static function make(array $override = null): mixed
    {
        $factory = self::createFactory();
        $override = $override ? (array)$override : [];

        return $factory->create($override);
    }

    public static function many(
        int $total,
        array $globalOverride = [],
        array $individualOverrides = []
    ): array {
        $objects = [];
        $factory = self::createFactory();
        $globalOverride = $globalOverride ? (array)$globalOverride : [];
        for ($i = 0; $i < $total; $i++) {
            $individualOverrides = $individualOverrides ? (array)$individualOverrides : [];
            $objects[] = $factory->create($globalOverride + $individualOverrides);
        }

        return $objects;
    }

    abstract protected function create(array $override): mixed;

    private static function createFactory(): self
    {
        return new static();
    }
}
