<?php

use Kpebedko22\FilamentYandexMap\Rules\MultiPolygonRule;

// Closed rings: the first point is repeated as the last one.
$outer = [[53.0, 83.0], [53.0, 84.0], [54.0, 84.0], [54.0, 83.0], [53.0, 83.0]];
$hole = [[53.4, 83.4], [53.4, 83.6], [53.6, 83.6], [53.6, 83.4], [53.4, 83.4]];
$island = [[55.0, 85.0], [55.0, 86.0], [56.0, 86.0], [56.0, 85.0], [55.0, 85.0]];
// Less than 4 points.
$short = [[53.0, 83.0], [53.0, 84.0], [53.0, 83.0]];

/** Messages of the failures of the rule for the value. */
$failuresOf = static function (mixed $value): array {
    $failures = [];

    (new MultiPolygonRule)->validate('area', $value, static function (string $message) use (&$failures): void {
        $failures[] = $message;
    });

    return $failures;
};

it('passes valid multipolygons', function (array $value) use ($failuresOf) {
    expect($failuresOf($value))->toBe([]);
})->with([
    'one polygon' => [[[[[53.0, 83.0], [53.0, 84.0], [54.0, 84.0], [54.0, 83.0], [53.0, 83.0]]]]],
    'two polygons' => [[
        [[[53.0, 83.0], [53.0, 84.0], [54.0, 84.0], [54.0, 83.0], [53.0, 83.0]]],
        [[[55.0, 85.0], [55.0, 86.0], [56.0, 86.0], [56.0, 85.0], [55.0, 85.0]]],
    ]],
    'a polygon with a hole' => [[
        [
            [[53.0, 83.0], [53.0, 84.0], [54.0, 84.0], [54.0, 83.0], [53.0, 83.0]],
            [[53.4, 83.4], [53.4, 83.6], [53.6, 83.6], [53.6, 83.4], [53.4, 83.4]],
        ],
    ]],
    'no polygons' => [[]],
    'a ring of exactly 4 points' => [[[[[53.0, 83.0], [53.0, 84.0], [54.0, 84.0], [53.0, 83.0]]]]],
]);

it('fails a ring of less than 4 points in the first polygon', function () use ($failuresOf, $short, $island) {
    $failures = $failuresOf([[$short], [$island]]);

    expect($failures)->toHaveCount(1)
        ->and($failures[0])->toContain('at least 3 points');
});

it('fails a ring of less than 4 points in the second polygon', function () use ($failuresOf, $short, $outer) {
    $failures = $failuresOf([[$outer], [$short]]);

    expect($failures)->toHaveCount(1)
        ->and($failures[0])->toContain('at least 3 points');
});

it('fails a hole of less than 4 points', function () use ($failuresOf, $short, $outer) {
    $failures = $failuresOf([[$outer, $short]]);

    expect($failures)->toHaveCount(1)
        ->and($failures[0])->toContain('at least 3 points');
});

it('fails every invalid ring', function () use ($failuresOf, $short) {
    expect($failuresOf([[$short], [$short]]))->toHaveCount(2);
});

it('ignores values that are not arrays', function (mixed $value) use ($failuresOf) {
    expect($failuresOf($value))->toBe([]);
})->with([null, 'string', 1]);
