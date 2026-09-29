import { useQuery as useQueryTanstack } from '@tanstack/vue-query';
import {fetchCategoryPaginated} from "@/inertia/Modules/MastersCategories/Services/CategoryService.js";
import { fetchCategoryPaginatedQueryKey } from "@/inertia/Constans/QueryKeys.js";

const useQuery = () => {
  const useFetchCategoryPaginated = () =>
    useQueryTanstack({
      queryKey: fetchCategoryPaginatedQueryKey(),
      queryFn: () => fetchCategoryPaginated(),
      placeholderData: [],
    });
  return {
      useFetchCategoryPaginated,
  };
};

export default useQuery;
