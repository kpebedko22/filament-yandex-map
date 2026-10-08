<?php

use Kpebedko22\FilamentYandexMap\Enums\YandexMapLang;
use Kpebedko22\FilamentYandexMap\Forms\Components\YandexMap;
use Kpebedko22\FilamentYandexMap\Infolists\Components\YandexMapEntry;
use Kpebedko22\FilamentYandexMap\ValueObjects\Point;

dataset('components', [
    'form' => [YandexMap::class],
    'infolist' => [YandexMapEntry::class],
]);

it('falls back to defaults when the config is missing', function (string $component) {
    config()->set('services.yandex_map', null);

    $map = $component::make('area');

    expect($map->getApiKey())->toBe('')
        ->and($map->getSuggestApiKey())->toBe('')
        ->and($map->getLang())->toBe(YandexMapLang::ru_RU)
        ->and($map->getCenter()->toArray())->toBe([53.35, 83.75])
        ->and($map->getZoom())->toBe(12);
})->with('components');

it('falls back to defaults when the config values are null', function (string $component) {
    config()->set('services.yandex_map', [
        'api_key' => env('YANDEX_MAP_API_KEY_UNSET'),
        'suggest_api_key' => null,
        'lang' => null,
        'center' => null,
        'zoom' => null,
    ]);

    $map = $component::make('area');

    expect($map->getApiKey())->toBe('')
        ->and($map->getSuggestApiKey())->toBe('')
        ->and($map->getLang())->toBe(YandexMapLang::ru_RU)
        ->and($map->getCenter()->toArray())->toBe([53.35, 83.75])
        ->and($map->getZoom())->toBe(12);
})->with('components');

it('treats an empty lang in the config as not set', function (string $component) {
    config()->set('services.yandex_map.lang', '');

    expect($component::make('area')->getLang())->toBe(YandexMapLang::ru_RU);
})->with('components');

it('takes values from the config', function (string $component) {
    config()->set('services.yandex_map', [
        'api_key' => 'key',
        'suggest_api_key' => 'suggest-key',
        'lang' => 'en_US',
        'center' => [55.75, 37.62],
        'zoom' => 8,
    ]);

    $map = $component::make('area');

    expect($map->getApiKey())->toBe('key')
        ->and($map->getSuggestApiKey())->toBe('suggest-key')
        ->and($map->getLang())->toBe(YandexMapLang::en_US)
        ->and($map->getCenter()->toArray())->toBe([55.75, 37.62])
        ->and($map->getZoom())->toBe(8);
})->with('components');

it('prefers values set on the component over the config and the defaults', function (string $component) {
    config()->set('services.yandex_map', null);

    $map = $component::make('area')
        ->apiKey('own-key')
        ->suggestApiKey('own-suggest-key')
        ->lang(YandexMapLang::uk_UA)
        ->center(new Point(50.45, 30.52))
        ->zoom(5);

    expect($map->getApiKey())->toBe('own-key')
        ->and($map->getSuggestApiKey())->toBe('own-suggest-key')
        ->and($map->getLang())->toBe(YandexMapLang::uk_UA)
        ->and($map->getCenter()->toArray())->toBe([50.45, 30.52])
        ->and($map->getZoom())->toBe(5);
})->with('components');

it('falls back to defaults when a closure returns null', function (string $component) {
    $map = $component::make('area')
        ->apiKey(fn () => null)
        ->suggestApiKey(fn () => null)
        ->lang(fn () => null)
        ->center(fn () => null)
        ->zoom(fn () => null);

    expect($map->getApiKey())->toBe('')
        ->and($map->getSuggestApiKey())->toBe('')
        ->and($map->getLang())->toBe(YandexMapLang::ru_RU)
        ->and($map->getCenter()->toArray())->toBe([53.35, 83.75])
        ->and($map->getZoom())->toBe(12);
})->with('components');
