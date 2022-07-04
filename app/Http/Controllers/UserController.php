<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with('employee', 'roles', 'permissions')->whereHas('employee')
                    ->get();
        return view('user.index', compact('users'));
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
        //
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
        $user = User::findOrFail($id);
        $user_direct_permissions = $user->getDirectPermissions()->pluck('name')->toArray();
        $roles = Role::where('name', '!=', 'Super Admin')->with('permissions')->get();
        $permissions = Permission::all();

        return view('user.edit', compact('user', 'roles', 'permissions', 'user_direct_permissions'));
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
        $request->validate([
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($id)
            ],
            'password' => 'nullable|min:6|alpha_num',
            'role' => 'required|exists:roles,name'
        ]);

        $user = User::findOrFail($id);

        if ($request->filled('password')) {
            $user->update([
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);
        }else{
            $user->update($request->except('password'));
        }

        $user->syncRoles($request->role);

        if ($request->filled('extra_permissions')) {
            $user->syncPermissions([]);
            foreach ($request->extra_permissions as $extra_permission) {
                if (!$user->hasPermissionTo($extra_permission)) {
                    $user->givePermissionTo($extra_permission);
                }
            }
        }else{
            $user->permissions()->detach();
        }

        return redirect()->back()->with('alert',[
            'type' => 'success',
            'msg' => 'User role dan extra permission berhasil diupdate'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        DB::transaction(function () use($user) {
            $user->delete();
            $user->employee->delete();
        });

        session()->flash('alert', [
            'type' => 'success',
            'msg' => 'User karyawan: '.$user->employee->fullname.' berhasil dihapus'
        ]);

        return response()->json(true);
    }
}
