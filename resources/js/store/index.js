import Vue from 'vue';
import Vuex from 'vuex';

import app from './modules/app';
import job from './modules/job';
import user from './modules/user';
import message from './modules/message';
import jobSearch from './modules/jobSearch';
import permission from './modules/permission';
import hrSearchQuery from './modules/hrSearchQuery';
import userManagement from './modules/userManagement';
import routerParam from './modules/routerParam';

import getters from './getters';

Vue.use(Vuex);

const modules = {
  app,
  job,
  user,
  message,
  jobSearch,
  permission,
  hrSearchQuery,
  userManagement,
  routerParam,
};

const store = new Vuex.Store({
  modules,
  getters,
});

export default store;
