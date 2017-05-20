<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleDestroyRequest;
use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Policy;
use App\PolicyCategory;
use App\PolicyEncryption;
use App\PolicyMethod;
use App\Repositories\RoleRepository;
use App\Role;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

/**
 * Class RoleController
 * @package App\Http\Controllers
 */
class RoleController extends Controller
{
    /**
     * @var RoleRepository
     */
    protected $roles;

    /**
     * RoleController constructor.
     * @param RoleRepository $roles
     */
    public function __construct(RoleRepository $roles)
    {
        $this->roles = $roles;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return $this->roles->serialize();
        }
        $this->authorize(new Role());

        $breadcrumb = [
            ['text' => 'home', 'route' => 'home.index'],
            ['text' => 'role management', 'class' => 'active'],
        ];
        return view('role.index', compact('breadcrumb'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $this->authorize(new Role());

        $breadcrumb = [
            ['text' => 'home', 'route' => 'home.index'],
            ['text' => 'role management', 'route' => 'role.index'],
            ['text' => 'create', 'class' => 'active']
        ];

        return view('role.create', compact('breadcrumb'));
    }

    /**
     * @param RoleStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(RoleStoreRequest $request)
    {
        $this->authorize(new Role());
        $role = Role::create($request->only(['name', 'description']));
        alert()->success('Role record is created with the name of ' . $role->name)->autoclose(2000);
        Cache::forget('roles');
        return redirect()->route('role.index');
    }

    /**
     * @param Role $role
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Role $role)
    {
        $this->authorize($role);
        $breadcrumb = [
            ['text' => 'home', 'route' => 'home.index'],
            ['text' => 'role management', 'route' => 'role.index'],
            ['text' => 'edit', 'class' => 'active']
        ];
        return view('role.edit', compact('role', 'breadcrumb'));
    }

    /**
     * @param RoleUpdateRequest $request
     * @param Role $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(RoleUpdateRequest $request, Role $role)
    {
        $this->authorize($role);
        $role->update($request->only(['name', 'description']));
        alert()->success('Role record is updated for ' . $role->name)->autoclose(2000);
        Cache::forget('roles');
        return redirect()->route('role.index');
    }

    /**
     * @param Role $role
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delete(Role $role)
    {
        $this->authorize($role);
        $breadcrumb = [
            ['text' => 'home', 'route' => 'home.index'],
            ['text' => 'role management', 'route' => 'role.index'],
            ['text' => 'delete', 'class' => 'active']
        ];
        return view('role.delete', compact('role', 'breadcrumb'));
    }

    /**
     * @param RoleDestroyRequest $request
     * @param Role $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(RoleDestroyRequest $request, Role $role)
    {
        $this->authorize($role);
        $role->delete();
        Cache::forget('roles');
        alert()->success('Role record is deleted')->autoclose(2000);
        return redirect()->route('role.index');
    }

    /**
     * @param Role $role
     * @param PolicyCategory $policyCategory
     * @param Policy $policy
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function show(Role $role, PolicyCategory $policyCategory, Policy $policy)
    {
        $this->authorize($role);
        if(!$policyCategory->exists) {
            $policyCategory = PolicyCategory::find(1);
            $policy = $policyCategory->policies()->first();
            return redirect()->route('role.show', ['role' => $role->id, 'policyCategory' => $policyCategory->id, 'policy' => $policy->id]);
        }
        if($policyCategory->exists && !$policy->exists) {
            $policy = $policyCategory->policies()->first();
            if(isset($policy)) {
                return redirect()->route('role.show', ['role' => $role->id, 'policyCategory' => $policyCategory->id, 'policy' => $policy->id]);
            }
            $policyCategory = PolicyCategory::find(1);
            $policy = $policyCategory->policies()->first();
            return redirect()->route('role.show', ['role' => $role->id, 'policyCategory' => $policyCategory->id, 'policy' => $policy->id]);

        }
        $policyMethods = $policy->policyMethods()->get();
        $policyEncryptions = $policy->policyEncryptions()->get();
        $policies = $policyCategory->policies;
        $rolePolicyMethods = $role->policyMethods()->get();
        $policyMethods->transform(function ($method) use($rolePolicyMethods) {
            $transformedMethod = $method;
            $rolePolicyMethod = $rolePolicyMethods->where('id', $method->id)->first();
            $transformedMethod->authorized = false;
            if($rolePolicyMethod) {
                $transformedMethod->authorized = (boolean) $rolePolicyMethods->where('id', $method->id)->first()->pivot->authorized;
            }
            return $transformedMethod;
        });
        $rolePolicyEncryptions = $role->policyEncryptions()->get();
        $policyEncryptions->transform(function ($encryption) use($rolePolicyEncryptions) {
            $transformedEncryption = $encryption;
            $rolePolicyEncryption = $rolePolicyEncryptions->where('id', $encryption->id)->first();
            $transformedEncryption->authorized = false;
            if($rolePolicyEncryption) {
                $transformedEncryption->authorized = (boolean) $rolePolicyEncryptions->where('id', $encryption->id)->first()->pivot->authorized;
            }
            return $transformedEncryption;
        });
        $policyCategories = PolicyCategory::all();


        $breadcrumb = [
            ['text' => 'home', 'route' => 'home.index'],
            ['text' => 'role management', 'route' => 'role.index'],
            ['text' => $role->name, 'route' => 'role.show', 'parameters' => ['role' => $role, 'policyCategory' => $policyCategory->id]],
            ['text' => 'permissions', 'class' => 'active']
        ];


        return view('role.permissions', compact('policyCategories', 'role', 'policy', 'policyMethods', 'policyEncryptions', 'policies', 'policyCategory', 'breadcrumb'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sync()
    {
        $policyFiles = array_values(array_filter(scandir(app_path('Policies')), function($name) {
            if($name == 'Policy.php') {
                return false;
            }
            if(strpos($name, '.php')) {
                return true;
            }
        }));

        foreach ($policyFiles as $policyFile) {
            $policyFileName = 'App\Policies\\' . str_replace('.php', '', $policyFile);
            $policyInstance = new $policyFileName();
            $policyClassName = class_basename($policyInstance);
            $policy = Policy::firstOrNew(['name' => $policyClassName]);
            $policy->policy_category_id = 1;
            $policy->save();
            $methods = $policyInstance->getMethods();

            $currentPolicyMethods = $policy->policyMethods->pluck('name', 'id')->toArray();

            $newMethods = array_diff($methods, $currentPolicyMethods);
            $removedMethods = array_diff($currentPolicyMethods, $methods);

            foreach ($newMethods as $method) {
                PolicyMethod::firstOrCreate(['name' => $method, 'policy_id' => $policy->id]);
            }

            PolicyMethod::destroy(array_keys($removedMethods));

            $encryptions = $policyInstance->getEncrypted();
            foreach ($encryptions as $encryption) {
                PolicyEncryption::firstOrCreate(['name' => $encryption, 'policy_id' => $policy->id]);
            }
        }

        return redirect()->route('role.index');
    }

    /**
     * @param Role $role
     * @param Policy $policy
     */
    public function updateMethod(Role $role, Policy $policy)
    {
        $this->authorize($role);
        $method = request()->input('method');
        $authorized = request()->input('authorized') ? 1 : 0;
        $policyMethod = $role->policyMethods()->wherePivot('policy_method_id', $method)->first();
        if($policyMethod) {
            $policyMethod->roles()->updateExistingPivot($role->id, ['authorized' => $authorized]);
        }
        $role->policyMethods()->sync([$method => ['authorized' => $authorized]], false);

        cache()->flush();
    }

    /**
     * @param Role $role
     * @param Policy $policy
     */
    public function updateEncryption(Role $role, Policy $policy)
    {
        $this->authorize($role);
        $encryption = request()->input('encryption');
        $authorized = request()->input('authorized') ? 1 : 0;
        $policyEncryption = $role->policyEncryptions()->wherePivot('policy_encryption_id', $encryption)->first();
        if($policyEncryption) {
            $policyEncryption->roles()->updateExistingPivot($role->id, ['authorized' => $authorized]);
        }
        $role->policyEncryptions()->sync([$encryption => ['authorized' => $authorized]], false);
    }

    /**
     * @param Role $role
     * @param PolicyCategory $policyCategory
     * @param Policy $policy
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeCategory(Role $role, PolicyCategory $policyCategory, Policy $policy)
    {
        $policy->policy_category_id = $policyCategory->id;
        $policy->save();
        return redirect()->route('role.show', ['role' => $role->id, 'policyCategory' => $policyCategory->id, 'policy' => $policy->id]);
    }
}
