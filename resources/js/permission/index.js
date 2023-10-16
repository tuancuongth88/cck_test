import { getToken, getUserInfo } from '@/utils/handleToken';
import router, { resetRouter } from '@/router';
import { getCurrentRoutes } from '@/utils/handleRole';
import getPageTitle from '@/utils/getPageTitle';
import { MakeToast } from '../utils/toastMessage';
import store from '../store';
import i18n from '@/lang';

const whiteList = [
  '/login',
  '/forget-password',
  '/human-resourse-register',
  '/company-register',
  '/api/auth/password-reset',
];

let toastShowed = false;

router.beforeEach(async(to, from, next) => {
  if (from.name) {
    const NONSAVE_ROUTER = [
      'HrDetail',
      'HrEdit',
      'JobDetail',
      'JobEdit',
      'JobDetailForHr',
      'SelectEntryHr',
      'JobInformationEntry',
      'JobConfirmationEntry',
      'JobConfirmationEntry',
      'JobEntryComplete',
    ];
    if (!NONSAVE_ROUTER.includes(to.name)) {
      const revert_router = {
        name: to.name,
        path: to.path,
      };
      store.dispatch('routerParam/setRevertRouter', revert_router);
    }
  }
  document.title = getPageTitle(to.meta.title);

  const TOKEN = getToken();
  const USERINFO = getUserInfo();
  const currentRoute = getCurrentRoutes();

  if (TOKEN) {
    if (to.path === '/login') {
      next({ path: '/' });
    } else {
      const userType = USERINFO.type;
      const requiresType = to.meta.role;
      if (requiresType) {
        if (Array.isArray(requiresType)) {
          // Nếu requiresType là một mảng, kiểm tra xem userType có nằm trong mảng đó hay không
          if (requiresType.includes(userType)) {
            next();
          } else {
            if (!toastShowed) {
              MakeToast({
                variant: 'warning',
                title: 'warning',
                content: i18n.t('NOT_PERMISSION'),
              });
              toastShowed = true;
              // Nếu userType không nằm trong mảng requiresType, chuyển hướng đến trang lỗi hoặc trang khác tùy ý
              // next('/not-permission');
            }
            next(currentRoute);
          }
        } else {
          // Nếu requiresType là một giá trị đơn, kiểm tra xem userType có bằng giá trị đó hay không
          if (userType === requiresType) {
            next();
          } else {
            // Nếu userType không bằng requiresType, chuyển hướng đến trang lỗi hoặc trang khác tùy ý
            // next('/not-permission');
            MakeToast({
              variant: 'warning',
              title: 'warning',
              content: i18n.t('NOT_PERMISSION'),
            });
            next(currentRoute);
          }
        }
      } else {
        next();
      }
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
