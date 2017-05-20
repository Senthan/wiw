<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
