import { useMutation as useMutationTanstack } from '@tanstack/vue-query';
import {login, logout} from "@/inertia/Modules/Auth/Services/AuthService.js";
import { loginQueryKey, logoutQueryKey } from "@/inertia/Constans/MutationKeys.js";

const useMutation = () => {
  const useLogin = ({ onSuccess, onError } = {}) =>
    useMutationTanstack({
      mutationKey: loginQueryKey(),
      mutationFn: ({ payload }) => login(payload),
      onError: error => onError?.(error),
      onSuccess: (data, variables, context) => onSuccess?.(data, variables, context),
    });

    const useLogout = ({ onSuccess, onError } = {}) =>
        useMutationTanstack({
            mutationKey: logoutQueryKey(),
            mutationFn: () => logout(),
            onError: error => onError?.(error),
            onSuccess: (data, variables, context) => onSuccess?.(data, variables, context),
        });

  return {
      useLogin,
      useLogout
  };
};
export default useMutation;
