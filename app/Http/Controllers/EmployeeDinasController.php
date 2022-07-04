<?php

namespace App\Http\Controllers;

use File;
use PDF;
use Exception;
use Carbon\Carbon;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\EmployeeDinas;
use Illuminate\Support\Facades\DB;
use App\Notifications\NotifNewDinas;
use App\Notifications\NotifApprovalDinas;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class EmployeeDinasController extends Controller
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
	public function cetak($id)
    {
			$employee_id = auth()->user()->employee->id;
						
		    $pengajuan = DB::select('select a.*, b.fullname as nama_atasan,
			c.fullname, c.job_title_name, c.level_title_name from employee_dinas_luar as a, employees as  b, employeemaster as c
			where a.approval_by = b.registration_number and a.employee_no = c.employee_id and  a.id = ?',[$id]);

			$pdf = PDF::loadView('employee_dinas.cetak', compact('pengajuan'))->setPaper('b5', 'landscape');
		    return $pdf->stream();
	} 
	
    public function index()
    {
        // if (auth()->user()->hasRole('Personnel')) {
        //     $employee_leaves = EmployeeDinas::with('leave')->get();
        // }else{
            $employee_leaves = EmployeeDinas::with('leave', 'approved_by')->where('employee_no', $this->employee_no)->get();
        // }

        return view('employee_dinas.index', compact('employee_leaves'));
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
		$employee_dl = DB::select('select a.*, b.fullname from employee_dinas_luar a
				        inner join employees b on b.registration_number = a.employee_no
				        where b.id = ?', [$employee_id]);
        $direct_superior = auth()->user()->employee->superior->fullname ?? 'Belum ada Atasan';
        
        return view('employee_dinas.create', [
                'direct_superior' => $direct_superior,
				'employee_dl' => $employee_dl,
            ]);
    }
	
	public function create_direct()
    {
        $carbonNow = now();
		$employee_id = auth()->user()->employee->id;
		
		$employees = Employee::all();

        $direct_superior = auth()->user()->employee->superior->fullname ?? 'Belum ada Atasan';
        
        return view('employee_dinas.create_direct', [
                'direct_superior' => $direct_superior,
				'employees' => $employees,
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
				
				$employeeDinas = EmployeeDinas::create([
                    'employee_no' => $nik,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'start_time' => $request->start_time ?? null,
                    'end_time' => $request->end_time ?? null,
                    'reason' => $request->reason,
                    'status' => 'new',
					'created_at'=> $now,
                    'total' => $request->total
                ]);
			
			//$requestor = auth()->user()->employee;
			
			$atasan = auth()->user()->employee->superior;       
			$atasan->user->notify(new NotifNewDinas($employeeDinas));
			
            DB::commit();
            return redirect(route('employee-dinas.create'))->with('alert', [
                'type' => 'success',
                'msg' => 'Pengajuan Izin Dinas Luar Anda Berhasil, menunggu approval',
            ]);
			
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('alert', [
                'type' => 'danger',
                'msg' => $ex->getMessage()
            ]);
        } 
    }
	
	
	public function store_direct(Request $request)
    {
       DB::beginTransaction();
        try { 
				$employee_id = auth()->user()->employee->id;	
				$employee_no = Employee::where('id', $employee_id)->first();
                $nik = $employee_no->registration_number;
				$now = now();
				
				$employeeDinas = EmployeeDinas::create([
                    'employee_no' => $request->employee_no,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'start_time' => $request->start_time ?? null,
                    'end_time' => $request->end_time ?? null,
                    'reason' => $request->reason,
                    'status' => 'apv',
					'approval_by' => auth()->user()->employee->registration_number,
					'created_at'=> $now,
                    'total' => $request->total
                ]);
					
            DB::commit();
            session()->flash('alert', [
                             'type' => 'success',
                             'msg' => 'Input dinas luar berhasil'
                                ]);
                        
            return response()->json([
                                    'redirect' => route('employee-dinas.create_direct')
             ]);
			
        }
			catch (Exception $ex) {
                DB::rollBack();
                return response()->json([
                    'error' => true,
                    'msg' => $ex->getMessage()
                ]);
            }
		
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmployeeDinas  $employeeLeave
     * @return \Illuminate\Http\Response
     */
    public function show(EmployeeDinas $employeeLeave)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmployeeDinas  $employee_leave
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
     * @param  \App\Models\EmployeeDinas  $employee_leave
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmployeeDinas $employee_dinas)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmployeeDinas  $employee_leave
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmployeeDinas $employee_leave)
    {
       
    }

    public function approval()
    {
		$employee_id = auth()->user()->employee->id;
        $teams = Employee::where('direct_superior', $employee_id)->get(); 
        
		$employee_dinas = DB::select("select a.*, b.fullname from employee_dinas_luar a
        inner join employees b on b.registration_number = a.employee_no
        where a.status='new' and b.direct_superior = ?", [$employee_id]);
		
        return view('employee_dinas.approve', compact('employee_dinas'));  
    }

    public function approve(Request $request, EmployeeDinas $employee_dinas)
    {
        DB::beginTransaction();
        $atasan = auth()->user()->employee;
		
        $requestor = $employee_dinas->employee;
		
        try {
	            $employee_dinas->update([
	                'status' => $request->status,
	                'approval_by' => $atasan->registration_number,
	                'approval_note' => $request->reason,
					'approval_date' => now(),
	            ]);
			
            $requestor->user->notify(new NotifApprovalDinas($employee_dinas, [
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
