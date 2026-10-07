<?php

namespace App\Filament\Resources\Regions;

use App\Filament\Resources\Regions\Pages\CreateRegion;
use App\Filament\Resources\Regions\Pages\EditRegion;
use App\Filament\Resources\Regions\Pages\ListRegions;
use App\Filament\Resources\Regions\Pages\ViewRegion;
use App\Models\Region;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Kpebedko22\FilamentYandexMap\Forms\Components\YandexMap;
use Kpebedko22\FilamentYandexMap\Infolists\Components\YandexMapEntry;
use UnitEnum;

final class RegionResource extends Resource
{
    protected static ?string $model = Region::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMap;

    protected static string|null|UnitEnum $navigationGroup = 'Geo Objects';

    protected static ?int $navigationSort = 4;

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->components([
                        YandexMapEntry::make('array_multipolygon')
                            ->usingMultiPolygon()
                            ->usingArray(),

                        YandexMapEntry::make('magellan_multipolygon')
                            ->usingMultiPolygon()
                            ->usingMagellan(),
                    ]),
            ]);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->components([
                        YandexMap::make('array_multipolygon')
                            ->usingMultiPolygon()
                            ->usingArray(),

                        YandexMap::make('magellan_multipolygon')
                            ->usingMultiPolygon()
                            ->usingMagellan(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRegions::route('/'),
            'create' => CreateRegion::route('/create'),
            'view' => ViewRegion::route('/{record}'),
            'edit' => EditRegion::route('/{record}/edit'),
        ];
    }
}
