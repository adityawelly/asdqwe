<?php

namespace App\Console\Commands;

use App\Notifications\NotifCutiExtend;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class SyncPKWT extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hrms:sync_pkwt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Expired Contract and send remainder email';

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
        //$log_file = storage_path('logs/CutiExtend/CutiExtend_'.date('Y-m-d').'.txt');
        $msg = '';

        /**
         * Check Quota Existing reminder for 1 and 2 months
         */
        $quotas = collect(DB::select('select a.*, b.fullname, b.date_of_work, c.id as user_id from list_pkwt a
            inner join employees b on b.registration_number = a.employee_id
            inner join users c on c.employee_id = b.direct_superior
            where a.edate ?
            and b.id not in (select employee_id from employee_retirements)', [$now->format('Y-m-d')]));

        $month_before = $quotas->filter(function($quota) use($now) {
            return $quota->edate == $now->copy()->addMonth()->format('Y-m-d');
        });

        $users_month_before = User::whereIn('id', $month_before->pluck('user_id')->toArray())->get();

        foreach ($users_month_before as $user) {
            $data = $month_before->firstWhere('user_id', $user->id);
            $msg .= $data->employee_no.' contract reminded'.PHP_EOL;
            if ($data) {
                $user->notify(new NotifCutiExtend([
                    'fullname' => $data->fullname,
                    'msg' => "Dengan ini kami sampaikan bahwa PKWT atas nama ".$data->fullname."  akan berakhir 
                        pada".date('d-m-Y', strtotime($data->edate))."</b> akan berakhir.</b> silahkan segera ajukan pembuatan FPK.",
                    'short_msg' => 'Pemberitahuan PKWT',
                    'subject' => 'Notifikasi Pemberitahuan PKWT a/n'.$data->fullname
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
