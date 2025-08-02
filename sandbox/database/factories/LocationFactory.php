<?php

namespace Database\Factories;

use App\Models\Location;
use Clickbar\Magellan\Data\Geometries\Point;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Location>
 */
final class LocationFactory extends Factory
{
    use HasBounds;

    public function definition(): array
    {
        $lat = $this->lat();
        $lng = $this->lng();

        return [
            'array_point' => [$lat, $lng],
            'magellan_point' => Point::makeGeodetic($lat, $lng),
        ];
    }
}
