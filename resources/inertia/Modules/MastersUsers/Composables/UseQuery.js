import { useQuery as useQueryTanstack } from '@tanstack/vue-query';
import {fetchUserPaginated} from "@/inertia/Modules/MastersUsers/Services/UserService.js";

const useQuery = () => {
  const useFetchUserPaginated = () =>
    useQueryTanstack({
      queryKey: ['fetchUserPaginated'],
      queryFn: () => fetchUserPaginated(),
      placeholderData: [],
    });
  return {
      useFetchUserPaginated,
  };
};

export default useQuery;
