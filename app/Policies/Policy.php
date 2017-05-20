<?php

namespace App\Policies;

use App\PolicyEncryption;
use App\PolicyMethod;
use App\Role;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Cache;
use App\Policy as PolicyModel;

class Policy
{
    use HandlesAuthorization;

    protected $methods = ['index', 'create', 'show', 'edit', 'delete', 'deleteOwn'];

    protected $encrypted = [];

    protected $replacedMethods = ['store' => 'create', 'update' => 'edit', 'destroy' => 'delete'];

    protected $policyMethods;
    protected $policyEncryptions;
    protected $rolePolicyMethods;
    protected $rolePolicyEncryptions;
    protected $role;
    protected $policyModel;

    protected $commonCheck;

    public function getMethods()
    {
        return $this->methods;
    }

    public function getEncrypted()
    {
        return $this->encrypted;
    }

    public function before(User $user, $ability, $model)
    {
        $this->role = $user->findHighestRole();
        if(!$this->role) {
            $this->commonCheck = false;
            return;
        }
        $this->policyModel = Cache::rememberForever('policy', function() {
            return PolicyModel::get();
        });

        if(starts_with($ability, 'decrypt-')) {
            $ability = str_replace('decrypt-', '', $ability);
            $this->rolePolicyEncryptions = Cache::rememberForever('role_policy_encryptions_' . $this->role->id, function() {
                return $this->role->policyEncryptions()->get();
            });

            $this->policyEncryptions = Cache::rememberForever('policy_encryptions', function() {
                return PolicyEncryption::get();
            });

            $policyClassName = class_basename(policy($model));
//            $policy = PolicyModel::where('name', $policyClassName)->first();
            $policy = $this->policyModel->where('name', $policyClassName)->first();
            if ($policy) {
                $policyEncryption = $this->policyEncryptions->where('policy_id', $policy->id)->where('name', $ability)->first();

            } else {
                $policyEncryption = false;
            }
            if($policyEncryption) {
                $rolePolicyEncryption = $this->rolePolicyEncryptions->find($policyEncryption->id);
                if($rolePolicyEncryption && $rolePolicyEncryption->pivot) {
                    return $rolePolicyEncryption->pivot->authorized == 1 ? true : false;
                }
            }
            return false;
        }


        $permissions = array_map('strtolower', session('delegated_permissions', []));
        if(in_array(strtolower(class_basename($model)), $permissions)) {
            return true;
        }

        if (!($this->role instanceof Role)) {
            return false;
        }

        $this->rolePolicyMethods = Cache::rememberForever('role_policy_methods_' . $this->role->id, function() {
            return $this->role->policyMethods()->get();
        });

        $this->policyMethods = Cache::rememberForever('policy_methods', function() {
            return PolicyMethod::get();
        });


        if($this->role->name == 'Super Admin') {
            return true;
        }
        $policyClassName = class_basename(policy($model));

        $policy = $this->policyModel->where('name', $policyClassName)->first();
        $currentMethod = $ability;
        if (array_key_exists($currentMethod, $this->replacedMethods)) {
            $currentMethod = $this->replacedMethods[$currentMethod];
        }
        if ($policy) {
            $policyMethod = $this->policyMethods->where('policy_id', $policy->id)->where('name', $currentMethod)->first();
        } else {
            $policyMethod = false;
        }

        $this->commonCheck = false;

        if($policyMethod) {
            $rolePolicyMethod = $this->rolePolicyMethods->find($policyMethod->id);
            if($rolePolicyMethod && $rolePolicyMethod->pivot) {
                $this->commonCheck = $rolePolicyMethod->pivot->authorized == 1 ? true : false;
            }
        }
    }


    public function deleteOwnObject(User $user, $model)
    {
        if(!isset($model) || (isset($model) && !isset($model->id))) {
            return false;
        }
        return false;
    }
}
