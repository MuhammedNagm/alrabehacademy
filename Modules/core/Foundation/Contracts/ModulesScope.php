<?php

namespace Modules\Foundation\Contracts;


interface ModulesScope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     * @param $builder
     * @param array $extras
     * @return void
     */
    public function apply($builder, $extras = []);
}
