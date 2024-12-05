<?php

namespace Kpebedko22\FilamentYandexMap\DTOs\Placemarks;

use Kpebedko22\FilamentYandexMap\Enums\Placemarks\Badge;
use Kpebedko22\FilamentYandexMap\Enums\Placemarks\Color;
use Kpebedko22\FilamentYandexMap\Enums\Placemarks\Icon;

/**
 * @link https://yandex.ru/dev/jsapi-v2-1/doc/en/v2-1/ref/reference/option.presetStorage
 */
final readonly class PresetIcon
{
    public function __construct(
        public Icon $icon,
        public Color $color,
        public ?Badge $badge = null,
    ) {}

    public function __toString(): string
    {
        $badge = $this->badge?->value ?: '';

        return "islands#{$this->color->value}{$badge}{$this->icon->value}";
    }
}
