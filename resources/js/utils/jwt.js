import Cookies from 'js-cookie';
const ID_TOKEN_KEY = 'token';
const ID_USER_INFO_KEY = 'user_info';

export const getToken = () => {
  return Cookies.get(ID_TOKEN_KEY);
};

export const saveToken = (token) => {
  Cookies.set(ID_TOKEN_KEY, token, { expires: 7 });
};

export const destroyToken = () => {
  Cookies.remove(ID_TOKEN_KEY);
};

export const getUserInfoCookie = () => {
  return Cookies.get(ID_USER_INFO_KEY) ? JSON.parse(Cookies.get(ID_USER_INFO_KEY)) : {};
};

export const saveUserInfoCookie = (user) => {
  Cookies.set(ID_USER_INFO_KEY, JSON.stringify(user));
};

export const destroyUserInfoCookie = () => {
  Cookies.remove(ID_USER_INFO_KEY);
};

