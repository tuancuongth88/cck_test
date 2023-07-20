import store from '@/store';
import router from '@/router';
import HrList from '@/pages/Hr/create';

import { mount, createLocalVue } from '@vue/test-utils';

describe('TEST SCREEN HR LIST', () => {
  test('Test render component Hr List', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(HrList, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    const hrRegistration = wrapper.find('.hr-registration');
    expect(hrRegistration.exists()).toBe(true);

    const hrRegistrationContainer = wrapper.find('.hr-registration-container');
    expect(hrRegistrationContainer.exists()).toBe(true);

    const hrRegistrationWrap = wrapper.find('.hr-registration-wrap');
    expect(hrRegistrationWrap.exists()).toBe(true);

    const hrRegistrationHead = wrapper.find('.hr-registration-head');
    expect(hrRegistrationHead.exists()).toBe(true);

    const hrRegistrationHeadTitle = wrapper.find('.hr-registration-head-title');
    expect(hrRegistrationHeadTitle.exists()).toBe(true);

    const hrRegistrationFormAutox = wrapper.find('.hr-registration-form-autox');
    expect(hrRegistrationFormAutox.exists()).toBe(true);

    const hrRegistrationCategoryList = wrapper.find('.hr-registration-category-list');
    expect(hrRegistrationCategoryList.exists()).toBe(true);

    const hrRegistrationCategoryItem = wrapper.find('.hr-registration-category-item');
    expect(hrRegistrationCategoryItem.exists()).toBe(true);

    const hrRegistrationFormItemTitle = wrapper.find('.hr-registration-form-item-title');
    expect(hrRegistrationFormItemTitle.exists()).toBe(true);

    const hrRegisterBasicInformation = hrRegistrationFormItemTitle.find('.hr-basic-information');
    expect(hrRegisterBasicInformation.exists()).toBe(true);
    expect(hrRegisterBasicInformation.text()).toEqual('HR_REGISTER.BASIC_INFORMATION');

    const hrRegisterTitle = hrRegistrationFormItemTitle.find('.hr-register-title');
    expect(hrRegisterTitle.exists()).toBe(true);
    expect(hrRegisterTitle.text()).toEqual('HR_REGISTER.TITLE');

    await wrapper.setData({
      overlay: {
        opacity: 1,
        show: false,
        blur: '1rem',
        rounded: 'sm',
        variant: 'light',
      },

      statusModalConfirmLeaving: false,

      hrCreate: {
        hr_organization_id: '',

        organization_name: '',
        organization_japanese_name: '',

        country_id: null,
        country_name: '',

        full_name: '',
        full_name_furigana: '',
        gender_id: { id: null, content: '' },
        date_of_birth_year: null,
        date_of_birth_month: null,
        date_of_birth_day: null,
        work_form: { id: null, content: '' },
        preferred_working_hours: '',
        japanese_level: { id: null, content: '' },

        final_education_timing_year: null,
        final_education_timing_month: null,
        final_education_classification: { id: null, content: '' },
        final_education_degree: { id: null, content: '' },

        major_classification: { id: null, content: '' },
        middle_classification: { id: null, content: '' },

        main_job_career_1_year_from: null,
        main_job_career_1_month_from: null,
        main_job_career_1_year_to: null,
        main_job_career_1_month_to: null,
        main_job_career_1_time_now: false,
        main_job_career_1_department: { id: null, content: '' },
        main_job_career_1_job_title: null,
        main_job_career_1_textarea: '',

        main_job_career_2_year_from: null,
        main_job_career_2_month_from: null,
        main_job_career_2_year_to: null,
        main_job_career_2_month_to: null,
        main_job_career_2_time_now: false,
        main_job_career_2_department: { id: null, content: '' },
        main_job_career_2_job_title: null,
        main_job_career_2_textarea: '',

        main_job_career_3_year_from: null,
        main_job_career_3_month_from: null,
        main_job_career_3_year_to: null,
        main_job_career_3_month_to: null,
        main_job_career_3_time_now: false,
        main_job_career_3_department: { id: null, content: '' },
        main_job_career_3_job_title: null,
        main_job_career_3_textarea: '',

        personal_pr_special_textarea: '',
        remarks_textarea: '',

        telephone_phone_number_id: null,
        telephone_phone_number: '',
        mobile_phone_number_id: null,
        mobile_phone_number: '',
        mail_address: '',
        address_city: '',
        address_district: '',
        address_number: '',
        address_other: '',
        hometown_address_city: '',
        hometown_address_district: '',
        hometown_address_number: '',
        hometown_address_other: '',

        phone_number_options_common: [
          { value: null, text: '選択してください' },
          { value: 1, text: '+84' },
          { value: 2, text: '+81' },
        ],

        error: {
          full_name: '',
          full_name_furigana: '',
          date_of_birth_year: '',
          date_of_birth_month: '',
          date_of_birth_day: '',
          japanese_level: '',
          final_education_timing_year: '',
          final_education_timing_month: '',
          final_education_classification: '',
          final_education_degree: '',
          major_classification: '',
          middle_classification: '',
          main_job_career_1_year_from: '',
          main_job_career_1_month_from: '',
          main_job_career_1_year_to: '',
          main_job_career_1_month_to: '',
          mail_address: '',
        },
      },
    });

    wrapper.destroy();
  });

  test('Test call function handleFillInDefaultData when created', async() => {
    const handleFillInDefaultData = jest.fn();

    const localVue = createLocalVue();
    const wrapper = mount(HrList, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
      methods: {
        handleFillInDefaultData,
      },
    });

    const profile = store.getters.profile;

    const provincesr_option = [
      {
        'value': {
          'id': 1,
          'content': '男',
        },
        'text': '北海道',
      },
      {
        'value': {
          'id': 2,
          'content': '男',
        },
        'text': '青森県',
      },
      {
        'value': {
          'id': 3,
          'content': '男',
        },
        'text': '岩手県',
      },
      {
        'value': {
          'id': 4,
          'content': '男',
        },
        'text': '宮城県',
      },
      {
        'value': {
          'id': 5,
          'content': '男',
        },
        'text': '秋田県',
      },
      {
        'value': {
          'id': 6,
          'content': '男',
        },
        'text': '山形県',
      },
      {
        'value': {
          'id': 7,
          'content': '男',
        },
        'text': '福島県',
      },
      {
        'value': {
          'id': 8,
          'content': '男',
        },
        'text': '茨城県',
      },
      {
        'value': {
          'id': 9,
          'content': '男',
        },
        'text': '栃木県',
      },
      {
        'value': {
          'id': 10,
          'content': '男',
        },
        'text': '群馬県',
      },
    ];

    await wrapper.setData({
      hrCreate: {
        hr_organization_id: '',

        organization_name: '',
        organization_japanese_name: '',

        country_id: null,
        country_name: '',

        full_name: '',
        full_name_furigana: '',
        gender_id: { id: null, content: '' },
        date_of_birth_year: null,
        date_of_birth_month: null,
        date_of_birth_day: null,
        work_form: { id: null, content: '' },
        preferred_working_hours: '',
        japanese_level: { id: null, content: '' },

        final_education_timing_year: null,
        final_education_timing_month: null,
        final_education_classification: { id: null, content: '' },
        final_education_degree: { id: null, content: '' },

        major_classification: { id: null, content: '' },
        middle_classification: { id: null, content: '' },

        main_job_career_1_year_from: null,
        main_job_career_1_month_from: null,
        main_job_career_1_year_to: null,
        main_job_career_1_month_to: null,
        main_job_career_1_time_now: false,
        main_job_career_1_department: { id: null, content: '' },
        main_job_career_1_job_title: null,
        main_job_career_1_textarea: '',

        main_job_career_2_year_from: null,
        main_job_career_2_month_from: null,
        main_job_career_2_year_to: null,
        main_job_career_2_month_to: null,
        main_job_career_2_time_now: false,
        main_job_career_2_department: { id: null, content: '' },
        main_job_career_2_job_title: null,
        main_job_career_2_textarea: '',

        main_job_career_3_year_from: null,
        main_job_career_3_month_from: null,
        main_job_career_3_year_to: null,
        main_job_career_3_month_to: null,
        main_job_career_3_time_now: false,
        main_job_career_3_department: { id: null, content: '' },
        main_job_career_3_job_title: null,
        main_job_career_3_textarea: '',

        personal_pr_special_textarea: '',
        remarks_textarea: '',

        telephone_phone_number_id: null,
        telephone_phone_number: '',
        mobile_phone_number_id: null,
        mobile_phone_number: '',
        mail_address: '',
        address_city: '',
        address_district: '',
        address_number: '',
        address_other: '',
        hometown_address_city: '',
        hometown_address_district: '',
        hometown_address_number: '',
        hometown_address_other: '',
      },
    });

    handleFillInDefaultData.mockImplementation(() => {
      if (profile['hr_organization']) {
        const COUNTRY = provincesr_option.find((item) => {
          return item ? item['value']['id'] === profile['hr_organization']['country'] : 'asca';
        });

        wrapper.hrCreate.hr_organization_id = profile['hr_organization']['id'];

        wrapper.hrCreate.organization_name = profile['hr_organization']['corporate_name_en'];
        wrapper.hrCreate.organization_japanese_name = profile['hr_organization']['corporate_name_ja'];

        wrapper.hrCreate.country_id = COUNTRY['value']['id'];
        wrapper.hrCreate.country_name = COUNTRY['text'];
      }
    });

    handleFillInDefaultData();

    expect(wrapper.vm.hrCreate.hr_organization_id).toEqual(profile['hr_organization']['id']);
    expect(wrapper.vm.hrCreate.organization_name).toEqual(profile['hr_organization']['corporate_name_en']);
    expect(wrapper.vm.hrCreate.organization_japanese_name).toEqual(profile['hr_organization']['corporate_name_ja']);
    expect(wrapper.vm.hrCreate.country_id).toEqual(1);
    expect(wrapper.vm.hrCreate.country_name).toEqual('北海道');

    expect(handleFillInDefaultData).toHaveBeenCalled();

    wrapper.destroy();
  });

  test('Test call function handleRegisterHr when click button 保存', () => {
    const handleRegisterHr = jest.fn();

    const localVue = createLocalVue();

    const wrapper = mount(HrList, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
      methods: {
        handleRegisterHr,
      },
    });

    const BUTTON_REGISTER = wrapper.find('.register-btn');
    expect(BUTTON_REGISTER.exists()).toBe(true);

    const BUTTON_REGISTER_TEXT = BUTTON_REGISTER.find('.register-text');
    expect(BUTTON_REGISTER_TEXT.exists()).toBe(true);
    expect(BUTTON_REGISTER_TEXT.text()).toEqual('キャンセル');

    BUTTON_REGISTER.trigger('click');

    handleRegisterHr();

    expect(handleRegisterHr).toHaveBeenCalled();

    wrapper.destroy();
  });

  test('Test call function handleValidateFormData when click button 保存', async() => {
    const handleValidateFormData = jest.fn();

    const localVue = createLocalVue();

    const wrapper = mount(HrList, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
      methods: {
        handleValidateFormData,
      },
    });

    await wrapper.setData({
      validation_status: false,

      hrCreate: {
        hr_organization_id: '',

        organization_name: '',
        organization_japanese_name: '',

        country_id: null,
        country_name: '',

        full_name: '',
        full_name_furigana: '',
        gender_id: { id: null, content: '' },
        date_of_birth_year: null,
        date_of_birth_month: null,
        date_of_birth_day: null,
        work_form: { id: null, content: '' },
        preferred_working_hours: '',
        japanese_level: { id: null, content: '' },

        final_education_timing_year: null,
        final_education_timing_month: null,
        final_education_classification: { id: null, content: '' },
        final_education_degree: { id: null, content: '' },

        major_classification: { id: null, content: '' },
        middle_classification: { id: null, content: '' },

        main_job_career_1_year_from: null,
        main_job_career_1_month_from: null,
        main_job_career_1_year_to: null,
        main_job_career_1_month_to: null,
        main_job_career_1_time_now: false,
        main_job_career_1_department: { id: null, content: '' },
        main_job_career_1_job_title: null,
        main_job_career_1_textarea: '',

        main_job_career_2_year_from: null,
        main_job_career_2_month_from: null,
        main_job_career_2_year_to: null,
        main_job_career_2_month_to: null,
        main_job_career_2_time_now: false,
        main_job_career_2_department: { id: null, content: '' },
        main_job_career_2_job_title: null,
        main_job_career_2_textarea: '',

        main_job_career_3_year_from: null,
        main_job_career_3_month_from: null,
        main_job_career_3_year_to: null,
        main_job_career_3_month_to: null,
        main_job_career_3_time_now: false,
        main_job_career_3_department: { id: null, content: '' },
        main_job_career_3_job_title: null,
        main_job_career_3_textarea: '',

        personal_pr_special_textarea: '',
        remarks_textarea: '',

        telephone_phone_number_id: null,
        telephone_phone_number: '',
        mobile_phone_number_id: null,
        mobile_phone_number: '',
        mail_address: '',
        address_city: '',
        address_district: '',
        address_number: '',
        address_other: '',
        hometown_address_city: '',
        hometown_address_district: '',
        hometown_address_number: '',
        hometown_address_other: '',
      },

      error: {
        full_name: '',
        full_name_furigana: '',
        date_of_birth_year: '',
        date_of_birth_month: '',
        date_of_birth_day: '',
        japanese_level: '',
        final_education_timing_year: '',
        final_education_timing_month: '',
        final_education_classification: '',
        final_education_degree: '',
        major_classification: '',
        middle_classification: '',
        main_job_career_1_year_from: '',
        main_job_career_1_month_from: '',
        main_job_career_1_year_to: '',
        main_job_career_1_month_to: '',
        mail_address: '',
      },
    });

    const BUTTON_REGISTER = wrapper.find('.register-btn');
    expect(BUTTON_REGISTER.exists()).toBe(true);

    const BUTTON_REGISTER_TEXT = BUTTON_REGISTER.find('.register-text');
    expect(BUTTON_REGISTER_TEXT.exists()).toBe(true);
    expect(BUTTON_REGISTER_TEXT.text()).toEqual('キャンセル');

    BUTTON_REGISTER.trigger('click');

    handleValidateFormData.mockImplementation(() => {
      let result = false;

      if (wrapper.vm.hrCreate.full_name === '') {
        wrapper.vm.error.full_name = false;
      } else if (wrapper.vm.hrCreate.full_name_furigana === '') {
        wrapper.vm.error.full_name_furigana = false;
      } else if (wrapper.vm.hrCreate.date_of_birth_year === null) {
        wrapper.vm.error.date_of_birth_year = false;
      } else if (wrapper.vm.hrCreate.date_of_birth_month === null) {
        wrapper.vm.error.date_of_birth_month = false;
      } else if (wrapper.vm.hrCreate.date_of_birth_day === null) {
        wrapper.vm.error.date_of_birth_day = false;
      } else if (wrapper.vm.hrCreate.japanese_level['id'] === null) {
        wrapper.vm.error.japanese_level = false;
      } else if (wrapper.vm.hrCreate.final_education_timing_year === null) {
        wrapper.vm.error.final_education_timing_year = false;
      } else if (wrapper.vm.hrCreate.final_education_timing_month === null) {
        wrapper.vm.error.final_education_timing_month = false;
      } else if (wrapper.vm.hrCreate.final_education_classification['id'] === null) {
        wrapper.vm.error.final_education_classification = false;
      } else if (wrapper.vm.hrCreate.final_education_degree['id'] === null) {
        wrapper.vm.error.final_education_degree = false;
      } else if (wrapper.vm.hrCreate.major_classification['id'] === null) {
        wrapper.vm.error.major_classification = false;
      } else if (wrapper.vm.hrCreate.middle_classification['id'] === null) {
        wrapper.vm.error.middle_classification = false;
      } else if (wrapper.vm.hrCreate.main_job_career_1_year_from === null) {
        wrapper.vm.error.main_job_career_1_year_from = false;
      } else if (wrapper.vm.hrCreate.main_job_career_1_month_from === null) {
        wrapper.vm.error.main_job_career_1_month_from = false;
      } else if (wrapper.vm.hrCreate.main_job_career_1_year_from !== null && wrapper.vm.hrCreate.main_job_career_1_month_from !== null && !wrapper.vm.hrCreate.main_job_career_1_time_now) {
        if (wrapper.vm.hrCreate.main_job_career_1_year_to === null) {
          wrapper.vm.error.main_job_career_1_year_to = false;
        } else if (wrapper.vm.hrCreate.main_job_career_1_month_to === null) {
          wrapper.vm.error.main_job_career_1_month_to = false;
        } else if (wrapper.vm.hrCreate.mail_address === '') {
          wrapper.vm.error.mail_address = false;
        } else {
          return true;
        }
      } else if (wrapper.vm.hrCreate.mail_address === '') {
        wrapper.vm.error.mail_address = false;
      } else {
        result = true;
      }

      wrapper.validation_status = result;
    });

    handleValidateFormData();

    expect(handleValidateFormData).toHaveBeenCalled();

    expect(wrapper.vm.validation_status).toBe(true);

    wrapper.destroy();
  });

  test('Test call function formatDateTime to format date time before send data', async() => {
    const formatDateTime = jest.fn();

    const localVue = createLocalVue();

    const wrapper = mount(HrList, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
      methods: {
        formatDateTime,
      },
    });

    await wrapper.setData({
      month: 1,
    });

    formatDateTime.mockImplementation((month) => {
      if (month < 10) {
        return '0' + month;
      } else {
        return month;
      }
    });

    formatDateTime(wrapper.vm.month);

    expect(formatDateTime).toHaveBeenCalled();

    expect(wrapper.vm.month).toEqual('01');

    wrapper.destroy();
  });

  test('Test call function handleChangeFormData when input data in the form', async() => {
    const handleChangeFormData = jest.fn();

    const localVue = createLocalVue();

    const wrapper = mount(HrList, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
      methods: {
        handleChangeFormData,
      },
    });

    await wrapper.setData({
      hrCreate: {
        hr_organization_id: '',

        organization_name: '',
        organization_japanese_name: '',

        country_id: null,
        country_name: '',

        full_name: '',
        full_name_furigana: '',
      },
    });

    const HR_ORGANIZATION_ID = wrapper.find('.hr_organization_id');
    expect(HR_ORGANIZATION_ID.exists()).toBe(true);

    HR_ORGANIZATION_ID.setValue('1');

    wrapper.vm.hrCreate.hr_organization_id = HR_ORGANIZATION_ID.value;

    const ORGANIZATION_NAME = wrapper.find('.organization_name');
    expect(ORGANIZATION_NAME.exists()).toBe(true);

    ORGANIZATION_NAME.setValue('1');
    wrapper.vm.hrCreate.organization_name = ORGANIZATION_NAME.value;

    const ORGANIZATION_JAPANESE_NAME = wrapper.find('.organization_japanese_name');
    expect(ORGANIZATION_JAPANESE_NAME.exists()).toBe(true);

    ORGANIZATION_JAPANESE_NAME.setValue('1');
    wrapper.vm.hrCreate.organization_japanese_name = ORGANIZATION_JAPANESE_NAME.value;

    const COUNTRY_ID = wrapper.find('.country_id');
    expect(COUNTRY_ID.exists()).toBe(true);

    COUNTRY_ID.setValue('1');
    wrapper.vm.hrCreate.country_id = COUNTRY_ID.value;

    const COUNTRY_NAME = wrapper.find('.country_name');
    expect(COUNTRY_NAME.exists()).toBe(true);

    COUNTRY_NAME.setValue('1');
    wrapper.vm.hrCreate.country_name = COUNTRY_NAME.value;

    const FULL_NAME = wrapper.find('.full_name');
    expect(FULL_NAME.exists()).toBe(true);

    FULL_NAME.setValue('1');
    wrapper.vm.hrCreate.full_name = FULL_NAME.value;

    const FULL_NAME_FURIGANA = wrapper.find('.full_name_furigana');
    expect(FULL_NAME_FURIGANA.exists()).toBe(true);

    FULL_NAME_FURIGANA.setValue('1');
    wrapper.vm.hrCreate.full_name_furigana = FULL_NAME_FURIGANA.value;

    handleChangeFormData();

    expect(handleChangeFormData).toHaveBeenCalled();

    expect(wrapper.vm.hrCreate.hr_organization_id).toEqual('1');
    expect(wrapper.vm.hrCreate.organization_name).toEqual('1');
    expect(wrapper.vm.hrCreate.organization_japanese_name).toEqual('1');
    expect(wrapper.vm.hrCreate.country_id).toEqual('1');
    expect(wrapper.vm.hrCreate.country_name).toEqual('1');
    expect(wrapper.vm.hrCreate.full_name).toEqual('1');
    expect(wrapper.vm.hrCreate.full_name_furigana).toEqual('1');

    wrapper.destroy();
  });

  test('Test call function handCancelCreateHr when click on button キャンセル', async() => {
    const handCancelCreateHr = jest.fn();

    const localVue = createLocalVue();

    const wrapper = mount(HrList, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
      methods: {
        handCancelCreateHr,
      },
    });

    await wrapper.setData({
      editting: false,
    });

    const BUTTON_CANCEL = wrapper.find('.cancel-btn');
    expect(BUTTON_CANCEL.exists()).toBe(true);

    const BUTTON_CANCEL_TEXT = BUTTON_CANCEL.find('.cancel-text');
    expect(BUTTON_CANCEL_TEXT.exists()).toBe(true);
    expect(BUTTON_CANCEL_TEXT.text()).toEqual('キャンセル');

    BUTTON_CANCEL.trigger('click');

    handCancelCreateHr.mockImplementation(() => {
      wrapper.vm.editting = true;
    });

    handCancelCreateHr();

    expect(handCancelCreateHr).toHaveBeenCalled();

    expect(wrapper.vm.editting).toBe(true);

    wrapper.destroy();
  });

  test('Test call function handleToggleConfirmLeavingModal when click on button キャンセル', async() => {
    const handleToggleConfirmLeavingModal = jest.fn();

    const localVue = createLocalVue();

    const wrapper = mount(HrList, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
      methods: {
        handleToggleConfirmLeavingModal,
      },
    });

    await wrapper.setData({
      statusModalConfirmLeaving: false,
    });

    const BUTTON_CANCEL = wrapper.find('.cancel-btn');
    expect(BUTTON_CANCEL.exists()).toBe(true);

    const BUTTON_CANCEL_TEXT = BUTTON_CANCEL.find('.cancel-text');
    expect(BUTTON_CANCEL_TEXT.exists()).toBe(true);
    expect(BUTTON_CANCEL_TEXT.text()).toEqual('キャンセル');

    BUTTON_CANCEL.trigger('click');

    handleToggleConfirmLeavingModal.mockImplementation(() => {
      wrapper.vm.statusModalConfirmLeaving = !wrapper.vm.statusModalConfirmLeaving;
    });

    handleToggleConfirmLeavingModal();

    expect(handleToggleConfirmLeavingModal).toHaveBeenCalled();

    expect(wrapper.vm.statusModalConfirmLeaving).toBe(!wrapper.vm.statusModalConfirmLeaving);

    wrapper.destroy();
  });

  test('Test render enough input fields', () => {
    const localVue = createLocalVue();
    const wrapper = mount(HrList, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    const INPUT_FIELDS = wrapper.findAll('.input-field');
    expect(INPUT_FIELDS.length).toEqual(32);

    wrapper.destroy();
  });
});
