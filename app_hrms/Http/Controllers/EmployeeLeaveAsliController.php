<?php

namespace App\Http\Controllers;

use File;
use Exception;
use Carbon\Carbon;
use App\Models\Leave;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\EmployeeLeave;
use App\Services\LeaveService;
use Illuminate\Support\Facades\DB;
use App\Notifications\NotifNewCuti;
use App\Notifications\NotifApprovalCuti;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use App\Http\Requests\EmployeeLeaveRequest;

class EmployeeLeaveController extends Controller
{
    private $employee_no;
    private $leave_service;

    /**
     * Controller's construct function
     */
    public function __construct() {
        $this->middleware(function ($request, $next) {
            $this->employee_no = auth()->user()->employee->registration_number ?? 0;
            return $next($request);
        });
        $this->leave_service = new LeaveService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if (auth()->user()->hasRole('Personnel')) {
        //     $employee_leaves = EmployeeLeave::with('leave')->get();
        // }else{
            $employee_leaves = EmployeeLeave::with('leave', 'approved_by')->where('employee_no', $this->employee_no)->get();
        // }

        return view('employee_leave.index', compact('employee_leaves'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($type)
    {
        $carbonNow = now();

        $checkLeaveQuota = $this->leave_service->checkLeaveQuota($this->employee_no, $carbonNow);
        $direct_superior = auth()->user()->employee->superior->fullname ?? 'Belum ada Atasan';
        
        if ($type == 'ijin') {
            return view('employee_leave.create', [
                'leaves' => Leave::where('leave_category', 'izin')
                            ->whereNotIn('leave_code', ['LVAL', 'LVSTD'])
                            ->get(),
                'direct_superior' => $direct_superior,
                'quota' => $checkLeaveQuota,
            ]);
        }elseif ($type == 'cuti') {
            return view('employee_leave.create_cuti', [
                'leaves' => Leave::where('leave_category', 'cuti')->get(),
                'direct_superior' => $direct_superior,
                'quota' => $checkLeaveQuota,
            ]);
        }elseif ($type == 'direct'){
            if (!auth()->user()->hasRole('Personnel')) {
                abort(403);
            }
            return view('employee_leave.create_direct', [
                'leaves' => Leave::where('leave_category', 'izin')->orWhere('leave_code', 'LVANL')->get(),
            ]);
        }else{
            abort(404);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeLeaveRequest $request)
    {
        if ($request->leave_type == 'direct') {
            if (!auth()->user()->hasRole('Personnel')) {
                abort(403);
            }
            DB::beginTransaction();
            try {
                $employee_no = Employee::where('id', $request->employee_id)->first();
                $nik = $employee_no->registration_number;
                $leave = DB::table('leaves')->where('leave_code', $request->leave_code)->first();
                $is_extend = false;
                $carbonRequest = Carbon::parse($request->start_date);

            
                if ($leave->is_minus_annual == 1) {
                    $date_of_work = Carbon::parse($employee_no->date_of_work);
                    $allowed_date = $date_of_work->addYear();
                    if ($request->leave_code == 'LVANL') {
                        if ($carbonRequest < $allowed_date) {
                            return response()->json([
                                'error' => true,
                                'msg' => "<h3>Maaf baru bisa mengajukan cuti pada <strong>".$allowed_date->format('d F Y')."</strong></h3>
                                <small>Silahkan gunakan pengajuan izin.</small>"
                            ]);
                        }
                    }
                    $checkLeaveQuota = $this->leave_service->checkLeaveQuota($nik, $carbonRequest);
                    $checkTotalDays = $this->leave_service->checkTotalDays($nik, $request->start_date, $request->end_date);

                    if ($checkLeaveQuota->status == 'error') {
                        return response()->json([
                            'error' => true,
                            'msg' => $checkLeaveQuota->msg
                        ]);
                    }

                    if ($checkTotalDays->QtyIsPengajuan > 0) {
                        return response()->json([
                            'error' => true,
                            'msg' => 'Ada pengajuan di rentang tanggal tersebut.'
                        ]);
                    }else{
                        if ($checkTotalDays->QtyWorkingDays == 0) {
                            return response()->json([
                                'error' => true,
                                'msg' => 'Tidak ada hari kerja pada rentang tanggal tersebut.'
                            ]);
                        }
                        if ($checkLeaveQuota->msg->qty_extend && $checkLeaveQuota->msg->ext_sts) {
                            if ($checkTotalDays->QtyWorkingDays > $checkLeaveQuota->msg->sisa_extend) {
                                return response()->json([
                                    'error' => true,
                                    'msg' => 'Ada quota extend sebesar '.$checkLeaveQuota->msg->sisa_extend.' hari silahkan pakai terlebih dahulu'
                                ]);
                            }
                        }
                        if (($checkLeaveQuota->msg->qty - $checkTotalDays->QtyWorkingDays) < -99) {
                            return response()->json([
                                'error' => true,
                                'msg' => 'Quota cuti melebihi -99 hari. Kuota '.$checkLeaveQuota->msg->qty.' hari sedangkan yang diajukan '.$checkTotalDays->QtyWorkingDays.' hari'
                            ]);
                        }elseif(($checkLeaveQuota->msg->qty - $checkTotalDays->QtyWorkingDays) < -6 && $request->leave_code == 'LVANL'){
                            return response()->json([
                                'error' => true,
                                'msg' => 'Quota cuti melebihi -6 hari. Kuota '.$checkLeaveQuota->msg->qty.' hari sedangkan yang diajukan '.$checkTotalDays->QtyWorkingDays.' hari'
                            ]);
                        }else{
                            $checkMaxDays = $this->leave_service->checkMaxDays($request->leave_code, $checkTotalDays->QtyDays, $checkTotalDays->QtyWorkingDays);

                            if ($checkMaxDays->status == 'error') {
                                return response()->json([
                                    'error' => true,
                                    'msg' => $checkMaxDays->msg
                                ]);
                            }else{
                                if ($checkLeaveQuota->msg->qty_extend && $checkLeaveQuota->msg->ext_sts && 
                                        $checkLeaveQuota->msg->sisa_extend >= $checkMaxDays->msg) {
                                    DB::update('update leave_quota_extends
                                    set used = used+?, status = if(used >= qty, 0, 1), updated_at = now()
                                    where employee_no = ? and quota_id = ?', [$checkMaxDays->msg, $nik, $checkLeaveQuota->msg->quota_id]);
                                    $is_extend = true;
                                }else{
                                    DB::table('employee_leave_quotas')->where([
                                        'start_date' => $checkLeaveQuota->msg->start_date,
                                        'end_date' => $checkLeaveQuota->msg->end_date,
                                        'employee_no' => $nik,
                                        ])->update([
                                            'used' => $checkLeaveQuota->msg->used+$checkMaxDays->msg,
                                        ]);
                                }

                                $employeeLeave = EmployeeLeave::create([
                                    'employee_no' => $nik,
                                    'leave_type' => $request->leave_code,
                                    'start_date' => $request->start_date,
                                    'end_date' => $request->end_date,
                                    'reason' => $request->reason,
                                    'status' => 'apv',
                                    'approval_by' => auth()->user()->employee->registration_number,
                                    'total' => $checkMaxDays->msg,
                                    'is_extend' => $is_extend
                                ]);

                                $employee_no->user->notify(new NotifApprovalCuti($employeeLeave, [
                                    'atasan_name' => auth()->user()->employee->fullname,
                                ]));

                                DB::commit();

                                session()->flash('alert', [
                                    'type' => 'success',
                                    'msg' => 'Input ijin berhasil, mengurangi quota sebanyak '.$checkMaxDays->msg.' hari'
                                ]);
                        
                                return response()->json([
                                    'redirect' => route('employee-leave.create', 'direct')
                                ]);
                            }
                        }
                    }
                }else{
                    $total_days = Carbon::parse($request->end_date)->diffInDays($request->start_date)+1;
                    $employeeLeave = EmployeeLeave::create([
                        'employee_no' => $nik,
                        'leave_type' => $request->leave_code,
                        'start_date' => $request->start_date,
                        'end_date' => $request->end_date,
                        'start_time' => $request->start_time ?? null,
                        'end_time' => $request->end_time ?? null,
                        'reason' => $request->reason,
                        'status' => 'apv',
                        'approval_by' => auth()->user()->employee->registration_number,
                        'total' => $total_days
                    ]);

                    $employee_no->user->notify(new NotifApprovalCuti($employeeLeave, [
                        'atasan_name' => auth()->user()->employee->fullname,
                    ]));

                    DB::commit();

                    session()->flash('alert', [
                        'type' => 'success',
                        'msg' => 'Input ijin berhasil'
                    ]);
            
                    return response()->json([
                        'redirect' => route('employee-leave.create', 'direct')
                    ]);
                }
            } catch (Exception $ex) {
                DB::rollBack();
                return response()->json([
                    'error' => true,
                    'msg' => $ex->getMessage()
                ]);
            }
        }

        if ($request->leave_type == 'cuti') {
            $carbonNow = now();
            $date_of_work = Carbon::parse(auth()->user()->employee->date_of_work);
            $allowed_date = $date_of_work->addYear();
        
            if ($carbonNow < $allowed_date && $request->leave_code == 'LVANL') {
                return response()->json([
                    'error' => true,
                    'msg' => "<h3>Maaf anda baru bisa mengajukan cuti pada <strong>".$allowed_date->format('d F Y')."</strong></h3>
                    <small>Silahkan gunakan pengajuan izin.</small>"
                ]);
            }

            $diff_day = $carbonNow->startOfDay()->diffInDays(Carbon::parse($request->start_date));
            if ($diff_day < 6 && $request->leave_code == 'LVANL') {
                return response()->json([
                    'error' => true,
                    'msg' => 'Minimal pengajuan 7 hari, ajukan pada '.$carbonNow->startOfDay()->addDays(7)->format('d M Y')
                ]);
            }
            $checkLeaveQuota = $this->leave_service->checkLeaveQuota($this->employee_no, Carbon::parse($request->start_date));
            $checkTotalDays = $this->leave_service->checkTotalDays($this->employee_no, $request->start_date, $request->end_date);

            if ($checkLeaveQuota->status == 'error') {
                return response()->json([
                    'error' => true,
                    'msg' => $checkLeaveQuota->msg
                ]);
            }

            if ($checkTotalDays->QtyIsPengajuan > 0) {
                return response()->json([
                    'error' => true,
                    'msg' => 'Ada pengajuan di rentang tanggal tersebut.'
                ]);
            }else{
                if ($checkTotalDays->QtyWorkingDays == 0) {
                    return response()->json([
                        'error' => true,
                        'msg' => 'Tidak ada hari kerja pada rentang tanggal tersebut.'
                    ]);
                }

                if ($checkLeaveQuota->msg->qty_extend && $checkLeaveQuota->msg->ext_sts && $request->leave_code == 'LVANL') {
                    if ($checkTotalDays->QtyWorkingDays > $checkLeaveQuota->msg->sisa_extend) {
                        return response()->json([
                            'error' => true,
                            'msg' => 'Ada quota extend sebesar '.$checkLeaveQuota->msg->sisa_extend.' hari silahkan pakai terlebih dahulu'
                        ]);
                    }
                }

                if (($checkLeaveQuota->msg->qty - $checkTotalDays->QtyWorkingDays) < -6 && $request->leave_code == 'LVANL') {
                    return response()->json([
                        'error' => true,
                        'msg' => 'Quota cuti melebihi -6 hari. Kuota '.$checkLeaveQuota->msg->qty.' hari sedangkan yang diajukan '.$checkTotalDays->QtyWorkingDays.' hari'
                    ]);
                }else{
                    $checkMaxDays = $this->leave_service->checkMaxDays($request->leave_code, $checkTotalDays->QtyDays, $checkTotalDays->QtyWorkingDays);

                    if ($checkMaxDays->status == 'error') {
                        return response()->json([
                            'error' => true,
                            'msg' => $checkMaxDays->msg
                        ]);
                    }else{
                        DB::beginTransaction();
                        try {
                            $employeeLeave = EmployeeLeave::create([
                                'employee_no' => $this->employee_no,
                                'leave_type' => $request->leave_code,
                                'start_date' => $request->start_date,
                                'end_date' => $request->end_date,
                                'reason' => $request->reason,
                                'status' => 'new',
                                'total' => $checkMaxDays->msg
                            ]);
                            
                            $atasan = auth()->user()->employee->superior;
                            $atasan->user->notify(new NotifNewCuti($employeeLeave));
                            DB::commit();
    
                            session()->flash('alert', [
                                'type' => 'success',
                                'msg' => 'Pengajuan cuti tahunan berhasil, menunggu approval'
                            ]);
                    
                            return response()->json([
                                'redirect' => route('employee-leave.index')
                            ]);
                        } catch (Exception $ex) {
                            DB::rollBack();
                            return response()->json([
                                'error' => true,
                                'msg' => $ex->getMessage()
                            ]);
                        }
                    }
                }
            }
        }

        if ($request->leave_type == 'ijin') {
            $leave = DB::table('leaves')->where('leave_code', $request->leave_code)->first();
            
            if ($leave->is_minus_annual == 1) {
                $checkLeaveQuota = $this->leave_service->checkLeaveQuota($this->employee_no, Carbon::parse($request->start_date));
                $checkTotalDays = $this->leave_service->checkTotalDays($this->employee_no, $request->start_date, $request->end_date);

                if ($checkLeaveQuota->status == 'error') {
                    return response()->json([
                        'error' => true,
                        'msg' => $checkLeaveQuota->msg
                    ]);
                }

                if ($checkTotalDays->QtyIsPengajuan > 0) {
                    return response()->json([
                        'error' => true,
                        'msg' => 'Ada pengajuan di rentang tanggal tersebut.'
                    ]);
                }else{
                    if ($checkTotalDays->QtyWorkingDays == 0) {
                        return response()->json([
                            'error' => true,
                            'msg' => 'Tidak ada hari kerja pada rentang tanggal tersebut.'
                        ]);
                    }

                    if ($checkLeaveQuota->msg->qty_extend && $checkLeaveQuota->msg->ext_sts && $request->leave_code == 'LVANL') {
                        if ($checkTotalDays->QtyWorkingDays > $checkLeaveQuota->msg->sisa_extend) {
                            return response()->json([
                                'error' => true,
                                'msg' => 'Ada quota extend sebesar '.$checkLeaveQuota->msg->sisa_extend.' hari silahkan pakai terlebih dahulu'
                            ]);
                        }
                    }

                    if (($checkLeaveQuota->msg->qty - $checkTotalDays->QtyWorkingDays) < -6 && $request->leave_code == 'LVANL') {
                        return response()->json([
                            'error' => true,
                            'msg' => 'Quota cuti melebihi -6 hari. Kuota '.$checkLeaveQuota->msg->qty.' hari sedangkan yang diajukan '.$checkTotalDays->QtyWorkingDays.' hari'
                        ]);
                    }else{
                        $checkMaxDays = $this->leave_service->checkMaxDays($request->leave_code, $checkTotalDays->QtyDays, $checkTotalDays->QtyWorkingDays);

                        if ($checkMaxDays->status == 'error') {
                            return response()->json([
                                'error' => true,
                                'msg' => $checkMaxDays->msg
                            ]);
                        }else{
                            $employeeLeave = EmployeeLeave::create([
                                'employee_no' => $this->employee_no,
                                'leave_type' => $request->leave_code,
                                'start_date' => $request->start_date,
                                'end_date' => $request->end_date,
                                'reason' => $request->reason,
                                'status' => 'new',
                                'total' => $checkMaxDays->msg
                            ]);

                            $atasan = auth()->user()->employee->superior;
                            $atasan->user->notify(new NotifNewCuti($employeeLeave));

                            session()->flash('alert', [
                                'type' => 'success',
                                'msg' => 'Pengajuan ijin berhasil, menunggu approval'
                            ]);
                    
                            return response()->json([
                                'redirect' => route('employee-leave.index')
                            ]);
                        }
                    }
                }
            }else{
                $total_days = Carbon::parse($request->end_date)->diffInDays($request->start_date)+1;
                $employeeLeave = EmployeeLeave::create([
                    'employee_no' => $this->employee_no,
                    'leave_type' => $request->leave_code,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'start_time' => $request->start_time ?? null,
                    'end_time' => $request->end_time ?? null,
                    'reason' => $request->reason,
                    'status' => 'new',
                    'total' => $total_days
                ]);

                $atasan = auth()->user()->employee->superior;
                $atasan->user->notify(new NotifNewCuti($employeeLeave));

                session()->flash('alert', [
                    'type' => 'success',
                    'msg' => 'Pengajuan ijin berhasil, menunggu approval'
                ]);
        
                return response()->json([
                    'redirect' => route('employee-leave.index')
                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmployeeLeave  $employeeLeave
     * @return \Illuminate\Http\Response
     */
    public function show(EmployeeLeave $employeeLeave)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmployeeLeave  $employee_leave
     * @return \Illuminate\Http\Response
     */
    public function edit(EmployeeLeave $employee_leave)
    {
        if ($employee_leave->status == 'pending' && $employee_leave->employee_no == $this->employee_no) {
            $leaves = Leave::all();

            return view('employee_leave.edit', compact('employee_leave', 'leaves'));
        }else{
            abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmployeeLeave  $employee_leave
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmployeeLeave $employee_leave)
    {
        if ($employee_leave->status == 'pending' && $employee_leave->employee_no == $this->employee_no) {
            $employee_leave->update($request->all());

            session()->flash('alert', [
                'type' => 'success',
                'msg' => 'Pengajuan cuti diupdate, menunggu approval'
            ]);
    
            return response()->json([
                'redirect' => route('employee-leave.index')
            ]);
        }else{
            abort(404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmployeeLeave  $employee_leave
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmployeeLeave $employee_leave)
    {
        if ($employee_leave->status == 'pending' && $employee_leave->employee_no == $this->employee_no) {
            $employee_leave->delete();

            session()->flash('alert', [
                'type' => 'success',
                'msg' => 'Pengajuan cuti berhasil dihapus'
            ]);
    
            return response()->json([
                'redirect' => route('employee-leave.index')
            ]);
        }else {
            abort(404);
        }
    }

    public function approval()
    {
        $employee_id = auth()->user()->employee->id;
        $teams = Employee::where('direct_superior', $employee_id)->get();
        $employee_leaves = EmployeeLeave::with('employee', 'leave', 'approved_by')->whereIn('employee_no', $teams->pluck('registration_number')->toArray())->get();
        $quota_teams = DB::select('select a.*, b.fullname, a.qty-a.used+qty_before as sisa, c.qty-c.used as sisa_extend, c.status from employee_leave_quotas a
        inner join employees b on b.registration_number = a.employee_no
        left join leave_quota_extends c on c.quota_id = a.id
        where b.direct_superior = ? and curdate() between a.start_date and a.end_date', [$employee_id]);

        return view('employee_leave.approve', compact('employee_leaves', 'quota_teams'));
    }

    public function approve(Request $request, EmployeeLeave $employee_leave)
    {
        DB::beginTransaction();
        $atasan = auth()->user()->employee;
        $requestor = $employee_leave->employee;
        $is_extend = false;

        try {
            $checkQuota = $this->leave_service->checkLeaveQuota($employee_leave->employee_no, Carbon::parse($employee_leave->start_date));
            if ($checkQuota->status == 'error') {
                throw new Exception($checkQuota->msg);
            }
            $checkQuota = $checkQuota->msg;
            if ($employee_leave->leave->is_minus_annual == 1 && $request->status == 'apv') {
                if ($checkQuota->qty_extend && $checkQuota->ext_sts) {
                    if ($checkQuota->sisa_extend < $employee_leave->total) {
                        session()->flash('alert', [
                            'type' => 'danger',
                            'msg' => 'Ada quota extend sebesar '.$checkQuota->qty_extend.' hari silahkan tolak pengajuan'
                        ]);
    
                        return response()->json();
                    }else {
                        $is_extend = true;
                        DB::update('update leave_quota_extends
                                    set used = used+?, status = if(used >= qty, 0, 1), updated_at = now()
                                    where employee_no = ? and quota_id = ?', [$employee_leave->total, $employee_leave->employee_no, $checkQuota->quota_id]);
                    }
                }else{
                    $quota = $checkQuota->qty - $employee_leave->total;
                    if ($quota < -6) {
                        session()->flash('alert', [
                            'type' => 'danger',
                            'msg' => 'Kuota Cuti Melebihi -6 yaitu '.$quota.', silahkan tolak pengajuan'
                        ]);
    
                        return response()->json();
                    }
                    DB::table('employee_leave_quotas')->where([
                        'start_date' => $checkQuota->start_date,
                        'end_date' => $checkQuota->end_date,
                        'employee_no' => $employee_leave->employee_no,
                        ])->update([
                            'used' => $checkQuota->used+$employee_leave->total,
                        ]);
                }
            }
            $employee_leave->update([
                'status' => $request->status,
                'approval_by' => $atasan->registration_number,
                'approval_note' => $request->reason,
                'is_extend' => $is_extend,
            ]);
            $requestor->user->notify(new NotifApprovalCuti($employee_leave, [
                'atasan_name' => $atasan->fullname,
            ]));
            
            DB::commit();
            session()->flash('alert', [
                'type' => 'success',
                'msg' => 'Form pengajuan sudah di '.($request->status == 'apv' ? 'Approve':'Reject')
            ]);
        } catch (Exception $ex) {
            DB::rollBack();
            session()->flash('alert', [
                'type' => 'danger',
                'msg' => $ex->getMessage()
            ]);
        }

        return response()->json();
    }

    public function calculate(Request $request)
    {
        $checkTotalDays = $this->leave_service->checkTotalDays($this->employee_no, $request->start_date, $request->end_date);

        return response()->json([
            'qty_days' => $checkTotalDays->QtyDays,
            'working_days' => $checkTotalDays->QtyWorkingDays,
        ]);
    }

    public function load_quota_cuti(Request $request)
    {
        $employee_no = DB::table('employees')->where('id', $request->employee_id)->first();
        $quota = $this->leave_service->checkLeaveQuota($employee_no->registration_number, now());

        $html = '<div class="card">
        <div class="card-header card-primary">
            <div class="d-flex align-items-center">
                <h4 class="card-title">Rekap cuti periode 
                    '.($quota->status == 'success' ? date('d M Y', strtotime($quota->msg->start_date)).' s/d '.date('d M Y', strtotime($quota->msg->end_date)):'N/A').'
                </h4>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table style="width:100%">
                    <thead>
                        <tr>
                            <th class="content">Tipe</th>
                            <th class="content">Kuota</th>
                            <th class="content">Terpakai</th>
                            <th class="content">Sisa Periode Lalu</th>
                            <th class="content">Sisa Hak Cuti</th>
                        </tr>
                    </thead>
                    <tbody>';
        if ($quota->status == 'success'){
            $html .= "<tr>
                <td class='content'>Existing</td>
                <td class='content'>".$quota->msg->qty_gen."</td>
                <td class='content'>".$quota->msg->used."</td>
                <td class='content'>".$quota->msg->qty_before."</td>
                <td class='content'>".$quota->msg->qty."</td>
            </tr>";
            if ($quota->msg->qty_extend != null){
                $html .= "<tr>
                <td class='content'>Extend</td>
                <td class='content'>{$quota->msg->qty_extend}</td>
                <td class='content'>{$quota->msg->used_extend}</td>
                <td class='content'>-</td>
                <td class='content'>";
                    if ($quota->msg->ext_sts == 1)
                        $html .= $quota->msg->qty_extend - $quota->msg->used_extend." (s/d {".date('d-m-Y', strtotime($quota->msg->expired_at)).")";
                    else
                        $html .= '<span class="badge badge-danger">Expired</span>';
            $html .= "</td>
            </tr>";
            }
        }else{
            $html .= '<tr>
                <td colspan="4" class="content">'.$quota->msg.'</td>
            </tr>';
        }

        $html .= '</tbody>
                </table>
            </div>
        </div>
        </div>';

        return response($html);
    }

    public function cuti_upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file'
        ]);

        $file = $request->file('file');
        $filename = 'temp-cuti-import.'.$file->getClientOriginalExtension();
        $file->move('uploads', $filename);

        $reader = new Xlsx();
        $path = public_path('uploads/'.$filename);
        $sheet = $reader->load($path);
        $sheetData = $sheet->getActiveSheet()->toArray();

        $index = 1;
        $date_limit = date('Y-m-d', strtotime('2020-01-01'));
        $importData = [];
        
        foreach ($sheetData as $row) {
            if ($index == 1) {
                $index++;
                continue;
            }

            if ($row[0] == '') {
                break;
            }

            $nik = $row[0];
            $date = date('Y-m-d', strtotime($row[1]));

            if ($date >= $date_limit) {
                $importData[] = (object) [
                    'nik' => $nik,
                    'date' => $date
                ];
            }
            $index++;
        }

        File::delete($path);
        $sessionTmp = uniqid();

        session()->put($sessionTmp, $importData);

        return view('employee_leave.import_cuti_bersama', [
            'sessionTmp' => $sessionTmp
        ]);
    }

    public function cuti_upload_do(Request $request)
    {
        DB::beginTransaction();
        try {
            $sessionData = session()->get($request->sessionTmp);
            session()->remove($request->sessionTmp);
            $created_at = now();

            foreach ($sessionData as $item) {
                $nik = $item->nik;
                $date = $item->date;

                $checkQuota = DB::select("select a.registration_number, a.date_of_work, b.start_date, b.end_date, (case when b.qty is null then 1 else 0 end) as qty 
                    from employees a
                    left join employee_leave_quotas b on b.employee_no = a.registration_number and ? between b.start_date and b.end_date
                    where a.registration_number = ?
                    and a.id not in (select employee_id from employee_retirements)", [$date, $nik]);
                    
                if (!$checkQuota) {
                    //Jika NIK tidak terdaftar
                    continue;
                }

                $emp = $checkQuota[0];
                $start_date = $emp->start_date;
                $end_date = $emp->end_date;

                if ($emp->qty == 1) {
                    if (substr($emp->date_of_work, -5) == '02-29') {
                        $emp->date_of_work = substr($emp->date_of_work, 0, 5).'02-28';
                    }
                    $carbonDate = Carbon::createFromDate($date);
                    $now = date('m-d', strtotime($carbonDate->format('Y-m-d')));
                    $dow = date('m-d', strtotime($emp->date_of_work));
                    $carbonDOW = Carbon::parse($emp->date_of_work);
                    $initQuota = $carbonDate->diffInYears($carbonDOW) < 1 ? 0:12;

                    if ($dow <= $now) {
                        $from = Carbon::createFromDate(date($carbonDate->year.'-m-d', strtotime($emp->date_of_work)));
                    }else{
                        $from = Carbon::createFromDate(date($carbonDate->year.'-m-d', strtotime($emp->date_of_work)))->subYear();
                    }
                    $to = $from->copy()->addYear()->subDay();

                    $start_date = $from->format('Y-m-d');
                    $end_date = $to->format('Y-m-d');

                    DB::table('employee_leave_quotas')->insert([
                        'employee_no' => $emp->registration_number,
                        'start_date' => $start_date,
                        'end_date' => $end_date,
                        'qty' => $initQuota,
                        'used' => 0,
                        'qty_before' => 0,
                    ]);
                }

                $checkPengajuan = DB::select("select * from employee_leaves a
                    inner join leaves b on b.leave_code = a.leave_type
                    where a.employee_no = ? and (b.leave_category = 'cuti' or a.leave_type in ('LVAL', 'LVSTD', 'LVSTD'))
                    and ? between a.start_date and a.end_date", [$nik, $date]);

                if (!$checkPengajuan) {
                    //Jika tidak ada pengajuan
                    DB::update('update employee_leave_quotas set used = used + 1 where employee_no = ? and start_date = ? and end_date = ?', [
                        $nik, $start_date, $end_date
                    ]);

                    DB::table('employee_leaves')->insert([
                        'employee_no' => $nik,
                        'leave_type' => 'LVANL',
                        'start_date' => $date,
                        'end_date' => $date,
                        'reason' => 'Cuti bersama tgl '.$date,
                        'status' => 'apv',
                        'total' => 1,
                        'approval_by' => 0,
                        'created_at' => $created_at
                    ]);
                }
            }

            DB::commit();
            return redirect(route('employee-leave.create', 'direct'))->with('alert', [
                'type' => 'success',
                'msg' => 'Berhasil upload data cuti bersama'
            ]);
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect(route('employee-leave.create', 'direct'))->with('alert', [
                'type' => 'danger',
                'msg' => $ex->getMessage()
            ]);
        }
    }
}
