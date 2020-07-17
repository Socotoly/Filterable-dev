<?php


namespace Socotoly\Filterable\Filters;


use Socotoly\Filterable\Contracts\Filter;

class OrderFilter extends Filter
{

    const ASC = 'asc';
    const DSC = 'dsc';

    public function apply(): void
    {
        $order = $this->request->get('order', $this->model);
        $order = empty($order) ? 'asc' : $order;
        $orderBy = $this->request->has('orderby', $this->model) ? $this->request->get('orderby', $this->model) : 'id';

        if (!($orderBy && $this->model->getConnection()->getSchemaBuilder()->hasColumn($this->model->getTable(), $orderBy)))
            $orderBy = 'id';

        if ($order == self::ASC) {
            $this->builder->orderBy($orderBy);
        } elseif ($order == self::DSC) {
            $this->builder->orderByDesc($orderBy);
        }
    }
}
