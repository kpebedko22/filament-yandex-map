<?php

namespace Kpebedko22\FilamentYandexMap\Buttons;

use Illuminate\Contracts\Support\Arrayable;
use Kpebedko22\FilamentYandexMap\Enums\Buttons\ButtonFloat;
use Kpebedko22\FilamentYandexMap\Enums\Buttons\ButtonSize;

final readonly class ButtonOptions implements Arrayable
{
    /**
     * Регистрирует ли кнопка свои размеры в менеджере отступов карты map.margin.Manager.
     */
    public ?bool $adjustMapMargin;

    /**
     * Сторона, по которой нужно выравнивать элемент управления.
     * Может принимать три значения: "left", "right" или "none".
     * При значении "left" или "right" элементы управления выстраиваются
     * друг за другом, начиная от левого или правого края карты соответственно.
     * При значении "none" элементы управления позиционируется только
     * по значениям опций left, right, bottom, top относительно границ карты.
     * Также смотрите описание опции position.
     */
    public ?ButtonFloat $float;

    /**
     * Приоритет расположения элемента управления. Элемент с максимальным
     * приоритетом находится ближе к указанному в свойстве float краю
     * карты. Не работает при float = "none".
     */
    public ?int $floatIndex;

    /**
     * Максимальная ширина кнопки в различных состояниях.
     * Если задано число, то считается, что кнопка имеет одинаковые
     * максимальные размеры во всех состояниях. Если задан массив,
     * то он будет трактоваться как максимальная ширина кнопки в различных
     * состояниях - от меньшего к большему. Количество доступных состояний
     * задается в экземпляре класса control.Manager через опцию states.
     * Этот класс обычно является полем Map.controls.
     * По умолчанию у элементов управления есть три
     * состояния - ['small', 'medium', 'large'].
     */
    public int|array|null $maxWidth;

    /**
     * Объект, описывающий позицию элемента управления.
     * При указании опции position значение опции float автоматически
     * трактуется как "none".
     */
    public ?ButtonPosition $position;

    /**
     * Опция, описывающая поведение кнопки.
     * - true — кнопка становится "нажатой" после клика, то есть меняется ее
     * внешний вид и значение поля control.Button.state
     * устанавливается в 'selected';
     * - false - кнопка не меняет свой внешний вид и состояние после клика
     * на нее.
     */
    public ?bool $selectOnClick;

    /**
     * Параметр, отвечающий за внешний вид стандартного макета кнопки.
     */
    public ?ButtonSize $size;

    /**
     * Признак того, что элемент управления отображается.
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
