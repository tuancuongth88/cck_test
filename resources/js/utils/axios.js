import axios from 'axios';
import jwt_decode from 'jwt-decode';
import moment from 'moment';
import router from '../router/index';
import EventBus from '@/utils/eventBus';
import { getToken, destroyToken, destroyUserInfoCookie } from '@/utils/jwt';

const baseURL = process.env.MIX_BASE_API;
const defaultLocale = process.env.MIX_LARAVEL_LANG;

const service = axios.create({
  baseURL: baseURL,
  timeout: 100000,
});

const token = getToken();
const decodeToken = token ? jwt_decode(token) : {};
const currentTime = moment().format('X');
if (decodeToken && decodeToken.exp < Number(currentTime)) {
  destroyToken();
  destroyUserInfoCookie();
  window.location.href = '/login';
}

// if (token) {
//   service.defaults.headers.common['Authorization'] = token;
// }
// service.defaults.headers.post['Content-Type'] = 'application/json';

service.interceptors.request.use(
  config => {
    const token = getToken();
    const hrOrgRegister = router.currentRoute.path === '/human-resourse-register';
    const companyRegister = router.currentRoute.path === '/company-register';
    config.headers['Accept-Language'] = defaultLocale;

    if (token) {
      config.headers['Authorization'] = token;
    } else if (!token && companyRegister) {
      router.push({ path: '/company-register' });
    } else if (!token && hrOrgRegister) {
      router.push({ path: '/human-resourse-register' });
    } else {
      router.push({ path: '/login' });
    }
    return config;
  },
  error => {
    return Promise.reject(error);
  }
);
service.interceptors.response.use(
  response => {
    return response;
  },
  error => {
    const status = error.response.status;
    if (error.response.data.code && error.response.data.code === 403) {
      console.log('Enter error 403');
      EventBus.$emit('responseErrorCode', error.response.data.code);
      // this.$bus.emit('responseErrorCode', error.response.data.code);
    }

    EventBus.$emit('responseStatusCode', status);
    // this.$bus.emit('responseStatusCode', status);
    return Promise.reject(error);
  }
);

export default service;
