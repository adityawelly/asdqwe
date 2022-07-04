<?php

namespace App\Http\Controllers;

use File;
use Exception;
use App\Models\Holiday;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class HolidayController extends Controller
{
    public function index(Request $request)
    {
        $holidays = Holiday::query();
        $years = range(now()->year+1, now()->year-5);

        if ($request->has('tahun')) {
            $holidays->whereYear('date', $request->tahun);
        }

        $employee_hk = Employee::whereDoesntHave('hari_kerja')->get();

        return view('holiday.index', [
            'holidays' => $holidays->get(),
            'years' => $years,
            'employee_hk' => $employee_hk,
        ]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            // 'tahun' => 'required',
            'file' => 'required|file'
        ]);
        
        DB::beginTransaction();
        try {
            Holiday::whereYear('date', $request->tahun)->delete();
            $file = $request->file('file');
            $filename = 'temp-holiday-import.'.$file->getClientOriginalExtension();
            $file->move('uploads', $filename);

            $reader = new Xlsx();
            $path = public_path('uploads/'.$filename);
            $sheet = $reader->load($path);
            $sheetData = $sheet->getActiveSheet()->toArray();

            $index = 1;
            $date_limit = date('Y-m-d', strtotime('2021-01-01'));

            foreach ($sheetData as $row) {
                if ($index == 1){
                    $index++;
                    continue;
                }
    
                if ($row[0] == ''){
                    break;
                }

                $date = date('Y-m-d', strtotime($row['0']));
                $cuti_bersama = strtolower($row['2']);
                $hari_kerja = strtolower($row['3']);
                
                $checkExists = Holiday::where('date', $date)->first();
				
                if ($checkExists || $date < $date_limit) {
                    $index++;
                    continue;
                }
                
                Holiday::create([
                    'date' => $date,
                    'date_desc' => $row['1'],
                    'is_mass_leave' => $cuti_bersama == 'ya' ? true:false,
                    'hk' => $hari_kerja == 'semua' ? 0:$hari_kerja
                ]);

                if ($cuti_bersama == 'ya') {
                    if ($hari_kerja == 'semua') {
                        $hk = '(c.hk = 5 or c.hk = 6)';
                    }else{
                        $hk = 'c.hk = '.$hari_kerja;
                    }
                    $checkQuota = DB::select("select a.registration_number, (case when b.qty is null then 1 else 0 end) as qty
                        from employees a
                        left join employee_leave_quotas b on b.employee_no = a.registration_number and ? between b.start_date and b.end_date
                        where a.id not in (select employee_id from employee_retirements)
                        group by a.registration_number, b.qty",[$date]);

                    $generateQuota = [];
                    $carbonDate = Carbon::createFromDate($date);

                    foreach ($checkQuota as $item) {
                        if ($item->qty == 1) {
                            $emx = Employee::where('registration_number', $item->registration_number)->get();
							foreach ($emx as $emp)
							{
	                            if (substr($emp->date_of_work, -5) == '02-29') {
	                                $emp->date_of_work = substr($emp->date_of_work, 0, 5).'02-28';
	                            }
	                            $now = date('m-d', strtotime($carbonDate->format('Y-m-d')));
	                            $dow = date('m-d', strtotime($emp->date_of_work));
								//$now2 = date('m-d', strtotime('+4 days'));
	                            $carbonDOW = Carbon::parse($emp->date_of_work);
	                            $initQuota = $carbonDate->diffInYears($carbonDOW) < 1 ? 0:12;

	                            if ($dow <= $now) {
	                                $from = Carbon::createFromDate(date($carbonDate->year.'-m-d', strtotime($emp->date_of_work)));
	                            }else{
	                                $from = Carbon::createFromDate(date($carbonDate->year.'-m-d', strtotime($emp->date_of_work)))->subYear();
	                            }
	                            $to = $from->copy()->addYear()->subDay();
							
                            $generateQuota[] = [
                                'employee_no' => $item->registration_number,
                                'start_date' => $from->format('Y-m-d'),
                                'end_date' => $to->format('Y-m-d'),
                                'qty' => $initQuota,
                                'used' => 0,
                                'qty_before' => 0,
                            ];
                            
							//throw new Exception("Karyawan NIK $item->registration_number belum punya quota diperiode di tanggal $date untuk dikurangi");
                        }
					  }
                    }
                    //Jika ada yang belum generate quota, maka generate quota
                    if (count($generateQuota) > 0) {
                        DB::table('employee_leave_quotas')->insert($generateQuota);
                    }

                    DB::insert("insert into employee_leaves(employee_no, leave_type, start_date, end_date, reason, status, total, approval_by, created_at)
                        select a.registration_number, 'LVANL', ?, ?, 'Cuti Bersama tgl $date', 'apv', 1, 0, now()
                        from employees a
                        inner join employee_leave_quotas b on b.employee_no = a.registration_number and ? between b.start_date and b.end_date
                        inner join employee_hks c on c.employee_no = a.registration_number and $hk 
                        where a.id not in (select employee_id from employee_retirements)", [$date, $date, $date, $date]);

                    DB::update("update employee_leave_quotas a
                        inner join employee_hks c on c.employee_no = a.employee_no
                        set a.used = a.used + 1
                        where $hk and ? between a.start_date and a.end_date",[$date]);
                }

                $index++;
            }

            DB::commit();
            File::delete($path);
            return redirect()->back()->with('alert', [
                'type' => 'success',
                'msg' => 'Berhasil import '.($index-1).' data',
            ]);
        } catch (Exception $ex) {
            DB::rollBack();
            if (file_exists($path)) {
                File::delete($path);
            }
            return redirect()->back()->with('alert', [
                'type' => 'danger',
                'msg' => $ex->getMessage()
            ]);
        }
    }

    public function delete(Request $request)
    {
        $date = $request->date;
        $holiday = Holiday::where('date', $date)->first();

        if (!$holiday) {
            return redirect()->back()->with('alert', [
                'type' => 'danger',
                'msg' => 'Tanggal tidak terdaftar'
            ]);
        }

        DB::beginTransaction();
        try {
            if ($holiday->is_mass_leave) {
                DB::update("update employee_leave_quotas set used = used-1 where ? between start_date and end_date and employee_no in (
                    select employee_no from employee_leaves 
                    where start_date = ? and end_date = ? 
                    and status = 'apv' and approval_by = 0 and leave_type = 'LVANL'
                    )", [$date, $date, $date]);
                
                DB::delete("delete from employee_leaves 
                    where start_date = ? and end_date = ? 
                    and status = 'apv' and approval_by = 0 and leave_type = 'LVANL'", [$date, $date]);

                $holiday->delete();
            }else{
                $holiday->delete();
            }

            DB::commit();
            return redirect()->back()->with('alert', [
                'type' => 'success',
                'msg' => 'Berhasil hapus tanggal '.$date
            ]);
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('alert', [
                'type' => 'danger',
                'msg' => $ex->getMessage()
            ]);
        }
    }
}
