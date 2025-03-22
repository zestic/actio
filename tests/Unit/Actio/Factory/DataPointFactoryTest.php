<?php
declare(strict_types=1);

namespace Tests\Unit\Actio\Factory;

use Actio\Entity\DataPoint;
use Actio\Factory\DataPointFactory;
use PHPUnit\Framework\TestCase;
use Tests\Support\Data\Factory\Entity\DataPointFactory as SupportDataPointFactory;

class DataPointFactoryTest extends TestCase
{
    private function setAsArrayOrJson(array $data): array|string
    {
        if (rand(0,1)) {
            return json_encode($data);
        }

        return $data;
    }

    public function testCreatesNewInstance(): void
    {
        $data = SupportDataPointFactory::make(['asArray' => true]);

        $activity = $this->setAsArrayOrJson($data['activity']);
        $actor = $this->setAsArrayOrJson($data['actor']);
        $target = $this->setAsArrayOrJson($data['target']);

        $dataPointFactory = new DataPointFactory();
        $dataPoint = $dataPointFactory->createFromRecord(
            $activity,
            $actor,
            $target,
            $data['summary'],
            $data['context'],
            $data['level'],
        );

        $data['date'] = null;
        $data['id'] = null;
        $expected = SupportDataPointFactory::make($data);

        $this->assertInstanceOf(DataPoint::class, $dataPoint);
        $this->assertEquals($expected, $dataPoint);
    }
}
