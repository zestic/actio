<?php
declare(strict_types=1);

use Actio\Handler\Driver\MySQLPDODriver;

it('creates a data point table', function () {
    $driver = new MySQLPDODriver();
    $db = $driver->db();
    $db->exec('DROP TABLE IF EXISTS `actio_data_points`');

    $result = $driver->createTable();
    expect($result)->toBeTrue();

    $statement = $db->query('SHOW TABLES LIKE "actio_data_points"');
    expect($statement->rowCount())->toBe(1);

    $db->exec('DROP TABLE IF EXISTS `actio_data_points`');
});
