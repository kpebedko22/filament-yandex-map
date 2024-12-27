<?php

namespace Kpebedko22\FilamentYandexMap\DTOs\Buttons;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Object describing the position of a control.
 *
 * @link https://yandex.ru/dev/jsapi-v2-1/doc/en/v2-1/ref/reference/control.Button#param-parameters.options.position
 */
final readonly class ButtonPosition implements Arrayable
{
    /**
     * Position relative to the bottom edge of the map.
     */
    public int|string|null $bottom;

    /**
     * Position relative to the left edge of the map.
     */
    public int|string|null $left;

    /**
     * Position relative to the right edge of the map.
     */
    public int|string|null $right;

    /**
     * Position relative to the top edge of the map.
     */
    public int|string|null $top;

    public function __construct(
        int|string|null $bottom = null,
        int|string|null $left = null,
        int|string|null $right = null,
        int|string|null $top = null
    ) {
        $this->bottom = $bottom;
        $this->left = $left;
        $this->right = $right;
        $this->top = $top;
    }

    public function toArray(): array
    {
        return collect([
            'bottom' => $this->bottom,
            'left' => $this->left,
            'right' => $this->right,
            'top' => $this->top,
        ])->whereNotNull()->toArray();
    }
}
