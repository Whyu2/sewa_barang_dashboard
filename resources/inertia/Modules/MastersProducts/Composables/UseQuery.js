import { useQuery as useQueryTanstack, useQueryClient } from '@tanstack/vue-query';
import {fetchProductPaginated} from "@/inertia/Modules/MastersProducts/Services/ProductService.js";
import {fetchRegions} from "@/inertia/Modules/MastersProducts/Services/RegionService.js";
import {fetchCategories} from "@/inertia/Modules/MastersCategories/Services/CategoryService.js";

const useQuery = () => {
  const useFetchProductPaginated = () =>
    useQueryTanstack({
      queryKey: ['fetchProductPaginated'],
      queryFn: () => fetchProductPaginated(),
      placeholderData: [],
    });

    const useFetchCategories = () =>
        useQueryTanstack({
            queryKey: ['fetchCategories'],
            queryFn: () => fetchCategories(),
            placeholderData: [],
        });
    const useFetchRegions = () =>
        useQueryTanstack({
            queryKey: ['fetchRegions'],
            queryFn: () => fetchRegions(),
            placeholderData: [],
        });
  return {
      useFetchProductPaginated,
      useFetchCategories,
      useFetchRegions,
  };
};

export default useQuery;
