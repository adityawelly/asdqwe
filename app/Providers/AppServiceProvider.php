<?php

namespace App\Providers;

use App\GlobalSetting;
use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        setlocale(LC_ALL, 'id_ID');
        date_default_timezone_set('Asia/Jakarta');
        
        Schema::defaultStringLength(191);

        view()->composer('*', function(View $view){
            $app_settings = cache()->remember('app_settings', 60*15, function(){
                return new GlobalSetting();
            });
            $view->with('app_settings', $app_settings);
        });

        view()->composer('layouts.partials.navbar', function(View $view){
            $unreads = auth()->user()->unreadNotifications;
            $view->with([
                'unreads' => $unreads->take(5),
                'countUnreads' => count($unreads),
            ]);
        });
    }
}
