<?php

namespace App\Exports;

use App\Models\GradeTitle;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class GradeTitleExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return GradeTitle::all();
    }
    
    public function headings(): array
    {
        return [
            'Kode Grade Title',
            'Nama Grade Title',
            'Deskripsi Grade Title',
        ];
    }

    public function map($grade_title): array
    {
        return [
            $grade_title->grade_title_code,
            $grade_title->grade_title_name,
            $grade_title->grade_title_description,
        ];
    }
}
