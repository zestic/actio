<?php
declare(strict_types=1);

use Actio\Entity\DataPoint;
use Actio\Handler\Driver\MySQLPDODriver;

it('creates a data point table', function () {
    $driver = new MySQLPDODriver();
    $db = $driver->db;
    $db->exec('DROP TABLE IF EXISTS `actio_data_points`');

    $result = $driver->createTable();
    expect($result)->toBeTrue();

    $statement = $driver->db->query('SHOW TABLES LIKE "actio_data_points"');
    expect($statement->rowCount())->toBe(1);

    $db->exec('DROP TABLE IF EXISTS `actio_data_points`');
});

it ('saves a data point', function () {
    $driver = new MySQLPDODriver();
    $driver->createTable();
    $db = $driver->db;

    $activity = [
        'type' => 'test',
    ];
    $actor = [
        'type' => 'tester',
    ];
    $context = [
        'could' => 'be anything'
    ];
    $summary = "Testing if it saved";
    $target = [
        'type' => 'code',
    ];
    $dataPoint = (new DataPoint())
        ->setActivity($activity)
        ->setActor($actor)
        ->setContext($context)
        ->setLevel('warning')
        ->setSummary($summary)
        ->setTarget($target);

    $driver->save($dataPoint);

    expect($dataPoint->getId())->not->toBeNull();
    $statement = $db->query('SELECT * FROM `actio_data_points` WHERE `id` = '. $dataPoint->getId());
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    expect($result['activity'])
        ->json()
        ->type->toBe($activity['type']);
    expect($result['activity_type'])
        ->toBe($activity['type']);
    expect($result['actor'])
        ->json()
        ->type->toBe($actor['type']);
    expect($result['actor_type'])
        ->toBe($actor['type']);
    expect($result['context'])
        ->json()
        ->could->toBe($context['could']);
    expect($result['date'])->not->toBeNull();
    expect($result['id'])->toEqual($dataPoint->getId());
    expect($result['level'])->toBe('warning');
    expect($result['summary'])->toBe($summary);
    expect($result['target'])
        ->json()
        ->type->toBe($target['type']);
    expect($result['target_type'])
        ->toBe($target['type']);

    $db->exec('DROP TABLE IF EXISTS `actio_data_points`');
});
