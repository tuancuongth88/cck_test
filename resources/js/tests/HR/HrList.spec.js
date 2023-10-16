import store from '@/store';
import router from '@/router';
import HrList from '@/pages/Hr/create';
import HRList from '@/pages/Hr/index.vue';
// import HrFormSearch from '@/layout/components/search/HrFormSearch.vue';
// import Bus from '@/eventBus';
import { mount, createLocalVue, shallowMount } from '@vue/test-utils';

describe('TEST SCREEN HR LIST', () => {
  test('Test 1: Test render component Hr List', async() => {
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
    // expect(hrRegisterBasicInformation.text()).toEqual('HR_REGISTER.BASIC_INFORMATION');
    expect(hrRegisterBasicInformation.text()).toEqual('◾️ HR_REGISTER.BASIC_INFORMATION');

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

  test('Test 2: test render data', async() => {
    const DATA = {
      overlay: {
        opacity: 0,
        show: true,
        blur: '1rem',
        rounded: 'sm',
        variant: 'light',
        fixed: true,
      },
      // status_select_jobs_to_offer: status_select_jobs_to_offer,
      stateCollaseHrFormSearch: true,
      statusModalConfirmDelAll: false,
      statusModalImportMultiCSV: false,

      showModalErrorImportCSV: false,
      error_data_import: [],
      data_success: [],
      is_data_success: false,
      reRender: 0,

      currentPage: 1,
      totalRows: 0,
      perPage: 20,

      hrFields: [
        {
          key: 'selected',
          sortable: false,
          label: '',
          class: 'choose',
          thClass: 'col-1',
        },
        {
          key: 'id',
          sortable: true,
          label: 'ID',
          class: 'id',
          thClass: 'col-2',
        },
        {
          key: 'full_name',
          sortable: true,
          label: 'HR_REGISTER.FULL_NAME',
          class: 'fullName',
          thClass: 'col-3',
        },

        {
          key: 'corporate_name',
          sortable: true,
          label: 'HR_REGISTER.ORGANIZATION_NAME',
          class: 'organization_name',
          thClass: 'col-4',
        },
        {
          key: 'age',
          sortable: true,
          label: 'HR_REGISTER.AGE',
          class: 'age',
          thClass: 'col-5',
        },
        {
          key: 'japanese_level',
          sortable: true,
          label: 'HR_REGISTER.JAPANESE_LEVEL',
          class: 'japanese_level',
          thClass: 'col-6',
        },

        {
          key: 'status',
          sortable: true,
          label: 'HR_REGISTER.STATUS',
          class: 'status',
          thClass: 'col-7',
        },
        {
          key: 'detail',
          sortable: false,
          label: 'HR_LIST_FORM.DETAIL',
          class: 'detail',
          thClass: 'col-8',
        },
      ],
      hrFieldsError: [
        {
          key: 'full_name',
          label: 'HR_REGISTER.FULL_NAME',
          class: 'fullName',
          thClass: 'col-3',
        },
        {
          key: 'full_name_ja',
          label: 'HR_REGISTER.FULL_NAME',
          class: 'fullName',
          thClass: 'col-3',
        },

        {
          key: 'message',
          label: 'エラー',
          class: 'organization_name',
          thClass: 'col-4',
        },
      ],
      hrItems: [],
      selectedItems: [],
      satateSelectAllHrItem: false,

      sortHRs: {
        field: '',
        sort_by: '',
      },
      paramSearch: null,
      file_import_id: 0,
      file_import_name: '',
    };

    const localVue = createLocalVue();
    const wrapper = shallowMount(HRList, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
      mocks: { $t: () => {} },
    });

    expect(wrapper.vm.overlay).toEqual(DATA.overlay);

    expect(wrapper.vm.stateCollaseHrFormSearch).toEqual(DATA.stateCollaseHrFormSearch);
    expect(wrapper.vm.statusModalConfirmDelAll).toEqual(DATA.statusModalConfirmDelAll);
    expect(wrapper.vm.statusModalImportMultiCSV).toEqual(DATA.statusModalImportMultiCSV);

    expect(wrapper.vm.showModalErrorImportCSV).toEqual(DATA.showModalErrorImportCSV);
    expect(wrapper.vm.error_data_import).toEqual(DATA.error_data_import);
    expect(wrapper.vm.data_success).toEqual(DATA.data_success);
    expect(wrapper.vm.is_data_success).toEqual(DATA.is_data_success);
    expect(wrapper.vm.reRender).toEqual(DATA.reRender);

    expect(wrapper.vm.currentPage).toEqual(DATA.currentPage);
    expect(wrapper.vm.totalRows).toEqual(DATA.totalRows);
    expect(wrapper.vm.perPage).toEqual(DATA.perPage);

    // expect(wrapper.vm.hrFields).toEqual(DATA.hrFields);
    // expect(wrapper.vm.hrFieldsError).toEqual(DATA.hrFieldsError);
    expect(wrapper.vm.hrItems).toEqual(DATA.hrItems);
    expect(wrapper.vm.selectedItems).toEqual(DATA.selectedItems);
    expect(wrapper.vm.satateSelectAllHrItem).toEqual(DATA.satateSelectAllHrItem);

    expect(wrapper.vm.sortHRs).toEqual(DATA.sortHRs);
    expect(wrapper.vm.paramSearch).toEqual(DATA.paramSearch);
    expect(wrapper.vm.file_import_id).toEqual(DATA.file_import_id);
    expect(wrapper.vm.file_import_name).toEqual(DATA.file_import_name);

    wrapper.destroy();
  });

  test('Test 3: test action sort data', async() => {
    const DATA = [{
      id: 34,
      corporate_name_en: 'Công ty Cổ phần phát triển nguồn nhân lực LOD',
      corporate_name_ja: 'LOD人材開発株式会社',
      hr_organization_id: 1,
      full_name: 'Hoa',
      full_name_ja: 'Hoa',
      status: 1,
      age: 23,
      japanese_level: 1,
      is_favorite: 1,
      is_move_entry: 0,
      _isSelected: false,
    }];

    const localVue = createLocalVue();
    const wrapper = mount(HRList, {
      localVue,
      router,
      store,
      stubs: {
        Bus: true,
        BIcon: true,
        HrFormSearch: true,
      },
    });

    await wrapper.setData({ hrItems: DATA });
    expect(wrapper).toMatchSnapshot();

    const table_hr = wrapper.find('#hr-table-list');
    expect(table_hr.exists()).toBe(true);
    // expect(table_hr).toMatchSnapshot();

    const sort_btn = table_hr.findAll('.sr-only');
    expect(sort_btn.exists()).toBe(true);

    const one_sort_btn = sort_btn.at(2);
    expect(one_sort_btn.exists()).toBe(true);

    const handleSortTableHrList = jest.spyOn(wrapper.vm, 'handleSortTableHrList');

    if (expect(one_sort_btn.exists()).toBe(true)) {
      await one_sort_btn.trigger('click');
      expect(handleSortTableHrList).toHaveBeenCalled();
    }

    wrapper.destroy();
  });

  test('Test 4: test render delete button and click', async() => {
    const DATA = [{
      id: 34,
      corporate_name_en: 'Công ty Cổ phần phát triển nguồn nhân lực LOD',
      corporate_name_ja: 'LOD人材開発株式会社',
      hr_organization_id: 1,
      full_name: 'Hoa',
      full_name_ja: 'Hoa',
      status: 1,
      age: 23,
      japanese_level: 1,
      is_favorite: 1,
      is_move_entry: 0,
      _isSelected: false,
    }];

    const localVue = createLocalVue();
    const wrapper = mount(HRList, {
      localVue,
      router,
      store,
      stubs: {
        Bus: true,
        BIcon: true,
        HrFormSearch: true,
      },
    });

    await wrapper.setData({ hrItems: DATA });

    const btn_delete_hr = wrapper.find('.btn-delete-hr');
    expect(btn_delete_hr.exists()).toBe(true);
    // expect(table_hr).toMatchSnapshot();

    const handleToggleDeleteAllItemModal = jest.spyOn(wrapper.vm, 'handleToggleDeleteAllItemModal');

    if (expect(btn_delete_hr.exists()).toBe(true)) {
      await btn_delete_hr.trigger('click');
      expect(handleToggleDeleteAllItemModal).toHaveBeenCalled();
    }

    wrapper.destroy();
  });

  test('Test 5: test render export csv button and click', async() => {
    const DATA = [{
      id: 34,
      corporate_name_en: 'Công ty Cổ phần phát triển nguồn nhân lực LOD',
      corporate_name_ja: 'LOD人材開発株式会社',
      hr_organization_id: 1,
      full_name: 'Hoa',
      full_name_ja: 'Hoa',
      status: 1,
      age: 23,
      japanese_level: 1,
      is_favorite: 1,
      is_move_entry: 0,
      _isSelected: false,
    }];

    const localVue = createLocalVue();
    const wrapper = mount(HRList, {
      localVue,
      router,
      store,
      stubs: {
        Bus: true,
        BIcon: true,
        HrFormSearch: true,
      },
    });

    await wrapper.setData({ hrItems: DATA });

    const btn_export_csv = wrapper.find('.btn-export-csv-hr');
    expect(btn_export_csv.exists()).toBe(true);
    // expect(table_hr).toMatchSnapshot();

    const handleExportCsv = jest.spyOn(wrapper.vm, 'handleExportCsv');

    if (expect(btn_export_csv.exists()).toBe(true)) {
      await btn_export_csv.trigger('click');
      expect(handleExportCsv).toHaveBeenCalled();
    }

    wrapper.destroy();
  });

  test('Test 6: test render go to register button and click', async() => {
    const DATA = [{
      id: 34,
      corporate_name_en: 'Công ty Cổ phần phát triển nguồn nhân lực LOD',
      corporate_name_ja: 'LOD人材開発株式会社',
      hr_organization_id: 1,
      full_name: 'Hoa',
      full_name_ja: 'Hoa',
      status: 1,
      age: 23,
      japanese_level: 1,
      is_favorite: 1,
      is_move_entry: 0,
      _isSelected: false,
    }];

    const localVue = createLocalVue();
    const wrapper = mount(HRList, {
      localVue,
      router,
      store,
      stubs: {
        Bus: true,
        BIcon: true,
        HrFormSearch: true,
      },
    });

    await wrapper.setData({ hrItems: DATA });

    const btn_to_register_hr = wrapper.find('.btn-to-register-hr');
    expect(btn_to_register_hr.exists()).toBe(true);
    // expect(table_hr).toMatchSnapshot();

    const handleOpenRegisterForm = jest.spyOn(wrapper.vm, 'handleOpenRegisterForm');

    if (expect(btn_to_register_hr.exists()).toBe(true)) {
      await btn_to_register_hr.trigger('click');
      expect(handleOpenRegisterForm).toHaveBeenCalled();
    }

    wrapper.destroy();
  });

  test('Test 7: test render import csv button and click', async() => {
    const DATA = [{
      id: 34,
      corporate_name_en: 'Công ty Cổ phần phát triển nguồn nhân lực LOD',
      corporate_name_ja: 'LOD人材開発株式会社',
      hr_organization_id: 1,
      full_name: 'Hoa',
      full_name_ja: 'Hoa',
      status: 1,
      age: 23,
      japanese_level: 1,
      is_favorite: 1,
      is_move_entry: 0,
      _isSelected: false,
    }];

    const localVue = createLocalVue();
    const wrapper = mount(HRList, {
      localVue,
      router,
      store,
      stubs: {
        Bus: true,
        BIcon: true,
        HrFormSearch: true,
      },
    });

    await wrapper.setData({ hrItems: DATA });

    const btn_import_csv = wrapper.find('.btn-import-csv-hr');
    expect(btn_import_csv.exists()).toBe(true);
    // expect(table_hr).toMatchSnapshot();

    const handleToggleAddGroupModal = jest.spyOn(wrapper.vm, 'handleToggleAddGroupModal');

    if (expect(btn_import_csv.exists()).toBe(true)) {
      await btn_import_csv.trigger('click');
      expect(handleToggleAddGroupModal).toHaveBeenCalled();
    }

    wrapper.destroy();
  });

  test('Test 8: test render go to detail button and click', async() => {
    const DATA = [{
      id: 34,
      corporate_name_en: 'Công ty Cổ phần phát triển nguồn nhân lực LOD',
      corporate_name_ja: 'LOD人材開発株式会社',
      hr_organization_id: 1,
      full_name: 'Hoa',
      full_name_ja: 'Hoa',
      status: 1,
      age: 23,
      japanese_level: 1,
      is_favorite: 1,
      is_move_entry: 0,
      _isSelected: false,
    }];

    const localVue = createLocalVue();
    const wrapper = mount(HRList, {
      localVue,
      router,
      store,
      stubs: {
        Bus: true,
        BIcon: true,
        HrFormSearch: true,
      },
    });

    await wrapper.setData({ hrItems: DATA });

    const table_hr = wrapper.find('#hr-table-list');
    expect(table_hr).toMatchSnapshot();

    const btn_to_detail = table_hr.findAll('.icon-detail');
    expect(btn_to_detail.exists()).toBe(true);

    // const one_sort_btn = sort_btn.at(2);
    // expect(one_sort_btn.exists()).toBe(true);

    const goToHRDetail = jest.spyOn(wrapper.vm, 'goToHRDetail');

    if (expect(btn_to_detail.exists()).toBe(true)) {
      await btn_to_detail.trigger('click');
      expect(goToHRDetail).toHaveBeenCalled();
    }

    wrapper.destroy();
  });

  // test('Test call function handleFillInDefaultData when created', async() => {
  //   const handleFillInDefaultData = jest.fn();

  //   const localVue = createLocalVue();
  //   const wrapper = mount(HrList, {
  //     localVue,
  //     router,
  //     store,
  //     stubs: {
  //       BIcon: true,
  //     },
  //     methods: {
  //       handleFillInDefaultData,
  //     },
  //   });

  //   const profile = store.getters.profile;

  //   const provincesr_option = [
  //     {
  //       'value': {
  //         'id': 1,
  //         'content': '男',
  //       },
  //       'text': '北海道',
  //     },
  //     {
  //       'value': {
  //         'id': 2,
  //         'content': '男',
  //       },
  //       'text': '青森県',
  //     },
  //     {
  //       'value': {
  //         'id': 3,
  //         'content': '男',
  //       },
  //       'text': '岩手県',
  //     },
  //     {
  //       'value': {
  //         'id': 4,
  //         'content': '男',
  //       },
  //       'text': '宮城県',
  //     },
  //     {
  //       'value': {
  //         'id': 5,
  //         'content': '男',
  //       },
  //       'text': '秋田県',
  //     },
  //     {
  //       'value': {
  //         'id': 6,
  //         'content': '男',
  //       },
  //       'text': '山形県',
  //     },
  //     {
  //       'value': {
  //         'id': 7,
  //         'content': '男',
  //       },
  //       'text': '福島県',
  //     },
  //     {
  //       'value': {
  //         'id': 8,
  //         'content': '男',
  //       },
  //       'text': '茨城県',
  //     },
  //     {
  //       'value': {
  //         'id': 9,
  //         'content': '男',
  //       },
  //       'text': '栃木県',
  //     },
  //     {
  //       'value': {
  //         'id': 10,
  //         'content': '男',
  //       },
  //       'text': '群馬県',
  //     },
  //   ];

  //   await wrapper.setData({
  //     hrCreate: {
  //       hr_organization_id: '',

  //       organization_name: '',
  //       organization_japanese_name: '',

  //       country_id: null,
  //       country_name: '',

  //       full_name: '',
  //       full_name_furigana: '',
  //       gender_id: { id: null, content: '' },
  //       date_of_birth_year: null,
  //       date_of_birth_month: null,
  //       date_of_birth_day: null,
  //       work_form: { id: null, content: '' },
  //       preferred_working_hours: '',
  //       japanese_level: { id: null, content: '' },

  //       final_education_timing_year: null,
  //       final_education_timing_month: null,
  //       final_education_classification: { id: null, content: '' },
  //       final_education_degree: { id: null, content: '' },

  //       major_classification: { id: null, content: '' },
  //       middle_classification: { id: null, content: '' },

  //       main_job_career_1_year_from: null,
  //       main_job_career_1_month_from: null,
  //       main_job_career_1_year_to: null,
  //       main_job_career_1_month_to: null,
  //       main_job_career_1_time_now: false,
  //       main_job_career_1_department: { id: null, content: '' },
  //       main_job_career_1_job_title: null,
  //       main_job_career_1_textarea: '',

  //       main_job_career_2_year_from: null,
  //       main_job_career_2_month_from: null,
  //       main_job_career_2_year_to: null,
  //       main_job_career_2_month_to: null,
  //       main_job_career_2_time_now: false,
  //       main_job_career_2_department: { id: null, content: '' },
  //       main_job_career_2_job_title: null,
  //       main_job_career_2_textarea: '',

  //       main_job_career_3_year_from: null,
  //       main_job_career_3_month_from: null,
  //       main_job_career_3_year_to: null,
  //       main_job_career_3_month_to: null,
  //       main_job_career_3_time_now: false,
  //       main_job_career_3_department: { id: null, content: '' },
  //       main_job_career_3_job_title: null,
  //       main_job_career_3_textarea: '',

  //       personal_pr_special_textarea: '',
  //       remarks_textarea: '',

  //       telephone_phone_number_id: null,
  //       telephone_phone_number: '',
  //       mobile_phone_number_id: null,
  //       mobile_phone_number: '',
  //       mail_address: '',
  //       address_city: '',
  //       address_district: '',
  //       address_number: '',
  //       address_other: '',
  //       hometown_address_city: '',
  //       hometown_address_district: '',
  //       hometown_address_number: '',
  //       hometown_address_other: '',
  //     },
  //   });

  //   handleFillInDefaultData.mockImplementation(() => {
  //     if (profile['hr_organization']) {
  //       const COUNTRY = provincesr_option.find((item) => {
  //         return item ? item['value']['id'] === profile['hr_organization']['country'] : 'asca';
  //       });

  //       wrapper.hrCreate.hr_organization_id = profile['hr_organization']['id'];

  //       wrapper.hrCreate.organization_name = profile['hr_organization']['corporate_name_en'];
  //       wrapper.hrCreate.organization_japanese_name = profile['hr_organization']['corporate_name_ja'];

  //       wrapper.hrCreate.country_id = COUNTRY['value']['id'];
  //       wrapper.hrCreate.country_name = COUNTRY['text'];
  //     }
  //   });

  //   handleFillInDefaultData();

  //   expect(wrapper.vm.hrCreate.hr_organization_id).toEqual(profile['hr_organization']['id']);
  //   expect(wrapper.vm.hrCreate.organization_name).toEqual(profile['hr_organization']['corporate_name_en']);
  //   expect(wrapper.vm.hrCreate.organization_japanese_name).toEqual(profile['hr_organization']['corporate_name_ja']);
  //   expect(wrapper.vm.hrCreate.country_id).toEqual(1);
  //   expect(wrapper.vm.hrCreate.country_name).toEqual('北海道');

  //   expect(handleFillInDefaultData).toHaveBeenCalled();

  //   wrapper.destroy();
  // });

  // test('Test call function handleRegisterHr when click button 保存', () => {
  //   const handleRegisterHr = jest.fn();

  //   const localVue = createLocalVue();

  //   const wrapper = mount(HrList, {
  //     localVue,
  //     router,
  //     store,
  //     stubs: {
  //       BIcon: true,
  //     },
  //     methods: {
  //       handleRegisterHr,
  //     },
  //   });

  //   const BUTTON_REGISTER = wrapper.find('.register-btn');
  //   expect(BUTTON_REGISTER.exists()).toBe(true);

  //   const BUTTON_REGISTER_TEXT = BUTTON_REGISTER.find('.register-text');
  //   expect(BUTTON_REGISTER_TEXT.exists()).toBe(true);
  //   expect(BUTTON_REGISTER_TEXT.text()).toEqual('キャンセル');

  //   BUTTON_REGISTER.trigger('click');

  //   handleRegisterHr();

  //   expect(handleRegisterHr).toHaveBeenCalled();

  //   wrapper.destroy();
  // });

  // test('Test call function handleValidateFormData when click button 保存', async() => {
  //   const handleValidateFormData = jest.fn();

  //   const localVue = createLocalVue();

  //   const wrapper = mount(HrList, {
  //     localVue,
  //     router,
  //     store,
  //     stubs: {
  //       BIcon: true,
  //     },
  //     methods: {
  //       handleValidateFormData,
  //     },
  //   });

  //   await wrapper.setData({
  //     validation_status: false,

  //     hrCreate: {
  //       hr_organization_id: '',

  //       organization_name: '',
  //       organization_japanese_name: '',

  //       country_id: null,
  //       country_name: '',

  //       full_name: '',
  //       full_name_furigana: '',
  //       gender_id: { id: null, content: '' },
  //       date_of_birth_year: null,
  //       date_of_birth_month: null,
  //       date_of_birth_day: null,
  //       work_form: { id: null, content: '' },
  //       preferred_working_hours: '',
  //       japanese_level: { id: null, content: '' },

  //       final_education_timing_year: null,
  //       final_education_timing_month: null,
  //       final_education_classification: { id: null, content: '' },
  //       final_education_degree: { id: null, content: '' },

  //       major_classification: { id: null, content: '' },
  //       middle_classification: { id: null, content: '' },

  //       main_job_career_1_year_from: null,
  //       main_job_career_1_month_from: null,
  //       main_job_career_1_year_to: null,
  //       main_job_career_1_month_to: null,
  //       main_job_career_1_time_now: false,
  //       main_job_career_1_department: { id: null, content: '' },
  //       main_job_career_1_job_title: null,
  //       main_job_career_1_textarea: '',

  //       main_job_career_2_year_from: null,
  //       main_job_career_2_month_from: null,
  //       main_job_career_2_year_to: null,
  //       main_job_career_2_month_to: null,
  //       main_job_career_2_time_now: false,
  //       main_job_career_2_department: { id: null, content: '' },
  //       main_job_career_2_job_title: null,
  //       main_job_career_2_textarea: '',

  //       main_job_career_3_year_from: null,
  //       main_job_career_3_month_from: null,
  //       main_job_career_3_year_to: null,
  //       main_job_career_3_month_to: null,
  //       main_job_career_3_time_now: false,
  //       main_job_career_3_department: { id: null, content: '' },
  //       main_job_career_3_job_title: null,
  //       main_job_career_3_textarea: '',

  //       personal_pr_special_textarea: '',
  //       remarks_textarea: '',

  //       telephone_phone_number_id: null,
  //       telephone_phone_number: '',
  //       mobile_phone_number_id: null,
  //       mobile_phone_number: '',
  //       mail_address: '',
  //       address_city: '',
  //       address_district: '',
  //       address_number: '',
  //       address_other: '',
  //       hometown_address_city: '',
  //       hometown_address_district: '',
  //       hometown_address_number: '',
  //       hometown_address_other: '',
  //     },

  //     error: {
  //       full_name: '',
  //       full_name_furigana: '',
  //       date_of_birth_year: '',
  //       date_of_birth_month: '',
  //       date_of_birth_day: '',
  //       japanese_level: '',
  //       final_education_timing_year: '',
  //       final_education_timing_month: '',
  //       final_education_classification: '',
  //       final_education_degree: '',
  //       major_classification: '',
  //       middle_classification: '',
  //       main_job_career_1_year_from: '',
  //       main_job_career_1_month_from: '',
  //       main_job_career_1_year_to: '',
  //       main_job_career_1_month_to: '',
  //       mail_address: '',
  //     },
  //   });

  //   const BUTTON_REGISTER = wrapper.find('.register-btn');
  //   expect(BUTTON_REGISTER.exists()).toBe(true);

  //   const BUTTON_REGISTER_TEXT = BUTTON_REGISTER.find('.register-text');
  //   expect(BUTTON_REGISTER_TEXT.exists()).toBe(true);
  //   expect(BUTTON_REGISTER_TEXT.text()).toEqual('キャンセル');

  //   BUTTON_REGISTER.trigger('click');

  //   handleValidateFormData.mockImplementation(() => {
  //     let result = false;

  //     if (wrapper.vm.hrCreate.full_name === '') {
  //       wrapper.vm.error.full_name = false;
  //     } else if (wrapper.vm.hrCreate.full_name_furigana === '') {
  //       wrapper.vm.error.full_name_furigana = false;
  //     } else if (wrapper.vm.hrCreate.date_of_birth_year === null) {
  //       wrapper.vm.error.date_of_birth_year = false;
  //     } else if (wrapper.vm.hrCreate.date_of_birth_month === null) {
  //       wrapper.vm.error.date_of_birth_month = false;
  //     } else if (wrapper.vm.hrCreate.date_of_birth_day === null) {
  //       wrapper.vm.error.date_of_birth_day = false;
  //     } else if (wrapper.vm.hrCreate.japanese_level['id'] === null) {
  //       wrapper.vm.error.japanese_level = false;
  //     } else if (wrapper.vm.hrCreate.final_education_timing_year === null) {
  //       wrapper.vm.error.final_education_timing_year = false;
  //     } else if (wrapper.vm.hrCreate.final_education_timing_month === null) {
  //       wrapper.vm.error.final_education_timing_month = false;
  //     } else if (wrapper.vm.hrCreate.final_education_classification['id'] === null) {
  //       wrapper.vm.error.final_education_classification = false;
  //     } else if (wrapper.vm.hrCreate.final_education_degree['id'] === null) {
  //       wrapper.vm.error.final_education_degree = false;
  //     } else if (wrapper.vm.hrCreate.major_classification['id'] === null) {
  //       wrapper.vm.error.major_classification = false;
  //     } else if (wrapper.vm.hrCreate.middle_classification['id'] === null) {
  //       wrapper.vm.error.middle_classification = false;
  //     } else if (wrapper.vm.hrCreate.main_job_career_1_year_from === null) {
  //       wrapper.vm.error.main_job_career_1_year_from = false;
  //     } else if (wrapper.vm.hrCreate.main_job_career_1_month_from === null) {
  //       wrapper.vm.error.main_job_career_1_month_from = false;
  //     } else if (wrapper.vm.hrCreate.main_job_career_1_year_from !== null && wrapper.vm.hrCreate.main_job_career_1_month_from !== null && !wrapper.vm.hrCreate.main_job_career_1_time_now) {
  //       if (wrapper.vm.hrCreate.main_job_career_1_year_to === null) {
  //         wrapper.vm.error.main_job_career_1_year_to = false;
  //       } else if (wrapper.vm.hrCreate.main_job_career_1_month_to === null) {
  //         wrapper.vm.error.main_job_career_1_month_to = false;
  //       } else if (wrapper.vm.hrCreate.mail_address === '') {
  //         wrapper.vm.error.mail_address = false;
  //       } else {
  //         return true;
  //       }
  //     } else if (wrapper.vm.hrCreate.mail_address === '') {
  //       wrapper.vm.error.mail_address = false;
  //     } else {
  //       result = true;
  //     }

  //     wrapper.validation_status = result;
  //   });

  //   handleValidateFormData();

  //   expect(handleValidateFormData).toHaveBeenCalled();

  //   expect(wrapper.vm.validation_status).toBe(true);

  //   wrapper.destroy();
  // });

  // test('Test call function formatDateTime to format date time before send data', async() => {
  //   const formatDateTime = jest.fn();

  //   const localVue = createLocalVue();

  //   const wrapper = mount(HrList, {
  //     localVue,
  //     router,
  //     store,
  //     stubs: {
  //       BIcon: true,
  //     },
  //     methods: {
  //       formatDateTime,
  //     },
  //   });

  //   await wrapper.setData({
  //     month: 1,
  //   });

  //   formatDateTime.mockImplementation((month) => {
  //     if (month < 10) {
  //       return '0' + month;
  //     } else {
  //       return month;
  //     }
  //   });

  //   formatDateTime(wrapper.vm.month);

  //   expect(formatDateTime).toHaveBeenCalled();

  //   expect(wrapper.vm.month).toEqual('01');

  //   wrapper.destroy();
  // });

  // test('Test call function handleChangeFormData when input data in the form', async() => {
  //   const handleChangeFormData = jest.fn();

  //   const localVue = createLocalVue();

  //   const wrapper = mount(HrList, {
  //     localVue,
  //     router,
  //     store,
  //     stubs: {
  //       BIcon: true,
  //     },
  //     methods: {
  //       handleChangeFormData,
  //     },
  //   });

  //   await wrapper.setData({
  //     hrCreate: {
  //       hr_organization_id: '',

  //       organization_name: '',
  //       organization_japanese_name: '',

  //       country_id: null,
  //       country_name: '',

  //       full_name: '',
  //       full_name_furigana: '',
  //     },
  //   });

  //   const HR_ORGANIZATION_ID = wrapper.find('.hr_organization_id');
  //   expect(HR_ORGANIZATION_ID.exists()).toBe(true);

  //   HR_ORGANIZATION_ID.setValue('1');

  //   wrapper.vm.hrCreate.hr_organization_id = HR_ORGANIZATION_ID.value;

  //   const ORGANIZATION_NAME = wrapper.find('.organization_name');
  //   expect(ORGANIZATION_NAME.exists()).toBe(true);

  //   ORGANIZATION_NAME.setValue('1');
  //   wrapper.vm.hrCreate.organization_name = ORGANIZATION_NAME.value;

  //   const ORGANIZATION_JAPANESE_NAME = wrapper.find('.organization_japanese_name');
  //   expect(ORGANIZATION_JAPANESE_NAME.exists()).toBe(true);

  //   ORGANIZATION_JAPANESE_NAME.setValue('1');
  //   wrapper.vm.hrCreate.organization_japanese_name = ORGANIZATION_JAPANESE_NAME.value;

  //   const COUNTRY_ID = wrapper.find('.country_id');
  //   expect(COUNTRY_ID.exists()).toBe(true);

  //   COUNTRY_ID.setValue('1');
  //   wrapper.vm.hrCreate.country_id = COUNTRY_ID.value;

  //   const COUNTRY_NAME = wrapper.find('.country_name');
  //   expect(COUNTRY_NAME.exists()).toBe(true);

  //   COUNTRY_NAME.setValue('1');
  //   wrapper.vm.hrCreate.country_name = COUNTRY_NAME.value;

  //   const FULL_NAME = wrapper.find('.full_name');
  //   expect(FULL_NAME.exists()).toBe(true);

  //   FULL_NAME.setValue('1');
  //   wrapper.vm.hrCreate.full_name = FULL_NAME.value;

  //   const FULL_NAME_FURIGANA = wrapper.find('.full_name_furigana');
  //   expect(FULL_NAME_FURIGANA.exists()).toBe(true);

  //   FULL_NAME_FURIGANA.setValue('1');
  //   wrapper.vm.hrCreate.full_name_furigana = FULL_NAME_FURIGANA.value;

  //   handleChangeFormData();

  //   expect(handleChangeFormData).toHaveBeenCalled();

  //   expect(wrapper.vm.hrCreate.hr_organization_id).toEqual('1');
  //   expect(wrapper.vm.hrCreate.organization_name).toEqual('1');
  //   expect(wrapper.vm.hrCreate.organization_japanese_name).toEqual('1');
  //   expect(wrapper.vm.hrCreate.country_id).toEqual('1');
  //   expect(wrapper.vm.hrCreate.country_name).toEqual('1');
  //   expect(wrapper.vm.hrCreate.full_name).toEqual('1');
  //   expect(wrapper.vm.hrCreate.full_name_furigana).toEqual('1');

  //   wrapper.destroy();
  // });

  // test('Test call function handCancelCreateHr when click on button キャンセル', async() => {
  //   const handCancelCreateHr = jest.fn();

  //   const localVue = createLocalVue();

  //   const wrapper = mount(HrList, {
  //     localVue,
  //     router,
  //     store,
  //     stubs: {
  //       BIcon: true,
  //     },
  //     methods: {
  //       handCancelCreateHr,
  //     },
  //   });

  //   await wrapper.setData({
  //     editting: false,
  //   });

  //   const BUTTON_CANCEL = wrapper.find('.cancel-btn');
  //   expect(BUTTON_CANCEL.exists()).toBe(true);

  //   const BUTTON_CANCEL_TEXT = BUTTON_CANCEL.find('.cancel-text');
  //   expect(BUTTON_CANCEL_TEXT.exists()).toBe(true);
  //   expect(BUTTON_CANCEL_TEXT.text()).toEqual('キャンセル');

  //   BUTTON_CANCEL.trigger('click');

  //   handCancelCreateHr.mockImplementation(() => {
  //     wrapper.vm.editting = true;
  //   });

  //   handCancelCreateHr();

  //   expect(handCancelCreateHr).toHaveBeenCalled();

  //   expect(wrapper.vm.editting).toBe(true);

  //   wrapper.destroy();
  // });

  // test('Test call function handleToggleConfirmLeavingModal when click on button キャンセル', async() => {
  //   const handleToggleConfirmLeavingModal = jest.fn();

  //   const localVue = createLocalVue();

  //   const wrapper = mount(HrList, {
  //     localVue,
  //     router,
  //     store,
  //     stubs: {
  //       BIcon: true,
  //     },
  //     methods: {
  //       handleToggleConfirmLeavingModal,
  //     },
  //   });

  //   await wrapper.setData({
  //     statusModalConfirmLeaving: false,
  //   });

  //   const BUTTON_CANCEL = wrapper.find('.cancel-btn');
  //   expect(BUTTON_CANCEL.exists()).toBe(true);

  //   const BUTTON_CANCEL_TEXT = BUTTON_CANCEL.find('.cancel-text');
  //   expect(BUTTON_CANCEL_TEXT.exists()).toBe(true);
  //   expect(BUTTON_CANCEL_TEXT.text()).toEqual('キャンセル');

  //   BUTTON_CANCEL.trigger('click');

  //   handleToggleConfirmLeavingModal.mockImplementation(() => {
  //     wrapper.vm.statusModalConfirmLeaving = !wrapper.vm.statusModalConfirmLeaving;
  //   });

  //   handleToggleConfirmLeavingModal();

  //   expect(handleToggleConfirmLeavingModal).toHaveBeenCalled();

  //   expect(wrapper.vm.statusModalConfirmLeaving).toBe(!wrapper.vm.statusModalConfirmLeaving);

  //   wrapper.destroy();
  // });

  // test('Test render enough input fields', () => {
  //   const localVue = createLocalVue();
  //   const wrapper = mount(HrList, {
  //     localVue,
  //     router,
  //     store,
  //     stubs: {
  //       BIcon: true,
  //     },
  //   });

  //   const INPUT_FIELDS = wrapper.findAll('.input-field');
  //   expect(INPUT_FIELDS.length).toEqual(32);

  //   wrapper.destroy();
  // });
});

