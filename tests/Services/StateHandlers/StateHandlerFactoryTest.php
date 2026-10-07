<?php

use Kpebedko22\FilamentYandexMap\Enums\YandexMapMode;
use Kpebedko22\FilamentYandexMap\Services\StateHandlers\PlacemarkStateHandler;
use Kpebedko22\FilamentYandexMap\Services\StateHandlers\PolygonStateHandler;
use Kpebedko22\FilamentYandexMap\Services\StateHandlers\PolylineStateHandler;
use Kpebedko22\FilamentYandexMap\Services\StateHandlers\StateHandlerFactory;

it('returns the handler of the mode', function (YandexMapMode $mode, string $handler) {
    expect((new StateHandlerFactory)->getHandler($mode))->toBeInstanceOf($handler);
})->with([
    'placemark' => [YandexMapMode::Placemark, PlacemarkStateHandler::class],
    'polyline' => [YandexMapMode::Polyline, PolylineStateHandler::class],
    'polygon' => [YandexMapMode::Polygon, PolygonStateHandler::class],
]);

it('has a handler for every mode', function () {
    $factory = new StateHandlerFactory;

    foreach (YandexMapMode::cases() as $mode) {
        expect(fn () => $factory->getHandler($mode))->not->toThrow(InvalidArgumentException::class);
    }
});

it('returns a new handler on every call', function () {
    $factory = new StateHandlerFactory;

    $first = $factory->getHandler(YandexMapMode::Polygon)->usingLatLngAttributes('latitude', 'longitude');
    $second = $factory->getHandler(YandexMapMode::Polygon);

    expect($second)->not->toBe($first)
        ->and($second->formatJsonState([[['lat' => 53.0, 'lng' => 83.0]]]))->toBe([[[53.0, 83.0]]]);
});
