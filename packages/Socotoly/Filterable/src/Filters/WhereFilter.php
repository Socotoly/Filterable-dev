<?php

namespace Socotoly\Filterable\Filters;


use Socotoly\Filterable\Contracts\Filter;

class WhereFilter extends Filter
{

    private $operators = ['=', '<', '>', '<=', '>='];

    public function apply($queryValue): void
    {
        if (!is_array($queryValue))
            return;

        $property = $queryValue[0];
        $comparer = $queryValue[1];

    }
}
