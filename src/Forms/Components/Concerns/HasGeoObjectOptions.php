<?php

namespace Kpebedko22\FilamentYandexMap\Forms\Components\Concerns;

use Kpebedko22\FilamentYandexMap\GeoObjects\GeoObjectOptions;
use Closure;

trait HasGeoObjectOptions
{
    protected GeoObjectOptions|Closure|array|null $geoObjectOptions = null;

    public function geoObjectOptions(GeoObjectOptions|Closure|array|null $options): static
    {
        $this->geoObjectOptions = $options;

        return $this;
    }

    public function getGeoObjectOptions(): ?array
    {
        $rawValue = $this->evaluate($this->geoObjectOptions);

        if ($rawValue instanceof GeoObjectOptions) {
            return $rawValue->toArray();
        }

        return $rawValue;
    }
}
