<?php

namespace Kpebedko22\FilamentYandexMap\Forms\Components\Concerns;

use Kpebedko22\FilamentYandexMap\Enums\YandexMapLang;
use Closure;

trait HasLang
{
    protected YandexMapLang|Closure|string $lang;

    public function lang(YandexMapLang|Closure|string $lang): static
    {
        $this->lang = $lang;

        return $this;
    }

    public function getLang(): YandexMapLang
    {
        $rawValue = $this->evaluate($this->lang);

        return $rawValue instanceof YandexMapLang
            ? $rawValue
            : YandexMapLang::from($rawValue);
    }
}
