<?php
declare(strict_types=1);

use Tests\Support\Data\Factory\Entity\DataPointFactory;

describe('DataPoint', function () {
    test('toArray', function () {
        $dataPoint = DataPointFactory::make();
    });
});
