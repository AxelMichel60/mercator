<?php

namespace App\Providers;

use App\Ldap\LdapUser;
use App\Ldap\Scopes\OnlyOrgUnitUser;
use App\Models\MApplication;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        // if (! app()->runningInConsole()) {
        //    Passport::routes();
        // }

        // if LDAP activated
        if (env('LDAP_DOMAIN')) {
            // LDAP Restrictions on connection
            LDAPuser::addGlobalScope(new OnlyOrgUnitUser);

            // Token expires after 4 hours
            Passport::tokensExpireIn(now()->addHours(4));
            Passport::refreshTokensExpireIn(now()->addHours(4));
            Passport::personalAccessTokensExpireIn(now()->addHours(4));
        }

        /**
         * MApplication
         */
        Gate::define('is-cartographer-m-application', function (User $user, MApplication $application) {
            if (! config('app.cartographers', false) || $user->isAdmin()) {
                return true;
            }

            return $application->hasCartographer($user);
        });
    }
}
