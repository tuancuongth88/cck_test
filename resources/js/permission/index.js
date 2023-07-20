import { getToken } from '@/utils/handleToken';
import router, { resetRouter } from '@/router';

import getPageTitle from '@/utils/getPageTitle';

const whiteList = [
  '/login',
  '/reset-password-send-email', '/reset-password-sent-email',
  '/api/auth/password-reset', '/reset-password-done',
  '/change-password', '/change-password-confirm', '/change-password-done',
  '/human-resourse-register', '/human-resourse-register-detail', '/human-resourse-register-success',
  '/company-register', '/company-preview', '/company-register-success',
];

router.beforeEach(async(to, from, next) => {
  document.title = getPageTitle(to.meta.title);

  const TOKEN = getToken();

  if (TOKEN) {
    if (to.path === '/login') {
      next({ path: '/' });
    } else {
      next();
    }
  } else {
    resetRouter();

    if (whiteList.indexOf(to.matched[0] ? to.matched[0].path : '') !== -1) {
      next();
    } else {
      next(`/login`);
    }
  }
});
