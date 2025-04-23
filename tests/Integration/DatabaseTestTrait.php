<?php

namespace Tests\Integration;

trait DatabaseTestTrait
{
    use EnvironmentTrait;
    use MySQLDatabaseTrait;
    use PostgresDatabaseTrait;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        // Load environment variables
        $trait = new class {
            use EnvironmentTrait;
        };
        $trait->loadEnvironment();

        // Set up databases
        static::setUpMySQLDatabase();
        static::setUpPostgresDatabase();
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();
    }
}
