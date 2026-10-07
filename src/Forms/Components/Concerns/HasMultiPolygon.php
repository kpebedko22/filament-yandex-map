<?php

namespace Kpebedko22\FilamentYandexMap\Forms\Components\Concerns;

use Closure;
use Kpebedko22\FilamentYandexMap\DTOs\GeoObjects\Polygons\PolygonOptions;
use Kpebedko22\FilamentYandexMap\DTOs\GeoObjects\Polygons\PolygonProperties;
use Kpebedko22\FilamentYandexMap\Enums\YandexMapMode;

trait HasMultiPolygon
{
    use HasGeoObject;

    /**
     * Properties and options are applied to every polygon of the multipolygon.
     */
    public function usingMultiPolygon(
        PolygonProperties|Closure|array|null $properties = null,
        PolygonOptions|Closure|array|null    $options = null,
    ): static {
        $this->mode = YandexMapMode::MultiPolygon;

        $this->properties = $properties;

        $this->options = $options;

        return $this;
    }
}
