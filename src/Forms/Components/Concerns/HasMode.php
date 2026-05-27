<?php

namespace Kpebedko22\FilamentYandexMap\Forms\Components\Concerns;

use Closure;
use Kpebedko22\FilamentYandexMap\Enums\YandexMapMode;

trait HasMode
{
    protected YandexMapMode|Closure|string $mode;

    public function getMode(): YandexMapMode
    {
        $value = $this->evaluate($this->mode);

        return $value instanceof YandexMapMode
            ? $value
            : YandexMapMode::from($value);
    }
}
