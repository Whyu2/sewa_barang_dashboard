import useAxios from '@/inertia/Libs/useAxios';

export const login = (payload ) => {
    return useAxios()
        .post(`/login`, payload)
        .then(res => res.data.data);
};

export const me = ( ) => {
    return useAxios()
        .post(`/me`)
        .then(res => res.data.data);
};

export const logout = ( ) => {
    return useAxios()
        .post(`/logout`)
        .then(res => res.data.data);
};
