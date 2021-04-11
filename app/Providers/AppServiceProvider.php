<?php

namespace App\Providers;

use ConsoleTVs\Charts\Registrar as Charts;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public function register()
    {
        //
    }


    public function boot(Charts $charts)
    {
        $charts->register([
            \App\Charts\SampleChart::class
        ]);

        Route::resourceVerbs([
            'create'=>'novo',
            'edit'=>'editar'
        ]);

         //Custom blade directive for role check
        Blade::if('role', function ($role) {
            return Auth::user()->role->slug == $role;

        });
    }
}
