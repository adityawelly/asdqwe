<?php

namespace App\Http\Controllers;

use File;
use PDF;
use Exception;
use Carbon\Carbon;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\PASUB;
use App\Models\GradeTitle;
use App\Models\ListPASUB;
use App\Http\Requests\PASUBRequest;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class PASUBController extends Controller
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
		$PASUB = DB::select("SELECT a.*, b.NamaBab, c.grade_title_name from pa_subbab as a, pa_bab as b, grade_titles as c where a.babid = b.BabId and a.GradeId = c.id");
		
		$grade = GradeTitle::select('id', 'grade_title_code', 'grade_title_name')->get();
		
		$bab = DB::table('pa_bab')->where(['Status' => 1
        ])->get();
		 
        return view('PASUB.index', compact('PASUB','grade','bab'));
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
    public function store(PASUBRequest $request)
    {
        PASUB::create($request->all());

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'msg' => 'Draft berhasil ditambahkan'
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
        //$PASUB = DB::select("SELECT a.*, b.NamaBab, c.grade_title_name from pa_subbab as a, pa_bab as b, grade_titles as c where a.SubbabId = $id and a.babid = b.BabId and a.GradeId = c.id")->first();

		$PASUB = PASUB::findOrFail($id);
        return response()->json($PASUB);
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
    public function update(PASUBRequest $request, PASUB $PASUB)
    {
        $PASUB->update($request->all());

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'msg' => 'Draft berhasil diupdate'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $PASUB = PASUB::findOrFail($id);

            DB::transaction(function () use ($PASUB){
                $PASUB->delete();
            });
        
        session()->flash('alert', [
            'type' => 'success',
            'msg' => 'Draft Berhasil Dihapus'
        ]);

        return response()->json(true);
    }

    /**
     * Restore soft deletes
     *
     * @param int $id
     * @return void
     */
    public function restore($id)
    {
        $PASUB = PASUB::withTrashed()->findOrFail($id);

        $PASUB->restore();
        $PASUB->departments()->restore();

        session()->flash('alert', [
            'type' => 'success',
            'msg' => 'Divisi berhasil dikembalikan semula'
        ]);

        return response()->json();
    }

    /**
     * Export Excel
     *
     * @return void
     */
    public function excel()
    {
        return Excel::download(new PASUBExport, 'division-'.date('Y-m-d').'.xlsx');
    }

    /**
     * Export CSV
     *
     * @return void
     */
    public function csv()
    {
        return Excel::download(new PASUBExport, 'division-'.date('Y-m-d').'.csv');
    }

    /**
     * Export PDF
     *
     * @return void
     */
    public function pdf()
    {
        return Excel::download(new PASUBExport, 'division-'.date('Y-m-d').'.pdf', \Maatwebsite\Excel\Excel::TCPDF);
    }

    public function get_departments(PASUB $PASUB)
    {
        $data = [];
        foreach ($PASUB->departments as $department) {
            $data[] = [
                'id' => $department->id,
                'text' => $department->department_code.'-'.$department->department_name
            ];
        }

        return response()->json($data);
    }
	
	
	public function detail($id)
    {
        $PASUB = DB::select("SELECT a.*, b.NamaBab, c.grade_title_name from pa_subbab as a, pa_bab as b, grade_titles as c where a.babid = b.BabId and a.GradeId = c.id and a.id = $id");		
		$PADTL = DB::select("SELECT a.*, b.Namasub, c.grade_title_name from pa_params as a, pa_subbab as b, grade_titles as c where a.SubbabId = b.id and a.GradeId = c.id and a.SubbabId = $id");
		
        return view('PASUB.detail',
		[
			'PADTL' => $PADTL,
			'PASUB' => $PASUB,
        ]);
    }
}
