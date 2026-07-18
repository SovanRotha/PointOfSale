<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{

    // Get all permissions
    public function index()
    {
        $permissions = Permission::all();

        return response()->json([
            'data' => $permissions
        ]);
    }


    // Create permission
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:permissions,name'
        ]);


        $permission = Permission::create([
            'name' => $data['name'],
            'guard_name' => 'web'
        ]);


        return response()->json([
            'message' => 'Permission created',
            'data' => $permission
        ],201);
    }



    // Delete permission
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);

        $permission->delete();


        return response()->json([
            'message'=>'Permission deleted'
        ]);
    }


    // Assign permissions to role
    public function assignToRole(Request $request)
    {

        $data = $request->validate([
            'role' => 'required|exists:roles,name',
            'permissions'=>'required|array',
            'permissions.*'=>'exists:permissions,name'
        ]);


        $role = Role::findByName($data['role']);


        $role->syncPermissions($data['permissions']);


        return response()->json([
            'message'=>'Permissions assigned to role',
            'role'=>$role->load('permissions')
        ]);
    }

    public function roles()
{
    $roles = Role::with('permissions')->get();

    return response()->json([
        'data'=>$roles
    ]);
}

public function showRole($id)
{
    $role = Role::with('permissions')
        ->where('name',$id)
        ->firstOrFail();


    return response()->json([
        'data'=>$role
    ]);
}

}