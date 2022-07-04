<?php

namespace App\Services;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LeaveService
{
    public function checkLeaveQuota($employee_no, Carbon $carbonDate)
    {
        $now = date('m-d', strtotime($carbonDate->format('Y-m-d')));
        $emp = Employee::where('registration_number', $employee_no)->first();
        if (substr($emp->date_of_work, -5) == '02-29') {
            $emp->date_of_work = substr($emp->date_of_work, 0, 5).'02-28';
        }
        $dow = date('m-d', strtotime($emp->date_of_work));

        if ($dow <= $now) {
            $from = Carbon::createFromDate(date($carbonDate->year.'-m-d', strtotime($emp->date_of_work)));
        }else{
            $from = Carbon::createFromDate(date($carbonDate->year.'-m-d', strtotime($emp->date_of_work)))->subYear();
        }

        $to = $from->copy()->addYear()->subDay();

        $periode = (object) [
            'start_date' => $from->format('Y-m-d'),
            'end_date' => $to->format('Y-m-d'),
        ];
        
        // $periode = DB::select("select concat(year(curdate()),'-',lpad(month(a.date_of_work),2,'00'),'-',lpad(day(a.date_of_work),2,'00')) as start_date, 
        //     date_sub(date_add(concat(year(curdate()),'-',month(a.date_of_work),'-',day(a.date_of_work)), interval 1 year), interval 1 day) as end_date
        //     from employees a where a.registration_number = ?", [$employee_no]);
        // $periode = $periode[0];

        $quota = DB::select("select b.employee_no, (b.qty-b.used+b.qty_before+b.qty_paid) as qty, 
            '$periode->start_date' as start_date, '$periode->end_date' as end_date, 
            b.qty as qty_gen, b.used, b.qty_before, c.qty as qty_extend, c.used as used_extend, 
            c.status as ext_sts, c.quota_id, c.qty-c.used as sisa_extend, c.expired_at
            from employees a
            inner join employee_leave_quotas b on b.employee_no = a.registration_number
            left join leave_quota_extends c on c.quota_id = b.id
            where a.registration_number = ? and b.start_date = ? and b.end_date = ?",[$employee_no, $periode->start_date, $periode->end_date]);

        if (!$quota) {
            return (object) [
                'status' => 'error',
                'msg' => "Belum ada quota cuti pada periode tahun $periode->start_date s/d $periode->end_date",
            ];
        }

        return (object) [
            'status' => 'success',
            'msg' => $quota[0],
        ];
    }

    public function isPengajuan($employee_no, $start_date, $end_date)
    {
        $start_date = Carbon::parse($start_date)->startOfDay();
        $end_date = Carbon::parse($end_date);

        while ($start_date <= $end_date) {
            $pengajuan = DB::select('select b.leave_name, a.status from employee_leaves a
            inner join leaves b on b.leave_code = a.leave_type
            where a.employee_no = ? and ? between a.start_date and a.end_date
            and a.status in ("new", "apv")',[$employee_no, $start_date]);
            if ($pengajuan) {
                return (object) [
                    'status' => 'error',
                    'msg' => 'Ada pengajuan pada tanggal '.$start_date.' ('.$pengajuan[0]->leave_name.' dengan status '.$pengajuan[0]->status.')',
                ];
            }
            $start_date = $start_date->addDay();
        }

        return (object) [
            'status' => 'success',
            'msg' => 'Bisa pengajuan',
        ];
    }

    public function checkTotalDays($employee_no, $start_date, $end_date)
    {
        $checkTotalDays = DB::select('CALL SP_LEAVE_IsPengajuan(?, ?, ?)', [$employee_no, $start_date, $end_date]);

        return (object) [
            'QtyIsPengajuan' => $checkTotalDays[0]->QtyIsPengajuan,
            'QtyDays' => $checkTotalDays[0]->QtyDays,
            'QtyIsHoliday' => $checkTotalDays[0]->QtyIsHoliday,
            'QtyWorkingDays' => $checkTotalDays[0]->QtyWorkingDays,
        ];
    }

    public function checkMaxDays($leave_code, $total_days, $total_working_days)
    {
        $check = DB::select('select leave_code, leave_category, qty_max, is_holiday_count, is_minus_annual from leaves where leave_code = ?', [$leave_code]);
        if (!$check) {
            return (object) [
                'status' => 'error',
                'msg' => 'Kategori leave tidak ditemukan'
            ];
        }
        $check = $check[0];

        if ($check->is_minus_annual == 1 || $leave_code == 'LVANL') {
            return (object) [
                'status' => 'success',
                'msg' => $total_working_days
            ];
        }else{
            if ($check->is_holiday_count == 0) {
                $dayComp = $total_working_days;
            }else {
                $dayComp = $total_days;
            }
            if ($dayComp <= $check->qty_max) {
                return (object) [
                    'status' => 'success',
                    'msg' => $dayComp
                ];
            }else{
                return (object) [
                    'status' => 'error',
                    'msg' => 'Maksimal cuti anda hanya '.$check->qty_max.' hari'
                ];
            }
        }
    }
}