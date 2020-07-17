<?php


namespace Socotoly\Filterable\Contracts;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Socotoly\Filterable\Support\Request;

abstract class Filter
{
    public $name;

    public $model;

    protected $builder;

    protected $request;

    public function __construct(Builder &$builder, Model $model, Request &$request)
    {
        if (empty($this->name))
        {
            $name = strtolower(class_basename(static::class));

            $pos = strpos($name, 'filter');

            if ($pos !== false)
                $name = substr($name, 0, $pos - strlen($name));

            $this->name = $name;
        }

        $this->builder = $builder;
        $this->model = $model;

        $this->request = $request;
    }

    abstract public function apply(): void;

}
