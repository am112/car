<?php

namespace App\Console\Commands;

use App\Features\Automation\Services\CreateCharges;
use App\Features\Automation\Services\CreateInvoices;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SimulateInvoiceCreation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'simulate:create-invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simulation command for creating invoices';

    /**
     * Execute the console command.
     */
    public function handle(CreateCharges $lateChargesAction, CreateInvoices $invoicesAction): void
    {
        Order::factory()->count(1)->create();

        $firstOrder = Order::query()
            ->orderBy('contract_at', 'ASC')
            ->first();

        $runningAt = Carbon::parse($firstOrder->contract_at);
        while ($runningAt->lte(today())) {
            $lateChargesAction->handle($runningAt);
            $invoicesAction->handle($runningAt);
            $runningAt->addDay();
            usleep(100);
        }

    }
}
