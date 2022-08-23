<?php


namespace App\Filters;


use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class PageFilter extends QueryFilters
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * @param $value
     * @return Builder
     * @version 1.0.0
     * @since 1.0
     */
    public function queryFilter($value)
    {
        if (!blank($value)) {
            return $this->builder->whereRaw("LOWER(name) LIKE '%" . strtolower($value) . "%'");
        } else {
            return $this->builder;
        }
    }
}
