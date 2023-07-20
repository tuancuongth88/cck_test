import { shallowMount, createLocalVue } from '@vue/test-utils';
import store from '@/store';
import router from '@/router';
import { postLogin } from '@/api/modules/login';
import Login from '@/pages/Login/index.vue';

describe('Test Component Login', () => {
  const localVue = createLocalVue();
  const wrapper = shallowMount(Login, { localVue, store, router });

  let TOKEN;
  let PROFILE;
  let USER;

  it('Case 1: Check component Login has data', () => {
    expect(typeof Login.data).toBe('function');
  });

  it('Case 2: Check Account (email, password) is blank', () => {
    const Account = {
      email: '',
      password: '',
    };

    expect(wrapper.vm.account.email).toBe(Account.email);
    expect(wrapper.vm.account.password).toBe(Account.password);
  });

  it('Case 3: Check Change Account (email, password)', async() => {
    const Account = {
      email: 'test@gmail.com',
      password: '123456789',
    };

    await wrapper.setData({
      account: Account,
    });

    expect(wrapper.vm.account.email).toBe(Account.email);
    expect(wrapper.vm.account.password).toBe(Account.password);
  });

  it('Case 4: Check isProcess is Equal fasle when create component', () => {
    expect(wrapper.vm.isProcess).toBe(false);
  });

  it('Case 5: Check object message when create component', () => {
    const message = {
      isShowMessage: false,
      isMessage: '',
    };

    expect(wrapper.vm.message).toStrictEqual(message);
  });

  it('Case 6: Check if the login button is pressed, the system calls the login handler function or not?', () => {
    const Account = {
      email: 'test@gmail.com',
      password: '123456789',
    };

    const spy = jest.spyOn(wrapper.vm, 'handleLogin');

    wrapper.setData({ account: Account });

    const Button = wrapper.find('#btn-login');

    Button.trigger('click');

    expect(spy).toHaveBeenCalled();
  });

  it('Case 7: Check the login with EMAIL AND PASSWORD INCORRECT', async() => {
    const Account = {
      email: 'nothing@gmail.com',
      password: 'nothinghere1',
    };

    await wrapper.setData({ account: Account });

    await wrapper.vm.handleLogin();

    const message = {
      isShowMessage: true,
      isMessage: 'Sai tài khoản hoặc mật khẩu',
    };

    expect(wrapper.vm.message).toStrictEqual(message);
  });

  it('Case 8: Check the function that STORES DATA into STORE', async() => {
    const url = '/auth/login';

    const Account = {
      user_name: 'test@gmail.com',
      password: '123456789',
    };

    await postLogin(url, Account)
      .then((res) => {
        TOKEN = res.data.access_token;
        PROFILE = res.data.profile;

        USER = {
          address: PROFILE.address || '',
          avatar: PROFILE.avatar || '',
          email: PROFILE.email || '',
          fax: PROFILE.fax || '',
          gender: PROFILE.gender || '',
          id: PROFILE.id || '',
          name: PROFILE.name || '',
          phone: PROFILE.phone || '',
          status: PROFILE.status || '',
        };
      });

    await store.dispatch('user/saveLogin', { USER, TOKEN });

    expect(store.getters.email).toBe(Account.user_name);

    store.dispatch('user/saveLogin', { USER, TOKEN });

    expect(store.getters.token).toBe(TOKEN);
    expect(store.getters.profile).toStrictEqual(USER);
  });

  it('Case 9: Check the component Login (ACCOUNT CORRECT)', async() => {
    const Account = {
      email: 'test@gmail.com',
      password: '123456789',
    };

    await wrapper.setData({ account: Account });

    wrapper.vm.handleLogin();

    expect(store.getters.token).toBeTruthy();
  });

  it('Case 10: Check the login with EMAIL NOTHING', async() => {
    const Account = {
      email: '',
      password: '123456789',
    };

    await wrapper.setData({ account: Account });

    wrapper.vm.handleLogin();

    const message = {
      isShowMessage: true,
      isMessage: 'ERROR_VALIDATE_EMAIL_PASSWORD',
    };

    expect(wrapper.vm.message).toStrictEqual(message);
  });

  it('Case 11: Check the login with PASSWORD NOTHING', async() => {
    const Account = {
      email: 'test@gmail.com',
      password: '',
    };

    await wrapper.setData({ account: Account });

    wrapper.vm.handleLogin();

    const message = {
      isShowMessage: true,
      isMessage: 'ERROR_VALIDATE_EMAIL_PASSWORD',
    };

    expect(wrapper.vm.message).toStrictEqual(message);
  });

  it('Case 12: Check the login with EMAIL AND PASSWORD NOTHING', async() => {
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
  });
});
