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
        $db->exec('DROP TABLE IF EXISTS `actio_data_points`');

        $result = $driver->createTable();
        $this->assertTrue($result);

        $statement = $db->query('SHOW TABLES LIKE "actio_data_points"');
        $this->assertEquals(1, $statement->rowCount());

        $db->exec('DROP TABLE IF EXISTS `actio_data_points`');
    }
}
