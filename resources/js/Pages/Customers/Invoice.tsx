import {
    BreadcrumbItem,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from "@/components/ui/breadcrumb";
import { Separator } from "@/components/ui/separator";
import CustomerMenu from "@/features/customers/components/customer-menu";
import DashboardLayout from "@/layouts/dashboard-layout";
import { Customer } from "@/types/customer";
import { Head, Link } from "@inertiajs/react";

export default function Page({ customer }: { customer: Customer }) {
    return (
        <DashboardLayout
            header={
                <BreadcrumbList>
                    <BreadcrumbItem className="hidden md:block">
                        <Link href={route("dashboard")}>Dashboard</Link>
                    </BreadcrumbItem>
                    <BreadcrumbSeparator className="hidden md:block" />
                    <BreadcrumbItem className="hidden md:block">
                        <Link href={route("customers.index")}>Customers</Link>
                    </BreadcrumbItem>
                    <BreadcrumbSeparator className="hidden md:block" />
                    <BreadcrumbItem>
                        <Link href={route("customers.show", customer.id)}>
                            {customer.name}
                        </Link>
                    </BreadcrumbItem>
                    <BreadcrumbSeparator className="hidden md:block" />
                    <BreadcrumbItem>
                        <BreadcrumbPage>Invoices</BreadcrumbPage>
                    </BreadcrumbItem>
                </BreadcrumbList>
            }
        >
            <Head title="Customers Detail" />
            <div className="flex flex-col gap-2">
                <CustomerMenu
                    title={customer.uuid}
                    id={customer.id}
                    activeName="Invoices"
                />

                <Separator className="my-2" />

                <div className="grid gap-6">
                    <div> invoices table here... </div>
                </div>
            </div>
        </DashboardLayout>
    );
}
