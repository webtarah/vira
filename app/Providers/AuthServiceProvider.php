<?php

namespace App\Providers;

use App\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        Gate::define('viewUser',function (User $user,User $model){
           // dd($user->id ,  $model->id);
            return $user->id == $model->id
                ? Response::allow()
                : Response::deny('you do not have permission');

        });
        $this->registerPolicies();
    }
}
