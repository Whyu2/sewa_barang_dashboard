import { useMutation as useMutationTanstack } from '@tanstack/vue-query';
import {login, logout} from "@/inertia/Modules/Auth/Services/AuthService.js";

const useMutation = () => {
  const useLogin = ({ onSuccess, onError }) =>
    useMutationTanstack({
      mutationKey: ['login'],
      mutationFn: ({ payload }) => login(payload),
      onError: error => onError(error),
      onSuccess: data => onSuccess(data),
    });

    const useLogout = ({ onSuccess, onError }) =>
        useMutationTanstack({
            mutationKey: ['logout'],
            mutationFn: () => logout(),
            onError: error => onError(error),
            onSuccess: data => onSuccess(data),
        });

  return {
      useLogin,
      useLogout
  };
};
export default useMutation;
