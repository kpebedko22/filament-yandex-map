<?php

use Clickbar\Magellan\Data\Geometries\LineString;
use Clickbar\Magellan\Data\Geometries\MultiPolygon;
use Clickbar\Magellan\Data\Geometries\Point as MagellanPoint;
use Clickbar\Magellan\Data\Geometries\Polygon;
use Kpebedko22\FilamentYandexMap\Services\StateHandlers\PolygonStateHandler;
use Kpebedko22\FilamentYandexMap\Tests\Support\Rings;

it('formats a magellan polygon as a list of rings', function () {
    $polygon = Polygon::make([
        LineString::make(array_map(
            fn (array $point) => MagellanPoint::makeGeodetic($point[0], $point[1]),
            Rings::OUTER
        )),
    ]);

    expect((new PolygonStateHandler)->formatMagellanState($polygon))->toBe([Rings::OUTER]);
});

it('dehydrates a list of rings into a magellan polygon', function () {
    $polygon = (new PolygonStateHandler)
        ->dehydrateMagellanState([Rings::OUTER, Rings::HOLE]);

    expect($polygon)->toBeInstanceOf(Polygon::class)
        ->and($polygon->getLineStrings())->toHaveCount(2)
        ->and($polygon->getLineStrings()[0]->getPoints())->toHaveCount(5)
        ->and($polygon->getLineStrings()[1]->getPoints()[2]->getLatitude())->toBe(53.6)
        ->and($polygon->getLineStrings()[1]->getPoints()[2]->getLongitude())->toBe(83.6);
});

it('gives the same state after dehydrating and formatting a polygon', function (array $state) {
    $handler = new PolygonStateHandler;

    expect($handler->formatMagellanState($handler->dehydrateMagellanState($state)))->toBe($state);
})->with([
    'outer ring only' => [[Rings::OUTER]],
    'outer ring with a hole' => [[Rings::OUTER, Rings::HOLE]],
]);

it('does not change the rings, including the closing point', function () {
    $handler = new PolygonStateHandler;

    $ring = $handler->formatMagellanState(
        $handler->dehydrateMagellanState([Rings::OUTER])
    )[0];

    expect($ring)->toHaveCount(5)
        ->and($ring[0])->toBe($ring[4]);
});

it('rejects a magellan state of another geometry type', function () {
    $handler = new PolygonStateHandler;

    $line = LineString::make([MagellanPoint::makeGeodetic(53.35, 83.75)]);

    $states = [
        MagellanPoint::makeGeodetic(53.35, 83.75),
        $line,
        MultiPolygon::make([Polygon::make([$line])]),
        [Rings::OUTER],
        null,
    ];

    foreach ($states as $state) {
        expect(fn () => $handler->formatMagellanState($state))
            ->toThrow(InvalidArgumentException::class, 'State must be a ['.Polygon::class.']');
    }
});

it('reads and writes the json state using custom attributes', function () {
    $handler = (new PolygonStateHandler)->usingLatLngAttributes('latitude', 'longitude');

    $json = [[
        ['latitude' => 53.0, 'longitude' => 83.0],
        ['latitude' => 53.0, 'longitude' => 84.0],
        ['latitude' => 54.0, 'longitude' => 84.0],
        ['latitude' => 53.0, 'longitude' => 83.0],
    ]];
    $state = [[[53.0, 83.0], [53.0, 84.0], [54.0, 84.0], [53.0, 83.0]]];

    expect($handler->formatJsonState($json))->toBe($state)
        ->and($handler->dehydrateJsonState($state))->toBe($json);
});

it('reads the json state of several rings using the default attributes', function () {
    $state = (new PolygonStateHandler)->formatJsonState([
        [['lat' => 53.0, 'lng' => 83.0], ['lat' => 53.0, 'lng' => 84.0]],
        [['lat' => 53.4, 'lng' => 83.4]],
    ]);

    expect($state)->toBe([
        [[53.0, 83.0], [53.0, 84.0]],
        [[53.4, 83.4]],
    ]);
});

it('returns itself from usingLatLngAttributes', function () {
    $handler = new PolygonStateHandler;

    expect($handler->usingLatLngAttributes('latitude', 'longitude'))->toBe($handler);
});
