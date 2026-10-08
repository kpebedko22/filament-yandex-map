<?php

namespace Kpebedko22\FilamentYandexMap\Forms\Components\Concerns;

use Closure;
use Kpebedko22\FilamentYandexMap\ValueObjects\Point;

trait HasCenter
{
    protected Point|Closure|array|null $center = null;

    public function center(Point|Closure|array $center): static
    {
        $this->center = $center;

        return $this;
    }

    public function getCenter(): Point
    {
        $rawValue = $this->evaluate($this->center) ?? [53.35, 83.75];

        return $rawValue instanceof Point
            ? $rawValue
            : Point::makeFromArray($rawValue);
    }
}
