import axios from '@/utils/axios';

export const login = async(params = {}) => {
  return await axios.post('auth/login', params);
};
