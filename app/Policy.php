<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    protected $encrypted = [];

    protected $fillable = ['name', 'description', 'policy_category_id'];
    protected static $logAttributes = ['name', 'description', 'policy_category_id'];

    public function policyMethods()
    {
        return $this->hasMany(PolicyMethod::class);
    }

    public function policyEncryptions()
    {
        return $this->hasMany(PolicyEncryption::class);
    }

    public function policyCategory()
    {
        return $this->belongsTo(PolicyCategory::class);
    }
}
