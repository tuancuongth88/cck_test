import axios from '@/utils/axios';

export const getListMainjob = async(params = {}) => {
  return await axios.get('job-type?type=1', { params });
};
// List job for HR - /api/work
export const listJob = async(params) => {
  return await axios.get('work', { params });
};
// Add to favorite /api/user-favorite
export const addFavoriteJob = async(params) => {
  // ?relation_id=18&type=2
  return await axios.post(`user-favorite?relation_id=${params.relation_id}&type=${params.type}`, params);
};
// Remove to favorite /api/user-favorite/delete
export const removeFavoriteJob = async(params) => {
  return await axios.delete(`user-favorite/delete?relation_id=${params.relation_id}&type=${params.type}`, params);
};
// Delete Job for company  /api/work/{id}
export const deleteJob = async(params = {}) => {
  return await axios.delete(`/work`, { params });
};

export const getListEduCourse = async(params = {}) => {
  return await axios.get('job-type?type=2', { params });
};

export const getListCountry = async(params = {}) => {
  return await axios.get('/city', { params });
};

export const updateStatusWork = async(params = {}) => {
  return await axios.post(`/work/update-status-work/${params.id}`, { status: params.status });
};
