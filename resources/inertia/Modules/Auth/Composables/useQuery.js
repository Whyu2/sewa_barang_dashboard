import { useQuery as useQueryTanstack } from '@tanstack/vue-query';
import {me} from "@/inertia/Modules/Auth/Services/AuthService.js";
import { fetchMeQueryKey } from "@/inertia/Constans/QueryKeys.js";

const useQuery = () => {
  const useFetchMe= () =>
    useQueryTanstack({
      queryKey: fetchMeQueryKey(),
      queryFn: () => me(),
      retry: false,
      staleTime: 5 * 60 * 1000,
    });
  return {
      useFetchMe,
  };
};

export default useQuery;
