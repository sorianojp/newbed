<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RoleController extends Controller
{
    public function index(): View
    {
        $roles = Role::with('permissions')->orderBy('id', 'DESC')->get();
        $permissions = Permission::orderBy('id','DESC')->get();
        return view('roles.index', compact('roles', 'permissions'));
    }

    public function create(): View
    {
        $permission = Permission::get();
        return view('roles.create',compact('permission'));
    }
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);
        $permissionsID = array_map(
            function($value) { return (int)$value; },
            $request->input('permission')
        );
        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($permissionsID);
        return redirect()->route('roles.index')
                        ->with('success','Role created successfully');
    }
    public function edit($id): View
    {
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();
        return view('roles.edit',compact('role','permission','rolePermissions'));
    }
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'permission' => 'required',
        ]);
        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();
        $permissionsID = array_map(
            function($value) { return (int)$value; },
            $request->input('permission')
        );
        $role->syncPermissions($permissionsID);
        return redirect()->route('roles.index')
                        ->with('success','Role updated successfully');
    }
    public function destroy($id): RedirectResponse
    {
        DB::table("roles")->where('id',$id)->delete();
        return redirect()->route('roles.index')
                        ->with('success','Role deleted successfully');
    }
    public function createPermission(): View
    {
        return view('permissions.create');
    }
    public function storePermission(Request $request): RedirectResponse
    {
        $request->validate([
            'permission' => 'required|string|max:20|unique:permissions,name',
        ]);
        Permission::create(['name' => $request->input('permission')]);
        return redirect()->route('roles.index')->with('success', 'Permission added successfully!');
    }
}
