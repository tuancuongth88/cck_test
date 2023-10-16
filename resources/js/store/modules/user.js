import Cookies from 'js-cookie';

import { resetRouter } from '@/router';
import { getToken } from '@/utils/handleToken';
import { getRole, getPermission } from '@/utils/handleRole';
import { getUserInfoCookie, saveUserInfoCookie, destroyToken, destroyUserInfoCookie } from '@/utils/jwt';

import constAuth from '@/const/auth';

const state = {
  access_token: getToken(),
  profile: getUserInfoCookie(),
  role: getRole(),
  permission: getPermission(),
};

const mutations = {
  SET_ACCESS_TOKEN: (state, access_token) => {
    Cookies.set('token', access_token);
    state.access_token = access_token;
  },
  SET_PROFILE: (state, profile) => {
    saveUserInfoCookie(profile);
    state.profile = profile;
  },
  SET_ROLE: (state, role) => {
    Cookies.set('role', role);
    state.role = role;
  },
  SET_PERMISSON: (state, permission) => {
    Cookies.set('permission', permission);
    state.permission = permission;
  },
  DO_LOGOUT: (state, profile) => {
    state.access_token = '';
    state.profile = profile;
    state.role = [];
    state.permission = [];

    destroyToken();
    destroyUserInfoCookie();
  },
};

const actions = {
  saveLogin({ commit }, userInfo) {
    commit('SET_ACCESS_TOKEN', userInfo.token);
    commit('SET_PROFILE', userInfo.profile);
    commit('SET_ROLE', userInfo.roles);
    commit('SET_PERMISSON', userInfo.permissions);
  },
  doLogout({ commit }) {
    const PROFILE = constAuth.PROFILE;

    commit('SET_ACCESS_TOKEN', '');
    commit('SET_PROFILE', PROFILE);
    commit('DO_LOGOUT', PROFILE);
    commit('SET_ROLE', []);
    commit('SET_PERMISSON', []);

    localStorage.setItem('currentRoutes', '');
    localStorage.removeItem('revertRouter');

    resetRouter();
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
