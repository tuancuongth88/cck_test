import store from '@/store';
import router from '@/router';
import HrDetail from '@/pages/Hr/Detail/index.vue';
import HrDetailControl from '@/pages/Hr/HrBasicInformation/index.vue';
import SelectJobsToOffer from '@/pages/Hr/HrBasicInformation/SelectJobsToOffer.vue';
import { status_select_jobs_to_offer } from '@/pages/Hr/common.js';
import { mount, createLocalVue, shallowMount } from '@vue/test-utils';

describe('TEST SCREEN HR Detail Basic Infomation', () => {
  const DATA = {
    id: 11,
    country_id: 1,
    hr_organization_id: 1,
    user_id: '5',
    full_name: 'Hoa 11',
    full_name_ja: 'Hoa 11ja',
    gender: 1,
    date_of_birth: '2000-05-11',
    work_form: 1,
    preferred_working_hours: 8,
    japanese_level: 1,
    final_education_date: '2023-08-01',
    final_education_classification: 1,
    final_education_degree: 1,
    major_classification_id: 1,
    middle_classification_id: 2,
    personal_pr_special_notes: 'test personal pr special note',
    remarks: 'remarks',
    telephone_number: '+84 0987654321',
    mobile_phone_number: '+81 0123456789',
    mail_address: 'vuhoa@gmail.com',
    address_city: 'Ha Noi city',
    address_district: '266 Doi Can address district',
    address_number: '266 address number',
    address_other: 'other address',
    hometown_city: 'Ha Tay hometown',
    home_town_district: 'Ung Hoa homtown district',
    home_town_number: '144 homtown number',
    home_town_other: 'other homtown',
    file_cv_id: 49,
    file_job_id: 50,
    file_others: '[{"name":null,"file_id":null,"file_path":null}]',
    status: 1,
    created_by: 1,
    updated_by: null,
    deleted_at: null,
    created_at: 1689214928,
    updated_at: 1689577354,
    entry: [
      {
        id: 1,
        status: 4,
        job_title: 'php',
        company_name: 'City Computer Co., Ltd.',
        updated_at: '2023年07月11日',
      },
      {
        id: 5,
        status: 1,
        job_title: 'php',
        company_name: 'City Computer Co., Ltd.',
        updated_at: '2023年07月11日',
      },
    ],
    offer: [
      {
        id: 2,
        status: 2,
        job_title: 'php',
        company_name: 'City Computer Co., Ltd.',
        updated_at: '2023年07月10日',
      },
      {
        id: 3,
        status: 1,
        job_title: 'Tuyên php',
        company_name: 'City Computer Co., Ltd.',
        updated_at: '2023年07月10日',
      },
    ],
    interview: [
      {
        id: 1,
        status: 2,
        job_title: 'php',
        company_name: 'City Computer Co., Ltd.',
        updated_at: '2023年05月20日',
      },
      {
        id: 6,
        status: 2,
        job_title: 'Tuyên php',
        company_name: 'City Computer Co., Ltd.',
        updated_at: '2023年05月20日',
      },
      {
        id: 11,
        status: 1,
        job_title: 'php',
        company_name: 'City Computer Co., Ltd.',
        updated_at: '2023年07月11日',
      },
    ],
    result: [
      {
        id: 1,
        status: 3,
        job_title: 'php',
        company_name: 'City Computer Co., Ltd.',
        updated_at: '2023年07月20日',
      },
    ],
    fileCV: {
      id: 49,
      file_model: 'test_file.pptx (0.03MB)',
      file_name: 'test_file.pptx',
      file_extension: 'pptx',
      file_path: 'storage/test_file.pptx (0.03MB)/20230717/308ae11a7e6c4b09440972ebfc2e45b5test_file.pptx',
      file_size: '32825',
      item_type: '',
      item_id: null,
      created_at: '2023-07-17T07:02:30.000000Z',
      updated_at: '2023-07-17T07:02:30.000000Z',
      deleted_at: null,
    },
    fileJob: {
      id: 50,
      file_model: 'test_file.pptx (0.03MB)',
      file_name: 'test_file.pptx',
      file_extension: 'pptx',
      file_path: 'storage/test_file.pptx (0.03MB)/20230717/d4a50aa9c61e0e45bd5eecd6a07c3bcbtest_file.pptx',
      file_size: '32825',
      item_type: '',
      item_id: null,
      created_at: '2023-07-17T07:02:33.000000Z',
      updated_at: '2023-07-17T07:02:33.000000Z',
      deleted_at: null,
    },
    is_favorite: 1,
    file_c_v: {
      id: 49,
      file_model: 'test_file.pptx (0.03MB)',
      file_name: 'test_file.pptx',
      file_extension: 'pptx',
      file_path: 'storage/test_file.pptx (0.03MB)/20230717/308ae11a7e6c4b09440972ebfc2e45b5test_file.pptx',
      file_size: '32825',
      item_type: '',
      item_id: null,
      created_at: '2023-07-17T07:02:30.000000Z',
      updated_at: '2023-07-17T07:02:30.000000Z',
      deleted_at: null,
    },
    file_job: {
      id: 50,
      file_model: 'test_file.pptx (0.03MB)',
      file_name: 'test_file.pptx',
      file_extension: 'pptx',
      file_path: 'storage/test_file.pptx (0.03MB)/20230717/d4a50aa9c61e0e45bd5eecd6a07c3bcbtest_file.pptx',
      file_size: '32825',
      item_type: '',
      item_id: null,
      created_at: '2023-07-17T07:02:33.000000Z',
      updated_at: '2023-07-17T07:02:33.000000Z',
      deleted_at: null,
    },
    main_jobs: [
      {
        id: 32,
        hrs_id: 11,
        main_job_career_date_from: '2021-01-01',
        main_job_career_date_to: '2021-02-01',
        department_id: 18,
        job_id: 79,
        detail: 'gsfdfg',
        deleted_at: null,
        created_at: '2023-07-17T07:02:34.000000Z',
        updated_at: '2023-07-17T07:02:34.000000Z',
      },
    ],
  };

  test('Test 1: test render component HR detail', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(HrDetail, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    const render_component = wrapper.find('.hr-detail');
    expect(render_component.exists()).toBe(true);
    // expect(render_component).toMatchSnapshot();

    const render_title = wrapper.find('.title-hr-detail');
    expect(render_title.exists()).toBe(true);
    expect(render_title.text()).toEqual('HR_LIST_FORM.DEAIL_FORM_TITLE');

    wrapper.destroy();
  });

  test('Test 2: test render data hr detail', async() => {
    const HR_DETAIL_DATA = {
      overlay: {
        opacity: 0,
        show: true,
        blur: '1rem',
        rounded: 'sm',
        variant: 'light',
        fixed: true,
      },
      // status_select_jobs_to_offer: status_select_jobs_to_offer,
      id_hr: this.$route.params.id,

      detail_data: {
        address_city: '',
        address_district: '',
        address_number: '',
        address_other: null,
        country_id: null,
        date_of_birth: '2000-01-01',
        deleted_at: null,
        entries: [],
        file_c_v: {
          id: null,
          file_name: '',
          file_path: '',
        },
        file_cv_id: null,
        file_job: {
          id: null,
          file_name: '',
          file_path: '',
        },
        file_job_id: null,
        file_others: '',
        final_education_classification: null,
        final_education_date: '2000-01-01',
        final_education_degree: null,
        full_name: '',
        full_name_ja: '',
        gender: null,
        home_town_district: '',
        home_town_number: '',
        home_town_other: null,
        hometown_city: '',
        hr_organization_id: null,
        id: null,
        interviews: [],
        is_favorite: null,
        japanese_level: null,
        mail_address: '',
        main_jobs: [],
        major_classification_id: null,
        middle_classification_id: null,
        mobile_phone_number: null,
        offers: [],
        personal_pr_special_notes: null,
        preferred_working_hours: null,
        remarks: null,
        results: [],
        status: null,
        telephone_number: null,
        user_id: '',
        work_form: null,
      },

      role_accept: [1, 3, 5],
    };

    const localVue = createLocalVue();
    const wrapper = mount(HrDetail, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    expect(wrapper.vm.overlay).toEqual(HR_DETAIL_DATA.overlay);
    expect(wrapper.vm.id_hr).toEqual(HR_DETAIL_DATA.id_hr);
    expect(wrapper.vm.detail_data).toEqual(HR_DETAIL_DATA.detail_data);
    expect(wrapper.vm.role_accept).toEqual(HR_DETAIL_DATA.role_accept);

    // const btn_back_to_list = wrapper.find('.btn-back-to-list-hr');
    // expect(btn_back_to_list.exists()).toBe(true);
    // expect(table_hr).toMatchSnapshot();

    // const handleBackToHrList = jest.spyOn(wrapper.vm, 'handleBackToHrList');

    // if (expect(btn_back_to_list.exists()).toBe(true)) {
    //   await btn_back_to_list.trigger('click');
    //   expect(handleBackToHrList).toHaveBeenCalled();
    // }

    wrapper.destroy();
  });

  test('Test 3: test render button back to list and click', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(HrDetail, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    const btn_back_to_list = wrapper.find('.btn-back-to-list-hr');
    expect(btn_back_to_list.exists()).toBe(true);

    const btn_text = wrapper.find('.btn-back-to-list-text');
    expect(btn_text.exists()).toBe(true);
    expect(btn_text.text()).toEqual('BUTTON.BACK_TO_LIST');

    const handleBackToHrList = jest.spyOn(wrapper.vm, 'handleBackToHrList');

    if (expect(btn_back_to_list.exists()).toBe(true)) {
      await btn_back_to_list.trigger('click');
      expect(handleBackToHrList).toHaveBeenCalled();
    }

    wrapper.destroy();
  });

  test('Test 4: test render button to edit and click', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(HrDetail, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    const btn_back_to_list = wrapper.find('.btn-to-edit-hr');
    expect(btn_back_to_list.exists()).toBe(true);

    const btn_text = wrapper.find('.btn-to-edit-hr-text');
    expect(btn_text.exists()).toBe(true);
    expect(btn_text.text()).toEqual('BUTTON_EDIT');

    const handleGoToEditHr = jest.spyOn(wrapper.vm, 'handleGoToEditHr');

    if (expect(btn_back_to_list.exists()).toBe(true)) {
      await btn_back_to_list.trigger('click');
      expect(handleGoToEditHr).toHaveBeenCalled();
    }

    wrapper.destroy();
  });

  test('Test 5: test render component HR detail Basic control', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(HrDetailControl, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    const render_component = wrapper.find('.hr-basic-information');
    expect(render_component.exists()).toBe(true);
    // expect(render_component).toMatchSnapshot();

    wrapper.destroy();
  });

  test('Test 6: test render data HR detail control', async() => {
    const CONTROL_DATA = {
      cv_status: 0,

      statusModalSelectJobsToOffer: false,

      favorite_added: true,

      formData: {},

      companyList: [],

      company_name_en: '',

      company_name_jp: '',

      role_offer: [1, 2, 4],
    };
    const localVue = createLocalVue();
    const wrapper = shallowMount(HrDetailControl, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    expect(wrapper.vm.cv_status).toEqual(CONTROL_DATA.cv_status);
    expect(wrapper.vm.statusModalSelectJobsToOffer).toEqual(CONTROL_DATA.statusModalSelectJobsToOffer);
    expect(wrapper.vm.favorite_added).toEqual(CONTROL_DATA.favorite_added);
    expect(wrapper.vm.formData).toEqual(CONTROL_DATA.formData);
    expect(wrapper.vm.companyList).toEqual(CONTROL_DATA.companyList);
    expect(wrapper.vm.company_name_en).toEqual(CONTROL_DATA.company_name_en);
    expect(wrapper.vm.company_name_jp).toEqual(CONTROL_DATA.company_name_jp);
    expect(wrapper.vm.role_offer).toEqual(CONTROL_DATA.role_offer);

    wrapper.destroy();
  });

  test('Test 7: test render basic infomation hr', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(HrDetailControl, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    await wrapper.setProps({ basicInformation: DATA, hrIndentify: 11 });

    const name_en = wrapper.find('.info-full-name');
    expect(name_en.exists()).toBe(true);
    expect(name_en.text()).toEqual('Hoa 11');

    const name_en_ja = wrapper.find('.info-full-name-ja');
    expect(name_en_ja.exists()).toBe(true);
    expect(name_en_ja.text()).toEqual('Hoa 11ja');

    const info_is = wrapper.find('.info-id');
    expect(info_is.exists()).toBe(true);
    expect(info_is.text()).toEqual(11);

    wrapper.destroy();
  });

  test('Test 8: test render buttom favorite hr and click', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(HrDetailControl, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    const btn_favorite = wrapper.find('#favorite');
    expect(btn_favorite.exists()).toBe(true);

    const handleAddedToFavorites = jest.spyOn(wrapper.vm, 'handleAddedToFavorites');

    if (expect(btn_favorite.exists()).toBe(true)) {
      await btn_favorite.trigger('click');
      expect(handleAddedToFavorites).toHaveBeenCalled();
    }
    wrapper.destroy();
  });

  test('Test 9: test render buttom select job to offer and click', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(HrDetailControl, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
      computed: { checkRoleOffer: true },
    });

    const btn_offer = wrapper.find('#offer');
    expect(btn_offer.exists()).toBe(true);

    const handleToggleModalSelectJobsToOffer = jest.spyOn(wrapper.vm, 'handleToggleModalSelectJobsToOffer');

    if (expect(btn_offer.exists()).toBe(true)) {
      await btn_offer.trigger('click');
      expect(handleToggleModalSelectJobsToOffer).toHaveBeenCalled();
    }
    wrapper.destroy();
  });

  test('Test 10: test render modal offer and data', async() => {
    const DATA_OFFER = {
      data: {
        statusModalSelectJobsToOffer: false,
        statusModalMakeAnOffer: false,
        reRender: 0,
        status_select_jobs_to_offer: status_select_jobs_to_offer,
        selectJobFields: [
          {
            key: 'id',
            sortable: true,
            label: 'ID',

            thClass: 'col-1',
          },
          {
            key: 'title',
            sortable: true,
            label: this.$t('SELECT_JOB_TO_OFFER.JOB_NAME'),
            thClass: 'col-2',
          },
          {
            key: 'company_name_jp',
            sortable: true,
            label: this.$t('SELECT_JOB_TO_OFFER.COMPANY_NAME'),
            thClass: 'col-3',
          },
          {
            key: 'status',
            sortable: true,
            label: this.$t('SELECT_JOB_TO_OFFER.STATUS'),
            thClass: 'col-4',
          },
        ],
        listJobSelect: [],
        id_offer_data_selected: null,
        offer_job: '',
        currentPage: 1,
        totalRows: 0,
        perPage: 20,

        paramsSearch: null,
        sort: {
          field: '',
          sort_by: '',
        },
      },
      data_props: {
        hrId: {
          type: Number,
          require: true,
          default: function() {
            return null;
          },
        },
        hrFullName: {
          type: String,
          require: true,
          default: function() {
            return '';
          },
        },
        hrFullNameJp: {
          type: String,
          require: true,
          default: function() {
            return '';
          },
        },
      },
    };

    const localVue = createLocalVue();
    const wrapper = shallowMount(SelectJobsToOffer, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    expect(wrapper.vm.sort).toEqual(DATA_OFFER.data.sort);
    expect(wrapper.vm.paramsSearch).toEqual(DATA_OFFER.data.paramsSearch);
    expect(wrapper.vm.perPage).toEqual(DATA_OFFER.data.perPage);
    expect(wrapper.vm.totalRows).toEqual(DATA_OFFER.data.totalRows);
    expect(wrapper.vm.currentPage).toEqual(DATA_OFFER.data.currentPage);
    expect(wrapper.vm.offer_job).toEqual(DATA_OFFER.data.offer_job);
    expect(wrapper.vm.id_offer_data_selected).toEqual(DATA_OFFER.data.id_offer_data_selected);
    expect(wrapper.vm.listJobSelect).toEqual(DATA_OFFER.data.listJobSelect);
    expect(wrapper.vm.selectJobFields).toEqual(DATA_OFFER.data.selectJobFields);
    expect(wrapper.vm.status_select_jobs_to_offer).toEqual(DATA_OFFER.data.status_select_jobs_to_offer);
    expect(wrapper.vm.reRender).toEqual(DATA_OFFER.data.reRender);
    expect(wrapper.vm.statusModalMakeAnOffer).toEqual(DATA_OFFER.data.statusModalMakeAnOffer);
    expect(wrapper.vm.statusModalSelectJobsToOffer).toEqual(DATA_OFFER.data.statusModalSelectJobsToOffer);
    expect(wrapper.vm.listJobSelect).toEqual(DATA_OFFER.data.listJobSelect);

    expect(wrapper.props().hrId).toEqual(DATA_OFFER.data_props.hrId);
    expect(wrapper.props().hrFullName).toEqual(DATA_OFFER.data_props.hrFullName);
    expect(wrapper.props().hrFullNameJp).toEqual(DATA_OFFER.data_props.hrFullNameJp);

    // const handleToggleModalSelectJobsToOffer = jest.spyOn(wrapper.vm, 'handleToggleModalSelectJobsToOffer');

    // if (expect(btn_offer.exists()).toBe(true)) {
    //   await btn_offer.trigger('click');
    //   expect(handleToggleModalSelectJobsToOffer).toHaveBeenCalled();
    // }
    wrapper.destroy();
  });

  test('Test 11: test render button confirm offer and click', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(SelectJobsToOffer, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    const btn_confirm_offer = wrapper.find('.btn-confirm-offer');
    expect(btn_confirm_offer.exists()).toBe(true);

    const goToConfirmSelectJobToOffer = jest.spyOn(wrapper.vm, 'goToConfirmSelectJobToOffer');

    if (expect(btn_confirm_offer.exists()).toBe(true)) {
      await btn_confirm_offer.trigger('click');
      expect(goToConfirmSelectJobToOffer).toHaveBeenCalled();
    }
    wrapper.destroy();
  });

  test('Test 12: test render button cancel offer and click', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(SelectJobsToOffer, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    const btn_cancel_offer = wrapper.find('.btn-cancel-offer');
    expect(btn_cancel_offer.exists()).toBe(true);

    const cancleFormSelectJob = jest.spyOn(wrapper.vm, 'cancleFormSelectJob');

    if (expect(btn_cancel_offer.exists()).toBe(true)) {
      await btn_cancel_offer.trigger('click');
      expect(cancleFormSelectJob).toHaveBeenCalled();
    }
    wrapper.destroy();
  });
});
