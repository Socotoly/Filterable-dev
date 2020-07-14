<?php


namespace Socotoly\Filterable;


use Exception;
use Illuminate\Database\Eloquent\Builder;
use Socotoly\Filterable\Contracts\Filter;

class FilterFactory
{

    private $builder;

    private $userFilters;

    private $filters;

    public function __construct(Builder $builder, array $filters)
    {
        $this->builder = $builder;
        $this->userFilters = $filters;

        $this->filters = collect();
    }

    public function apply()
    {
        foreach ($this->userFilters as $filter) {
            if (!is_a($filter, Filter::class, true))
                throw new Exception('the provided filter is not a type of Filter class');

            $this->filters->add(new $filter);
        }

        $filters = require_once "Filters.php";

        foreach ($filters as $filter) {
            $this->filters->add(new $filter);
        }

        $this->filters->whereIn('name', request()->all())->each(function (Filter $filter) {
            $filter->apply($this->builder);
        });
    }
}
