<?php

namespace App\Models;

use Clickbar\Magellan\Data\Geometries\LineString;
use Database\Factories\RouteFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 *
 * @property array<array-key, mixed>|null $array_line
 * @property LineString|null $magellan_line
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static RouteFactory factory($count = null, $state = [])
 * @method static Builder<static>|self query()
 *
 * @mixin Eloquent
 */
final class Route extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'array_line' => 'array',
            'magellan_line' => LineString::class,
        ];
    }
}
