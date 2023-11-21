<?php
declare(strict_types=1);

use Actio\Entity\DataPoint;
use Actio\Factory\DataPointFactory;
use Tests\Support\Data\Factory\Entity\DataPointFactory as SupportDataPointFactory;

function setAsArrayOrJson(array $data): array|string
{
    if (rand(0,1)) {
        return json_encode($data);
    }

    return $data;
}

it('creates a new instance', function () {

    $data  = SupportDataPointFactory::make(['asArray' => true]);

    $activity = setAsArrayOrJson($data['activity']);
    $actor = setAsArrayOrJson($data['actor']);
    $target = setAsArrayOrJson($data['target']);

    $dataPointFactory  = new DataPointFactory();
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

    expect($dataPoint)
        ->toBeInstanceOf(DataPoint::class)
        ->toEqual($expected);
});
