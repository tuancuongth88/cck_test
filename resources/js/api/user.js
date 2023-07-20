import axios from '@/utils/axios';

// /api/update-status-company
export const updateStatus = async(params) => {
  return await axios.post(`update-status-company`, params);
};

// Get notify  /api/notify
export const listNoti = async(params = {}) => {
  return await axios.get('notify', { params });
};

// Get notify detail /api/notification/{id}
export const detailNoti = async(id) => {
  return await axios.get(`notification/${id}`);
};

// Update Notification /api/notification/{id}
export const updateNoti = async(id) => {
  return await axios.put(`notification/${id}`);
};
