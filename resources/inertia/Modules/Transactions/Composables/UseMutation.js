import { useMutation as useMutationTanstack, useQueryClient } from '@tanstack/vue-query';
import { updateTransaction, deleteTransaction } from '@/inertia/Modules/Transactions/Services/TransactionService.js';
import { fetchTransactionsPaginatedQueryKey } from "@/inertia/Constans/QueryKeys.js";
import { updateTransactionQueryKey, deleteTransactionQueryKey } from "@/inertia/Constans/MutationKeys.js";

const useMutation = () => {
    const queryClient = useQueryClient();
    const invalidateFetchTransactionsPaginated = () =>
        queryClient.invalidateQueries({ queryKey: fetchTransactionsPaginatedQueryKey() });

    const useUpdateTransaction = ({ onSuccess, onError } = {}) =>
        useMutationTanstack({
            mutationKey: updateTransactionQueryKey(),
            mutationFn: ({ id, payload }) => updateTransaction(id, payload),
            onError: (error) => onError?.(error),
            onSuccess: async (data, variables, context) => {
                await invalidateFetchTransactionsPaginated();
                onSuccess?.(data, variables, context);
            },
        });
    const useDeleteTransaction = ({ onSuccess, onError } = {}) =>
        useMutationTanstack({
            mutationKey: deleteTransactionQueryKey(),
            mutationFn: ({ id }) => deleteTransaction(id),
            onError: (error) => onError?.(error),
            onSuccess: async (data, variables, context) => {
                await invalidateFetchTransactionsPaginated();
                onSuccess?.(data, variables, context);
            },
        });
    return { useUpdateTransaction, useDeleteTransaction };
};
export default useMutation;
