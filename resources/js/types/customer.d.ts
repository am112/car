export type Customer = {
    id: number;
    uuid: string;
    name: string;
    email: string;
    phone: string;
    active: boolean;
    created_at: string;
    updated_at: string;
    subscription_fee: string;
    tenure: number;
    contract_at: string;
    payment_gateway: string;
    payment_reference: string;
    unresolved_invoices_amount: string;
};
