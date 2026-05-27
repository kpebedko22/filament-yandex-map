<?php

namespace Kpebedko22\FilamentYandexMap\Forms\Components\Concerns;

use Closure;
use Kpebedko22\FilamentYandexMap\DTOs\GeoObjects\Placemarks\PlacemarkOptions;
use Kpebedko22\FilamentYandexMap\DTOs\GeoObjects\Placemarks\PlacemarkProperties;
use Kpebedko22\FilamentYandexMap\Enums\YandexMapMode;

trait HasPlacemark
{
    use HasGeoObject;

    public function usingPlacemark(
        PlacemarkProperties|Closure|array|null $properties = null,
        PlacemarkOptions|Closure|array|null    $options = null,
    ): static {
        $this->mode = YandexMapMode::Placemark;

        $this->properties = $properties;

        $this->options = $options;

        return $this;
    }
}
