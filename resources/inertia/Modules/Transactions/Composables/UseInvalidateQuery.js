import { useQueryClient } from '@tanstack/vue-query';
const useInvalidateQuery = () => {
    const qc = useQueryClient();
    const useInvalidateFetchTransactionsPaginated = () => qc.invalidateQueries({ queryKey: ['fetchTransactionsPaginated'] });
    return { useInvalidateFetchTransactionsPaginated };
};
export default useInvalidateQuery;
