<?php


namespace Socotoly\Filterable\Filters;


use Socotoly\Filterable\Contracts\Filter;
use Socotoly\Filterable\Support\Helpers;

class OrderFilter extends Filter
{

    const ASC = 'asc';
    const DSC = 'dsc';

    public function apply(): void
    {
        $request = Helpers::arrayToLowerCase(request()->all(), false);

        $orderBy = in_array('orderby', array_keys($request)) ? $request['orderby'] : 'id';

        if (!($orderBy && $this->model->getConnection()->getSchemaBuilder()->hasColumn($this->model->getTable(), $orderBy)))
            $orderBy = 'id';

        if ($request['order'] == self::ASC) {
            $this->builder->orderBy($orderBy);
        } elseif ($request['order'] == self::DSC) {
            $this->builder->orderByDesc($orderBy);
        }
    }
}
