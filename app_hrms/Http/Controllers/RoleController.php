<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::where('name', '!=', 'Super Admin')->get();

        return view('role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|unique:roles'
        ]);

        $role = Role::firstOrCreate(['name' => $request->name]);

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'msg' => 'Role: ' . $role->name . ' ditambahkan'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        $role->delete();

        // DB::transaction(function () use ($role) {
        //     $role->delete();

        //     $users = User::whereHas('roles', function($query) use ($role) {
        //         $query->where('name', $role->name);
        //     });

        //     foreach ($users as $user) {
        //         $user->removeRole($role->name);
        //     }
        // });

        session()->flash('alert',[
            'type' => 'success',
            'msg' => 'Role: ' . $role->name . ' dihapus'
        ]);

        return response()->json(true);
    }

    public function permissions($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        $role_has_permissions = [];

        foreach ($permissions as $permission) {
            if ($role->hasPermissionTo($permission->name)) {
                $role_has_permissions[] = $permission->name;
            }
        }

        return view('role.permission', compact('role', 'permissions', 'role_has_permissions'));
    }

    public function assign_permissions(Request $request, $id)
    {
        $request->validate([
            'permissions' => 'exists:permissions,name'
        ]);

        $role = Role::findOrFail($id);

        $role->syncPermissions($request->permissions);

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'msg' => 'Permissions berhasil diberikan ke role: '.$role->name
        ]);
    }
}
