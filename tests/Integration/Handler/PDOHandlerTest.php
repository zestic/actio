<?php
declare(strict_types=1);

namespace Tests\Integration\Handler;

use Actio\Entity\DataPoint;
use Actio\Handler\Driver\MySQLPDODriver;
use Actio\Handler\PDOHandler;
use PDO;
use PHPUnit\Framework\TestCase;
use Tests\Integration\DatabaseTestTrait;

class PDOHandlerTest extends TestCase
{
    use DatabaseTestTrait;
    public function testSavesDataPoint(): void
    {
        $driver = new MySQLPDODriver();
        $driver->createTable();
        $db = $driver->db();

        $handler = new PDOHandler($driver);

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

        $handler->save($dataPoint);

        $this->assertNotNull($dataPoint->getId());

        $statement = $db->query('SELECT * FROM `actio_data_points` WHERE `id` = '. $dataPoint->getId());
        $this->assertNotFalse($statement, 'Query failed');
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $this->assertNotFalse($result, 'No data found');

        $activityData = json_decode($result['activity'], true);
        $this->assertEquals($activity['type'], $activityData['type']);
        $this->assertEquals($activity['type'], $result['activity_type']);

        $actorData = json_decode($result['actor'], true);
        $this->assertEquals($actor['type'], $actorData['type']);
        $this->assertEquals($actor['type'], $result['actor_type']);

        $contextData = json_decode($result['context'], true);
        $this->assertEquals($context['could'], $contextData['could']);

        $this->assertNotNull($result['date']);
        $this->assertEquals($dataPoint->getId(), $result['id']);
        $this->assertEquals('warning', $result['level']);
        $this->assertEquals($summary, $result['summary']);

        $targetData = json_decode($result['target'], true);
        $this->assertEquals($target['type'], $targetData['type']);
        $this->assertEquals($target['type'], $result['target_type']);

        $db->exec('DROP TABLE IF EXISTS `actio_data_points`');
    }
}

