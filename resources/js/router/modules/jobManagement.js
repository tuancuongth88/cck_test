import Layout from '@/layout';

const jobManagement = {
  path: '/job',
  name: 'JobManagement',
  // hidden: true,
  meta: {
    title: 'TAB_JOB_LIST',
    breadcrumb: 'BREADCRUMB_COMPANY_MANAGEMENT',
    role: [1, 2, 4],
  },
  component: Layout,
  redirect: { name: 'JobList' },
  children: [
    {
      path: 'list',
      name: 'JobList',
      meta: {
        title: 'ROUTER_COMPANY_MANAGEMENT_LIST',
        breadcrumb: 'BREADCRUMB_COMPANY_MANAGEMENT_LIST',
      },
      component: () => import('@/pages/CompanyManagement/Job/index.vue'),
    },
    {
      path: 'create',
      name: 'JobCreate',
      meta: {
        title: 'ROUTER_COMPANY_MANAGEMENT_CREATE',
        breadcrumb: 'BREADCRUMB_COMPANY_MANAGEMENT_CREATE',
      },
      component: () => import('@/pages/CompanyManagement/Job/create.vue'),
    },
    {
      path: 'detail/:id',
      name: 'JobDetail',
      meta: {
        title: 'ROUTER_COMPANY_MANAGEMENT_DETAIL',
        breadcrumb: 'BREADCRUMB_COMPANY_MANAGEMENT_DETAIL',
      },
      component: () => import('@/pages/CompanyManagement/Job/detail.vue'),
    },
    {
      path: 'edit/:id',
      name: 'JobEdit',
      meta: {
        title: 'ROUTER_COMPANY_MANAGEMENT_EDIT',
        breadcrumb: 'BREADCRUMB_COMPANY_MANAGEMENT_EDIT',
      },
      component: () => import('@/pages/CompanyManagement/Job/edit.vue'),
    },
  ],
};

export default jobManagement;
