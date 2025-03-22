<?php
declare(strict_types=1);

namespace Tests\Support\Data\Factory;

use Faker\Factory;
use Faker\Generator;

/**
 * @template T
 */
abstract class AbstractFactory
{
    protected Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    /**
     * @param array<string, mixed>|null $override
     * @return T
     */
    public static function make(array $override = []): mixed
    {
        $factory = static::createFactory();

        return $factory->create($override);
    }

    /**
     * @param array<string, mixed> $globalOverride
     * @param array<string, mixed> $individualOverrides
     * @return array<int, T>
     */
    public static function many(
        int $total,
        array $globalOverride = [],
        array $individualOverrides = []
    ): array {
        $objects = [];
        $factory = static::createFactory();
        for ($i = 0; $i < $total; $i++) {
            $objects[] = $factory->create($globalOverride + $individualOverrides);
        }

        return $objects;
    }

    /**
     * @param array<string, mixed> $override
     * @return mixed
     */
    abstract protected function create(array $override): mixed;

    /**
     * @return static
     */
    protected static function createFactory(): static
    {
        /** @phpstan-ignore-next-line */
        return new static();
    }
}
