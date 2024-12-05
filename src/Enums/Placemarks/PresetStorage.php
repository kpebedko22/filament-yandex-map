<?php

namespace Kpebedko22\FilamentYandexMap\Enums\Placemarks;

/**
 * @link https://yandex.ru/dev/jsapi-v2-1/doc/en/v2-1/ref/reference/option.presetStorage
 */
enum PresetStorage: string
{
    case Icon = 'islands#icon';
    case DotIcon = 'islands#dotIcon';
    case CircleIcon = 'islands#circleIcon';
    case CircleDotIcon = 'islands#circleDotIcon';
    case GeolocationIcon = 'islands#geolocationIcon';
}
