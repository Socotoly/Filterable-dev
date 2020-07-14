<?php


namespace Socotoly\Filterable\Filters;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Socotoly\Filterable\Contracts\Filter;

class FirstFilter extends Filter
{

    public function apply(Builder &$builder, Model $model)
    {
        $builder->take(1);
    }
}
