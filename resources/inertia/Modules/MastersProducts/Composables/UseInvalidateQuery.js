import { useQueryClient } from '@tanstack/vue-query';

const useInvalidateQuery = () => {
  const clientQuery = useQueryClient();

  const useInvalidateFetchProductPaginated = () =>
    clientQuery.invalidateQueries({
      queryKey: ['fetchProductPaginated'],
    });

  return {
      useInvalidateFetchProductPaginated,
  };
};

export default useInvalidateQuery;
