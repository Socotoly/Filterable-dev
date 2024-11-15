<?php


namespace Socotoly\Filterable;


use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Socotoly\Filterable\Contracts\Filter;
use Socotoly\Filterable\Support\Helpers;
use Socotoly\Filterable\Support\Request;

class FilterFactory
{

    private $builder;

    private $userFilters;

    private $filters;

    private $model;

    private $request;

    public function __construct(Builder &$builder, Model $model, array $filters)
    {
        $this->builder = $builder;
        $this->userFilters = $filters;
        $this->model = $model;

        $this->filters = collect();
        $this->request = new Request(request()->server->get('QUERY_STRING'));
    }

    public function apply(): Builder
    {
        foreach ($this->userFilters as $filter) {
            if (!is_a($filter, Filter::class, true))
                throw new Exception('the provided filter is not a type of Filter class');

            $this->filters->add(new $filter($this->builder, $this->model, $this->request));
        }

        $filters = require "Filters.php";

        foreach ($filters as $filter) {
            $this->filters->add(new $filter($this->builder, $this->model, $this->request));
        }

        $allFilters = $this->request->allFilters();
        $undefined = $allFilters->only('undefined')->first();
        $otherFilters = $allFilters->except('undefined');

        $this->applyFilters($undefined);

        $otherFilters->reverse()->each(function ($filterQuery, $key) {
            if (Helpers::classToLowerCase($this->model) != $key)
                return;

            $this->applyFilters($filterQuery);
        });

        return $this->builder;
    }

    private function applyFilters(Collection $filters): void
    {
        foreach ($filters as $filterName => $filterVal)
        {
            $filter = $this->filters->first(function (Filter $filter) use ($filterName, $filterVal) {
                if ($filter->name == $filterName)
                    return true;

                return false;
            });

            if ($filter && $this->applicable($filter))
                $filter->apply($this->formatQuery($filterVal));
        }
    }

    private function applicable(Filter $filter): bool
    {
        if ($this->request->has($filter->name, $filter->model))
            return true;

        return false;
    }

    /**
     * @param string $query
     * @return int|string|array
     */
    private function formatQuery(string $query)
    {
        if (is_numeric($query))
            return $query;

        if (strpos($query, ',') !== false)
            return explode(',', $query);

        return $query;
    }
}
