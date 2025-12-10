import { useQuery as useQueryTanstack, useQueryClient } from '@tanstack/vue-query';
import {fetchCategoryPaginated} from "@/inertia/Modules/MastersCategories/Services/CategoryService.js";

const useQuery = () => {
  const useFetchCategoryPaginated = () =>
    useQueryTanstack({
      queryKey: ['fetchCategoryPaginated'],
      queryFn: () => fetchCategoryPaginated(),
      placeholderData: [],
    });
  return {
      useFetchCategoryPaginated,
  };
};

export default useQuery;
