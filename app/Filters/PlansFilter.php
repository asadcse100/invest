<?php


namespace App\Filters;


use App\Filters\QueryFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PlansFilter extends QueryFilters
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
        if (blank($value)) return $this->builder;
        return $this->builder->where('ivx', get_inv($value))
                ->orWhere('user_id', get_uid($value));
        
    }

    /**
     * @param $value
     * @return Builder
     * @version 1.0.0
     * @since 1.0
     */
    public function statusFilter($value)
    {
        if (!blank($value) && $value !== 'any') {
            return $this->builder->where('status', $value);
        } else {
            return $this->builder;
        }
    }

    /**
     * @param $value
     * @return Builder
     * @version 1.0.0
     * @since 1.1.3
     */
    public function userFilter($value)
    {
        if (blank($value)) return $this->builder;
        return $this->builder->where('user_id', get_uid($value));
    }
}
