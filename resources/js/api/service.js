import axios from 'axios';
import i18n from '../lang';
import store from '../store';
import router from '../router';
import constAuth from '../const/auth';

import { getToken } from '../utils/handleToken';
import { getLanguage } from '../utils/getLang';
import { MakeToast } from '../utils/toastMessage';

const baseURL = process.env.MIX_BASE_API;

const service = axios.create({
  baseURL: baseURL,
  timeout: 100000,
});

service.interceptors.request.use(
  config => {
    const token = getToken();

    const ResetPass = router.currentRoute.path !== '/reset-password';
    const HumanResourseRegister = router.currentRoute.path !== '/human-resourse-register';
    const HumanResourseRegisterDetail = router.currentRoute.path !== '/human-resourse-register-detail';
    const RumanResourseRegisterSuccess = router.currentRoute.path !== '/human-resourse-register-success';
    const CompanyResourseRegister = router.currentRoute.path !== '/company-register';
    const CompanyResourseRegisterDetail = router.currentRoute.path !== '/company-resourse-register-detail';
    const CompanyResourseRegisterSuccess = router.currentRoute.path !== '/company-resourse-register-success';

    config.headers['Accept-Language'] = getLanguage();

    if (token) {
      config.headers['Authorization'] = token;
    } else {
      if (router.currentRoute.path !== '/login' || ResetPass || HumanResourseRegister || HumanResourseRegisterDetail || RumanResourseRegisterSuccess || CompanyResourseRegister || CompanyResourseRegisterDetail || CompanyResourseRegisterSuccess) {
        console.log('service');
        router.push({ path: '/login' });
      }
    }

    return config;
  },
  error => {
    Promise.reject(error);
  }
);

service.interceptors.response.use(
  response => {
    const USER_NOT_FOUND = constAuth.USER_NOT_FOUND;

    if (JSON.stringify(USER_NOT_FOUND) === JSON.stringify(response.data)) {
      store.dispatch('user/doLogout')
        .then(() => {
          router.push('/login');
        })
        .catch(() => {
          MakeToast({
            variant: 'danger',
            title: i18n.$t('DANGER'),
            content: i18n.$t('TOAST_HAVE_ERROR'),
          });
        });
    }

    return response.data;
  },
  error => {
    const isCheckTitle = i18n.te(error.response.data.title);
    const isCheckContent = i18n.te(error.response.data.message);

    if (isCheckTitle && isCheckContent) {
      MakeToast({
        variant: 'warning',
        title: i18n.t(error.response.data.title),
        content: i18n.t(error.response.data.message),
      });
    }

    return Promise.reject(error);
  }
);

export { service };
