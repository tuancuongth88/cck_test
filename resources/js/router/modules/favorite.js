import Layout from '@/layout';
import ROLE from '@/const/role.js';

const favorite = {
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
        role: [ROLE.TYPE_COMPANY_ADMIN, ROLE.TYPE_HR_MANAGER, ROLE.TYPE_COMPANY, ROLE.TYPE_HR],
      },
      component: () => import('@/pages/Favorite/index.vue'),
    },
  ],
};

export default favorite;
