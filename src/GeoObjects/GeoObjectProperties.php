<?php

namespace Kpebedko22\FilamentYandexMap\GeoObjects;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @link https://yandex.ru/dev/jsapi-v2-1/doc/en/v2-1/ref/reference/Placemark#param-properties
 */
final readonly class GeoObjectProperties implements Arrayable
{
    /**
     * Content of the geo object's icon.
     */
    public ?string $iconContent;

    /**
     * Caption for the geo object's icon.
     */
    public ?string $iconCaption;

    /**
     * Content of the geo object's popup hint.
     */
    public ?string $hintContent;

    /**
     * Content of the geo object's balloon.
     */
    public ?string $balloonContent;

    /**
     * Content of the geo object balloon title.
     */
    public ?string $balloonContentHeader;

    /**
     * Content of the main part of the geo object's balloon.
     */
    public ?string $balloonContentBody;

    /**
     * Content of the lower part of the geo object's balloon.
     */
    public ?string $balloonContentFooter;

    public function __construct(
        ?string $iconContent = null,
        ?string $iconCaption = null,
        ?string $hintContent = null,
        ?string $balloonContent = null,
        ?string $balloonContentHeader = null,
        ?string $balloonContentBody = null,
        ?string $balloonContentFooter = null
    ) {
        $this->iconContent = $iconContent;
        $this->iconCaption = $iconCaption;
        $this->hintContent = $hintContent;
        $this->balloonContent = $balloonContent;
        $this->balloonContentHeader = $balloonContentHeader;
        $this->balloonContentBody = $balloonContentBody;
        $this->balloonContentFooter = $balloonContentFooter;
    }

    public function toArray(): array
    {
        return collect([
            'iconContent' => $this->iconContent,
            'iconCaption' => $this->iconCaption,
            'hintContent' => $this->hintContent,
            'balloonContent' => $this->balloonContent,
            'balloonContentHeader' => $this->balloonContentHeader,
            'balloonContentBody' => $this->balloonContentBody,
            'balloonContentFooter' => $this->balloonContentFooter,
        ])->filter()->toArray();
    }
}
