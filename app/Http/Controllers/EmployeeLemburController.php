<?php

namespace App\Http\Controllers;

use File;
use Exception;
use Carbon\Carbon;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\EmployeeLembur;
use App\Services\LeaveService;
use Illuminate\Support\Facades\DB;
use App\Notifications\NotifNewLembur;
use App\Notifications\NotifApprovalLembur;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class EmployeeLemburController extends Controller
{
    private $employee_no;
    //private $leave_service;

    /**
     * Controller's construct function
     */
    public function __construct() {
        $this->middleware(function ($request, $next) {
            $this->employee_no = auth()->user()->employee->registration_number ?? 0;
            return $next($request);
        });
       // $this->leave_service = new LeaveService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if (auth()->user()->hasRole('Personnel')) {
        //     $employee_leaves = EmployeeLembur::with('leave')->get();
        // }else{
            $employee_leaves = EmployeeLembur::with('leave', 'approved_by')->where('employee_no', $this->employee_no)->get();
        // }

        return view('employee_lembur.index', compact('employee_leaves'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $carbonNow = now();
		$employee_id = auth()->user()->employee->id;
        //$checkLeaveQuota = $this->leave_service->checkLeaveQuota($this->employee_no, $carbonNow);
		$employee_dl = DB::select('select a.*, b.fullname from employee_lembur a
				        inner join employees b on b.registration_number = a.employee_no
				        where b.id = ?', [$employee_id]);
        $direct_superior = auth()->user()->employee->superior->fullname ?? 'Belum ada Atasan';
        
        return view('employee_lembur.create', [
                'direct_superior' => $direct_superior,
				'employee_dl' => $employee_dl,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	 
			
    public function store(Request $request)
    {
       DB::beginTransaction();
        try { 
				$employee_id = auth()->user()->employee->id;	
				$employee_no = Employee::where('id', $employee_id)->first();
                $nik = $employee_no->registration_number;
				$now = now();
				
				$start_date = $request->input('start_date', []);
				$reason = $request->input('reason', []);
				$start_time = $request->input('start_time', []);
				$end_time = $request->input('end_time', []);
				$approv = $request->input('approval_position', []);
				
                for ($sdate=0; $sdate < count($start_date); $sdate++) {
                    if ($start_date[$sdate] != '') 
                    {
						$checkTotalDays = $checkTotalDays = DB::select('CALL SP_LEAVE_IsPengajuan(?, ?, ?)', [$nik, $start_date[$sdate], $start_date[$sdate]]);
						
						foreach($checkTotalDays as $checkTotalDays)
						{
							$workdays = $checkTotalDays->QtyWorkingDays;
							$qty = $checkTotalDays->QtyDays;
							$weekend = $qty - $workdays;
						}
						
                        $employeeLembur = EmployeeLembur::create([
							'employee_no' => $nik,
							'start_date' => $start_date[$sdate],
							'end_date' => $start_date[$sdate],
							'start_time' => $start_time[$sdate],
							'end_time' => $end_time[$sdate],
							'approval_position' => $approv[$sdate],
							'reason' => $reason[$sdate],
							'status' => 'new',
							'created_at'=> $now,
							'total'=>$workdays,
							'total_libur'=>$weekend
						]);
						
						$requestor = auth()->user()->employee;
			
						$atasan = auth()->user()->employee->superior;       
						$atasan->user->notify(new NotifNewLembur($employeeLembur));
                    }
                }
				
				
			
			
			
			
            DB::commit();
            return redirect(route('employee-lembur.create'))->with('alert', [
                'type' => 'success',
                'msg' => 'Pengajuan Over Time Anda Berhasil, menunggu approval',
            ]);
			
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('alert', [
                'type' => 'danger',
                'msg' => $ex->getMessage()
            ]);
        } 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmployeeLembur  $employeeLeave
     * @return \Illuminate\Http\Response
     */
    public function show(EmployeeLembur $employeeLeave)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmployeeLembur  $employee_leave
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
     * @param  \App\Models\EmployeeLembur  $employee_leave
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmployeeLembur $employee_lembur)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmployeeLembur  $employee_leave
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmployeeLembur $employee_leave)
    {
       
    }

    public function approval()
    {
		$employee_id = auth()->user()->employee->id;
        $teams = Employee::where('direct_superior', $employee_id)->get(); 
        
		$employee_lembur = DB::select("select a.*, b.fullname from employee_lembur a
        inner join employees b on b.registration_number = a.employee_no
        where a.status='new' and b.direct_superior = ?", [$employee_id]);
		
        return view('employee_lembur.approve', compact('employee_lembur'));  
    }

    public function approve(Request $request, EmployeeLembur $employee_lembur)
    {
        DB::beginTransaction();
        $atasan = auth()->user()->employee;
		
        $requestor = $employee_lembur->employee;
		
        try {
	            $employee_lembur->update([
	                'status' => $request->status,
	                'approval_by' => $atasan->registration_number,
	                'approval_note' => $request->reason,
	            ]);
			
           $requestor->user->notify(new NotifApprovalLembur($employee_lembur, [
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
        
    }


    public function cuti_upload(Request $request)
    {
        
    }
}
