import useAxios from '@/inertia/Libs/useAxios';

export const fetchProducts = () => {
  return useAxios()
    .get('/products')
    .then(res => res.data.data);
};
const clean = (p={}) => Object.fromEntries(Object.entries(p).filter(([_,v])=>v!==undefined && v!==null && v!=='' && v!=='all'));
export const fetchDashboardStats = (params={}) => useAxios().get('/dashboard-stats', { params: clean(params) }).then(r=>r.data.data);
export const fetchDashboardCharts = (params={}) => useAxios().get('/dashboard-charts', { params: clean(params) }).then(r=>r.data.data);
export const fetchDashboardTables = (params={}) => useAxios().get('/dashboard-tables', { params: clean(params) }).then(r=>r.data.data);
