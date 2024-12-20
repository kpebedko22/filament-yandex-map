<?php

namespace Kpebedko22\FilamentYandexMap\Forms\Components\Concerns;

use Closure;
use Kpebedko22\FilamentYandexMap\Enums\YandexMapMode;

trait HasMode
{
    protected Closure|YandexMapMode|string $mode;

    public function mode(Closure|YandexMapMode|string $mode): static
    {
        $this->mode = $mode;

        return $this;
    }

    public function getMode(): YandexMapMode
    {
        $rawValue = $this->evaluate($this->mode);

        return $rawValue instanceof YandexMapMode
            ? $rawValue
            : YandexMapMode::from($rawValue);
    }
}
