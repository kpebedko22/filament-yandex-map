<?php

namespace Kpebedko22\FilamentYandexMap\Forms\Components\Concerns;

use Closure;
use Kpebedko22\FilamentYandexMap\DTOs\GeoObjects\Contracts\Options;
use Kpebedko22\FilamentYandexMap\DTOs\GeoObjects\Contracts\Properties;

trait HasGeoObject
{
    protected Properties|Closure|array|null $properties = null;

    protected Options|Closure|array|null $options = null;

    public function geoObjectProperties(Properties|Closure|array|null $properties): static
    {
        $this->properties = $properties;

        return $this;
    }

    public function geoObjectOptions(Options|Closure|array|null $options): static
    {
        $this->options = $options;

        return $this;
    }

    public function getGeoObjectProperties(): ?array
    {
        $value = $this->evaluate($this->properties);

        if ($value instanceof Properties) {
            return $value->toArray();
        }

        return $value;
    }

    public function getGeoObjectOptions(): ?array
    {
        $value = $this->evaluate($this->options);

        if ($value instanceof Options) {
            return $value->toArray();
        }

        return $value;
    }
}
