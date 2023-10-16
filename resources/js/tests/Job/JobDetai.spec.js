import { shallowMount, createLocalVue } from '@vue/test-utils';

import store from '@/store';
import router from '@/router';
import JobDetail from '@/pages/CompanyManagement/Job/detail';

const localVue = createLocalVue();

describe(`TEST SCREEN JOB DETAIL`, () => {
  test('TEST RENDER COMPONENT JOB DETAIL', () => {
    const wrapper = shallowMount(JobDetail, {
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

  test('TEST RENDER COMPONENT JOB DETAIL DEFAULT DATA', () => {
    const wrapper = shallowMount(JobDetail, {
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

    const CITY_LIST = [
      { id: 1, name_en: 'Hokkaido', name_ja: '北海道' },
      { id: 2, name_en: 'Aomori', name_ja: '青森県' },
      { id: 3, name_en: 'Iwate', name_ja: '岩手県' },
      { id: 4, name_en: 'Miyagi', name_ja: '宮城県' },
      { id: 5, name_en: 'Akita', name_ja: '秋田県' },
      { id: 6, name_en: 'Yamagata', name_ja: '山形県' },
      { id: 7, name_en: 'Fukushima', name_ja: '福島県' },
      { id: 16, name_en: 'Toyama', name_ja: '富山県' },
      { id: 17, name_en: 'Ishikawa', name_ja: '石川県' },
      { id: 18, name_en: 'Fukui', name_ja: '福井県' },
      { id: 15, name_en: 'Niigata', name_ja: '新潟県' },
      { id: 19, name_en: 'Yamanashi', name_ja: '山梨県' },
      { id: 20, name_en: 'Nagano', name_ja: '長野県' },
      { id: 13, name_en: 'Tokyo', name_ja: '東京都' },
      { id: 14, name_en: 'Kanagawa', name_ja: '神奈川県' },
      { id: 12, name_en: 'Chiba', name_ja: '千葉県' },
      { id: 11, name_en: 'Saitama', name_ja: '埼玉県' },
      { id: 8, name_en: 'Ibaraki', name_ja: '茨城県' },
      { id: 9, name_en: 'Tochigi', name_ja: '栃木県' },
      { id: 10, name_en: 'Gunma', name_ja: '群馬県' },
      { id: 23, name_en: 'Aichi', name_ja: '愛知県' },
      { id: 22, name_en: 'Shizuoka', name_ja: '静岡県' },
      { id: 21, name_en: 'Gifu', name_ja: '岐阜県' },
      { id: 24, name_en: 'Mie', name_ja: '三重県' },
      { id: 27, name_en: 'Osaka', name_ja: '大阪府' },
      { id: 26, name_en: 'Kyoto', name_ja: '京都府' },
      { id: 28, name_en: 'Hyogo', name_ja: '兵庫県' },
      { id: 25, name_en: 'Shiga', name_ja: '滋賀県' },
      { id: 29, name_en: 'Nara', name_ja: '奈良県' },
      { id: 30, name_en: 'Wakayama', name_ja: '和歌山県' },
      { id: 34, name_en: 'Hiroshima', name_ja: '広島県' },
      { id: 33, name_en: 'Okayama', name_ja: '岡山県' },
      { id: 31, name_en: 'Tottori', name_ja: '鳥取県' },
      { id: 32, name_en: 'Shimane', name_ja: '島根県' },
      { id: 35, name_en: 'Yamaguchi', name_ja: '山口県' },
      { id: 36, name_en: 'Tokushima', name_ja: '徳島県' },
      { id: 37, name_en: 'Kagawa', name_ja: '香川県' },
      { id: 38, name_en: 'Ehime', name_ja: '愛媛県' },
      { id: 39, name_en: 'Kochi', name_ja: '高知県' },
      { id: 40, name_en: 'Fukuoka', name_ja: '福岡県' },
      { id: 43, name_en: 'Kumamoto', name_ja: '熊本県' },
      { id: 41, name_en: 'Saga', name_ja: '佐賀県' },
      { id: 42, name_en: 'Nagasaki', name_ja: '長崎県' },
      { id: 44, name_en: 'Ōita', name_ja: '大分県' },
      { id: 45, name_en: 'Miyazaki', name_ja: '宮崎県' },
      { id: 46, name_en: 'Kagoshima', name_ja: '鹿児島県' },
      { id: 47, name_en: 'Okinawa', name_ja: '沖縄県' },
    ];

    const INTERVIEW_FOLLOW_STEPS = ['一次面接', '二次面接', '三次面接', '四次面接', '五次面接'];

    expect(wrapper.vm.overlay).toStrictEqual(OVERLAY);

    expect(wrapper.vm.city_list).toStrictEqual(CITY_LIST);

    expect(wrapper.vm.interview_follow_steps).toStrictEqual(INTERVIEW_FOLLOW_STEPS);

    expect(wrapper.vm.job_title).toBe('');
    expect(wrapper.vm.company_id).toBe(null);
    expect(wrapper.vm.company_name).toBe('');
    expect(wrapper.vm.application_period_start).toBe('');
    expect(wrapper.vm.application_period_end).toBe('');
    expect(wrapper.vm.major_classification).toBe(null);
    expect(wrapper.vm.middle_classification).toBe(null);
    expect(wrapper.vm.job_description).toBe('');
    expect(wrapper.vm.application_condition).toBe('');
    expect(wrapper.vm.application_requirement).toBe('');
    expect(wrapper.vm.country).toBe('ベトナム');
    expect(wrapper.vm.language_requirement).toBe([]);
    expect(wrapper.vm.other_skill).toBe('');
    expect(wrapper.vm.preferred_skill).toBe('');
    expect(wrapper.vm.form_of_employment).toBe('');
    expect(wrapper.vm.vacation).toBe('');
    expect(wrapper.vm.expected_income).toBe('');
    expect(wrapper.vm.working_time_to).toBe(null);
    expect(wrapper.vm.working_time_from).toBe(null);
    expect(wrapper.vm.working_place).toBe(null);
    expect(wrapper.vm.working_place_detail).toBe('');
    expect(wrapper.vm.treatment_welfare).toBe('');
    expect(wrapper.vm.company_pr_appeal).toBe('');
    expect(wrapper.vm.bonus_pay).toBe('');
    expect(wrapper.vm.overtime).toBe('');
    expect(wrapper.vm.transfer).toBe('');
    expect(wrapper.vm.passive_smoking).toBe('');
    expect(wrapper.vm.passion).toBe('');
    expect(wrapper.vm.housing_allowance).toBe(false);
    expect(wrapper.vm.welfare_enhancement).toBe(false);
    expect(wrapper.vm.severance_pay).toBe(false);
    expect(wrapper.vm.more_annual_holidays).toBe(false);
    expect(wrapper.vm.residence).toBe(false);
    expect(wrapper.vm.no_experience_ok).toBe(false);
    expect(wrapper.vm.foreign_managerial_staff_hired).toBe(false);
    expect(wrapper.vm.remote_work_available).toBe(false);
    expect(wrapper.vm.moving_allowance).toBe(false);
    expect(wrapper.vm.interview_follow).toBe(null);
    expect(wrapper.vm.establishment_year).toBe('');
    expect(wrapper.vm.startup_year).toBe('');
    expect(wrapper.vm.capital).toBe('');
    expect(wrapper.vm.proceeds).toBe('');
    expect(wrapper.vm.number_of_staffs).toBe('');
    expect(wrapper.vm.number_of_operations).toBe('');
    expect(wrapper.vm.number_of_shops).toBe('');
    expect(wrapper.vm.number_of_factories).toBe('');
    expect(wrapper.vm.fiscal_year).toBe('');
    expect(wrapper.vm.company_list).toBe([]);
    expect(wrapper.vm.list_main_job_occupation).toBe([]);

    wrapper.destroy();
  });

  test('TEST CALL FUNCTION handleInitComponentData WHEN COMPONENT CREATED', async() => {
    const wrapper = shallowMount(JobDetail, {
      store,
      router,
      localVue,
    });

    const handleInitComponentData = jest.fn();
    const handleGetListCompany = jest.fn();
    const handleGetListJobOccupation = jest.fn();
    const handleGetJobInformation = jest.fn();

    wrapper.setMethods({
      handleInitComponentData,
      handleGetListCompany,
      handleGetListJobOccupation,
      handleGetJobInformation,
    });

    handleInitComponentData.mockImplementation(async() => {
      wrapper.vm.statusJob = router.query.status;

      expect(wrapper.vm.statusJob).not.toBeNull();

      await handleGetListCompany();
      await handleGetListJobOccupation();
      await handleGetJobInformation();
    });

    await wrapper.vm.handleInitComponentData();

    expect(handleInitComponentData).toHaveBeenCalled();

    expect(handleGetListCompany).toHaveBeenCalled();

    expect(handleGetListJobOccupation).toHaveBeenCalled();

    wrapper.destroy();
  });

  test('TEST CALL FUNCTION handleGoToEdit WHEN CLICK BUTTON EDIT', async() => {
    const wrapper = shallowMount(JobDetail, {
      store,
      router,
      localVue,
    });

    const handleGoToEdit = jest.fn();

    wrapper.setMethods({
      handleGoToEdit,
    });

    const buttonEdit = wrapper.find('.btn-edit');

    await buttonEdit.trigger('click');

    expect(handleGoToEdit).toHaveBeenCalled();

    wrapper.destroy();
  });

  test('TEST CALL FUNCTION handleBackList WHEN CLICK BUTTON BACK', async() => {
    const wrapper = shallowMount(JobDetail, {
      store,
      router,
      localVue,
    });

    const handleBackList = jest.fn();

    wrapper.setMethods({
      handleBackList,
    });

    const buttonBack = wrapper.find('.btn-back');

    await buttonBack.trigger('click');

    expect(handleBackList).toHaveBeenCalled();

    wrapper.destroy();
  });
});
