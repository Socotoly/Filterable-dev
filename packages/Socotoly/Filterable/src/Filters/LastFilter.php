<?php


namespace Socotoly\Filterable\Filters;


use Socotoly\Filterable\Contracts\Filter;

class LastFilter extends Filter
{

    public function apply($queryValue): void
    {
        $boundOrder = $this->builder->getQuery()->orders;

        $order = 'desc';
        $orderBy = $this->model->getKeyName();

        if(is_array($boundOrder))
        {
            $orderBy = $boundOrder[0]['column'];
            $order = $boundOrder[0]['direction'] == 'desc' ? 'asc' : $order;

            array_splice($this->builder->getQuery()->orders, 0);
        }

        $this->builder->orderBy($orderBy, $order)->take(1);
    }
}
