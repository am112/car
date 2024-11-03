import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Customer } from "@/types/customer";

export default function CustomerOrder({ customer }: { customer: Customer }) {
    return (
        <Card className="flex-1">
            <CardHeader>
                <CardTitle>Order Summary</CardTitle>
            </CardHeader>
            <CardContent>
                <form className="grid gap-4">
                    <div className="grid gap-4 grid-cols-2">
                        <div className="grid gap-2">
                            <Label htmlFor="tenure">Tenure</Label>
                            <Input
                                id="tenure"
                                type="text"
                                disabled
                                value={`${customer.tenure} Months`}
                                className="mt-1 block w-full"
                            />
                        </div>
                        <div className="grid gap-2">
                            <Label htmlFor="subscription_fee">
                                Monthly Fee (RM)
                            </Label>
                            <Input
                                id="subscription_fee"
                                type="text"
                                value={`RM ${customer.subscription_fee}`}
                                disabled
                                className="mt-1 block w-full"
                            />
                        </div>
                    </div>

                    <div className="grid gap-4 grid-cols-2">
                        <div className="grid gap-2">
                            <Label htmlFor="contract_at">Contract Date</Label>
                            <Input
                                id="contract_at"
                                type="text"
                                disabled
                                value={customer.contract_at}
                                className="mt-1 block w-full"
                            />
                        </div>
                        <div className="grid gap-2">
                            <Label htmlFor="payment_gateway">
                                Payment Provider
                            </Label>
                            <Input
                                id="payment_gateway"
                                type="text"
                                value={customer.payment_gateway}
                                disabled
                                className="mt-1 block w-full"
                            />
                        </div>
                    </div>

                    <div className="grid gap-4 grid-cols-2">
                        <div className="grid gap-2">
                            <Label htmlFor="payment_reference">
                                Payment Reference
                            </Label>
                            <Input
                                id="payment_reference"
                                type="text"
                                disabled
                                value={customer.payment_reference}
                                className="mt-1 block w-full"
                            />
                        </div>
                    </div>
                </form>
            </CardContent>
        </Card>
    );
}
