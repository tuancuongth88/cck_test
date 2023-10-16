import { shallowMount, createLocalVue } from '@vue/test-utils';
import store from '@/store';
import router from '@/router';
// import { postLogin } from '@/api/modules/login';
import { login } from '@/api/auth';
import { handleRole } from '@/utils/handleRole';
import { validEmail } from '@/utils/validate';

import Login from '@/pages/Login/index.vue';

describe('Test Component Login', () => {
  test('Case 1: Check component render Login Template', () => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(Login, {
      localVue,
      store,
      router,
    });

    const LoginTemplate = wrapper.findComponent({ name: 'Login' });
    expect(LoginTemplate.exists()).toBe(true);

    wrapper.destroy();
  });

  test('Case 2: Check component Login has data', () => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(Login, { localVue, store, router });

    expect(typeof Login.data).toBe('function');
    const loginComponent = wrapper.find('.login-form');
    expect(loginComponent.exists()).toBe(true);

    const loginTitle = wrapper.find('.login-title');
    expect(loginTitle.exists()).toBe(true);

    const mailAddressInput = wrapper.find('#mail-address-input');
    expect(mailAddressInput.exists()).toBe(true);

    const passwordInput = wrapper.find('#password-input');
    expect(passwordInput.exists()).toBe(true);

    const btnLoginSubmit = wrapper.find('.login-submit');
    expect(btnLoginSubmit.exists()).toBe(true);

    const btnForgetPass = wrapper.find('#btn-forget-pass');
    expect(btnForgetPass.exists()).toBe(true);

    const btnCreateHr = wrapper.find('#btn-create-hr');
    expect(btnCreateHr.exists()).toBe(true);

    const btnCreateCompany = wrapper.find('#btn-create-company');
    expect(btnCreateCompany.exists()).toBe(true);

    wrapper.destroy();
  });

  test('Case 2: Check the initial empty input', () => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(Login, { localVue, store, router });

    const Account = {
      mail_address: '',
      password: '',
    };

    expect(wrapper.vm.account.mail_address).toBe(Account.mail_address);
    expect(wrapper.vm.account.password).toBe(Account.password);

    wrapper.destroy();
  });

  test('Case 3: Check required fields', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(Login, { localVue, store, router });

    const Account = {
      email: '',
      password: '',
    };

    await wrapper.setData({ account: Account });

    wrapper.vm.handleLogin();

    const message = {
      isShowMessage: true,
      isMessage: 'ERROR_VALIDATE_EMAIL_PASSWORD',
    };

    expect(wrapper.vm.message).toStrictEqual(message);

    wrapper.destroy();
  });

  test('Case 4: Check the function that STORES DATA into STORE', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(Login, { localVue, store, router });

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

  test('Case 5: Check Login wrong User ID', async() => {
    const localVue = createLocalVue();
    // const handleLogin = jest.fn();
    // const validEmail = jest.fn();

    const wrapper = shallowMount(Login, {
      localVue,
      store,
      router,
      methods: {
        // handleLogin,
        // validEmail,
      },
    });

    const Account = {
      mail_address: '1okuridashi_hanoi',
      password: '123456789CCk',
    };

    // await wrapper.setData({ account: Account });
    // const btnLoginSubmit = wrapper.find('.login-submit');
    // expect(btnLoginSubmit.exists());
    // btnLoginSubmit.trigger('click');
    // expect(handleLogin).toHaveBeenCalled();

    await wrapper.setData({ account: Account });
    const resutltValidEmail = validEmail(wrapper.vm.account.mail_address);

    expect(resutltValidEmail).toBe(false);
    wrapper.destroy();
  });

  test('Case 5: Check wrong password', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(Login, { localVue, store, router });

    const Account = {
      mail_address: '1okuridashi_hanoi@gmail.vn',
      password: '',
    };

    await login(Account)
      .then((response) => {
        // console.log('4 response: ', response);
        expect(response['data']['code']).not.toBe(200);
      });

    wrapper.destroy();
  });

  test('Case 6: Check Change Account (email, password)', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(Login, { localVue, store, router });

    const Account = {
      mail_address: '1okuridashi_hanoi@gmail.vn',
      password: '123456789CCk',
    };

    await wrapper.setData({
      account: Account,
    });

    expect(wrapper.vm.account.mail_address).toBe(Account.mail_address);
    expect(wrapper.vm.account.password).toBe(Account.password);

    wrapper.destroy();
  });

  //
});
