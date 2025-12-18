import { useQuery as useQueryTanstack, useQueryClient } from '@tanstack/vue-query';
import {me} from "@/inertia/Modules/Auth/Services/AuthService.js";

const useQuery = () => {
  const useFetchMe= () =>
    useQueryTanstack({
      queryKey: ['fetchMe'],
      queryFn: () => me(),
      placeholderData: [],
    });
  return {
      useFetchMe,
  };
};

export default useQuery;
