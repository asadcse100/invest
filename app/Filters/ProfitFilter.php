<?php


namespace App\Filters;


use App\Filters\QueryFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProfitFilter extends QueryFilters
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
            $this->builder->leftJoin('iv_invests', 'iv_profits.invest_id', '=', 'iv_invests.id')
                ->where('iv_invests.ivx', $value);
        } else {
            return $this->builder;
        }
    }
}
