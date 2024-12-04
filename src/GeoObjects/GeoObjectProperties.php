<?php

namespace Kpebedko22\FilamentYandexMap\GeoObjects;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Данные метки.
 *
 * @link https://yandex.ru/dev/jsapi-v2-1/doc/ru/v2-1/ref/reference/Placemark#param-properties
 */
final readonly class GeoObjectProperties implements Arrayable
{
    /**
     * Содержимое иконки геообъекта
     */
    public ?string $iconContent;

    /**
     * Подпись иконки геообъекта
     */
    public ?string $iconCaption;

    /**
     * Содержимое всплывающей подсказки геообъекта
     */
    public ?string $hintContent;

    /**
     * Содержимое балуна геообъекта.
     *
     * Поле balloonContent является кратким обозначением для поля
     * balloonContentBody, но при одновременном задании balloonContentBody
     * более приоритетен. Также вы можете дополнить данные метки своими
     * собственными полями и использовать их везде, где это возможно.
     * Например в макете метки или макете балуна.
     */
    public ?string $balloonContent;

    /**
     * Содержимое заголовка балуна геообъекта
     */
    public ?string $balloonContentHeader;

    /**
     * Содержимое основой части балуна геообъекта
     */
    public ?string $balloonContentBody;

    /**
     * Содержимое нижней части балуна геообъекта
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
