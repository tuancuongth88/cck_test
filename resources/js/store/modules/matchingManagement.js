
const state = {
  listMatching: [],
};

const mutations = {
  LIST_ALL_MATCHING: (state, listMatching) => {
    state.listMatching = listMatching;
  },
};

const actions = {
  savelistMatchingr({ commit }, listMatching) {
    commit('LIST_ALL_MATCHING', listMatching);
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
