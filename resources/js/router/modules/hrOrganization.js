import Layout from '@/layout';
import ROLE from '@/const/role.js';

const hrOrganization = {
  path: '/hr-organization',
  name: 'HrOrganization',
  // hidden: true,
  meta: {
    title: 'ROUTER_HR_MANAGEMENT',
    breadcrumb: 'BREADCRUMB_HR_MANAGEMENT',
    role: [ROLE.TYPE_SUPER_ADMIN, ROLE.TYPE_HR_MANAGER],
  },
  component: Layout,
  redirect: { name: 'HrOrganizationList' },
  children: [
    // List
    {
      path: 'list',
      name: 'HrOrganizationList',
      meta: {
        title: 'ROUTER_HR_MANAGEMENT_LIST',
        breadcrumb: 'BREADCRUMB_HR_MANAGEMENT_LIST',
        role: [ROLE.TYPE_SUPER_ADMIN, ROLE.TYPE_HR_MANAGER],
      },
      component: () => import('@/pages/HrOrganization/index.vue'),
    },
    // Detail vs ID
    {
      path: 'detail/:id',
      name: 'HrOrganizationDetail',
      meta: {
        title: 'ROUTER_HR_MANAGEMENT_DETAIL',
        breadcrumb: 'BREADCRUMB_HR_MANAGEMENT_DETAIL',
        role: [ROLE.TYPE_SUPER_ADMIN, ROLE.TYPE_HR_MANAGER, ROLE.TYPE_HR],
      },
      component: () => import('@/pages/HrOrganization/detail.vue'),

    },
    {
      path: 'edit/:id',
      name: 'HrOrganizationEdit',
      meta: {
        title: 'ROUTER_HR_MANAGEMENT_EDIT',
        breadcrumb: 'BREADCRUMB_HR_MANAGEMENT_EDIT',
        role: [ROLE.TYPE_SUPER_ADMIN, ROLE.TYPE_HR_MANAGER, ROLE.TYPE_HR],
      },
      component: () => import('@/pages/HrOrganization/edit.vue'),
    },
    //
  ],
};

export default hrOrganization;
