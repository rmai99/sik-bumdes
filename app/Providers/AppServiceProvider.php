<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Auth;
use App\Companies;
use App\Business;
use App\Employee;

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
        // view()->composer('user.layout.navbar', function ($view) {

        //     $role = Auth::user();
        //     $isOwner = $role->hasRole('owner');
        //     $user = Auth::user()->id;

        //     if($isOwner){
        //         $session = session('business');
                
        //         $company = Companies::where('id_user', $user)->first()->id;
                
        //         $business = Business::where('id_company', $company)->get();
        //         $getBusiness = Business::where('id_company', $company)->first()->id;
                
        //         if($session == 0){
        //             $session = $getBusiness;
        //         }
        //     } 
        //     else 
        //     {
        //         $getBusiness = Employee::where('id_user', $user)->select('id_business')->first();
        //         $idBusiness= $getBusiness->id_business;
    
        //         $session = $idBusiness;
    
        //     }
        
        //     $view->with(compact('session', 'business'));
        
        // });

        Schema::defaultStringLength(191);
    }
}
