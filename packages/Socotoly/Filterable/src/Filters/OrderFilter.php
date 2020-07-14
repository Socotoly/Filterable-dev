<?php


namespace Socotoly\Filterable\Filters;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Socotoly\Filterable\Contracts\Filter;
use Socotoly\Filterable\Support\Helpers;

class OrderFilter extends Filter
{

    const ASC = 'asc';
    const DSC = 'dsc';

    public function apply(Builder &$builder, Model $model)
    {
        $request = Helpers::arrayToLowerCase(request()->all(), false);

        $orderBy = in_array('orderby', array_keys($request)) ? $request['orderby'] : 'id';

        if (!($orderBy && $model->getConnection()->getSchemaBuilder()->hasColumn($model->getTable(), $orderBy)))
            $orderBy = 'id';

        if ($request['order'] == self::ASC) {
            $builder->orderBy($orderBy);
        } elseif ($request['order'] == self::DSC) {
            $builder->orderByDesc($orderBy);
        };
    }
}
