import Cookies from 'js-cookie';

import { getLanguage } from '../../utils/getLang';

const state = {
  language: getLanguage(),
  loading: false,
  tabIndex: 0,
  setOpen: false,
};

const mutations = {
  SET_LANGUAGE: (state, language) => {
    state.language = language;
    Cookies.set('language', 'ja');
  },
  SET_LOADING(state, loading) {
    state.loading = loading;
  },
  SAVE_TAB_INDEX: (state, tabIndex) => {
    state.tabIndex = tabIndex;
  },
  SET_REDIRECT_TO_MODAL_SELECT_JOBS_TO_OFFER: (state, setOpen) => {
    state.setOpen = setOpen;
  },

};

const actions = {
  setLanguage({ commit }, language) {
    commit('SET_LANGUAGE', language);
  },
  setLoading({ commit }, loading) {
    commit('SET_LOADING', loading);
  },
  saveTabIndex({ commit }, tabIndex) {
    commit('SAVE_TAB_INDEX', tabIndex);
  },
  setRedirectToModalSelectJobsToOffer({ commit }, setOpen) {
    commit('SET_REDIRECT_TO_MODAL_SELECT_JOBS_TO_OFFER', setOpen);
  },

};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
