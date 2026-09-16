import useAxios from '@/inertia/Libs/useAxios';

export const fetchTransactionsPaginated = (params = {}) => {
    return useAxios().get('/rent-transactions-paginated', { params }).then(res => res.data.data);
};

export const fetchTransactions = () => {
    return useAxios().get('/rent-transactions').then(res => res.data.data);
};

export const updateTransaction = (id, payload) => {
    if (payload instanceof FormData) {
        payload.append('_method', 'PUT');
        return useAxios().post(`/rent-transaction/${id}`, payload, { headers: { 'Content-Type': 'multipart/form-data' } }).then(res => res.data.data);
    }
    return useAxios().put(`/rent-transaction/${id}`, payload).then(res => res.data.data);
};

export const deleteTransaction = (id) => {
    return useAxios().delete(`/rent-transaction/${id}`).then(res => res.data.data);
};
