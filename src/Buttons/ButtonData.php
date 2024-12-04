<?php

namespace Kpebedko22\FilamentYandexMap\Buttons;

use Illuminate\Contracts\Support\Arrayable;

final readonly class ButtonData implements Arrayable
{
    /**
     * Содержимое кнопки в виде HTML.
     */
    public ?string $content;

    /**
     * URL иконки кнопки. Стандартный макет кнопки рассчитан на иконку размером 16x16 пикселей.
     */
    public ?string $image;

    /**
     * Текст всплывающей подсказки, которая появляется при наведении на кнопку курсора мыши.
     */
    public ?string $title;

    public function __construct(
        ?string $content = null,
        ?string $image = null,
        ?string $title = null,
    ) {
        $this->content = $content;
        $this->image = $image;
        $this->title = $title;
    }

    public function toArray(): array
    {
        return collect([
            'content' => $this->content,
            'image' => $this->image,
            'title' => $this->title,
        ])->whereNotNull()->toArray();
    }
}
