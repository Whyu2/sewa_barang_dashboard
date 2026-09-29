import { useQuery as useQueryTanstack } from '@tanstack/vue-query';
import { fetchProducts, fetchDashboardStats, fetchDashboardCharts, fetchDashboardTables } from "@/inertia/Modules/Home/Services/homeService.js";
import { fetchProductsQueryKey, dashboardStatsQueryKey, dashboardChartsQueryKey, dashboardTablesQueryKey } from "@/inertia/Constans/QueryKeys.js";

const homeUseQuery = () => {
  const useFetchProducts = () =>
    useQueryTanstack({ queryKey: fetchProductsQueryKey(), queryFn: () => fetchProducts(), placeholderData: [] });
  const useFetchDashboardStats = (params) =>
    useQueryTanstack({ queryKey: dashboardStatsQueryKey(params?.value ?? params), queryFn: () => fetchDashboardStats(params?.value ?? params), placeholderData: {} });
  const useFetchDashboardCharts = (params) =>
    useQueryTanstack({ queryKey: dashboardChartsQueryKey(params?.value ?? params), queryFn: () => fetchDashboardCharts(params?.value ?? params), placeholderData: {} });
  const useFetchDashboardTables = (params) =>
    useQueryTanstack({ queryKey: dashboardTablesQueryKey(params?.value ?? params), queryFn: () => fetchDashboardTables(params?.value ?? params), placeholderData: {} });
  return { useFetchProducts, useFetchDashboardStats, useFetchDashboardCharts, useFetchDashboardTables };
};
export default homeUseQuery;
