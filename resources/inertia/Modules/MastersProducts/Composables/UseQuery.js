import { useQuery as useQueryTanstack, useQueryClient } from '@tanstack/vue-query';

import { computed } from 'vue';
import {fetchProducts} from "@/inertia/Modules/Home/Services/homeService.js";
import {fetchProductPaginated} from "@/inertia/Modules/MastersProducts/Services/masterProductService.js";

const useQuery = () => {
  const useFetchProductPaginated = () =>
    useQueryTanstack({
      queryKey: ['fetchProductPaginated'],
      queryFn: () => fetchProductPaginated(),
      placeholderData: [],
    });
  return {
      useFetchProductPaginated,
  };
};

export default useQuery;
