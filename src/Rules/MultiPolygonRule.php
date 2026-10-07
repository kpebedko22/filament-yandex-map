<?php

namespace Kpebedko22\FilamentYandexMap\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Every ring of every polygon of the multipolygon must satisfy {@see PolygonRule}.
 */
class MultiPolygonRule implements ValidationRule
{
    public function validate(
        string  $attribute,
        mixed   $value,
        Closure $fail
    ): void {
        if (is_array($value)) {
            $polygonRule = new PolygonRule;

            foreach ($value as $polygon) {
                $polygonRule->validate($attribute, $polygon, $fail);
            }
        }
    }
}
