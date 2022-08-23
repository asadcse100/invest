<?php


namespace App\Filters;


class UserFilter extends QueryFilters
{

    /**
     * @param $value
     * @return \Illuminate\Database\Eloquent\Builder
     * @version 1.0.0
     * @since 1.0
     */
    public function statusFilter($value)
    {
        if ($value != 'any') {
            return $this->builder->where('status', $value);
        } else {
            return $this->builder;
        }
    }

    /**
     * @param $value
     * @return \Illuminate\Database\Eloquent\Builder
     * @version 1.0.0
     * @since 1.0
     */
    public function queryFilter($value)
    {
        if(blank($value)) return $this->builder;
        return $this->builder->whereRaw("LOWER(name) LIKE '%" . strtolower($value) . "%'")
            ->orWhere('email', $value)->orWhere('email', 'like', '%' . $value . '%')->orWhere('id', get_uid($value));

    }

    /**
     * @param $value
     * @return \Illuminate\Database\Eloquent\Builder
     * @version 1.0.0
     * @since 1.0
     */
    public function roleFilter($value)
    {
        if ($value != 'any') {
            return $this->builder->where('role', $value);
        } else {
            return $this->builder;
        }
    }

    /**
     * @param $value
     * @return \Illuminate\Database\Eloquent\Builder
     * @version 1.0.0
     * @since 1.1.3
     */
    public function regMethodFilter($value)
    {
        if ($value != 'any') {
            return $this->builder->with('user_metas')->has('user_metas', '>=', 1, 'and', function ($query) use ($value)
            {
                return $query->where('meta_key', 'registration_method')->where('meta_value', $value);
            });
        } else {
            return $this->builder;
        }
    }

    /**
     * @param $value
     * @return \Illuminate\Database\Eloquent\Builder
     * @version 1.0.0
     * @since 1.1.3
     */
    public function emailVerifiedFilter($value)
    {
        if ($value == true) {
            return $this->builder->with('user_metas')->has('user_metas', '>=', 1, 'and', function ($query)
            {
                return $query->where('meta_key', 'email_verified')->whereNotNull('meta_value');
            });
        } else {
            return $this->builder;
        }
    }

    /**
     * @param $value
     * @return \Illuminate\Database\Eloquent\Builder
     * @version 1.0.0
     * @since 1.1.3
     */
    public function hasBalanceFilter($value)
    {
        if ($value == true) {
            return $this->builder->with('balances')->has('balances', '>=', 1, 'and', function ($query)
            {
                return $query->where('balance', '=', 'main_wallet')->where('amount', '>', 0);
            });
        } else {
            return $this->builder;
        }
    }

    /**
     * @param $value
     * @return \Illuminate\Database\Eloquent\Builder
     * @version 1.0.0
     * @since 1.1.3
     */
    public function referralJoinFilter($value)
    {
        if ($value == true) {
            return $this->builder->whereNotNull('refer');
        } else {
            return $this->builder;
        }
    }
}
