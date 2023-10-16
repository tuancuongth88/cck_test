import Layout from '@/layout';
import ROLE from '@/const/role.js';

const homeManagement = {
  path: '/home',
  name: 'HomeManagement',
  meta: {
    title: 'TAB_HOME_MANAGEMENT',
    breadcrumb: 'BREADCRUMB_HOME_MANAGEMENT',
    role: [ROLE.TYPE_SUPER_ADMIN, ROLE.TYPE_COMPANY_ADMIN, ROLE.TYPE_HR_MANAGER, ROLE.TYPE_COMPANY, ROLE.TYPE_HR],
  },
  component: Layout,
  redirect: { name: 'Home' },
  children: [
    {
      path: '',
      name: 'Home',
      meta: {
        title: 'ROUTER_COMPANY_MANAGEMENT_LIST',
        breadcrumb: 'BREADCRUMB_COMPANY_MANAGEMENT_LIST',
        role: [ROLE.TYPE_SUPER_ADMIN, ROLE.TYPE_COMPANY_ADMIN, ROLE.TYPE_HR_MANAGER, ROLE.TYPE_COMPANY, ROLE.TYPE_HR],
      },
      component: () => import('@/pages/Home/index.vue'),
    },
    {
      path: 'distribute',
      name: 'Distribute',
      meta: {
        title: 'ROUTER_COMPANY_MANAGEMENT_LIST',
        breadcrumb: 'BREADCRUMB_COMPANY_MANAGEMENT_LIST',
        role: [ROLE.TYPE_SUPER_ADMIN, ROLE.TYPE_COMPANY_ADMIN, ROLE.TYPE_HR_MANAGER, ROLE.TYPE_COMPANY, ROLE.TYPE_HR],
      },
      component: () => import('@/pages/Home/DistributeMsg/DistributeMsgCreate.vue'),

    },
    {
      path: 'detail',
      name: 'Detail',
      meta: {
        title: 'ROUTER_COMPANY_MANAGEMENT_DETAIL',
        breadcrumb: 'BREADCRUMB_COMPANY_MANAGEMENT_DETAIL',
        role: [ROLE.TYPE_SUPER_ADMIN, ROLE.TYPE_COMPANY_ADMIN, ROLE.TYPE_HR_MANAGER, ROLE.TYPE_COMPANY, ROLE.TYPE_HR],
      },
      component: () => import('@/pages/Home/detail.vue'),
    },
    {
      path: 'entry',
      name: 'Entry',
      meta: {
        title: 'ROUTER_COMPANY_MANAGEMENT_DETAIL',
        breadcrumb: 'BREADCRUMB_COMPANY_MANAGEMENT_DETAIL',
        role: [ROLE.TYPE_SUPER_ADMIN, ROLE.TYPE_COMPANY_ADMIN, ROLE.TYPE_HR_MANAGER, ROLE.TYPE_COMPANY, ROLE.TYPE_HR],
      },
      component: () => import('@/pages/Home/entry.vue'),
    },
    {
      path: 'interview-schedule',
      name: 'InterviewSchedule',
      meta: {
        title: 'ROUTER_COMPANY_MANAGEMENT_DETAIL',
        breadcrumb: 'BREADCRUMB_COMPANY_MANAGEMENT_DETAIL',
        role: [ROLE.TYPE_SUPER_ADMIN, ROLE.TYPE_COMPANY_ADMIN, ROLE.TYPE_HR_MANAGER, ROLE.TYPE_COMPANY, ROLE.TYPE_HR],
      },
      component: () => import('@/pages/Home/interviewSchedule.vue'),
    },
    {
      path: 'interview-zoom',
      name: 'InterviewZoom',
      meta: {
        title: 'ROUTER_COMPANY_MANAGEMENT_DETAIL',
        breadcrumb: 'BREADCRUMB_COMPANY_MANAGEMENT_DETAIL',
        role: [ROLE.TYPE_SUPER_ADMIN, ROLE.TYPE_COMPANY_ADMIN, ROLE.TYPE_HR_MANAGER, ROLE.TYPE_COMPANY, ROLE.TYPE_HR],
      },
      component: () => import('@/pages/Home/interviewZoom.vue'),
    },
    {
      path: 'detail-msg',
      name: 'DetailMessage',
      meta: {
        title: 'ROUTER_COMPANY_MANAGEMENT_DETAIL',
        breadcrumb: 'BREADCRUMB_COMPANY_MANAGEMENT_DETAIL',
        role: [ROLE.TYPE_SUPER_ADMIN, ROLE.TYPE_COMPANY_ADMIN, ROLE.TYPE_HR_MANAGER, ROLE.TYPE_COMPANY, ROLE.TYPE_HR],
      },
      component: () => import('@/pages/Home/detailMsg.vue'),
    },
    {
      path: 'show-more-msg/:id',
      name: 'ShowMore',
      meta: {
        title: 'Show More MSG',
        breadcrumb: 'Show_More_MSG',
        role: [ROLE.TYPE_SUPER_ADMIN, ROLE.TYPE_COMPANY_ADMIN, ROLE.TYPE_HR_MANAGER, ROLE.TYPE_COMPANY, ROLE.TYPE_HR],
      },
      component: () => import('@/pages/Home/ShowMore/showMoreMsg.vue'),
    },
    {
      path: 'show-more-distribution-msg',
      name: 'ShowMoreDistributionMsg',
      meta: {
        title: 'Show More distribution msg',
        breadcrumb: 'Show_More_Distribution_Msg',
        role: [ROLE.TYPE_SUPER_ADMIN, ROLE.TYPE_COMPANY_ADMIN, ROLE.TYPE_HR_MANAGER, ROLE.TYPE_COMPANY, ROLE.TYPE_HR],
      },
      component: () => import('@/pages/Home/ShowMore/showMoreDistributeMsg.vue'),
    },
  ],
};

export default homeManagement;

