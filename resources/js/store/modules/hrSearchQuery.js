import Cookies from 'js-cookie';

const state = {
  searchParams: null,
};

const mutations = {
  SET_SEARCH_PARAMS: (state, searchParams) => {
    state.searchParams = searchParams;
    Cookies.set('searchParams', searchParams);
  },
};

const actions = {
  setSearchParams({ commit }, searchParams) {
    commit('SET_SEARCH_PARAMS', searchParams);
  },
};

export default {
  state,
  actions,
  mutations,
  namespaced: true,
};
