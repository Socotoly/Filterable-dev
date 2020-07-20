<?php


namespace Socotoly\Filterable\Filters;


use Socotoly\Filterable\Contracts\Filter;

class OrderByFilter extends Filter
{

    const ASC = 'asc';
    const DSC = 'dsc';

    public function apply($queryValue): void
    {
        if (is_array($queryValue))
        {
            $orderBy = $queryValue[0];
            $order = $queryValue[1] == self::ASC || $queryValue[1] == self::DSC ? $queryValue[1] : self::ASC;
        }else{
            $orderBy = $queryValue;
            $order = self::ASC;
        }

        if (!($orderBy && $this->model->getConnection()->getSchemaBuilder()->hasColumn($this->model->getTable(), $orderBy)))
            $orderBy = $this->model->getKeyName();

        if ($order == self::ASC) {
            $this->builder->orderBy($orderBy);
        } elseif ($order == self::DSC) {
            $this->builder->orderByDesc($orderBy);
        }
    }
}
