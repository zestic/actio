<?php
declare(strict_types=1);

namespace Tests\Unit\Handler\Driver;

use Actio\Handler\Driver\MySQLPDODriver;
use Actio\Handler\Driver\PDODriver;

/**
 * @covers \Actio\Handler\Driver\MySQLPDODriver
 */
class MySQLPDODriverTest extends AbstractTestPDODriver
{
    /** @var array<int, string> */
    protected array $requiredEnvVars = [
        'ACTIO_MYSQL_HOST',
        'ACTIO_MYSQL_DB_NAME',
        'ACTIO_MYSQL_USERNAME',
        'ACTIO_MYSQL_PASSWORD'
    ];

    protected function createDriver(): PDODriver
    {
        return new MySQLPDODriver();
    }

    protected function getDefaultTableName(): string
    {
        return 'actio_data_points';
    }

    protected function getTableEnvVar(): string
    {
        return 'ACTIO_MYSQL_TABLE';
    }

    /** @return array<int, array{string}> */
    public static function envVarProvider(): array
    {
        return [
            ['ACTIO_MYSQL_HOST'],
            ['ACTIO_MYSQL_DB_NAME'],
            ['ACTIO_MYSQL_USERNAME'],
            ['ACTIO_MYSQL_PASSWORD']
        ];
    }
}
