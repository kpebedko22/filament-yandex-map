<?php

use Kpebedko22\FilamentYandexMap\DTOs\GeoObjects\Polygons\PolygonOptions;
use Kpebedko22\FilamentYandexMap\DTOs\GeoObjects\Polygons\PolygonProperties;
use Kpebedko22\FilamentYandexMap\Enums\YandexMapMode;
use Kpebedko22\FilamentYandexMap\Forms\Components\YandexMap;
use Kpebedko22\FilamentYandexMap\Infolists\Components\YandexMapEntry;

beforeEach(function () {
    config()->set('services.yandex_map', [
        'api_key' => 'key',
        'suggest_api_key' => 'suggest-key',
        'lang' => 'en_US',
        'center' => [53.35, 83.75],
        'zoom' => 12,
    ]);
});

it('sets the mode of the geo-object', function (string $method, YandexMapMode $mode) {
    expect(YandexMap::make('area')->{$method}()->getMode())->toBe($mode)
        ->and(YandexMapEntry::make('area')->{$method}()->getMode())->toBe($mode);
})->with([
    'placemark' => ['usingPlacemark', YandexMapMode::Placemark],
    'polyline' => ['usingPolyline', YandexMapMode::Polyline],
    'polygon' => ['usingPolygon', YandexMapMode::Polygon],
    'multipolygon' => ['usingMultiPolygon', YandexMapMode::MultiPolygon],
]);

it('passes properties and options of a multipolygon to its polygons', function () {
    $component = YandexMap::make('area')->usingMultiPolygon(
        new PolygonProperties(hintContent: 'Area'),
        new PolygonOptions(fillColor: 'ff000099'),
    );

    expect($component->getGeoObjectProperties())->toBe(['hintContent' => 'Area'])
        ->and($component->getGeoObjectOptions()['fillColor'])->toBe('ff000099');
});
