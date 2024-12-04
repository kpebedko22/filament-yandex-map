<?php

namespace Kpebedko22\FilamentYandexMap\Forms\Components\Concerns;

use Kpebedko22\FilamentYandexMap\Support\Point;
use Closure;

trait HasCenter
{
    protected Point|Closure|array $center;

    public function center(Point|Closure|array $center): static
    {
        $this->center = $center;

        return $this;
    }

    public function getCenter(): Point
    {
        $rawValue = $this->evaluate($this->center);

        return $rawValue instanceof Point
            ? $rawValue
            : Point::makeFromArray($rawValue);
    }
}
