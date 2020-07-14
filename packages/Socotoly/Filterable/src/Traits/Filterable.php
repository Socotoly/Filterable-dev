<?php


namespace Socotoly\Filterable\Traits;


use Socotoly\Filterable\Scopes\FilterScope;

trait Filterable
{
    private static $filters = [];

    protected static function booted()
    {
        static::addGlobalScope(new FilterScope(static::$filters));
    }
}
