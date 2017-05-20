<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\PolicyEncryption
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $policy_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Policy $policy
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Role[] $roles
 * @method static \Illuminate\Database\Query\Builder|\App\PolicyEncryption whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PolicyEncryption whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PolicyEncryption whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PolicyEncryption whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PolicyEncryption wherePolicyId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PolicyEncryption whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PolicyEncryption extends Model
{
    

    protected $fillable = ['name', 'description', 'policy_id'];
    protected static $logAttributes = ['name', 'description', 'policy_id'];

    public function policy()
    {
        return $this->belongsTo(Policy::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    
}
