import * as React from "react";
import {
    Bot,
    Command,
    DollarSign,
    File,
    FileX2,
    Home,
    Inbox,
    LifeBuoy,
    Notebook,
    Send,
    Settings2,
    SquareTerminal,
    User2,
} from "lucide-react";

import { NavSecondary } from "@/components/nav-secondary";
import { NavUser } from "@/components/nav-user";
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarGroup,
    SidebarGroupContent,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from "@/components/ui/sidebar";
import { Link, usePage } from "@inertiajs/react";
import DynamicIcon, { IconProps } from "./dynamic-icon";

export function AppSidebar({ ...props }: React.ComponentProps<typeof Sidebar>) {
    const menu = usePage().props?.menu;

    // const items = [
    //     {
    //         title: "Dashboard",
    //         url: route("dashboard"),
    //         isActive: route().current("dashboard"),
    //         icon: Home,
    //     },
    //     {
    //         title: "Customers",
    //         url: route("customers.index"),
    //         icon: User2,
    //         isActive: route().current()?.includes("customers"),
    //     },
    //     {
    //         title: "Invoices",
    //         url: "invoices",
    //         icon: FileX2,
    //     },
    //     {
    //         title: "Credit Notes",
    //         url: "credits",
    //         icon: Notebook,
    //     },
    //     {
    //         title: "Instant Payment",
    //         url: "instant-payment",
    //         icon: DollarSign,
    //     },
    //     {
    //         title: "Aging Report",
    //         url: "reports",
    //         icon: File,
    //     },
    // ];

    const itemsSecondary = [
        {
            title: "Settings",
            url: "#",
            icon: Settings2,
        },
    ];

    return (
        <Sidebar variant="inset" {...props}>
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <a href="#">
                                <div className="flex aspect-square size-8 items-center justify-center rounded-lg bg-sidebar-primary text-sidebar-primary-foreground">
                                    <Command className="size-4" />
                                </div>
                                <div className="grid flex-1 text-left text-sm leading-tight">
                                    <span className="truncate font-semibold">
                                        AR Module
                                    </span>
                                    <span className="truncate text-xs">
                                        Malaysia
                                    </span>
                                </div>
                            </a>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>
            <SidebarContent className="flex justify-between">
                <SidebarGroup>
                    <SidebarGroupContent>
                        <SidebarMenu>
                            {menu.map((item) => (
                                <SidebarMenuItem key={item.title}>
                                    <SidebarMenuButton
                                        asChild
                                        isActive={route()
                                            .current()
                                            ?.includes(item.name)}
                                    >
                                        <Link
                                            href={item.link}
                                            prefetch={
                                                !route()
                                                    .current()
                                                    ?.includes(item.name)
                                            }
                                            cacheFor="5m"
                                        >
                                            <DynamicIcon name={item.icon} />
                                            {/* <item.icon /> */}
                                            <span>{item.title}</span>
                                        </Link>
                                    </SidebarMenuButton>
                                </SidebarMenuItem>
                            ))}
                        </SidebarMenu>
                    </SidebarGroupContent>
                </SidebarGroup>
                <NavSecondary items={itemsSecondary} />
            </SidebarContent>
            <SidebarFooter>
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
