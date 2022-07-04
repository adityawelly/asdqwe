<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Employee;
use App\Models\JobTitle;
use App\Models\Department;
use App\Models\GradeTitle;
use App\Models\LevelTitle;
use Illuminate\Http\Request;
use App\Models\CompanyRegion;
use App\Exports\EmployeeExport;
use App\Imports\EmployeeImport;
use App\Imports\EmployeeRekImport;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\EmployeeRequest;
use App\Notifications\NotifNewEmployee;
use App\Notifications\NotifSetAtasan;
use App\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{

    /**
     * Form Data Create and Edit
     *
     * @var array
     */
    protected $form_options;

    public function __construct()
    {
        //
    }

    private function _set_form_options()
    {
        $this->form_options = [
            'gradeOptions' => ['I', 'II', 'III', 'IV', 'V', 'VI'],
            'levelOptions' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14],
            'statusOptions' => ['Kontrak', 'Probation', 'Tetap'],
            'plantOptions' => ['Bekasi', 'Pandaan', 'Sukabumi'],
            'divisionOptions' => Division::all(),
            'departmentOptions' => Department::all(),
            'grade_titleOptions' => GradeTitle::all(),
            'level_titleOptions' => LevelTitle::all()->sortBy('level_title_type')->mapToGroups(function($item, $key){
                return [$item['level_title_type'] => $item];
            }),
            'job_titleOptions' => JobTitle::all(),
            'company_regionOptions' => CompanyRegion::all(),
            'marital_statusOptions' => [
                ['view' => 'K', 'value' => 'K'],
                ['view' => 'K.0', 'value' => 'K.0'],
                ['view' => 'K.1', 'value' => 'K.1'],
                ['view' => 'K.2', 'value' => 'K.2'],
                ['view' => 'K.3', 'value' => 'K.3'],
                ['view' => 'T.K', 'value' => 'T.K']
            ],
            'sexOptions' => [
                "Laki - Laki",
                "Perempuan"
            ],
            'religionOptions' => [
                "Islam", "Kristen", "Katholik", "Hindu", "Budha", "Konghucu"
            ],
            'last_educationOptions' => [
                "SD", "SMP", "SMA", "SMK", "D3", "D4", "S1", "S2", "S3"
            ],
            'education_focusOptions' => [
                "Tidak Ada",
                "Administrasi Bisnis",
                "Administrasi Perkantoran",
                "Administrasi Negara",
            ],
            'payroll_typeOptions' => [
                "Bulan",
                "Hari"
            ],
            'meal_allowanceOptions' => [
                "Tidak",
                "Ya"
            ],
            'salary_postOptions' => [
                "direksi",
                "hcm",
                "pontianak"
            ],
            'bankOptions' => [
                "BCA",
                "Mandiri"
            ],
            'roles' => Role::where('name', '!=', 'Super Admin')->get()
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = Department::all();
        $grade_titles = GradeTitle::all();
        $company_regions = CompanyRegion::all();
		$employees = Employee::all();
        $unset_superiors = Employee::doesntHave('superior')->get();
        $unsets = $unset_superiors->reject(function($item, $key){
            return $item->registration_number == '00815';
        })->all();

        return view('employee.index', [
            'departments' => $departments,
            'grade_titles' => $grade_titles,
			'employees' => $employees,
            'company_regions' => $company_regions,
            'unset_superiors' => $unsets
        ]);
    }

    public function datatable(Request $request)
    {
        $data = Employee::with('employee_detail', 'division', 'department', 'job_title', 'company_region', 'grade_title', 'hari_kerja', 'created_by');

        if ($request->department_id != 'all' && $request->department_id != null) {
            $data->where('department_id', $request->department_id);
        }

        if ($request->grade_title_id != 'all' && $request->grade_title_id != null) {
            $data->where('grade_title_id', $request->grade_title_id);
        }

        if ($request->company_region_id != 'all' && $request->company_region_id != null) {
            $data->where('company_region_id', $request->company_region_id);
        }

        if (!auth()->user()->hasAnyRole('HCMTeam|Personnel|Super Admin')) {
            $data->where('direct_superior', auth()->user()->employee->id);
        }

        if (auth()->user()->can('restore-employee')) {
            $data->withTrashed();
        }

        return DataTables::make($data)
                ->addColumn('status', function($row) {
                    if ($row->trashed()) {
                        $badge = '<span class="badge badge-danger">Terhapus</span>';
                    }else {
                        $badge = '<span class="badge badge-primary">Tersedia</span>';
                    }

                    return auth()->user()->can('restore-employee') ? $badge:'';
                })
                ->addColumn('action', function($row) {
                    $button_strings = '<a class="dropdown-item" href="'.route('employee.show', $row->id).'"><i class="fas fa-search"></i> Lihat</a>';
                    if (auth()->user()->can('update-employee')) {
                        $button_strings .= '<a class="dropdown-item" href="'.route('employee.edit', $row->id).'"><i class="fas fa-pencil-alt"></i> Edit</a>';
                    }
                    if (auth()->user()->can('create-resign')) {
                        $button_strings .= '<a class="dropdown-item" href="javascript:void(0)" onclick="resign('.$row->id.', this)"><i class="fas fa-user-slash"></i> Resign</a>';
                    }
                    if ($row->trashed()) {
                        if (auth()->user()->can('restore-employee')) {
                            $button_strings .= '<a class="dropdown-item" href="javascript:void(0)" onclick="restore('.$row->id.', this)"><i class="fas fa-recycle"></i> Restore</a>';
                        }
                    }else{
                        if (auth()->user()->can('delete-employee')) {
                            $button_strings .= '<a class="dropdown-item" href="javascript:void(0)" onclick="remove('.$row->id.', this, false)"><i class="fas fa-trash-alt"></i> Hapus</a>';
                        }
                        if (auth()->user()->can('restore-employee')) {
                            $button_strings .= '<a class="dropdown-item" href="javascript:void(0)" onclick="remove('.$row->id.', this, true)"><i class="fas fa-trash-alt"></i> Hapus Permanen</a>';
                        }
                    }
                    $button_strings .= '<div class="dropdown-divider"></div>';
                    if (auth()->user()->can('update-employee')) {
                        $button_strings .= '<a class="dropdown-item" href="javascript:void(0)" onclick="set_superior('.$row->id.', this)"><i class="far fa-id-badge"></i> Set atasan</a>';
                    }
                    $button_strings .= '<a class="dropdown-item" href="javascript:void(0)" onclick="view_teams('.$row->id.', true)"><i class="fas fa-search"></i> Lihat tim</a>';
                    return '<div class="dropdown">
                                <button class="btn btn-secondary btn-xs dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-bars"></i> Opsi
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    '.$button_strings.'
                                </div>
                            </div>';
                })
                ->rawColumns(['status','action'])
                ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->_set_form_options();
        return view('employee.create', $this->form_options);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeRequest $request)
    {
        if ($request->hasFile('photo')) {
            $filename = $request->file('photo')->hashName();

            $request->file('photo')->move(public_path('uploads/images/users'), $filename);
        }else{
            $filename = null;
        }

		if (!empty($request->idx))
		{
			 DB::table('applier')->where('id', $request->idx)->update([
				'input_status' => 1,
            ]);
		}

		$EmployeeId = auth()->user()->employee->id;

        DB::transaction(function () use($request, $filename) {
            $request_employee = [
                'registration_number' => $request->registration_number,
                'date_of_work' => $request->date_of_work,
                'fullname' => $request->fullname,
                'grade' => $request->grade,
                'level' => $request->level,
                'status' => $request->status,
				'created_by' => \Auth::user()->employee->id,
                'division_id' => $request->division_id,
                'department_id' => $request->department_id,
                'grade_title_id' => $request->grade_title_id,
                'level_title_id' => $request->level_title_id,
                'job_title_id' => $request->job_title_id,
                'company_region_id' => $request->company_region_id,
                'photo' => $filename
            ];
            $employee = Employee::create($request_employee);
            $employee->employee_detail()->create($request->only([
                'place_of_birth',
                'date_of_birth',
                'ID_number',
                'mother_name',
                'marital_status',
                'sex',
                'religion',
                'phone_number',
                'npwp',
                'last_education',
                'education_focus',
                'address',
            ]));
            $employee->employee_salary()->create([
                'basic_salary' => preg_replace('/[^0-9]/', '', $request->basic_salary),
                'payroll_type' => $request->payroll_type,
                'meal_allowance' => $request->meal_allowance,
                'salary_post' => $request->salary_post,
                'bank' => $request->bank,
                'bank_account_number' => $request->bank_account_number
            ]);
            $user = $employee->user()->create([
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);

            $user->assignRole($request->role);
            $users = User::all();
            Notification::send($users, new NotifNewEmployee($employee));
        });

        session()->flash('alert', [
            'type' => 'success',
            'msg' => 'Karyawan dan user berhasil ditambahkan'
        ]);

        return response()->json(true);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = Employee::with('employee_detail', 'user')
                        ->withoutGlobalScopes()
                        ->findOrFail($id);

        return view('employee.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employee = Employee::with('employee_detail', 'employee_salary', 'user')
                        ->withoutGlobalScopes()
                        ->findOrFail($id);

        $this->_set_form_options();
        $data = array_merge($this->form_options, ['employee' => $employee]);

        return view('employee.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EmployeeRequest $request, Employee $employee)
    {
        if ($request->hasFile('photo')) {
            $filename = $request->file('photo')->hashName();

            $request->file('photo')->move(public_path('uploads/images/users'), $filename);
        }else{
            $filename = null;
        }

		$EmployeeId = auth()->user()->employee->id;

        DB::transaction(function () use($request, $employee, $filename) {
            $request_employee = [
                'registration_number' => $request->registration_number,
                'date_of_work' => $request->date_of_work,
                'fullname' => $request->fullname,
                'grade' => $request->grade,
                'level' => $request->level,
                'status' => $request->status,
				'updated_by' => \Auth::user()->employee->id,
                'division_id' => $request->division_id,
                'department_id' => $request->department_id,
                'grade_title_id' => $request->grade_title_id,
                'level_title_id' => $request->level_title_id,
                'job_title_id' => $request->job_title_id,
                'company_region_id' => $request->company_region_id
            ];
            if ($filename) {
                $request_employee['photo'] = $filename;
            }
            $employee->update($request_employee);
            $employee->employee_detail->update($request->only([
                'place_of_birth',
                'date_of_birth',
                'ID_number',
                'ID_number_expiration',
                'mother_name',
                'marital_status',
                'sex',
                'religion',
                'phone_number',
                'npwp',
                'last_education',
                'education_focus',
                'address',
            ]));
            $employee->employee_salary->update([
                'basic_salary' => preg_replace('/[^0-9]/', '', $request->basic_salary),
                'payroll_type' => $request->payroll_type,
                'meal_allowance' => $request->meal_allowance,
                'salary_post' => $request->salary_post,
                'bank' => $request->bank,
                'bank_account_number' => $request->bank_account_number
            ]);

            $request_user = [];
            $request_user['email'] = $request->email;

            if ($request->filled('password')) {
                $request_user['password'] = bcrypt($request->password);
            }

            $employee->user->update($request_user);
        });

        session()->flash('alert', [
            'type' => 'success',
            'msg' => 'Karyawan berhasil diupdate'
        ]);

        return response()->json(true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $employee = Employee::withTrashed()->findOrFail($id);

        // dd($employee);

        if ($request->force == 'true' && auth()->user()->can('restore-employee')) {
            $employee->forceDelete();
        }else {
            DB::transaction(function () use($employee){

                $employee->delete();

                $employee->user->delete();
            });
        }

        return response()->json([
            'type' => 'success',
            'msg' => 'Karyawan dan Usernya berhasil dihapus'
        ]);
    }

    /**
     * Restore soft deletes
     *
     * @param int $id
     * @return void
     */
    public function restore($id)
    {
        $employee = Employee::withTrashed()->findOrFail($id);
        $employee->restore();
        $employee->user()->restore();

        return response()->json([
            'type' => 'success',
            'msg' => 'Karyawan dan user berhasil di restore'
        ]);
    }

    /**
     * Export Excel
     *
     * @return void
     */
    public function excel()
    {
        return Excel::download(new EmployeeExport, 'employee-'.date('Y-m-d').'.xlsx');
    }

    /**
     * Export CSV
     *
     * @return void
     */
    public function csv()
    {
        return Excel::download(new EmployeeExport, 'employee-'.date('Y-m-d').'.csv');
    }

    /**
     * Export PDF
     *
     * @return void
     */
    public function pdf()
    {
        $employees = Employee::with('employee_detail', 'employee_salary', 'user', 'job_title', 'superior')->get();

        return view('employee.pdf', compact('employees'));
    }

    public function import(Request $request)
    {
        $validator = Validator::make([
            'file' => $request->file('file'),
            'ekstensi' => strtolower($request->file('file')->getClientOriginalExtension())
        ],[
            'file' => 'required|max:2048|file',
            'ekstensi' => 'required|in:xlsx'
        ]);

        if ($validator->fails()) {
            return response()->json($validator);
        }

        $importer = new EmployeeImport;

        try {
            $importer->import($request->file('file'));

            session()->flash('alert',[
                'type' => 'success',
                'msg' => 'Berhasil mengimport: '.$importer->counter.' data karyawan, tabel karyawan telah diperbarui'
            ]);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            session()->flash('import_error', $failures);
        }

        return response()->json(true);
    }

	public function import_rek(Request $request)
    {
        $validator = Validator::make([
            'file' => $request->file('file'),
            'ekstensi' => strtolower($request->file('file')->getClientOriginalExtension())
        ],[
            'file' => 'required|max:2048|file',
            'ekstensi' => 'required|in:xlsx'
        ]);

        if ($validator->fails()) {
            return response()->json($validator);
        }

        $importer = new EmployeeRekImport;

        try {
            $importer->import($request->file('file'));
        } catch (ValidationException $e) {
            $failures = $e->failures();
            return redirect()->back()->with('import_error', $failures);
        } catch (Exception $e) {
            return redirect()->back()->with('alert', [
                'type' => 'danger',
                'msg' => $e->getMessage()
            ]);
        }

        return redirect()->back()->with('alert',[
            'type' => 'success',
            'msg' => 'Berhasil mengupdate: '.$importer->counter.' data rekening karyawan'
        ]);
    }

    public function employee_select_data(Request $request)
    {
        $data = [];
        $employees = Employee::where('fullname', 'like', '%'.$request->q.'%')->get();

        foreach($employees as $employee){
            array_push($data, [
                'id' => $employee->id,
                'text' => $employee->id.'-'.$employee->fullname.'-'.$employee->department->department_name
            ]);
        }

        return response()->json($data);
    }

    public function update_direct_superior(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|numeric|exists:employees,id',
            'direct_superior' => 'required|numeric|exists:employees,id'
        ]);

        DB::transaction(function () use($request){
            $employee = Employee::findOrFail($request->employee_id);

            $employee->update([
                'direct_superior' => $request->direct_superior
            ]);

            $employee->superior->user->notify(new NotifSetAtasan($employee));
        });

        return response()->json([
            'status' => 'success',
            'msg' => 'Berhasil set direct superior'
        ]);
    }

    public function get_direct_superior($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->load('superior');

        if (!$employee->superior) {
            return response()->json([
                'nik' => 'Belum diisi',
                'fullname' => 'Belum diisi'
            ]);
        }

        return response()->json([
            'nik' => $employee->superior->registration_number,
            'fullname' => $employee->superior->fullname
        ]);
    }

    public function get_teams(Request $request)
    {
        $superior = Employee::findOrFail($request->direct_superior);
        $teams = Employee::where('direct_superior', $request->direct_superior)->get();

        $html = '<ul class="list-group">';
        foreach ($teams as $team) {
            $html .= '<li class="list-group-item"><span class="label">'.$team->registration_number.'-'.$team->fullname.'</span>';
            $html .= '<a href="'.route('employee.show', $team->id).'" class="btn btn-primary btn-xs ml-auto">Profil</a>';
            $html .= '<button type="button" onclick="view_teams('.$team->id.', false)" class="btn btn-default btn-xs ml-2">Lihat tim</button>';
            $html .= '</li>';
        }
        $html .= '</ul>';

        return response()->json([
            'superior' => $superior->fullname,
            'html' => $html
        ]);
    }
}
