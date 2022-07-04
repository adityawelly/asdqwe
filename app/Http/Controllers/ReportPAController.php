<?php

namespace App\Http\Controllers;

use PDF;
use App\User;
use Exception;
use App\Exports\PAExport;
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

class ReportPAController extends Controller
{
    public function index(Request $request)
    {

		$query = "select a.*, b.employee_id, b.fullname , b.job_title_name, b.department_name, c.fullname as nama_atasan, d.* from
				  pa_hdr as a, employeemaster as  b, employees as c, pa_periode as d where a.EmployeeId = b.id and a.DirectSuperior=c.id and a.PaPeriodId = d.id and a.ReqSts<>1";
				  
		$years = range(now()->year+1, now()->year-5);
	
        $pengajuans = DB::select($query);		
				
        return view('reportpa.index', [
            'pengajuans' => $pengajuans,
			'years' => $years,
        ]);
    }
	
	public function export(Request $request)
    {
		$request->validate([
            'tahun' => 'required',
        ]);

        $params = [
            'tahun' => $request->tahun
        ];
		
        return Excel::download(new PAExport($params), 'pa-export-'.date('Y-m-d').'.xlsx');
    }
}
