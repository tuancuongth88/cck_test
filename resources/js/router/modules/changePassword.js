import Layout from '@/layout';

const changePassword = {
  path: '/change-password',
  name: 'CompanyManagement',
  meta: {
    title: 'Change password',
    breadcrumb: 'Change password',
  },
  component: Layout,
  redirect: { name: 'Company' },
  children: [
    {
      path: 'list',
      name: 'Company',
      meta: {
        title: 'ROUTER_COMPANY_MANAGEMENT_LIST',
        breadcrumb: 'BREADCRUMB_COMPANY_MANAGEMENT_LIST',
      },
      component: () => import('@/pages/ChangePassword/changePassword.vue'),
    },
  ],
};

export default changePassword;
