<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\PolicyMethod
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $policy_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Policy $policy
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Role[] $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @method static \Illuminate\Database\Query\Builder|\App\PolicyMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PolicyMethod whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PolicyMethod whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PolicyMethod whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PolicyMethod wherePolicyId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PolicyMethod whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PolicyMethod extends Model
{
    

    protected $encrypted = [];

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

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    
}
