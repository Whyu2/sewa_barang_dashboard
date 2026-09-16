import { useQuery as useQueryTanstack } from '@tanstack/vue-query';
import { fetchTransactionsPaginated } from '@/inertia/Modules/Transactions/Services/TransactionService.js';

const useQuery = () => {
    const useFetchTransactionsPaginated = () =>
        useQueryTanstack({
            queryKey: ['fetchTransactionsPaginated'],
            queryFn: () => fetchTransactionsPaginated({ limit: 999 }),
            placeholderData: [],
        });
    return { useFetchTransactionsPaginated };
};
export default useQuery;
