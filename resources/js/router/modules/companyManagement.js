import Layout from '@/layout';

const companyManagement = {
  path: '/company',
  name: 'CompanyManagement',
  meta: {
    title: 'TAB_COMPANY_MANAGEMENT',
    breadcrumb: 'BREADCRUMB_COMPANY_MANAGEMENT',
    // permissions: [1, 2, 3],
    role: [1, 2],
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
      component: () => import('@/pages/CompanyManagement/Company/index.vue'),
    },
    {
      path: 'detail/:id',
      name: 'CompanyDetail',
      meta: {
        title: 'ROUTER_COMPANY_MANAGEMENT_DETAIL',
        breadcrumb: 'BREADCRUMB_COMPANY_MANAGEMENT_DETAIL',
      },
      component: () => import('@/pages/CompanyManagement/Company/form.vue'),
    },
    {
      path: 'edit/:id',
      name: 'CompanyEdit',
      meta: {
        title: 'ROUTER_COMPANY_MANAGEMENT_EDIT',
        breadcrumb: 'BREADCRUMB_COMPANY_MANAGEMENT_EDIT',
      },
      // component: () => import('@/pages/CompanyManagement/Company/edit.vue'),
      component: () => import('@/pages/CompanyManagement/Company/form.vue'),
    },
    {
      path: 'create',
      name: 'CompanyCreate',
      meta: {
        title: 'ROUTER_COMPANY_MANAGEMENT_CREATE',
        breadcrumb: 'BREADCRUMB_COMPANY_MANAGEMENT_CREATE',
      },
      component: () => import('@/pages/CompanyManagement/Company/create.vue'),
    },
  ],
};

export default companyManagement;
