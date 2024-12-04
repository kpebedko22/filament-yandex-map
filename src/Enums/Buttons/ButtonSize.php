<?php

namespace Kpebedko22\FilamentYandexMap\Enums\Buttons;

/**
 * @link https://yandex.ru/dev/jsapi-v2-1/doc/en/v2-1/ref/reference/control.Button#param-parameters.options.size
 */
enum ButtonSize: string
{
    /**
     * The default button layout is changed automatically depending on the
     * dimensions of the map and the number of added controls.
     */
    case Auto = 'auto';

    /**
     * The button layout displays the icon, regardless of the map size.
     */
    case Small = 'small';

    /**
     * The button layout displays only text, regardless of the map size.
     */
    case Medium = 'medium';

    /**
     * The button layout always displays both the icon and text,
     * regardless of the map size.
     */
    case Large = 'large';
}
