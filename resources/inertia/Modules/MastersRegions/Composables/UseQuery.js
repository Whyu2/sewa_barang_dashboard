import { useQuery as useQueryTanstack } from '@tanstack/vue-query';
import {fetchRegionPaginated} from "@/inertia/Modules/MastersRegions/Services/RegionService.js";
import { fetchRegionPaginatedQueryKey } from "@/inertia/Constans/QueryKeys.js";

const useQuery = () => {
  const useFetchRegionPaginated = () =>
    useQueryTanstack({
      queryKey: fetchRegionPaginatedQueryKey(),
      queryFn: () => fetchRegionPaginated(),
      placeholderData: [],
    });
  return {
      useFetchRegionPaginated,
  };
};

export default useQuery;
