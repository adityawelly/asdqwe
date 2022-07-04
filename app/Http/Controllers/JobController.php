<?php

namespace App\Http\Controllers;

use PDF;
use App\User;
use Exception;
use App\Models\Employee;
use App\Models\JobTitle;
use App\Exports\JobExport;
use App\Models\Department;
use App\Models\Division;
use App\Models\Applier;
use App\Models\GradeTitle;
use App\Models\HCApproval;
use Illuminate\Http\Request;
use App\Models\CompanyRegion;
use App\Notifications\NotifNewJob;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Notifications\NotifApprovalJob;

class JobController extends Controller
{
   public function cetak($id)
    {

	}


    public function index()
    {
        $employee_id = auth()->user()->employee->id;

		$job = DB::table('jobs')->get();
		foreach ($job as $job) {
				$id = $job->id;

				$bd = DB::table('applier')->where('status_data','BD')->where('id_job',$id)->count();
				$ts = DB::table('applier')->where('status_data','TS')->where('id_job',$id)->count();
				$tp = DB::table('applier')->where('status_data','TP')->where('id_job',$id)->count();
				$wc = DB::table('applier')->where('status_data','WC')->where('id_job',$id)->count();

				DB::table('jobs')->where('id', $id)->update([
                'wawancara' => $wc,
                'terpilih' => $tp,
                'tidak_sesuai' => $ts,
                'views' => $bd,
            ]);

		}



        $query = "select a.id, a.status,  a.created_at, a.views, a.wawancara, a.tidak_sesuai, a.terpilih, b.job_title_name, c.grade_title_name, d.department_name, e.region_city from jobs as a,
				  job_titles as b, grade_titles as c, departments as d, company_regions as e where a.job_id = b.id and a.level_id = c.id
				  and a.dept_id = d.id and a.region_id = e.id order by a.status ASC";

        if (!auth()->user()->can('modify-job')) {
            $query .= " where a.user_id = " . $employee_id;
        }

        $pengajuans = DB::select($query);

		//$jumlah = DB::table('v_jml_apl')->where('id_job', $pengajuans->id)->get();

        return view('job.index', [
            'pengajuans' => $pengajuans,
			//'jumlah' => $jumlah,
            'employee_id' => $employee_id
        ]);
    }

    public function create()
    {
        $direct_superior = auth()->user()->employee->load('level_title', 'department', 'division');
        $grade_titles = GradeTitle::all();

        $job_titles = JobTitle::all();
        $departments = Department::all();
        $company_regions = CompanyRegion::all();
        $reasonOfHiring = DB::table('lookups')->where([
            'category' => 'JobROH'
        ])->get();

		$ptkno = DB::table('t_job_request')->where([
            'ReqSts' => 0
        ])->get();

        $employeeStatus = DB::table('lookups')->where([
            'category' => 'JobES'
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

        return view('job.create', [
            'grade_titles' => $grade_titles,
			'ptkno' => $ptkno,
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

    public function submit(Request $request)
    {
        DB::beginTransaction();
        try {
            $requestor = auth()->user()->employee;

            $now = now();

            $id = DB::table('jobs')->insertGetid([
                'job_id' => $request->job_id,
                'ptk_id' => $request->ptk_id,
                'level_id' => $request->level_id,
                'dept_id' => $request->dept_id,
                'region_id' => $request->region_id,
				'gender' => $request->gender,
                'Education' => $request->Education,
                'EducationFocus' => $request->EducationFocus,
                'MinAge' => $request->MinAge,
				'working_time' => $request->working_time,
                'MaxAge' => $request->MaxAge,
                'Notes' => $request->Notes,
				'user_id' => $requestor->id,
                'created_at' => $now,
            ]);

            $jobDescData = [];
            foreach ($request->JobDesc as $item) {
                $jobDescData[] = [
                    'ReqId' => $id,
                    'JobDesc' => $item,
                ];
            }
            DB::table('job_description')->insert($jobDescData);

            $JobReqData = [];
            foreach ($request->JobReq as $item) {
                $JobReqData[] = [
                    'ReqId' => $id,
                    'JobReq' => $item,
                ];
            }
            DB::table('job_requirments')->insert($JobReqData);

			$JobSpecData = [];
            foreach ($request->JobSpec as $item) {
                $JobSpecData[] = [
                    'ReqId' => $id,
                    'JobSpec' => $item,
                ];
            }
            DB::table('job_spesialis')->insert($JobSpecData);

            DB::commit();
            return redirect(route('job.index'))->with('alert', [
                'type' => 'success',
                'msg' => 'Berhasil Submit Job, menunggu approval',
            ]);

        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('alert', [
                'type' => 'danger',
                'msg' => $ex->getMessage()
            ]);
        }
    }


    public function detail($id)
    {
        $pengajuan = DB::select('select a.*, b.job_title_name, c.grade_title_name, d.department_name, e.region_city from jobs as a,
					  job_titles as b, grade_titles as c, departments as d, company_regions as e where a.job_id = b.id and a.level_id = c.id
					  and a.dept_id = d.id and a.region_id = e.id and a.id = ?',[$id]);
        $pengajuan = $pengajuan[0];

        $skill_desc = DB::table('job_requirments')->where('ReqId', $id)->get();
        $job_desc = DB::table('job_description')->where('ReqId', $id)->get();
		$job_spec = DB::table('job_spesialis')->where('ReqId', $id)->get();

        return view('job.detail', [
            'pengajuan' => $pengajuan,
            'skill_desc' => $skill_desc,
            'job_desc' => $job_desc,
			'job_spec' => $job_spec,

        ]);
    }

    public function close(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        DB::table('jobs')->where('id', $request->id)->update([
            'deadline' => now(),
            'status' => 1
        ]);

        return redirect(route('job.index'))->with('alert', [
            'type' => 'success',
            'msg' => 'Informasi Lowongan berhasil ditutup'
        ]);
    }

    public function edit($id)
    {
        $current_emp = auth()->user()->employee->load('department');
        $grade_titles = GradeTitle::all();
		$job_titles = JobTitle::all();
        $departments = Department::all();
        $company_regions = CompanyRegion::all();

        $employeeStatus = DB::table('lookups')->where([
            'category' => 'JobES'
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

        $pengajuan = DB::table('jobs')->where('id', $id)->first();
        if (!$pengajuan) {
            return redirect()->back()->with('alert', [
                'status' => 'danger',
                'msg' => 'Pengajuan tidak ditemukan'
            ]);
        }else {

        }

		$job_spec = DB::table('job_spesialis')->where('ReqId', $id)->get();

        $job_req = DB::table('job_requirments')->where('ReqId', $id)->get();

        $job_desc = DB::table('job_description')->where('ReqId', $id)->get();

        return view('job.edit', [
			'grade_titles' => $grade_titles,
            'job_titles' => $job_titles,
            'departments' => $departments,
            'company_regions' => $company_regions,
            'workingTime' => $workingTime,
            'employees' => $employees,
            'gradeOptions' => $gradeOptions,
            'levelOptions' => $levelOptions,
            'last_educationOptions' => $last_educationOptions,
            'facilities' => $facilities,
            'pengajuan' => $pengajuan,
            'job_req' => $job_req,
            'job_desc' => $job_desc,
			'job_spec' => $job_spec,
        ]);
    }

    public function submit_edit(Request $request)
    {
        DB::beginTransaction();
        try {
            $id = $request->id;

			$now = now();

            //Update table utama
            DB::table('jobs')->where('id', $id)->update([
                'job_id' => $request->job_id,
                'level_id' => $request->level_id,
                'dept_id' => $request->dept_id,
                'region_id' => $request->region_id,
				'gender' => $request->gender,
				'working_time' => $request->working_time,
                'Education' => $request->Education,
                'EducationFocus' => $request->EducationFocus,
                'MinAge' => $request->MinAge,
                'MaxAge' => $request->MaxAge,
                'Notes' => $request->Notes,
                'updated_at' => $now,
            ]);

            $jobDescData = [];
            foreach ($request->JobDesc as $item) {
                $jobDescData[] = [
                    'ReqId' => $id,
                    'JobDesc' => $item,
                ];
            }
            DB::table('job_description')->where('ReqId', $id)->delete();
            DB::table('job_description')->insert($jobDescData);

            $JobReqData = [];
            foreach ($request->JobReq as $item) {
                $JobReqData[] = [
                    'ReqId' => $id,
                    'JobReq' => $item,
                ];
            }

            DB::table('job_requirments')->where('ReqId', $id)->delete();
			DB::table('job_requirments')->insert($JobReqData);

			$JobSpecData = [];
            foreach ($request->JobSpec as $item) {
                $JobSpecData[] = [
                    'ReqId' => $id,
                    'JobSpec' => $item,
                ];
            }
			DB::table('job_spesialis')->where('ReqId', $id)->delete();
            DB::table('job_spesialis')->insert($JobSpecData);

            DB::commit();
            return redirect(route('job.index'))->with('alert', [
                'type' => 'success',
                'msg' => 'Berhasil Edit Job',
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

        return Excel::download(new JobExport($params), 'job-export-'.date('Y-m-d').'.xlsx');
    }

    public function remove(Request $request)
    {
        $request->validate([
            'ReqId' => 'required|numeric'
        ]);

        $id = $request->id;

        DB::beginTransaction();
        try {
            DB::table('jobs')->where('id', $id)->delete();
            DB::table('job_description')->where('ReqId', $id)->delete();
            DB::table('job_requirments')->where('ReqId', $id)->delete();
            DB::table('job_spesialis')->where('ReqId', $id)->delete();

            DB::commit();
            return redirect()->back()->with('alert', [
                'type' => 'success',
                'msg' => 'Berhasil Hapus Job',
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
