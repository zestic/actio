<?php
declare(strict_types=1);

namespace Tests\Unit\Handler\Driver;

use Actio\Handler\Driver\PDODriver;
use PDO;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use RuntimeException;

/**
 * @covers \Actio\Handler\Driver\PDODriver
 * @codeCoverageIgnore
 */
abstract class AbstractPDODriverTest extends TestCase
{
    protected PDODriver $driver;
    /** @var array<string, string|false> */
    protected array $originalEnv = [];
    /** @var array<int, string> */
    protected array $requiredEnvVars = [];
    protected ReflectionMethod $createDsnMethod;
    protected ReflectionMethod $getUsernameMethod;
    protected ReflectionMethod $getPasswordMethod;

    abstract protected function createDriver(): PDODriver;
    abstract protected function getDefaultTableName(): string;

    protected function setUp(): void
    {
        parent::setUp();
        $this->driver = $this->createDriver();
        
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

        // Then set the specific one we're testing to empty
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

    public function testTableDefaultsToDefaultValue(): void
    {
        // Make sure the table env var is not set
        putenv($this->getTableEnvVar());

        $this->assertEquals($this->getDefaultTableName(), $this->driver->table());
    }

    public function testUsesCustomTableName(): void
    {
        // Set custom table name
        $customTable = 'custom_table';
        putenv($this->getTableEnvVar() . "=$customTable");

        $this->assertEquals($customTable, $this->driver->table());
    }

    abstract protected function getTableEnvVar(): string;

    /** @return array<int, array{string}> */
    abstract public static function envVarProvider(): array;
}
