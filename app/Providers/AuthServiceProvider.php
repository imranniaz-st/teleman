<?php

namespace App\Providers;

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
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('admin', function ($user) {
            return $user->role == 'admin';
        });

        Gate::define('everyone', function ($user) {
            return $user->role == 'admin' || $user->role == 'customer' || $user->role == 'agent';
        });

        Gate::define('customer', function ($user) {
            return $user->role == 'customer';
        });

        Gate::define('staff', function ($user) {
            return $user->role == 'staff';
        });

        Gate::define('agent', function ($user) {
            return $user->role == 'agent';
        });

        Gate::define('dev', function ($user) {
            return env('DEV_MODE') == 'YES';
        });

        Gate::define('adminCustomer', function ($user) {
            return $user->role == 'admin' || $user->role == 'customer';
        });

        Gate::define('CustomerAgent', function ($user) {
            return $user->role == 'agent' || $user->role == 'customer';
        });
    }
}
