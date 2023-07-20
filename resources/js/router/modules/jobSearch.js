import Layout from '@/layout';

const JobSearch = {
  path: '/job-search',
  name: 'JobSearch',
  // hidden: true,
  meta: {
    title: 'TAB_JOB_SEARCH',
    breadcrumb: 'BREADCRUMB_COMPANY_MANAGEMENT',
    role: [5, 3],
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
      },
      component: () => import('@/pages/JobSearch/JobDetailForHr'),
    },
    {
      path: 'job-entry',
      name: 'JobEntry',
      meta: {
        title: 'ROUTER_JOB_ENTRY',
        breadcrumb: 'BREADCRUMB_JOB_ENTRY',
      },
      component: () => import('@/pages/JobSearch/JobEntry/index'),
    },
    {
      path: 'job-information-entry',
      name: 'JobInformationEntry',
      meta: {
        title: 'ROUTER_JOB_ENTRY',
        breadcrumb: 'BREADCRUMB_JOB_ENTRY',
      },
      component: () => import('@/pages/JobSearch/JobEntry/information'),
    },
    {
      path: 'job-information-entry-complete',
      name: 'JobInformationEntryComplete',
      meta: {
        title: 'ROUTER_JOB_ENTRY',
        breadcrumb: 'BREADCRUMB_JOB_ENTRY',
      },
      component: () => import('@/pages/JobSearch/JobEntry/complete'),
    },
  ],
};

export default JobSearch;
