<?php

namespace Kpebedko22\FilamentYandexMap\Forms\Components\Concerns;

use Closure;
use Kpebedko22\FilamentYandexMap\Enums\YandexMapLang;

trait HasLang
{
    protected YandexMapLang|Closure|string|null $lang = null;

    public function lang(YandexMapLang|Closure|string $lang): static
    {
        $this->lang = $lang;

        return $this;
    }

    public function getLang(): YandexMapLang
    {
        $rawValue = $this->evaluate($this->lang) ?: YandexMapLang::ru_RU;

        return $rawValue instanceof YandexMapLang
            ? $rawValue
            : YandexMapLang::from($rawValue);
    }
}
