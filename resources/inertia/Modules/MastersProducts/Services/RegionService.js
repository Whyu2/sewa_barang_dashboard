import useAxios from '@/inertia/Libs/useAxios';

export const fetchRegions = () => {
  return useAxios()
    .get('/regions')
    .then(res => res.data.data);
};

