<?php


namespace Socotoly\Filterable\Contracts;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class Filter
{
    public $name;

    protected $builder;

    protected $model;

    public function __construct(Builder &$builder, Model $model)
    {
        $name = strtolower(class_basename(static::class));

        $pos = strpos($name, 'filter');

        if ($pos !== false)
            $name = substr($name, 0, $pos - strlen($name));

        $this->name = $name;
        $this->builder = $builder;
        $this->model = $model;
    }

    abstract public function apply(): void;

    protected function applicable(): bool
    {
        if (!request()->has('for'))
            return true;

        $query = request()->server->get('QUERY_STRING');
        parse_str($query, $url);
        dd($url);
        $pos = strpos($query, 'for');
        if ($pos > 0)
        {
            $query = substr($query,0, $pos);
            parse_str($query, $url);
            $res = array_key_exists($this->name, $url);
            dd($res);
        }
        $query = substr($query, $pos);

        $pos = strpos($query, 'for', 3);

        if ($pos !== false)
        {
            $query = substr($query, 0, $pos - strlen($query) - 1);
        }

        $query = substr($query, 3);

        if (strlen($query) > 1 && strpos($query, '=') == 0)
        {
            $pos = strpos($query, '&');
            if ($pos !== false)
            {
                $query = substr($query, 1, $pos - strlen($query));
            }
        }else{
            return false;
        }

        if (strtolower($query) == strtolower(class_basename($this->model)))
            return true;

        return false;
    }
}
