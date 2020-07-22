<?php


namespace Socotoly\Filterable\Support;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Request
{

    /**
     * @var Collection
     */
    private $query;

    private $originalQuery;

    public function __construct(string $originalQuery)
    {
        $this->query = collect();
        $this->query->put('undefined', collect());

        $this->originalQuery = strtolower($originalQuery);

        $pos = strpos($this->originalQuery, 'for=');

        if ($pos !== false)
        {
            $query = substr($this->originalQuery, 0, $pos);

            $this->parseQueryStrings($query, $this->query['undefined']);
        }else{
            $this->parseQueryStrings($this->originalQuery, $this->query['undefined']);
        }

        $this->parseForQueryStrings($this->originalQuery);
    }

    private function parseForQueryStrings(string $queryString): void
    {
        $pos = strpos($queryString, 'for=');

        if ($pos !== false)
        {
            $forQuery = substr($queryString, $pos);
            $pos = strpos($forQuery, '&for=');
            if ($pos !== false)
            {
                $leftQuery = substr($forQuery, $pos);
                $this->parseForQueryStrings($leftQuery);

                $forQuery = substr($forQuery, 0, $pos);
            }
            $outForQuery = collect();
            $this->parseQueryStrings($forQuery, $outForQuery);

            if ($outForQuery->has('for'))
            {
                $this->query->put($outForQuery['for'], $outForQuery->except('for'));
            }
        }
    }

    private function parseQueryStrings(string $queryString, Collection &$queryArray): void
    {
        parse_str($queryString, $query);

        foreach ($query as $key => $val)
        {
            if ($queryArray->has($key))
            {
                dd($key);
            }
            $queryArray->has($key) ? $queryArray[$key] += $val : $queryArray->put($key, $val);
        }
    }

    public function get(string $key, Model $model): string
    {
        if ($model && $this->query->has($modelName = Helpers::classToLowerCase($model)))
        {
            if ($this->checkKey($key, $modelName))
                return $this->query[$modelName][$key];
        }

        if ($this->checkKey($key, 'undefined'))
            return $this->query['undefined'][$key];

        return "";
    }

    /**
     * @param string $key key to search for
     * @param Model $model
     * @return bool
     */
    public function has(string $key, Model $model): bool
    {
        if ($model && $this->query->has($modelName = Helpers::classToLowerCase($model)))
        {
            if ($this->checkKey($key, $modelName))
                return true;
        }

        return $this->checkKey($key, 'undefined');
    }

    private function checkKey(string $key, string $queryKey): bool
    {
        if ($this->query->get($queryKey)->has($key))
            return true;

        return false;
    }

    public function keys(): array
    {

    }

    public function allFilters(): Collection
    {
        return $this->query;
        $filters = collect();

        foreach ($this->query as $key => $val)
            $filters->put($key, $val);

        return $filters;
    }

}
