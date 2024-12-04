<?php

namespace Kpebedko22\FilamentYandexMap\Buttons;

use Illuminate\Contracts\Support\Arrayable;

final readonly class ButtonPosition implements Arrayable
{
    /**
     * Положение относительно нижнего края карты.
     */
    public int|string|null $bottom;

    /**
     * Положение относительно левого края карты.
     */
    public int|string|null $left;

    /**
     * Положение относительно правого края карты.
     */
    public int|string|null $right;

    /**
     * Положение относительно верхнего края карты.
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
