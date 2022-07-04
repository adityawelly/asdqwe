<?php

namespace App\Http\Controllers;

use PDF;
use App\User;
use Exception;
use App\Models\Employee;
use App\Models\EmployeeMaster;
use App\Models\JobTitle;
use App\Models\PADetail;
use App\Models\Department;
use App\Models\GradeTitle;
use App\Models\GroupJobtitle;
use App\Models\GroupAtasan;
use App\Models\HCApproval;
use Illuminate\Http\Request;
use App\Models\CompanyRegion;
use App\Notifications\NotifNewPAForm;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Notifications\NotifApprovalPAForm;

class PAFormController extends Controller
{
    
    public function cetak($id)
    {
			$employee_id = auth()->user()->employee->id;
						
		    $pengajuan = DB::select('select a.*, j.fullname as nama_atasan_baru,
			b.employee_id, b.fullname , b.date_of_birth, b.date_of_work, b.religion, b.last_education, b.education_focus, 
			b.department_name, d.registration_number, d.fullname as nama_creator , c.* , e.job_title_name , 
			f.job_title_name as jab_baru, g.department_name , h.department_name as dept_baru, i.region_city from 
			pa_hdr as a, employeemaster as  b, pa_hdr_perihal as  c, employees as d, job_titles as e, 
			job_titles as f , departments as g, departments as h, company_regions as i, employees as j
			where a.Jab_lama=e.job_title_code and a.Jab_baru=f.job_title_code and a.Insert_user = d.id and 
			a.PaId=c.Seq_id and a.Nik=b.id and a.Dept_lama = g.department_code and a.Dept_baru = h.department_code and a.Lokasi_baru = i.id 
			and a.Atasan_baru=j.id and  a.PaId = ?',[$id]);
			
			$atasan = DB::table('pa_hdr')->where('PaId', $id)->get();
			foreach ($atasan as $atasan) {
				$nik = $atasan->Nik;
				$ds = $atasan->DirectSuperior;
			}
			
			$ats_lsg = DB::table('employees')->where('id', $ds)->get();
			foreach ($ats_lsg as $ats_lsg) {
					$ids = $ats_lsg->direct_superior;			
				}
				
			$mgr = DB::table('employees')->where('id', $ids)->get();
			foreach ($mgr as $mgr) {
					$ism = $mgr->direct_superior;			
				}
			
			$dir = DB::table('employees')->where('id', $ism)->get();
			foreach ($dir as $dir) {
					$idm = $dir->direct_superior;			
				}
				
			$facilities = DB::table('pa_hdr_facility')->where('Req_id', $id)->get();
						
			if (!empty($ds))
			{			
			$ats_lsng = DB::select("(select a.`ApprovalSts`, a.`EmployeeId`, a.LevelId, a.`IsHcFlag` as IsHc, a.Flag, a.`ApprovalNotes`, a.`ApprovalDate`, b.fullname, c.grade_title_name
                        from pa_approval a
                        inner join employees b on b.id = a.`EmployeeId`
						left join grade_titles c on b.grade_title_id = c.id
                        where a.`PaId` = ? and a.IsHcFlag=0 and a.EmployeeId=$ds
                        order by a.`ApprovalDate` desc)", [$id]);	
			}
			
			if (!empty($ids))
			{
			$ats_td_lsng = DB::select("(select a.`ApprovalSts`, a.`EmployeeId`, a.LevelId, a.`IsHcFlag` as IsHc, a.Flag, a.`ApprovalNotes`, a.`ApprovalDate`, b.fullname, c.grade_title_name
                        from pa_approval a
                        inner join employees b on b.id = a.`EmployeeId`
						left join grade_titles c on b.grade_title_id = c.id
                        where a.`PaId` = ? and a.IsHcFlag=0 and a.EmployeeId=$ids
                        order by a.`ApprovalDate` desc)", [$id]);	
			}
			if (!empty($ism))
			{
			$mgr_lsng = DB::select("(select a.`ApprovalSts`, a.`EmployeeId`, a.LevelId, a.`IsHcFlag` as IsHc, a.Flag, a.`ApprovalNotes`, a.`ApprovalDate`, b.fullname, c.grade_title_name
                        from pa_approval a
                        inner join employees b on b.id = a.`EmployeeId`
						left join grade_titles c on b.grade_title_id = c.id
                        where a.`PaId` = ? and a.IsHcFlag=0 and a.EmployeeId=$ism
                        order by a.`ApprovalDate` desc)", [$id]);
            
            $mgr_jab = DB::select("(select a.`ApprovalSts`, a.`ApprovalDate`, b.fullname, c.level_title_name
                        from pa_approval a
                        inner join employees b on b.id = a.`EmployeeId`
						left join level_titles c on b.level_title_id = c.id
                        where a.`PaId` = ? and a.IsHcFlag=0 and a.EmployeeId=$ism
                        order by a.`ApprovalDate` desc)", [$id]);
			}
			if (!empty($idm))
			{
			$dir_lsng = DB::select("(select a.`ApprovalSts`, a.`EmployeeId`, a.LevelId, a.`IsHcFlag` as IsHc, a.Flag, a.`ApprovalNotes`, a.`ApprovalDate`, b.fullname, c.level_title_name
                        from pa_approval a
                        inner join employees b on b.id = a.`EmployeeId`
						left join level_titles c on b.level_title_id = c.id
                        where a.`PaId` = ? and a.IsHcFlag=0 and a.EmployeeId=$idm
                        order by a.`ApprovalDate` desc)", [$id]);
            
            $dir_jab = DB::select("(select a.`ApprovalSts`, a.`ApprovalDate`, b.fullname, c.level_title_name
                        from pa_approval a
                        inner join employees b on b.id = a.`EmployeeId`
						left join level_titles c on b.level_title_id = c.id
                        where a.`PaId` = ? and a.IsHcFlag=0 and a.EmployeeId=$idm
                        order by a.`ApprovalDate` desc)", [$id]);
			}
			
			$dir_hc = DB::select('(select a.`ApprovalSts`, a.`EmployeeId`, a.LevelId, a.`IsHcFlag` as IsHc, a.Flag, a.`ApprovalNotes`, a.`ApprovalDate`, b.fullname, c.grade_title_name
                        from pa_approval a
                        inner join employees b on b.id = a.`EmployeeId`
						left join grade_titles c on b.grade_title_id = c.id
                        where a.`PaId` = ? and a.IsHcFlag=1 and a.EmployeeId=35
                        order by a.`ApprovalDate` desc)', [$id]);

			$mgr_hc = DB::select('(select a.`ApprovalSts`, a.`EmployeeId`, a.LevelId, a.`IsHcFlag` as IsHc, a.Flag, a.`ApprovalNotes`, a.`ApprovalDate`, b.fullname, c.grade_title_name
                        from pa_approval a
                        inner join employees b on b.id = a.`EmployeeId`
						left join grade_titles c on b.grade_title_id = c.id
                        where a.`PaId` = ? and a.IsHcFlag=1 and a.EmployeeId=9
                        order by a.`ApprovalDate` desc)', [$id]);

			$pdf = PDF::loadView('pengajuan.fpk_cetak', compact('pengajuan','ats_lsng','ats_td_lsng','mgr_lsng','mgr_jab','dir_lsng','dir_jab','facilities','dir_hc','mgr_hc'))->setPaper('a4', 'potrait');
		    return $pdf->stream();
	}
	
	
    public function index(Request $request)
    {
        $employee_id = auth()->user()->employee->id;
		
		
		if ($employee_id <> 6)
		{
            $query = "select a.*, b.employee_id, b.fullname , b.job_title_name, b.department_name, c.fullname as nama_atasan, d.*  from
					  pa_hdr as a, employeemaster as  b, employees as c, pa_periode as d where a.EmployeeId = b.id and a.DirectSuperior=c.id and a.PaPeriodId = d.id and a.DirectSuperior = $employee_id";
		}
		else
		{
			$query = "select a.*, b.employee_id, b.fullname , b.job_title_name, b.department_name, c.fullname as nama_atasan, d.* from
				      pa_hdr as a, employeemaster as  b, employees as c, pa_periode as d where a.EmployeeId = b.id and a.DirectSuperior=c.id and a.PaPeriodId = d.id";
		}
	
		
        $pengajuans = DB::select($query);		
				
        return view('PAForm.index', [
            'pengajuans' => $pengajuans,
            'employee_id' => $employee_id
        ]);
    }

    

    public function approval()
    {
        $EmployeeId = auth()->user()->employee->id;

		$query = "select a.*, b.employee_id, b.fullname , b.job_title_name, b.department_name, c.fullname as nama_atasan, d.*  from
				  pa_hdr as a, employeemaster as  b, employees as c, pa_periode as d where a.EmployeeId = b.id and a.DirectSuperior=c.id and a.PaPeriodId = d.id and a.NextApproval = $EmployeeId";		
		
        $pengajuans = DB::select($query);		
				
        return view('PAForm.approval', [
            'pengajuans' => $pengajuans,			
        ]);
    }

    public function detail($id)
    {
        $direct_superior = auth()->user()->employee->load('level_title');
		
        $pengajuan = DB::table('pa_hdr')->where('PaId', $id)->first();	
		
        $EmployeeId = auth()->user()->employee->id;

		
        $nik = $pengajuan->EmployeeId;
		$App = $pengajuan->NextApproval;
	
	   
        $inserted = DB::select("select a.* , b.Namasub from pa_dtl as a, pa_subbab as b where a.PaParamsId = b.id and a.EmployeeId = $nik and PaId= $id");
		 
		$edited = DB::select("select a.*, b.employee_id, b.fullname ,  b.grade_title_id, b.job_title_name, b.department_name, c.fullname as nama_atasan, d.*  from
					  pa_hdr as a, employeemaster as  b, employees as c, pa_periode as d where a.EmployeeId = b.id and a.DirectSuperior=c.id and a.PaPeriodId = d.id and a.PaId = $id");


        $approval_log = DB::select('select a.`ApprovalSts`, a.`ApprovalNotes`, a.`ApprovalDate`, b.fullname, c.grade_title_name
                        from pa_approval a
                        inner join employees b on b.id = a.`EmployeeId`
						left join grade_titles c on b.grade_title_id = c.id
                        where a.`PaId` = ?
                        order by a.`ApprovalDate` asc', [$id]);
						
		$NextApproval = DB::select("select direct_superior, grade_title_id from employees where id='$App'");

        return view('PAForm.detail',
		[
			'pengajuan' => $pengajuan,
            'direct_superior' => $direct_superior,
            'inserted' => $inserted,
			'NextApproval' => $NextApproval,
			'edited' => $edited,
            'approval_log' => $approval_log,
        ]);
    }

    function submit_approval(request $request){
        $PaId = $request->PaId;
        $ApprovalNote = $request->ApprovalNote;
        $ApprovalSts = $request->ApprovalSts;
		$NextApproval = $request->NextApproval;

		$EmployeeId = auth()->user()->employee->id;
		
        DB::begintransaction();
        try{
			
			$pengajuan = DB::table('pa_hdr')->where('PaId', $PaId)->first();
			
			$idk = $pengajuan->EmployeeId;
			
			$dept = DB::table('employees')->where('id', $idk)->first();
			
			$idd = $dept->department_id;
			
			
		if(!empty($NextApproval))
		{						
				if ($pengajuan->ApprovedAll == 0)
				{
					if ($ApprovalSts == 1)	
					{
					    if ($idd == 7 || $idd == 10 || $idd == 17) 
						{
							
								DB::table('pa_approval')->insert([
									'PaId' => $PaId,
									'IsHcFlag' => 0,				
									'Flag' => 2,				
									'EmployeeId' => $EmployeeId,
									'ApprovalSts' => 1,
									'ApprovalNotes' => $ApprovalNote,
									'ApprovalDate' => now(),
								]);
							
							if ($NextApproval<>41)
							{
								
								DB::table('pa_hdr')->where('PaId', $PaId)->update([
									'NextApproval' => $NextApproval,
								]);
							}
							else
							{
								DB::table('pa_hdr')->where('PaId', $PaId)->update([
									'NextApproval' => null,
									'ApprovedAll' => 1,
								]);
							 }
						}
						else
						{
							
							    	DB::table('pa_approval')->insert([
            							'PaId' => $PaId,
            							'IsHcFlag' => 0,				
            							'Flag' => 2,				
            							'EmployeeId' => $EmployeeId,
            							'ApprovalSts' => 1,
										'ApprovalNotes' => $ApprovalNote,
										'ApprovalDate' => now(),
            						]);
							
							if ($NextApproval<>36)
							{
									
									DB::table('pa_hdr')->where('PaId', $PaId)->update([
										'NextApproval' => $NextApproval,
									]);
											
							}
							else
							{
								DB::table('pa_hdr')->where('PaId', $PaId)->update([
									'NextApproval' => null,
									'ApprovedAll' => 1,
								]);
								
							}
						}
					}
					else
					{
									DB::table('pa_approval')->insert([
            							'PaId' => $PaId,
            							'IsHcFlag' => 0,				
            							'Flag' => 2,				
            							'EmployeeId' => $EmployeeId,
            							'ApprovalSts' => 2,
										'ApprovalNotes' => $ApprovalNote,
										'ApprovalDate' => now(),
            						]);
									
									DB::table('pa_hdr')->where('PaId', $PaId)->update([
										'NextApproval' => null,
										'ApprovedAll' => 2,
									]);
					}
				}

            DB::commit();
            return redirect(route('PAForm.approval'))->with('alert', [
                'type' => 'success',
                'msg' => 'Berhasil Submit Approval',
            ]);
        }
	  }
        catch(Exception $ex){
            DB::rollBack();
            return redirect()->back()->with('alert', [
                'type' => 'danger',
                'msg' => $ex->getMessage()
            ]);
		}
	}


    public function edit($id)
    {
        
		$direct_superior = auth()->user()->employee->load('level_title');
		
        $pengajuan = DB::table('pa_hdr')->where('PaId', $id)->first();
		
		
		
        if (!$pengajuan) {
            return redirect()->back()->with('alert', [
                'status' => 'danger',
                'msg' => 'Pengajuan tidak ditemukan'
            ]);
        }
		
		
        $nik = $pengajuan->EmployeeId;
	
	   
        $inserted = DB::select("select a.* , b.Namasub from pa_dtl as a, pa_subbab as b where a.PaParamsId = b.id and a.EmployeeId = $nik and PaId= $id");
		 
		$edited = DB::select("select a.*, b.employee_id, b.fullname , b.grade_title_id, b.job_title_name, b.department_name, c.fullname as nama_atasan, d.*  from
					  pa_hdr as a, employeemaster as  b, employees as c, pa_periode as d where a.EmployeeId = b.id and a.DirectSuperior=c.id and a.PaPeriodId = d.id and a.PaId = $id");
        


        return view('PAForm.edit',[
			'pengajuan' => $pengajuan,
            'direct_superior' => $direct_superior,
            'inserted' => $inserted,
			'edited' => $edited,

        ]);
    }

    public function submit_edit(Request $request)
    {
        DB::beginTransaction();
        try {
			
			$requestor = auth()->user()->employee->id;
			$direct_superior = auth()->user()->employee->direct_superior;
            $PaId = $request->PaId;
			$EmpId = $request->EmpId;
			$note = $request->Notes;
			$grade = $request->grade_title_id;
			
			
			$pas = $request->PaPeriodId;
			
			$now = now();
			
			if($grade == 5)
			{
				$totalan = 0;
			}
			else
			{
				$totalan = $request->kpi;
			}
			
            //Update table utama
            DB::table('pa_hdr')->where('PaId', $PaId)->update([              
				'ReqSts' => 2,
                'UpdatedDate' => $now,
				'Notes' => $note,
                'UpdatedBy' => $requestor,
                'PaScore' => $request->total,
				'kpi' => $totalan,
				'skor' => $totalan + $request->total,
				'NextApproval' => $direct_superior,
            ]);
			
			$tahun = DB::table('pa_periode')->where('id', $pas)->first();
			$periode = $tahun->semester;
			$tahu = $tahun->tahun;
			
			$sum = $totalan + $request->total;
			
			
			if ($sum > 0 && $sum < 1)
			{
				$grd = 'E';
			}
			else if($sum > 1 && $sum < 2)
			{
				$grd = 'D';
			}
			else if($sum > 2 && $sum < 3)
			{
				$grd = 'C';
			}
			else if($sum > 3 && $sum < 4)
			{
				$grd = 'B';
			}
			else
			{
				$grd = 'A';
			}
			
			
			if($periode == 1)
			{
				DB::table('employee_pa')->where('employee_id', $EmpId)
					->where('tahun', $tahu)
					->update([              
					'pa_sem1' => $request->total,
					'kpi_sem1' => $totalan,
					'tot_sem1' => $totalan + $request->total,
					'grd_sem1' => $grd,
				]);
			}
			else
			{
				DB::table('employee_pa')->where('employee_id', $EmpId)
						->where('tahun', $tahu)
						->update([              
						'pa_sem2' => $request->total,
						'kpi_sem2' => $totalan,
						'tot_sem2' => $totalan + $request->total,
						'grd_sem2' => $grd,
					]);
			}
            
            DB::table('pa_dtl')->where('PaId', $PaId)->delete();
			 
			 $param = $request->input('PaParamsId', []);
			 $bobot = $request->input('PaParamsBobot', []);
			 $score =  $request->input('PaParamsScore', []);
			 
			 for ($paramx=0; $paramx < count($param); $paramx++) {
				if ($param[$paramx] != '') 
						{
							$Padtl = new PADetail();
	  
							$Padtl->PaId = $PaId;
							$Padtl->PaParamsId = $param[$paramx];
							$Padtl->EmployeeId = $EmpId;
							$Padtl->PaParamsBobot = $bobot[$paramx];
							$Padtl->PaParamsScore = $score[$paramx];
							$Padtl->CreatedBy = $requestor;
							$Padtl->created_at = $now;
							
							$Padtl->save();
						}
			}
          
			/*
			$approval_by = $requestor->superior->user;

            $approval_by->notify(new NotifNewPAForm([
                'email' => $approval_by->email,
                'RequestorName' => $requestor->fullname,
                'ReqNo' => $PaId,
            ]));
			*/

            DB::commit();
            return redirect(route('PAForm.index'))->with('alert', [
                'type' => 'success',
                'msg' => 'Berhasil Melakukan Penilaian, menunggu approval',
            ]);
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('alert', [
                'type' => 'danger',
                'msg' => $ex->getMessage()
            ]);
        }
    }

    public function export(Request $request)
    {
        $request->validate([
            'start_date' => 'bail|nullable|date',
            'end_date' => 'bail|nullable|date',
            'status' => 'required',
        ]);

        $params = [
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status
        ];

        $params['is_approved_all'] = $request->has('is_approved_all') ? true:false;

        return Excel::download(new PAFormExport($params), 'pa-export-'.date('Y-m-d').'.xlsx');
    }

    public function remove(Request $request)
    {
        $PaId = $request->PaId;
		
		$requestor = auth()->user()->employee;	
	    $now = now();

        DB::beginTransaction();
        try {
			
			DB::table('pa_hdr')->where('PaId', $request->PaId)->update([
            'Delete_date' => $now,
			'Delete_user' => $requestor->id,
            'Flag_data' => 3,
			]);
			
            DB::commit();
            return redirect()->back()->with('alert', [
                'type' => 'success',
                'msg' => 'Berhasil Hapus PAForm',
            ]);
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('alert', [
                'type' => 'danger',
                'msg' => $ex->getMessage()
            ]);
        }
    }
}
