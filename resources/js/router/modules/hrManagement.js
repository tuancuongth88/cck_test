import Layout from '@/layout';

const hrManagement = {
  path: '/hr',
  name: 'HRManagement',
  meta: {
    title: 'ROUTER_HR_LIST',
    breadcrumb: 'BREADCRUMB_HR_MANAGEMENT',
    // permissions: [1, 2, 3],
    role: [1, 3, 5],
  },
  component: Layout,
  redirect: { name: 'Hr' },
  children: [
    // Register
    {
      path: 'create',
      name: 'HrCreate',
      meta: {
        title: 'ROUTER_HR_MANAGEMENT_CREATE',
        breadcrumb: 'BREADCRUMB_HR_MANAGEMENT_CREATE',
      },
      component: () => import('@/pages/Hr/create.vue'),
    },
    // List
    {
      path: 'list',
      name: 'Hr',
      meta: {
        title: 'ROUTER_HR_MANAGEMENT_LIST',
        breadcrumb: 'BREADCRUMB_HR_MANAGEMENT_LIST',
      },
      component: () => import('@/pages/Hr/index.vue'),
    },
    // Detail vs ID
    {
      path: 'detail/:id',
      name: 'HrDetail',
      meta: {
        title: 'ROUTER_HR_MANAGEMENT_DETAIL',
        breadcrumb: 'BREADCRUMB_HR_MANAGEMENT_DETAIL',
      },
      component: () => import('../../pages/Hr/Detail'),

    },
    {
      path: 'edit/:id',
      name: 'HrEdit',
      meta: {
        title: 'ROUTER_HR_MANAGEMENT_EDIT',
        breadcrumb: 'BREADCRUMB_HR_MANAGEMENT_EDIT',
      },
      component: () => import('../../pages/Hr/Edit/HrEdit.vue'),
    },
    //
  ],
};

export default hrManagement;
