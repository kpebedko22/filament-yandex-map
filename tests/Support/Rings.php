<?php

namespace Kpebedko22\FilamentYandexMap\Tests\Support;

/**
 * Closed rings in the state format: lists of [lat, lng], the first point is repeated as the last one.
 */
final class Rings
{
    public const OUTER = [[53.0, 83.0], [53.0, 84.0], [54.0, 84.0], [54.0, 83.0], [53.0, 83.0]];

    public const HOLE = [[53.4, 83.4], [53.4, 83.6], [53.6, 83.6], [53.6, 83.4], [53.4, 83.4]];
}
