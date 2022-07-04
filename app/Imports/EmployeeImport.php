<?php

namespace App\Imports;

use App\Models\Division;
use App\Models\Employee;
use App\Models\JobTitle;
use App\Models\Department;
use App\Models\GradeTitle;
use App\Models\LevelTitle;
use App\Models\CompanyRegion;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class EmployeeImport implements ToModel, WithValidation, WithHeadingRow
{
    use Importable;

    public $counter = 0;

    private $form_options = [
        'gradeOptions' => ['I', 'II', 'III', 'IV', 'V', 'VI'],
        'levelOptions' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14],
        'statusOptions' => ['Kontrak', 'Probation', 'Tetap'],
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
        ]
    ];

    public function model(array $row)
    {
        $employee = Employee::create([
            'registration_number' => $row['nik'],
            'date_of_work' => $this->_format_tanggal($row['tanggal_masuk_kerja']),
            'fullname' => $row['nama_karyawan'],
            'division_id' => Division::where('division_code', $row['kode_divisi'])->first()->id,
            'department_id' => Department::where('department_code', $row['kode_department'])->first()->id,
            'grade_title_id' => GradeTitle::where('grade_title_code', $row['kode_grade_title'])->first()->id,
            'level_title_id' => LevelTitle::where('level_title_code', $row['kode_level_title'])->first()->id,
            'job_title_id' => JobTitle::where('job_title_code', $row['kode_job_title'])->first()->id,
            'grade' => $row['grade'],
            'level' => $row['level'],
            'company_region_id' => CompanyRegion::where('region_city', $row['regional'])->first()->id,
            'status' => $row['status']
            ]);
            $employee->employee_detail()->create([
                    'place_of_birth' => $row['tempat_lahir'],
                    'date_of_birth' => $this->_format_tanggal($row['tanggal_lahir']),
                    'ID_number' => $row['nomor_ktp'],
                    'mother_name' => $row['nama_ibu_kandung'],
                    'marital_status' => $row['status_pernikahan'],
                    'sex' => $row['jenis_kelamin'],
                    'religion' => $row['agama'],
                    'phone_number' => $row['no_telp'],
                    'npwp' => $row['npwp'],
                    'last_education' => $row['pendidikan_terakhir'],
                    'education_focus' => $row['jurusan'],
                    'address' => $row['alamat_sesuai_ktp']
            ]);
            $employee->employee_salary()->create([
                'salary_post' => $row['post_gaji'],
                'basic_salary' => preg_replace('/[^0-9]/', '', $row['gaji_pokok']),
                'payroll_type' => $row['tipe_penggajian'],
                'meal_allowance' => $row['uang_makan'],
                'bank' => $row['bank'],
                'bank_account_number' => $row['no_rekening'],
            ]);
            $user = $employee->user()->create([
                'email' => $row['email'],
                'password' => bcrypt($row['password'])
            ]);
            $user->assignRole([$row['role']]);

            ++$this->counter;
    }

    public function rules(): array
    {
        return [
            'nik' => 'required|between:5,20|unique:employees,registration_number',
            'tanggal_masuk_kerja' => 'required|date_format:d/m/Y',
            'nama_karyawan' => 'required',
            'kode_divisi' => 'required|exists:divisions,division_code',
            'kode_department' => 'required|exists:departments,department_code',
            'kode_grade_title' => 'required|exists:grade_titles,grade_title_code',
            'kode_level_title' => 'required|exists:level_titles,level_title_code',
            'kode_job_title' => 'required|exists:job_titles,job_title_code',
            'grade' => [
                'required',
                Rule::in($this->form_options['gradeOptions'])
            ],
            'level' => [
                'required',
                Rule::in($this->form_options['levelOptions'])
            ],
            'regional' => 'required|exists:company_regions,region_city',
            'status' => [
                'required',
                Rule::in($this->form_options['statusOptions'])
            ],
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date_format:d/m/Y',
            'nomor_ktp' => 'required|digits:16',
            'nama_ibu_kandung' => 'required',
            'status_pernikahan' => [
                'required',
                Rule::in(array_column($this->form_options['marital_statusOptions'], 'value'))
            ],
            'jenis_kelamin' => [
                'required',
                Rule::in($this->form_options['sexOptions'])
            ],
            'agama' => [
                'required',
                Rule::in($this->form_options['religionOptions'])
            ],
            'no_telp' => 'required',
            'pendidikan_terakhir' => [
                'required',
                Rule::in($this->form_options['last_educationOptions'])
            ],
            'alamat_sesuai_ktp' => 'required',
            'post_gaji' => [
                'required',
                Rule::in($this->form_options['salary_postOptions'])
            ],
            'gaji_pokok' => 'required',
            'tipe_penggajian' => [
                'required',
                Rule::in($this->form_options['payroll_typeOptions'])
            ],
            'uang_makan' => [
                'required',
                Rule::in($this->form_options['meal_allowanceOptions'])
            ],
            'bank' => [
                'nullable',
                Rule::in($this->form_options['bankOptions'])
            ],
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|alpha_num',
            'role' => 'required|exists:roles,name'
        ];
    }

    private function _format_tanggal($date){
		$date = str_replace("/","-",$date);
		$exp = explode('-',$date);
		if(count($exp) == 3) {
			$date = $exp[2].'-'.$exp[1].'-'.$exp[0];
		}
		return $date;
	}
}
