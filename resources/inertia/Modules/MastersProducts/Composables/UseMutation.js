import { useMutation as useMutationTanstack, useQueryClient } from '@tanstack/vue-query';
import {
  createProduct,
  deleteProduct,
  updateProduct
} from "@/inertia/Modules/MastersProducts/Services/ProductService.js";
import { fetchProductPaginatedQueryKey } from "@/inertia/Constans/QueryKeys.js";
import { createProductQueryKey, updateProductQueryKey, deleteProductQueryKey } from "@/inertia/Constans/MutationKeys.js";


const useMutation = () => {
  const queryClient = useQueryClient();
  const invalidateFetchProductPaginated = () =>
    queryClient.invalidateQueries({
      queryKey: fetchProductPaginatedQueryKey(),
    });

  const useCreateProduct = ({ onSuccess, onError } = {}) =>
    useMutationTanstack({
      mutationKey: createProductQueryKey(),
      mutationFn: ({ payload }) => createProduct(payload),
      onError: error => onError?.(error),
      onSuccess: async (data, variables, context) => {
        await invalidateFetchProductPaginated();
        onSuccess?.(data, variables, context);
      },
    });

  const useDeleteProduct = ({ onSuccess, onError } = {}) =>
    useMutationTanstack({
      mutationKey: deleteProductQueryKey(),
      mutationFn: ({ id }) => deleteProduct(id),
      onError: error => onError?.(error),
      onSuccess: async (data, variables, context) => {
        await invalidateFetchProductPaginated();
        onSuccess?.(data, variables, context);
      },
    });

  const useUpdateProduct = ({ onSuccess, onError } = {}) =>
    useMutationTanstack({
      mutationKey: updateProductQueryKey(),
      mutationFn: ({ id, payload }) => updateProduct(id, payload),
      onError: error => onError?.(error),
      onSuccess: async (data, variables, context) => {
        await invalidateFetchProductPaginated();
        onSuccess?.(data, variables, context);
      },
    });
  return {
    useCreateProduct,
    useDeleteProduct,
    useUpdateProduct
  };
};
export default useMutation;
