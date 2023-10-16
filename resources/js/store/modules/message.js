import Cookies from 'js-cookie';

const state = {
  message_id: Cookies.get('message_id') || null,
};

const mutations = {
  SET_MESSAGE_ID: (state, message_id) => {
    state.message_id = message_id;
    Cookies.set('message_id', message_id);
  },
};

const actions = {
  setMessageID({ commit }, message_id) {
    commit('SET_MESSAGE_ID', message_id);
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
