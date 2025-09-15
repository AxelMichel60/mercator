<?php

namespace App\Ldap\Scopes;

use LdapRecord\Models\Model;
use LdapRecord\Models\Scope;
use LdapRecord\Query\Model\Builder;

class OnlyOrgUnitUser implements Scope
{
    /**
     * Apply the scope to the given query.
     */
    public function apply(Builder $query, Model $model): void
    {
        if (! config('app.ldap_scope')) {
            return;
        }

        $query->in(config('app.ldap_scope'));
    }
}
