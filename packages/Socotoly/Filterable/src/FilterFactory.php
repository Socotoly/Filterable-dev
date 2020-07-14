<?php


namespace Socotoly\Filterable;


use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Socotoly\Filterable\Contracts\Filter;
use Socotoly\Filterable\Support\Helpers;

class FilterFactory
{

    private $builder;

    private $userFilters;

    private $filters;

    private $model;

    public function __construct(Builder &$builder, Model $model, array $filters)
    {
        $this->builder = $builder;
        $this->userFilters = $filters;
        $this->model = $model;

        $this->filters = collect();
    }

    public function apply(): Builder
    {
        foreach ($this->userFilters as $filter) {
            if (!is_a($filter, Filter::class, true))
                throw new Exception('the provided filter is not a type of Filter class');

            $this->filters->add(new $filter);
        }

        $filters = require "Filters.php";

        foreach ($filters as $filter) {
            $this->filters->add(new $filter);
        }

        $request = Helpers::arrayToLowerCase(request()->keys());

        $this->filters->whereIn('name', $request)->each(function (Filter $filter) {
            $filter->apply($this->builder, $this->model);
        });

        return $this->builder;
    }
}
