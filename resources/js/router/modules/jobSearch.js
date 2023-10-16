import Layout from '@/layout';
import ROLE from '@/const/role.js';

const JobSearch = {
  path: '/job-search',
  name: 'JobSearch',
  // hidden: true,
  meta: {
    title: 'TAB_JOB_SEARCH',
    breadcrumb: 'BREADCRUMB_COMPANY_MANAGEMENT',
    role: [ROLE.TYPE_HR, ROLE.TYPE_HR_MANAGER], // quyền 1.Super admin cũng được truy cập vào router này
  },
  component: Layout,
  redirect: { name: 'JobList' },
  children: [
    // Quyền HR - Search Job Cha - search (Thủ)
    {
      path: '',
      name: 'JobSearch',
      meta: {
        title: 'ROUTER_COMPANY_MANAGEMENT_EDIT',
        breadcrumb: 'BREADCRUMB_COMPANY_MANAGEMENT_EDIT',
        role: [ROLE.TYPE_HR, ROLE.TYPE_HR_MANAGER, ROLE.TYPE_SUPER_ADMIN],
      },
      component: () => import('@/pages/JobSearch/search.vue'),
    },
    // Danh sách job (Sơn)
    {
      path: 'list',
      name: 'JobSearchList',
      meta: {
        title: 'ROUTER_COMPANY_MANAGEMENT_EDIT',
        breadcrumb: 'BREADCRUMB_COMPANY_MANAGEMENT_EDIT',
        role: [ROLE.TYPE_HR, ROLE.TYPE_HR_MANAGER, ROLE.TYPE_SUPER_ADMIN],
      },
      component: () => import('@/pages/JobSearch/index.vue'),
    },
    // Detail 1 job
    {
      path: 'detail/:id',
      name: 'JobDetailForHr',
      meta: {
        title: 'ROUTER_JOB_DETAIL_FOR_HR',
        breadcrumb: 'BREADCRUMB_JOB_DETAIL_FOR_HR',
        role: [ROLE.TYPE_HR, ROLE.TYPE_HR_MANAGER, ROLE.TYPE_SUPER_ADMIN],
      },
      component: () => import('@/pages/JobSearch/JobDetailForHr'),
    },
    {
      path: 'select-entry-hr',
      name: 'SelectEntryHr',
      meta: {
        title: 'ROUTER_JOB_ENTRY',
        breadcrumb: 'BREADCRUMB_JOB_ENTRY',
        role: [ROLE.TYPE_HR, ROLE.TYPE_HR_MANAGER, ROLE.TYPE_SUPER_ADMIN],
      },
      component: () => import('@/pages/JobSearch/JobEntry/index'),
    },
    {
      path: 'job-information-entry',
      name: 'JobInformationEntry',
      meta: {
        title: 'ROUTER_JOB_ENTRY',
        breadcrumb: 'BREADCRUMB_JOB_ENTRY',
        role: [ROLE.TYPE_HR, ROLE.TYPE_HR_MANAGER, ROLE.TYPE_SUPER_ADMIN],
      },
      component: () => import('@/pages/JobSearch/JobEntry/information'),
    },
    {
      path: 'job-confirmation-entry',
      name: 'JobConfirmationEntry',
      meta: {
        title: 'ROUTER_JOB_ENTRY',
        breadcrumb: 'BREADCRUMB_JOB_ENTRY',
        role: [ROLE.TYPE_HR, ROLE.TYPE_HR_MANAGER, ROLE.TYPE_SUPER_ADMIN],
      },
      component: () => import('@/pages/JobSearch/JobEntry/confirmation'),
    },
    {
      path: 'job-information-entry-complete',
      name: 'JobEntryComplete',
      meta: {
        title: 'ROUTER_JOB_ENTRY',
        breadcrumb: 'BREADCRUMB_JOB_ENTRY',
        role: [ROLE.TYPE_HR, ROLE.TYPE_HR_MANAGER, ROLE.TYPE_SUPER_ADMIN],
      },
      component: () => import('@/pages/JobSearch/JobEntry/complete'),
    },
  ],
};

export default JobSearch;
