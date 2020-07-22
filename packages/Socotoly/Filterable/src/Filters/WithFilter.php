<?php


namespace Socotoly\Filterable\Filters;


use Closure;
use Socotoly\Filterable\Contracts\Filter;

class WithFilter extends Filter
{

    private $with;

    private $relatedModel;

    public function apply($queryValue): void
    {
        if (is_array($queryValue)) {
            foreach ($queryValue as $val) {
                if ($this->validate($val))
                {
                    $this->builder->with($this->with);
                    $this->with = '';
                }
            }
        } else {
            if ($this->validate($queryValue))
                $this->builder->with($this->with);
        }
    }

    private function validate($query): bool
    {
        $query = explode('.', $query);

        foreach ($query as $key => $string) {
            $model = $key == 0 ? $this->model : $query[$key - 1];

            if ($this->relationExists($string, $model))
            {
                $this->with .= ($key == 0 ? '' : '.') . $string;
            }else{
                break;
            }
        }

        return !empty($this->with);
    }

    private function relationExists(string $query, $model): bool
    {
        if (is_string($model))
        {
            $reader = $this->propertyReader();
            $this->relatedModel = &$reader($this->relatedModel->$model(), 'related');

            if ($this->relationFunctionExists($query, $this->relatedModel))
                return true;
        }else{
            $this->relatedModel = $this->model;
            if ($this->relationFunctionExists($query, $this->relatedModel))
                return true;
        }

        return false;
    }

    private function relationFunctionExists(string $query, $model): bool
    {
        return !!method_exists($model, $query)
            && ($method = new \ReflectionMethod($model, $query))->isPublic()
            && $method->getNumberOfParameters() == 0
            && $this->checkFunction($method);
    }

    private function checkFunction(\ReflectionMethod $func): bool
    {
        $relations = ['hasOne', 'hasMany', 'belongsTo',
            'belongsToMany', 'hasOneThrough', 'hasManyThrough',
            'morphTo', 'morphOne', 'morphMany', 'morphToMany',
            'morphedByMany'];

        // https://stackoverflow.com/a/50329308
        $f = $func->getFileName();
        $start_line = $func->getStartLine() - 1;
        $end_line = $func->getEndLine();

        $source = file($f);

        $body = '';
        for ($i = $start_line; $i < $end_line; $i++)
            $body .= "{$source[$i]}\n";

        foreach ($relations as $relation) {
            $function = '$this->' . $relation . '(';
            if (strpos($body, $function) !== false)
                return true;
        }

        return false;
    }

    // https://stackoverflow.com/a/58626859
    private function propertyReader(): Closure
    {
        return function &($object, $property) {
            $value = &Closure::bind(function &() use ($property) {
                return $this->$property;
            }, $object, $object)->__invoke();
            return $value;
        };
    }
}
