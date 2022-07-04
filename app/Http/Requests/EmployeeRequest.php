<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    private $form_options;

    public function __construct() {
        $this->form_options = [
            'gradeOptions' => ['I', 'II', 'III', 'IV', 'V', 'VI'],
            'levelOptions' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18],
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
            ],
            'roles' => Role::where('name', '!=', 'Super Admin')->get()
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'date_of_work' => 'required|date',
            'fullname' => 'required',
            'grade' => [
                'required',
                Rule::in($this->form_options['gradeOptions'])
            ],
            'level' => [
                'required',
                Rule::in($this->form_options['levelOptions'])
            ],
            'division_id' => 'required|exists:divisions,id',
            'department_id' => 'required|exists:departments,id',
            'job_title_id' => 'required|exists:job_titles,id',
            'company_region_id' => 'required|exists:company_regions,id',
            'grade_title_id' => 'required|exists:grade_titles,id',
            'level_title_id' => 'required|exists:level_titles,id',
            'status' => [
                'required',
                Rule::in($this->form_options['statusOptions'])
            ],
            'place_of_birth' => 'required',
            'date_of_birth' => 'required|date',
            'ID_number' => 'required|digits:16',
            'mother_name' => 'required',
            'marital_status' => [
                'required',
                Rule::in(array_column($this->form_options['marital_statusOptions'], 'value'))
            ],
            'sex' => [
                'required',
                Rule::in($this->form_options['sexOptions'])
            ],
            'religion' => [
                'required',
                Rule::in($this->form_options['religionOptions'])
            ],
            'phone_number' => 'required',
            'npwp' => 'required',
            'last_education' => [
                'required',
                Rule::in($this->form_options['last_educationOptions'])
            ],
            'address' => 'required',
            'payroll_type' => [
                'required',
                Rule::in($this->form_options['payroll_typeOptions'])
            ],
            'meal_allowance' => [
                'required',
                Rule::in($this->form_options['meal_allowanceOptions'])
            ],
            'salary_post' => [
                'required',
                Rule::in($this->form_options['salary_postOptions'])
            ],
            'bank' => [
                'nullable',
                Rule::in($this->form_options['bankOptions'])
            ],
            'photo' => 'nullable|sometimes|image|max:512'
        ];

        if ($this->getMethod() == 'POST') {
            $rules += [
                'registration_number' => 'required|digits_between:5,20|unique:employees',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6|alpha_num',
                'basic_salary' => 'required',
                'role' => 'required|exists:roles,name'
            ];
        }else if($this->getMethod() == 'PUT') {
            $rules += [
                'email' => [
                    'required',
                    'email',
                    Rule::unique('users')->ignoreModel($this->employee->user)
                ],
                'password' => 'nullable|min:6|alpha_num'
            ];
            if (auth()->user()->can('update-salary')) {
                $rules += [
                    'basic_salary' => 'required'
                ];
            }
        }
        
        return $rules;
    }
}
