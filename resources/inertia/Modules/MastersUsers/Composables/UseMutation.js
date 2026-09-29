import { useMutation as useMutationTanstack, useQueryClient } from '@tanstack/vue-query';
import {
  createUser,
  deleteUser,
  updateUser
} from "@/inertia/Modules/MastersUsers/Services/UserService.js";
import { fetchUserPaginatedQueryKey } from "@/inertia/Constans/QueryKeys.js";
import { createUserQueryKey, deleteUserQueryKey, updateUserQueryKey } from "@/inertia/Constans/MutationKeys.js";

const useMutation = () => {
  const queryClient = useQueryClient();
  const invalidateFetchUserPaginated = () =>
    queryClient.invalidateQueries({
      queryKey: fetchUserPaginatedQueryKey(),
    });

  const useCreateUser = ({ onSuccess, onError } = {}) =>
    useMutationTanstack({
      mutationKey: createUserQueryKey(),
      mutationFn: ({ payload }) => createUser(payload),
      onError: error => onError?.(error),
      onSuccess: async (data, variables, context) => {
        await invalidateFetchUserPaginated();
        onSuccess?.(data, variables, context);
      },
    });

  const useDeleteUser = ({ onSuccess, onError } = {}) =>
    useMutationTanstack({
      mutationKey: deleteUserQueryKey(),
      mutationFn: ({ id }) => deleteUser(id),
      onError: error => onError?.(error),
      onSuccess: async (data, variables, context) => {
        await invalidateFetchUserPaginated();
        onSuccess?.(data, variables, context);
      },
    });

  const useUpdateUser = ({ onSuccess, onError } = {}) =>
    useMutationTanstack({
      mutationKey: updateUserQueryKey(),
      mutationFn: ({ id, payload }) => updateUser(id, payload),
      onError: error => onError?.(error),
      onSuccess: async (data, variables, context) => {
        await invalidateFetchUserPaginated();
        onSuccess?.(data, variables, context);
      },
    });
  return {
    useCreateUser,
    useDeleteUser,
    useUpdateUser
  };
};
export default useMutation;
