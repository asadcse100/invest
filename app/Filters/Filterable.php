<?php

namespace App\Filters;

trait Filterable
{
    /**
     * @param $query
     * @param QueryFilters $filters
     * @return \Illuminate\Database\Eloquent\Builder|mixed
     */
    public static function scopeFilter($query, QueryFilters $filters)
    {
        return $filters->apply($query);
    }
}
