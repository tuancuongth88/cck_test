import Layout from '@/layout';
import ROLE from '@/const/role.js';

const hrSearch = {
  path: '/hr-search',
  name: 'HrSearch',
  meta: {
    title: 'TAB_HR_SEARCH',
    breadcrumb: 'BREADCRUMB_COMPANY_MANAGEMENT',
    role: [ROLE.TYPE_COMPANY_ADMIN, ROLE.TYPE_COMPANY],
  },
  component: Layout,
  redirect: { name: 'HrSearchIndex' },
  children: [
    // Quyền HR - Search HR Cha - search ()
    {
      path: '',
      name: 'HrSearchIndex',
      meta: {
        title: 'ROUTER_COMPANY_MANAGEMENT_EDIT',
        breadcrumb: 'BREADCRUMB_COMPANY_MANAGEMENT_EDIT',
        role: [ROLE.TYPE_COMPANY_ADMIN, ROLE.TYPE_COMPANY],
      },
      component: () => import('@/pages/HrSearch/search.vue'),
    },
    {
      path: 'list',
      name: 'HrSearchList',
      meta: {
        title: 'ROUTER_COMPANY_MANAGEMENT_EDIT',
        breadcrumb: 'BREADCRUMB_COMPANY_MANAGEMENT_EDIT',
        role: [ROLE.TYPE_COMPANY_ADMIN, ROLE.TYPE_COMPANY],
      },
      component: () => import('@/pages/HrSearch/list.vue'),
    },
  ],
};

export default hrSearch;
