<?php

namespace App\Http\Controllers;

use App\Actions\Customers\CustomerTable;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CustomerController extends Controller
{
    public function index(Request $request, CustomerTable $action): Response
    {
        return Inertia::render('Customers/Index', [
            'table' => Inertia::defer(function () use ($action) {
                return CustomerResource::collection($action->handle($request->limit ?? 10));
            }),
        ]);
    }

    public function show(Customer $customer)
    {

        return Inertia::render('Customers/Show', [
            'customer' => new CustomerResource($customer),
        ]);
    }

    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'uuid' => 'required|string|max:12',
            'phone' => 'required',
        ]);

        $customer->update($data);

        return redirect()->route('customers.show', $customer);
    }

    public function invoices(Customer $customer)
    {
        return Inertia::render('Customers/Invoice', [
            'customer' => new CustomerResource($customer),
        ]);
    }
}
