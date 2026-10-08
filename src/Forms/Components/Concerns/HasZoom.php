<?php

namespace Kpebedko22\FilamentYandexMap\Forms\Components\Concerns;

use Closure;

trait HasZoom
{
    public Closure|int|null $zoom = null;

    public function getZoom(): ?int
    {
        return $this->evaluate($this->zoom) ?? 12;
    }

    public function zoom(Closure|int|null $zoom): static
    {
        $this->zoom = $zoom;

        return $this;
    }
}
