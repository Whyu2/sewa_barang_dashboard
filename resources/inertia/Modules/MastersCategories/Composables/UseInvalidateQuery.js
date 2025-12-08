import { useQueryClient } from '@tanstack/vue-query';

const useInvalidateQuery = () => {
  const clientQuery = useQueryClient();

  const useInvalidateFetchCategoryPaginated = () =>
    clientQuery.invalidateQueries({
      queryKey: ['fetchCategoryPaginated'],
    });

  return {
      useInvalidateFetchCategoryPaginated,
  };
};

export default useInvalidateQuery;
