import useAxios from '@/inertia/Libs/useAxios';

export const fetchProductPaginated = () => {
    const params = {
        limit: 999,
    }
    return useAxios()
        .get('/product-paginated', { params })
        .then(res => res.data.data);
};

export const createProduct = payload => {
    return useAxios()
        .post('/product', payload, {
            headers: { 'Content-Type': 'multipart/form-data' },
        })

        .then(res => res.data.data);
};

export const deleteProduct = id => {
    return useAxios()
        .delete(`/product/${id}`)
        .then(res => res.data.data);
};


export const updateProduct = (id, payload) => {
    payload.append('_method', 'PUT');
    return useAxios()
        .post(`/product/${id}`, payload, {
            headers: { 'Content-Type': 'multipart/form-data' },
        })
        .then(res => res.data.data);
};
