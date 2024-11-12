<?php

namespace App\Features\Automation\Services;

use App\Models\Charge;
use App\Models\Order;
use App\Utils\Helper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CreateCharges
{
    /**
     * Summary of allowedAt
     *
     * @var array
     */
    private $allowedAt = [7, 14, 21, 28];

    /**
     * Summary of amountPerCharge
     *
     * @var int
     */
    private $amountPerCharge = 1000;

    /**
     * Summary of handle
     */
    public function handle(Carbon $runningAt): void
    {
        if (! in_array($runningAt->format('d'), $this->allowedAt)) {
            return;
        }

        Order::query()
            ->withCount(['invoices' => fn (Builder $query) => $query->unresolved()])
            ->withSum(['invoices' => fn (Builder $query) => $query->unresolved()], 'unresolved_amount')
            ->whereHas('invoices', fn (Builder $query) => $query->unresolved())
            ->whereNull('completed_at')
            ->chunk(100, function (Collection $orders) use ($runningAt): void {
                $orders->map(function (Order $order) use ($runningAt): void {
                    $this->create($order, $runningAt);
                });
            });
    }

    /**
     * Summary of create
     */
    public function create(Order $order, Carbon $runningAt): void
    {
        $latestUnresolvedInvoice = $order->invoices()->unresolved()->latest()->first();

        if (! $latestUnresolvedInvoice) {
            return;
        }

        $isChargeable = Charge::isLateChargeable(
            $order->invoices_sum_unresolved_amount ?? 0,
            Carbon::parse($latestUnresolvedInvoice->issue_at),
            $runningAt,
            $order->invoices_count
        );

        if ($isChargeable) {
            $charge = Charge::create([
                'customer_id' => $order->customer_id,
                'order_id' => $order->id,
                'reference_no' => time(),
                'type' => Charge::TYPE_LATE,
                'amount' => $this->amountPerCharge,
                'charged_at' => $runningAt,
            ]);
            $charge->update([
                'reference_no' => Helper::referenceNoConvention(Charge::PREFIX, $charge->id, $runningAt),
            ]);

            info('charge created:', $charge->toArray());
        }
    }
}
