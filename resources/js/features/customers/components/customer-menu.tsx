import {
    SidebarContent,
    SidebarGroup,
    SidebarGroupContent,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from "@/components/ui/sidebar";
import {
    NavigationMenu,
    NavigationMenuItem,
    NavigationMenuLink,
    NavigationMenuList,
    navigationMenuTriggerStyle,
} from "@/components/ui/navigation-menu";
import { Link } from "@inertiajs/react";
import { Calendar, Inbox, Search, Settings, User } from "lucide-react";

type PropsType = {
    title: string;
    id: number;
    activeName?: string;
};

export default function CustomerMenu({
    title,
    id,
    activeName = "Summary",
}: PropsType) {
    const items = [
        {
            title: "Summary",
            url: route("customers.show", id),
            icon: User,
        },
        {
            title: "Invoices",
            url: route("customers.invoices", id),
            icon: Inbox,
        },
        {
            title: "Payments",
            url: "#",
            icon: Calendar,
        },
        {
            title: "Statement Of Account",
            url: "#",
            icon: Search,
        },
        {
            title: "Aging Report",
            url: "#",
            icon: Settings,
        },
    ];

    return (
        <>
            <NavigationMenu>
                <NavigationMenuList>
                    {items.map((item) => (
                        <NavigationMenuItem key={item.title}>
                            <Link href={item.url} as="button">
                                <NavigationMenuLink
                                    active={item.title === activeName}
                                    className={navigationMenuTriggerStyle()}
                                >
                                    <item.icon className="h-4 w-4 mr-2" />
                                    {item.title}
                                </NavigationMenuLink>
                            </Link>
                        </NavigationMenuItem>
                    ))}
                </NavigationMenuList>
            </NavigationMenu>
        </>
    );
}
