<?php


namespace Socotoly\Filterable\Contracts;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class Filter
{
    public $name;

    public function __construct()
    {
        $name = strtolower(class_basename(static::class));

        $pos = strpos($name, 'filter');
        if ($pos !== false)
            $name = substr($name, 0, $pos - $pos * 2 - 1);

        $this->name = $name;
    }

    abstract public function apply(Builder &$builder, Model $model);
}
