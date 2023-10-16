const state = {
  work_id: null,
  list_selected_hr: [],
  is_filled_data: false,
  list_hr_information: [],
  list_disabled_hr: [],
};

const mutations = {
  SET_WORK_ID: (state, work_id) => {
    state.work_id = work_id;
  },
  SET_LIST_SELECTED_HR: (state, list_selected_hr) => {
    state.list_selected_hr = list_selected_hr;
  },
  SET_IS_FILLED_DATA: (state, is_filled_data) => {
    state.is_filled_data = is_filled_data;
  },
  SET_LIST_HR_INFORMATION: (state, list_hr_information) => {
    state.list_hr_information = list_hr_information;
  },
  SET_LIST_DISABLED_HR: (state, list_disabled_hr) => {
    state.list_disabled_hr = list_disabled_hr;
  },
};

const actions = {
  setWorkID({ commit }, work_id) {
    commit('SET_WORK_ID', work_id);
  },
  setListSelectedHr({ commit }, list_selected_hr) {
    commit('SET_LIST_SELECTED_HR', list_selected_hr);
  },
  setIsFilledData({ commit }, is_filled_data) {
    commit('SET_IS_FILLED_DATA', is_filled_data);
  },
  setListHrInformation({ commit }, list_hr_information) {
    commit('SET_LIST_HR_INFORMATION', list_hr_information);
  },
  setListDisabledHr({ commit }, list_disabled_hr) {
    commit('SET_LIST_DISABLED_HR', list_disabled_hr);
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
