import Cookies from 'js-cookie';

import { getLanguage } from '../../utils/getLang';

const state = {
  language: getLanguage(),
  loading: false,
};

const mutations = {
  SET_LANGUAGE: (state, language) => {
    state.language = language;
    Cookies.set('language', 'ja');
  },
  SET_LOADING(state, loading) {
    state.loading = loading;
  },
};

const actions = {
  setLanguage({ commit }, language) {
    commit('SET_LANGUAGE', language);
  },
  setLoading({ commit }, loading) {
    commit('SET_LOADING', loading);
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
