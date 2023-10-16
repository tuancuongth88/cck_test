const state = {
  job_search_data: null,
};

const mutations = {
  SET_JOB_SEARCH_DATA: (state, job_search_data) => {
    state.job_search_data = job_search_data;
  },
};

const actions = {
  setJobSearchData({ commit }, job_search_data) {
    commit('SET_JOB_SEARCH_DATA', job_search_data);
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
