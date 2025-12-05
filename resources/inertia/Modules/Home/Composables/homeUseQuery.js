import { useQuery as useQueryTanstack, useQueryClient } from '@tanstack/vue-query';

import { computed } from 'vue';
import {fetchProducts} from "@/inertia/Modules/Home/Services/homeService.js";

const homeUseQuery = () => {
  const useFetchProducts = () =>
    useQueryTanstack({
      queryKey: ['fetchProducts'],
      queryFn: () => fetchProducts(),
      placeholderData: [],
    });
  return {
    useFetchProducts,
  };
};

export default homeUseQuery;
