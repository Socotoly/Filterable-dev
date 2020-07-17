<?php


namespace Socotoly\Filterable\Support;


use Illuminate\Database\Eloquent\Model;

class Request
{

    private $query = ['undefined' => [], ];

    private $originalQuery;

    public function __construct(string $originalQuery)
    {
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
            $outForQuery = [];
            $this->parseQueryStrings($forQuery, $outForQuery);

            if (array_key_exists('for', $outForQuery))
            {
                $this->query[$outForQuery['for']] = array_slice($outForQuery, 1);
            }
        }
    }

    private function parseQueryStrings(string $queryString, array &$queryArray): void
    {
        parse_str($queryString, $query);

        foreach ($query as $key => $val)
        {
            $queryArray += [$key => $val];
        }
    }

    public function get(string $key, Model $model): string
    {
        if ($model && array_key_exists($modelName = strtolower(class_basename($model)), $this->query))
        {
            if (array_key_exists($key, $this->query[$modelName]))
            {
                return $this->query[$modelName][$key];
            }
        }

        if (array_key_exists($key, $this->query['undefined']))
        {
            return $this->query['undefined'][$key];
        }

        return "";
    }

    public function has(string $key, Model $model = null): bool
    {
        if ($model && array_key_exists($modelName = strtolower(class_basename($model)), $this->query))
        {
            if (array_key_exists($key, $this->query[$modelName]))
            {
                return true;
            }
        }

        if (array_key_exists($key, $this->query['undefined']))
        {
            return true;
        }

        return false;
    }

    public function keys(): array
    {

    }

}
