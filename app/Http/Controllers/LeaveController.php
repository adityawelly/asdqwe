<?php

namespace App\Http\Controllers;

use App\Exports\EmployeeLeaveQuotaExport;
use Exception;
use File;
use App\Models\Leave;
use App\Models\AnnualLeave;
use App\Exports\LeaveExport;
use App\Exports\OpnameExport;
use Illuminate\Http\Request;
use App\Services\LookupService;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\LeaveRequest;
use App\Models\Employee;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leaves = Leave::all();
        $leave_categories = LookupService::getByCategory('LVTYPE');

        return view('leave.index', compact('leaves', 'leave_categories'));
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
    public function store(LeaveRequest $request)
    {
        Leave::create($request->all());

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'msg' => 'Kategori berhasil ditambahkan'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function show(Leave $leave)
    {
        return response()->json($leave);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function edit(Leave $leave)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function update(LeaveRequest $request, Leave $leave)
    {
        $leave->update($request->all());

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'msg' => 'Kategori berhasil diupdate'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function destroy(Leave $leave)
    {
        $leave->delete();

        session()->flash('alert', [
            'type' => 'success',
            'msg' => 'Kategori berhasil dihapus'
        ]);

        return response()->json(true);
    }

    /**
     * Export Excel
     *
     * @return void
     */
    public function excel()
    {
        return Excel::download(new LeaveExport, 'leave-'.date('Y-m-d').'.xlsx');
    }

    /**
     * Export CSV
     *
     * @return void
     */
    public function csv()
    {
        return Excel::download(new LeaveExport, 'leave-'.date('Y-m-d').'.csv');
    }

    /**
     * Export PDF
     *
     * @return void
     */
    public function pdf()
    {
        return Excel::download(new LeaveExport, 'leave-'.date('Y-m-d').'.pdf', \Maatwebsite\Excel\Excel::TCPDF);
    }

    public function set_annual(Request $request)
    {
        $request->validate([
            'valid_year' => 'required|numeric|unique:annual_leaves,valid_year',
            'qty' => 'required|numeric',
        ]);

        AnnualLeave::create($request->all());

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'msg' => 'Kuota Cuti Tahunan disimpan'
        ]);
    }

    public function quota_index(Request $request)
    {
        $quotas = DB::table('v_quota_index as a')
            ->select([
                DB::raw('a.*'), DB::raw('b.*'), DB::raw('(a.qty-a.used+a.qty_before+a.qty_paid) as sisa_cuti'),
                DB::raw("IF(curdate() between a.start_date AND a.end_date, 1, 0) as cur_period"),
                DB::raw('a.id as period_id'),
                DB::raw('c.qty as qty_extend'),
                DB::raw('c.used as used_extend'),
            ])
            ->join('employees as b', 'b.registration_number', '=', 'a.employee_no')
            ->leftJoin('leave_quota_extends as c', function($join){
                $join->on('c.quota_id', '=', 'a.id');
                $join->on('c.status', '=', DB::raw(1));
            });

        if (!auth()->user()->can('create-leave')) {
            $quotas->where('a.employee_no', auth()->user()->employee->registration_number);
        }

        if ($request->has('employee_no')) {
            $quotas->where('a.employee_no', $request->employee_no);
        }

        $employees = Employee::all();
		//$employees = DB::select('select * from hrms.employees where id not in (select employee_id from employee_retirements)');

        $extends = DB::select('select a.*, b.fullname, c.start_date, c.end_date from leave_quota_extends a
        inner join employees b on b.registration_number = a.employee_no
        inner join employee_leave_quotas c on c.id = a.quota_id');

        return view('leave.quota_index', [
            'quotas' => $quotas->get(),
            'employees' => $employees,
            'extends' => $extends,
        ]);
    }

    public function quota_import(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
        ]);

        $file = $request->file('file');
        $filename = 'temp-quota-import.'.$file->getClientOriginalExtension();
        $file->move('uploads', $filename);

        $reader = new Xlsx();
        $path = public_path('uploads/'.$filename);
        $sheet = $reader->load($path);
        $sheetData = $sheet->getActiveSheet()->toArray();

        $index = 1;
        $quotaData = [];

        foreach ($sheetData as $data) {
            if ($index == 1){
                $index++;
                continue;
            }

            if ($data[0] == ''){
                break;
            }

            $quotaData[] = (object) [
                'nik' => $data[0],
                'start_date' => date('Y-m-d', strtotime($data[1])),
                'end_date' => date('Y-m-d', strtotime($data[2])),
                'kuota' => $data[3],
                'kuota_terpakai' => $data[4],
                'kuota_periode_sebelumnya' => $data[5],
                'opname' => $data[6],
            ];

            $index++;
        }

        File::delete($path);

        $sessionTmp = uniqid();

        session()->put($sessionTmp, $quotaData);

        return view('leave.import_quota_check', [
            'quotas' => $quotaData,
            'sessionTmp' => $sessionTmp,
            'reset_data' => $request->has('reset_data') ? 1:0
        ]);
    }

    public function quota_import_save(Request $request)
    {
        DB::beginTransaction();
        try {
            $sessionData = session()->get($request->sessionTmp);
            session()->remove($request->sessionTmp);
            $insertData = [];
            $opnameData = [];

            foreach ($sessionData as $item) {
                $insertData[] = [
                    'employee_no' => $item->nik,
                    'start_date' => $item->start_date,
                    'end_date' => $item->end_date,
                    'qty' => $item->kuota,
                    'used' => $item->kuota_terpakai,
                    'qty_before' => $item->kuota_periode_sebelumnya,
                ];
                if ($item->opname != 0) {
                    $opnameData[] = [
                        'start_date' => $item->start_date,
                        'end_date' => $item->end_date,
                        'employee_no' => $item->nik,
                        'qty' => $item->opname,
                        'status' => 'new',
                        'note' => 'imported',
                        'created_at' => now()
                    ];
                }
            }

            if ($request->reset_data == 1) {
                DB::table('employee_leave_quotas')->delete();
            }

            DB::table('employee_leave_quotas')->insert($insertData);

            if (count($opnameData) > 0) {
                DB::table('employee_opname_quotas')->insert($opnameData);
            }

            DB::commit();
            return redirect(route('leave.quota_index'))->with('alert', [
                'type' => 'success',
                'msg' => 'Berhasil import quota'
            ]);

        } catch (Exception $ex) {
            DB::rollback();
            return redirect(route('leave.quota_index'))->with('alert', [
                'type' => 'danger',
                'msg' => $ex->getMessage()
            ]);
        }
    }

    public function opname_index()
    {
        $opnames = DB::select('SELECT a.*, a.employee_no AS nik, b.fullname, c.employee_id
                FROM employee_opname_quotas a
                INNER JOIN employees b ON b.registration_number = a.employee_no
                LEFT JOIN employee_retirements c ON c.employee_id = b.id');

        return view('leave.opname_index', [
            'opnames' => $opnames,
        ]);
    }

    public function opname_update(Request $request)
    {
        if (!$request->has('ids')) {
            return redirect()->back()->with('alert', [
                'type' => 'danger',
                'msg' => 'Silahkan pilih data',
            ]);
        }

        DB::table('employee_opname_quotas')->whereIn('id', $request->ids)->update([
            'status' => $request->status,
            'note' => $request->note,
        ]);

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'msg' => 'Berhasil update status opname',
        ]);
    }

    public function quota_export()
    {
        return Excel::download(new EmployeeLeaveQuotaExport, 'export-leave-quota-'.date('Y-m-d').'.xlsx');
    }

    public function opname_export()
    {
        return Excel::download(new OpnameExport, 'export-opname-'.date('Y-m-d').'.xlsx');
    }

    public function submit_extend(Request $request)
    {
        $request->validate([
            'employee_no' => 'required',
            'period_id' => 'required',
            'qty' => 'required|numeric',
            'expired_at' => 'bail|nullable|date'
        ]);

        $data = $request->all();

        if ($data['expired_at'] == null) {
            $data['expired_at'] = Carbon::parse($data['start_date'])->addMonths(6);
        }

        try {
            DB::table('leave_quota_extends')->insert([
                'employee_no' => $data['employee_no'],
                'quota_id' => $data['period_id'],
                'qty' => $data['qty'],
                'used' => 0,
                'expired_at' => $data['expired_at'],
                'status' => now() > $data['expired_at'] ? 0:1
            ]);

            return redirect()->back()->with('alert', [
                'type' => 'success',
                'msg' => 'Kuota extend berhasil ditambahkan'
            ]);
        } catch (Exception $ex) {
            return redirect()->back()->with('alert', [
                'type' => 'danger',
                'msg' => $ex->getMessage()
            ]);
        }
    }
}
