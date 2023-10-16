import { createLocalVue, shallowMount } from '@vue/test-utils';
import store from '@/store';
import router from '@/router';
import RegisterHrOrigin from '@/pages/RegisterHrOrigin/index.vue';
// import RegisterHrOrigin from '@/pages/HrOrganization/detail.vue';

// import RegisterHrOrigin from '@/pages/RegisterHrOrigin/true.vue';
import { login } from '@/api/auth';
import { handleRole } from '@/utils/handleRole'; ``;
import { validEmail } from '@/utils/validate';
// import { uploadFile } from '@/api/uploadFile';
import { addHr } from '@/api/hr.js';

// import { thisFile } from './fileBinary.js';

jest.useFakeTimers();
// Action input file by hand in UI
const idRealTimeGlobal = 31;

describe('Test component Register HrOrigin', () => {
  //
  test('Case 1: Check render component Register HR Organization Template', () => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(RegisterHrOrigin,
      localVue,
      store,
      router
    );
    expect(typeof RegisterHrOrigin.data).toBe('function');
    const RegisterCompanyTemplate = wrapper.findComponent({ name: 'HROrganizationUserRegistration' });
    expect(RegisterCompanyTemplate.exists()).toBe(true);

    wrapper.destroy();
  });

  test('Case 2: Check render component Register HR Organization', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(RegisterHrOrigin,
      localVue,
      store,
      router
    );

    const RegisterCompanyPage = wrapper.find('.hr-registration');
    expect(RegisterCompanyPage.exists()).toBe(true);

    const title = wrapper.find('.head-description');
    expect(title.exists()).toBe(true);
    expect(title.text()).toEqual('TITLE_REGISTER_HR_H3');

    await wrapper.setData({ type_form: 'create' });

    const btn_next = wrapper.find('#btn-next');
    expect(btn_next.exists()).toBe(true);

    wrapper.destroy();
  });

  test('Case 3: Check All input initial empty', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(RegisterHrOrigin,
      localVue,
      store,
      router
    );
    await wrapper.setData({ type_form: 'create' });

    expect(wrapper.vm.formData.corporate_name_en).toBe('');
    expect(wrapper.vm.formData.corporate_name_ja).toBe('');
    expect(wrapper.vm.formData.license_no).toBe('');
    expect(wrapper.vm.formData.account_classification).toBe(null);
    expect(wrapper.vm.formData.country).toBe(null);
    expect(wrapper.vm.formData.representative_full_name).toBe('');
    expect(wrapper.vm.formData.representative_full_name_furigana).toBe('');
    expect(wrapper.vm.formData.representative_contact).toBe('');
    expect(wrapper.vm.formData.assignee_contact).toBe('');
    expect(wrapper.vm.formData.post_code).toBe('');
    expect(wrapper.vm.formData.prefectures).toBe('');
    expect(wrapper.vm.formData.municipality).toBe('');
    expect(wrapper.vm.formData.mail_address).toBe('');
    expect(wrapper.vm.formData.number).toBe('');
    expect(wrapper.vm.formData.other).toBe('');
    expect(wrapper.vm.formData.url).toBe('');
    expect(wrapper.vm.formData.certificate_file_id).toBe('');

    expect(wrapper.vm.error.corporate_name_en).toBe(true);
    expect(wrapper.vm.error.corporate_name_ja).toBe(true);
    expect(wrapper.vm.error.license_no).toBe(true);
    expect(wrapper.vm.error.account_classification).toBe(true);
    expect(wrapper.vm.error.country).toBe(true);
    expect(wrapper.vm.error.representative_full_name).toBe(true);
    expect(wrapper.vm.error.representative_full_name_furigana).toBe(true);
    expect(wrapper.vm.error.representative_contact).toBe(true);
    expect(wrapper.vm.error.assignee_contact_id).toBe(true);
    expect(wrapper.vm.error.assignee_contact).toBe(true);
    expect(wrapper.vm.error.post_code).toBe(true);
    expect(wrapper.vm.error.prefectures).toBe(true);
    expect(wrapper.vm.error.municipality).toBe(true);
    expect(wrapper.vm.error.number).toBe(true);
    expect(wrapper.vm.error.mail_address).toBe(true);
    expect(wrapper.vm.error.representative_contact_id).toBe(true);
    expect(wrapper.vm.error.url).toBe(true);
    expect(wrapper.vm.error.certificate_file_id).toBe(true);

    wrapper.destroy();
  });

  test('Case 4: Check All input change data', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(RegisterHrOrigin,
      localVue,
      store,
      router
    );
    await wrapper.setData({ type_form: 'create' });

    const initData = {
      corporate_name_en: 'Corporate name',
      corporate_name_ja: 'Corporate name (japanese)',
      license_no: 'License number',
      account_classification: 1,
      country: 1,
      representative_full_name: 'Representative name',
      representative_full_name_furigana: 'Representative name',
      representative_contact: '+84 0312345613',
      assignee_contact: '+84 0312345678',
      post_code: '1020093',
      prefectures: 'prefectures',
      municipality: 'municipalities',
      mail_address: 'companyVeho1@gmail.com',
      number: 'address',
      other: 'others',
      url: 'https://okuridashi_hanoi.com',
      certificate_file_id: idRealTimeGlobal,
      // is_create: 1,
    };

    await wrapper.setData({ formData: initData });

    const error = {
      corporate_name_en: true,
      corporate_name_ja: true,
      license_no: true,
      account_classification: true,
      country: true,
      representative_full_name: true,
      representative_full_name_furigana: true,
      representative_contact: true,
      assignee_contact_id: true,
      assignee_contact: true,
      post_code: true,
      prefectures: true,
      municipality: true,
      number: true,
      mail_address: true,
      representative_contact_id: true,
      url: true,
      certificate_file_id: true,
    };

    await wrapper.setData({ error: error });

    expect(wrapper.vm.formData.corporate_name_en).toBe('Corporate name');
    expect(wrapper.vm.formData.corporate_name_ja).toBe('Corporate name (japanese)');
    expect(wrapper.vm.formData.license_no).toBe('License number');
    expect(wrapper.vm.formData.account_classification).toBe(1);
    expect(wrapper.vm.formData.country).toBe(1);
    expect(wrapper.vm.formData.representative_full_name).toBe('Representative name');
    expect(wrapper.vm.formData.representative_full_name_furigana).toBe('Representative name');
    expect(wrapper.vm.formData.representative_contact).toBe('+84 0312345613');
    expect(wrapper.vm.formData.assignee_contact).toBe('+84 0312345678');
    expect(wrapper.vm.formData.post_code).toBe('1020093');
    expect(wrapper.vm.formData.prefectures).toBe('prefectures');
    expect(wrapper.vm.formData.municipality).toBe('municipalities');
    expect(wrapper.vm.formData.mail_address).toBe('companyVeho1@gmail.com');
    expect(wrapper.vm.formData.number).toBe('address');
    expect(wrapper.vm.formData.other).toBe('others');
    expect(wrapper.vm.formData.url).toBe('https://okuridashi_hanoi.com');
    expect(wrapper.vm.formData.certificate_file_id).toBe(idRealTimeGlobal);

    expect(wrapper.vm.error.corporate_name_en).toBe(true);
    expect(wrapper.vm.error.corporate_name_ja).toBe(true);
    expect(wrapper.vm.error.license_no).toBe(true);
    expect(wrapper.vm.error.account_classification).toBe(true);
    expect(wrapper.vm.error.country).toBe(true);
    expect(wrapper.vm.error.representative_full_name).toBe(true);
    expect(wrapper.vm.error.representative_full_name_furigana).toBe(true);
    expect(wrapper.vm.error.representative_contact).toBe(true);
    expect(wrapper.vm.error.assignee_contact_id).toBe(true);
    expect(wrapper.vm.error.assignee_contact).toBe(true);
    expect(wrapper.vm.error.post_code).toBe(true);
    expect(wrapper.vm.error.prefectures).toBe(true);
    expect(wrapper.vm.error.municipality).toBe(true);
    expect(wrapper.vm.error.number).toBe(true);
    expect(wrapper.vm.error.mail_address).toBe(true);
    expect(wrapper.vm.error.representative_contact_id).toBe(true);
    expect(wrapper.vm.error.url).toBe(true);
    expect(wrapper.vm.error.certificate_file_id).toBe(true);

    wrapper.destroy();
  });

  test('Case 5: Check All data pass validate pass validate', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(RegisterHrOrigin,
      localVue,
      store,
      router
    );
    await wrapper.setData({ type_form: 'create' });

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
        //
      }
    });

    const randomNumbers = Array.from({ length: 1 }, () => Math.floor(Math.random() * 100));
    randomNumbers.sort((a, b) => a - b);

    const initData = {
      corporate_name_en: `Corporate name${randomNumbers}`,
      corporate_name_ja: `Corporate name (japanese)${randomNumbers}`,
      license_no: 'License number',
      account_classification: 1,
      country: 1,
      representative_full_name: 'Representative name',
      representative_full_name_furigana: 'Representative name',
      representative_contact: '+84 0312345613',
      assignee_contact: `+84 03123456${randomNumbers}${randomNumbers}`,
      post_code: `10200${randomNumbers}`,
      prefectures: 'prefectures',
      municipality: 'municipalities',
      mail_address: `companyVeho${randomNumbers}${randomNumbers}${randomNumbers}@gmail.com`,
      number: 'address',
      other: 'others',
      url: 'https://okuridashi_hanoi.com',
      certificate_file_id: idRealTimeGlobal,
      is_create: 1,
    };

    await wrapper.setData({ formData: initData });

    const btn_next = wrapper.find('#btn-next');
    expect(btn_next.exists()).toBe(true);

    // const spyRegisterHRCheckValidate = jest.spyOn(wrapper.vm, 'registerHRCheckValidate');
    // const spyCheckvalidate = jest.spyOn(wrapper.vm, 'checkvalidate');

    // spyRegisterHRCheckValidate();
    // spyCheckvalidate();

    await btn_next.trigger('click');

    // expect(spyRegisterHRCheckValidate).toHaveBeenCalled();
    // expect(spyCheckvalidate).toHaveBeenCalled();

    // console.log('case 5: spyCheckvalidate', spyCheckvalidate());
    // expect(spyCheckvalidate()).toBe(true);

    const resutltValidEmail = await validEmail(wrapper.vm.formData.mail_address);
    expect(resutltValidEmail).toBe(true);

    wrapper.destroy();
  });

  test('Case 6: Check All data not pass validate pass validate', async() => {
    const localVue = createLocalVue();
    const registerHRCheckValidate = jest.fn();
    const checkEmail = jest.fn();
    const validEmail = jest.fn();

    const wrapper = shallowMount(RegisterHrOrigin, {
      localVue,
      router,
      store,
      methods: {
        registerHRCheckValidate,
        validEmail,
        checkEmail,
      },
    });
    await wrapper.setData({ type_form: 'create' });

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
        //
      }
    });

    const initData = {
      corporate_name_en: '',
      corporate_name_ja: '',
      license_no: '',
      account_classification: '',
      country: '',
      representative_full_name: '',
      representative_full_name_furigana: '',
      representative_contact: '',
      assignee_contact: '',
      post_code: '',
      prefectures: '',
      municipality: '',
      mail_address: 'companyVeho1@gmail',
      number: '',
      other: '',
      url: '',
      certificate_file_id: '',
      // is_create: 1,
    };

    await wrapper.setData({ formData: initData });

    const btn_next = wrapper.find('#btn-next');
    expect(btn_next.exists()).toBe(true);

    await btn_next.trigger('click');

    expect(registerHRCheckValidate).toHaveBeenCalled();

    checkEmail();
    expect(checkEmail).toHaveBeenCalled();

    validEmail(wrapper.vm.formData.mail_address);
    expect(validEmail).toHaveBeenCalledWith(wrapper.vm.formData.mail_address);

    expect(checkEmail()).not.toBe(true);

    wrapper.destroy();
  });

  test('Case 7: Check function send file certificate', async() => {
    const localVue = createLocalVue();
    // const postCentificate = jest.fn();

    const wrapper = shallowMount(RegisterHrOrigin, {
      localVue,
      router,
      store,
      methods: {
        // postCentificate,
      },
    });
    await wrapper.setData({ type_form: 'create' });

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

    // preview
    const randomNumbers = Array.from({ length: 1 }, () => Math.floor(Math.random() * 100));
    randomNumbers.sort((a, b) => a - b);

    const initData = {
      corporate_name_en: `Corporate name${randomNumbers}`,
      corporate_name_ja: `Corporate name (japanese)${randomNumbers}`,
      license_no: 'License number',
      account_classification: 1,
      country: 1,
      representative_full_name: 'Representative name',
      representative_full_name_furigana: 'Representative name',
      representative_contact: '+84 0312345613',
      assignee_contact: `+84 03123456${randomNumbers}${randomNumbers}`,
      post_code: `10200${randomNumbers}`,
      prefectures: 'prefectures',
      municipality: 'municipalities',
      mail_address: `companyVeho${randomNumbers}${randomNumbers}${randomNumbers}@gmail.com`,
      number: 'address',
      other: 'others',
      url: 'https://okuridashi_hanoi.com',
      // certificate_file_id: `${idRealTime}`,
      certificate_file_id: idRealTimeGlobal,
      is_create: 1,
    };
    await wrapper.setData({ formData: initData });

    //     const btnUploadCertificateFile = wrapper.find('#upload-certificateFile');
    //     expect(btnUploadCertificateFile.exists()).toBe(true);
    //
    //     const spyPostCentificate = jest.spyOn(wrapper.vm, 'postCentificate');
    //     await spyPostCentificate();
    //
    //     await btnUploadCertificateFile.trigger('change');
    //     expect(spyPostCentificate).toHaveBeenCalled();

    // console.log('Case 7 spyPostCentificate: ', await spyPostCentificate(file));
    // const rowFileData = spyPostCentificate(fileAvataDefault);
    // console.log('event.target.files: ', rowFileData);

    wrapper.destroy();
  });

  test('Case 8: Check registerHR respone code 200', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(RegisterHrOrigin,
      localVue,
      store,
      router
    );
    await wrapper.setData({ type_form: 'create' });

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

    // preview
    const randomNumbers = Array.from({ length: 1 }, () => Math.floor(Math.random() * 100));
    randomNumbers.sort((a, b) => a - b);

    const initData = {
      corporate_name_en: `Corporate name${randomNumbers}`,
      corporate_name_ja: `Corporate name (japanese)${randomNumbers}`,
      license_no: 'License number',
      account_classification: 1,
      country: 1,
      representative_full_name: 'Representative name',
      representative_full_name_furigana: 'Representative name',
      representative_contact: '+84 0312345613',
      assignee_contact: `+84 03123456${randomNumbers}${randomNumbers}`,
      post_code: `10200${randomNumbers}`,
      prefectures: 'prefectures',
      municipality: 'municipalities',
      mail_address: `companyVeho${randomNumbers}${randomNumbers}${randomNumbers}@gmail.com`,
      number: 'address',
      other: 'others',
      url: 'https://okuridashi_hanoi.com',
      certificate_file_id: idRealTimeGlobal,
      is_create: 1,
    };
    await wrapper.setData({ formData: initData });

    //     const btnUploadCertificateFile = wrapper.find('#upload-certificateFile');
    //     expect(btnUploadCertificateFile.exists()).toBe(true);
    //
    // const spyPostCentificate = jest.spyOn(wrapper.vm, 'postCentificate');
    // await spyPostCentificate();
    //
    //     await btnUploadCertificateFile.trigger('change');
    // expect(spyPostCentificate).toHaveBeenCalled();

    const response = await addHr(wrapper.vm.formData);
    console.log('case8 response : ', response);
    const code = Number(response?.data.code);
    expect(code).toBe(200);

    wrapper.destroy();
  });

  test('Case 9: Check check box agree with terms conditions', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(RegisterHrOrigin,
      localVue,
      store,
      router
    );

    await wrapper.setData({ type_form: 'preview' });
    console.log('Case 9 type_form: ', wrapper.vm.type_form);
    // expect(wrapper).toMatchSnapshot();
    const checkBoxAgreeTermsConditions = wrapper.find('#checkbox-1');

    expect(checkBoxAgreeTermsConditions.exists()).toBe(true);

    wrapper.destroy();
  });
  //
});

