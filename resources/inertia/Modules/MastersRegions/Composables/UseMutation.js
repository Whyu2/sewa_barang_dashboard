import { useMutation as useMutationTanstack } from '@tanstack/vue-query';
import {
    createRegion,
    deleteRegion,
    updateRegion
} from "@/inertia/Modules/MastersRegions/Services/RegionService.js";

const useMutation = () => {
  const useCreateRegion = ({ onSuccess, onError }) =>
    useMutationTanstack({
      mutationKey: ['createRegion'],
      mutationFn: ({ payload }) => createRegion(payload),
      onError: error => onError(error),
      onSuccess: data => onSuccess(data),
    });

    const useDeleteRegion = ({ onSuccess, onError }) =>
        useMutationTanstack({
            mutationKey: ['deleteRegion'],
            mutationFn: ({ id }) => deleteRegion(id),
            onError: error => onError(error),
            onSuccess: data => onSuccess(data),
        });

    const useUpdateRegion = ({ onSuccess, onError }) =>
        useMutationTanstack({
            mutationKey: ['updateRegion'],
            mutationFn: ({ id, payload }) => updateRegion( id, payload),
            onError: error => onError(error),
            onSuccess: data => onSuccess(data),
        });
  return {
    useCreateRegion,
      useDeleteRegion,
      useUpdateRegion
  };
};
export default useMutation;
