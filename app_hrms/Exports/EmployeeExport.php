<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class EmployeeExport implements FromCollection,
        WithHeadings, ShouldAutoSize, WithMapping
{

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Employee::with('employee_detail', 'division', 'department', 'job_title', 'company_region', 'grade_title', 'employee_salary', 'hari_kerja', 'user', 'superior')->get();
	}

    public function headings(): array
    {
        return [
            'ID',
            'NIK',
            'Tanggal Masuk Kerja',
            'Masa Kerja',
            'Nama Karyawan',
            'Divisi',
            'Department',
            'Grade Title',
            'Level Title',
            'Job Title',
            'Grade',
            'Level',
            'Status',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Umur',
            'Nomor KTP',
            'Nama Ibu Kandung',
            'Status Pernikahan',
            'Jenis Kelamin',
            'Agama',
            'No Telp',
            'NPWP',
            'Pendidikan Terakhir',
            'Jurusan',
            'Alamat Sesuai KTP',
            'Post Gaji',
            'Gaji Pokok',
            'Tipe Penggajian',
            'Uang Makan',
            'Bank',
            'No Rekening',
            'Email',
            'Role',
            'Atasan',
            'Dibuat pada',
            'Diupdate pada',
			'Hari Kerja'
        ];
    }

    public function map($employee): array
    {
        return [
            $employee->id,
            $employee->registration_number,
            $employee->date_of_work,
            $employee->year_of_service,
            $employee->fullname,
            $employee->division ? $employee->division->division_name:'-',
            $employee->department ? $employee->department->department_name:'-',
            $employee->grade_title ? $employee->grade_title->grade_title_name:'-',
            $employee->level_title ? $employee->level_title->level_title_name:'-',
            $employee->job_title ? $employee->job_title->job_title_name:'-',
            $employee->grade,
            $employee->level,
            $employee->status,
            $employee->employee_detail->place_of_birth,
            $employee->employee_detail->date_of_birth,
            $employee->employee_detail->age,
            "'".$employee->employee_detail->ID_number,
            $employee->employee_detail->mother_name,
            $employee->employee_detail->marital_status,
            $employee->employee_detail->sex,
            $employee->employee_detail->religion,
            $employee->employee_detail->phone_number,
            $employee->employee_detail->npwp,
            $employee->employee_detail->last_education,
            $employee->employee_detail->education_focus,
            $employee->employee_detail->address,
            $employee->employee_salary->salary_post,
            $employee->employee_salary->basic_salary,
            $employee->employee_salary->payroll_type,
            $employee->employee_salary->meal_allowance,
            $employee->employee_salary->bank,
            $employee->employee_salary->bank_account_number,
            $employee->user->email,
            implode(',', $employee->user->getRoleNames()->toArray()),
            !$employee->superior ? '-':$employee->superior->fullname,
            $employee->created_at,
            $employee->updated_at,
			$employee->hari_kerja->hk,
        ];
    }
}
