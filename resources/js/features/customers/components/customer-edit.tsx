import { Button } from "@/components/ui/button";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Customer } from "@/types/customer";
import { Loader } from "lucide-react";
import useCustomerDetail from "../hooks/useCustomerDetail";
import { Separator } from "@/components/ui/separator";

export default function CustomerEdit({ customer }: { customer: Customer }) {
    const { data, setData, onSubmit, processing, errors } = useCustomerDetail({
        customer,
    });

    return (
        <Card className="flex-1">
            <CardHeader>
                <CardTitle>Customer Summary</CardTitle>
            </CardHeader>
            <CardContent>
                <form onSubmit={onSubmit} className="grid gap-4">
                    <Separator className="my-2" />
                    <div>
                        <h4 className=" text-lg font-bold">Customer Detail</h4>
                    </div>
                    <div className="grid gap-2">
                        <Label htmlFor="name">Name as NRIC</Label>
                        <Input
                            id="name"
                            type="text"
                            name="name"
                            value={data.name}
                            className="mt-1 block w-full"
                            autoComplete="name"
                            onChange={(e) => setData("name", e.target.value)}
                        />

                        <Label className=" text-red-500">{errors.name}</Label>
                    </div>
                    <div className="grid gap-2">
                        <Label htmlFor="uuid">NRIC</Label>
                        <Input
                            id="uuid"
                            type="text"
                            name="uuid"
                            value={data.uuid}
                            className="mt-1 block w-full"
                            autoComplete="uuid"
                            onChange={(e) => setData("uuid", e.target.value)}
                        />

                        <Label className=" text-red-500">{errors.uuid}</Label>
                    </div>
                    <div className="grid gap-2">
                        <Label htmlFor="email">Email</Label>
                        <Input
                            id="email"
                            type="email"
                            name="email"
                            value={data.email}
                            className="mt-1 block w-full"
                            autoComplete="email"
                            onChange={(e) => setData("email", e.target.value)}
                        />

                        <Label className=" text-red-500">{errors.email}</Label>
                    </div>
                    <div className="grid gap-2">
                        <Label htmlFor="phone">Mobile No</Label>
                        <Input
                            id="phone"
                            type="text"
                            name="phone"
                            value={data.phone}
                            className="mt-1 block w-full"
                            autoComplete="Mobile No"
                            onChange={(e) => setData("phone", e.target.value)}
                        />

                        <Label className=" text-red-500">{errors.phone}</Label>
                    </div>

                    <div className="">
                        <Button disabled={processing} type="submit">
                            {processing && (
                                <Loader className="h-4 w-4 animate-spin" />
                            )}
                            Update
                        </Button>
                    </div>
                </form>
            </CardContent>
        </Card>
    );
}
