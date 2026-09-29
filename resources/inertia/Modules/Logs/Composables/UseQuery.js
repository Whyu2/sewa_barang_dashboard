import { useQuery as useQueryTanstack } from '@tanstack/vue-query';
import { fetchLogsPaginated } from '@/inertia/Modules/Logs/Services/LogService.js';
import { logsPaginatedQueryKey } from "@/inertia/Constans/QueryKeys.js";
const useQuery = () => {
  const useFetchLogsPaginated = (params) => useQueryTanstack({ queryKey: logsPaginatedQueryKey(params), queryFn: () => fetchLogsPaginated(params.value ?? params), placeholderData: [] });
  return { useFetchLogsPaginated };
};
export default useQuery;
