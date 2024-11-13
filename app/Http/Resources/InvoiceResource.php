<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reference_no' => $this->reference_no,
            'issue_at' => $this->issue_at,
            'due_at' => $this->due_at,
            'status' => $this->status,
            'subscription_fee' => $this->convertToHumanReadable($this->subscription_fee),
            'charge_fee' => $this->convertToHumanReadable($this->charge_fee),
            'total_fee' => $this->convertToHumanReadable($this->subscription_fee + $this->charge_fee),
            'credit_paid' => $this->convertToHumanReadable($this->credit_paid),
            'over_paid' => $this->convertToHumanReadable($this->over_paid),
            'unresolved' => $this->unresolved,
            'unresolved_amount' => $this->convertToHumanReadable($this->unresolved_amount),
            'paid_amount' => $this->convertToHumanReadable($this->payments_sum_invoice_paymentamount ?? 0),
            'created_at' => $this->created_at,
            'customer' => $this->whenLoaded('customer'),
            'order' => $this->whenLoaded('order'),
            'charges' => ChargeResource::collection($this->whenLoaded('charges')),

        ];
    }
}
