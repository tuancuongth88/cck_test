import axios from '@/utils/axios';

// params ok
export const uploadFile = async(params) => {
  return await axios.post('upload', params);
};
