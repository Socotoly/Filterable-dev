<?php


namespace Socotoly\Filterable\Filters;


use Socotoly\Filterable\Contracts\Filter;

class WithFilter extends Filter
{

    public function apply($queryValue): void
    {
        if (is_array($queryValue))
        {
            foreach ($queryValue as $val)
            {
                if ($this->relationExists($val))
                    $this->builder->with($val);
            }
        }else{
            if ($this->relationExists($queryValue))
                $this->builder->with($queryValue);
        }
    }

    private function relationExists($query): bool
    {
        return !! method_exists($this->model, $query)
            && ($method = new \ReflectionMethod($this->model, $query))->isPublic()
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
        $source = implode('', array_slice($source, 0, count($source)));
        $source = preg_split("/".PHP_EOL."/", $source);

        $body = '';
        for($i=$start_line; $i<$end_line; $i++)
            $body.="{$source[$i]}\n";

        foreach ($relations as $relation)
        {
            if (strpos($body, $relation) !== false)
                return true;
        }

        return false;
    }
}
