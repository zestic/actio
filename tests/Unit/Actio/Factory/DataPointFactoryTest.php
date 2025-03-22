<?php
declare(strict_types=1);

namespace Tests\Unit\Actio\Factory;

use Actio\Entity\DataPoint;
use Actio\Factory\DataPointFactory;
use PHPUnit\Framework\TestCase;
use Tests\Support\Data\Factory\Entity\DataPointFactory as SupportDataPointFactory;

class DataPointFactoryTest extends TestCase
{
    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>|string
     */
    private function setAsArrayOrJson(array $data): array|string
    {
        if (rand(0,1)) {
            $json = json_encode($data);
            if ($json === false) {
                return $data;
            }
            return $json;
        }

        return $data;
    }

    public function testCreatesNewInstance(): void
    {
        /** @var array<string, mixed> $data */
        $data = SupportDataPointFactory::make([
            'asArray' => true,
            'level' => 'warning'
        ]);
        $this->assertIsArray($data);

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
        /** @var \Actio\Entity\DataPoint $expected */
        $expected = SupportDataPointFactory::make($data);

        $this->assertInstanceOf(DataPoint::class, $dataPoint);
        $this->assertEquals($expected, $dataPoint);
    }
}
