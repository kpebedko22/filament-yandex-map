<?php

namespace Kpebedko22\FilamentYandexMap\DTOs\Buttons;

use Illuminate\Contracts\Support\Arrayable;
use Kpebedko22\FilamentYandexMap\Enums\Buttons\ButtonFloat;
use Kpebedko22\FilamentYandexMap\Enums\Buttons\ButtonSize;

/**
 * @link https://yandex.ru/dev/jsapi-v2-1/doc/en/v2-1/ref/reference/control.Button#param-parameters.options
 */
final readonly class ButtonOptions implements Arrayable
{
    /**
     * Whether the button registers its size in the map margins
     * manager map.margin.Manager.
     */
    public ?bool $adjustMapMargin;

    /**
     * The side to which you want to align the control.
     */
    public ?ButtonFloat $float;

    /**
     * The priority of the control positioning.
     */
    public ?int $floatIndex;

    /**
     * The maximum width of the button in different states.
     */
    public int|array|null $maxWidth;

    /**
     * Object describing the position of a control.
     */
    public ?ButtonPosition $position;

    /**
     * Options describing button behavior.
     */
    public ?bool $selectOnClick;

    /**
     * Defines the appearance of the standard button layout.
     */
    public ?ButtonSize $size;

    /**
     * Indicates if the control is displayed.
     */
    public ?bool $visible;

    public function __construct(
        ?bool $adjustMapMargin = null,
        ?ButtonFloat $float = null,
        ?int $floatIndex = null,
        int|array|null $maxWidth = null,
        ?ButtonPosition $position = null,
        ?bool $selectOnClick = null,
        ?ButtonSize $size = null,
        ?bool $visible = null,
    ) {
        $this->adjustMapMargin = $adjustMapMargin;
        $this->float = $float;
        $this->floatIndex = $floatIndex;
        $this->maxWidth = $maxWidth;
        $this->position = $position;
        $this->selectOnClick = $selectOnClick;
        $this->size = $size;
        $this->visible = $visible;
    }

    public function toArray(): array
    {
        return collect([
            'adjustMapMargin' => $this->adjustMapMargin,
            'float' => $this->float,
            'floatIndex' => $this->floatIndex,
            'maxWidth' => $this->maxWidth,
            'position' => $this->position,
            'selectOnClick' => $this->selectOnClick,
            'size' => $this->size,
            'visible' => $this->visible,
        ])->whereNotNull()->toArray();
    }
}
