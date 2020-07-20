<?php


namespace Socotoly\Filterable\Filters;


use Socotoly\Filterable\Contracts\Filter;

class FirstFilter extends Filter
{

    public function apply($queryValue): void
    {
        $this->builder->take(1);
    }
}
