import { useQuery as useQueryTanstack } from '@tanstack/vue-query';
import { fetchTransactionsPaginated } from '@/inertia/Modules/Transactions/Services/TransactionService.js';
import { fetchTransactionsPaginatedQueryKey } from "@/inertia/Constans/QueryKeys.js";

const useQuery = () => {
    const useFetchTransactionsPaginated = () =>
        useQueryTanstack({
            queryKey: fetchTransactionsPaginatedQueryKey(),
            queryFn: () => fetchTransactionsPaginated({ limit: 999 }),
            placeholderData: [],
        });
    return { useFetchTransactionsPaginated };
};
export default useQuery;
