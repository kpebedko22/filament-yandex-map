<?php

namespace Kpebedko22\FilamentYandexMap\GeoObjects;

use Kpebedko22\FilamentYandexMap\Placemark\PresetIcon;
use Kpebedko22\FilamentYandexMap\Placemark\PresetStorage;
use Illuminate\Contracts\Support\Arrayable;

/**
 * TODO: Добавить все остальные опции
 *
 * @link https://yandex.ru/dev/jsapi-v2-1/doc/ru/v2-1/ref/reference/Placemark#param-options
 */
final readonly class GeoObjectOptions implements Arrayable
{
    /**
     * Ключ предустановленных стилей метки
     */
    public PresetStorage|PresetIcon|string|null $preset;

    /**
     * Цвет иконки метки
     */
    public ?string $iconColor;

    public function __construct(
        PresetStorage|PresetIcon|string|null $preset = null,
        ?string                              $iconColor = null,
    ) {
        $this->preset = $preset;
        $this->iconColor = $iconColor;
    }

    public function toArray(): array
    {
        return collect([
            'preset' => $this->preset,
            'iconColor' => $this->iconColor,
        ])->filter()->toArray();
    }
}
