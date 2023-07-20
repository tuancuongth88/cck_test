import axios from '@/utils/axios';

export const getListEntry = async(params = {}) => {
  return await axios.get('/entries', { params });
};
export const deleteMultipleEntry = async(params = {}) => {
  return await axios.post('/entry/hide', params);
};
export const getDetailEntry = async(id) => {
  return await axios.get(`/entry/${id}`);
};

export const updateEntry = async(params = {}) => {
  return await axios.post(`/entry/update-status/${params.id}`, params);
};

// offer
export const getListOffer = async(params = {}) => {
  return await axios.get('/offer', { params });
};

export const deleteMultipleOffer = async(params = {}) => {
  return await axios.post('/offer/remove-offer', params);
};

export const getDetailOffer = async(id) => {
  return await axios.get(`/offer/${id}`);
};

export const updateOffer = async(params = {}) => {
  return await axios.post(`/offer/update-status/${params.id}`, params);
};

// result
export const getListResult = async(params = {}) => {
  return await axios.get('/result', { params });
};

export const deleteMultipleResult = async(params = {}) => {
  return await axios.post('/result/hide', params);
};
