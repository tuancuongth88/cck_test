import axios from '@/utils/axios';

export const getAllHrOrganization = async(params = {}) => {
  return await axios.get('/hr-organization', { params });
};

export const getDetailHrOrganization = async(id) => {
  return await axios.get(`/hr-organization/${id}`);
};

export const updateStatusHrOrganization = async(param) => {
  return await axios.post(`/update-status-hr`, param);
};

export const updateHrOrganization = async(data) => {
  return await axios.put(`/hr-organization/${data.id}`, data);
};
