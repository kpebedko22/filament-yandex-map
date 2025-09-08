<?php

namespace App\Models;

use Clickbar\Magellan\Data\Geometries\Point;
use Database\Factories\LocationFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 *
 * @property array<array-key, mixed>|null $array_point
 * @property Point|null $magellan_point
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static LocationFactory factory($count = null, $state = [])
 * @method static Builder<static>|self query()
 *
 * @mixin Eloquent
 */
final class Location extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'array_point' => 'array',
            'magellan_point' => Point::class,
        ];
    }
}
