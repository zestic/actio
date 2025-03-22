<?php
declare(strict_types=1);

namespace Tests\Unit\Actio\Entity;

use PHPUnit\Framework\TestCase;
use Tests\Support\Data\Factory\Entity\DataPointFactory;

class DataPointTest extends TestCase
{
    public function testToArray(): void
    {
        $dataPoint = DataPointFactory::make([
            'id' => 42,
        ]);
        $expectedData = [
            'activity' => $dataPoint->getActivity(),
            'actor'    => $dataPoint->getActor(),
            'context'  => $dataPoint->getContext(),
            'date'     => $dataPoint->getDate()->format('Y-m-d\TH:i:s\Z'),
            'id'       => 42,
            'level'    => $dataPoint->getLevel(),
            'summary'  => $dataPoint->getSummary(),
            'target'   => $dataPoint->getTarget(),
        ];
        $this->assertEquals($expectedData, $dataPoint->toArray());
    }

    public function testToArrayForNewDataPoint(): void
    {
        $dataPoint = DataPointFactory::make([
            'date' => null,
        ]);
        $expectedData = [
            'activity' => $dataPoint->getActivity(),
            'actor'    => $dataPoint->getActor(),
            'context'  => $dataPoint->getContext(),
            'level'    => $dataPoint->getLevel(),
            'summary'  => $dataPoint->getSummary(),
            'target'   => $dataPoint->getTarget(),
        ];
        $this->assertEquals($expectedData, $dataPoint->toArray());
    }
}
