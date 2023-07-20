import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

import homeManagement from './modules/homeManagement.js';
import hrManagement from './modules/hrManagement.js';
import hrOrganization from './modules/hrOrganization.js';
import companyManagement from './modules/companyManagement.js';
import matchingManagement from './modules/matchingManagement.js';
import jobManagement from './modules/jobManagement.js';
import jobSearch from './modules/jobSearch.js';
import hrSearch from './modules/hrSearch.js';
import favorite from './modules/favorite.js';

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
    component: () => import(/* webpackChunkName: "ResetPassWord" */ '@/pages/PassWords/ResetPassWordConfirm/index.vue'),
    meta: {
      title: 'RESET_PASS_WORD_TITLE',
    },
    hidden: true,
  },
  {
    path: '/reset-password-done',
    name: 'Reset password done',
    component: () => import(/* webpackChunkName: "ResetPassWord" */ '@/pages/PassWords/ResetPassWordConfirm/ResetPassWordDone.vue'),
    meta: {
      title: 'RESET_PASS_WORD_TITLE',
    },
    hidden: true,
  },
  //
  {
    path: '/reset-password-send-email',
    name: 'Reset password',
    component: () => import(/* webpackChunkName: "ResetPassWord" */ '@/pages/PassWords/ResetPassWord/index.vue'),
    meta: {
      title: 'RESET_PASS_WORD_TITLE',
    },
    hidden: true,
  },
  {
    path: '/reset-password-sent-email',
    name: 'Reset password sent email',
    component: () => import(/* webpackChunkName: "ResetPassWordSentEmail" */ '@/pages/PassWords/ResetPassWord/SentEmail.vue'),

    meta: {
      title: 'RESET_PASS_WORD_TITLE',
    },
    hidden: true,
  },
  //
  {
    path: '/change-password',
    name: 'Change password',
    component: () => import(/* webpackChunkName: "ResetPassWord" */ '@/pages/PassWords/ChangePassWord/ChangePassWord.vue'),
    meta: {
      title: 'CHANGE_PASSWORD_TITLE',
    },
    hidden: true,
  },
  {
    path: '/change-password-confirm',
    name: 'change password confirm',
    component: () => import(/* webpackChunkName: "ChangePassWordConfirm" */ '@/pages/PassWords/ChangePassWord/ChangePassWordConfirm.vue'),
    meta: {
      title: 'CHANGE_PASSWORD_TITLE',
    },
    hidden: true,
  },
  {
    path: '/change-password-done',
    name: 'Reset password done',
    component: () => import(/* webpackChunkName: "ChangePassWordDone" */ '@/pages/PassWords/ChangePassWord/ChangePassWordDone.vue'),
    meta: {
      title: 'Change password done',
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
];

export const asyncRoutes = [
  homeManagement,
  hrSearch,
  hrManagement,
  hrOrganization,
  companyManagement,
  jobManagement,
  jobSearch,
  matchingManagement,
  favorite,
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
