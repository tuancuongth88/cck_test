import Layout from '@/layout';
import ROLE from '../../const/role';

const jobManagement = {
  path: '/job',
  name: 'JobManagement',
  // hidden: true,
  meta: {
    title: 'TAB_JOB_LIST',
    breadcrumb: 'BREADCRUMB_COMPANY_MANAGEMENT',
    role: [ROLE.TYPE_SUPER_ADMIN, ROLE.TYPE_COMPANY_ADMIN, ROLE.TYPE_COMPANY],
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
        role: [ROLE.TYPE_SUPER_ADMIN, ROLE.TYPE_COMPANY_ADMIN, ROLE.TYPE_COMPANY],
      },
      component: () => import('@/pages/CompanyManagement/Job/index.vue'),
    },
    {
      path: 'create',
      name: 'JobCreate',
      meta: {
        title: 'ROUTER_COMPANY_MANAGEMENT_CREATE',
        breadcrumb: 'BREADCRUMB_COMPANY_MANAGEMENT_CREATE',
        role: [ROLE.TYPE_SUPER_ADMIN, ROLE.TYPE_COMPANY_ADMIN, ROLE.TYPE_COMPANY],
      },
      component: () => import('@/pages/CompanyManagement/Job/create.vue'),
    },
    {
      path: 'detail/:id',
      name: 'JobDetail',
      meta: {
        title: 'ROUTER_COMPANY_MANAGEMENT_DETAIL',
        breadcrumb: 'BREADCRUMB_COMPANY_MANAGEMENT_DETAIL',
        role: [ROLE.TYPE_SUPER_ADMIN, ROLE.TYPE_COMPANY_ADMIN, ROLE.TYPE_COMPANY],
      },
      component: () => import('@/pages/CompanyManagement/Job/detail.vue'),
    },
    {
      path: 'edit/:id',
      name: 'JobEdit',
      meta: {
        title: 'ROUTER_COMPANY_MANAGEMENT_EDIT',
        breadcrumb: 'BREADCRUMB_COMPANY_MANAGEMENT_EDIT',
        role: [ROLE.TYPE_SUPER_ADMIN, ROLE.TYPE_COMPANY_ADMIN, ROLE.TYPE_COMPANY],
      },
      component: () => import('@/pages/CompanyManagement/Job/edit.vue'),
    },
  ],
};

export default jobManagement;
