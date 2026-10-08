<?php

namespace Kpebedko22\FilamentYandexMap\Forms\Components\Concerns;

use Closure;
use Kpebedko22\FilamentYandexMap\Enums\SearchControlSize;

trait HasSearchControl
{
    protected Closure|bool $hasSearchControl = true;

    protected Closure|SearchControlSize|string $searchControlSize = SearchControlSize::Large;

    public function searchControl(Closure|bool $condition = true): static
    {
        $this->hasSearchControl = $condition;

        return $this;
    }

    public function hasSearchControl(): bool
    {
        return (bool) $this->evaluate($this->hasSearchControl);
    }

    public function searchControlSize(Closure|SearchControlSize|string $size): static
    {
        $this->searchControlSize = $size;

        return $this;
    }

    public function getSearchControlSize(): SearchControlSize
    {
        $value = $this->evaluate($this->searchControlSize);

        return $value instanceof SearchControlSize
            ? $value
            : SearchControlSize::from($value);
    }
}
