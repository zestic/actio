<?php
declare(strict_types=1);

namespace Tests\Integration\Handler\Driver;

use Actio\Handler\Driver\MySQLPDODriver;
use PHPUnit\Framework\TestCase;
use Tests\Integration\DatabaseTestTrait;

class MySQLPDODriverTest extends TestCase
{
    use DatabaseTestTrait;
    public function testCreatesDataPointTable(): void
    {
        $driver = new MySQLPDODriver();
        $db = $driver->db();
        $db->exec('DROP TABLE IF EXISTS `' . $driver->table() . '`');

        $result = $driver->createTable();
        $this->assertTrue($result);

        $statement = $db->query("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '" . getenv('ACTIO_MYSQL_DB_NAME') . "' AND table_name = '" . $driver->table() . "'");
        $this->assertNotFalse($statement, 'Query failed');
        $this->assertEquals(1, $statement->fetchColumn());

        $db->exec('DROP TABLE IF EXISTS `' . $driver->table() . '`');
    }
}

