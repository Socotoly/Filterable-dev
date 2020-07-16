<?php


namespace Socotoly\Filterable\Filters;


use Socotoly\Filterable\Contracts\Filter;

class FirstFilter extends Filter
{

    public function apply(): void
    {
        $this->builder->take(1);
    }
}
