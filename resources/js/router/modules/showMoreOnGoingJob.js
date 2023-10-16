import Layout from '@/layout';
import ROLE from '@/const/role.js';

const onGoingJob = {
  path: '/on-going-job',
  name: 'onGoingJob',
  meta: {
    title: '',
    breadcrumb: '',
    role: [ROLE.TYPE_SUPER_ADMIN, ROLE.TYPE_COMPANY_ADMIN, ROLE.TYPE_HR_MANAGER, ROLE.TYPE_COMPANY, ROLE.TYPE_HR],
  },
  hidden: true,
  component: Layout,

  redirect: { name: 'Home' },
  children: [
    {
      path: '',
      name: 'List',
      meta: {
        title: 'ON_GOING_JOB on-going-job',
        breadcrumb: 'BREADCRUMB_ON-GOING_JOB',
        role: [ROLE.TYPE_SUPER_ADMIN, ROLE.TYPE_COMPANY_ADMIN, ROLE.TYPE_HR_MANAGER, ROLE.TYPE_COMPANY, ROLE.TYPE_HR],
      },
      hidden: true,
      component: () => import('@/pages/Home/ShowMore/showMoreOnGoingJob.vue'),
    },
  ],
};

export default onGoingJob;

