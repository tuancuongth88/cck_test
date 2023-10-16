import axios from '@/utils/axios';

// /api/update-status-company
export const updateStatus = async(params) => {
  return await axios.post(`update-status-company`, params);
};

// Get notify - DB User /api/notify
export const listNoti = async(params) => {
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

// Get notify Tab Other /api/notification/distribution/other
export const listDistribution = async(params) => {
  return await axios.get(`notification/distribution/other`, { params });
};

// GET   /api/user/unread-messages   Getunread-
export const getUnread = async(params) => {
  return await axios.get(`user/unread-messages`, { params });
};

// POST /api/notification - distribution create with img
export const addDistribution = async(params) => {
  return await axios.post('notification', params);
};

export const listGoingJob = async(params) => {
  return await axios.get(`user/on-going-job`, { params });
};

export const listFavourite = async(params) => {
  return await axios.get(`user-favorite`, { params });
};

export const removeFavourite = async(params) => {
  return await axios.delete(`user-favorite/delete?${params}`);
};

// export const updateFavouriteHr = async(id) => {
//   return await axios.put(`user-favorite/${id}`);
// };

// export const updateFavouriteJob = async(id) => {
//   return await axios.put(`user-favorite/${id}`);
// };
