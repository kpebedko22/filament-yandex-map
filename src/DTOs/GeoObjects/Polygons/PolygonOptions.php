<?php

namespace Kpebedko22\FilamentYandexMap\DTOs\GeoObjects\Polygons;

use Kpebedko22\FilamentYandexMap\DTOs\GeoObjects\Contracts\Options;

/**
 * @link https://yandex.ru/dev/jsapi-v2-1/doc/en/v2-1/ref/reference/Polygon#param-options
 */
readonly class PolygonOptions implements Options
{
    public function __construct(
        public string            $cursor = 'pointer',
        public bool              $draggable = false,
        public bool              $fill = true,
        public string            $fillColor = '0066ff99',
        public ?string           $fillImageHref = null,
        public string            $fillMethod = 'stretch',
        public float             $fillOpacity = 1,
        public bool              $hasBalloon = true,
        public bool              $hasHint = true,
        public bool              $interactiveZIndex = false,
        public string            $interactivityModel = 'default#geoObject',
        public float             $opacity = 1,
        public bool              $openBalloonOnClick = true,
        public bool              $openEmptyBalloon = false,
        public bool              $openEmptyHint = false,
        public bool              $openHintOnHover = true,
        public string            $pane = 'areas',
        public string            $polygonOverlay = 'default#polygon',
        public string|array      $strokeColor = '0066ffff',
        public float|array       $strokeOpacity = 1,
        public string|array|null $strokeStyle = null,
        public int|array         $strokeWidth = 1,
        public bool              $syncOverlayInit = false,
        public bool              $useMapMarginInDragging = true,
        public bool              $visible = true,
        public ?int              $zIndex = null,
        public ?int              $zIndexActive = null,
        public ?int              $zIndexDrag = null,
        public ?int              $zIndexHover = null,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'cursor' => $this->cursor,
            'draggable' => $this->draggable,
            'fill' => $this->fill,
            'fillColor' => $this->fillColor,
            'fillImageHref' => $this->fillImageHref,
            'fillMethod' => $this->fillMethod,
            'fillOpacity' => $this->fillOpacity,
            'hasBalloon' => $this->hasBalloon,
            'hasHint' => $this->hasHint,
            'interactiveZIndex' => $this->interactiveZIndex,
            'interactivityModel' => $this->interactivityModel,
            'opacity' => $this->opacity,
            'openBalloonOnClick' => $this->openBalloonOnClick,
            'openEmptyBalloon' => $this->openEmptyBalloon,
            'openEmptyHint' => $this->openEmptyHint,
            'openHintOnHover' => $this->openHintOnHover,
            'pane' => $this->pane,
            'polygonOverlay' => $this->polygonOverlay,
            'strokeColor' => $this->strokeColor,
            'strokeOpacity' => $this->strokeOpacity,
            'strokeStyle' => $this->strokeStyle,
            'strokeWidth' => $this->strokeWidth,
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
