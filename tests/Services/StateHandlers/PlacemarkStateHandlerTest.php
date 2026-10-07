<?php

use Clickbar\Magellan\Data\Geometries\LineString;
use Clickbar\Magellan\Data\Geometries\Point as MagellanPoint;
use Kpebedko22\FilamentYandexMap\Services\StateHandlers\PlacemarkStateHandler;

it('formats a magellan point as [lat, lng]', function () {
    $state = (new PlacemarkStateHandler)
        ->formatMagellanState(MagellanPoint::makeGeodetic(53.35, 83.75));

    expect($state)->toBe([53.35, 83.75]);
});

it('dehydrates [lat, lng] into a magellan point', function () {
    $point = (new PlacemarkStateHandler)->dehydrateMagellanState([53.35, 83.75]);

    expect($point)
        ->toBeInstanceOf(MagellanPoint::class)
        ->and($point->getLatitude())->toBe(53.35)
        ->and($point->getLongitude())->toBe(83.75);
});

it('gives the same state after formatting and dehydrating a magellan point', function () {
    $handler = new PlacemarkStateHandler;
    $point = MagellanPoint::makeGeodetic(-33.86, 151.21);

    $dehydrated = $handler->dehydrateMagellanState($handler->formatMagellanState($point));

    expect($dehydrated->getLatitude())->toBe($point->getLatitude())
        ->and($dehydrated->getLongitude())->toBe($point->getLongitude());
});

it('keeps zero coordinates', function () {
    $handler = new PlacemarkStateHandler;

    expect($handler->formatMagellanState(MagellanPoint::makeGeodetic(0.0, 0.0)))->toBe([0.0, 0.0])
        ->and($handler->dehydrateJsonState([0, 0]))->toBe(['lat' => 0.0, 'lng' => 0.0]);
});

it('rejects a magellan state of another geometry type', function () {
    $handler = new PlacemarkStateHandler;

    $states = [
        LineString::make([MagellanPoint::makeGeodetic(53.35, 83.75)]),
        [53.35, 83.75],
        null,
    ];

    foreach ($states as $state) {
        expect(fn () => $handler->formatMagellanState($state))
            ->toThrow(InvalidArgumentException::class, 'State must be a ['.MagellanPoint::class.']');
    }
});

it('reads the json state using the default attributes', function () {
    $state = (new PlacemarkStateHandler)
        ->formatJsonState(['lat' => 53.35, 'lng' => 83.75]);

    expect($state)->toBe([53.35, 83.75]);
});

it('reads and writes the json state using custom attributes', function () {
    $handler = (new PlacemarkStateHandler)->usingLatLngAttributes('latitude', 'longitude');

    expect($handler->formatJsonState(['latitude' => 53.35, 'longitude' => 83.75]))
        ->toBe([53.35, 83.75])
        ->and($handler->dehydrateJsonState([53.35, 83.75]))
        ->toBe(['latitude' => 53.35, 'longitude' => 83.75]);
});

it('reads and writes the json state as a list using numeric attributes', function () {
    $handler = (new PlacemarkStateHandler)->usingLatLngAttributes('0', '1');

    expect($handler->formatJsonState([53.35, 83.75]))->toBe([53.35, 83.75])
        ->and($handler->dehydrateJsonState([53.35, 83.75]))->toBe([53.35, 83.75]);
});

it('returns itself from usingLatLngAttributes', function () {
    $handler = new PlacemarkStateHandler;

    expect($handler->usingLatLngAttributes('latitude', 'longitude'))->toBe($handler);
});

it('requires both attributes in the json state', function (array $state, string $message) {
    expect(fn () => (new PlacemarkStateHandler)->formatJsonState($state))
        ->toThrow(InvalidArgumentException::class, $message);
})->with([
    'no latitude' => [['lng' => 83.75], 'Key [lat] for latitude in array is required.'],
    'no longitude' => [['lat' => 53.35], 'Key [lng] for longitude in array is required.'],
]);
