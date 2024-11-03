<?php

namespace App\Actions\Customers;

use App\Models\Customer;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CustomerTable
{
    public function handle(int $limit): LengthAwarePaginator
    {
        return QueryBuilder::for(Customer::class)
            ->withSum(['invoices' => function ($builder) {
                $builder->where('unresolved', true);
            }], 'unresolved_amount')
            ->allowedSorts('uuid', 'name', 'email', 'phone', 'tenure', 'contract_at', 'subscription_fee')
            ->allowedFilters(
                AllowedFilter::callback('search', function (Builder $query, $value): void {
                    $this->globalSearch($query, $value);
                })
            )
            ->paginate($limit)
            ->withQueryString();
    }

    private function globalSearch(Builder $builder, $value): Builder
    {
        return $builder
            ->where('name', 'like', '%' . $value . '%')
            ->orWhere('tenure', $value)
            ->orWhere('subscription_fee', str_replace(['.', ',', ' '], '', $value))
            ->orWhere('contract_at', 'like', '%' . $value . '%');
    }
}
