import useAxios from '@/inertia/Libs/useAxios';
export const fetchLogsPaginated = (params={}) => useAxios().get('/transaction-logs-paginated', { params }).then(r=>r.data.data);
export const fetchLogs = () => useAxios().get('/transaction-logs').then(r=>r.data.data);
