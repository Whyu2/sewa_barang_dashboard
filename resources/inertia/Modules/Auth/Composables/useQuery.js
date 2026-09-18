import { useQuery as useQueryTanstack, useQueryClient } from '@tanstack/vue-query';
import {me} from "@/inertia/Modules/Auth/Services/AuthService.js";

const useQuery = () => {
  const useFetchMe= () =>
    useQueryTanstack({
      queryKey: ['fetchMe'],
      queryFn: () => me(),
      retry: false,
      staleTime: 5 * 60 * 1000,
    });
  return {
      useFetchMe,
  };
};

export default useQuery;
