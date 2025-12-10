import { useQuery as useQueryTanstack, useQueryClient } from '@tanstack/vue-query';
import {fetchRegionPaginated} from "@/inertia/Modules/MastersRegions/Services/RegionService.js";

const useQuery = () => {
  const useFetchRegionPaginated = () =>
    useQueryTanstack({
      queryKey: ['fetchRegionPaginated'],
      queryFn: () => fetchRegionPaginated(),
      placeholderData: [],
    });
  return {
      useFetchRegionPaginated,
  };
};

export default useQuery;
