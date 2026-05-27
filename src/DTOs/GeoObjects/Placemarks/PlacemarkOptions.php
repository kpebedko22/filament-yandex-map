<?php

namespace Kpebedko22\FilamentYandexMap\DTOs\GeoObjects\Placemarks;

use Kpebedko22\FilamentYandexMap\DTOs\GeoObjects\Contracts\Options;
use Kpebedko22\FilamentYandexMap\DTOs\Placemarks\PresetIcon;
use Kpebedko22\FilamentYandexMap\Enums\Placemarks\PresetStorage;

/**
 * @link https://yandex.ru/dev/jsapi-v2-1/doc/en/v2-1/ref/reference/Placemark#param-options
 */
readonly class PlacemarkOptions implements Options
{
    /**
     * @param PresetStorage|PresetIcon|string|null $preset Key for the placemark's preset options.
     * @param string|null $iconColor The color of the placemark icon.
     * @param string $cursor Type of cursor over a placemark.
     * @param bool $draggable Checks whether the placemark can be dragged.
     * @param bool $hasBalloon Checks whether the placemark has the "balloon" field.
     * @param bool $hasHint Checks whether the placemark has the "hint" field.
     * @param bool $hideIconOnBalloonOpen Hide the placemark when opening the balloon.
     * @param int[]|null $iconOffset The pixel offset of the icon relative to its set position.
     * @param string|null $iconShape The hotspot shape of the placemark. Specified as a JSON description of the pixel geometry of the icon. Use this option when creating your HTML layouts. The coordinates of the figure geometry are counted from the anchor point.
     * @param bool $interactiveZIndex Enables automatically modifying the z-index of the placemark depending on its state.
     * @param string $interactivityModel Interactivity model.
     * @param bool $openBalloonOnClick Checks whether to show the balloon when the placemark is clicked on.
     * @param bool $openEmptyBalloon Checks whether to show an empty balloon when the placemark is clicked on.
     * @param bool $openEmptyHint Checks whether to show an empty hint when the mouse pointer hovers over the placemark.
     * @param bool $openHintOnHover Checks whether to show the hint when the mouse pointer hovers over the placemark.
     * @param string $pane The key of the pane where the placemark overlay is placed.
     * @param string $pointOverlay Key identifier from overlay.storage or the overlay class.
     * @param bool $syncOverlayInit Enables synchronously adding an overlay to the map.
     * @param bool $useMapMarginInDragging When an object is dragged to the edge of the map, the map center changes automatically.
     * @param bool $visible Checks placemark visibility.
     * @param int|null $zIndex The z-index of a placemark in its normal state. Lowest priority.
     * @param int|null $zIndexActive The z-index of a placemark icon with an open balloon. Highest priority.
     * @param int|null $zIndexDrag The z-index of a placemark that is being dragged.
     * @param int|null $zIndexHover The z-index of a placemark when the mouse pointer is hovering over it.
     */
    public function __construct(
        public PresetStorage|PresetIcon|string|null $preset = null,
        public ?string                              $iconColor = null,
        public string                               $cursor = 'pointer',
        public bool                                 $draggable = false,
        public bool                                 $hasBalloon = true,
        public bool                                 $hasHint = true,
        public bool                                 $hideIconOnBalloonOpen = true,
        public ?array                               $iconOffset = null,
        public ?string                              $iconShape = null,
        public bool                                 $interactiveZIndex = true,
        public string                               $interactivityModel = 'default#geoObject',
        public bool                                 $openBalloonOnClick = true,
        public bool                                 $openEmptyBalloon = false,
        public bool                                 $openEmptyHint = false,
        public bool                                 $openHintOnHover = true,
        public string                               $pane = 'places',
        public string                               $pointOverlay = 'default#placemark',
        public bool                                 $syncOverlayInit = false,
        public bool                                 $useMapMarginInDragging = true,
        public bool                                 $visible = true,
        public ?int                                 $zIndex = null,
        public ?int                                 $zIndexActive = null,
        public ?int                                 $zIndexDrag = null,
        public ?int                                 $zIndexHover = null,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'preset' => $this->preset,
            'iconColor' => $this->iconColor,
            'cursor' => $this->cursor,
            'draggable' => $this->draggable,
            'hasBalloon' => $this->hasBalloon,
            'hasHint' => $this->hasHint,
            'hideIconOnBalloonOpen' => $this->hideIconOnBalloonOpen,
            'iconOffset' => $this->iconOffset,
            'iconShape' => $this->iconShape,
            'interactiveZIndex' => $this->interactiveZIndex,
            'interactivityModel' => $this->interactivityModel,
            'openBalloonOnClick' => $this->openBalloonOnClick,
            'openEmptyBalloon' => $this->openEmptyBalloon,
            'openEmptyHint' => $this->openEmptyHint,
            'openHintOnHover' => $this->openHintOnHover,
            'pane' => $this->pane,
            'pointOverlay' => $this->pointOverlay,
            'syncOverlayInit' => $this->syncOverlayInit,
            'useMapMarginInDragging' => $this->useMapMarginInDragging,
            'visible' => $this->visible,
            'zIndex' => $this->zIndex,
            'zIndexActive' => $this->zIndexActive,
            'zIndexDrag' => $this->zIndexDrag,
            'zIndexHover' => $this->zIndexHover,
        ], static function (mixed $value): bool {
            return !is_null($value);
        });
    }
}
