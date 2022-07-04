<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    public function index()
    {
        $user = User::with(['employee' => function($employee){
            $employee->with('employee_detail', 'employee_salary', 'division', 'department', 'grade_title', 'level_title');
        }])->findOrFail(auth()->user()->id);

        return view('account.index', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore(auth()->id())
            ],
            'password' => 'nullable|min:6|alpha_num',
            'photo' => 'nullable|sometimes|image|max:512'
        ]);

        $user = User::findOrFail(auth()->id());

        $update_data = [
            'email' => $request->email
        ];

        if ($request->filled('password')) {
            $update_data['password'] = bcrypt($request->password);
        }
        
        if ($request->hasFile('photo')) {
            $filename = $request->file('photo')->hashName();
            $request->file('photo')->move(public_path('uploads/images/users'), $filename);
        }else{
            $filename = null;
        }

        DB::transaction(function () use($user, $filename, $update_data) {
            $user->update($update_data);

            if ($filename) {
                $user->employee()->update([
                    'photo' => $filename
                ]);
            }
        });

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'msg' => 'Berhasil mengupdate user data'
        ]);
    }

    public function setting()
    {
        return view('account.setting');
    }

    public function notification()
    {
        $notifs = auth()->user()->notifications;

        return view('account.notification',[
            'notifs' => $notifs
        ]);
    }

    public function marksRead(Request $request)
    {
        if ($request->marksRead) {
            auth()->user()->unreadNotifications->markAsRead();
        }

        return redirect()->back();
    }
}
