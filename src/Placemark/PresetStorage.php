<?php

namespace Kpebedko22\FilamentYandexMap\Placemark;

/**
 * @link https://yandex.ru/dev/jsapi-v2-1/doc/ru/v2-1/ref/reference/option.presetStorage
 */
enum PresetStorage: string
{
    case Icon = 'islands#icon';
    case DotIcon = 'islands#dotIcon';
    case CircleIcon = 'islands#circleIcon';
    case CircleDotIcon = 'islands#circleDotIcon';
    case GeolocationIcon = 'islands#geolocationIcon';
}
