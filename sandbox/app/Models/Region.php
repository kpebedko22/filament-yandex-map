<?php

namespace App\Models;

use Clickbar\Magellan\Data\Geometries\MultiPolygon;
use Database\Factories\RegionFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 *
 * @property array<array-key, mixed>|null $array_multipolygon
 * @property MultiPolygon|null $magellan_multipolygon
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static RegionFactory factory($count = null, $state = [])
 * @method static Builder<static>|self query()
 *
 * @mixin Eloquent
 */
final class Region extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'array_multipolygon' => 'array',
            'magellan_multipolygon' => MultiPolygon::class,
        ];
    }
}
