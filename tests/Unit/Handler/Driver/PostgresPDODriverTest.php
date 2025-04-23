<?php
declare(strict_types=1);

namespace Tests\Unit\Handler\Driver;

use Actio\Handler\Driver\PostgresPDODriver;
use Actio\Handler\Driver\PDODriver;
use ReflectionClass;
use RuntimeException;

/**
 * @covers \Actio\Handler\Driver\PostgresPDODriver
 */
class PostgresPDODriverTest extends AbstractTestPDODriver
{
    /** @var array<int, string> */
    protected array $requiredEnvVars = [
        'ACTIO_PG_HOST',
        'ACTIO_PG_DB_NAME',
        'ACTIO_PG_USERNAME',
        'ACTIO_PG_PASSWORD'
    ];

    protected function createDriver(): PDODriver
    {
        return new PostgresPDODriver();
    }

    protected function getDefaultTableName(): string
    {
        return 'actio_data_points';
    }

    protected function getTableEnvVar(): string
    {
        return 'ACTIO_PG_TABLE';
    }

    protected function setUp(): void
    {
        parent::setUp();
        
        // Set up reflection methods
        $reflection = new ReflectionClass($this->driver);
        $this->createDsnMethod = $reflection->getMethod('createDsn');
        $this->createDsnMethod->setAccessible(true);
        $this->getUsernameMethod = $reflection->getMethod('getUsername');
        $this->getUsernameMethod->setAccessible(true);
        $this->getPasswordMethod = $reflection->getMethod('getPassword');
        $this->getPasswordMethod->setAccessible(true);
        
        // Backup current environment variables
        foreach ($this->requiredEnvVars as $var) {
            $this->originalEnv[$var] = getenv($var);
        }
    }

    protected function tearDown(): void
    {
        // Restore original environment variables
        foreach ($this->originalEnv as $var => $value) {
            if ($value === false) {
                putenv($var);
            } else {
                putenv("$var=$value");
            }
        }
        parent::tearDown();
    }

    /**
     * @dataProvider envVarProvider
     */
    public function testThrowsExceptionWhenEnvVarIsEmpty(string $envVar): void
    {
        // First set all required env vars to valid values
        foreach ($this->requiredEnvVars as $var) {
            putenv("$var=test_value");
        }

        // Then unset the specific one we're testing
        putenv("$envVar=");

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("$envVar environment variable is not set");

        // Call the appropriate method based on which env var we're testing
        if (str_contains($envVar, 'HOST') || str_contains($envVar, 'DB_NAME')) {
            $this->createDsnMethod->invoke($this->driver);
        } elseif (str_contains($envVar, 'USERNAME')) {
            $this->getUsernameMethod->invoke($this->driver);
        } elseif (str_contains($envVar, 'PASSWORD')) {
            $this->getPasswordMethod->invoke($this->driver);
        }
    }

    /**
     * @dataProvider envVarProvider
     */
    public function testThrowsExceptionWhenEnvVarNotSet(string $envVar): void
    {
        // First set all required env vars to valid values
        foreach ($this->requiredEnvVars as $var) {
            putenv("$var=test_value");
        }

        // Then unset the specific one we're testing
        putenv($envVar);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("$envVar environment variable is not set");

        // Call the appropriate method based on which env var we're testing
        if (str_contains($envVar, 'HOST') || str_contains($envVar, 'DB_NAME')) {
            $this->createDsnMethod->invoke($this->driver);
        } elseif (str_contains($envVar, 'USERNAME')) {
            $this->getUsernameMethod->invoke($this->driver);
        } elseif (str_contains($envVar, 'PASSWORD')) {
            $this->getPasswordMethod->invoke($this->driver);
        }
    }

    public function testPortDefaultsTo5432WhenNotSet(): void
    {
        putenv('ACTIO_PG_PORT');  // Unset port

        // Set other required env vars
        foreach ($this->requiredEnvVars as $var) {
            putenv("$var=test_value");
        }

        $dsn = $this->createDsnMethod->invoke($this->driver);
        $this->assertStringContainsString('port=5432', $dsn);
    }

    /** @return array<int, array{string}> */
    public static function envVarProvider(): array
    {
        return [
            ['ACTIO_PG_HOST'],
            ['ACTIO_PG_DB_NAME'],
            ['ACTIO_PG_USERNAME'],
            ['ACTIO_PG_PASSWORD']
        ];
    }
}
