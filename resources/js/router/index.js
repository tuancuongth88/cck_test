import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

import homeManagement from './modules/homeManagement.js';
import onGoingJob from './modules/showMoreOnGoingJob.js';
import hrManagement from './modules/hrManagement.js';
import hrOrganization from './modules/hrOrganization.js';
import companyManagement from './modules/companyManagement.js';
import matchingManagement from './modules/matchingManagement.js';
import jobManagement from './modules/jobManagement.js';
import jobSearch from './modules/jobSearch.js';
import hrSearch from './modules/hrSearch.js';
import favorite from './modules/favorite.js';
import changePassword from './modules/changePassword.js';

export const constantRoutes = [
  {
    path: '/login',
    name: 'Login',
    component: () => import(/* webpackChunkName: "Login" */ '@/pages/Login/index.vue'),
    meta: {
      title: 'ROUTER_LOGIN',
    },
    hidden: true,
  },
  // Link follow gmail from BE
  {
    path: '/api/auth/password-reset',
    name: 'Reset password confirm',
    component: () => import('@/pages/PassWords/ResetPassWord/index.vue'),
    meta: {
      title: 'RESET_PASS_WORD_TITLE',
    },
    hidden: true,
  },
  {
    path: '/forget-password',
    name: 'Reset password',
    component: () => import('@/pages/PassWords/ForgetPassWord/index.vue'),
    meta: {
      title: 'RESET_PASS_WORD_TITLE',
    },
    hidden: true,
  },
  {
    path: '/human-resourse-register',
    name: 'human-resourse-register',
    component: () => import('@/pages/RegisterHrOrigin/index.vue'),
    meta: {
      title: 'RegisterHrOrigin',
    },
    hidden: true,
  },
  {
    path: '/company-register',
    name: 'company-register',
    component: () => import('@/pages/RegisterCompany/index.vue'),
    meta: {
      title: 'Company register',
    },
    hidden: true,
  },
  // {
  //   path: '/not-permission',
  //   component: () => import('@/pages/Error/NotPermission.vue'),
  //   meta: {
  //     title: 'Permission not Found',
  //   },
  //   hidden: true,
  // },
];

export const asyncRoutes = [
  homeManagement,
  onGoingJob,
  hrSearch,
  hrManagement,
  hrOrganization,
  companyManagement,
  jobManagement,
  jobSearch,
  matchingManagement,
  favorite,
  changePassword,
  {
    path: '/',
    redirect: { name: 'Home' },
    hidden: true,
  },
];

const createRouter = () => new VueRouter({
  mode: 'history',
  scrollBehavior: () => ({ y: 0 }),
  routes: constantRoutes,
});

const router = createRouter();

export function resetRouter() {
  const newRouter = createRouter();
  router.matcher = newRouter.matcher;
}

export default router;
