<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PTKExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    use Exportable;
    protected $params;

    public function __construct(array $params) {
        $this->params = (object) $params;
    }

    public function collection()
    {
        $query = "select a.*, b.job_title_name, c.department_name, d.grade_title_name, 
        e.lookup_desc as StatusKaryawan, f.ApprovalDate as AcceptedDate
        from t_job_request a
        left join job_titles b on b.id = a.`JobTitle`
        left join departments c on c.id = a.`DeptId`
        left join grade_titles d on d.id = a.`PositionLevel`
        left join lookups e on e.lookup_value = a.`EmploymentStatus`
        left join (
            select ReqId, max(ApprovalDate) as ApprovalDate from t_job_request_approval group by ReqId
        ) f on f.ReqId = a.`ReqId` and a.`ApprovedAll` = 1 where 1=1";

        if ($this->params->start_date && $this->params->end_date) {
            $query .= " and a.CreatedDate between '{$this->params->start_date}' and '{$this->params->end_date}'";
        }

        if ($this->params->status != 'all') {
            $query .= " and a.ReqSts = {$this->params->status}";
        }

        if ($this->params->is_approved_all) {
            $query .= " and a.ApprovedAll = 1";
        }

        return collect(DB::select($query));
    }

    function headings(): array
    {
        return [
            'PTK ID',
            'Tanggal Request',
            'Requestor ID',
            'No PTK',
            'Jabatan',
            'Alasan Rekrut',
            'Dept',
            'Grade',
            'Req Qty L',
            'Req Qty P',
            'Req Qty Both',
            'Total Req Qty',
            'Tanggal Deadline',
            'Outs. L',
            'Outs. P',
            'Status Kary.',
            'Ket. Status',
            'Educ.',
            'Status PTK',
            'Status Approval',
            'Tanggal Terima',
            'Tgl Pemenuhan',
            'Total Hari Selesai',
            'Intvl. Pemenuhan Deadline'
        ];
    }

    function map($row): array
    {
        return [
            $row->ReqId,
            date('d-m-Y', strtotime($row->CreatedDate)),
            $row->EmployeeIdRequestor,
            $row->ReqNo ?? '-',
            $row->job_title_name,
            $row->ReasonOfHiring,
            $row->department_name,
            $row->grade_title_name,
            $row->QtyMale,
            $row->QtyFemale,
            $row->QtyBoth,
            $row->QtyMale ?? 0+$row->QtyFemale ?? 0+$row->QtyBoth ?? 0,
            $row->Deadline ? date('d-m-Y', strtotime($row->Deadline)):'-',
            $row->OutStandMale,
            $row->OutStandFemale,
            $row->StatusKaryawan,
            $row->EmploymentNote,
            $row->Education,
            ptk_status($row->ReqSts, true),
            $row->ApprovedAll ? 'Selesai':'Progress',
            $row->AcceptedDate ? date('d-m-Y', strtotime($row->AcceptedDate)):'-',
            $row->FilledDate ? date('d-m-Y', strtotime($row->FilledDate)):'-',
            $row->FilledDate ? date_diff(date_create($row->CreatedDate), date_create($row->FilledDate))->format('%a hari'):'-',
            $row->FilledDate && $row->Deadline ? 
                date_diff(date_create($row->FilledDate), date_create($row->Deadline))->format('%R%a hari'):'-',
        ];
    }
}
