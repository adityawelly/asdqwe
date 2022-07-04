<?php

namespace App\Http\Controllers;

use App\Models\PAPeriode;
use Illuminate\Http\Request;
use App\Exports\PAPeriodeExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\PAPeriodeRequest;
use Illuminate\Support\Facades\DB;

class PAPeriodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

	  $years = range(now()->year+1, now()->year-1);
      $paperiode = PAPeriode::all();
      

        return view('PAPeriode.index', compact('paperiode','years'));
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
    public function store(PAPeriodeRequest $request)
    {
        PAPeriode::create($request->all());

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'msg' => 'Periode Penilaian berhasil ditambahkan'
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
        $PAPeriode = PAPeriode::findOrFail($id);

        return response()->json($PAPeriode);
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
    public function update(PAPeriodeRequest $request, PAPeriode $PAPeriode)
    {
        $PAPeriode->update($request->all());

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'msg' => 'Periode Penilaian berhasil diupdate'
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
        $PAPeriode = PAPeriode::withTrashed()->findOrFail($id);

        if ($request->force == 'true' && auth()->user()->can('restore-PAPeriode')) {
            $PAPeriode->forceDelete();
        }else{
            DB::transaction(function () use ($PAPeriode){
                $PAPeriode->delete();
                $PAPeriode->departments()->delete();
            });
        }

        session()->flash('alert', [
            'type' => 'success',
            'msg' => 'Periode Penilaian berhasil dihapus'
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
        $PAPeriode = PAPeriode::withTrashed()->findOrFail($id);

        $PAPeriode->restore();
        $PAPeriode->departments()->restore();

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
        return Excel::download(new PAPeriodeExport, 'PAPeriode-'.date('Y-m-d').'.xlsx');
    }

    /**
     * Export CSV
     *
     * @return void
     */
    public function csv()
    {
        return Excel::download(new PAPeriodeExport, 'PAPeriode-'.date('Y-m-d').'.csv');
    }

    /**
     * Export PDF
     *
     * @return void
     */
    public function pdf()
    {
        return Excel::download(new PAPeriodeExport, 'PAPeriode-'.date('Y-m-d').'.pdf', \Maatwebsite\Excel\Excel::TCPDF);
    }

    public function get_departments(PAPeriode $PAPeriode)
    {
        $data = [];
        foreach ($PAPeriode->departments as $department) {
            $data[] = [
                'id' => $department->id,
                'text' => $department->department_code.'-'.$department->department_name
            ];
        }

        return response()->json($data);
    }
	
	public function generate($id)
    {
		$employee_id = auth()->user()->employee->id;
		
				$pengajua = DB::select('select * from pa_periode
							where id = ?',[$id]);
					
				foreach($pengajua as $pengajuan){	
					$edate = $pengajuan->edate;
					$periodeid = $pengajuan->id;
				}
					
				$employee = DB::select("select id, registration_number , date_of_work, 
										grade_title_id, level_title_id, 
										direct_superior, TIMESTAMPDIFF(MONTH, date_of_work, NOW()) AS bulan from employees 
										where  TIMESTAMPDIFF(MONTH, date_of_work, NOW()) > 2 and grade_title_id > 1 and id not in (select employee_id from employee_retirements)");
				foreach ($employee as $employees)
				{
					$emp = $employees->id;
					$gid = $employees->grade_title_id;
					$lid = $employees->level_title_id;
					$ds  = $employees->direct_superior;
					
					$now = now();
					$year = date("Y");
					
					 $rekap = DB::select("SELECT count(*) as jumlah from employee_pa where tahun=$year and employee_id = $emp");
					 foreach($rekap as $itemx) {					
							$jmz = $itemx->jumlah;
					 }
					 
					 if ($jmz == 0)
					 {
						 DB::table('employee_pa')->insert([
									'employee_id' => $emp,
									'tahun' => $year,
								 ]);
					 }
					 
					 
					 $hitung = DB::select("SELECT count(*) as jumlah from pa_hdr where PaPeriodId = $periodeid and EmployeeId = $emp ");
						foreach($hitung as $item) {					
							$jml = $item->jumlah;
						}
					 if ($jml == 0)
					 {
						 $PaId = DB::table('pa_hdr')->insertGetId([
									'PaPeriodId' => $periodeid,
									'EmployeeId' => $emp,
									'DirectSuperior' => $ds,
									'ReqSts' => 1,
									'CreatedDate' => $now,
									'CreatedBy' => $employee_id,
								 ]);
								 
						if($gid > 3)
						{
							$param = DB::select("SELECT id, Bobot from pa_subbab where GradeId = $gid");
						}
						else
						{
							$param = DB::select("SELECT id, Bobot from pa_subbab where GradeId = 3");
						}
						
						foreach ($param as $params)
						{
							$paris = $params->id;
							$bo    = $params->Bobot;
							
							$hit = DB::select("SELECT count(*) as jmh from pa_dtl where PaParamsId = $paris  and PaId = $PaId");
							foreach($hit as $it) {					
								$ctx = $it->jmh;
							}
							
							if ($ctx == 0)
							{
								 DB::table('pa_dtl')->insert([
									'PaId' => $PaId,
									'PaParamsId' => $paris,
									'EmployeeId' => $emp,
									'PaParamsBobot' =>$bo,				
									'created_at' => $now,
									'CreatedBy' => $employee_id,	
								 ]);
								 
							}
						}
						
						
						 
					 }
					
					
					
				}
				
				DB::table('pa_periode')->where('id', $id)->update([
					'status' => 2,
				]);
				
				return redirect(route('PAPeriode.index'))->with('alert', [
					'type' => 'success',
					'msg' => 'Berhasil Generate Form PA',
					]);
	}
}
