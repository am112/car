import DataTableIndex from "@/components/datatables/datatable-index";
import DataTableSortIcon from "@/components/datatables/datatable-sorticon";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { Customer } from "@/types";
import { Link } from "@inertiajs/react";
import { ColumnDef } from "@tanstack/react-table";

export const customerColumns = (page: number): ColumnDef<Customer>[] => {
    return [
        {
            header: "No",
            cell: ({ row, table }) => {
                return DataTableIndex(page, table, row);
            },
        },
        {
            accessorKey: "uuid",
            header: ({ column }) => {
                return (
                    <>
                        <div className="flex items-center gap-0">
                            ID
                            <Button
                                variant="ghost"
                                onClick={() => {
                                    return column.toggleSorting(
                                        column.getIsSorted() === "asc",
                                    );
                                }}
                            >
                                <DataTableSortIcon
                                    sorted={column.getIsSorted()}
                                />
                            </Button>
                        </div>
                    </>
                );
            },
        },
        {
            accessorKey: "name",
            header: ({ column }) => {
                return (
                    <>
                        <div className="flex items-center gap-0">
                            Name
                            <Button
                                variant="ghost"
                                onClick={() => {
                                    return column.toggleSorting(
                                        column.getIsSorted() === "asc",
                                    );
                                }}
                            >
                                <DataTableSortIcon
                                    sorted={column.getIsSorted()}
                                />
                            </Button>
                        </div>
                    </>
                );
            },
            cell: ({ row }) => {
                return (
                    <Link href={route("customers.show", row.original.id)}>
                        {row.getValue("name")}
                    </Link>
                );
            },
        },

        {
            accessorKey: "subscription_fee",
            header: ({ column }) => {
                return (
                    <>
                        <div className="flex items-center gap-0">
                            Monthly Fee
                            <Button
                                variant="ghost"
                                onClick={() => {
                                    return column.toggleSorting(
                                        column.getIsSorted() === "asc",
                                    );
                                }}
                            >
                                <DataTableSortIcon
                                    sorted={column.getIsSorted()}
                                />
                            </Button>
                        </div>
                    </>
                );
            },
        },
        {
            accessorKey: "tenure",
            header: ({ column }) => {
                return (
                    <>
                        <div className="flex items-center gap-0">
                            Tenure
                            <Button
                                variant="ghost"
                                onClick={() => {
                                    return column.toggleSorting(
                                        column.getIsSorted() === "asc",
                                    );
                                }}
                            >
                                <DataTableSortIcon
                                    sorted={column.getIsSorted()}
                                />
                            </Button>
                        </div>
                    </>
                );
            },
        },
        {
            accessorKey: "contract_at",
            header: ({ column }) => {
                return (
                    <>
                        <div className="flex items-center gap-0">
                            Contract Date
                            <Button
                                variant="ghost"
                                onClick={() => {
                                    return column.toggleSorting(
                                        column.getIsSorted() === "asc",
                                    );
                                }}
                            >
                                <DataTableSortIcon
                                    sorted={column.getIsSorted()}
                                />
                            </Button>
                        </div>
                    </>
                );
            },
        },
        {
            accessorKey: "unresolved_invoices_amount",
            header: "Unresolved Amount",
            cell: ({ row }) => {
                return (
                    <div className="flex justify-end">
                        <Badge variant="secondary" className="align-right">
                            {row.getValue("unresolved_invoices_amount")}
                        </Badge>
                    </div>
                );
            },
        },
        {
            accessorKey: "created_at",
            header: "Created At",
        },
    ];
};
