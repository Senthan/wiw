<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\RoleRepository;
use App\Http\Requests\UserDestroyRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\User;


class UserController extends Controller
{
    protected $users;
    protected $roles;
    public function __construct(UserRepository $users, RoleRepository $roles)
    {
        $this->users = $users;
        $this->roles = $roles;
    }

    public function index()
    {
        if (request()->ajax()) {
            return $this->users->serialize();
        }
        $this->authorize(new User());

        $breadcrumb = [
            ['text' => 'home', 'route' => 'home.index'],
            ['text' => 'user management', 'class' => 'active'],
        ];
        return view('user.index', compact('breadcrumb'));
    }

    public function create()
    {
        $this->authorize(new User());

        $breadcrumb = [
            ['text' => 'home', 'route' => 'home.index'],
            ['text' => 'user management', 'route' => 'user.index'],
            ['text' => 'create', 'class' => 'active']
        ];
        $roles = $this->roles->forDropDown();
        return view('user.create', compact('breadcrumb', 'roles'));
    }

    public function store(UserStoreRequest $request)
    {
        $this->authorize(new User());
        $user = User::create($request->only(['name', 'description']));

        $roleId = $request->role_id;
        if($roleId) {
           $user->roles()->attach($roleId);
        }


        alert()->success('User record is created with the name of ' . $user->name)->autoclose(2000);
        return redirect()->route('user.index');
    }

    public function edit(User $user)
    {
        $this->authorize($user);
        $breadcrumb = [
            ['text' => 'home', 'route' => 'home.index'],
            ['text' => 'user management', 'route' => 'user.index'],
            ['text' => 'edit', 'class' => 'active']
        ];
        $roles = $this->roles->forDropDown();
        $user->role_id = $user->roles->first()->id ?? 0;
        return view('user.edit', compact('user', 'breadcrumb', 'roles'));
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        $this->authorize($user);
        $user->update($request->only(['name', 'description']));
        if($user->id > 1) {
            $roleId = $request->role;
            $user->roles()->detach();
            $user->roles()->attach($roleId);
        }
        

        alert()->success('User record is updated for ' . $user->name)->autoclose(2000);
        return redirect()->route('user.index');
    }

    public function delete(User $user)
    {
        $this->authorize($user);
        $breadcrumb = [
            ['text' => 'home', 'route' => 'home.index'],
            ['text' => 'user management', 'route' => 'user.index'],
            ['text' => 'delete', 'class' => 'active']
        ];
        return view('user.delete', compact('user', 'breadcrumb'));
    }

    public function destroy(UserDestroyRequest $request, User $user)
    {
        $this->authorize($user);
        $user->delete();
        alert()->success('User record is deleted')->autoclose(2000);
        return redirect()->route('user.index');
    }
}
