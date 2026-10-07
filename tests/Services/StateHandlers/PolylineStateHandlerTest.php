<?php

use Clickbar\Magellan\Data\Geometries\LineString;
use Clickbar\Magellan\Data\Geometries\Point as MagellanPoint;
use Clickbar\Magellan\Data\Geometries\Polygon;
use Kpebedko22\FilamentYandexMap\Services\StateHandlers\PolylineStateHandler;

it('formats a magellan line string as a list of [lat, lng]', function () {
    $line = LineString::make([
        MagellanPoint::makeGeodetic(53.35, 83.75),
        MagellanPoint::makeGeodetic(53.4, 83.8),
        MagellanPoint::makeGeodetic(53.45, 83.9),
    ]);

    expect((new PolylineStateHandler)->formatMagellanState($line))->toBe([
        [53.35, 83.75],
        [53.4, 83.8],
        [53.45, 83.9],
    ]);
});

it('dehydrates a list of [lat, lng] into a magellan line string', function () {
    $line = (new PolylineStateHandler)->dehydrateMagellanState([
        [53.35, 83.75],
        [53.4, 83.8],
    ]);

    expect($line)->toBeInstanceOf(LineString::class)
        ->and($line->getPoints())->toHaveCount(2)
        ->and($line->getPoints()[1]->getLatitude())->toBe(53.4)
        ->and($line->getPoints()[1]->getLongitude())->toBe(83.8);
});

it('gives the same state after dehydrating and formatting a magellan line string', function () {
    $handler = new PolylineStateHandler;
    $state = [[53.35, 83.75], [53.4, 83.8], [53.45, 83.9]];

    expect($handler->formatMagellanState($handler->dehydrateMagellanState($state)))->toBe($state);
});

it('keeps the order of points', function () {
    $handler = new PolylineStateHandler;
    $state = [[3.0, 1.0], [1.0, 3.0], [2.0, 2.0]];

    expect($handler->formatMagellanState($handler->dehydrateMagellanState($state)))->toBe($state);
});

it('rejects a magellan state of another geometry type', function () {
    $handler = new PolylineStateHandler;

    $states = [
        MagellanPoint::makeGeodetic(53.35, 83.75),
        Polygon::make([LineString::make([MagellanPoint::makeGeodetic(53.35, 83.75)])]),
        [[53.35, 83.75]],
        null,
    ];

    foreach ($states as $state) {
        expect(fn () => $handler->formatMagellanState($state))
            ->toThrow(InvalidArgumentException::class, 'State must be a ['.LineString::class.']');
    }
});

it('reads and writes the json state using custom attributes', function () {
    $handler = (new PolylineStateHandler)->usingLatLngAttributes('latitude', 'longitude');

    expect($handler->formatJsonState([
        ['latitude' => 53.35, 'longitude' => 83.75],
        ['latitude' => 53.4, 'longitude' => 83.8],
    ]))->toBe([[53.35, 83.75], [53.4, 83.8]])
        ->and($handler->dehydrateJsonState([[53.35, 83.75], [53.4, 83.8]]))->toBe([
            ['latitude' => 53.35, 'longitude' => 83.75],
            ['latitude' => 53.4, 'longitude' => 83.8],
        ]);
});

it('reads the json state using the default attributes', function () {
    $state = (new PolylineStateHandler)->formatJsonState([
        ['lat' => 53.35, 'lng' => 83.75],
        ['lat' => 53.4, 'lng' => 83.8],
    ]);

    expect($state)->toBe([[53.35, 83.75], [53.4, 83.8]]);
});

it('returns itself from usingLatLngAttributes', function () {
    $handler = new PolylineStateHandler;

    expect($handler->usingLatLngAttributes('latitude', 'longitude'))->toBe($handler);
});
