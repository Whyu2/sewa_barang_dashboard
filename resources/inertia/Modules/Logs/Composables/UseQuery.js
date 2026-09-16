import { useQuery as useQueryTanstack } from '@tanstack/vue-query';
import { fetchLogsPaginated } from '@/inertia/Modules/Logs/Services/LogService.js';
const useQuery = () => {
  const useFetchLogsPaginated = (params) => useQueryTanstack({ queryKey: ['logsPaginated', params], queryFn: () => fetchLogsPaginated(params.value ?? params), placeholderData: [] });
  return { useFetchLogsPaginated };
};
export default useQuery;
