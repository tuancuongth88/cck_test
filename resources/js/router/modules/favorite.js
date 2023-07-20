import Layout from '@/layout';

const hrManagement = {
  path: '/my-favorite',
  name: 'Favorite',
  hidden: true,
  meta: {
    title: 'ROUTER_FAVORITE',
    breadcrumb: 'BREADCRUMB_FAVORITE',
  },
  component: Layout,
  redirect: { name: 'List' },
  children: [
    {
      path: 'list',
      name: 'List',
      meta: {
        title: 'ROUTER_FAVORITE_LIST',
        breadcrumb: 'BREADCRUMB_FAVORITE_LIST',
      },
      component: () => import('@/pages/Favorite/index.vue'),
    },
  ],
};

export default hrManagement;
