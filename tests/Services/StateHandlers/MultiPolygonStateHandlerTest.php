<?php

use Clickbar\Magellan\Data\Geometries\LineString;
use Clickbar\Magellan\Data\Geometries\MultiPolygon;
use Clickbar\Magellan\Data\Geometries\Point as MagellanPoint;
use Clickbar\Magellan\Data\Geometries\Polygon;
use Kpebedko22\FilamentYandexMap\Enums\YandexMapMode;
use Kpebedko22\FilamentYandexMap\Services\StateHandlers\MultiPolygonStateHandler;
use Kpebedko22\FilamentYandexMap\Services\StateHandlers\StateHandlerFactory;

// Closed rings: the first point is repeated as the last one.
$outer = [[53.0, 83.0], [53.0, 84.0], [54.0, 84.0], [54.0, 83.0], [53.0, 83.0]];
$hole = [[53.4, 83.4], [53.4, 83.6], [53.6, 83.6], [53.6, 83.4], [53.4, 83.4]];
// A ring that does not touch the outer one: a separate contour.
$island = [[55.0, 85.0], [55.0, 86.0], [56.0, 86.0], [56.0, 85.0], [55.0, 85.0]];

$polygon = static fn (array ...$rings): Polygon => Polygon::make(array_map(
    static fn (array $ring): LineString => LineString::make(array_map(
        static fn (array $point): MagellanPoint => MagellanPoint::makeGeodetic($point[0], $point[1]),
        $ring
    )),
    $rings
));

it('is the handler of the multipolygon mode', function () {
    $handler = (new StateHandlerFactory)->getHandler(YandexMapMode::MultiPolygon);

    expect($handler)->toBeInstanceOf(MultiPolygonStateHandler::class);
});

it('formats a magellan multipolygon as a list of polygons', function () use ($polygon, $outer, $hole, $island) {
    $multiPolygon = MultiPolygon::make([
        $polygon($outer, $hole),
        $polygon($island),
    ]);

    expect((new MultiPolygonStateHandler)->formatMagellanState($multiPolygon))->toBe([
        [$outer, $hole],
        [$island],
    ]);
});

it('dehydrates a list of polygons into a magellan multipolygon', function () use ($outer, $hole, $island) {
    $multiPolygon = (new MultiPolygonStateHandler)->dehydrateMagellanState([
        [$outer, $hole],
        [$island],
    ]);

    expect($multiPolygon)->toBeInstanceOf(MultiPolygon::class)
        ->and($multiPolygon->getPolygons())->toHaveCount(2)
        ->and($multiPolygon->getPolygons()[0]->getLineStrings())->toHaveCount(2)
        ->and($multiPolygon->getPolygons()[1]->getLineStrings())->toHaveCount(1)
        ->and($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getLatitude())->toBe(56.0)
        ->and($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getLongitude())->toBe(86.0);
});

it('gives the same state after dehydrating and formatting a multipolygon', function (array $state) {
    $handler = new MultiPolygonStateHandler;

    expect($handler->formatMagellanState($handler->dehydrateMagellanState($state)))->toBe($state);
})->with([
    'one polygon' => [[[[[53.0, 83.0], [53.0, 84.0], [54.0, 84.0], [54.0, 83.0], [53.0, 83.0]]]]],
    'two polygons' => [[
        [[[53.0, 83.0], [53.0, 84.0], [54.0, 84.0], [54.0, 83.0], [53.0, 83.0]]],
        [[[55.0, 85.0], [55.0, 86.0], [56.0, 86.0], [56.0, 85.0], [55.0, 85.0]]],
    ]],
    'two polygons, a hole in the first one' => [[
        [
            [[53.0, 83.0], [53.0, 84.0], [54.0, 84.0], [54.0, 83.0], [53.0, 83.0]],
            [[53.4, 83.4], [53.4, 83.6], [53.6, 83.6], [53.6, 83.4], [53.4, 83.4]],
        ],
        [[[55.0, 85.0], [55.0, 86.0], [56.0, 86.0], [56.0, 85.0], [55.0, 85.0]]],
    ]],
    'no polygons' => [[]],
]);

it('keeps the order of polygons', function () use ($outer, $island) {
    $handler = new MultiPolygonStateHandler;
    $state = [[$island], [$outer]];

    expect($handler->formatMagellanState($handler->dehydrateMagellanState($state)))->toBe($state);
});

it('rejects a magellan state of another geometry type', function () use ($polygon, $outer) {
    $handler = new MultiPolygonStateHandler;

    $states = [
        MagellanPoint::makeGeodetic(53.35, 83.75),
        LineString::make([MagellanPoint::makeGeodetic(53.35, 83.75)]),
        $polygon($outer),
        [[$outer]],
        null,
    ];

    foreach ($states as $state) {
        expect(fn () => $handler->formatMagellanState($state))
            ->toThrow(InvalidArgumentException::class, 'State must be a ['.MultiPolygon::class.']');
    }
});

it('reads and writes the json state using custom attributes', function () {
    $handler = (new MultiPolygonStateHandler)->usingLatLngAttributes('latitude', 'longitude');

    $json = [
        [[
            ['latitude' => 53.0, 'longitude' => 83.0],
            ['latitude' => 53.0, 'longitude' => 84.0],
            ['latitude' => 54.0, 'longitude' => 84.0],
            ['latitude' => 53.0, 'longitude' => 83.0],
        ]],
        [[
            ['latitude' => 55.0, 'longitude' => 85.0],
            ['latitude' => 55.0, 'longitude' => 86.0],
            ['latitude' => 56.0, 'longitude' => 86.0],
            ['latitude' => 55.0, 'longitude' => 85.0],
        ]],
    ];
    $state = [
        [[[53.0, 83.0], [53.0, 84.0], [54.0, 84.0], [53.0, 83.0]]],
        [[[55.0, 85.0], [55.0, 86.0], [56.0, 86.0], [55.0, 85.0]]],
    ];

    expect($handler->formatJsonState($json))->toBe($state)
        ->and($handler->dehydrateJsonState($state))->toBe($json);
});

it('reads the json state using the default attributes', function () {
    $state = (new MultiPolygonStateHandler)->formatJsonState([
        [[['lat' => 53.0, 'lng' => 83.0], ['lat' => 53.0, 'lng' => 84.0]]],
        [[['lat' => 55.0, 'lng' => 85.0]]],
    ]);

    expect($state)->toBe([
        [[[53.0, 83.0], [53.0, 84.0]]],
        [[[55.0, 85.0]]],
    ]);
});

it('returns itself from usingLatLngAttributes', function () {
    $handler = new MultiPolygonStateHandler;

    expect($handler->usingLatLngAttributes('latitude', 'longitude'))->toBe($handler);
});
