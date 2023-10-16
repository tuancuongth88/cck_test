/* eslint-disable no-unused-vars */
import { createLocalVue, shallowMount, mount } from '@vue/test-utils';
import store from '@/store';
import router from '@/router';

import newMsglistOop from '@/pages/Home/index.vue';

import { login } from '@/api/auth';
import { handleRole } from '@/utils/handleRole';

import { listNoti } from '@/api/user.js';

describe('TEST new message Screen ', () => {
  const roleGlobal = null;

  //   const paginationNewMsgMatchingRelated = {
  //     currentPage: 1,
  //     totalRows: 10, // null
  //     perPage: 5,
  //   };
  //
  //   const paramsPagination = {
  //     page: paginationNewMsgMatchingRelated.currentPage,
  //     per_page: paginationNewMsgMatchingRelated.perPage,
  //   };

  const listRole = [1];
  let role = 1;

  test('Case 1: Check render Distribution msg List Template', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(newMsglistOop, {
      localVue, store, router,
    });

    expect(typeof newMsglistOop.data).toBe('function');

    const newMsglistTemplate = wrapper.findComponent({ name: 'Home' });
    expect(newMsglistTemplate.exists()).toBe(true);

    wrapper.destroy();
  });

  test('Case 2: Check call API Distribution msg List', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(newMsglistOop, {
      localVue, store, router,
    });

    const newMsgPage = wrapper.find('.display-user-management-list');
    expect(newMsgPage.exists()).toBe(true);

    const params = {
      mail_address: '2okuridashi_hanoi@gmail.vn',
      password: '123456789CCk',
    };

    await login(params).then((response) => {
      if (response['data']['code'] === 200) {
        const TOKEN = response['data']['data']['access_token'];
        const USER = response['data']['data']['profile'];

        const { ROLES, PERMISSIONS } = handleRole([]);

        const USER_INFO = {
          token: TOKEN,
          profile: USER,
          roles: ROLES,
          permissions: PERMISSIONS,
        };

        store.dispatch('user/saveLogin', USER_INFO);
        // expect(store.getters.token).toBe(TOKEN);
      }
    });

    role = Number(store.getters.profile.type); // 1 - Admin

    if (listRole.includes(role)) {
      await wrapper.setData({ role: role });
      expect(role === 1).toBe(true);
    }

    wrapper.destroy();
  });

  test('Case 3: Check render components Distribution msg List', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(newMsglistOop, {
      localVue, store, router,
    });

    if (listRole.includes(role)) {
      const distributionMsgTable = wrapper.find('#distribution-msg-table');
      expect(distributionMsgTable.exists()).toBe(true);
    }

    // wrapper.destroy();
  });

  //
});
