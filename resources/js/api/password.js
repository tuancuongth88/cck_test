import axios from '@/utils/axios';

export const listPassword = async(params = {}) => {
  return await axios.get('/customer', { params });
};

// Change password
// Bước 1: Xác nhận mật khẩu hiện tại /api/auth/confirm-password
export const changePassConfirmPost = async(params) => {
  return await axios.post('auth/confirm-password', params);
};

// Bước 2: Gửi changepass mới
export const ChangeNewPasswordPut = async(params) => {
  return await axios.put(`auth/change-password`, params);
};

// Reset Password
// Bước 1: POST /api/auth/forget-password
export const ResetPassSendEmailPost = async(params) => {
  return await axios.post('auth/forget-password', params);
};
// Bước 2: PUT /api/auth/password-reset
export const ResetPassConfirmPut = async(params) => {
  return await axios.put(`auth/password-reset`, params);
};

export const detailsPassword = async(id) => {
  return await axios.get(`customer/${id}`);
};

