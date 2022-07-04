<?php

namespace App\Console\Commands;

use App\Notifications\NotifCutiExtend;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class SyncCutiExtend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hrms:sync_cuti_extend';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Expired Cuti Extend and send remainder email';

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
        $now = now();
        $log_file = storage_path('logs/CutiExtend/CutiExtend_'.date('Y-m-d').'.txt');
        $msg = '';

        /**
         * Check Quota Extend expired
         */
        $extends = collect(DB::select('select a.*, b.fullname, c.email, c.id as user_id, d.start_date, d.end_date
            from leave_quota_extends a
            inner join employees b on b.registration_number = a.employee_no
            inner join users c on c.employee_id = b.id 
            inner join employee_leave_quotas d on d.id = a.quota_id
            where a.status = 1'));

        $expired = $extends->where('expired_at', '<=', $now);

        DB::table('leave_quota_extends')->whereIn('quota_id', $expired->pluck('quota_id')->toArray())
                ->update([
                    'status' => 0,
                    'updated_at' => $now
                ]);

        $users_expired = User::whereIn('id', $expired->pluck('user_id')->toArray())->get();

        foreach ($users_expired as $user) {
            $data = $expired->firstWhere('user_id', $user->id);
            $msg .= $data->employee_no.' extend expired'.PHP_EOL;
            if ($data) {
                $user->notify(new NotifCutiExtend([
                    'fullname' => $data->fullname,
                    'msg' => "Dengan ini kami sampaikan bahwa kuota cuti extend anda sejumlah <b>".($data->qty-$data->used)."</b> pada 
                                periode <b>".date('d-m-Y', strtotime($data->start_date))."</b> s/d <b>".date('d-m-Y', strtotime($data->end_date))."</b> telah kadaluarsa. Terima Kasih !",
                    'short_msg' => 'Cuti Extend anda kadaluarsa',
                    'subject' => 'Notifikasi Kuota Cuti Extend'
                ]));
            }
        }

        /**
         * Check Quota Existing reminder for 1 and 2 months
         */
        $quotas = collect(DB::select('select a.*, b.fullname, b.date_of_work, c.id as user_id from employee_leave_quotas a
            inner join employees b on b.registration_number = a.employee_no
            inner join users c on c.employee_id = b.id
            where ? between a.start_date and a.end_date
            and b.id not in (select employee_id from employee_retirements)', [$now->format('Y-m-d')]));

        $month_before = $quotas->filter(function($quota) use($now) {
            return $quota->end_date == $now->copy()->addMonths(2)->format('Y-m-d') ||
            $quota->end_date == $now->copy()->addMonth()->format('Y-m-d');
        });

        $users_month_before = User::whereIn('id', $month_before->pluck('user_id')->toArray())->get();

        foreach ($users_month_before as $user) {
            $data = $month_before->firstWhere('user_id', $user->id);
            $msg .= $data->employee_no.' existing reminded'.PHP_EOL;
            if ($data) {
                $user->notify(new NotifCutiExtend([
                    'fullname' => $data->fullname,
                    'msg' => "Dengan ini kami sampaikan bahwa kuota cuti existing anda pada 
                        periode <b>".date('d-m-Y', strtotime($data->start_date))."</b> s/d <b>".date('d-m-Y', strtotime($data->end_date))."</b> akan berakhir 
                        pada <b>".(Carbon::parse($data->end_date)->startOfDay()->addDay()->format('d-m-Y'))."</b> bersamaan dengan pembuatan kuota cuti periode baru",
                    'short_msg' => 'Cuti Existing anda akan segera berakhir',
                    'subject' => 'Notifikasi Kuota Cuti Existing Anda'
                ]));
            }
        }

        if ($msg == '') {
            $msg = 'Nothing'.PHP_EOL;
        }

        $this->output->writeln('Finished');
        file_put_contents($log_file, $msg, FILE_APPEND);
    }
}
