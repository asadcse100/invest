<?php
namespace App\Filters;

use App\Filters\QueryFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class LedgerFilter extends QueryFilters
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
        return $this->builder->where('ivx', get_tnx($value, 'ivx'))
                ->orWhere('user_id', get_uid($value));
    }

    /**
     * @param $value
     * @return Builder
     * @version 1.0.0
     * @since 1.0
     */
    public function typeFilter($value)
    {
        if (!blank($value) && $value !== 'any') {
            return $this->builder->where('type', $value);
        } else {
            return $this->builder;
        }
    }

    /**
     * @param $value
     * @return Builder
     * @version 1.0.0
     * @since 1.0
     */
    public function sourceFilter($value)
    {
        if (!blank($value) && $value !== 'any') {
            return $this->builder->where('source', $value);
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
