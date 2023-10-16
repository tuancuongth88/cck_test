
/* eslint-disable no-undef */
import { shallowMount, createLocalVue } from '@vue/test-utils';
import Header from '@/layout/components/Header.vue';

import store from '@/store';
import router from '@/router';
// import { postLogin } from '@/api/modules/login';
import { login } from '@/api/auth';
import { handleRole } from '@/utils/handleRole';
import ROLE from '@/const/role.js';

describe('Test Component Logout', () => {
  const localVue = createLocalVue();
  const manager_type = [1, 2, 3];
  const permissionCheck = store.getters.permissionCheck; //
  const profile = store.getters.profile;

  test('Case 1: Check Role', async() => {
    // const thisFunction = jest.fn();
    const wrapper = shallowMount(Header, {
      localVue,
      store,
      router,
      methods: {
        // thisFunction,
      },
    });

    const params = {
      mail_address: '1okuridashi_hanoi@gmail.vn',
      password: '123456789CCk',
    };

    await login(params)
      .then((response) => {
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
          expect(store.getters.token).toBe(TOKEN);
          //
        }
      });
    wrapper.destroy();
  });

  test('Case 2: Check btn favorite', async() => {
    const wrapper = shallowMount(Header, {
      localVue,
      store,
      router,
      methods: {
        // thisFunction,
      },
    });

    if ([ROLE.TYPE_COMPANY, ROLE.TYPE_HR].includes(profile.type)) {
      const btnFavorite = wrapper.find('#btn-favorite');
      expect(btnFavorite.exists());

      // const handleClickToNewTab = jest.spyOn(wrapper.vm, 'handleClickToNewTab');
      btnFavorite.trigger('click');
      // Check rouoter
      // expect(handleClickToNewTab).toHaveBeenCalled();
    }

    wrapper.destroy();
  });

  test('Case 3: Check btn setting ', async() => {
    const wrapper = shallowMount(Header, {
      localVue,
      store,
      router,
      methods: {
        // thisFunction,
      },
    });

    if (!manager_type.includes(permissionCheck)) {
      const dropdown = wrapper.find('#go-to-profile-page');
      expect(dropdown.exists());
    }

    wrapper.destroy();
  });

  test('Case 4: Check btn-logout', async() => {
    const handleLogout = jest.fn();
    const wrapper = shallowMount(Header, {
      localVue,
      store,
      router,
      methods: {
        handleLogout,
      },
    });

    const btnLogOut = wrapper.find('.btn-logout');
    expect(btnLogOut.exists());
    wrapper.find('.btn-logout').trigger('click');
    expect(handleLogout).toHaveBeenCalled();

    wrapper.destroy();
  });

  test('Case 5: Check user info', async() => {
    // const handleLogout = jest.fn();
    const wrapper = shallowMount(Header, {
      localVue,
      store,
      router,
      methods: {
        // handleLogout,
      },
    });

    const userInfoEl = wrapper.find('.user-info');
    expect(userInfoEl.exists());

    if (profile.type === ROLE.TYPE_SUPER_ADMIN) {
      expect(userInfoEl.exists());
      // システム管理者
    }
    if (profile.type === ROLE.TYPE_COMPANY_ADMIN) {
      expect(userInfoEl.exists());
      // 企業管理;
    }
    if (profile.type === ROLE.TYPE_HR_MANAGER) {
      expect(userInfoEl.exists());
      // 運営事務局;
    }
    if (profile.type === ROLE.TYPE_COMPANY) {
      // profile.company.company_name;
    }
    if (profile.type === ROLE.TYPE_HR && profile.hr_organization.corporate_name_en) {
      // profile.hr_organization.corporate_name_en;
    }

    wrapper.destroy();
  });
  //
});

