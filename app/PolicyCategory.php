<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\PolicyCategory
 *
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Policy[] $policies
 * @method static \Illuminate\Database\Query\Builder|\App\PolicyCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PolicyCategory whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PolicyCategory whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PolicyCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PolicyCategory extends Model
{
	
    public function policies()
    {
        return $this->hasMany(Policy::class);
    }
}
