import useAxios from '@/inertia/Libs/useAxios';

export const fetchCategories = () => {
    return useAxios()
        .get('/category')
        .then(res => res.data.data);
};
export const fetchCategoryPaginated = () => {
    const params = {
        limit: 999,
    }
  return useAxios()
    .get('/category-paginated', {params})
    .then(res => res.data.data);
};

export const createCategory = payload => {
    return useAxios()
        .post('/category', payload)
        .then(res => res.data.data);
};

export const deleteCategory = id => {
    return useAxios()
        .delete(`/category/${id}`)
        .then(res => res.data.data);
};


export const updateCategory = ( id, payload ) => {
    return useAxios()
        .put(`/category/${id}`, payload)
        .then(res => res.data.data);
};
