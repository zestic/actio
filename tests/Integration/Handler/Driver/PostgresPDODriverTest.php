<?php
declare(strict_types=1);

namespace Tests\Integration\Handler\Driver;

use Actio\Handler\Driver\PostgresPDODriver;
use PHPUnit\Framework\TestCase;
use Tests\Integration\DatabaseTestTrait;

class PostgresPDODriverTest extends TestCase
{
    use DatabaseTestTrait;

    public function testCreatesDataPointTable(): void
    {
        $driver = new PostgresPDODriver();
        $db = $driver->db();
        $schema = getenv('ACTIO_PG_SCHEMA') ?: 'public';
        $db->exec('DROP TABLE IF EXISTS ' . $schema . '.' . $driver->table());

        $result = $driver->createTable();
        $this->assertTrue((bool) $result, 'Table creation failed');

        $schema = getenv('ACTIO_PG_SCHEMA') ?: 'public';
        $statement = $db->query("SELECT EXISTS (
            SELECT FROM pg_tables 
            WHERE schemaname = '{$schema}' 
            AND tablename = '" . $driver->table() . "'
        )");
        $this->assertNotFalse($statement, 'Query failed');
        $this->assertTrue($statement->fetchColumn());

        $schema = getenv('ACTIO_PG_SCHEMA') ?: 'public';
        $db->exec('DROP TABLE IF EXISTS ' . $schema . '.' . $driver->table());
    }
}
