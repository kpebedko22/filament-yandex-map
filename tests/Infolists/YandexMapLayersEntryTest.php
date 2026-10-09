<?php

use Clickbar\Magellan\Data\Geometries\LineString;
use Clickbar\Magellan\Data\Geometries\Point as MagellanPoint;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Kpebedko22\FilamentYandexMap\DTOs\GeoObjects\Placemarks\PlacemarkProperties;
use Kpebedko22\FilamentYandexMap\DTOs\GeoObjects\Polygons\PolygonOptions;
use Kpebedko22\FilamentYandexMap\DTOs\GeoObjects\Polygons\PolygonProperties;
use Kpebedko22\FilamentYandexMap\DTOs\GeoObjects\Polylines\PolylineOptions;
use Kpebedko22\FilamentYandexMap\DTOs\GeoObjects\Polylines\PolylineProperties;
use Kpebedko22\FilamentYandexMap\Enums\YandexMapLang;
use Kpebedko22\FilamentYandexMap\Infolists\Components\YandexMapEntry;
use Kpebedko22\FilamentYandexMap\Infolists\Components\YandexMapLayersEntry;
use Kpebedko22\FilamentYandexMap\Tests\Support\Rings;
use Livewire\Component;

beforeEach(function () {
    config()->set('services.yandex_map', [
        'api_key' => 'key',
        'suggest_api_key' => 'suggest-key',
        'lang' => 'en_US',
        'center' => [53.35, 83.75],
        'zoom' => 12,
    ]);
});

// Puts the entry into a schema with a record, the way an infolist of a resource does.
$layersOf = static function (YandexMapLayersEntry $entry, array $attributes): array {
    $livewire = new class extends Component implements HasSchemas
    {
        use InteractsWithSchemas;

        public function render(): string
        {
            return '';
        }
    };

    $record = (new class extends Model {})->forceFill($attributes);

    // Reading the components attaches the entry to the schema.
    Schema::make($livewire)->record($record)->components([$entry])->getComponents();

    return $entry->getLayers();
};

// Magellan geometries need the application (config), so they are made inside the tests.
$route = static fn (): LineString => LineString::make([
    MagellanPoint::makeGeodetic(53.0, 83.0),
    MagellanPoint::makeGeodetic(54.0, 84.0),
]);

it('collects a layer for every geo-object', function () use ($layersOf, $route) {
    $layers = $layersOf(
        YandexMapLayersEntry::make('overview')->layers([
            YandexMapEntry::make('route')->usingPolyline()->usingMagellan(),
            YandexMapEntry::make('zone')->usingPolygon()->usingArray(),
            YandexMapEntry::make('regions')->usingMultiPolygon()->usingArray(),
            YandexMapEntry::make('depot')->usingPlacemark()->usingArray('lat', 'lng'),
        ]),
        [
            'route' => $route(),
            'zone' => [Rings::OUTER],
            'regions' => [[Rings::OUTER], [Rings::HOLE]],
            'depot' => ['lat' => 53.5, 'lng' => 83.5],
        ],
    );

    expect(array_column($layers, 'mode'))->toBe(['polyline', 'polygon', 'multipolygon', 'placemark'])
        ->and($layers[0]['state'])->toBe([[53.0, 83.0], [54.0, 84.0]])
        ->and($layers[1]['state'])->toBe([Rings::OUTER])
        ->and($layers[2]['state'])->toBe([[Rings::OUTER], [Rings::HOLE]])
        ->and($layers[3]['state'])->toBe([53.5, 83.5]);
});

it('keeps properties and options of every layer', function () use ($layersOf, $route) {
    $layers = $layersOf(
        YandexMapLayersEntry::make('overview')->layers([
            YandexMapEntry::make('route')
                ->usingPolyline(new PolylineProperties(hintContent: 'Route'), new PolylineOptions(strokeColor: '#e11d48'))
                ->usingMagellan(),
            YandexMapEntry::make('zone')
                ->usingPolygon(
                    new PolygonProperties(hintContent: 'Zone'),
                    new PolygonOptions(fillColor: '#2563eb55', strokeColor: '#2563eb'),
                )
                ->usingArray(),
            YandexMapEntry::make('depot')
                ->usingPlacemark(new PlacemarkProperties(hintContent: 'Depot'))
                ->usingArray('lat', 'lng'),
        ]),
        ['route' => $route(), 'zone' => [Rings::OUTER], 'depot' => ['lat' => 53.5, 'lng' => 83.5]],
    );

    expect($layers[0]['properties'])->toBe(['hintContent' => 'Route'])
        ->and($layers[0]['options']['strokeColor'])->toBe('#e11d48')
        ->and($layers[1]['properties'])->toBe(['hintContent' => 'Zone'])
        ->and($layers[1]['options']['strokeColor'])->toBe('#2563eb')
        ->and($layers[1]['options']['fillColor'])->toBe('#2563eb55')
        ->and($layers[2]['properties'])->toBe(['hintContent' => 'Depot'])
        ->and($layers[2]['options'])->toBeNull();
});

it('skips layers without a geo-object', function () use ($layersOf, $route) {
    $layers = $layersOf(
        YandexMapLayersEntry::make('overview')->layers([
            YandexMapEntry::make('route')->usingPolyline()->usingMagellan(),
            YandexMapEntry::make('zone')->usingPolygon()->usingArray(),
        ]),
        ['route' => $route(), 'zone' => null],
    );

    expect(array_column($layers, 'mode'))->toBe(['polyline']);
});

it('has no layers when none of them has a geo-object', function () use ($layersOf) {
    $layers = $layersOf(
        YandexMapLayersEntry::make('overview')->layers([
            YandexMapEntry::make('zone')->usingPolygon()->usingArray(),
        ]),
        ['zone' => null],
    );

    expect($layers)->toBe([]);
});

it('skips hidden layers', function () use ($layersOf, $route) {
    $layers = $layersOf(
        YandexMapLayersEntry::make('overview')->layers([
            YandexMapEntry::make('route')->usingPolyline()->usingMagellan()->hidden(),
            YandexMapEntry::make('zone')->usingPolygon()->usingArray(),
        ]),
        ['route' => $route(), 'zone' => [Rings::OUTER]],
    );

    expect(array_column($layers, 'mode'))->toBe(['polygon']);
});

it('skips components which are not layers', function () use ($layersOf) {
    $layers = $layersOf(
        YandexMapLayersEntry::make('overview')->layers([
            TextEntry::make('zone'),
            YandexMapEntry::make('zone')->usingPolygon()->usingArray(),
        ]),
        ['zone' => [Rings::OUTER]],
    );

    expect(array_column($layers, 'mode'))->toBe(['polygon']);
});

it('takes layers from a closure', function () use ($layersOf) {
    $layers = $layersOf(
        YandexMapLayersEntry::make('overview')->layers(fn () => [
            YandexMapEntry::make('zone')->usingPolygon()->usingArray(),
        ]),
        ['zone' => [Rings::OUTER]],
    );

    expect(array_column($layers, 'mode'))->toBe(['polygon']);
});

it('lets a layer get its state itself', function () use ($layersOf) {
    $layers = $layersOf(
        YandexMapLayersEntry::make('overview')->layers([
            YandexMapEntry::make('route')
                ->usingPolyline()
                ->getStateUsing(fn () => [[53.0, 83.0], [54.0, 84.0]]),
        ]),
        [],
    );

    expect($layers[0]['state'])->toBe([[53.0, 83.0], [54.0, 84.0]]);
});

it('takes the map settings from the config', function () {
    $entry = YandexMapLayersEntry::make('overview');

    expect($entry->getApiKey())->toBe('key')
        ->and($entry->getSuggestApiKey())->toBe('suggest-key')
        ->and($entry->getLang())->toBe(YandexMapLang::en_US)
        ->and($entry->getCenter()->toArray())->toBe([53.35, 83.75])
        ->and($entry->getZoom())->toBe(12)
        ->and($entry->getHeight())->toBe('600px');
});

it('prefers the map settings set on the entry', function () {
    $entry = YandexMapLayersEntry::make('overview')
        ->apiKey('own-key')
        ->lang(YandexMapLang::uk_UA)
        ->height('300px');

    expect($entry->getApiKey())->toBe('own-key')
        ->and($entry->getLang())->toBe(YandexMapLang::uk_UA)
        ->and($entry->getHeight())->toBe('300px');
});
