<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

//passport
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        //passport
        'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //GATES
        Gate::define('isAdmin', function ($user)
        {
            return $user->type==='admin';
        });
        Gate::define('isAuthor', function ($user)
        {
            return $user->type==='author';
        });
        Gate::define('isUser', function ($user)
        {
            return $user->type==='user';
        });

        //passport
        if (! $this->app->routesAreCached()) {
            Passport::routes();
        }
    }
}
