import {
    BreadcrumbItem,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from "@/components/ui/breadcrumb";
import DashboardLayout from "@/layouts/DashboardLayout";
import { Deferred, Head, Link } from "@inertiajs/react";
import { Datatable } from "@/types/datatable";
import { DataTable } from "@/components/datatables/data-table";
import DataTableLoading from "@/components/datatables/datatable-loading";
import CustomerFilter from "@/features/customers/tables/customer-filter";
import { customerColumns } from "@/features/customers/tables/customer-columns";

export default function Page({ table }: { table: Datatable }) {
    return (
        <DashboardLayout
            header={
                <BreadcrumbList>
                    <BreadcrumbItem className="hidden md:block">
                        <Link href={route("dashboard")}>Dashboard</Link>
                    </BreadcrumbItem>
                    <BreadcrumbSeparator className="hidden md:block" />
                    <BreadcrumbItem>
                        <BreadcrumbPage>Customers</BreadcrumbPage>
                    </BreadcrumbItem>
                </BreadcrumbList>
            }
        >
            <Head title="Customers" />
            <div className="flex flex-col gap-4">
                <CustomerFilter />
                <Deferred data="table" fallback={<DataTableLoading />}>
                    {table && (
                        <DataTable
                            columns={customerColumns(table.meta.current_page)}
                            data={table.data}
                            paginator={table}
                        />
                    )}
                </Deferred>
            </div>
        </DashboardLayout>
    );
}
