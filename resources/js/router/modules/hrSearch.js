import Layout from '@/layout';

const hrSearch = {
  path: '/hr-search',
  name: 'HrSearch',
  meta: {
    title: 'TAB_HR_SEARCH',
    breadcrumb: 'BREADCRUMB_COMPANY_MANAGEMENT',
    role: [2, 4],
  },
  component: Layout,
  redirect: { name: 'HrSearchIndex' },
  children: [
    // Quyền HR - Search HR Cha - search ()
    {
      path: 'index',
      name: 'HrSearchIndex',
      meta: {
        title: 'ROUTER_COMPANY_MANAGEMENT_EDIT',
        breadcrumb: 'BREADCRUMB_COMPANY_MANAGEMENT_EDIT',
      },
      component: () => import('@/pages/HrSearch/search.vue'),
    },
    {
      path: 'search-result',
      name: 'HrSearchList',
      meta: {
        title: 'ROUTER_COMPANY_MANAGEMENT_EDIT',
        breadcrumb: 'BREADCRUMB_COMPANY_MANAGEMENT_EDIT',
      },
      component: () => import('@/pages/HrSearch/list.vue'),
    },
  ],
};

export default hrSearch;
