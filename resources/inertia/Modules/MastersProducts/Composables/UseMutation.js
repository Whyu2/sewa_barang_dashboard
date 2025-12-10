import { useMutation as useMutationTanstack } from '@tanstack/vue-query';
import {
    createProduct,
    deleteProduct,
    updateProduct
} from "@/inertia/Modules/MastersProducts/Services/ProductService.js";

const useMutation = () => {
  const useCreateProduct = ({ onSuccess, onError }) =>
    useMutationTanstack({
      mutationKey: ['createProduct'],
      mutationFn: ({ payload }) => createProduct(payload),
      onError: error => onError(error),
      onSuccess: data => onSuccess(data),
    });

    const useDeleteProduct = ({ onSuccess, onError }) =>
        useMutationTanstack({
            mutationKey: ['deleteProduct'],
            mutationFn: ({ id }) => deleteProduct(id),
            onError: error => onError(error),
            onSuccess: data => onSuccess(data),
        });

    const useUpdateProduct = ({ onSuccess, onError }) =>
        useMutationTanstack({
            mutationKey: ['updateProduct'],
            mutationFn: ({ id, payload }) => updateProduct( id, payload),
            onError: error => onError(error),
            onSuccess: data => onSuccess(data),
        });
  return {
    useCreateProduct,
      useDeleteProduct,
      useUpdateProduct
  };
};
export default useMutation;
