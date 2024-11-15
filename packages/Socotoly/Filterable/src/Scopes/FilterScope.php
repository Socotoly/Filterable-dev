<?php


namespace Socotoly\Filterable\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class FilterScope implements Scope
{

    public function apply(Builder $builder, Model $model)
    {
        $builder->filter();
    }
}
