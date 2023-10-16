import axios from '@/utils/axios';

// Search form
export const getOptionHrOrganization = async(params = {}) => {
  return await axios.get('/hr-org', { params });
};

// Entry
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

// interview
export const getListInterview = async(params = {}) => {
  return await axios.get('/interview', { params });
};

export const deleteMultipleInterview = async(params = {}) => {
  return await axios.post('/interview/hide', params);
};

export const putSetupCalender = async(params = {}) => {
  return await axios.put(`/interview/setup-calendar/${params.id}`, params.calender);
};

export const putConfirmCalender = async(params = {}) => {
  return await axios.put(`/interview/confirm-calendar/${params.id}`, params);
};

export const putDeclineInterview = async(params = {}) => {
  return await axios.put(`/interview/confirm-interview-hr-decline/${params.id}`, params);
};

export const putCancelInterview = async(params = {}) => {
  return await axios.put(`/interview/confirm-interview-company-cancel/${params.id}`, params);
};

export const putSetupZoom = async(params = {}) => {
  return await axios.put(`/interview/setup-zoom/${params.id}`, params);
};

export const putConfirmReview = async(params = {}) => {
  return await axios.put(`/interview/confirm-interview-company-review/${params.id}`, params);
};

export const getDetailInterview = async(id) => {
  return await axios.get(`/interview/${id}`);
};

// result
export const getListResult = async(params = {}) => {
  return await axios.get('/result', { params });
};

export const deleteMultipleResult = async(params = {}) => {
  return await axios.post('/result/hide', params);
};

export const getDetailResult = async(id) => {
  return await axios.get(`/result/${id}`);
};

export const updateResult = async(params = {}) => {
  return await axios.put(`/result/${params.id}`, params);
};
