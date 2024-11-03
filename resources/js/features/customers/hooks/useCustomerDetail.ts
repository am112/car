import { useToast } from "@/hooks/use-toast";
import { Customer } from "@/types/customer";
import { useForm } from "@inertiajs/react";
import { FormEventHandler, useEffect } from "react";

type PropsType = {
    customer: Customer;
};

const useCustomerDetail = ({ customer }: PropsType) => {
    const { toast } = useToast();
    const { data, setData, patch, processing, errors } = useForm({
        name: customer.name,
        email: customer.email,
        uuid: customer.uuid,
        phone: customer.phone,
    });

    const onSubmit: FormEventHandler = (e) => {
        e.preventDefault();
        patch(route("customers.update", customer.id), {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                toast({
                    title: "Customer Updated",
                    description: "Customer updated successfully",
                });
            },
        });
    };

    return {
        data,
        setData,
        onSubmit,
        processing,
        errors,
    };
};

export default useCustomerDetail;
