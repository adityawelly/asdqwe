<?php

namespace App\Http\Controllers;

use File;
use PDF;
use Exception;
use Carbon\Carbon;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\PKWT;
use App\Models\ListPKWT;
use App\Http\Requests\PKWTRequest;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class PKWTController extends Controller
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
        
		$pkwt = PKWT::all();
        return view('PKWT.index', compact('pkwt'));
    }
	
	 public function list_pkwt()
    {
        
		$pkwt = ListPKWT::all();
        return view('PKWT.list', compact('pkwt'));
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
    public function store(PKWTRequest $request)
    {
        PKWT::create($request->all());

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
        $division = PKWT::findOrFail($id);

        return response()->json($division);
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
    public function update(PKWTRequest $request, PKWT $division)
    {
        $division->update($request->all());

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
        $division = PKWT::findOrFail($id);

            DB::transaction(function () use ($PKWT){
                $PKWT->delete();
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
        $division = PKWT::withTrashed()->findOrFail($id);

        $division->restore();
        $division->departments()->restore();

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
        return Excel::download(new PKWTExport, 'division-'.date('Y-m-d').'.xlsx');
    }

    /**
     * Export CSV
     *
     * @return void
     */
    public function csv()
    {
        return Excel::download(new PKWTExport, 'division-'.date('Y-m-d').'.csv');
    }

    /**
     * Export PDF
     *
     * @return void
     */
    public function pdf()
    {
        return Excel::download(new PKWTExport, 'division-'.date('Y-m-d').'.pdf', \Maatwebsite\Excel\Excel::TCPDF);
    }

    public function get_departments(PKWT $division)
    {
        $data = [];
        foreach ($division->departments as $department) {
            $data[] = [
                'id' => $department->id,
                'text' => $department->department_code.'-'.$department->department_name
            ];
        }

        return response()->json($data);
    }
	
	
	public function detail($id)
    {
        $PKWT = PKWT::find($id);		
		$PKDTL = DB::table('pkwt_draft')->where('pkwt_id', $id)->get();
		
        return view('PKWT.detail',
		[
			'PKDTL' => $PKDTL,
			'PKWT' => $PKWT,
        ]);
    }
}
