<?php

namespace Kpebedko22\FilamentYandexMap\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Polyline must have at least 2 points
 */
final readonly class PolylineRule implements ValidationRule
{
    private const int MIN_POINTS_COUNT = 2;

    public function validate(
        string $attribute,
        mixed $value,
        Closure $fail
    ): void {
        if (is_array($value) && count($value) < self::MIN_POINTS_COUNT) {
            $fail(__('filament-yandex-map::rules/polyline.min', ['count' => self::MIN_POINTS_COUNT]));
        }
    }
}
