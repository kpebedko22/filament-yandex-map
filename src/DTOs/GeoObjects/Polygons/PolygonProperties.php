<?php

namespace Kpebedko22\FilamentYandexMap\DTOs\GeoObjects\Polygons;

use Kpebedko22\FilamentYandexMap\DTOs\GeoObjects\Contracts\Properties;

/**
 * @link https://yandex.ru/dev/jsapi-v2-1/doc/en/v2-1/ref/reference/Polygon#param-properties
 */
readonly class PolygonProperties implements Properties
{
    public function __construct(
        public ?string $hintContent = null,
        public ?string $balloonContent = null,
        public ?string $balloonContentHeader = null,
        public ?string $balloonContentBody = null,
        public ?string $balloonContentFooter = null,

    ) {
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return array_filter([
            'hintContent' => $this->hintContent,
            'balloonContent' => $this->balloonContent,
            'balloonContentHeader' => $this->balloonContentHeader,
            'balloonContentBody' => $this->balloonContentBody,
            'balloonContentFooter' => $this->balloonContentFooter,
        ], static function (?string $value): bool {
            return !is_null($value);
        });
    }
}
