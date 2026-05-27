<?php

namespace Kpebedko22\FilamentYandexMap\DTOs\GeoObjects\Placemarks;

use Kpebedko22\FilamentYandexMap\DTOs\GeoObjects\Contracts\Properties;

/**
 * @link https://yandex.ru/dev/jsapi-v2-1/doc/en/v2-1/ref/reference/Placemark#param-properties
 */
readonly class PlacemarkProperties implements Properties
{
    /**
     * @param string|null $iconContent Content of the geo object's icon.
     * @param string|null $iconCaption Caption for the geo object's icon.
     * @param string|null $hintContent Content of the geo object's popup hint.
     * @param string|null $balloonContent Content of the geo object's balloon.
     * @param string|null $balloonContentHeader Content of the geo object balloon title.
     * @param string|null $balloonContentBody Content of the main part of the geo object's balloon.
     * @param string|null $balloonContentFooter Content of the lower part of the geo object's balloon.
     */
    public function __construct(
        public ?string $iconContent = null,
        public ?string $iconCaption = null,
        public ?string $hintContent = null,
        public ?string $balloonContent = null,
        public ?string $balloonContentHeader = null,
        public ?string $balloonContentBody = null,
        public ?string $balloonContentFooter = null
    ) {
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return array_filter([
            'iconContent' => $this->iconContent,
            'iconCaption' => $this->iconCaption,
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
