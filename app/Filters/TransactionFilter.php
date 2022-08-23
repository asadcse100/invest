<?php

namespace App\Filters;

use App\Enums\TransactionStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class TransactionFilter extends QueryFilters
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
    public function statusFilter($value)
    {
        if ($value == 'process') {
            return $this->builder->whereIn('status', [TransactionStatus::PENDING, TransactionStatus::ONHOLD, TransactionStatus::CONFIRMED]);
        }
        if ($value != 'any') {    
            $query = $this->builder->where('status', $value);
            $orders = $query->getQuery()->orders;
            
            if ($value == 'cancelled' && !in_array('created_at', Arr::pluck($orders, 'column'))) {
                $direction = Arr::pluck($orders, 'direction')[0];
                return $query->orderBy('created_at', $direction);
            }
            
            return $query;
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
    public function typeFilter($value)
    {
        if ($value != 'any') {
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
    public function queryFilter($value)
    {
        if(blank($value)) return $this->builder;
        return $this->builder->where('tnx', get_tnx($value, 'tnx'))
                ->orWhere('user_id', get_uid($value));
    }

    /**
     * @param $value
     * @return Builder
     * @version 1.0.0
     * @since 1.0
     */
    public function listTypeFilter($value)
    {
        return $this->builder->where('type', $value);
    }

    /**
     * @param $value
     * @return Builder
     * @version 1.0.0
     * @since 1.0
     */
    public function viewFilter($value)
    {
        if(!empty($value) && $value === 'scheduled'){
            return $this->builder->whereIn('status', [
                TransactionStatus::PENDING,
                TransactionStatus::CONFIRMED,
                TransactionStatus::ONHOLD
            ]);
        }
        return $this->builder;
    }

    /**
     * @param $value
     * @return Builder
     * @version 1.0.0
     * @since 1.0
     */
    public function dateFilter($value)
    {
        if (!empty(Arr::get($value, 'from'))) {
            $from = Carbon::parse(Arr::get($value, 'from'))->toDateString();
            $this->builder->whereDate('created_at', '>=', $from);
        }

        if (!empty(Arr::get($value,'to'))) {
            $to = Carbon::parse(Arr::get($value,'to'))->toDateString();
            $this->builder->whereDate('created_at', '<=', $to);
        }

        return $this->builder;
    }

    public function currencyFilter($value)
    {
        if(!empty($value) && $value != 'any') {
            $this->builder->where('tnx_currency', $value);
        }
        return $this->builder;
    }

    public function methodFilter($value)
    {
        if(!empty($value) && $value != 'any') {
            $this->builder->where('tnx_method', $value);
        }
        return $this->builder;
    }
}
