import { useQueryClient } from '@tanstack/vue-query';

const useInvalidateQuery = () => {
  const clientQuery = useQueryClient();

  const useInvalidateFetchUserPaginated = () =>
    clientQuery.invalidateQueries({
      queryKey: ['fetchUserPaginated'],
    });

  return {
      useInvalidateFetchUserPaginated,
  };
};

export default useInvalidateQuery;
