<?php

namespace Kpebedko22\FilamentYandexMap\Buttons;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @link https://yandex.ru/dev/jsapi-v2-1/doc/en/v2-1/ref/reference/control.Button#param-parameters.data
 */
final readonly class ButtonData implements Arrayable
{
    /**
     * Button contents in HTML format.
     */
    public ?string $content;

    /**
     * URL of the button icon. The standard layout of a button is
     * designed for the icon size 16x16 pixels.
     */
    public ?string $image;

    /**
     * Text of the popup hint that appears when the mouse cursor
     * hovers over the button.
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
