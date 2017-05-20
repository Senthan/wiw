<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Role
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $read_only
 * @property int $authorization_level
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PolicyEncryption[] $policyEncryptions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PolicyMethod[] $policyMethods
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @method static \Illuminate\Database\Query\Builder|\App\Role whereAuthorizationLevel($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Role whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Role whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Role whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Role whereReadOnly($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Role whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Role extends Model
{

    protected $encrypted = [];

    protected $fillable = ['name', 'description'];
    protected static $logAttributes = ['name', 'description'];

    public function policyMethods()
    {
        return $this->belongsToMany(PolicyMethod::class)->withPivot(['authorized']);
    }

    public function policyEncryptions()
    {
        return $this->belongsToMany(PolicyEncryption::class)->withPivot(['authorized']);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
