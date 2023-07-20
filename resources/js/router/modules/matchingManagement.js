import Layout from '@/layout';

const matchingManagement = {
  path: '/matching-management',
  name: 'MatchingManagement',
  meta: {
    title: 'TAB_MATCHING_MANAGEMENT',
    breadcrumb: 'BREADCRUMB_MATCHING_MANAGEMENT',
    role: [1, 2, 3, 4, 5],
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
