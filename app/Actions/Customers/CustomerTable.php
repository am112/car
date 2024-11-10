<?php

namespace App\Actions\Customers;

use App\Actions\Contracts\WithActionTable;
use App\Models\Customer;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CustomerTable implements WithActionTable
{
    /**
     * Summary of allowedSorts
     *
     * @var array
     */
    private $allowedSorts = ['uuid', 'name', 'email', 'phone', 'tenure', 'contract_at', 'subscription_fee'];

    /**
     * Summary of handle
     *
     * @param  array<int|string, mixed>|null  $payload
     */
    public function handle(?array $payload): LengthAwarePaginator
    {
        return QueryBuilder::for(Customer::class)
            ->withSum(['invoices' => function ($builder): void {
                $builder->where('unresolved', true);
            }], 'unresolved_amount')
            ->allowedSorts($this->allowedSorts)
            ->allowedFilters($this->allowedFilter())
            ->paginate($payload['limit'] ?? 10)
            ->withQueryString();
    }

    /**
     * Summary of allowedFilter
     */
    private function allowedFilter(): AllowedFilter
    {
        return AllowedFilter::callback('search', function (Builder $query, $value): void {
            $query
                ->where('name', 'like', '%' . $value . '%')
                ->orWhere('tenure', $value)
                ->orWhere('subscription_fee', str_replace(['.', ',', ' '], '', $value))
                ->orWhere('contract_at', 'like', '%' . $value . '%');
        });
    }
}
