<?php

namespace Kpebedko22\FilamentYandexMap\Forms\Components\Concerns;

use Closure;
use Kpebedko22\FilamentYandexMap\DTOs\GeoObjects\Polylines\PolylineOptions;
use Kpebedko22\FilamentYandexMap\DTOs\GeoObjects\Polylines\PolylineProperties;
use Kpebedko22\FilamentYandexMap\Enums\YandexMapMode;

trait HasPolyline
{
    use HasGeoObject;

    public function usingPolyline(
        PolylineProperties|Closure|array|null $properties = null,
        PolylineOptions|Closure|array|null    $options = null,
    ): static {
        $this->mode = YandexMapMode::Polyline;

        $this->properties = $properties;

        $this->options = $options;

        return $this;
    }
}
