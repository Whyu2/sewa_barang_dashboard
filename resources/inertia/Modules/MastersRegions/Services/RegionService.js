import useAxios from '@/inertia/Libs/useAxios';


export const fetchRegions = () => {
    return useAxios()
        .get('/region')
        .then(res => res.data.data);
};
export const fetchRegionPaginated = () => {
    const params = {
        limit: 999,
    }
  return useAxios()
    .get('/region-paginated', {params})
    .then(res => res.data.data);
};

export const createRegion = payload => {
    return useAxios()
        .post('/region', payload)
        .then(res => res.data.data);
};

export const deleteRegion = id => {
    return useAxios()
        .delete(`/region/${id}`)
        .then(res => res.data.data);
};


export const updateRegion = ( id, payload ) => {
    return useAxios()
        .put(`/region/${id}`, payload)
        .then(res => res.data.data);
};
