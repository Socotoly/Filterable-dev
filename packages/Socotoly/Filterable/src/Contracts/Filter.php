<?php


namespace Socotoly\Filterable\Contracts;


use Illuminate\Database\Eloquent\Builder;

abstract class Filter
{
    public $name;

    public function __construct()
    {
        $name = strtolower(class_basename(self::class));
        if($pos = strpos($name, 'Filter') !== false)
            $name = substr($name, $pos);

        $this->name = $name;
    }

    abstract public function apply(Builder &$builder);
}
