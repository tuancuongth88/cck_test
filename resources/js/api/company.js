import axios from '@/utils/axios';

export const listJob = async(params = {}) => {
  return await axios.get('/company', { params });
};

export const addJob = async(params = {}) => {
  return await axios.post('company', params);
};

export const destroyJob = async(id) => {
  return await axios.delete(`company/${id}`);
};

export const detailsJob = async(id) => {
  return await axios.get(`company/${id}`);
};

export const updateJob = async(params = {}) => {
  return await axios.put(`company/${params.id}`, params);
};

// Company list - /api/company - sort(params)
export const listCompany = async(params) => {
  return await axios.get('company', { params });
};
// Company Register - /api/company
export const companyRegister = async(params) => {
  return await axios.post('company', params);
};
// Company Detail id company/id
export const detailCompany = async(id) => {
  return await axios.get(`company/${id}`);
};
// Company Edit
export const updateCompany = async(id, params) => {
  // return await axios.put(`company/${params}`, params);
  return await axios.put(`company/${id}`, params);
};
