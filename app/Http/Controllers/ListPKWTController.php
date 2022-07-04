<?php

namespace App\Http\Controllers;

use File;
use PDF;
use Exception;
use Carbon\Carbon;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\ListPKWT;
use App\Exports\ListPKWTExport;
use App\Http\Requests\PKWTRequest;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class ListPKWTController extends Controller
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
        
		$pkwt = ListPKWT::all();
		$masa_kontrak = [3, 6, 9, 12];
		$employee = Employee::where('status','!=','Tetap')->get();
		 
        return view('ListPKWT.index', compact('pkwt','employee','masa_kontrak' ));
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
    public function store(Request $request)
    {
		
		$pengajuan = DB::select('select a.*, b.hk from employeemaster as a, employee_hks as b where a.employee_id = b.employee_no and  a.id = ?',[$request->employee_id]);
	    $pengajuan = $pengajuan[0];
		
		
					$jabatan_baru = $pengajuan->job_title_code;					
					$lokasi_baru = $pengajuan->loc_id;
					$hk = $pengajuan->hk;
		
		        $now = now();
				$bulan = date("m", strtotime($now));
				$tahun = date("Y", strtotime($now));
										
				if($bulan == 1 ) $romawibulan = "I";
				else if($bulan == 2 ) $romawibulan = "II";
				else if($bulan == 3 ) $romawibulan = "III";
				else if($bulan == 4 ) $romawibulan = "IV";
				else if($bulan == 5 ) $romawibulan = "V";
				else if($bulan == 6 ) $romawibulan = "VI";
				else if($bulan == 7 ) $romawibulan = "VII";
				else if($bulan == 8 ) $romawibulan = "VIII";
				else if($bulan == 9 ) $romawibulan = "IX";
				else if($bulan == 10 ) $romawibulan = "X";
				else if($bulan == 11 ) $romawibulan = "XI";
				else if($bulan == 12 ) $romawibulan = "XII";
										
				$maxNo = DB::select("select case
					when coalesce(max(left(`pkwt_no`, 3)), 0)+1 = 1000 then 1
					else coalesce(max(left(`pkwt_no`, 3)), 0)+1
					end as maxID from list_pkwt where pkwt_no like '%$tahun%'");
												
					$no = sprintf($maxNo[0]->maxID);
										
					$nomor = sprintf('%03d', $maxNo[0]->maxID).'/PKWT/HCM/NU/'.$romawibulan.'/'.$tahun;
					
					
		$nup = new ListPKWT();
		$nup->pkwt_no = $nomor;
	    $nup->employee_id = $request->employee_id;
		$nup->location_id = $lokasi_baru;
		$nup->job_title_id = $jabatan_baru;
		$nup->kontrak_ke = 1;
		$nup->hk_id = $hk;
		$nup->fpk_id = $request->fpk_id;
		$nup->no_reff = $request->no_reff;
		$nup->bulan = $request->bulan;
		$nup->sdate = $request->sdate;
		$nup->edate = $request->edate;
		$nup->created_by = auth()->user()->employee->id;
		$nup->created_at = $now;
		
        $nup->save();
		
		
		DB::table('employees')->where(['id' => $request->employee_id ])->update([
						'kontrak' => 1,
						]);	

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'msg' => 'Draft berhasil ditambahkan'
        ]);
    }
	
	
	public function cetak($id)
    {
			$employee_id = auth()->user()->employee->id;
			
			$isi_sk = DB::select('select a.*, b.fullname, c.sex, c.ID_number, c.address, d.job_title_name from list_pkwt as a, employees as b, employee_details as c, 
			job_titles as d where a.employee_id = b.id and a.employee_id = c.employee_id and a.job_title_id = d.job_title_code and a.id = ?',[$id]);

			$pasal2 = DB::table('pkwt_draft')->where('pkwt_id', 1)->get();
			$pasal3 = DB::table('pkwt_draft')->where('pkwt_id', 2)->get();
			$pasal4 = DB::table('pkwt_draft')->where('pkwt_id', 3)->where('urut',1)->get();
			$pasal41 = DB::table('pkwt_draft')->where('pkwt_id', 3)->where('urut','>',1)->get();
			$pasal5 = DB::table('pkwt_draft')->where('pkwt_id', 4)->get();
			$pasal6 = DB::table('pkwt_draft')->where('pkwt_id', 5)->get();
			$pasal7 = DB::table('pkwt_draft')->where('pkwt_id', 6)->get();
			$pasal8 = DB::table('pkwt_draft')->where('pkwt_id', 7)->get();
			$pasal9 = DB::table('pkwt_draft')->where('pkwt_id', 8)->get();
			$pasal10 = DB::table('pkwt_draft')->where('pkwt_id', 9)->get();
			$pasal11 = DB::table('pkwt_draft')->where('pkwt_id', 10)->get();
			$pasal12 = DB::table('pkwt_draft')->where('pkwt_id', 11)->get();
			$pasal13 = DB::table('pkwt_draft')->where('pkwt_id', 12)->get();
			

			$pdf = PDF::loadView('ListPKWT.cetak', compact('isi_sk','pasal2','pasal3','pasal4','pasal41','pasal5','pasal6','pasal7','pasal8','pasal9','pasal10','pasal11','pasal12','pasal13'))->setPaper('a4', 'potrait');
		    return $pdf->stream();
	}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $division = ListPKWT::findOrFail($id);

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
    public function update(Request $request, $id)
    {
		$pengajuan = DB::select('select a.*, b.hk from employeemaster as a, employee_hks as b where a.employee_id = b.employee_no and  a.id = ?',[$request->employee_id]);
	    $pengajuan = $pengajuan[0];
		
		
					$jabatan_baru = $pengajuan->job_title_code;					
					$lokasi_baru = $pengajuan->loc_id;
					$hk = $pengajuan->hk;
		$now = now();
		
		$nup = ListPKWT::find($id);
	    $nup->employee_id = $request->employee_id;
		$nup->location_id = $lokasi_baru;
		$nup->job_title_id = $jabatan_baru;
		$nup->hk_id = $hk;
		$nup->bulan = $request->bulan;
		$nup->sdate = $request->sdate;
		$nup->edate = $request->edate;
		$nup->updated_by = auth()->user()->employee->id;
		$nup->updated_at = $now;
		
        $nup->save();
		
		
		DB::table('employees')->where(['id' => $request->employee_id ])->update([
						'kontrak' => 1,
						]);	

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
        $pkwt = ListPKWT::findOrFail($id);

            DB::transaction(function () use ($ListPKWT){
                $ListPKWT->delete();
            });
			
			DB::table('employees')->where(['id' => $pkwt->employee_id ])->update([
						'kontrak' => 1,
						]);
        
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
        $division = ListPKWT::withTrashed()->findOrFail($id);

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
        return Excel::download(new ListPKWTExport, 'data-PKWT-'.date('Y-m-d').'.xlsx');
    }

    /**
     * Export CSV
     *
     * @return void
     */
    public function csv()
    {
        return Excel::download(new ListPKWTExport, 'data-PKWT-'.date('Y-m-d').'.csv');
    }

    /**
     * Export PDF
     *
     * @return void
     */
    public function pdf()
    {
        return Excel::download(new ListPKWTExport, 'data-PKWT-'.date('Y-m-d').'.pdf', \Maatwebsite\Excel\Excel::TCPDF);
    }

    public function get_departments(ListPKWT $division)
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
        $ListPKWT = ListPKWT::find($id);		
		$PKDTL = DB::table('pkwt_draft')->where('pkwt_id', $id)->get();
		
        return view('ListPKWT.detail',
		[
			'PKDTL' => $PKDTL,
			'ListPKWT' => $ListPKWT,
        ]);
    }
}
