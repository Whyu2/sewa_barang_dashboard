import useAxios from '@/inertia/Libs/useAxios';


export const fetchUsers = () => {
    return useAxios()
        .get('/users')
        .then(res => res.data.data);
};
export const fetchUserPaginated = () => {
    const params = {
        limit: 999,
    }
  return useAxios()
    .get('/user-paginated', {params})
    .then(res => res.data.data);
};

export const createUser = payload => {
    return useAxios()
        .post('/user', payload)
        .then(res => res.data.data);
};

export const deleteUser = id => {
    return useAxios()
        .delete(`/user/${id}`)
        .then(res => res.data.data);
};


export const updateUser = ( id, payload ) => {
    return useAxios()
        .put(`/user/${id}`, payload)
        .then(res => res.data.data);
};
