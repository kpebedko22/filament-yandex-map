<?php

namespace Kpebedko22\FilamentYandexMap\Forms\Components\Concerns;

use Closure;

trait HasHeight
{
    protected Closure|string $height;

    public function height(Closure|string $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function getHeight(): string
    {
        return $this->evaluate($this->height);
    }
}
