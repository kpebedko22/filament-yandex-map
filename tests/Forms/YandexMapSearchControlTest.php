<?php

use Kpebedko22\FilamentYandexMap\Enums\SearchControlSize;
use Kpebedko22\FilamentYandexMap\Forms\Components\YandexMap;

beforeEach(function () {
    config()->set('services.yandex_map', [
        'api_key' => 'key',
        'suggest_api_key' => 'suggest-key',
        'lang' => 'en_US',
        'center' => [53.35, 83.75],
        'zoom' => 12,
    ]);
});

it('keeps the large search control by default', function () {
    $component = YandexMap::make('area');

    expect($component->hasSearchControl())->toBeTrue()
        ->and($component->getSearchControlSize())->toBe(SearchControlSize::Large);
});

it('toggles the search control', function (?bool $condition, bool $expected) {
    $component = $condition === null
        ? YandexMap::make('area')->searchControl()
        : YandexMap::make('area')->searchControl($condition);

    expect($component->hasSearchControl())->toBe($expected);
})->with([
    'enabled without argument' => [null, true],
    'enabled' => [true, true],
    'disabled' => [false, false],
]);

it('evaluates the search control condition from a closure', function () {
    $component = YandexMap::make('area')->searchControl(static fn (): bool => false);

    expect($component->hasSearchControl())->toBeFalse();
});

it('sets the size of the search control', function (mixed $size, SearchControlSize $expected) {
    expect(YandexMap::make('area')->searchControlSize($size)->getSearchControlSize())->toBe($expected);
})->with([
    'enum' => [SearchControlSize::Small, SearchControlSize::Small],
    'string' => ['medium', SearchControlSize::Medium],
    'closure with enum' => [static fn (): SearchControlSize => SearchControlSize::Small, SearchControlSize::Small],
    'closure with string' => [static fn (): string => 'large', SearchControlSize::Large],
]);

it('rejects an unknown size of the search control', function () {
    YandexMap::make('area')->searchControlSize('huge')->getSearchControlSize();
})->throws(ValueError::class);
