<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
