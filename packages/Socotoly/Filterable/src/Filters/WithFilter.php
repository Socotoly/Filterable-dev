<?php


namespace Socotoly\Filterable\Filters;


use Socotoly\Filterable\Contracts\Filter;

class WithFilter extends Filter
{

    public function apply(): void
    {
        $this->applicable();
        $with = request('with');

    }
}
