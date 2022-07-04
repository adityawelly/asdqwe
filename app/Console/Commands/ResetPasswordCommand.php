<?php

namespace App\Console\Commands;

use App\User;
use App\Mail\ResetPassword;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ResetPasswordCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hrms:reset_password';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset password all users';

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
        $users = User::whereHas('employee')
                ->whereDate('updated_at', '<', date('Y-m-d'))
                ->get();
            
        foreach ($users as $user) {
            $new_password = random_string(6);

            $user->update([
                'password' => bcrypt($new_password),
            ]);

            Mail::send(new ResetPassword([
                'new_password' => $new_password,
                'email' => $user->email,
            ]));

            $this->output->writeln('Reset and send to '.$user->email);
        }
        $this->output->writeln('Finished');
    }
}
