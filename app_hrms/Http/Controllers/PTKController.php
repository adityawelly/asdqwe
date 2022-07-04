<?php

namespace App\Http\Controllers;

use PDF;
use App\User;
use Exception;
use App\Models\Employee;
use App\Models\JobTitle;
use App\Exports\PTKExport;
use App\Models\Department;
use App\Models\Division;
use App\Models\ListPKWT;
use App\Models\GradeTitle;
use App\Models\HCApproval;
use Illuminate\Http\Request;
use App\Models\CompanyRegion;
use App\Notifications\NotifNewPTK;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Notifications\NotifApprovalPTK;

class PTKController extends Controller
{
   public function cetak($id)
    {
		$employee_id = auth()->user()->employee->id;
		$reasonOfHiring = DB::table('lookups')->where([
            'category' => 'PTKROH'
        ])->get();

        $employeeStatus = DB::table('lookups')->where([
            'category' => 'PTKES'
        ])->get();
		
		$pengajuan = DB::select('select *, d.level_title_id, h.lookup_desc as empStatus, g.lookup_desc as reasonDesc, i.level_title_name as jabatan
        from t_job_request a
        left join grade_titles b on a.PositionLevel = b.id 
        left join departments c on a.DeptId  = c.id 
        left join employees d on a.EmployeeIdRequestor = d.id  		  
        left join job_titles e on a.JobTitle = e.id
        left join company_regions f on a.`WorkLocation` = f.id
        left join lookups g on a.`ReasonOfHiring` = g.lookup_value and g.category = "PTKROH"
        left join lookups h on a.`EmploymentStatus` = h.lookup_value and h.category = "PTKES"
		left join level_titles i on d.level_title_id = i.id
        where a.ReqId = ?',[$id]);
				
		$skill_desc = DB::table('t_job_particular_skill')->where('ReqId', $id)->get();
        $job_desc = DB::table('t_job_description')->where('ReqId', $id)->get();
        $replacements = [];
		
        $replacements = DB::table('t_job_reason_hiring_replacement')->where('ReqId', $id)->get();

        $facilities = DB::table('t_job_equipment_facilities')->where('ReqId', $id)
                        ->whereNotNull('Description')
                        ->get();
									
		$atasan = DB::table('t_job_request')->where('ReqId', $id)->get();
			foreach ($atasan as $atasan) {
				$ds = $atasan->CreatedBy;
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
				
		$ats_td_lsng =  DB::select("(select a.`ApprovalSts`, a.`ApprovalNotes`, a.`ApprovalDate`, b.fullname, c.level_title_name
                        from t_job_request_approval a
                        inner join employees b on b.id = a.`EmployeeId`
						left join level_titles c on b.level_title_id = c.id
                        where a.`EmployeeId` = '$ids' and a.IsHcFlag=0 and a.`ReqId` = ?
                        order by a.`ApprovalDate` desc)
                        union
                        (select a.`ApprovalSts`, a.`ApprovalNotes`, a.`ApprovalDate`, b.fullname, c.level_title_name
                        from t_job_request_approval_log a
                        inner join employees b on b.id = a.`EmployeeId`
						left join level_titles c on b.level_title_id = c.id
                        where a.`EmployeeId` = '$ids' and a.IsHcFlag=0 and a.`ReqId` = ?
                        order by a.`ApprovalDate` desc)", [$id, $id]);
		
		$ats_jab =  DB::select("(select a.`ApprovalSts`, a.`ApprovalNotes`, a.`ApprovalDate`, b.fullname, c.level_title_name
                        from t_job_request_approval a
                        inner join employees b on b.id = a.`EmployeeId`
						left join level_titles c on b.level_title_id = c.id
                        where a.`EmployeeId` = '$ids' and a.IsHcFlag=0 and a.`ReqId` = ?
                        order by a.`ApprovalDate` desc)
                        union
                        (select a.`ApprovalSts`, a.`ApprovalNotes`, a.`ApprovalDate`, b.fullname, c.level_title_name
                        from t_job_request_approval_log a
                        inner join employees b on b.id = a.`EmployeeId`
						left join level_titles c on b.level_title_id = c.id
                        where a.`EmployeeId` = '$ids' and a.IsHcFlag=0 and a.`ReqId` = ?
                        order by a.`ApprovalDate` desc)", [$id, $id]);

		$mgr_lsng =     DB::select("(select a.`ApprovalSts`, a.`ApprovalNotes`, a.`ApprovalDate`, b.fullname, c.level_title_name
                        from t_job_request_approval a
                        inner join employees b on b.id = a.`EmployeeId`
						left join level_titles c on b.level_title_id = c.id
                        where a.`EmployeeId` = '$ism' and a.IsHcFlag=0 and a.`ReqId` = ?
                        order by a.`ApprovalDate` desc)
                        union
                        (select a.`ApprovalSts`, a.`ApprovalNotes`, a.`ApprovalDate`, b.fullname, c.level_title_name
                        from t_job_request_approval_log a
                        inner join employees b on b.id = a.`EmployeeId`
						left join level_titles c on b.level_title_id = c.id
                        where a.`EmployeeId` = '$ism' and a.IsHcFlag=0 and a.`ReqId` = ?
                        order by a.`ApprovalDate` desc)", [$id, $id]);
						

		$dir_lsng =     DB::select("(select a.`ApprovalSts`, a.`ApprovalNotes`, a.`ApprovalDate`, b.fullname, c.level_title_name as jabatan_direktur
                        from t_job_request_approval a
                        inner join employees b on b.id = a.`EmployeeId`
						left join level_titles c on b.level_title_id = c.id
                        where a.`EmployeeId` = '$idm' and a.IsHcFlag=0 and a.`ReqId` = ?
                        order by a.`ApprovalDate` desc)
                        union
                        (select a.`ApprovalSts`, a.`ApprovalNotes`, a.`ApprovalDate`, b.fullname, c.level_title_name as jabatan_direktur
                        from t_job_request_approval_log a
                        inner join employees b on b.id = a.`EmployeeId`
						left join level_titles c on b.level_title_id = c.id
                        where a.`EmployeeId` = '$idm' and a.IsHcFlag=0 and a.`ReqId` = ?
                        order by a.`ApprovalDate` desc)", [$id, $id]);
				
						
		$dir_hc = DB::select('(select a.`ApprovalSts`, a.`ApprovalNotes`, a.`ApprovalDate`, b.fullname
                        from t_job_request_approval a
                        inner join employees b on b.id = a.`EmployeeId`
                        where a.EmployeeId=35 and a.IsHcFlag=1 and a.`ReqId` = ?
                        order by a.`ApprovalDate` desc)
                        union
                        (select a.`ApprovalSts`, a.`ApprovalNotes`, a.`ApprovalDate`, b.fullname
                        from t_job_request_approval_log a
                        inner join employees b on b.id = a.`EmployeeId`
                        where a.EmployeeId=35 and a.IsHcFlag=1 and a.`ReqId` = ?
                        order by a.`ApprovalDate` desc)', [$id, $id]);
		
		$mgr_hc = DB::select('(select a.`ApprovalSts`, a.`ApprovalNotes`, a.`ApprovalDate`, b.fullname
                        from t_job_request_approval a
                        inner join employees b on b.id = a.`EmployeeId`
                        where a.EmployeeId=9 and a.IsHcFlag=1 and a.`ReqId` = ?
                        order by a.`ApprovalDate` desc)
                        union
                        (select a.`ApprovalSts`, a.`ApprovalNotes`, a.`ApprovalDate`, b.fullname
                        from t_job_request_approval_log a
                        inner join employees b on b.id = a.`EmployeeId`
                        where a.EmployeeId=9 and a.IsHcFlag=1 and a.`ReqId` = ?
                        order by a.`ApprovalDate` desc)', [$id, $id]);		
		
		
		$pdf = PDF::loadView('pengajuan.ptk_cetak', compact('pengajuan','skill_desc','replacements','job_desc','facilities','ats_jab','dir_lsng','mgr_lsng','ats_td_lsng','mgr_hc','dir_hc'))->setPaper('a4', 'potrait');
		return $pdf->stream();
			
	}
    
    
    public function index()
    {
        $employee_id = auth()->user()->employee->id;
        $query = "select a.`ReqId`, a.`ReqNo`, b.job_title_name, c.grade_title_name, d.department_name, a.`ReqQty`, e.fullname, a.`ReqSts`, a.EmployeeIdRequestor, a.ApprovedAll, a.CreatedDate
            from t_job_request a
            inner join job_titles b on b.id = a.`JobTitle`
            inner join grade_titles c on c.id = a.`PositionLevel`
            inner join departments d on d.id = a.`DeptId`
            inner join employees e on e.id = a.`EmployeeIdRequestor`";

        if (!auth()->user()->can('modify-ptk')) {
            $query .= " where a.`EmployeeIdRequestor` = " . $employee_id;
        }
		
			$query .= " ORDER BY  a.`ReqId` DESC";
		

        $pengajuans = DB::select($query);
		
		//echo $query;

        return view('pengajuan.ptk', [
            'pengajuans' => $pengajuans,
            'employee_id' => $employee_id
        ]);
    }

    public function create()
    {
        $direct_superior = auth()->user()->employee->load('level_title', 'department', 'division');
        $grade_titles = GradeTitle::all();
		if (!auth()->user()->can('create-ptk-master')) {			
        $job_titles = JobTitle::where('department_id', $direct_superior->department->id)->get();
		}
		else
		{
		 $job_titles = JobTitle::all();	
		}
        //$job_titles = JobTitle::where('department_id', $direct_superior->department->id)->get();	
        $departments = Department::all();
        $company_regions = CompanyRegion::all();
        $reasonOfHiring = DB::table('lookups')->where([
            'category' => 'PTKROH'
        ])->get();

        $employeeStatus = DB::table('lookups')->where([
            'category' => 'PTKES'
        ])->get();

        $workingTime = DB::table('lookups')->where([
            'category' => 'PTWT'
        ])->get();

        $employees = Employee::with('level_title')->get();

        $gradeOptions = ['I', 'II', 'III', 'IV', 'V', 'VI'];
        $levelOptions = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14];
        $last_educationOptions = [
            "SD", "SMP", "SMA", "SMK", "D3", "D4", "S1", "S2", "S3"
        ];

        return view('pengajuan.ptk_create', [
            'grade_titles' => $grade_titles,
            'job_titles' => $job_titles,
            'departments' => $departments,
            'company_regions' => $company_regions,
            'reasonOfHiring' => $reasonOfHiring,
            'employeeStatus' => $employeeStatus,
            'workingTime' => $workingTime,
            'employees' => $employees,
            'gradeOptions' => $gradeOptions,
            'levelOptions' => $levelOptions,
            'last_educationOptions' => $last_educationOptions,
            'direct_superior' => $direct_superior,
        ]);
    }

    public function get_indirect_superior(Request $request)
    {
        $InDirectSuperior = Employee::findOrFail($request->id);
        $InDirectSuperior->load('level_title');

        return response()->json([
            'status' => 'success',
            'value' => $InDirectSuperior->superior->fullname . '-' . $InDirectSuperior->superior->level_title->level_title_name,
        ]);
    }

    public function submit(Request $request)
    {
        DB::beginTransaction();
        try {
            $requestor = auth()->user()->employee;

            $now = now();

            // $maxReqNo = DB::select('select case
            //             when coalesce(max(left(`ReqNo`, 3)), 0)+1 = 1000 then 1
            //             else coalesce(max(left(`ReqNo`, 3)), 0)+1
            //         end as maxID from t_job_request');

            // $ReqNo = sprintf('%03d', $maxReqNo[0]->maxID).'/PTK/'.$now->format('m').'/'.$now->year;
            $ReqId = DB::table('t_job_request')->insertGetId([
                // 'ReqNo' => $ReqNo,
                'JobTitle' => $request->JobTitle,
                'PositionLevel' => $request->PositionLevel,
                'Grade' => $request->Grade,
                'Level' => $request->Level,
                'DeptId' => $request->DeptId,
                'WorkLocation' => $request->WorkLocation,
                'ReqQty' => $request->ReqQty,
                'EmploymentStatus' => $request->EmploymentStatus,
                'EmploymentNote' => $request->EmploymentNote,
                'WorkingTime' => $request->WorkingTime,
                'QtyMale' => $request->QtyMale,
                'QtyFemale' => $request->QtyFemale,
                'QtyBoth' => $request->QtyBoth,
                'Education' => $request->Education,
                'EducationFocus' => $request->EducationFocus,
                'MinAge' => $request->MinAge,
                'MaxAge' => $request->MaxAge,
                'WorkingExperience' => $request->WorkingExperience,
                'ActiveDate' => $request->ActiveDate,
                'Notes' => $request->Notes,
                'EmployeeIdRequestor' => $requestor->id,
                'CreatedDate' => $now,
                'CreatedBy' => $requestor->id,
                'ReasonOfHiring' => $request->reasons,
                'ReqSts' => 0,
            ]);

            $jobDescData = [];
            foreach ($request->JobDesc as $item) {
                $jobDescData[] = [
                    'ReqId' => $ReqId,
                    'JobDesc' => $item,
                ];
            }
            DB::table('t_job_description')->insert($jobDescData);

            $skillData = [];
            foreach ($request->ParticularSkill as $item) {
                $skillData[] = [
                    'ReqId' => $ReqId,
                    'SkillDesc' => $item,
                ];
            }
            DB::table('t_job_particular_skill')->insert($skillData);

            DB::table('t_job_employee_relation')->insert([
                'ReqId' => $ReqId,
                'RelType' => 'Superior',
                'EmployeeId' => $request->DirectSuperior,
            ]);

            if ($request->reasons == 'ReplcMut' || $request->reasons == 'ReplcRsgn') {
                $replaceData = [];
                for ($i = 0; $i < count($request->replaced); $i++) {
                    $replaceData[] = [
                        'ReqId' => $ReqId,
                        'EmployeeReplaced' => $request->replaced[$i],
                        'EmployeeReplacement' => $request->replacement[$i],
                    ];
                }
                DB::table('t_job_reason_hiring_replacement')->insert($replaceData);
            }

            $facilitiesData = [];
            foreach ($request->facilities as $item) {
                $facilitiesData[] = [
                    'ReqId' => $ReqId,
                    'Description' => $item,
                ];
            }
            DB::table('t_job_equipment_facilities')->insert($facilitiesData);

            $approval_by = $requestor->superior->user;

            $approval_by->notify(new NotifNewPTK([
                'email' => $approval_by->email,
                'RequestorName' => $requestor->fullname,
                'ReqNo' => $ReqId,
            ]));

            DB::commit();
            return redirect(route('pengajuan.ptk'))->with('alert', [
                'type' => 'success',
                'msg' => 'Berhasil Submit PTK, menunggu approval',
            ]);
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('alert', [
                'type' => 'danger',
                'msg' => $ex->getMessage()
            ]);
        }
    }

    public function approval()
    {
        $EmployeeId = auth()->user()->employee->id;
        $arr_data = DB::select('call SP_JobRequest_ListApprovalEmployeeContain(?,null)', [$EmployeeId]);
        if (!$arr_data) {
            $arr_data = array();
        }
        $arr_data = collect($arr_data);
        return view('pengajuan.ptk_approval')->with(array(
            'arr_data' => $arr_data->sortBy('CurrentApprovalFlag')->sortBy('ReqSts')->unique('ReqId')->values()->all()
        ));
    }

    public function detail($id)
    {
        $pengajuan = DB::select('select *, h.lookup_desc as empStatus, g.lookup_desc as reasonDesc
        from t_job_request a
        left join grade_titles b on a.PositionLevel = b.id 
        left join departments c on a.DeptId  = c.id 
        left join employees d on a.EmployeeIdRequestor = d.id  
        left join job_titles e on a.JobTitle = e.id
        left join company_regions f on a.`WorkLocation` = f.id
        left join lookups g on a.`ReasonOfHiring` = g.lookup_value and g.category = "PTKROH"
        left join lookups h on a.`EmploymentStatus` = h.lookup_value and h.category = "PTKES"
        where a.ReqId = ?',[$id]);
        $pengajuan = $pengajuan[0];

        if ($pengajuan->ReqSts == 1) {
            $approval_data = DB::select('select a.`IsHcFlag` as IsHc, a.`ApprovalSts` as ApprovedFlag, b.fullname, c.grade_title_name, 0 as EmployeeIdApproval
            from t_job_request_approval a
            left join employees b on a.`EmployeeId` = b.id
            left join grade_titles c on b.grade_title_id = c.id
            where a.`ReqId` = ?
            order by a.`ApprovalDate` asc', [$id]);
        }else{
            $approval_data = DB::select('call SP_JobRequest_ListApprovalEmployeeContain(null,?)', [$id]);
        }

        $EmployeeId = auth()->user()->employee->id;
        $check_current_approval = DB::Select('call SP_JobRequest_ListPendingApproval(?,0)',[$EmployeeId]);
        $FlagApproval = 0;
        $FlagIsCurrentHC = 0;
        if(!$check_current_approval){
            $FlagApproval = 0;
        }
        else {
            foreach($check_current_approval as $list_check){
                $ReqIdCheck = $list_check->ReqId;
                if($ReqIdCheck == $id){
                    $FlagApproval = 1;
                    $FlagIsCurrentHC = $list_check->PresdirIsApprove;
                    break;
                }
            }
        }

        /*
        $approval_non_hc = [];
        $superior_id = null;
        while (true) {
            $superior = DB::select('select a.direct_superior, b.fullname, c.grade_title_name, d.ApprovalSts from employees a
                        inner join employees b on b.id = a.direct_superior
                        inner join grade_titles c on c.id = b.grade_title_id
                        left join t_job_request_approval d on d.`EmployeeId` = b.id and d.`ReqId` = ?
                        where a.id = ?', [$id, $superior_id ?? $pengajuan->EmployeeIdRequestor]);

            if (!$superior) {
                break;
            }

            $approval_non_hc[] = (object)[
                'id' => $superior[0]->direct_superior,
                'fullname' => $superior[0]->fullname,
                'grade_title' => $superior[0]->grade_title_name,
                'approval_sts' => $superior[0]->ApprovalSts
            ];
            $superior_id = $superior[0]->direct_superior;
        }

        $approval_hc = DB::select('select * from t_job_hc_approval a
                        inner join employees c on c.id = a.`EmployeeId`
                        left join t_job_request_approval b on b.`EmployeeId` = a.`EmployeeId` and b.`ReqId` = ?', [$id]);

        */

        $approval_log = DB::select('(select a.`ApprovalSts`, a.`ApprovalNotes`, a.`ApprovalDate`, b.fullname
                        from t_job_request_approval a
                        inner join employees b on b.id = a.`EmployeeId`
                        where a.`ReqId` = ?
                        order by a.`ApprovalDate` asc)
                        union
                        (select a.`ApprovalSts`, a.`ApprovalNotes`, a.`ApprovalDate`, b.fullname
                        from t_job_request_approval_log a
                        inner join employees b on b.id = a.`EmployeeId`
                        where a.`ReqId` = ?
                        order by a.`ApprovalDate` asc) ORDER BY `ApprovalDate` ASC', [$id, $id]);

        $skill_desc = DB::table('t_job_particular_skill')->where('ReqId', $id)->get();
        $job_desc = DB::table('t_job_description')->where('ReqId', $id)->get();
        $replacements = [];
		$pkwt = ListPKWT::where('fpk_id',$id)->get();
		$masa_kontrak = [3, 6, 9, 12];
		$employee = Employee::where('kontrak',0)->get();

        if (in_array($pengajuan->ReasonOfHiring, ['ReplcMut', 'ReplcRsgn'])) {
            $replacements = DB::table('t_job_reason_hiring_replacement')->where('ReqId', $id)->get();
        }

        $facilities = DB::table('t_job_equipment_facilities')->where('ReqId', $id)
                        ->whereNotNull('Description')
                        ->get();

        return view('pengajuan.ptk_detail', [
            'pengajuan' => $pengajuan,
            'approval_data' => $approval_data,
            'approval_log' => $approval_log,
            'FlagApproval' => $FlagApproval,
            'FlagIsCurrentHC' => $FlagIsCurrentHC,
            'skill_desc' => $skill_desc,
            'job_desc' => $job_desc,
            'replacements' => $replacements,
            'facilities' => $facilities,
			'pkwt' => $pkwt,
			'masa_kontrak' => $masa_kontrak,
			'employee' => $employee,
        ]);
    }

    function submit_approval(request $request){
        $ReqId = $request->ReqId;
        $ApprovalNote = $request->ApprovalNote;
        $ApprovalSts = $request->ApprovalSts;
        $EmployeeId = auth()->user()->employee->id;
        $FlagIsCurrentHC = $request->FlagIsCurrentHC;
        DB::begintransaction();
        try{
            DB::statement('call SP_JobRequest_InputApproval(?,?,?,?,?)',[$ReqId,$EmployeeId,$ApprovalSts,$ApprovalNote,$FlagIsCurrentHC]);
            $users = [];

            if ($ApprovalSts == 1) {
                $users = User::where('employee_id', $request->RequestorId)->get();
                if ($request->NextApproval !== 0) {
                    $next_approval = User::where('employee_id', $request->NextApproval)->first();

                    if ($next_approval) {
                        $next_approval->notify(new NotifNewPTK([
                            'email' => $next_approval->email,
                            'RequestorName' => $request->RequestorName,
                            'ReqNo' => $ReqId,
                        ]));
                    }
                }
            }else{
                $hc_pic = HCApproval::all();
                $plucked = $hc_pic->pluck('EmployeeId');
                $plucked->push($request->RequestorId);

                $users = User::whereIn('employee_id', $plucked->all())->get();
            }

            foreach ($users as $user) {
                $user->notify(new NotifApprovalPTK([
                    'email' => $user->email,
                    'ReqNo' => $ReqId,
                    'ApprovalSts' => $ApprovalSts,
                    'ApprovalBy' => $request->ApprovalBy
                ]));
            }

            DB::commit();
            return redirect(route('pengajuan.ptk.approval'))->with('alert', [
                'type' => 'success',
                'msg' => 'Berhasil Submit Approval',
            ]);
        }
        catch(Exception $ex){
            DB::rollBack();
            return redirect()->back()->with('alert', [
                'type' => 'danger',
                'msg' => $ex->getMessage()
            ]);
        }
    }

    public function close(Request $request)
    {
        $request->validate([
            'ReqId' => 'required',
            'FilledDate' => 'required|date'
        ]);

        DB::table('t_job_request')->where('ReqId', $request->ReqId)->update([
            'FilledDate' => $request->FilledDate,
            'ReqSts' => 1
        ]);
		
		$cek= DB::select("select count(*) as jumlah from jobs where ptk_id ='$request->ReqId'");
			foreach($cek as $item) {					
					$hitungid = $item->jumlah;
			}
			
			if ($hitungid >= 1)
			{
					 DB::table('jobs')->where(['ptk_id' => $request->ReqId])->update([
								'status' => 1,
					 ]);										
			}
		
        return redirect(route('pengajuan.ptk'))->with('alert', [
            'type' => 'success',
            'msg' => 'Pengajuan PTK berhasil ditutup'
        ]);
    }

    public function edit($id)
    {
        $current_emp = auth()->user()->employee->load('department');
        $grade_titles = GradeTitle::all();
        if (!auth()->user()->can('edit-ptk-master')) {			
        $job_titles = JobTitle::where('department_id', $direct_superior->department->id)->get();
		}
		else
		{
		 $job_titles = JobTitle::all();	
		}
        $departments = Department::all();
        $company_regions = CompanyRegion::all();
        $reasonOfHiring = DB::table('lookups')->where([
            'category' => 'PTKROH'
        ])->get();

        $employeeStatus = DB::table('lookups')->where([
            'category' => 'PTKES'
        ])->get();

        $workingTime = DB::table('lookups')->where([
            'category' => 'PTWT'
        ])->get();

        $employees = Employee::with('level_title')->get();

        $gradeOptions = ['I', 'II', 'III', 'IV', 'V', 'VI'];
        $levelOptions = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14];
        $last_educationOptions = [
            "SD", "SMP", "SMA", "SMK", "D3", "D4", "S1", "S2", "S3"
        ];
        $facilities = DB::table('lookups')->where([
            'category' => 'PTFAC'
        ])->get();

        $pengajuan = DB::table('t_job_request')->where('ReqId', $id)->first();
        if (!$pengajuan) {
            return redirect()->back()->with('alert', [
                'status' => 'danger',
                'msg' => 'Pengajuan tidak ditemukan'
            ]);
        }else {
            
            /*
            if ($pengajuan->EmployeeIdRequestor != $current_emp->id) {
                abort(403);
            }
            */
        }
        $replacements = [];

        if (in_array($pengajuan->ReasonOfHiring, ['ReplcMut', 'ReplcRsgn'])) {
            $replacements = DB::table('t_job_reason_hiring_replacement')->where('ReqId', $id)->get();
        }

        $skill_desc = DB::table('t_job_particular_skill')->where('ReqId', $id)->get();
        $job_desc = DB::table('t_job_description')->where('ReqId', $id)->get();
        $inserted_fac = DB::table('t_job_equipment_facilities')->where('ReqId', $id)->get();

        return view('pengajuan.ptk_edit', [
            'grade_titles' => $grade_titles,
            'job_titles' => $job_titles,
            'departments' => $departments,
            'company_regions' => $company_regions,
            'reasonOfHiring' => $reasonOfHiring,
            'employeeStatus' => $employeeStatus,
            'workingTime' => $workingTime,
            'employees' => $employees,
            'gradeOptions' => $gradeOptions,
            'levelOptions' => $levelOptions,
            'last_educationOptions' => $last_educationOptions,
            'facilities' => $facilities,
            'pengajuan' => $pengajuan,
            'replacements' => $replacements,
            'skill_desc' => $skill_desc,
            'job_desc' => $job_desc,
            'facilities' => $facilities,
            'inserted_fac' => $inserted_fac,
        ]);
    }

    public function submit_edit(Request $request)
    {
        DB::beginTransaction();
        try {
            $ReqId = $request->ReqId;

            //Update table utama
            DB::table('t_job_request')->where('ReqId', $ReqId)->update([
                'JobTitle' => $request->JobTitle,
                'PositionLevel' => $request->PositionLevel,
                'Grade' => $request->Grade,
                'Level' => $request->Level,
                'DeptId' => $request->DeptId,
                'WorkLocation' => $request->WorkLocation,
                'ReqQty' => $request->ReqQty,
                'EmploymentStatus' => $request->EmploymentStatus,
                'EmploymentNote' => $request->EmploymentNote,
                'WorkingTime' => $request->WorkingTime,
                'QtyMale' => $request->QtyMale,
                'QtyFemale' => $request->QtyFemale,
				'QtyBoth' => $request->QtyBoth,
                'Education' => $request->Education,
                'EducationFocus' => $request->EducationFocus,
                'MinAge' => $request->MinAge,
                'MaxAge' => $request->MaxAge,
                'WorkingExperience' => $request->WorkingExperience,
                'ActiveDate' => $request->ActiveDate,
                'Notes' => $request->Notes,
                'ReasonOfHiring' => $request->reasons,
                //'ReqSts' => 0,
            ]);

            $jobDescData = [];
            foreach ($request->JobDesc as $item) {
                $jobDescData[] = [
                    'ReqId' => $ReqId,
                    'JobDesc' => $item,
                ];
            }
            DB::table('t_job_description')->where('ReqId', $ReqId)->delete();
            DB::table('t_job_description')->insert($jobDescData);

            $skillData = [];
            foreach ($request->ParticularSkill as $item) {
                $skillData[] = [
                    'ReqId' => $ReqId,
                    'SkillDesc' => $item,
                ];
            }
            DB::table('t_job_particular_skill')->where('ReqId', $ReqId)->delete();
            DB::table('t_job_particular_skill')->insert($skillData);

            if ($request->reasons == 'ReplcMut' || $request->reasons == 'ReplcRsgn') {
                $replaceData = [];
                for ($i = 0; $i < count($request->replaced); $i++) {
                    $replaceData[] = [
                        'ReqId' => $ReqId,
                        'EmployeeReplaced' => $request->replaced[$i],
                        'EmployeeReplacement' => $request->replacement[$i],
                    ];
                }
                DB::table('t_job_reason_hiring_replacement')->where('ReqId', $ReqId)->delete();
                DB::table('t_job_reason_hiring_replacement')->insert($replaceData);
            }

            $facilitiesData = [];
            foreach ($request->facilities as $item) {
                $facilitiesData[] = [
                    'ReqId' => $ReqId,
                    'Description' => $item,
                ];
            }
            DB::table('t_job_equipment_facilities')->where('ReqId', $ReqId)->delete();
            DB::table('t_job_equipment_facilities')->insert($facilitiesData);

            $requestor = auth()->user()->employee;
            $approval_by = $requestor->superior->user;
            
            if (!auth()->user()->can('edit-ptk-master')) {
                $approval_by->notify(new NotifNewPTK([
                    'email' => $approval_by->email,
                    'RequestorName' => $requestor->fullname,
                    'ReqNo' => $ReqId,
                ]));
            }
            
            DB::commit();
            return redirect(route('pengajuan.ptk'))->with('alert', [
                'type' => 'success',
                'msg' => 'Berhasil Edit PTK, menunggu approval',
            ]);
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('alert', [
                'type' => 'danger',
                'msg' => $ex->getMessage()
            ]);
        }
    }

    public function outstanding(Request $request)
    {
        $request->validate([
            'OutStandMale' => 'bail|nullable|numeric',
            'OutStandFemale' => 'bail|nullable|numeric',
            'Deadline' => 'bail|nullable|date',
        ]);

        DB::table('t_job_request')->where('ReqId', $request->ReqId)
            ->update([
                'OutStandMale' => $request->OutStandMale,
                'OutStandFemale' => $request->OutStandFemale,
                'ReqNo' => $request->ReqNo,
                'Deadline' => $request->Deadline,
            ]);

            return redirect()->back()->with('alert', [
                'type' => 'success',
                'msg' => 'Update outstanding PTK berhasil'
            ]);
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

        return Excel::download(new PTKExport($params), 'ptk-export-'.date('Y-m-d').'.xlsx');
    }

    public function remove(Request $request)
    {
        $request->validate([
            'ReqId' => 'required|numeric'
        ]);

        $ReqId = $request->ReqId;

        DB::beginTransaction();
        try {
            DB::table('t_job_request')->where('ReqId', $ReqId)->delete();
            DB::table('t_job_description')->where('ReqId', $ReqId)->delete();
            DB::table('t_job_employee_relation')->where('ReqId', $ReqId)->delete();
            DB::table('t_job_equipment_facilities')->where('ReqId', $ReqId)->delete();
            DB::table('t_job_particular_skill')->where('ReqId', $ReqId)->delete();
            DB::table('t_job_reason_hiring_replacement')->where('ReqId', $ReqId)->delete();
            DB::table('t_job_request_approval')->where('ReqId', $ReqId)->delete();
            DB::table('t_job_request_approval_log')->where('ReqId', $ReqId)->delete();

            DB::commit();
            return redirect()->back()->with('alert', [
                'type' => 'success',
                'msg' => 'Berhasil Hapus PTK',
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
