<?php
declare(strict_types=1);

namespace Tests\Integration\Handler\Driver;

use Actio\Handler\Driver\PostgreSQLPDODriver;
use PHPUnit\Framework\TestCase;
use Tests\Integration\DatabaseTestTrait;

class PostgreSQLPDODriverTest extends TestCase
{
    use DatabaseTestTrait;

    public function testCreatesDataPointTable(): void
    {
        $driver = new PostgreSQLPDODriver();
        $db = $driver->db();
        $db->exec('DROP TABLE IF EXISTS actio_test.actio_data_points');

        $result = $driver->createTable();
        $this->assertTrue((bool) $result, 'Table creation failed');

        $statement = $db->query("SELECT EXISTS (
            SELECT FROM pg_tables 
            WHERE schemaname = 'actio_test' 
            AND tablename = 'actio_data_points'
        )");
        $this->assertNotFalse($statement, 'Query failed');
        $this->assertTrue($statement->fetchColumn());

        $db->exec('DROP TABLE IF EXISTS actio_test.actio_data_points');
    }
}
