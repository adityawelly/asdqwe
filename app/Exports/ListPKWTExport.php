<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ListPKWTExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
	use Exportable;
	
    public function collection()
    {
        $query = "select a.*, b.registration_number, b.fullname, c.region_city, d.job_title_name from list_pkwt a, employees b, company_regions c, job_titles d
				  where a.employee_id = b.id and a.location_id = c.id and a.job_title_id = d.job_title_code";

        return collect(DB::select($query));
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nomor PKWT',
            'NIK',
            'Nama Karyawan',
            'Job Title',
            'Lokasi Kerja',
			'Lama Kontrak',
			'Kontrak Ke',
			'Tanggal Awal',
			'Tanggal Akhir',
			'No FPK / PTK',
        ];
    }
	
	function map($row): array
    {
        return [
            $row->id,
			$row->pkwt_no,
            $row->registration_number,
            $row->fullname,
			$row->job_title_name,
            $row->region_city,
            $row->bulan,
			$row->kontrak_ke,
			date('d-m-Y', strtotime($row->sdate)),
			date('d-m-Y', strtotime($row->edate)),
			$row->no_reff,
        ];
    }
}
