<?php

namespace App\Http\Controllers;

use File;
use PDF;
use Exception;
use Carbon\Carbon;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\PADTL;
use App\Http\Requests\PADTLRequest;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class PADTLController extends Controller
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
        
		$pkwt = PADTL::all();
        return view('PKWT.detail', compact('pkwt'));
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
    public function store(PADTLRequest $request)
    {
        PADTL::create($request->all());

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'msg' => 'Skala berhasil ditambahkan'
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
        $padtl = PADTL::where('ParamsId',$id)->first();

        return response()->json($padtl);
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
    public function update(PADTLRequest $request, PADTL $padtl)
    {
        $padtl->update($request->all());

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'msg' => 'Skala berhasil diupdate'
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
        $padtl = PADTL::findOrFail($id);

            DB::transaction(function () use ($PADTL){
                $PADTL->delete();
            });
        
        session()->flash('alert', [
            'type' => 'success',
            'msg' => 'Skala Berhasil Dihapus'
        ]);

        return response()->json(true);
    }


	
}
