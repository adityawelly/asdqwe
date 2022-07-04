<?php

namespace App\Exports;

use App\Models\JobTitle;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class JobTitleExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return JobTitle::with('department')->get();
    }

    public function headings(): array
    {
        return [
            'Departemen',
            'Kode Job Title',
            'Nama Job Title',
            'Deskripsi Job Title',
        ];
    }

    public function map($job_title): array
    {
        return [
            $job_title->department ? $job_title->department->department_name:'-',
            $job_title->job_title_code,
            $job_title->job_title_name,
            $job_title->job_title_description,
        ];
    }
}
