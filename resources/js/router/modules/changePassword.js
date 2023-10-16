import ROLE from '@/const/role.js';

const changePassWord = {
  path: '/change-password',
  // name: 'ChangePassWord',
  meta: {
    title: 'CHANGE_PASSWORD_TITLE',
    breadcrumb: 'CHANGE_PASSWORD_TITLE',
    role: [ROLE.TYPE_SUPER_ADMIN, ROLE.TYPE_COMPANY_ADMIN, ROLE.TYPE_HR_MANAGER, ROLE.TYPE_COMPANY, ROLE.TYPE_HR],
  },
  hidden: true,
  component: () => import('@/pages/PassWords/ChangePassWord/index.vue'),
  // redirect: { name: 'ChangePassWord' },
};

export default changePassWord;
