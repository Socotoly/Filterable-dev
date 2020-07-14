<?php


namespace Socotoly\Filterable\Traits;


use Illuminate\Database\Eloquent\Builder;
use Socotoly\Filterable\FilterFactory;
use Socotoly\Filterable\Scopes\FilterScope;

trait Filterable
{

    protected static function booted()
    {
        if (static::filterable())
            static::addGlobalScope(new FilterScope());
    }

    public function scopeFilter(Builder $builder): Builder
    {
        return (new FilterFactory($builder, $this, static::filters()))->apply();
    }

    protected static function filters(): array
    {
        return [];
    }

    protected static function filterable()
    {
        return true;
    }

}
