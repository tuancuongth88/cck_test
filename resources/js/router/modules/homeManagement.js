import Layout from '@/layout';

const homeManagement = {
  path: '/home',
  name: 'HomeManagement',
  meta: {
    title: 'TAB_HOME_MANAGEMENT',
    breadcrumb: 'BREADCRUMB_HOME_MANAGEMENT',
    role: [1, 2, 3, 4, 5],
    // permissions: [1, 2],
  },
  component: Layout,
  redirect: { name: 'Home' },
  children: [
    {
      path: 'index',
      name: 'Home',
      meta: {
        title: 'ROUTER_COMPANY_MANAGEMENT_LIST',
        breadcrumb: 'BREADCRUMB_COMPANY_MANAGEMENT_LIST',
      },
      component: () => import('@/pages/Home/index.vue'),
    },
    {
      path: 'distribute',
      name: 'Distribute',
      meta: {
        title: 'ROUTER_COMPANY_MANAGEMENT_LIST',
        breadcrumb: 'BREADCRUMB_COMPANY_MANAGEMENT_LIST',
      },
      component: () => import('@/pages/Home/distribute.vue'),
    },
    {
      path: 'detail',
      name: 'Detail',
      meta: {
        title: 'ROUTER_COMPANY_MANAGEMENT_DETAIL',
        breadcrumb: 'BREADCRUMB_COMPANY_MANAGEMENT_DETAIL',
      },
      component: () => import('@/pages/Home/detail.vue'),
    },
    {
      path: 'entry',
      name: 'Entry',
      meta: {
        title: 'ROUTER_COMPANY_MANAGEMENT_DETAIL',
        breadcrumb: 'BREADCRUMB_COMPANY_MANAGEMENT_DETAIL',
      },
      component: () => import('@/pages/Home/entry.vue'),
    },
    {
      path: 'interview-schedule',
      name: 'InterviewSchedule',
      meta: {
        title: 'ROUTER_COMPANY_MANAGEMENT_DETAIL',
        breadcrumb: 'BREADCRUMB_COMPANY_MANAGEMENT_DETAIL',
      },
      component: () => import('@/pages/Home/interviewSchedule.vue'),
    },
    {
      path: 'interview-zoom',
      name: 'InterviewZoom',
      meta: {
        title: 'ROUTER_COMPANY_MANAGEMENT_DETAIL',
        breadcrumb: 'BREADCRUMB_COMPANY_MANAGEMENT_DETAIL',
      },
      component: () => import('@/pages/Home/interviewZoom.vue'),
    },
    {
      path: 'detail-msg/:id',
      name: 'DetailMessage',
      meta: {
        title: 'ROUTER_COMPANY_MANAGEMENT_DETAIL',
        breadcrumb: 'BREADCRUMB_COMPANY_MANAGEMENT_DETAIL',
      },
      component: () => import('@/pages/Home/detailMsg.vue'),
    },
  ],
};

export default homeManagement;
