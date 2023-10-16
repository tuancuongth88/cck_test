import { shallowMount, createLocalVue } from '@vue/test-utils';
import store from '@/store';
import router from '@/router';
import ChangePass from '@/pages/PassWords/ChangePassWord/index.vue';
import ChangePassConfirm from '@/pages/PassWords/ChangePassWord/ChangePassWordConfirm.vue';
import { validPassOnlyRequireAz09 } from '@/utils/validate';
// import { changePassConfirmPost } from '@/api/password';
// import { ChangeNewPasswordPut } from '@/api/password';

describe('Test Component ChangePass', () => {
  test('Case 1: Check render Change password Template', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(ChangePass, {
      localVue, store, router,
    });

    expect(typeof ChangePass.data).toBe('function');
    const ChangePassWordTemplate = wrapper.findComponent({ name: 'ChangePassWord' });
    expect(ChangePassWordTemplate.exists()).toBe(true);

    wrapper.destroy();
  });

  test('Case 2: Check render component Change Password', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(ChangePass, {
      localVue, store, router,
    });

    const ChangePassPage = wrapper.find('#change-pass-page');
    expect(ChangePassPage.exists()).toBe(true);

    wrapper.destroy();
    //
  });

  test('Case 3: Check All input initial empty  (old pass)', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(ChangePass, {
      localVue, store, router,
    });

    const Password = {
      current_password: '',
      current_password_confirm: '',
    };

    await wrapper.setData({ password: Password });

    expect(wrapper.vm.password.current_password).toBe('');
    expect(wrapper.vm.password.current_password_confirm).toBe('');

    //
  });

  test('Case 4: Check All input wrong format (old pass)', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(ChangePass, {
      localVue, store, router,
    });
    const Password = {
      current_password: 'Abcd@1234',
      current_password_confirm: 'Abcd@1234',
    };

    await wrapper.setData({ password: Password });

    const btnConfirmCurrentpass = wrapper.find('#btn-confirm-current-pass');
    expect(btnConfirmCurrentpass.exists()).toBe(true);

    const spyhandleConfirmCurrentPass = jest.spyOn(wrapper.vm, 'handleConfirmCurrentPass');
    const spycheckvalidate = jest.spyOn(wrapper.vm, 'checkvalidate');
    btnConfirmCurrentpass.trigger('click');

    expect(spyhandleConfirmCurrentPass).toHaveBeenCalled();
    expect(spycheckvalidate).toHaveBeenCalled();

    const resutltCurrentPassword =
      validPassOnlyRequireAz09(wrapper.vm.password.current_password);
    expect(resutltCurrentPassword).toBe(false);

    const resutltCurrentPasswordConfirm =
      validPassOnlyRequireAz09(wrapper.vm.password.current_password_confirm);
    expect(resutltCurrentPasswordConfirm).toBe(false);

    wrapper.destroy();
    //
  });

  test('Case 5: Check All input correct format  (old pass)', async() => {
    const localVue = createLocalVue();
    const checkvalidate = jest.fn();
    const wrapper = shallowMount(ChangePass, {
      localVue, store, router,
      methods: {
        checkvalidate,
      },
    });
    const Password = {
      current_password: 'Abcd1234',
      current_password_confirm: 'Abcd1234',
    };

    await wrapper.setData({ password: Password });

    const btnConfirmCurrentpass = wrapper.find('#btn-confirm-current-pass');
    expect(btnConfirmCurrentpass.exists()).toBe(true);

    const spyhandleConfirmCurrentPass = jest.spyOn(wrapper.vm, 'handleConfirmCurrentPass');
    const spycheckvalidate = jest.spyOn(wrapper.vm, 'checkvalidate');
    btnConfirmCurrentpass.trigger('click');

    expect(spyhandleConfirmCurrentPass).toHaveBeenCalled();
    expect(spycheckvalidate).toHaveBeenCalled();

    const resutltCurrentPassword =
      validPassOnlyRequireAz09(wrapper.vm.password.current_password);
    expect(resutltCurrentPassword).toBe(true);

    const resutltCurrentPasswordConfirm =
      validPassOnlyRequireAz09(wrapper.vm.password.current_password_confirm);
    expect(resutltCurrentPasswordConfirm).toBe(true);

    wrapper.destroy();

    //
  });

  test('Case 6: Check All input less than or equal 12 characters (old pass)', async() => {
    const localVue = createLocalVue();
    const checkvalidate = jest.fn();
    const wrapper = shallowMount(ChangePass, {
      localVue, store, router,
      methods: {
        checkvalidate,
      },
    });
    const Password = {
      current_password: 'Abcd12349012',
      current_password_confirm: 'Abcd12349012',
    };

    await wrapper.setData({ password: Password });

    const btnConfirmCurrentpass = wrapper.find('#btn-confirm-current-pass');
    expect(btnConfirmCurrentpass.exists()).toBe(true);

    const spyhandleConfirmCurrentPass =
      jest.spyOn(wrapper.vm, 'handleConfirmCurrentPass');
    const spycheckvalidate = jest.spyOn(wrapper.vm, 'checkvalidate');
    btnConfirmCurrentpass.trigger('click');

    expect(spyhandleConfirmCurrentPass).toHaveBeenCalled();
    expect(spycheckvalidate).toHaveBeenCalled();

    // const input = wrapper.find('#current-password-confirm');
    // expect(input.exists()).toBe(true);
    // console.log('input value', input.element.password);
    expect(wrapper.vm.password.current_password.length).toBeLessThanOrEqual(12);

    wrapper.destroy();

    //
  });

  test('Case 7: Check All input Currently Password not match Currently Password (Confirm)  (old pass)', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(ChangePass, {
      localVue, store, router,
    });

    const Password = {
      current_password: 'Abcd1234',
      current_password_confirm: 'Abcd12345', // 5
    };

    await wrapper.setData({ password: Password });

    const inputCurrentPassword = wrapper.find('#current-password');
    const inputCurrentPasswordConfirm = wrapper.find('#current-password-confirm');

    expect(inputCurrentPassword.exists());
    expect(inputCurrentPasswordConfirm.exists());

    expect(wrapper.vm.password.current_password)
      .not.toEqual(wrapper.vm.password.current_password_confirm);

    wrapper.destroy();
    //
  });

  test('Case 8: Check All input initial empty (new pass)', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(ChangePassConfirm, {
      localVue,
      store,
      router,
    });
    expect(typeof ChangePass.data).toBe('function');
    const ChangePassWordTemplate = wrapper.findComponent({ name: 'ChangePassWordConfirm' });
    expect(ChangePassWordTemplate.exists()).toBe(true);

    wrapper.destroy();
    //
  });

  test('Case 9: Check All input wrong format (new pass)', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(ChangePassConfirm, {
      localVue,
      store,
      router,
    });

    const Password = {
      current_password: 'Abcd@1234',
      current_password_confirm: 'Abcd@1234',
    };

    await wrapper.setData({ password: Password });

    const btnConfirmCurrentpass = wrapper.find('#btn-set-new-password');
    expect(btnConfirmCurrentpass.exists()).toBe(true);

    const spyHandleSetNewPassword =
      jest.spyOn(wrapper.vm, 'handleSetNewPassword');
    const spycheckvalidate = jest.spyOn(wrapper.vm, 'checkvalidate');
    btnConfirmCurrentpass.trigger('click');

    expect(spyHandleSetNewPassword).toHaveBeenCalled();
    expect(spycheckvalidate).toHaveBeenCalled();

    const resutltCurrentPassword =
      validPassOnlyRequireAz09(wrapper.vm.password.current_password);
    expect(resutltCurrentPassword).toBe(false);

    const resutltCurrentPasswordConfirm =
      validPassOnlyRequireAz09(wrapper.vm.password.current_password_confirm);
    expect(resutltCurrentPasswordConfirm).toBe(false);

    wrapper.destroy();
    //
  });

  test('Case 9: Check All input correct format  (new pass)', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(ChangePassConfirm, {
      localVue,
      store,
      router,
    });

    const Password = {
      current_password: 'Abcd1234',
      current_password_confirm: 'Abcd1234',
    };

    await wrapper.setData({ password: Password });

    const btnConfirmCurrentpass = wrapper.find('#btn-set-new-password');
    expect(btnConfirmCurrentpass.exists()).toBe(true);

    const spyHandleSetNewPassword =
      jest.spyOn(wrapper.vm, 'handleSetNewPassword');
    const spycheckvalidate = jest.spyOn(wrapper.vm, 'checkvalidate');
    btnConfirmCurrentpass.trigger('click');

    expect(spyHandleSetNewPassword).toHaveBeenCalled();
    expect(spycheckvalidate).toHaveBeenCalled();

    const resutltCurrentPassword =
      validPassOnlyRequireAz09(wrapper.vm.password.current_password);
    expect(resutltCurrentPassword).toBe(true);

    const resutltCurrentPasswordConfirm =
      validPassOnlyRequireAz09(wrapper.vm.password.current_password_confirm);
    expect(resutltCurrentPasswordConfirm).toBe(true);

    wrapper.destroy();
    //
  });

  test('Case 10: Check All input New password not match New password (confirm) (new pass)', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(ChangePassConfirm, {
      localVue,
      store,
      router,
    });

    const Password = {
      current_password: 'Abcd1234',
      current_password_confirm: 'Abcd12346', // 6
    };

    await wrapper.setData({ password: Password });

    console.log('Error: ', await wrapper.vm.error.pass_match_content);

    const inputCurrentPassword = wrapper.find('#new-password');
    const inputCurrentPasswordConfirm = wrapper.find('#new-password-confirm');

    expect(inputCurrentPassword.exists());
    expect(inputCurrentPasswordConfirm.exists());

    expect(wrapper.vm.password.current_password)
      .not.toEqual(wrapper.vm.password.current_password_confirm);

    wrapper.destroy();
    //
  });

  test('Case 11: Check All input less than or equal 12 characters (new pass)', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(ChangePassConfirm, {
      localVue,
      store,
      router,
    });

    const Password = {
      current_password: 'Abcd12349012',
      current_password_confirm: 'Abcd12349012',
    };

    await wrapper.setData({ password: Password });

    const btnConfirmCurrentpass = wrapper.find('#btn-set-new-password');
    expect(btnConfirmCurrentpass.exists()).toBe(true);

    const spyHandleSetNewPassword =
      jest.spyOn(wrapper.vm, 'handleSetNewPassword');
    const spycheckvalidate = jest.spyOn(wrapper.vm, 'checkvalidate');
    btnConfirmCurrentpass.trigger('click');

    expect(spyHandleSetNewPassword).toHaveBeenCalled();
    expect(spycheckvalidate).toHaveBeenCalled();

    expect(wrapper.vm.password.current_password.length).toBeLessThanOrEqual(12);

    wrapper.destroy();
    //
  });

  test('Case 12: Check function handleConfirmCurrentPass respone code 200', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(ChangePass, {
      localVue,
      store,
      router,
    });

    // const Password = {
    //   current_password: '123456789CCk',
    //   current_password_confirm: '123456789CCk', // 6
    // };

    const btnConfirmCurrentpass = wrapper.find('#btn-confirm-current-pass');
    expect(btnConfirmCurrentpass.exists()).toBe(true);

    const spyhandleConfirmCurrentPass = jest.spyOn(wrapper.vm, 'handleConfirmCurrentPass');
    btnConfirmCurrentpass.trigger('click');
    expect(spyhandleConfirmCurrentPass).toHaveBeenCalled();

    // await changePassConfirmPost(Password)
    //   .then((response) => {
    //     // console.log('ResetPassSendEmailPost response: ', response);
    //     expect(response['data']['code']).not.toBe(200);
    //   });
    //
  });

  test('Case 13: Check function handleSetNewPassword respone code 200', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(ChangePassConfirm, {
      localVue,
      store,
      router,
    });

    // const Password = {
    //   current_password: '123456789Cck',
    //   current_password_confirm: '123456789Cck', // 6
    // };

    const btnConfirmCurrentpass = wrapper.find('#btn-confirm-current-pass');
    expect(btnConfirmCurrentpass.exists()).toBe(true);

    const spyhandleSetNewPassword = jest.spyOn(wrapper.vm, 'handleSetNewPassword');
    btnConfirmCurrentpass.trigger('click');
    expect(spyhandleSetNewPassword).toHaveBeenCalled();

    // await changePassConfirmPost(Password)
    //   .then((response) => {
    //     // console.log('ResetPassSendEmailPost response: ', response);
    //     expect(response['data']['code']).not.toBe(200);
    //   });
    //
  });

  //
});
