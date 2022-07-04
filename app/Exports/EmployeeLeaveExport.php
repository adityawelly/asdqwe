<?php

namespace App\Exports;

use App\Models\EmployeeLeave;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class EmployeeLeaveExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    use Exportable;
    private $params;

    public function __construct(array $params) {
        $this->params = (object) $params;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = EmployeeLeave::with('leave', 'employee.department', 'employee.company_region', 'employee.grade_title');

        if ($this->params->employee_no != 'semua') {
            $data->where('employee_no', '=', $this->params->employee_no);
        }

        $data->whereBetween('start_date', [$this->params->start_date, $this->params->end_date]);

        return $data->get();
    }

    public function headings(): array
    {
        return [
            'NIK',
            'Nama',
            'Departemen',
			'Grade',
			'Lokasi',
            'Kategori',
            'Start Date',
            'End Date',
            'Start Time',
            'End Time',
            'Keterangan',
            'Durasi',
            'Status',
            'Minus Tahunan ?',
			'Kuota Extend',
			'Potong Gaji',
            'Diajukan Pada',
        ];
    }

    public function map($row): array
    {
        return [
            $row->employee->registration_number,
            $row->employee->fullname,
            $row->employee->department->department_name,
			$row->employee->grade_title->grade_title_name,
			$row->employee->company_region->region_city,
            $row->leave->leave_name,
            date('d-m-Y', strtotime($row->start_date)),
            date('d-m-Y', strtotime($row->end_date)),
            $row->start_time,
            $row->end_time,
            $row->reason,
            $row->total,
            leave_status($row->status, false),
            $row->leave->is_minus_annual == 1 ? 'ya':'',
			$row->is_extend == 1 ? 'ya':'',
			$row->potong_gaji == 1 ? 'ya':'',
            date('d-m-Y', strtotime($row->created_at)),
        ];
    }
}
