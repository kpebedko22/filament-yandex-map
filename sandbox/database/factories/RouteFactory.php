<?php

namespace Database\Factories;

use App\Models\Route;
use Clickbar\Magellan\Data\Geometries\LineString;
use Clickbar\Magellan\Data\Geometries\Point;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Route>
 */
final class RouteFactory extends Factory
{
    use HasBounds;

    public function definition(): array
    {
        $lat1 = $this->lat();
        $lng1 = $this->lng();

        $lat2 = $this->lat();
        $lng2 = $this->lng();

        return [
            'array_line' => [
                [$lat1, $lng1],
                [$lat2, $lng2],
            ],
            'magellan_line' => LineString::make([
                Point::makeGeodetic($lat1, $lng1),
                Point::makeGeodetic($lat2, $lng2),
            ]),
        ];
    }
}
