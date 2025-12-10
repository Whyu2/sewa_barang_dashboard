import useAxios from '@/inertia/Libs/useAxios';

export const fetchProductPaginated = () => {
    const params = {
        limit: 999,
    }
  return useAxios()
    .get('/product-paginated', {params})
    .then(res => res.data.data);
};

export const createProduct = payload => {
    return useAxios()
        .post('/product', payload)
        .then(res => res.data.data);
};

export const deleteProduct = id => {
    return useAxios()
        .delete(`/product/${id}`)
        .then(res => res.data.data);
};


export const updateProduct = ( id, payload ) => {
    return useAxios()
        .put(`/product/${id}`, payload)
        .then(res => res.data.data);
};
