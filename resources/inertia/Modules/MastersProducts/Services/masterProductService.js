import useAxios from '@/inertia/Libs/useAxios';

export const fetchProductPaginated = () => {
    const params = {
        limit: 999,
    }
  return useAxios()
    .get('/product-paginated', {params})
    .then(res => res.data.data);
};
