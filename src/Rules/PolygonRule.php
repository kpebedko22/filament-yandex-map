<?php

namespace Kpebedko22\FilamentYandexMap\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Polygon outline must have at least 3 points.
 *
 * Therefore, polygons' first and last point is the same then MIN_POINTS_COUNT is 4.
 */
final readonly class PolygonRule implements ValidationRule
{
    private const int MIN_POINTS_COUNT = 4;

    public function validate(
        string  $attribute,
        mixed   $value,
        Closure $fail
    ): void {
        if (is_array($value)) {
            foreach ($value as $item) {
                if (is_array($item) && count($item) < self::MIN_POINTS_COUNT) {
                    $fail(__('filament-yandex-map::rules/polyline.min', ['count' => self::MIN_POINTS_COUNT - 1]));
                }
            }
        }
    }
}
