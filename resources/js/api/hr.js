import axios from '@/utils/axios';

// Get push empty
export const getHrs = async(params = {}) => {
  return await axios.get('/hr', { params });
};

export const addHr = async(params) => {
  return await axios.post('hr-organization', params);
};

export const downloadHrCsv = async(params) => {
  return await axios.post('/hr/download', params, { responseType: 'blob' });
};

export const checkFileImportHr = async(params) => {
  return await axios.post(`/hr/check-file-import`, params);
};

export const ImportHrCSV = async(params) => {
  return await axios.post(`/hr/import`, params);
};

export const hideHr = async(params) => {
  return await axios.post(`/hr/hide`, params);
};

export const getDetailsHr = async(id) => {
  return await axios.get(`/hr/${id}`);
};

export const getJobTypeHr = async(params = {}) => {
  return await axios.get('/job-type', { params });
};

export const userAddFavorite = async(params) => {
  return await axios.post(`/user-favorite`, params);
};

export const destroyFavorite = async(query) => {
  return await axios.delete(`/user-favorite/delete?${query}`);
};

export const offerJob = async(params) => {
  return await axios.post(`/offer`, params);
};

export const destroyHr = async(id) => {
  return await axios.delete(`hr-organization/${id}`);
};

export const detailsHr = async(id) => {
  return await axios.get(`hr-organization/${id}`);
};

export const updateHr = async(params = {}) => {
  return await axios.put(`hr-organization/${params.id}`, params);
};
