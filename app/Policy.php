<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Policy
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $policy_category_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\PolicyCategory $policyCategory
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PolicyEncryption[] $policyEncryptions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PolicyMethod[] $policyMethods
 * @method static \Illuminate\Database\Query\Builder|\App\Policy whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Policy whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Policy whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Policy whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Policy wherePolicyCategoryId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Policy whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
