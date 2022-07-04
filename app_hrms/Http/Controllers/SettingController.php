<?php

namespace App\Http\Controllers;

use App\GlobalSetting;
use App\Mail\ResetPassword;
use App\Models\Employee;
use App\Models\Setting;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $setting = new GlobalSetting();
        $backups = Storage::allFiles(str_replace(' ', '-', config('backup.backup.name')));
        $employees = Employee::has('user')->with('user')->get();
        $approvals = DB::table('t_job_hc_approval')->get();
        
        return view('setting.index', [
            'setting' => $setting, 
            'backups' => $backups, 
            'employees' => $employees,
            'approval_1' => $approvals->where('Id', 1)->first(),
            'approval_2' => $approvals->where('Id', 2)->first(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required',
            'company_logo' => 'nullable|sometimes|mimes:png|max:512',
            'default_avatar' => 'nullable|sometimes|mimes:png|max:512',
            'company_address' => 'nullable',
            'company_email' => 'nullable|email',
            'company_phone' => 'nullable',
            'dashboard_banner' => 'nullable|max:191',
            'calendar_google_id' => 'nullable',
            'calendar_api_key' => 'nullable',
        ]);

        $request_data = [
            'company_name' => $request->company_name,
            'company_address' => $request->company_address,
            'company_email' => $request->company_email,
            'company_phone' => $request->company_phone,
            'dashboard_banner' => $request->dashboard_banner,
            'calendar_google_id' => $request->calendar_google_id,
            'calendar_api_key' => $request->calendar_api_key,
        ];

        if ($request->hasFile('company_logo')) {
            $company_logo = 'logo.png';
            $request->file('company_logo')->move(public_path('uploads/images'), $company_logo);
            $request_data['company_logo'] = $company_logo;
        }

        if ($request->hasFile('default_avatar')) {
            $default_avatar = 'profile-avatar-flat.png';
            $request->file('default_avatar')->move(public_path('uploads/images'), $default_avatar);
            $request_data['default_avatar'] = $default_avatar;
        }

        if ($request->has('use_logo')) {
            $request_data['use_logo'] = 'true';
        }else{
            $request_data['use_logo'] = null;
        }

        DB::transaction(function () use($request_data) {
            foreach ($request_data as $key=>$value) {
                Setting::where('key', $key)->update(['value' => $value]);
            }
        });

        $this->refresh();

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'msg' => 'Setting aplikasi berhasil diperbarui, cache telah direfresh'
        ]);
    }

    public function refresh()
    {
        Cache::forget('app_settings');

        return response()->json(true);
    }

    public function flush()
    {
        Cache::flush();

        return response()->json(true);
    }

    public function download_backup($id)
    {
        return response()->download(storage_path('app/'.str_replace(' ', '-', config('backup.backup.name')).'/'.$id));
    }

    public function reset_password(Request $request)
    {
        if ($request->has('all_user')) {
            $users = User::whereHas('employee')->get();
            
            foreach ($users as $user) {
                $new_password = random_string(6);

                $user->update([
                    'password' => bcrypt($new_password),
                ]);

                Mail::send(new ResetPassword([
                    'new_password' => $new_password,
                    'email' => $user->email,
                ]));
            }

            return redirect()->back()->with('alert', [
                'type' => 'success',
                'msg' => 'Berhasil Kirim Email Reset Ke Semua'
            ]);
        } else {
            if (!$request->user_id) {
                return redirect()->back()->with('alert', [
                    'type' => 'danger',
                    'msg' => 'Silahkan Pilih Karyawan'
                ]);
            }else{
                foreach ($request->user_id as $user_id) {
                    $user = User::find($user_id);

                    if ($user) {
                        $new_password = random_string(6);

                        $user->update([
                            'password' => bcrypt($new_password),
                        ]);
        
                        Mail::send(new ResetPassword([
                            'new_password' => $new_password,
                            'email' => $user->email,
                        ]));
                    }
                }

                return redirect()->back()->with('alert', [
                    'type' => 'success',
                    'msg' => 'Berhasil Kirim Email Reset'
                ]);
            }
        }
    }

    public function setting_approval_ptk(Request $request)
    {
        $request->validate([
            'approval_1' => 'required',
            'approval_2' => 'required',
        ]);

        DB::beginTransaction();
        try {
            DB::table('t_job_hc_approval')->where('id', 1)->update([
                'EmployeeId' => $request->approval_1
            ]);

            DB::table('t_job_hc_approval')->where('id', 2)->update([
                'EmployeeId' => $request->approval_2
            ]);

            DB::commit();
            return redirect()->back()->with('alert', [
                'type' => 'success',
                'msg' => 'Berhasil ubah approval PTK HC'
            ]);
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('alert', [
                'type' => 'danger',
                'msg' => $ex->getMessage()
            ]);
        }
    }
}
