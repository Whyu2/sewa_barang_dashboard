import { useQuery as useQueryTanstack, useQueryClient } from '@tanstack/vue-query';
import {fetchCategoryPaginated} from "@/inertia/Modules/MastersCategories/Services/masterCategoryService.js";

const useQuery = () => {
  const useFetchProductPaginated = () =>
    useQueryTanstack({
      queryKey: ['fetchCategoryPaginated'],
      queryFn: () => fetchCategoryPaginated(),
      placeholderData: [],
    });
  return {
      useFetchProductPaginated,
  };
};

export default useQuery;
