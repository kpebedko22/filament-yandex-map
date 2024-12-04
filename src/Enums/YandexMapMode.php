<?php

namespace Kpebedko22\FilamentYandexMap\Enums;

enum YandexMapMode: string
{
    case Placemark = 'placemark';
    case Polyline = 'polyline';
    case Polygon = 'polygon';
}
