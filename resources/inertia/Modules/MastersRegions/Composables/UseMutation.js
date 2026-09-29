import { useMutation as useMutationTanstack, useQueryClient } from '@tanstack/vue-query';
import {
    createRegion,
    deleteRegion,
    updateRegion
} from "@/inertia/Modules/MastersRegions/Services/RegionService.js";
import { fetchRegionPaginatedQueryKey } from "@/inertia/Constans/QueryKeys.js";
import { createRegionQueryKey, deleteRegionQueryKey, updateRegionQueryKey } from "@/inertia/Constans/MutationKeys.js";

const useMutation = () => {
  const queryClient = useQueryClient();
  const invalidateFetchRegionPaginated = () =>
    queryClient.invalidateQueries({
      queryKey: fetchRegionPaginatedQueryKey(),
    });

  const useCreateRegion = ({ onSuccess, onError } = {}) =>
    useMutationTanstack({
      mutationKey: createRegionQueryKey(),
      mutationFn: ({ payload }) => createRegion(payload),
      onError: error => onError?.(error),
      onSuccess: async (data, variables, context) => {
        await invalidateFetchRegionPaginated();
        onSuccess?.(data, variables, context);
      },
    });

    const useDeleteRegion = ({ onSuccess, onError } = {}) =>
        useMutationTanstack({
            mutationKey: deleteRegionQueryKey(),
            mutationFn: ({ id }) => deleteRegion(id),
            onError: error => onError?.(error),
            onSuccess: async (data, variables, context) => {
              await invalidateFetchRegionPaginated();
              onSuccess?.(data, variables, context);
            },
        });

    const useUpdateRegion = ({ onSuccess, onError } = {}) =>
        useMutationTanstack({
            mutationKey: updateRegionQueryKey(),
            mutationFn: ({ id, payload }) => updateRegion( id, payload),
            onError: error => onError?.(error),
            onSuccess: async (data, variables, context) => {
              await invalidateFetchRegionPaginated();
              onSuccess?.(data, variables, context);
            },
        });
  return {
    useCreateRegion,
      useDeleteRegion,
      useUpdateRegion
  };
};
export default useMutation;
