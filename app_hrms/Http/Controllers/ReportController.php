<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\EmployeeLeave;
use App\Models\EmployeeDinas;
use App\Models\EmployeeWfh;
use App\Models\EmployeeIsoman;
use App\Models\EmployeeLembur;
use App\Models\EmployeeResign;
use App\Models\EmployeeRetirement;
use Yajra\DataTables\DataTables;
use App\Imports\ReportLeaveImport;
use App\Exports\EmployeeLeaveExport;
use App\Exports\EmployeeDinasExport;
use App\Exports\EmployeeIsomanExport;
use App\Exports\EmployeeWfhExport;
use App\Exports\EmployeeLemburExport;
use App\Exports\EmployeeResignExport;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Validators\ValidationException;

class ReportController extends Controller
{
    public function leave(Request $request)
    {
        $employees = Employee::all();
        $tipe = ['excel', 'csv', 'pdf'];

        if ($request->ajax() && $request->isMethod('POST')) {
            $data = EmployeeLeave::with('leave', 'employee', 'approved_by');

            if ($request->employee_no != 'all') {
                $data->where('employee_no', $request->employee_no);
            }

            if ($request->leave_type != 'all') {
                $data->whereHas('leave', function($query) use($request){
                    $query->where('leave_category', $request->leave_type);
                });
            }
			
			if (($request->start_date != null) && ($request->end_date != null)){
                $data->whereBetween('start_date', [$request->start_date,$request->end_date] );
            }
			/*
            if ($request->start_date != null) {
                $data->where('start_date', $request->start_date);
            }

            if ($request->end_date != null) {
                $data->where('end_date', $request->end_date);
            }
			*/
            
			if ($request->status != 'all') {
                $data->where('employee_leaves.status', $request->status);
            }
			
            $data->select('employee_leaves.*');

            return DataTables::of($data)
                    ->addColumn('status', function($row){
                        return leave_status($row->status);
                    })
                    ->addColumn('action', function($row){
                        // Ini untuk kalau cuti bersama aja
                        // if ($row->leave_type == 'LVANL' && $row->status == 'apv' && $row->approval_by == 0) {
                            return '<button class="btn btn-danger btn-sm" onclick="delete_leave(\''.$row->id.'\')"><i class="fa fa-trash"></i> Hapus</button>';
                        // }
                    })
                    ->rawColumns(['status', 'action'])
                    ->make(true);
        }

        $leave_types = DB::table('lookups')->where('category', 'LVTYPE')->get();

        return view('report.leave', compact('employees', 'tipe', 'leave_types'));
    }
	
	
	public function dinas(Request $request)
    {
        $employees = Employee::all();
        $tipe = ['excel', 'csv', 'pdf'];

        if ($request->ajax() && $request->isMethod('POST')) {
            $data = EmployeeDinas::with('employee', 'approved_by');

            if ($request->employee_no != 'all') {
                $data->where('employee_no', $request->employee_no);
            }

            if (($request->start_date != null) && ($request->end_date != null)){
                $data->whereBetween('start_date', [$request->start_date,$request->end_date] );
            }

            //if ($request->end_date != null) {
            //    $data->where('end_date', $request->end_date);
            //}
            
			if ($request->status != 'all') {
                $data->where('employee_dinas_luar.status', $request->status);
            }
			
            //$data->select('employee_leaves.*');

            return DataTables::of($data)
					->addColumn('status', function($row){
                        return leave_status($row->status);
                    })
                    ->addColumn('action', function($row){
                            return '<button class="btn btn-danger btn-sm" onclick="delete_dinas(\''.$row->id.'\')"><i class="fa fa-trash"></i> Hapus</button>';
                    })
                    ->rawColumns(['status','action'])
                    ->make(true);
        }

        //$leave_types = DB::table('lookups')->where('category', 'LVTYPE')->get();

        return view('report.dinas', compact('employees', 'tipe'));
    }
	
	public function isoman(Request $request)
    {
        $employees = Employee::all();
        $tipe = ['excel', 'csv', 'pdf'];

        if ($request->ajax() && $request->isMethod('POST')) {
            $data = EmployeeIsoman::with('employee', 'approved_by');

            if ($request->employee_no != 'all') {
                $data->where('employee_no', $request->employee_no);
            }

            if (($request->start_date != null) && ($request->end_date != null)){
                $data->whereBetween('start_date', [$request->start_date,$request->end_date] );
            }

            //if ($request->end_date != null) {
            //    $data->where('end_date', $request->end_date);
            //}
            
			if ($request->status != 'all') {
                $data->where('employee_isoman.status', $request->status);
            }
			
            //$data->select('employee_leaves.*');

            return DataTables::of($data)
					->addColumn('status', function($row){
                        return leave_status($row->status);
                    })
                    ->addColumn('action', function($row){
                            return '<button class="btn btn-danger btn-sm" onclick="delete_dinas(\''.$row->id.'\')"><i class="fa fa-trash"></i> Hapus</button>';
                    })
                    ->rawColumns(['status','action'])
                    ->make(true);
        }

        //$leave_types = DB::table('lookups')->where('category', 'LVTYPE')->get();

        return view('report.isoman', compact('employees', 'tipe'));
    }
	
	public function wfh(Request $request)
    {
        $employees = Employee::all();
        $tipe = ['excel', 'csv', 'pdf'];

        if ($request->ajax() && $request->isMethod('POST')) {
            $data = EmployeeWfh::with('employee', 'approved_by');

            if ($request->employee_no != 'all') {
                $data->where('employee_no', $request->employee_no);
            }

            if (($request->start_date != null) && ($request->end_date != null)){
                $data->whereBetween('start_date', [$request->start_date,$request->end_date] );
            }

            //if ($request->end_date != null) {
            //    $data->where('end_date', $request->end_date);
            //}
            
			if ($request->status != 'all') {
                $data->where('employee_wfh.status', $request->status);
            }
			
            //$data->select('employee_leaves.*');

            return DataTables::of($data)
					->addColumn('status', function($row){
                        return leave_status($row->status);
                    })
                    ->addColumn('action', function($row){
                            return '<button class="btn btn-danger btn-sm" onclick="delete_wfh(\''.$row->id.'\')"><i class="fa fa-trash"></i> Hapus</button>';
                    })
                    ->rawColumns(['status','action'])
                    ->make(true);
        }

        //$leave_types = DB::table('lookups')->where('category', 'LVTYPE')->get();

        return view('report.wfh', compact('employees', 'tipe'));
    }
	
	public function lembur(Request $request)
    {
        $employees = Employee::all();
        $tipe = ['excel', 'csv', 'pdf'];

        if ($request->ajax() && $request->isMethod('POST')) {
            $data = EmployeeLembur::with('employee', 'approved_by');

            if ($request->employee_no != 'all') {
                $data->where('employee_no', $request->employee_no);
            }

            if (($request->start_date != null) && ($request->end_date != null)){
                $data->whereBetween('start_date', [$request->start_date,$request->end_date] );
            }

            //if ($request->end_date != null) {
            //    $data->where('end_date', $request->end_date);
            //}
            
			if ($request->status != 'all') {
                $data->where('employee_lembur.status', $request->status);
            }
			
            //$data->select('employee_leaves.*');

            return DataTables::of($data)
					->addColumn('status', function($row){
                        return leave_status($row->status);
                    })
                    ->addColumn('action', function($row){
                            return '<button class="btn btn-danger btn-sm" onclick="delete_lembur(\''.$row->id.'\')"><i class="fa fa-trash"></i> Hapus</button>';
                    })
                    ->rawColumns(['status','action'])
                    ->make(true);
        }

        //$leave_types = DB::table('lookups')->where('category', 'LVTYPE')->get();

        return view('report.lembur', compact('employees', 'tipe'));
    }
	
	public function resign(Request $request)
    {
        $employees = EmployeeRetirement::with('employee');
        $tipe = ['excel'];

        if ($request->ajax() && $request->isMethod('POST')) {
            $data = EmployeeResign::orderBy('fullname','asc')->get();

            if ($request->employee_no != 'all') {
                $data->where('employee_no', $request->employee_no);
            }
			
            //$data->select('employee_leaves.*');

            return DataTables::of($data)
                    ->make(true);
        }

        //$leave_types = DB::table('lookups')->where('category', 'LVTYPE')->get();

        return view('report.resign', compact('employees', 'tipe'));
    }

    public function leave_import(Request $request)
    {
        $validator = Validator::make([
            'file' => $request->file('file'),
            'ekstensi' => strtolower($request->file('file')->getClientOriginalExtension())
        ],[
            'file' => 'required|file',
            'ekstensi' => 'required|in:xlsx'
        ]);

        if ($validator->fails()) {
            return response()->json($validator);
        }

        $importer = new ReportLeaveImport;
        $reset_string = '';

        try {
            $importer->import($request->file('file'));

            return redirect()->back()->with('alert',[
                'type' => 'success',
                'msg' => 'Berhasil'.$reset_string.' mengimport: '.$importer->counter.' data training'
            ]);
        } catch (ValidationException $e) {
            $failures = $e->failures();
            return redirect()->back()->with('import_error', $failures);
        } catch (Exception $e) {
            return redirect()->back()->with('alert', [
                'type' => 'danger',
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
     * Export Excel
     *
     * @return void
     */
    public function leave_excel(Request $request)
    {
        return Excel::download(new EmployeeLeaveExport([
            'employee_no' => $request->employee_no,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]), 'report-leave-'.date('Y-m-d').'.xlsx');
    }
	
	public function dinas_excel(Request $request)
    {
        return Excel::download(new EmployeeDinasExport([
            'employee_no' => $request->employee_no,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]), 'report-dinas-luar-'.date('Y-m-d').'.xlsx');
    }
	
	public function isoman_excel(Request $request)
    {
        return Excel::download(new EmployeeIsomanExport([
            'employee_no' => $request->employee_no,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]), 'report-isoman-'.date('Y-m-d').'.xlsx');
    }
	
	public function wfh_excel(Request $request)
    {
        return Excel::download(new EmployeeWfhExport([
            'employee_no' => $request->employee_no,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]), 'report-wfh-'.date('Y-m-d').'.xlsx');
    }
	
	public function lembur_excel(Request $request)
    {
        return Excel::download(new EmployeeLemburExport([
            'employee_no' => $request->employee_no,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]), 'rekap-lembur-'.date('Y-m-d').'.xlsx');
    }
	    /**
     * Export CSV
     *
     * @return void
     */
    public function leave_csv(Request $request)
    {
        return Excel::download(new EmployeeLeaveExport([
            'employee_no' => $request->employee_no,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]), 'report-leave-'.date('Y-m-d').'.csv');
    }
	
	public function dinas_csv(Request $request)
    {
        return Excel::download(new EmployeeDinasExport([
            'employee_no' => $request->employee_no,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]), 'report-dinas-luar-'.date('Y-m-d').'.csv');
    }
	
	public function isoman_csv(Request $request)
    {
        return Excel::download(new EmployeeIsomanExport([
            'employee_no' => $request->employee_no,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]), 'report-isoman-'.date('Y-m-d').'.csv');
    }
	
	public function wfh_csv(Request $request)
    {
        return Excel::download(new EmployeeWfhExport([
            'employee_no' => $request->employee_no,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]), 'report-wfh-'.date('Y-m-d').'.csv');
    }

	public function lembur_csv(Request $request)
    {
        return Excel::download(new EmployeeLemburExport([
            'employee_no' => $request->employee_no,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]), 'rekap-lembur-'.date('Y-m-d').'.csv');
    }
    /**
     * Export PDF
     *
     * @return void
     */
    public function leave_pdf(Request $request)
    {
        return Excel::download(new EmployeeLeaveExport([
            'employee_no' => $request->employee_no,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]), 'report-leave-'.date('Y-m-d').'.pdf', \Maatwebsite\Excel\Excel::TCPDF);
    }
	
	public function dinas_pdf(Request $request)
    {
        return Excel::download(new EmployeeDinasExport([
            'employee_no' => $request->employee_no,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]), 'report-dinas-luar-'.date('Y-m-d').'.pdf', \Maatwebsite\Excel\Excel::TCPDF);
    }
	
	public function isoman_pdf(Request $request)
    {
        return Excel::download(new EmployeeIsomanExport([
            'employee_no' => $request->employee_no,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]), 'report-isoman'.date('Y-m-d').'.pdf', \Maatwebsite\Excel\Excel::TCPDF);
    }
	
	public function wfh_pdf(Request $request)
    {
        return Excel::download(new EmployeeWfhExport([
            'employee_no' => $request->employee_no,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]), 'report-wfh-'.date('Y-m-d').'.pdf', \Maatwebsite\Excel\Excel::TCPDF);
    }
	
	public function lembur_pdf(Request $request)
    {
        return Excel::download(new EmployeeLemburExport([
            'employee_no' => $request->employee_no,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]), 'rekap-lembur-'.date('Y-m-d').'.pdf', \Maatwebsite\Excel\Excel::TCPDF);
    }

    public function do_export(Request $request)
    {
        if ($request->tipe == 'excel') {
            return $this->leave_excel($request);
        } elseif ($request->tipe == 'csv') {
            return $this->leave_csv($request);
        }else{
            return $this->leave_pdf($request);
        }
    }
	
	public function do_dinas_export(Request $request)
    {
        if ($request->tipe == 'excel') {
            return $this->dinas_excel($request);
        } elseif ($request->tipe == 'csv') {
            return $this->dinas_csv($request);
        }else{
            return $this->dinas_pdf($request);
        }
    }
	
	public function do_isoman_export(Request $request)
    {
        if ($request->tipe == 'excel') {
            return $this->isoman_excel($request);
        } elseif ($request->tipe == 'csv') {
            return $this->isoman_csv($request);
        }else{
            return $this->isoman_pdf($request);
        }
    }
	
	public function do_wfh_export(Request $request)
    {
        if ($request->tipe == 'excel') {
            return $this->wfh_excel($request);
        } elseif ($request->tipe == 'csv') {
            return $this->wfh_csv($request);
        }else{
            return $this->wfh_pdf($request);
        }
    }
	
	public function do_lembur_export(Request $request)
    {
        if ($request->tipe == 'excel') {
            return $this->lembur_excel($request);
        } elseif ($request->tipe == 'csv') {
            return $this->lembur_csv($request);
        }else{
            return $this->lembur_pdf($request);
        }
    }
	
	public function do_resign_export(Request $request)
    {

        return Excel::download(new EmployeeResignExport, 'export-leave-quota-resign-'.date('Y-m-d').'.xlsx');

    }
	
	public function delete_dinas(Request $request)
    {             
        DB::beginTransaction();
        try {
			$id = $request->id;
            DB::table('employee_dinas_luar')->where('id', $id)->delete();
            DB::commit();
            return response()->json([
                        'type' => 'success',
                        'msg' => 'Berhasil hapus pengajuan',
                    ]);
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('alert', [
                'type' => 'danger',
                'msg' => $ex->getMessage()
            ]);
        } 
    }
	
	public function delete_isoman(Request $request)
    {             
        DB::beginTransaction();
        try {
			$id = $request->id;
            DB::table('employee_isoman')->where('id', $id)->delete();
            DB::commit();
            return response()->json([
                        'type' => 'success',
                        'msg' => 'Berhasil hapus pengajuan',
                    ]);
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('alert', [
                'type' => 'danger',
                'msg' => $ex->getMessage()
            ]);
        } 
    }
	
	public function delete_wfh(Request $request)
    {             
        DB::beginTransaction();
        try {
			$id = $request->id;
            DB::table('employee_wfh')->where('id', $id)->delete();
            DB::commit();
            return response()->json([
                        'type' => 'success',
                        'msg' => 'Berhasil hapus pengajuan',
                    ]);
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('alert', [
                'type' => 'danger',
                'msg' => $ex->getMessage()
            ]);
        } 
    }
	
	public function delete_lembur(Request $request)
    {             
        DB::beginTransaction();
        try {
			$id = $request->id;
            DB::table('employee_lembur')->where('id', $id)->delete();
            DB::commit();
            return response()->json([
                        'type' => 'success',
                        'msg' => 'Berhasil hapus pengajuan',
                    ]);
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('alert', [
                'type' => 'danger',
                'msg' => $ex->getMessage()
            ]);
        } 
    }

    public function delete_leave(Request $request)
    {
        DB::beginTransaction();
        try {
            $id = $request->id;
            $pengajuan = DB::table('employee_leaves AS a')
                            ->addSelect([
                                'a.id', 'b.is_minus_annual', 'a.is_extend', 'a.total', 'a.employee_no',
                                'a.start_date', 'a.end_date', 'c.expired_at', 'c.quota_id', 'a.status'
                            ])
                            ->join('leaves AS b', 'b.leave_code', '=', 'a.leave_type')
                            ->leftJoin('leave_quota_extends AS c', 'c.quota_id', '=', 'a.id')
                            ->where('a.id', $id)
                            ->first();
            if ($pengajuan) {
                /**
                 * Kalau cuti bersama aja pakai ini  && $pengajuan->leave_type == 'LVANL' 
                 *       && $pengajuan->approval_by == 0 && $pengajuan->status == 'apv'
                 */
                if ($pengajuan->is_minus_annual && $pengajuan->status == 'apv') {
                    if ($pengajuan->is_extend) {
                        DB::update('update leave_quota_extends a 
                                inner join employee_leave_quotas b on a.quota_id = b.id and ? between b.start_date and b.end_date 
                                set a.used = a.used - ?, a.status = if(curdate() < a.expired_at, 1, 0) 
                                where a.employee_no = ?', 
                                [
                                    $pengajuan->start_date, $pengajuan->total, $pengajuan->employee_no
                                ]);
                    }else{
                        DB::update('update employee_leave_quotas set used = used - ? 
                                where ? between start_date and end_date and employee_no = ?', 
                                [
                                    $pengajuan->total, $pengajuan->start_date, $pengajuan->employee_no
                                ]);
                    }
                    DB::table('employee_leaves')->where('id', $pengajuan->id)->delete();

                    DB::commit();
                    return response()->json([
                        'type' => 'success',
                        'msg' => 'Berhasil hapus pengajuan, saldo '.($pengajuan->is_extend ? 'Extend':'Existing').' bertambah',
                    ]);
                } else{
                    /**
                     * Kalau cuti bersama error ini
                     */
                    // return response()->json([
                    //     'type' => 'danger',
                    //     'msg' => 'Bukan Pengajuan Cuti Bersama',
                    // ]);
                    DB::table('employee_leaves')->where('id', $pengajuan->id)->delete();

                    DB::commit();
                    return response()->json([
                        'type' => 'success',
                        'msg' => 'Berhasil hapus pengajuan',
                    ]);
                }
            }else{
                return response()->json([
                    'type' => 'danger',
                    'msg' => 'Ketidakhadiran Tidak Ditemukan',
                ]);
            }
        } catch (Exception $ex) {
            return response()->json([
                'type' => 'danger',
                'msg' => $ex->getMessage(),
            ]);
        }
    }
}
