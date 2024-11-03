import dynamicIconImports from "lucide-react/dynamicIconImports";

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User;
    };
    menu: Menu[];
};

type Menu = {
    title: string;
    link: string;
    name: string;
    icon: keyof typeof dynamicIconImports;
};

export {
    Menu,
    PageProps,
    Datatable,
    Meta,
    User,
    Customer,
    Order,
    Address,
    Invoice,
};
