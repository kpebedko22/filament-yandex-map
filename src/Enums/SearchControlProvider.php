<?php

namespace Kpebedko22\FilamentYandexMap\Enums;

/**
 * @link https://yandex.ru/dev/jsapi-v2-1/doc/en/v2-1/ref/reference/control.SearchControl#param-parameters.options.provider
 */
enum SearchControlProvider: string
{
    /**
     * Toponyms (addresses, streets, cities) only.
     */
    case Map = 'yandex#map';

    /**
     * Toponyms and businesses.
     */
    case Search = 'yandex#search';
}
