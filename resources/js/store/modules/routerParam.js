function getRevertRouter() {
  const REVERT = localStorage.getItem('revertRouter');

  if (REVERT) {
    return JSON.parse(REVERT);
  }
  return null;
}

const state = {
  routerInfo: {},
  revertRouter: getRevertRouter(),
};

const mutations = {
  SET_ALL_ROUTER_KEY: (state, data) => {
    state.routerInfo = {
      [data.routerName]: {
        params: data?.params,
        queries: data?.queries,
      },
    };
  },
  SET_REVERT_ROUTER: (state, revert_router) => {
    state.revertRouter = revert_router;
  },
};

const actions = {
  setRouterWithParamQuery({ commit }, data) {
    commit('SET_ALL_ROUTER_KEY', data);
  },
  setRevertRouter({ commit }, revert_router) {
    const REVERT_ROUTER = JSON.stringify(revert_router);
    localStorage.setItem('revertRouter', REVERT_ROUTER);
    commit('SET_REVERT_ROUTER', revert_router);
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
