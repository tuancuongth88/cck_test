import { shallowMount, createLocalVue } from '@vue/test-utils';
import store from '@/store';
import router from '@/router';
import ResetPassWord from '@/pages/PassWords/ResetPassWord/index.vue';
import { validEmail, validPassOnlyRequireAz09 } from '@/utils/validate';
import { ResetPassSendEmailPost } from '@/api/password';
// import { ResetPassConfirmPut } from '@/api/password';

describe('Check render Component Reset Password', () => {
  it('Case 1: Check component render Reset password Template', () => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(ResetPassWord, {
      localVue,
      store,
      router,
    });
    expect(typeof ResetPassWord.data).toBe('function');

    const ResetPassWordTemplate = wrapper.findComponent({ name: 'ResetPassWordSendEmail' });
    expect(ResetPassWordTemplate.exists()).toBe(true);
  });

  it('Case 2: Check render component Reset Password ', () => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(ResetPassWord, {
      localVue, store, router,
    });
    const ResetPassWordPage = wrapper.find('.login-page');

    expect(ResetPassWordPage.exists()).toBe(true);
    wrapper.destroy();
  });

  it('Case 3: Check email initial empty input ', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(ResetPassWord, {
      localVue, store, router,
    });

    const formData = {
      mail_address: '',
    };

    await wrapper.setData({ formData: formData });

    expect(wrapper.vm.formData.mail_address).toBe('');
    wrapper.destroy();
  });

  it('Case 4: Check validate email empty ', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(ResetPassWord, {
      localVue, store, router,
    });

    const formData = {
      mail_address: '',
    };
    await wrapper.setData({ formData: formData });

    expect(wrapper.vm.formData.mail_address).toBe(formData.mail_address);
    const resutltValidEmail = validEmail(wrapper.vm.formData.mail_address);

    expect(resutltValidEmail).toBe(false);

    wrapper.destroy();
  });

  it('Case 5: Check validate email wrong format ', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(ResetPassWord, {
      localVue, store, router,
    });

    const formData = {
      mail_address: '1okuridashi_hanoi',
    };
    await wrapper.setData({ formData: formData });

    const btnSendEmail = wrapper.find('#btn-send-email');
    expect(btnSendEmail.exists()).toBe(true);

    const spy = jest.spyOn(wrapper.vm, 'goToSendEmail');
    btnSendEmail.trigger('click');
    expect(spy).toHaveBeenCalled();

    expect(wrapper.vm.formData.mail_address).toBe(formData.mail_address);
    const resutltValidEmail = validEmail(wrapper.vm.formData.mail_address);

    expect(resutltValidEmail).toBe(false);

    wrapper.destroy();
  });

  it('Case 6: Check function goToSendEmail respone code 200', async() => {
    const localVue = createLocalVue();

    const wrapper = shallowMount(ResetPassWord, { localVue, store, router });

    const formData = {
      mail_address: '1okuridashi_hanoi@gmail.vn', // 1okuridashi_hanoi@gmail.vn
    };
    const btnSendEmail = wrapper.find('#btn-send-email');
    expect(btnSendEmail.exists()).toBe(true);

    const spy = jest.spyOn(wrapper.vm, 'goToSendEmail');
    btnSendEmail.trigger('click');
    expect(spy).toHaveBeenCalled();

    await ResetPassSendEmailPost(formData)
      .then((response) => {
        // console.log('ResetPassSendEmailPost response: ', response);
        expect(response['data']['code']).toBe(200);
      });

    wrapper.destroy();
  });

  it('Case 7: Check all input forgetpass initial empty input', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(ResetPassWord, { localVue, store, router });

    const Password = {
      current_password: '',
      current_password_confirm: '',
    };

    await wrapper.setData({ password: Password });

    // const btnsetNewpass = wrapper.find('#btn-set-newpass');
    // expect(btnsetNewpass.exists()).toBe(true);
    // const spy = jest.spyOn(wrapper.vm, 'checkvalidate');
    // btnsetNewpass.trigger('click');
    // expect(spy).toHaveBeenCalled();

    expect(wrapper.vm.password.current_password).toBe('');
    expect(wrapper.vm.password.current_password_confirm).toBe('');
  });

  it('Case 8: Check All input wrong format', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(ResetPassWord, { localVue, store, router });
    // it nhất 1 chữ hoa, 1 chữ thường và số không kí tự đặc biệt
    const Password = {
      current_password: 'abcd@1234',
      current_password_confirm: 'abcd@1234',
    };

    await wrapper.setData({ password: Password });

    const btnsetNewpass = wrapper.find('#btn-set-newpass');
    expect(btnsetNewpass.exists()).toBe(true);

    const spy = jest.spyOn(wrapper.vm, 'checkvalidate');
    btnsetNewpass.trigger('click');
    expect(spy).toHaveBeenCalled();

    const resutltCurrentPassword = validPassOnlyRequireAz09(wrapper.vm.password.current_password);
    expect(resutltCurrentPassword).toBe(false);

    const resutltCurrentPasswordConfirm = validPassOnlyRequireAz09(wrapper.vm.password.current_password_confirm);
    expect(resutltCurrentPasswordConfirm).toBe(false);
    // console.log('resutltCurrentPassword false: ', resutltCurrentPassword);

    wrapper.destroy();
  });

  it('Case 9: Check All input correct format', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(ResetPassWord, { localVue, store, router });
    const Password = {
      current_password: 'Abcd1234',
      current_password_confirm: 'Abcd1234',
    };

    await wrapper.setData({ password: Password });

    const btnsetNewpass = wrapper.find('#btn-set-newpass');
    expect(btnsetNewpass.exists()).toBe(true);

    const spy = jest.spyOn(wrapper.vm, 'checkvalidate');
    btnsetNewpass.trigger('click');
    expect(spy).toHaveBeenCalled();

    const resutltCurrentPassword = validPassOnlyRequireAz09(wrapper.vm.password.current_password);
    expect(resutltCurrentPassword).toBe(true);

    const resutltCurrentPasswordConfirm = validPassOnlyRequireAz09(wrapper.vm.password.current_password_confirm);
    expect(resutltCurrentPasswordConfirm).toBe(true);
    // console.log('resutltCurrentPassword true: ', resutltCurrentPassword);

    wrapper.destroy();
  });

  it('Case 10: Check wrong currently password (Confirm) not match current password', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(ResetPassWord, { localVue, store, router });

    const inputNewPassword = wrapper.find('#new_password');
    const inputNewPasswordComfirm = wrapper.find('#new_password_comfirm');

    expect(inputNewPassword.exists()).toBe(true);
    expect(inputNewPasswordComfirm.exists()).toBe(true);

    const Password = {
      current_password: 'password_match',
      current_password_confirm: 'password_match2',
    };

    await wrapper.setData({ password: Password });

    expect(wrapper.vm.password.current_password).not.toBe(wrapper.vm.password.current_password_confirm);
    wrapper.destroy();
  });

  it('Case 11: Check function handle Set New Password', async() => {
    const localVue = createLocalVue();

    const wrapper = shallowMount(ResetPassWord, { localVue, store, router });

    const btnsetNewpass = wrapper.find('#btn-set-newpass');
    expect(btnsetNewpass.exists()).toBe(true);

    const spy = jest.spyOn(wrapper.vm, 'handleSetNewPassword');
    btnsetNewpass.trigger('click');

    expect(spy).toHaveBeenCalled();

    //     const Password = {
    //       current_password: 'Abcd1234',
    //       current_password_confirm: 'Abcd1234',
    //     };
    //
    //     const TOKEN = this.$route.query.token;
    //     const gmail = 'nguyennt.veho@gmail.com';
    //
    //     const dataconfirmPass = {
    //       token: TOKEN,
    //       mail_address: gmail,
    //       new_password: Password.current_password_confirm,
    //       new_password_confirm: this.password.current_password_confirm,
    //     };
    //
    //     await ResetPassConfirmPut(dataconfirmPass)
    //       .then((response) => {
    //         console.log('ResetPassConfirmPut response: ', response);
    //         expect(response['data']['code']).toBe(200);
    //       });

    wrapper.destroy();
  });

  //
});

