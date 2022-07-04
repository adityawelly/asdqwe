<?php

namespace App\Console\Commands;

use App\LeaveRecords;
use App\Models\Employee;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateLeaveQuota extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hrms:generate_leave_quota';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Employee Leave Quota';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $employees = Employee::all();
		//$employees = DB::select('select * from hrms.employees where id not in (select employee_id from hrms.employee_retirements)');
        //$carbonNow = now()->startOfDay();
		$carbonNow2 = now()->addDays(4);
		$carbonNow = $carbonNow2->startOfDay();
        $now = date('m-d');
		$now2 = date('m-d', strtotime('+4 days'));
        $createdAt = now();
        $log_file = storage_path('logs/GenerateQuota/GenerateQuota_'.date('Y-m-d').'.txt');
        
        foreach ($employees as $employees) {
            $emp = Employee::where('registration_number', $employees->registration_number)->first();
            if (substr($emp->date_of_work, -5) == '02-29') {
                $emp->date_of_work = substr($emp->date_of_work, 0, 5).'02-28';
            }
            $dow = date('m-d', strtotime($emp->date_of_work));
            $carbonDOW = Carbon::parse($emp->date_of_work);
            $initQuota = $carbonNow->diffInYears($carbonDOW) < 1 ? 0:12;

            if ($dow <= $now2) {
                $from = Carbon::createFromDate(date($carbonNow->year.'-m-d', strtotime($emp->date_of_work)));
            }else{
                $from = Carbon::createFromDate(date($carbonNow->year.'-m-d', strtotime($emp->date_of_work)))->subYear();
            }

            $to = $from->copy()->addYear()->subDay();
            // start periode before
            $bstart_date = $from->copy();
            $bstart_date = $bstart_date->subYear();
            // end periode before
            $bend_date = $bstart_date->copy();
            $bend_date = $bend_date->addYear()->subDay();

            // check quota periode
            $exist = DB::table('employee_leave_quotas')
            ->where([
                'start_date' => $from,
                'end_date' => $to,
                'employee_no' => $emp->registration_number,
            ])->first();

            //check quota periode sebelum
            $before = DB::table('employee_leave_quotas')
            ->select(['*', DB::raw('qty-used+qty_before as sisa')])
            ->where([
                'start_date' => $bstart_date,
                'end_date' => $bend_date,
                'employee_no' => $emp->registration_number,
            ])->first();
			
			//echo $before;

            if (!$exist) {
                if ($before) {
                    if ($before->sisa < 0) {
                        $sisa = 0;
                        $opname = $before->sisa;

                        DB::table('employee_opname_quotas')->insert([
                            'start_date' => $bstart_date,
                            'end_date' => $bend_date,
                            'employee_no' => $emp->registration_number,
                            'qty' => $opname,
                            'status' => 'new',
                            'note' => 'auto system',
                            'created_at' => $createdAt
                        ]);
                    }
					elseif ($before->sisa > 0) {
                        $sisa = 0;
						$idk = $emp->registration_number;
						$sisaan = $before->sisa;
						$endext = Carbon::parse($from)->addMonths(6);
						//cek penolakan cuti 
						$extends = DB::select("select sum(total) as jumlah from employee_leaves where start_date BETWEEN '$bstart_date' and '$bend_date' and leave_type='LVANL' and status='rjt' and is_extend=0 and employee_no='$idk'");
						
						foreach ($extends as $extends) {
						$jmlext = $extends->jumlah;
						}
						if ($jmlext > 0)
						{
							if ($sisaan >= $jmlext)
							{
								DB::table('leave_quota_extends')->insert([
						                'employee_no' => $idk,
						                'quota_id' => 0,
						                'qty' => $jmlext,
						                'used' => 0,
										'auto' => 1,
						                'expired_at' => $endext,
						                'status' => now() > $endext ? 0:1
						            ]);
							}
							else
							{
								DB::table('leave_quota_extends')->insert([
						                'employee_no' => $idk,
						                'quota_id' => 0,
						                'qty' => $sisaan,
						                'used' => 0,
										'auto' => 1,
						                'expired_at' => $endext,
						                'status' => now() > $endext ? 0:1
						            ]);
							}
						}
					
                    } 
					else{
                        $sisa = $before->sisa;
                    }

                    DB::table('employee_leave_quotas')->insert([
                        'employee_no' => $emp->registration_number,
                        'start_date' => $from,
                        'end_date' => $to,
                        'qty' => $initQuota,
                        'used' => 0,
                        'qty_before' => $sisa,
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt
                    ]);
                    $msg = $emp->registration_number.' generate quota '.$from.' s/d '.$to;
					
					$cekidext = DB::Select("select id from employee_leave_quotas where employee_no='$emp->registration_number' and start_date='$from' and end_date='$to'");
						foreach ($cekidext as $cekidext) {
							$hasil = $cekidext->id;
							}
						
						DB::table('leave_quota_extends')->where([
	                            'quota_id' => 0,
	                            'employee_no' => $emp->registration_number,
	                        ])->update([
	                            'quota_id' => $hasil,
	                        ]);

                }else{
                    if ($carbonDOW <= $carbonNow) {
                        DB::table('employee_leave_quotas')->insert([
                            'employee_no' => $emp->registration_number,
                            'start_date' => $from,
                            'end_date' => $to,
                            'qty' => $initQuota,
                            'used' => 0,
                            'qty_before' => 0,
                            'created_at' => $createdAt,
                            'updated_at' => $createdAt,
                        ]);
                        $msg = $emp->registration_number.' generate quota '.$from.' s/d '.$to;
                    }else{
                        $msg = $emp->registration_number.' skip cause not yet quota '.$from.' s/d '.$to;
                    }
                }
            }else{
                if (!$exist->updated_at) {
                    if ($before) {
                        // Jika sisa lebih dari -6 maka masukan kelebihan ke opname dan set qty_before -6
                        if ($before->sisa < 0) {
                            $sisa = 0;
                            $opname = $before->sisa;
    
                            DB::table('employee_opname_quotas')->insert([
                                'start_date' => $bstart_date,
                                'end_date' => $bend_date,
                                'employee_no' => $emp->registration_number,
                                'qty' => $opname,
                                'status' => 'new',
                                'note' => 'auto system',
                                'created_at' => $createdAt
                            ]);
                        // Jika ada sisa quota lebih masukin ke opname dan qty_before 0
                        }
						
						elseif ($before->sisa > 0) {
                            $sisa = 0;
						$idk = $emp->registration_number;
						$sisaan = $before->sisa;
						$endext = Carbon::parse($from)->addMonths(6);
						//cek penolakan cuti 
						$extends = DB::select("select sum(total) as jumlah from employee_leaves where start_date BETWEEN '$bstart_date' and '$bend_date' and leave_type='LVANL' and is_extend=0 and status='rjt' and employee_no='$idk'");
						
						foreach ($extends as $extends) {
						$jmlext = $extends->jumlah;
						}
						if ($jmlext > 0)
						{
							if ($sisaan >= $jmlext)
							{
								DB::table('leave_quota_extends')->insert([
						                'employee_no' => $idk,
						                'quota_id' => 0,
						                'qty' => $jmlext,
						                'used' => 0,
										'auto' => 1,
						                'expired_at' => $endext,
						                'status' => now() > $endext ? 0:1
						            ]);
							}
							else
							{
								DB::table('leave_quota_extends')->insert([
						                'employee_no' => $idk,
						                'quota_id' => 0,
						                'qty' => $sisaan,
						                'used' => 0,
										'auto' => 1,
						                'expired_at' => $endext,
						                'status' => now() > $endext ? 0:1
						            ]);
							}
						}
							/*
                            $opname = $before->sisa;
    
                            DB::table('employee_opname_quotas')->insert([
                                'start_date' => $bstart_date,
                                'end_date' => $bend_date,
                                'employee_no' => $emp->registration_number,
                                'qty' => $opname,
                                'status' => 'new',
                                'note' => 'auto system',
                                'created_at' => $createdAt
                            ]);
							*/
                        } 
						else{
                            $sisa = $before->sisa;
                        }
                        DB::table('employee_leave_quotas')->where([
                            'start_date' => $from,
                            'end_date' => $to,
                            'employee_no' => $emp->registration_number,
                        ])->update([
                            'qty_before' => $sisa,
                            'updated_at' => $createdAt
                        ]);
                        $msg = $emp->registration_number.' clear, qty before '.$before->sisa;
						
						$cekidext = DB::Select("select id from employee_leave_quotas where employee_no='$emp->registration_number' and start_date='$from' and end_date='$to'");
						foreach ($cekidext as $cekidext) {
							$hasil = $cekidext->id;
							}
						
						DB::table('leave_quota_extends')->where([
	                            'quota_id' => 0,
	                            'employee_no' => $emp->registration_number,
	                        ])->update([
	                            'quota_id' => $hasil,
	                        ]);
						
                    }else{
                        $msg = $emp->registration_number.' clear, ada quota gak ada beforenya';
                    }
                }else{
                    $msg = $emp->registration_number.' skipped '.$now2;
                }
            }
            file_put_contents($log_file, $msg.PHP_EOL, FILE_APPEND);
            $this->output->writeln($msg);
        }
        $this->output->writeln('Finished');
    }
}
