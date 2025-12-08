import axios from 'axios';
const defaultConfig = {
  baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8000/api',
};

const axiosInstance = axios.create(defaultConfig);


axiosInstance.interceptors.response.use(
  async response => {
    // bisa digunakan untuk melakukan parsing response, tetapi harus hati-hati dalam implementasinya
    // karena ini bersifat global
    return response;
  },
  error => {

    if (axios.isCancel(error)) {
      return Promise.reject(new Error(error));
    }
    try {
      const errMessage = error.response.data?.message;
      return Promise.reject(new Error(errMessage));
    } catch (e) {
      console.info('Failed to parsing response message: ', e);
    }
    return Promise.reject(new Error(error));
  }
);

const useAxios = () => {
  return axiosInstance;
};

export default useAxios;
