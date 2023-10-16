import { shallowMount, createLocalVue } from '@vue/test-utils';
import store from '@/store';
import router from '@/router';
import RegisterCompany from '@/pages/RegisterCompany/index.vue';
// import { companyRegister } from '@/api/company.js';
// import { getListMainjob } from '@/api/job';
jest.useFakeTimers();

describe('Test component Register Company', () => {
  test('Case 1: Check render component render Register Company Template', () => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(RegisterCompany,
      localVue,
      store,
      router
    );
    expect(typeof RegisterCompany.data).toBe('function');
    const RegisterCompanyTemplate = wrapper.findComponent({ name: 'HROrganizationUserRegistration' });
    expect(RegisterCompanyTemplate.exists()).toBe(true);

    const title = wrapper.find('.head-description');
    expect(title.exists()).toBe(true);
    expect(title.text()).toEqual('TITLE_REGISTER_HR_H3');
  });

  test('Case 2: Check render component Register Company', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(RegisterCompany,
      localVue,
      store,
      router
    );
    const RegisterCompanyPage = wrapper.find('.hr-registration');
    expect(RegisterCompanyPage.exists()).toBe(true);

    const btn_next = wrapper.find('#btn-next');
    expect(btn_next.exists()).toBe(true);

    wrapper.destroy();
  });

  test('Case 3: Check All input initial empty', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(RegisterCompany,
      localVue,
      store,
      router
    );
    const initData = {
      company_name: '',
      company_name_jp: '',
      major_classification: '',
      middle_classification: '',

      post_code: '',
      prefectures: '',
      municipality: '',
      number: '',
      other: '',

      telephone_number: '',
      mail_address: '',
      url: '',

      job_title: '',
      full_name: '',
      full_name_furigana: '',
      representative_contact: '',
      assignee_contact: '',
    };

    await wrapper.setData({ formData: initData });
    expect(wrapper.vm.formData.company_name).toBe('');
    expect(wrapper.vm.formData.company_name_jp).toBe('');
    expect(wrapper.vm.formData.major_classification).toBe('');
    expect(wrapper.vm.formData.middle_classification).toBe('');
    expect(wrapper.vm.formData.post_code).toBe('');
    expect(wrapper.vm.formData.prefectures).toBe('');
    expect(wrapper.vm.formData.municipality).toBe('');
    expect(wrapper.vm.formData.number).toBe('');
    expect(wrapper.vm.formData.other).toBe('');
    expect(wrapper.vm.formData.telephone_number).toBe('');
    expect(wrapper.vm.formData.mail_address).toBe('');
    expect(wrapper.vm.formData.url).toBe('');
    expect(wrapper.vm.formData.job_title).toBe('');
    expect(wrapper.vm.formData.full_name).toBe('');
    expect(wrapper.vm.formData.full_name_furigana).toBe('');
    expect(wrapper.vm.formData.representative_contact).toBe('');
    expect(wrapper.vm.formData.assignee_contact).toBe('');
  });

  test('Case 4: Check All input change data', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(RegisterCompany,
      localVue,
      store,
      router
    );
    const initData = {
      company_name: 'company_name',
      company_name_jp: 'company_name_jp',
      major_classification: 'major_classification',
      middle_classification: 'middle_classification',

      post_code: 'post_code',
      prefectures: 'prefectures',
      municipality: 'municipality',
      number: 'number',
      other: 'other',

      telephone_number: 'telephone_number',
      mail_address: 'mail_address',
      url: 'url',

      job_title: 'job_title',
      full_name: 'full_name',
      full_name_furigana: 'full_name_furigana',
      representative_contact: 'representative_contact',
      assignee_contact: 'assignee_contact',
    };

    await wrapper.setData({ formData: initData });
    expect(wrapper.vm.formData.company_name).toBe(initData.company_name);
    expect(wrapper.vm.formData.company_name_jp).toBe(initData.company_name_jp);
    expect(wrapper.vm.formData.major_classification).toBe(initData.major_classification);
    expect(wrapper.vm.formData.middle_classification).toBe(initData.middle_classification);
    expect(wrapper.vm.formData.post_code).toBe(initData.post_code);
    expect(wrapper.vm.formData.prefectures).toBe(initData.prefectures);
    expect(wrapper.vm.formData.municipality).toBe(initData.municipality);
    expect(wrapper.vm.formData.number).toBe(initData.number);
    expect(wrapper.vm.formData.other).toBe(initData.other);
    expect(wrapper.vm.formData.telephone_number).toBe(initData.telephone_number);
    expect(wrapper.vm.formData.mail_address).toBe(initData.mail_address);
    expect(wrapper.vm.formData.url).toBe(initData.url);
    expect(wrapper.vm.formData.job_title).toBe(initData.job_title);
    expect(wrapper.vm.formData.full_name).toBe(initData.full_name);
    expect(wrapper.vm.formData.full_name_furigana).toBe(initData.full_name_furigana);
    expect(wrapper.vm.formData.representative_contact).toBe(initData.representative_contact);
    expect(wrapper.vm.formData.assignee_contact).toBe(initData.assignee_contact);

    wrapper.destroy();
  });

  test('Case 5: Check All data pass validate', async() => {
    const localVue = createLocalVue();
    // const registerCompanyCheckValidate = jest.fn();
    const checkvalidate = jest.fn();

    const wrapper = shallowMount(RegisterCompany, {
      localVue,
      router,
      store,
      methods: {
        // registerCompanyCheckValidate,
        checkvalidate,
      },
    });
    const RegisterCompanyPage = wrapper.find('.hr-registration');
    expect(RegisterCompanyPage.exists()).toBe(true);

    const createFormData = {
      company_name: 'Company name',
      company_name_jp: 'Company name (furigana)',
      major_classification: 1,
      middle_classification: 2,
      post_code: 'post code',
      prefectures: 'prefectures',
      municipality: 'municipalities',
      number: 'address',
      other: 'others',
      telephone_number: '+84 0312345678',
      mail_address: 'company1@gmail.com',
      url: 'https://okuridashi_hanoi.com',
      job_title: 'title',
      full_name: 'family name',
      full_name_furigana: 'Name (furigana)',
      representative_contact: ' ',
      assignee_contact: '+84 0312345688',
      status: 1,
      is_create: 0,
    };

    const displayAreaCode = {
      telephone_number: '+84',
      representative_contact: '+81',
      assignee_contact: '+84',
    };

    await wrapper.setData({ formData: createFormData });
    await wrapper.setData({ display_area_code: displayAreaCode });

    checkvalidate.mockImplementation = () => {
      console.log('checkvalidate mockImplementation', checkvalidate);

      if (
        createFormData.company_name !== '' &&
            createFormData.company_name_jp !== '' &&
            createFormData.major_classification !== '' &&
            createFormData.middle_classification !== '' &&
            createFormData.post_code !== '' &&
            createFormData.prefectures !== '' &&
            createFormData.municipality !== '' &&
            createFormData.number !== '' &&
            // createFormData.other !== '' &&
            displayAreaCode.telephone_number && // option khác
            createFormData.telephone_number &&
            createFormData.mail_address &&
            createFormData.url !== '' &&
            createFormData.job_title !== '' &&
            createFormData.full_name !== '' &&
            displayAreaCode.assignee_contact && // option khác
            createFormData.assignee_contact
      ) {
        return 'true';
      } else {
        return 'false';
      }
    };

    const btnNext = wrapper.find('#btn-next');
    expect(btnNext.exists()).toBe(true);
    // const spy = jest.spyOn(wrapper.vm, 'checkvalidate');
    btnNext.trigger('click');

    await checkvalidate();
    // expect(spy).toHaveBeenCalled();
    expect(checkvalidate).toHaveBeenCalled();

    wrapper.destroy();
  });

  test('Case 6: Check function getListMainjob respone code 200', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(RegisterCompany,
      localVue,
      store,
      router
    );

    // await getListMainjob()
    //   .then((response) => {
    //     console.log('getListMainjob response: ', response);
    //     expect(response['data']['code']).toBe(200);
    //   });

    wrapper.destroy();

    //
  });
  test('Case 7: Check function registerCompanyCheckValidate respone code 200', async() => {
    const localVue = createLocalVue();
    const registerCompanyCheckValidate = jest.fn();
    const checkvalidate = jest.fn();

    const wrapper = shallowMount(RegisterCompany, {
      localVue,
      router,
      store,
      methods: {
        registerCompanyCheckValidate,
        checkvalidate,
      },
    });

    const RegisterCompanyPage = wrapper.find('.hr-registration');
    expect(RegisterCompanyPage.exists()).toBe(true);

    const createFormData = {
      company_name: 'Company 9',
      company_name_jp: 'シティコンピュータ株式会社',
      major_classification: 1,
      middle_classification: 1,
      post_code: '1020093',
      prefectures: '東京都',
      municipality: '千代田区平河町',
      number: '1-7-10',
      other: '大盛丸平河町ビル2階',
      telephone_number: '+84 0312345678',
      mail_address: 'okuridashi_hanoi9@gmail.vn',
      url: 'https://okuridashi_hanoi.com',
      job_title: '代表取締役会長',
      full_name: '鈴木　太郎',
      full_name_furigana: 'スズキ　タロウ',
      representative_contact: '+84 0312345678',
      assignee_contact: '+84 0312345678',
      is_create: 0,
    };

    const displayAreaCode = {
      telephone_number: '+84',
      representative_contact: '+81',
      assignee_contact: '+84',
    };

    await wrapper.setData({ formData: createFormData });
    await wrapper.setData({ display_area_code: displayAreaCode });

    const btnNext = wrapper.find('#btn-next');
    expect(btnNext.exists()).toBe(true);

    // const spyCheckvalidate = jest.spyOn(wrapper.vm, 'checkvalidate');
    btnNext.trigger('click');
    expect(registerCompanyCheckValidate).toHaveBeenCalled();
    checkvalidate();
    expect(checkvalidate).toHaveBeenCalled();
    // expect(spyCheckvalidate).toHaveBeenCalled();

    // await companyRegister(createFormData)
    //   .then((response) => {
    //     const code = response.data.code;
    //     console.log('calll api response', response);
    //     expect(code).toBe(200);
    //   });

    //
  });
  test('Case 8: Check function registerCompanyPreview respone code 200', async() => {
    const localVue = createLocalVue();
    const registerCompanyCheckValidate = jest.fn();
    const checkvalidate = jest.fn();

    const wrapper = shallowMount(RegisterCompany, {
      localVue,
      router,
      store,
      methods: {
        registerCompanyCheckValidate,
        checkvalidate,
      },
    });

    const RegisterCompanyPage = wrapper.find('.hr-registration');
    expect(RegisterCompanyPage.exists()).toBe(true);

    const createFormData = {
      company_name: 'Company 8',
      company_name_jp: 'シティコンピュータ株式会社',
      major_classification: 1,
      middle_classification: 1,
      post_code: '1020093',
      prefectures: '東京都',
      municipality: '千代田区平河町',
      number: '1-7-10',
      other: '大盛丸平河町ビル2階',
      telephone_number: '+84 0312345678',
      mail_address: 'okuridashi_hanoi8@gmail.vn',
      url: 'https://okuridashi_hanoi.com',
      job_title: '代表取締役会長',
      full_name: '鈴木　太郎',
      full_name_furigana: 'スズキ　タロウ',
      representative_contact: '+84 0312345678',
      assignee_contact: '+84 0312345678',
      is_create: 1,
    };

    const displayAreaCode = {
      telephone_number: '+84',
      representative_contact: '+81',
      assignee_contact: '+84',
    };

    await wrapper.setData({ formData: createFormData });
    await wrapper.setData({ display_area_code: displayAreaCode });

    const btnNext = wrapper.find('#btn-next');
    expect(btnNext.exists()).toBe(true);

    btnNext.trigger('click');
    expect(registerCompanyCheckValidate).toHaveBeenCalled();
    checkvalidate();
    expect(checkvalidate).toHaveBeenCalled();

    // await companyRegister(createFormData)
    //   .then((response) => {
    //     const code = response.data.code;
    //     expect(code).toBe(200);
    //   });

    //
  });

  //
});
