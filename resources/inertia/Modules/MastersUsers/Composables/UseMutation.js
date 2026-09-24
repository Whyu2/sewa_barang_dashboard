import { useMutation as useMutationTanstack } from '@tanstack/vue-query';
import {
    createUser,
    deleteUser,
    updateUser
} from "@/inertia/Modules/MastersUsers/Services/UserService.js";

const useMutation = () => {
  const useCreateUser = ({ onSuccess, onError }) =>
    useMutationTanstack({
      mutationKey: ['createUser'],
      mutationFn: ({ payload }) => createUser(payload),
      onError: error => onError?.(error),
      onSuccess: data => onSuccess?.(data),
    });

    const useDeleteUser = ({ onSuccess, onError }) =>
        useMutationTanstack({
            mutationKey: ['deleteUser'],
            mutationFn: ({ id }) => deleteUser(id),
            onError: error => onError?.(error),
            onSuccess: data => onSuccess?.(data),
        });

    const useUpdateUser = ({ onSuccess, onError }) =>
        useMutationTanstack({
            mutationKey: ['updateUser'],
            mutationFn: ({ id, payload }) => updateUser( id, payload),
            onError: error => onError?.(error),
            onSuccess: data => onSuccess?.(data),
        });
  return {
    useCreateUser,
      useDeleteUser,
      useUpdateUser
  };
};
export default useMutation;
