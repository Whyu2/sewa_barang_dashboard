import { useMutation as useMutationTanstack, useQueryClient } from '@tanstack/vue-query';
import {
    createCategory,
    deleteCategory,
    updateCategory
} from "@/inertia/Modules/MastersCategories/Services/CategoryService.js";

import { fetchCategoryPaginatedQueryKey } from "@/inertia/Constans/QueryKeys.js";
import { createCategoryQueryKey, deleteCategoryQueryKey, updateCategoryQueryKey } from "@/inertia/Constans/MutationKeys.js";

const useMutation = () => {
  const queryClient = useQueryClient();
  const invalidateFetchCategoryPaginated = () =>
    queryClient.invalidateQueries({
      queryKey: fetchCategoryPaginatedQueryKey(),
    });

  const useCreateCategory = ({ onSuccess, onError } = {}) =>
    useMutationTanstack({
      mutationKey: createCategoryQueryKey(),
      mutationFn: ({ payload }) => createCategory(payload),
      onError: error => onError?.(error),
      onSuccess: async (data, variables, context) => {
        await invalidateFetchCategoryPaginated();
        onSuccess?.(data, variables, context);
      },
    });

    const useDeleteCategory = ({ onSuccess, onError } = {}) =>
        useMutationTanstack({
            mutationKey: deleteCategoryQueryKey(),
            mutationFn: ({ id }) => deleteCategory(id),
            onError: error => onError?.(error),
            onSuccess: async (data, variables, context) => {
              await invalidateFetchCategoryPaginated();
              onSuccess?.(data, variables, context);
            },
        });

    const useUpdateCategory = ({ onSuccess, onError } = {}) =>
        useMutationTanstack({
            mutationKey: updateCategoryQueryKey(),
            mutationFn: ({ id, payload }) => updateCategory( id, payload),
            onError: error => onError?.(error),
            onSuccess: async (data, variables, context) => {
              await invalidateFetchCategoryPaginated();
              onSuccess?.(data, variables, context);
            },
        });
  return {
    useCreateCategory,
      useDeleteCategory,
      useUpdateCategory
  };
};
export default useMutation;
