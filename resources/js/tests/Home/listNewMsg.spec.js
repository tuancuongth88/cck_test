/* eslint-disable no-unused-vars */
import { createLocalVue, shallowMount, mount } from '@vue/test-utils';
import store from '@/store';
import router from '@/router';

import newMsglistOop from '@/pages/Home/index.vue';

import { login } from '@/api/auth';
import { handleRole } from '@/utils/handleRole';

import { listNoti } from '@/api/user.js';

describe('TEST new message Screen ', () => {
  let roleGlobal = null;

  const paginationNewMsgMatchingRelated = {
    currentPage: 1,
    totalRows: 10, // null
    perPage: 5,
  };

  const paramsPagination = {
    page: paginationNewMsgMatchingRelated.currentPage,
    per_page: paginationNewMsgMatchingRelated.perPage,
  };

  test('Case 1: Check render new message Template', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(newMsglistOop, {
      localVue, store, router,
    });

    expect(typeof newMsglistOop.data).toBe('function');

    const newMsglistTemplate = wrapper.findComponent({ name: 'Home' });
    expect(newMsglistTemplate.exists()).toBe(true);

    wrapper.destroy();
  });

  test('Case 2: Check render components new message', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(newMsglistOop, {
      localVue, store, router,
    });

    const newMsgPage = wrapper.find('.display-user-management-list');
    expect(newMsgPage.exists()).toBe(true);

    wrapper.destroy();
  });

  test('Case 3: Check role when login', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(newMsglistOop, {
      localVue, store, router,
    });

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
    // 1: 'admin',
    // 2: 'company_manager',
    // 3: 'hr_manager',
    // 4: 'company',
    // 5: 'hr',

    const roleId = Number(await store.getters.profile.type);

    if (params.mail_address === '1okuridashi_hanoi@gmail.vn') {
      roleGlobal = roleId;
      expect(roleId).toEqual(1);
    } else if (params.mail_address === '2okuridashi_hanoi@gmail.vn') {
      roleGlobal = roleId;
      expect(roleId).toEqual(2);
    } else if (params.mail_address === '3okuridashi_hanoi@gmail.vn') {
      roleGlobal = roleId;
      expect(roleId).toEqual(3);
    } else if (params.mail_address === '4okuridashi_hanoi@gmail.vn') {
      roleGlobal = roleId;
      expect(roleId).toEqual(4);
    } else if (params.mail_address === '5okuridashi_hanoi@gmail.vn') {
      roleGlobal = roleId;
      expect(roleId).toEqual(5);
    }

    const newMsgTable = wrapper.find('#new-msg');
    expect(newMsgTable.exists()).toBe(true);

    console.log('Case 3: store', store.getters.profile.type);

    wrapper.destroy();
  });

  test('Case 4: Check API new message respone code 200', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(newMsglistOop, {
      localVue, store, router,
    });

    const params = {
      mail_address: '1okuridashi_hanoi@gmail.vn',
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

    const response = await listNoti(paramsPagination);
    const { code } = response['data'];
    expect(code).toEqual(200);
    // console.log('Case 5: params:', response);

    wrapper.destroy();
  });

  test('Case 5: Check components dot unread_all', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(newMsglistOop, {
      localVue, store, router,
    });

    const role = 1;
    await wrapper.setData({ role: role });

    await wrapper.setData({ readAllMsgMatchingRelated: false });
    console.log('Case 5: readAllMsgMatchingRelated', wrapper.vm.readAllMsgMatchingRelated);
    // expect(wrapper).toMatchSnapshot();

    // const readAllMsgComponent = wrapper.find('.dot');
    // expect(readAllMsgComponent.exists()).toBe(true);

    wrapper.destroy();
  });

  test('Case 6: Check function showmore new message', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(newMsglistOop, {
      localVue, store, router,
    });

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

    // console.log('store.getters.profile.type: ', store.getters.profile.type);

    const listRole = [1, 2, 3, 4, 5];
    const role = Number(store.getters.profile.type);
    if (listRole.includes(role)) {
      await wrapper.setData({ role: role });

      const response = await listNoti(paramsPagination);
      const { code } = response['data'];
      expect(code).toEqual(200);

      const result = response['data']['data']['result'];

      console.log('listNoti: ', result.length);

      if (result.length > 5) {
        const btnShowMoreMsg = wrapper.find('#show-more-msg');
        expect(btnShowMoreMsg.exists()).toBe(true);

        const showMore = jest.spyOn(wrapper.vm, 'showMore'); // showMore
        showMore();
        expect(showMore).toHaveBeenCalled();
      }

      wrapper.destroy();
    }
  });

  //
});

