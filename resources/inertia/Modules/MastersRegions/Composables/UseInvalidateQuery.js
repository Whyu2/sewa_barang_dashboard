import { useQueryClient } from '@tanstack/vue-query';

const useInvalidateQuery = () => {
  const clientQuery = useQueryClient();

  const useInvalidateFetchRegionPaginated = () =>
    clientQuery.invalidateQueries({
      queryKey: ['fetchRegionPaginated'],
    });

  return {
      useInvalidateFetchRegionPaginated,
  };
};

export default useInvalidateQuery;
