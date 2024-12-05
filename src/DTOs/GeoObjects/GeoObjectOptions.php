<?php

namespace Kpebedko22\FilamentYandexMap\DTOs\GeoObjects;

use Illuminate\Contracts\Support\Arrayable;
use Kpebedko22\FilamentYandexMap\DTOs\Placemarks\PresetIcon;
use Kpebedko22\FilamentYandexMap\Enums\Placemarks\PresetStorage;

/**
 * TODO: Add other options.
 *
 * @link https://yandex.ru/dev/jsapi-v2-1/doc/en/v2-1/ref/reference/Placemark#param-options
 */
final readonly class GeoObjectOptions implements Arrayable
{
    /**
     * Key for the placemark's preset options.
     */
    public PresetStorage|PresetIcon|string|null $preset;

    /**
     * The color of the placemark icon.
     */
    public ?string $iconColor;

    public function __construct(
        PresetStorage|PresetIcon|string|null $preset = null,
        ?string $iconColor = null,
    ) {
        $this->preset = $preset;
        $this->iconColor = $iconColor;
    }

    public function toArray(): array
    {
        return collect([
            'preset' => $this->preset,
            'iconColor' => $this->iconColor,
        ])->whereNotNull()->toArray();
    }
}
