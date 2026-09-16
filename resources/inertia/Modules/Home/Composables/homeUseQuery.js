import { useQuery as useQueryTanstack } from '@tanstack/vue-query';
import { fetchProducts, fetchDashboardStats, fetchDashboardCharts, fetchDashboardTables } from "@/inertia/Modules/Home/Services/homeService.js";

const homeUseQuery = () => {
  const useFetchProducts = () =>
    useQueryTanstack({ queryKey: ['fetchProducts'], queryFn: () => fetchProducts(), placeholderData: [] });
  const useFetchDashboardStats = (params) =>
    useQueryTanstack({ queryKey: ['dashboardStats', params?.value ?? params], queryFn: () => fetchDashboardStats(params?.value ?? params), placeholderData: {} });
  const useFetchDashboardCharts = (params) =>
    useQueryTanstack({ queryKey: ['dashboardCharts', params?.value ?? params], queryFn: () => fetchDashboardCharts(params?.value ?? params), placeholderData: {} });
  const useFetchDashboardTables = (params) =>
    useQueryTanstack({ queryKey: ['dashboardTables', params?.value ?? params], queryFn: () => fetchDashboardTables(params?.value ?? params), placeholderData: {} });
  return { useFetchProducts, useFetchDashboardStats, useFetchDashboardCharts, useFetchDashboardTables };
};
export default homeUseQuery;
