<?php

namespace App\Models;

use Clickbar\Magellan\Data\Geometries\Polygon;
use Database\Factories\AreaFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 *
 * @property array<array-key, mixed>|null $array_polygon
 * @property Polygon|null $magellan_polygon
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static AreaFactory factory($count = null, $state = [])
 * @method static Builder<static>|self query()
 *
 * @mixin Eloquent
 */
final class Area extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'array_polygon' => 'array',
            'magellan_polygon' => Polygon::class,
        ];
    }
}
