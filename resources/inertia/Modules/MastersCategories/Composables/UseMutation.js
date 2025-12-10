import { useMutation as useMutationTanstack } from '@tanstack/vue-query';
import {
    createCategory,
    deleteCategory,
    updateCategory
} from "@/inertia/Modules/MastersCategories/Services/CategoryService.js";

const useMutation = () => {
  const useCreateCategory = ({ onSuccess, onError }) =>
    useMutationTanstack({
      mutationKey: ['createCategory'],
      mutationFn: ({ payload }) => createCategory(payload),
      onError: error => onError(error),
      onSuccess: data => onSuccess(data),
    });

    const useDeleteCategory = ({ onSuccess, onError }) =>
        useMutationTanstack({
            mutationKey: ['deleteCategory'],
            mutationFn: ({ id }) => deleteCategory(id),
            onError: error => onError(error),
            onSuccess: data => onSuccess(data),
        });

    const useUpdateCategory = ({ onSuccess, onError }) =>
        useMutationTanstack({
            mutationKey: ['updateCategory'],
            mutationFn: ({ id, payload }) => updateCategory( id, payload),
            onError: error => onError(error),
            onSuccess: data => onSuccess(data),
        });
  return {
    useCreateCategory,
      useDeleteCategory,
      useUpdateCategory
  };
};
export default useMutation;
