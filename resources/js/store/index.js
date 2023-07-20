import Vue from 'vue';
import Vuex from 'vuex';

import app from './modules/app';
import user from './modules/user';
import permission from './modules/permission';
import userManagement from './modules/userManagement';
import hrSearchQuery from './modules/hrSearchQuery';

import getters from './getters';

Vue.use(Vuex);

const modules = {
  app,
  user,
  permission,
  userManagement,
  hrSearchQuery,
};

const store = new Vuex.Store({
  modules,
  getters,
});

export default store;
