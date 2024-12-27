<?php

namespace Kpebedko22\FilamentYandexMap\Forms\Components\Concerns;

use Closure;
use Kpebedko22\FilamentYandexMap\DTOs\GeoObjects\GeoObjectProperties;

trait HasGeoObjectProperties
{
    protected GeoObjectProperties|Closure|array|null $geoObjectProperties = null;

    public function geoObjectProperties(GeoObjectProperties|Closure|array|null $properties): static
    {
        $this->geoObjectProperties = $properties;

        return $this;
    }

    public function getGeoObjectProperties(): ?array
    {
        $rawValue = $this->evaluate($this->geoObjectProperties);

        if ($rawValue instanceof GeoObjectProperties) {
            return $rawValue->toArray();
        }

        return $rawValue;
    }
}
