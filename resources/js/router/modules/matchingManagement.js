import Layout from '@/layout';
import ROLE from '@/const/role.js';

const matchingManagement = {
  path: '/matching-management',
  name: 'MatchingManagement',
  meta: {
    title: 'TAB_MATCHING_MANAGEMENT',
    breadcrumb: 'BREADCRUMB_MATCHING_MANAGEMENT',
    role: [ROLE.TYPE_SUPER_ADMIN, ROLE.TYPE_COMPANY_ADMIN, ROLE.TYPE_HR_MANAGER, ROLE.TYPE_COMPANY, ROLE.TYPE_HR],
  },
  component: Layout,
  redirect: { name: 'MatchingManagement' },
  children: [
    {
      path: '/',
      name: 'MatchingManagement',
      meta: {
        title: 'TAB_MATCHING_MANAGEMENT_LIST',
        breadcrumb: 'BREADCRUMB_MATCHING_MANAGEMENT_LIST',
      },
      component: () => import('@/pages/MatchingManagement/index.vue'),
    },
  ],
};

export default matchingManagement;
