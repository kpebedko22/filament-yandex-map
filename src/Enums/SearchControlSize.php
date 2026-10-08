<?php

namespace Kpebedko22\FilamentYandexMap\Enums;

/**
 * @link https://yandex.ru/dev/jsapi-v2-1/doc/en/v2-1/ref/reference/control.SearchControl#param-parameters.options.size
 */
enum SearchControlSize: string
{
    case Small = 'small';
    case Medium = 'medium';
    case Large = 'large';
}
