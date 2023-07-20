
const state = {
  listUser: [],
};

const mutations = {
  LIST_ALL_USER: (state, listUser) => {
    state.listUser = listUser;
  },
};

const actions = {
  saveListUser({ commit }, listUser) {
    commit('LIST_ALL_USER', listUser);
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
