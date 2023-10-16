import store from '@/store';
import router from '@/router';
import HREdit from '@/pages/Hr/Edit/HREdit.vue';
// import HrFormSearch from '@/layout/components/search/HrFormSearch.vue';
// import Bus from '@/eventBus';
import {
  renderYears,
  renderMonth,
  renderYearNotRequire,
  renderMonthNotRequire,
} from '@/pages/Hr/common.js';
import { mount, createLocalVue, shallowMount } from '@vue/test-utils';

describe('TEST SCREEN HR Edit', () => {
  test('Test 1: Test render component Hr edit', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(HREdit, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    const render_component = wrapper.find('.hr-detail');
    expect(render_component.exists()).toBe(true);

    const title = wrapper.find('.hr-edit-title');
    expect(title.exists()).toBe(true);
    expect(title.text()).toBe('HR_LIST_FORM.EDIT_FORM_TITLE');

    wrapper.destroy();
  });

  test('Test 2: test render data', async() => {
    const HR_EDIT_DATA = {
      overlay: {
        opacity: 1,
        show: false,
        blur: '1rem',
        rounded: 'sm',
        variant: 'light',
      },

      companyList: [],

      gender_option: [
        { value: null, text: '選択してください', disabled: false },
        { value: 1, text: '男性' },
        { value: 2, text: '女性' },
      ],

      vItems: {},
      cv_status: 'private',
      hr_status: 0,
      favorite_added: true,

      original_hr_id: '',
      country_id: '',

      company_name_en: '',
      company_name_jp: '',

      cv_file: null,
      cv_file_name: '選択されていません',
      cv_file_preview_src: null,
      cv_file_id: '',
      cv_file_path: '',

      jd_file: null,
      jd_file_name: '選択されていません',
      jd_file_preview_src: null,
      jd_file_id: '',
      jd_file_path: '',

      default_other_file: null,
      default_other_file_name: '選択されていません',
      default_other_file_preview_src: null,
      default_other_file_id: '',
      default_other_file_path: '',
      default_other_file_system_file_name: '',

      date_of_birth_year_option: renderYears(),
      date_of_birth_month_option: renderMonth(),

      date_of_birth_year_option_not_require: renderYearNotRequire(),
      date_of_birth_month_option_not_require: renderMonthNotRequire(),

      date_of_birth_day_option: [],

      work_form_option: [
        { value: null, text: '選択してください', disabled: false },
        { value: 1, text: '正社員' },
        { value: 2, text: '契約社員' },
        { value: 3, text: '派遣社員' },
        { value: 4, text: 'その他' },
      ],

      japanese_level_options: [
        { value: 1, text: 'N1' },
        { value: 2, text: 'N2' },
        { value: 3, text: 'N3' },
        { value: 4, text: 'N4' },
        { value: 5, text: 'N5' },
        { value: 6, text: '資格なし' },
      ],

      full_name: '',
      full_name_furigana: '',
      gender: null,
      date_of_birth_year: '',
      date_of_birth_month: '',
      date_of_birth_day: '',
      work_form: null,
      work_form_part_time: '',
      japanese_level: null,

      final_education_timing_year: '',
      final_education_timing_month: '',
      final_education_classification: null,
      final_education_degree: null,
      major_classification: null,
      middle_classification: null,

      main_job_career_1_year_from: null,
      main_job_career_1_month_from: null,
      main_job_career_1_year_to: null,
      main_job_career_1_month_to: null,
      main_job_career_1_time_now: false,
      main_job_career_1_department: null,
      main_job_career_1_job_title: null,
      main_job_career_1_textarea: '',

      main_job_career_2_year_from: null,
      main_job_career_2_month_from: null,
      main_job_career_2_year_to: null,
      main_job_career_2_month_to: null,
      main_job_career_2_time_now: false,
      main_job_career_2_department: null,
      main_job_career_2_job_title: null,
      main_job_career_2_textarea: '',

      main_job_career_3_year_from: null,
      main_job_career_3_month_from: null,
      main_job_career_3_year_to: null,
      main_job_career_3_month_to: null,
      main_job_career_3_time_now: false,
      main_job_career_3_department: null,
      main_job_career_3_job_title: null,
      main_job_career_3_textarea: '',

      personal_pr_special_textarea: '',
      remarks_textarea: '',

      telephone_phone_number_prefix: null,
      telephone_phone_number: '',
      mobile_phone_number_prefix: null,
      mobile_phone_number: '',

      mail_address: '',
      invalid_mail_error_text: '',

      address_city: '',
      address_district: '',
      address_number: '',
      address_other: '',
      hometown_address_city: '',
      hometown_address_district: '',
      hometown_address_number: '',
      hometown_address_other: '',

      isPassFirstTabValidation: true,
      isPassSecondTabValidation: true,
      isPassThirdTabValidation: true,
      isPassFourthTabValidation: true,
      isPassFifthTabValidation: true,

      other_files: [],

      error: {
        full_name: true,
        full_name_furigana: true,
        date_of_birth_year: true,
        date_of_birth_month: true,
        date_of_birth_day: true,
        japanese_level: true,

        final_education_timing_year: true,
        final_education_timing_month: true,
        final_education_classification: true,
        final_education_degree: true,
        major_classification: true,
        middle_classification: true,

        main_job_career_1_year_from: null,
        main_job_career_1_month_from: null,
        main_job_career_1_year_to: null,
        main_job_career_1_month_to: null,
        main_job_career_1_department: null,
        main_job_career_1_job_title: null,

        mail_address: true,

        cv_file: null,
        jd_file: null,
      },
      major_middle_list: [],
      main_job_career_list: [],
    };

    const localVue = createLocalVue();
    const wrapper = shallowMount(HREdit, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
      mocks: { $t: () => {} },
    });

    expect(wrapper.vm.overlay).toEqual(HR_EDIT_DATA.overlay);

    expect(wrapper.vm.gender_option).toEqual(HR_EDIT_DATA.gender_option);
    expect(wrapper.vm.vItems).toEqual(HR_EDIT_DATA.vItems);
    expect(wrapper.vm.cv_status).toEqual(HR_EDIT_DATA.cv_status);

    expect(wrapper.vm.hr_status).toEqual(HR_EDIT_DATA.hr_status);
    expect(wrapper.vm.favorite_added).toEqual(HR_EDIT_DATA.favorite_added);
    expect(wrapper.vm.original_hr_id).toEqual(HR_EDIT_DATA.original_hr_id);
    expect(wrapper.vm.country_id).toEqual(HR_EDIT_DATA.country_id);
    expect(wrapper.vm.company_name_en).toEqual(HR_EDIT_DATA.company_name_en);

    expect(wrapper.vm.company_name_jp).toEqual(HR_EDIT_DATA.company_name_jp);
    expect(wrapper.vm.cv_file).toEqual(HR_EDIT_DATA.cv_file);
    expect(wrapper.vm.cv_file_name).toEqual(HR_EDIT_DATA.cv_file_name);

    expect(wrapper.vm.cv_file_preview_src).toEqual(HR_EDIT_DATA.cv_file_preview_src);
    expect(wrapper.vm.cv_file_id).toEqual(HR_EDIT_DATA.cv_file_id);
    expect(wrapper.vm.cv_file_path).toEqual(HR_EDIT_DATA.cv_file_path);
    expect(wrapper.vm.jd_file).toEqual(HR_EDIT_DATA.jd_file);
    expect(wrapper.vm.jd_file_name).toEqual(HR_EDIT_DATA.jd_file_name);
    expect(wrapper.vm.jd_file_preview_src).toEqual(HR_EDIT_DATA.jd_file_preview_src);
    expect(wrapper.vm.jd_file_id).toEqual(HR_EDIT_DATA.jd_file_id);
    expect(wrapper.vm.jd_file_path).toEqual(HR_EDIT_DATA.jd_file_path);

    expect(wrapper.vm.default_other_file).toEqual(HR_EDIT_DATA.default_other_file);
    expect(wrapper.vm.default_other_file_name).toEqual(HR_EDIT_DATA.default_other_file_name);
    expect(wrapper.vm.default_other_file_preview_src).toEqual(HR_EDIT_DATA.default_other_file_preview_src);
    expect(wrapper.vm.default_other_file_id).toEqual(HR_EDIT_DATA.default_other_file_id);

    expect(wrapper.vm.default_other_file_path).toEqual(HR_EDIT_DATA.default_other_file_path);
    expect(wrapper.vm.default_other_file_system_file_name).toEqual(HR_EDIT_DATA.default_other_file_system_file_name);
    expect(wrapper.vm.date_of_birth_year_option).toEqual(HR_EDIT_DATA.date_of_birth_year_option);
    expect(wrapper.vm.date_of_birth_month_option).toEqual(HR_EDIT_DATA.date_of_birth_month_option);

    expect(wrapper.vm.date_of_birth_year_option_not_require).toEqual(HR_EDIT_DATA.date_of_birth_year_option_not_require);
    expect(wrapper.vm.date_of_birth_month_option_not_require).toEqual(HR_EDIT_DATA.date_of_birth_month_option_not_require);
    expect(wrapper.vm.date_of_birth_day_option).toEqual(HR_EDIT_DATA.date_of_birth_day_option);
    expect(wrapper.vm.work_form_option).toEqual(HR_EDIT_DATA.work_form_option);

    expect(wrapper.vm.japanese_level_options).toEqual(HR_EDIT_DATA.japanese_level_options);
    expect(wrapper.vm.full_name).toEqual(HR_EDIT_DATA.full_name);
    expect(wrapper.vm.gender).toEqual(HR_EDIT_DATA.gender);
    expect(wrapper.vm.date_of_birth_year).toEqual(HR_EDIT_DATA.date_of_birth_year);

    expect(wrapper.vm.date_of_birth_month).toEqual(HR_EDIT_DATA.date_of_birth_month);
    expect(wrapper.vm.date_of_birth_day).toEqual(HR_EDIT_DATA.date_of_birth_day);
    expect(wrapper.vm.work_form).toEqual(HR_EDIT_DATA.work_form);
    expect(wrapper.vm.work_form_part_time).toEqual(HR_EDIT_DATA.work_form_part_time);

    expect(wrapper.vm.final_education_timing_year).toEqual(HR_EDIT_DATA.final_education_timing_year);
    expect(wrapper.vm.final_education_timing_month).toEqual(HR_EDIT_DATA.final_education_timing_month);
    expect(wrapper.vm.final_education_classification).toEqual(HR_EDIT_DATA.final_education_classification);
    expect(wrapper.vm.final_education_degree).toEqual(HR_EDIT_DATA.final_education_degree);

    expect(wrapper.vm.major_classification).toEqual(HR_EDIT_DATA.major_classification);
    expect(wrapper.vm.middle_classification).toEqual(HR_EDIT_DATA.middle_classification);
    expect(wrapper.vm.final_education_classification).toEqual(HR_EDIT_DATA.final_education_classification);

    expect(wrapper.vm.main_job_career_1_year_from).toEqual(HR_EDIT_DATA.main_job_career_1_year_from);
    expect(wrapper.vm.main_job_career_1_month_from).toEqual(HR_EDIT_DATA.main_job_career_1_month_from);
    expect(wrapper.vm.main_job_career_1_year_to).toEqual(HR_EDIT_DATA.main_job_career_1_year_to);
    expect(wrapper.vm.main_job_career_1_month_to).toEqual(HR_EDIT_DATA.main_job_career_1_month_to);
    expect(wrapper.vm.main_job_career_1_time_now).toEqual(HR_EDIT_DATA.main_job_career_1_time_now);
    expect(wrapper.vm.main_job_career_1_department).toEqual(HR_EDIT_DATA.main_job_career_1_department);
    expect(wrapper.vm.main_job_career_1_job_title).toEqual(HR_EDIT_DATA.main_job_career_1_job_title);
    expect(wrapper.vm.main_job_career_1_textarea).toEqual(HR_EDIT_DATA.main_job_career_1_textarea);

    expect(wrapper.vm.main_job_career_2_year_from).toEqual(HR_EDIT_DATA.main_job_career_2_year_from);
    expect(wrapper.vm.main_job_career_2_month_from).toEqual(HR_EDIT_DATA.main_job_career_2_month_from);
    expect(wrapper.vm.main_job_career_2_year_to).toEqual(HR_EDIT_DATA.main_job_career_2_year_to);
    expect(wrapper.vm.main_job_career_2_month_to).toEqual(HR_EDIT_DATA.main_job_career_2_month_to);
    expect(wrapper.vm.main_job_career_2_time_now).toEqual(HR_EDIT_DATA.main_job_career_2_time_now);
    expect(wrapper.vm.main_job_career_2_department).toEqual(HR_EDIT_DATA.main_job_career_2_department);
    expect(wrapper.vm.main_job_career_2_job_title).toEqual(HR_EDIT_DATA.main_job_career_2_job_title);
    expect(wrapper.vm.main_job_career_2_textarea).toEqual(HR_EDIT_DATA.main_job_career_2_textarea);

    expect(wrapper.vm.main_job_career_3_year_from).toEqual(HR_EDIT_DATA.main_job_career_3_year_from);
    expect(wrapper.vm.main_job_career_3_month_from).toEqual(HR_EDIT_DATA.main_job_career_3_month_from);
    expect(wrapper.vm.main_job_career_3_year_to).toEqual(HR_EDIT_DATA.main_job_career_3_year_to);
    expect(wrapper.vm.main_job_career_3_month_to).toEqual(HR_EDIT_DATA.main_job_career_3_month_to);
    expect(wrapper.vm.main_job_career_3_time_now).toEqual(HR_EDIT_DATA.main_job_career_3_time_now);
    expect(wrapper.vm.main_job_career_3_department).toEqual(HR_EDIT_DATA.main_job_career_3_department);
    expect(wrapper.vm.main_job_career_3_job_title).toEqual(HR_EDIT_DATA.main_job_career_3_job_title);
    expect(wrapper.vm.main_job_career_3_textarea).toEqual(HR_EDIT_DATA.main_job_career_3_textarea);

    expect(wrapper.vm.personal_pr_special_textarea).toEqual(HR_EDIT_DATA.personal_pr_special_textarea);
    expect(wrapper.vm.remarks_textarea).toEqual(HR_EDIT_DATA.remarks_textarea);
    expect(wrapper.vm.telephone_phone_number_prefix).toEqual(HR_EDIT_DATA.telephone_phone_number_prefix);
    expect(wrapper.vm.telephone_phone_number).toEqual(HR_EDIT_DATA.telephone_phone_number);

    expect(wrapper.vm.mobile_phone_number_prefix).toEqual(HR_EDIT_DATA.mobile_phone_number_prefix);
    expect(wrapper.vm.mobile_phone_number).toEqual(HR_EDIT_DATA.mobile_phone_number);
    expect(wrapper.vm.mail_address).toEqual(HR_EDIT_DATA.mail_address);
    expect(wrapper.vm.invalid_mail_error_text).toEqual(HR_EDIT_DATA.invalid_mail_error_text);

    expect(wrapper.vm.address_city).toEqual(HR_EDIT_DATA.address_city);
    expect(wrapper.vm.address_district).toEqual(HR_EDIT_DATA.address_district);
    expect(wrapper.vm.address_other).toEqual(HR_EDIT_DATA.address_other);
    expect(wrapper.vm.hometown_address_city).toEqual(HR_EDIT_DATA.hometown_address_city);

    expect(wrapper.vm.hometown_address_district).toEqual(HR_EDIT_DATA.hometown_address_district);
    expect(wrapper.vm.hometown_address_number).toEqual(HR_EDIT_DATA.hometown_address_number);
    expect(wrapper.vm.hometown_address_other).toEqual(HR_EDIT_DATA.hometown_address_other);

    expect(wrapper.vm.isPassFirstTabValidation).toEqual(HR_EDIT_DATA.isPassFirstTabValidation);
    expect(wrapper.vm.isPassSecondTabValidation).toEqual(HR_EDIT_DATA.isPassSecondTabValidation);
    expect(wrapper.vm.isPassThirdTabValidation).toEqual(HR_EDIT_DATA.isPassThirdTabValidation);
    expect(wrapper.vm.isPassFourthTabValidation).toEqual(HR_EDIT_DATA.isPassFourthTabValidation);
    expect(wrapper.vm.isPassFifthTabValidation).toEqual(HR_EDIT_DATA.isPassFifthTabValidation);

    expect(wrapper.vm.other_files).toEqual(HR_EDIT_DATA.other_files);
    expect(wrapper.vm.error).toEqual(HR_EDIT_DATA.error);
    expect(wrapper.vm.major_middle_list).toEqual(HR_EDIT_DATA.major_middle_list);
    expect(wrapper.vm.main_job_career_list).toEqual(HR_EDIT_DATA.main_job_career_list);

    wrapper.destroy();
  });

  test('Test 3: test render field basic infomation', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(HREdit, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    const hr_first_info = wrapper.find('.infor-first');
    expect(hr_first_info.exists()).toBe(true);

    const hr_org_info = wrapper.find('.infor-company');
    expect(hr_org_info.exists()).toBe(true);

    const hr_status_control = wrapper.find('.information-general-btns');
    expect(hr_status_control.exists()).toBe(true);
    const btn = hr_status_control.findAll('.btn');
    expect(btn.exists()).toBe(true);
    expect(btn.length).toBe(2);

    const handlePublicCV = jest.spyOn(wrapper.vm, 'handlePublicCV');

    if (expect(btn.at(0).exists()).toBe(true)) {
      await btn.at(0).trigger('click');
      expect(handlePublicCV).toHaveBeenCalled();
    }

    const handlePrivateCV = jest.spyOn(wrapper.vm, 'handlePrivateCV');

    if (expect(btn.at(1).exists()).toBe(true)) {
      await btn.at(1).trigger('click');
      expect(handlePrivateCV).toHaveBeenCalled();
    }

    const btn_text = hr_status_control.findAll('span');
    expect(btn_text.exists()).toBe(true);
    expect(btn_text.length).toBe(2);
    expect(btn_text.at(0).text()).toEqual('公開');
    expect(btn_text.at(1).text()).toEqual('非公開');
    // expect(table_hr).toMatchSnapshot();

    const file_control = wrapper.find('.information-general-select-file');
    expect(file_control.exists()).toBe(true);
    const label_file_cv = file_control.find('.label-file-cv');
    expect(label_file_cv.exists()).toBe(true);
    expect(label_file_cv.text()).toEqual('履歴書');

    const btn_input_file = file_control.findAll('.file-btn');
    expect(btn_input_file.exists()).toBe(true);
    expect(btn_input_file.length).toBe(3);

    const text_btn_file_input = file_control.findAll('.file-btn-text');
    expect(text_btn_file_input.exists()).toBe(true);
    expect(text_btn_file_input.length).toBe(3);
    expect(text_btn_file_input.at(0).text()).toEqual('ファイルを選択');
    expect(text_btn_file_input.at(1).text()).toEqual('ファイルを選択');
    expect(text_btn_file_input.at(2).text()).toEqual('ファイルを選択');

    const handleOpenFileSelect = jest.spyOn(wrapper.vm, 'handleOpenFileSelect');

    if (expect(btn_input_file.at(0).exists()).toBe(true)) {
      await btn_input_file.at(0).trigger('click');
      expect(handleOpenFileSelect).toHaveBeenCalled();
    }

    wrapper.destroy();
  });

  test('Test 4: test render field basic infomation requied', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(HREdit, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    const save_btn = wrapper.find('.btn-bold');
    expect(save_btn.exists()).toBe(true);

    const handleSaveHr = jest.spyOn(wrapper.vm, 'handleSaveHr');

    if (expect(save_btn.exists()).toBe(true)) {
      await save_btn.trigger('click');
      expect(handleSaveHr).toHaveBeenCalled();
    }

    const file_control = wrapper.find('.information-general-select-file');
    expect(file_control.exists()).toBe(true);
    const requied_text = file_control.findAll('.display-requied-text');
    expect(requied_text.exists()).toBe(true);
    expect(requied_text.length).toBe(2);
    expect(requied_text.at(0).text()).toEqual('VALIDATE.REQUIRED_TEXT');
    expect(requied_text.at(0).text()).toEqual('VALIDATE.REQUIRED_TEXT');

    wrapper.destroy();
  });

  test('Test 5: test render button back to edit, save and click', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(HREdit, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    const save_btn = wrapper.find('.btn-bold');
    expect(save_btn.exists()).toBe(true);
    const save_btn_text = save_btn.find('span');
    expect(save_btn_text.exists()).toBe(true);
    expect(save_btn_text.text()).toEqual('BUTTON.SAVE');

    const handleSaveHr = jest.spyOn(wrapper.vm, 'handleSaveHr');

    if (expect(save_btn.exists()).toBe(true)) {
      await save_btn.trigger('click');
      expect(handleSaveHr).toHaveBeenCalled();
    }

    const btn_control = wrapper.find('.hr-detail-head-btns');
    expect(btn_control.exists()).toBe(true);
    const find_btn = wrapper.findAll('.btn-light');
    expect(find_btn.exists()).toBe(true);
    expect(find_btn.length).toBe(2);
    const btn_back_to_detail = find_btn.at(0);
    expect(btn_back_to_detail.exists()).toBe(true);
    expect(btn_back_to_detail.text()).toEqual('BUTTON.CANCEL');

    const handleCancelHrEdit = jest.spyOn(wrapper.vm, 'handleCancelHrEdit');

    if (expect(btn_back_to_detail.exists()).toBe(true)) {
      await btn_back_to_detail.trigger('click');
      expect(handleCancelHrEdit).toHaveBeenCalled();
    }

    wrapper.destroy();
  });

  test('Test 6: test render field basic infomation (tab 1)', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(HREdit, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    // expect(wrapper).toMatchSnapshot();
    const TEXT_FIELD = [
      '氏名',
      '氏名（ﾌﾘｶﾞﾅ）',
      '性別',
      '生年月日',
      '勤務形態',
      '勤務形態（非常勤）',
      '日本語レベル',
    ];

    const basic_tab_1 = wrapper.find('.hr-tab-basic-infomation');
    expect(basic_tab_1.exists()).toBe(true);

    const all_field_basic_tab_1 = basic_tab_1.findAll('.hr-content-tab-item__title');
    expect(all_field_basic_tab_1.exists()).toBe(true);
    expect(all_field_basic_tab_1.length).toEqual(7);

    let idx = 0;
    while (idx < 7) {
      const field_text = all_field_basic_tab_1.at(idx).find('span');
      expect(field_text.exists()).toBe(true);
      expect(field_text.text()).toEqual(TEXT_FIELD[idx]);

      idx++;
    }

    wrapper.destroy();
  });

  test('Test 7: test render field basic infomation (tab 1) requied', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(HREdit, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    const save_btn = wrapper.find('.btn-bold');
    expect(save_btn.exists()).toBe(true);

    const handleSaveHr = jest.spyOn(wrapper.vm, 'handleSaveHr');

    if (expect(save_btn.exists()).toBe(true)) {
      await save_btn.trigger('click');
      expect(handleSaveHr).toHaveBeenCalled();
    }

    const basic_tab_1 = wrapper.find('.hr-tab-basic-infomation');
    expect(basic_tab_1.exists()).toBe(true);

    const requied_field_basic_tab_1 = basic_tab_1.findAll('.invalid-feedback');
    expect(requied_field_basic_tab_1.exists()).toBe(true);
    expect(requied_field_basic_tab_1.length).toEqual(6);

    let idx = 0;
    while (idx < 6) {
      const field_text = requied_field_basic_tab_1.at(idx).find('span');
      expect(field_text.exists()).toBe(true);
      expect(field_text.text()).toEqual('VALIDATE.REQUIRED_TEXT');

      idx++;
    }
    wrapper.destroy();
  });

  test('Test 8: test render field education work history (tab 2)', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(HREdit, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    // expect(wrapper).toMatchSnapshot();
    const TEXT_FIELD = [
      'HR_LIST_FORM.FINAL_EDUCATION',
      'HR_LIST_FORM.MAIN_JOB_CAREER_1',
      'HR_LIST_FORM.MAIN_JOB_CAREER_2',
      'HR_LIST_FORM.MAIN_JOB_CAREER_3',
    ];

    const tab = wrapper.find('.hr-education-work-history');
    expect(tab.exists()).toBe(true);

    const all_field_tab = tab.findAll('.hr-content-tab-item__title');
    expect(all_field_tab.exists()).toBe(true);
    expect(all_field_tab.length).toEqual(4);

    let idx = 0;
    while (idx < 4) {
      const field_text = all_field_tab.at(idx).find('span');
      expect(field_text.exists()).toBe(true);
      expect(field_text.text()).toEqual(TEXT_FIELD[idx]);

      idx++;
    }

    const TEXT_FIELD_1 = [
      // 'HR_LIST_FORM.GRADUATION_DATE',
      'HR_LIST_FORM.CLASSIFICATION',
      'HR_LIST_FORM.DEGREE',
      'HR_LIST_FORM.MAIN_CATEGORY',
      'HR_LIST_FORM.DEPARTMENT',
    ];

    const TEXT_FIELD_MAIN_JOB = [
      'HR_LIST_FORM.DATE',
      'HR_LIST_FORM.DEPARTMENT',
      'HR_LIST_FORM.JOB_TITLE',
      'HR_LIST_FORM.DETAIL',
    ];

    const fields_label = tab.findAll('.hr-content-tab__data');
    expect(fields_label.exists()).toBe(true);
    expect(fields_label.length).toEqual(4);

    const final_education = fields_label.at(0);
    expect(final_education.exists()).toBe(true);
    const final_education_label = final_education.findAll('.pl-1');
    expect(final_education_label.exists()).toBe(true);
    expect(final_education_label.length).toEqual(4);

    let idx_edu = 0;
    while (idx_edu < 4) {
      const final_education_label_item = final_education_label.at(idx_edu);
      expect(final_education_label_item.exists()).toBe(true);
      expect(final_education_label_item.text()).toEqual(TEXT_FIELD_1[idx_edu]);

      idx_edu++;
    }

    // main job career
    const main_job = fields_label.at(1);
    expect(main_job.exists()).toBe(true);
    const main_job_label = main_job.findAll('.pl-1');
    expect(main_job_label.exists()).toBe(true);
    expect(main_job_label.length).toEqual(4);

    let idx_main_job = 0;
    while (idx_main_job < 4) {
      const main_job_label_item = main_job_label.at(idx_main_job);
      expect(main_job_label_item.exists()).toBe(true);
      expect(main_job_label_item.text()).toEqual(TEXT_FIELD_MAIN_JOB[idx_main_job]);

      idx_main_job++;
    }

    wrapper.destroy();
  });

  test('Test 9: test render field education work history (tab 2) requied', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(HREdit, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    const save_btn = wrapper.find('.btn-bold');
    expect(save_btn.exists()).toBe(true);

    const handleSaveHr = jest.spyOn(wrapper.vm, 'handleSaveHr');

    if (expect(save_btn.exists()).toBe(true)) {
      await save_btn.trigger('click');
      expect(handleSaveHr).toHaveBeenCalled();
    }

    const tab = wrapper.find('.hr-education-work-history');
    expect(tab.exists()).toBe(true);

    const requied_field_tab = tab.findAll('.invalid-feedback');
    expect(requied_field_tab.exists()).toBe(true);
    expect(requied_field_tab.length).toEqual(13);

    let idx = 0;
    while (idx < 13) {
      const field_text = requied_field_tab.at(idx).find('span');
      expect(field_text.exists()).toBe(true);
      expect(field_text.text()).toEqual('VALIDATE.REQUIRED_TEXT');

      idx++;
    }
    wrapper.destroy();
  });

  test('Test 10: test render field persional PR remarks (tab 3)', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(HREdit, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    // expect(wrapper).toMatchSnapshot();
    const TEXT_FIELD = [
      'HR_LIST_FORM.PERSONAL_PR_SPECIAL_NOTES',
      'HR_LIST_FORM.REMARKS',
    ];

    const tab = wrapper.find('.hr-persional-pr-remarks');
    expect(tab.exists()).toBe(true);

    const all_field_tab = tab.findAll('.hr-content-tab-item__title');
    expect(all_field_tab.exists()).toBe(true);
    expect(all_field_tab.length).toEqual(2);

    let idx = 0;
    while (idx < 2) {
      const field_text = all_field_tab.at(idx).find('span');
      expect(field_text.exists()).toBe(true);
      expect(field_text.text()).toEqual(TEXT_FIELD[idx]);

      idx++;
    }

    wrapper.destroy();
  });

  test('Test 11: test render field contact (tab 4)', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(HREdit, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    // expect(wrapper).toMatchSnapshot();
    const TEXT_FIELD = [
      'HR_LIST_FORM.TELEPHONE_NUMBER',
      'HR_LIST_FORM.MOBILE_PHONE_NUMBER',
      'HR_LIST_FORM.MAIL_ADDRESS',
      'HR_LIST_FORM.ADDRESS',
      'HR_LIST_FORM.HOMETOWN_ADDRESS',
    ];

    const tab = wrapper.find('.hr-contact');
    expect(tab.exists()).toBe(true);

    const all_field_tab = tab.findAll('.hr-content-tab-item__title');
    expect(all_field_tab.exists()).toBe(true);
    expect(all_field_tab.length).toEqual(5);

    let idx = 0;
    while (idx < 4) {
      const field_text = all_field_tab.at(idx).find('span');
      expect(field_text.exists()).toBe(true);
      expect(field_text.text()).toEqual(TEXT_FIELD[idx]);

      idx++;
    }

    const TEXT_FIELD_ADDRESS = [
      // 'HR_LIST_FORM.GRADUATION_DATE',
      'HR_LIST_FORM.CITY',
      'HR_LIST_FORM.DISTRICT',
      'HR_LIST_FORM.NUMBER',
      'HR_LIST_FORM.ORTHER',
    ];

    const all_field_tab_check = tab.findAll('.hr-content-tab__data');
    expect(all_field_tab_check.exists()).toBe(true);
    expect(all_field_tab_check.length).toEqual(5);

    const address = all_field_tab_check.at(3);
    expect(address.exists()).toBe(true);
    const address_label = address.findAll('span');
    expect(address_label.exists()).toBe(true);
    expect(address_label.length).toEqual(4);

    let idx_address = 0;
    while (idx_address < 4) {
      const address_label_item = address_label.at(idx_address);
      expect(address_label_item.exists()).toBe(true);
      expect(address_label_item.text()).toEqual(TEXT_FIELD_ADDRESS[idx_address]);

      idx_address++;
    }

    wrapper.destroy();
  });

  test('Test 12: test render field contact (tab 4) requied', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(HREdit, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    const save_btn = wrapper.find('.btn-bold');
    expect(save_btn.exists()).toBe(true);

    const handleSaveHr = jest.spyOn(wrapper.vm, 'handleSaveHr');

    if (expect(save_btn.exists()).toBe(true)) {
      await save_btn.trigger('click');
      expect(handleSaveHr).toHaveBeenCalled();
    }

    const tab = wrapper.find('.hr-contact');
    expect(tab.exists()).toBe(true);

    const requied_field_tab = tab.findAll('.invalid-feedback');
    expect(requied_field_tab.exists()).toBe(true);
    expect(requied_field_tab.length).toEqual(1);
    expect(requied_field_tab.at(0).text()).toEqual('');

    wrapper.destroy();
  });
});
