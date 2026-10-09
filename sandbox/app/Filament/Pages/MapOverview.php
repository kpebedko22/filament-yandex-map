<?php

namespace App\Filament\Pages;

use App\Models\Area;
use App\Models\Region;
use App\Models\Route;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Kpebedko22\FilamentYandexMap\DTOs\GeoObjects\Polygons\PolygonOptions;
use Kpebedko22\FilamentYandexMap\DTOs\GeoObjects\Polygons\PolygonProperties;
use Kpebedko22\FilamentYandexMap\DTOs\GeoObjects\Polylines\PolylineOptions;
use Kpebedko22\FilamentYandexMap\DTOs\GeoObjects\Polylines\PolylineProperties;
use Kpebedko22\FilamentYandexMap\Infolists\Components\YandexMapEntry;
use Kpebedko22\FilamentYandexMap\Infolists\Components\YandexMapLayersEntry;
use UnitEnum;

final class MapOverview extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;

    protected static string|null|UnitEnum $navigationGroup = 'Geo Objects';

    protected static ?int $navigationSort = 5;

    protected static ?string $title = 'Map overview';

    public function content(Schema $schema): Schema
    {
        return $schema
            ->record($this->getOverview())
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->components([
                        YandexMapLayersEntry::make('overview')
                            ->hiddenLabel()
                            ->layers([
                                YandexMapEntry::make('route')
                                    ->usingPolyline(
                                        new PolylineProperties(hintContent: 'Route'),
                                        new PolylineOptions(strokeColor: '#e11d48', strokeWidth: 4),
                                    )
                                    ->usingMagellan(),

                                YandexMapEntry::make('zone')
                                    ->usingPolygon(
                                        new PolygonProperties(hintContent: 'Zone'),
                                        new PolygonOptions(fillColor: '#2563eb55', strokeColor: '#2563eb', strokeWidth: 2),
                                    )
                                    ->usingMagellan(),

                                YandexMapEntry::make('regions')
                                    ->usingMultiPolygon(
                                        new PolygonProperties(hintContent: 'Regions'),
                                        new PolygonOptions(fillColor: '#16a34a55', strokeColor: '#16a34a', strokeWidth: 2),
                                    )
                                    ->usingArray(),
                            ]),
                    ]),
            ]);
    }

    /**
     * The layers read their geo-objects from one record, so the sandbox
     * collects a route, a zone and regions into a record which is not stored.
     */
    private function getOverview(): Model
    {
        return (new class extends Model {})->forceFill([
            'route' => Route::query()->first()?->magellan_line,
            'zone' => Area::query()->first()?->magellan_polygon,
            'regions' => Region::query()->first()?->array_multipolygon,
        ]);
    }
}
