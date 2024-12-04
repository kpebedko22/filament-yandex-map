<?php

namespace Kpebedko22\FilamentYandexMap\Placemark;

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
