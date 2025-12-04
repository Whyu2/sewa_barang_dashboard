import useAxios from '@/inertia/Libs/useAxios';

export const fetchProducts = () => {
  return useAxios()
    .get('/products')
    .then(res => res.data.data);
};
