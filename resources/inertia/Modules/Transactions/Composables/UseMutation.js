import { useMutation as useMutationTanstack } from '@tanstack/vue-query';
import { updateTransaction, deleteTransaction } from '@/inertia/Modules/Transactions/Services/TransactionService.js';

const useMutation = () => {
    const useUpdateTransaction = ({ onSuccess, onError }) =>
        useMutationTanstack({
            mutationKey: ['updateTransaction'],
            mutationFn: ({ id, payload }) => updateTransaction(id, payload),
            onError: (e) => onError?.(e),
            onSuccess: (d) => onSuccess?.(d),
        });
    const useDeleteTransaction = ({ onSuccess, onError }) =>
        useMutationTanstack({
            mutationKey: ['deleteTransaction'],
            mutationFn: ({ id }) => deleteTransaction(id),
            onError: (e) => onError?.(e),
            onSuccess: (d) => onSuccess?.(d),
        });
    return { useUpdateTransaction, useDeleteTransaction };
};
export default useMutation;
