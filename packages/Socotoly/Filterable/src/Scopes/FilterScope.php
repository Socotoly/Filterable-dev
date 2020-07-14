<?php


namespace Socotoly\Filterable\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Socotoly\Filterable\FilterFactory;

class FilterScope implements Scope
{

    private $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function apply(Builder $builder, Model $model)
    {
        (new FilterFactory($builder, $this->filters))->apply();
    }
}
