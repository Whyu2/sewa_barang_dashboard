import { useQuery as useQueryTanstack } from '@tanstack/vue-query';
import {fetchUserPaginated} from "@/inertia/Modules/MastersUsers/Services/UserService.js";
import { fetchUserPaginatedQueryKey } from "@/inertia/Constans/QueryKeys.js";

const useQuery = () => {
  const useFetchUserPaginated = () =>
    useQueryTanstack({
      queryKey: fetchUserPaginatedQueryKey(),
      queryFn: () => fetchUserPaginated(),
      placeholderData: [],
    });
  return {
      useFetchUserPaginated,
  };
};

export default useQuery;
