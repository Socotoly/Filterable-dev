<?php


namespace Socotoly\Filterable\Traits;


use Exception;
use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    private $filters = [];

    public function scopeFilter(Builder $query): Builder
    {
        if (!isset($this->filter)) {
            $filter = '\\App\\Filters\\' . class_basename(self::class) . 'Filter';
            if (class_exists($filter)) {
                $this->filter = $filter;
            } else {
                throw new Exception('Can not find filter ' . $filter);
            }
        }

        if (!is_a($this->filter, Filter::class, true))
            throw new Exception('the provided filter is not a type of Filter class');

        return (new $this->filter($query))->apply();
    }
}
