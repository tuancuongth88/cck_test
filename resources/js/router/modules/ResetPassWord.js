import Layout from '@/layout';

const ResetPass = {
  path: '/reset-password',
  component: Layout,
  redirect: { name: 'forget-password' },
  meta: {
    title: 'Reset password',
    breadcrumb: 'Reset password',
    component: () => import(/* webpackChunkName: "ResetPass" */ '@/pages/ResetPassWord/index.vue'),
  },
};

export default ResetPass;
