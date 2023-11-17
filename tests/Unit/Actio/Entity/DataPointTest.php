<?php
declare(strict_types=1);

use Tests\Support\Data\Factory\Entity\DataPointFactory;

describe('DataPoint', function () {
    test('toArray', function () {
        $dataPoint = DataPointFactory::make();
        $expectedData = [
            'activity' => $dataPoint->getActivity(),
            'actor'    => $dataPoint->getActor(),
            'context'  => $dataPoint->getContext(),
            'date'     => $dataPoint->getDate()->format('Y-m-d\TH:i:s\Z'),
            'id'       => $dataPoint->getId(),
            'level'    => $dataPoint->getLevel(),
            'summary'  => $dataPoint->getSummary(),
            'target'   => $dataPoint->getTarget(),
        ];
        expect($dataPoint->toArray())->toEqual($expectedData);
    });
});

$override = [];
