import { useQuery as useQueryTanstack } from '@tanstack/vue-query';
import { fetchProductPaginated } from "@/inertia/Modules/MastersProducts/Services/ProductService.js";
import { fetchRegions } from "@/inertia/Modules/MastersProducts/Services/RegionService.js";
import { fetchCategories } from "@/inertia/Modules/MastersCategories/Services/CategoryService.js";
import { fetchProductPaginatedQueryKey, fetchCategoriesQueryKey, fetchRegionsQueryKey } from "@/inertia/Constans/QueryKeys.js";

const useQuery = () => {
    const useFetchProductPaginated = () =>
        useQueryTanstack({
            queryKey: fetchProductPaginatedQueryKey(),
            queryFn: () => fetchProductPaginated(),
            placeholderData: [],
        });

    const useFetchCategories = () =>
        useQueryTanstack({
            queryKey: fetchCategoriesQueryKey(),
            queryFn: () => fetchCategories(),
            placeholderData: [],
        });
    const useFetchRegions = () =>
        useQueryTanstack({
            queryKey: fetchRegionsQueryKey(),
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
