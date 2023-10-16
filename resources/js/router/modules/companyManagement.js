import Layout from '@/layout';
import ROLE from '@/const/role.js';

const companyManagement = {
  path: '/company',
  name: 'CompanyManagement',
  meta: {
    title: 'TAB_COMPANY_MANAGEMENT',
    breadcrumb: 'BREADCRUMB_COMPANY_MANAGEMENT',
    role: [ROLE.TYPE_SUPER_ADMIN, ROLE.TYPE_COMPANY_ADMIN],
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
        role: [ROLE.TYPE_SUPER_ADMIN, ROLE.TYPE_COMPANY_ADMIN],
      },
      component: () => import('@/pages/CompanyManagement/Company/index.vue'),
    },
    {
      path: 'detail/:id',
      name: 'CompanyDetail',
      meta: {
        title: 'ROUTER_COMPANY_MANAGEMENT_DETAIL',
        breadcrumb: 'BREADCRUMB_COMPANY_MANAGEMENT_DETAIL',
        role: [ROLE.TYPE_SUPER_ADMIN, ROLE.TYPE_COMPANY_ADMIN, ROLE.TYPE_COMPANY],
      },
      component: () => import('@/pages/CompanyManagement/Company/form.vue'),
    },
    {
      path: 'edit/:id',
      name: 'CompanyEdit',
      meta: {
        title: 'ROUTER_COMPANY_MANAGEMENT_EDIT',
        breadcrumb: 'BREADCRUMB_COMPANY_MANAGEMENT_EDIT',
        role: [ROLE.TYPE_SUPER_ADMIN, ROLE.TYPE_COMPANY_ADMIN, ROLE.TYPE_COMPANY],
      },
      component: () => import('@/pages/CompanyManagement/Company/form.vue'),
    },
  ],
};

export default companyManagement;
