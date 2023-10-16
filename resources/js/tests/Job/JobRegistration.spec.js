import { shallowMount, createLocalVue } from '@vue/test-utils';

import store from '@/store';
import router from '@/router';
import JobRegistration from '@/pages/CompanyManagement/Job/create';

const localVue = createLocalVue();

describe(`TEST SCREEN JOB REGISTRATRION`, () => {
  test('TEST RENDER COMPONENT JOB REGISTRATION', () => {
    const wrapper = shallowMount(JobRegistration, {
      store,
      router,
      localVue,
    });

    const OVERLAY = {
      opacity: 1,
      show: false,
      blur: '1rem',
      rounded: 'sm',
      variant: 'light',
    };

    expect(wrapper.vm.overlay).toStrictEqual(OVERLAY);

    wrapper.destroy();
  });

  test('TEST RENDER COMPONENT JOB REGISTRATION DEFAULT DATA', () => {
    const wrapper = shallowMount(JobRegistration, {
      store,
      router,
      localVue,
    });

    const OVERLAY = {
      opacity: 1,
      show: false,
      blur: '1rem',
      rounded: 'sm',
      variant: 'light',
    };

    const housing_allowance = false;
    const welfare_enhancement = false;
    const severance_pay = false;
    const more_annual_holidays = false;
    const residence = false;
    const no_experience_ok = false;
    const foreign_managerial_staff_hired = false;
    const remote_work_available = false;
    const moving_allowance = false;

    const FORM_DATA = {
      job_title: '',
      company_id: null,
      company_name: '',
      application_period_start: '',
      application_period_end: '',
      major_classification: null,
      middle_classification: null,
      job_description: '',
      application_condition: '',
      application_requirement: '',
      country: null,
      language_requirement: [],
      other_skill: '',
      preferred_skill: '',
      form_of_employment: '',
      vacation: '',
      expected_income: '',
      working_time_to: null,
      working_time_from: null,
      working_place: null,
      working_place_detail: '',
      treatment_welfare: '',
      company_pr_appeal: '',
      bonus_pay: '',
      overtime: '',
      transfer: '',
      passive_smoking: '',
      passion: '',
      interview_follow: null,
      establishment_year: '',
      startup_year: '',
      capital: '',
      proceeds: '',
      number_of_staffs: '',
      number_of_operations: '',
      number_of_shops: '',
      number_of_factories: '',
      fiscal_year: '',
    };

    const ERROR = {
      job_title: null,
      company_id: null,
      application_period_start: null,
      application_period_end: null,
      major_classification: null,
      middle_classification: null,
      job_description: null,
      application_condition: null,
      application_requirement: null,
      country: null,
      language_requirement: null,
      other_skill: null,
      form_of_employment: null,
      working_time: null,
      vacation: null,
      expected_income: null,
      working_time_from: null,
      working_time_to: null,
      working_place: null,
      treatment_welfare: null,
      bonus_pay: null,
      overtime: null,
      transfer: null,
      passive_smoking: null,
      passion: null,
      interview_follow: null,
    };

    const WORKING_TIME_OPTIONS = [
      { value: null, text: '選択してください', disabled: true },
      { value: 1, text: '00:00', disabled: false },
      { value: 2, text: '00:30', disabled: false },
      { value: 3, text: '01:00', disabled: false },
      { value: 4, text: '01:30', disabled: false },
      { value: 5, text: '02:00', disabled: false },
      { value: 6, text: '02:30', disabled: false },
      { value: 7, text: '03:00', disabled: false },
      { value: 8, text: '03:30', disabled: false },
      { value: 9, text: '04:00', disabled: false },
      { value: 10, text: '04:30', disabled: false },
      { value: 11, text: '05:00', disabled: false },
      { value: 12, text: '05:30', disabled: false },
      { value: 13, text: '06:00', disabled: false },
      { value: 14, text: '06:30', disabled: false },
      { value: 15, text: '07:00', disabled: false },
      { value: 16, text: '07:30', disabled: false },
      { value: 17, text: '08:00', disabled: false },
      { value: 18, text: '08:30', disabled: false },
      { value: 19, text: '09:00', disabled: false },
      { value: 20, text: '09:30', disabled: false },
      { value: 21, text: '10:00', disabled: false },
      { value: 22, text: '10:30', disabled: false },
      { value: 23, text: '11:00', disabled: false },
      { value: 24, text: '11:30', disabled: false },
      { value: 25, text: '12:00', disabled: false },
      { value: 26, text: '12:30', disabled: false },
      { value: 27, text: '13:00', disabled: false },
      { value: 28, text: '13:30', disabled: false },
      { value: 29, text: '14:00', disabled: false },
      { value: 30, text: '14:30', disabled: false },
      { value: 31, text: '15:30', disabled: false },
      { value: 32, text: '15:00', disabled: false },
      { value: 33, text: '15:30', disabled: false },
      { value: 34, text: '16:00', disabled: false },
      { value: 35, text: '16:30', disabled: false },
      { value: 36, text: '17:00', disabled: false },
      { value: 37, text: '17:30', disabled: false },
      { value: 38, text: '18:00', disabled: false },
      { value: 39, text: '18:30', disabled: false },
      { value: 40, text: '19:00', disabled: false },
      { value: 41, text: '19:30', disabled: false },
      { value: 42, text: '20:00', disabled: false },
      { value: 43, text: '20:30', disabled: false },
      { value: 44, text: '21:00', disabled: false },
      { value: 45, text: '21:30', disabled: false },
      { value: 46, text: '22:00', disabled: false },
      { value: 47, text: '22:30', disabled: false },
      { value: 48, text: '23:00', disabled: false },
      { value: 49, text: '23:30', disabled: false },
    ];

    const INTERVIEW_FOLLOW_OPTIONS = [
      { value: null, text: '選択してください', disabled: true },
      { value: 1, text: '一次面接まで', disabled: false },
      { value: 2, text: '二次面接まで', disabled: false },
      { value: 3, text: '三次面接まで', disabled: false },
      { value: 4, text: '四次面接まで', disabled: false },
      { value: 5, text: '五次面接まで', disabled: false },
    ];

    const COMPANY_LIST = [
      { value: null, text: '選択してください', disabled: true },
    ];

    const LIST_MAIN_JOB_OCCUPATION = [
      { value: null, text: '選択してください', sub_options: [], disabled: true },
    ];

    const LIST_MIDDLE_CLASSIFICATION = [
      { value: null, text: '選択してください', disabled: true },
    ];

    const CITY_LIST = [
      {
        timezone: '北海道・東北',
        options: [
          { id: 1, name_en: 'Hokkaido', name_ja: '北海道' },
          { id: 2, name_en: 'Aomori', name_ja: '青森県' },
          { id: 3, name_en: 'Iwate', name_ja: '岩手県' },
          { id: 4, name_en: 'Miyagi', name_ja: '宮城県' },
          { id: 5, name_en: 'Akita', name_ja: '秋田県' },
          { id: 6, name_en: 'Yamagata', name_ja: '山形県' },
          { id: 7, name_en: 'Fukushima', name_ja: '福島県' },
        ],
      },
      {
        timezone: '北陸・甲信越',
        options: [
          { id: 16, name_en: 'Toyama', name_ja: '富山県' },
          { id: 17, name_en: 'Ishikawa', name_ja: '石川県' },
          { id: 18, name_en: 'Fukui', name_ja: '福井県' },
          { id: 15, name_en: 'Niigata', name_ja: '新潟県' },
          { id: 19, name_en: 'Yamanashi', name_ja: '山梨県' },
          { id: 20, name_en: 'Nagano', name_ja: '長野県' },
        ],
      },
      {
        timezone: '関東',
        options: [
          { id: 13, name_en: 'Tokyo', name_ja: '東京都' },
          { id: 14, name_en: 'Kanagawa', name_ja: '神奈川県' },
          { id: 12, name_en: 'Chiba', name_ja: '千葉県' },
          { id: 11, name_en: 'Saitama', name_ja: '埼玉県' },
          { id: 8, name_en: 'Ibaraki', name_ja: '茨城県' },
          { id: 9, name_en: 'Tochigi', name_ja: '栃木県' },
          { id: 10, name_en: 'Gunma', name_ja: '群馬県' },
        ],
      },
      {
        timezone: '東海',
        options: [
          { id: 23, name_en: 'Aichi', name_ja: '愛知県' },
          { id: 22, name_en: 'Shizuoka', name_ja: '静岡県' },
          { id: 21, name_en: 'Gifu', name_ja: '岐阜県' },
          { id: 24, name_en: 'Mie', name_ja: '三重県' },
        ],
      },
      {
        timezone: '関西',
        options: [
          { id: 27, name_en: 'Osaka', name_ja: '大阪府' },
          { id: 26, name_en: 'Kyoto', name_ja: '京都府' },
          { id: 28, name_en: 'Hyogo', name_ja: '兵庫県' },
          { id: 25, name_en: 'Shiga', name_ja: '滋賀県' },
          { id: 29, name_en: 'Nara', name_ja: '奈良県' },
          { id: 30, name_en: 'Wakayama', name_ja: '和歌山県' },
        ],
      },
      {
        timezone: '中国',
        options: [
          { id: 34, name_en: 'Hiroshima', name_ja: '広島県' },
          { id: 33, name_en: 'Okayama', name_ja: '岡山県' },
          { id: 31, name_en: 'Tottori', name_ja: '鳥取県' },
          { id: 32, name_en: 'Shimane', name_ja: '島根県' },
          { id: 35, name_en: 'Yamaguchi', name_ja: '山口県' },
        ],
      },
      {
        timezone: '四国',
        options: [
          { id: 36, name_en: 'Tokushima', name_ja: '徳島県' },
          { id: 37, name_en: 'Kagawa', name_ja: '香川県' },
          { id: 38, name_en: 'Ehime', name_ja: '愛媛県' },
          { id: 39, name_en: 'Kochi', name_ja: '高知県' },
        ],
      },
      {
        timezone: '九州・沖縄',
        options: [
          { id: 40, name_en: 'Fukuoka', name_ja: '福岡県' },
          { id: 43, name_en: 'Kumamoto', name_ja: '熊本県' },
          { id: 41, name_en: 'Saga', name_ja: '佐賀県' },
          { id: 42, name_en: 'Nagasaki', name_ja: '長崎県' },
          { id: 44, name_en: 'Ōita', name_ja: '大分県' },
          { id: 45, name_en: 'Miyazaki', name_ja: '宮崎県' },
          { id: 46, name_en: 'Kagoshima', name_ja: '鹿児島県' },
          { id: 47, name_en: 'Okinawa', name_ja: '沖縄県' },
        ],
      },
    ];

    expect(wrapper.vm.overlay).toStrictEqual(OVERLAY);

    expect(wrapper.vm.housing_allowance).toBe(housing_allowance);
    expect(wrapper.vm.welfare_enhancement).toBe(welfare_enhancement);
    expect(wrapper.vm.severance_pay).toBe(severance_pay);
    expect(wrapper.vm.more_annual_holidays).toBe(more_annual_holidays);
    expect(wrapper.vm.residence).toBe(residence);
    expect(wrapper.vm.no_experience_ok).toBe(no_experience_ok);
    expect(wrapper.vm.foreign_managerial_staff_hired).toBe(foreign_managerial_staff_hired);
    expect(wrapper.vm.remote_work_available).toBe(remote_work_available);
    expect(wrapper.vm.moving_allowance).toBe(moving_allowance);

    expect(wrapper.vm.formData).toStrictEqual(FORM_DATA);

    expect(wrapper.vm.error).toStrictEqual(ERROR);

    expect(wrapper.vm.country_options).toHaveLengthGreaterThan(0);

    expect(wrapper.vm.working_time_options).toStrictEqual(WORKING_TIME_OPTIONS);

    expect(wrapper.vm.interview_follow_options).toStrictEqual(INTERVIEW_FOLLOW_OPTIONS);

    expect(wrapper.vm.company_list).toStrictEqual(COMPANY_LIST);

    expect(wrapper.vm.list_main_job_occupation).toBe(LIST_MAIN_JOB_OCCUPATION);
    expect(wrapper.vm.list_middle_classification).toBe(LIST_MIDDLE_CLASSIFICATION);

    expect(wrapper.vm.city_list).toBe(CITY_LIST);

    expect(wrapper.vm.min_application_date_from).not.toBe(null);
    expect(wrapper.vm.min_application_date_to).toBe('');

    wrapper.destroy();
  });

  test('TEST CALL FUNCTION handleInitComponentData WHEN COMPONENT CREATED', () => {
    const wrapper = shallowMount(JobRegistration, {
      store,
      router,
      localVue,
    });

    const handleInitComponentData = jest.fn();

    wrapper.setMethods({
      handleInitComponentData,
    });

    wrapper.vm.handleInitComponentData();

    expect(handleInitComponentData).toHaveBeenCalled();

    wrapper.destroy();
  });

  test('TEST CALL FUNCTION handleSetMinApplicationDateFrom WHEN COMPONENT CREATED', () => {
    const wrapper = shallowMount(JobRegistration, {
      store,
      router,
      localVue,
    });

    const handleInitComponentData = jest.fn();
    const handleSetMinApplicationDateFrom = jest.fn();

    wrapper.setMethods({
      handleInitComponentData,
      handleSetMinApplicationDateFrom,
    });

    wrapper.vm.handleInitComponentData();

    expect(handleInitComponentData).toHaveBeenCalled();

    expect(handleSetMinApplicationDateFrom).toHaveBeenCalled();

    wrapper.destroy();
  });

  test('TEST CALL FUNCTION handleGetListCompany WHEN COMPONENT CREATED', () => {
    const wrapper = shallowMount(JobRegistration, {
      store,
      router,
      localVue,
    });

    const handleInitComponentData = jest.fn();
    const handleGetListCompany = jest.fn();

    wrapper.setMethods({
      handleInitComponentData,
      handleGetListCompany,
    });

    wrapper.vm.handleInitComponentData();

    expect(handleInitComponentData).toHaveBeenCalled();

    expect(handleGetListCompany).toHaveBeenCalled();

    wrapper.destroy();
  });

  test('TEST CALL FUNCTION handleGetCompanyInfo WHEN COMPONENT CREATED', () => {
    const wrapper = shallowMount(JobRegistration, {
      store,
      router,
      localVue,
    });

    const handleInitComponentData = jest.fn();
    const handleGetCompanyInfo = jest.fn();

    wrapper.setMethods({
      handleInitComponentData,
      handleGetCompanyInfo,
    });

    wrapper.vm.handleInitComponentData();

    expect(handleInitComponentData).toHaveBeenCalled();

    expect(handleGetCompanyInfo).toHaveBeenCalled();

    wrapper.destroy();
  });

  test('TEST CALL FUNCTION handleGetListJobOccupation WHEN COMPONENT CREATED', () => {
    const wrapper = shallowMount(JobRegistration, {
      store,
      router,
      localVue,
    });

    const handleInitComponentData = jest.fn();
    const handleGetListJobOccupation = jest.fn();

    wrapper.setMethods({
      handleInitComponentData,
      handleGetListJobOccupation,
    });

    wrapper.vm.handleInitComponentData();

    expect(handleInitComponentData).toHaveBeenCalled();

    expect(handleGetListJobOccupation).toHaveBeenCalled();

    wrapper.destroy();
  });

  test('TEST CHECK ROLE WHEN RUNNING FUNCTION handleInitComponentData', async() => {
    const wrapper = shallowMount(JobRegistration, {
      store,
      router,
      localVue,
    });

    const ROLE = store.getters.profile['type'];

    const handleInitComponentData = jest.fn();
    const handleSetMinApplicationDateFrom = jest.fn();
    const handleGetListCompany = jest.fn();
    const handleGetCompanyInfo = jest.fn();
    const handleGetListJobOccupation = jest.fn();

    handleInitComponentData.mockImplementation(async() => {
      await handleSetMinApplicationDateFrom();

      if (ROLE && [1, 2].includes(ROLE)) {
        await handleGetListCompany();
      } else {
        await handleGetCompanyInfo();
      }

      await handleGetListJobOccupation();
    });

    wrapper.setMethods({
      handleInitComponentData,
      handleSetMinApplicationDateFrom,
      handleGetListCompany,
      handleGetCompanyInfo,
      handleGetListJobOccupation,
    });

    wrapper.vm.handleInitComponentData();

    expect(handleSetMinApplicationDateFrom).toHaveBeenCalled();

    if (ROLE && [1, 2].includes(ROLE)) {
      expect(handleGetListCompany).toHaveBeenCalled();
    } else {
      expect(handleGetCompanyInfo).toHaveBeenCalled();
    }

    expect(handleGetListJobOccupation).toHaveBeenCalled();

    wrapper.destroy();
  });

  test('TEST CALL FUNCTION handleCreateJob WHEN CLICK ON REGISTER BUTTON', async() => {
    const wrapper = shallowMount(JobRegistration, {
      store,
      router,
      localVue,
    });

    const onSubmit = jest.fn();
    const handleCreateJob = jest.fn();

    const LANGUAGE_REQUIREMENT = [
      {
        id: 6,
        name: '資格なし',
        deleted_at: null,
        created_at: null,
        updated_at: null,
        pivot: {
          work_id: 5,
          language_requirement_id: 6,
        },
      },
    ];

    const PASSION = [
      {
        id: 1,
        name_en: 'Housing allowance',
        name_jp: '住宅手当有',
        deleted_at: null,
        created_at: null,
        updated_at: null,
        pivot: {
          work_id: 5,
          passion_id: 1,
        },
      },
      {
        id: 7,
        name_en: 'experience of foreigners appointed to managerial positions',
        name_jp: '外国人管理職登用実績有',
        deleted_at: null,
        created_at: null,
        updated_at: null,
        pivot: {
          work_id: 5,
          passion_id: 7,
        },
      },
      {
        id: 8,
        name_en: 'Remote work ok',
        name_jp: 'リモート勤務可',
        deleted_at: null,
        created_at: null,
        updated_at: null,
        pivot: {
          work_id: 5,
          passion_id: 8,
        },
      },
    ];

    const DATA_SAMPLE = {
      title: 'IT営業',
      major_classification_id: 12,
      middle_classification_id: 60,
      company_id: 1,
      application_period_from: '2023-08-02',
      application_period_to: '2023-08-19',
      job_description: 'a',
      application_condition: 'a',
      application_requirement: 'a',
      language_requirements: LANGUAGE_REQUIREMENT,
      other_skill: 'a',
      preferred_skill: null,
      form_of_employment: 1,
      working_time_from: '04:00',
      working_time_to: '15:30',
      vacation: 'a',
      expected_income: '1234',
      city_id: 4,
      working_palace_detail: 'a',
      treatment_welfare: 'a',
      company_pr_appeal: 'a',
      bonus_pay_rise: 1,
      over_time: 2,
      transfer: 2,
      passive_smoking: 1,
      passions: PASSION,
      interview_follow: 2,
    };

    wrapper.setData({
      formData: DATA_SAMPLE,
    });

    wrapper.setMethods({
      onSubmit,
      handleCreateJob,
    });

    await wrapper.find('.btn-register').trigger('click');

    expect(onSubmit).toHaveBeenCalled();

    expect(handleCreateJob).toHaveBeenCalled();

    wrapper.destroy();
  });

  test('TEST CALL FUNCTION handleValidationFormData BEFORE CALL FUNCTION handleCreateJob', async() => {
    const wrapper = shallowMount(JobRegistration, {
      store,
      router,
      localVue,
    });

    const onSubmit = jest.fn();
    const handleValidationFormData = jest.fn();
    const handleCreateJob = jest.fn();

    wrapper.setMethods({
      onSubmit,
      handleValidationFormData,
      handleCreateJob,
    });

    await wrapper.find('.btn-register').trigger('click');

    onSubmit.mockImplementation(() => {
      if (handleValidationFormData()) {
        handleCreateJob();
      } else {
        return false;
      }
    });

    expect(onSubmit).toHaveBeenCalled();

    expect(handleCreateJob).toHaveBeenCalled();

    wrapper.destroy();
  });

  test('TEST CALL FUNCTION handleBack WHEN CLICK ON BACK BUTTON', () => {
    const wrapper = shallowMount(JobRegistration, {
      store,
      router,
      localVue,
    });

    const handleBack = jest.fn();

    wrapper.setMethods({
      handleBack,
    });

    wrapper.find('.btn-back').trigger('click');

    expect(handleBack).toHaveBeenCalled();

    wrapper.destroy();
  });
});
