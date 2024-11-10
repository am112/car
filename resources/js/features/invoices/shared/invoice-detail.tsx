import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableFooter,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table";
import CustomerOrder from "@/features/customers/components/customer-order";
import { Charge } from "@/types/charge";
import { Invoice } from "@/types/invoice";

type Props = {
    invoice: Invoice;
};

export default function InvoiceDetail({ invoice }: Props) {
    return (
        <div className="grid gap-4">
            <div className="grid gap-4 grid-cols-2">
                <Card className="flex-1">
                    <CardHeader>
                        <CardTitle>Invoice Detail</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div className="grid gap-4">
                            <div className="grid gap-4 grid-cols-2">
                                <div className="grid gap-2">
                                    <Label htmlFor="reference_no">
                                        Reference No
                                    </Label>
                                    <Input
                                        id="reference_no"
                                        type="text"
                                        value={invoice.reference_no}
                                        disabled
                                        className="mt-1 block w-full"
                                    />
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="contract_at">
                                        Issue Date
                                    </Label>
                                    <Input
                                        id="contract_at"
                                        type="text"
                                        disabled
                                        value={invoice.issue_at}
                                        className="mt-1 block w-full"
                                    />
                                </div>
                            </div>

                            <div className="grid gap-4 grid-cols-2">
                                <div className="grid gap-2">
                                    <Label htmlFor="tenure">
                                        Monthly Fee (RM)
                                    </Label>
                                    <Input
                                        id="tenure"
                                        type="text"
                                        disabled
                                        value={invoice.subscription_fee}
                                        className="mt-1 block w-full"
                                    />
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="subscription_fee">
                                        Charge Fee (RM)
                                    </Label>
                                    <Input
                                        id="subscription_fee"
                                        type="text"
                                        value={invoice.charge_fee}
                                        disabled
                                        className="mt-1 block w-full"
                                    />
                                </div>
                            </div>

                            <div className="grid gap-4 grid-cols-2">
                                <div className="grid gap-2">
                                    <Label htmlFor="tenure">
                                        Credit Applied (RM)
                                    </Label>
                                    <Input
                                        id="tenure"
                                        type="text"
                                        disabled
                                        value={invoice.credit_paid}
                                        className="mt-1 block w-full"
                                    />
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="subscription_fee">
                                        Over Paid (RM)
                                    </Label>
                                    <Input
                                        id="subscription_fee"
                                        type="text"
                                        value={invoice.over_paid}
                                        disabled
                                        className="mt-1 block w-full"
                                    />
                                </div>
                            </div>

                            <div className="grid gap-4 grid-cols-2">
                                <div className="grid gap-2">
                                    <Label htmlFor="tenure">Status</Label>
                                    <Input
                                        id="tenure"
                                        type="text"
                                        disabled
                                        value={invoice.status}
                                        className="mt-1 block w-full"
                                    />
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="subscription_fee">
                                        Unresolved Amount (RM)
                                    </Label>
                                    <Input
                                        id="subscription_fee"
                                        type="text"
                                        value={invoice.unresolved_amount}
                                        disabled
                                        className="mt-1 block w-full"
                                    />
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
                <CustomerOrder order={invoice.order} />
            </div>

            <div className="grid gap-4 grid-cols-2">
                {invoice.charges && invoice.charges.length > 0 && (
                    <Card className="flex-1">
                        <CardHeader>
                            <CardTitle>List of Charges</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div className="grid gap-4">
                                <Table>
                                    <TableHeader>
                                        <TableRow>
                                            <TableHead>Reference No</TableHead>
                                            <TableHead>Charge At</TableHead>
                                            <TableHead className="text-right">
                                                Amount
                                            </TableHead>
                                        </TableRow>
                                    </TableHeader>
                                    <TableBody>
                                        {invoice.charges.map(
                                            (charge: Charge) => (
                                                <TableRow key={charge.id}>
                                                    <TableCell className="font-medium">
                                                        {charge.reference_no}
                                                    </TableCell>
                                                    <TableCell>
                                                        {charge.charged_at}
                                                    </TableCell>
                                                    <TableCell className="text-right">
                                                        {charge.amount}
                                                    </TableCell>
                                                </TableRow>
                                            ),
                                        )}
                                    </TableBody>
                                </Table>
                            </div>
                        </CardContent>
                    </Card>
                )}
            </div>
        </div>
    );
}
