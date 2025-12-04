import { useQuery as useQueryTanstack, useQueryClient } from '@tanstack/vue-query';

import { computed } from 'vue';
import {fetchProducts} from "@/inertia/Modules/Dashboard/Services/dashboardService.js";

const dashboardUseQuery = () => {
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

export default dashboardUseQuery;
