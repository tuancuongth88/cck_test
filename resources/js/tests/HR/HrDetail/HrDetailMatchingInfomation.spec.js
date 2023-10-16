import store from '@/store';
import router from '@/router';
import tab5MatchingSituation from '@/pages/Hr/Detail/TabEMatchingSituation';
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

  test('Test 1: Test render tab Basic Infomation (component)', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(tab5MatchingSituation, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    const render_component = wrapper.find('.hr-detail-matching-situation-tab');
    expect(render_component.exists()).toBe(true);

    wrapper.destroy();
  });

  test('Test 2: test render data Basic Infomation', async() => {
    const DATA = {
      data: {
        dataEntries: [],
        dataOffers: [],
        dataInterviews: [],
        dataResults: [],
      },
      detailData: {
        type: Object,
        require: true,
        default: function() {
          return {};
        },
      },
    };

    const localVue = createLocalVue();
    const wrapper = shallowMount(tab5MatchingSituation, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
      mocks: { $t: () => {} },
      propsData: {
        detailData: [],
      },
    });

    expect(wrapper.vm.dataEntries).toEqual(DATA.data.dataEntries);
    expect(wrapper.vm.dataOffers).toEqual(DATA.data.dataOffers);
    expect(wrapper.vm.dataInterviews).toEqual(DATA.data.dataInterviews);
    expect(wrapper.vm.dataResults).toEqual(DATA.data.dataResults);

    expect(wrapper.props().detailData).toEqual([]);

    wrapper.destroy();
  });

  test('Test 3: test render content data Entry Application History', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(tab5MatchingSituation, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
      propsData: {
        detailData: [],
      },
    });

    expect(wrapper.props().detailData).toEqual([]);
    await wrapper.setProps({ detailData: DATA });

    expect(wrapper.props().detailData).toEqual(DATA);

    expect(wrapper).toMatchSnapshot();

    const label_entry_applycation = wrapper.find('.label-entry-applycation');
    expect(label_entry_applycation.exists()).toBe(true);
    expect(label_entry_applycation.text()).toBe('HR_LIST.ENTRY_APPLICATION');

    const status_entry = wrapper.findAll('.status-entry');
    expect(status_entry.exists()).toBe(true);
    expect(status_entry.length).toEqual(2);
    expect(status_entry.at(1).text()).toEqual('申請中');

    const entry_title_company = wrapper.findAll('.entry-title-company');
    expect(entry_title_company.exists()).toBe(true);
    expect(entry_title_company.length).toEqual(2);
    expect(entry_title_company.at(1).text()).toEqual('php/City Computer Co., Ltd.');

    const label_entry_date = wrapper.findAll('.label-entry-date');
    expect(label_entry_date.exists()).toBe(true);
    expect(label_entry_date.length).toEqual(2);
    expect(label_entry_date.at(1).text()).toBe('HR_LIST.ENTRY_DATE：');

    const entry_date = wrapper.findAll('.entry-date');
    expect(entry_date.exists()).toBe(true);
    expect(entry_date.length).toEqual(2);
    expect(entry_date.at(1).text()).toEqual('');

    wrapper.destroy();
  });

  test('Test 4: test render content data Offer History', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(tab5MatchingSituation, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
      propsData: {
        detailData: [],
      },
    });

    expect(wrapper.props().detailData).toEqual([]);
    await wrapper.setProps({ detailData: DATA });

    expect(wrapper.props().detailData).toEqual(DATA);

    // expect(wrapper).toMatchSnapshot();

    const label_entry_applycation = wrapper.find('.label-offer-received');
    expect(label_entry_applycation.exists()).toBe(true);
    expect(label_entry_applycation.text()).toBe('HR_LIST.OFFER_RECEIVED');

    const status_entry = wrapper.findAll('.offer-status');
    expect(status_entry.exists()).toBe(true);
    expect(status_entry.length).toEqual(2);
    expect(status_entry.at(1).text()).toEqual('申請中');

    const entry_title_company = wrapper.findAll('.offer-title-company');
    expect(entry_title_company.exists()).toBe(true);
    expect(entry_title_company.length).toEqual(2);
    expect(entry_title_company.at(1).text()).toEqual('Tuyên php/City Computer Co., Ltd.');

    const label_entry_date = wrapper.findAll('.label-offer-date');
    expect(label_entry_date.exists()).toBe(true);
    expect(label_entry_date.length).toEqual(2);
    expect(label_entry_date.at(1).text()).toBe('HR_LIST.ENTRY_DATE：');

    const entry_date = wrapper.findAll('.offer-date');
    expect(entry_date.exists()).toBe(true);
    expect(entry_date.length).toEqual(2);
    expect(entry_date.at(1).text()).toEqual('');

    wrapper.destroy();
  });

  test('Test 5: test render content data Interview History', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(tab5MatchingSituation, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
      propsData: {
        detailData: [],
      },
    });

    expect(wrapper.props().detailData).toEqual([]);
    await wrapper.setProps({ detailData: DATA });

    expect(wrapper.props().detailData).toEqual(DATA);

    // expect(wrapper).toMatchSnapshot();

    const label_entry_applycation = wrapper.find('.label-arr-interview');
    expect(label_entry_applycation.exists()).toBe(true);
    expect(label_entry_applycation.text()).toBe('HR_LIST.ARRINTERVIEW');

    const status_entry = wrapper.findAll('.interview-status');
    expect(status_entry.exists()).toBe(true);
    expect(status_entry.length).toEqual(3);
    expect(status_entry.at(1).text()).toEqual('オファー承認');

    const entry_title_company = wrapper.findAll('.interview-title-company');
    expect(entry_title_company.exists()).toBe(true);
    expect(entry_title_company.length).toEqual(3);
    expect(entry_title_company.at(1).text()).toEqual('Tuyên php/City Computer Co., Ltd.');

    const label_entry_date = wrapper.findAll('.label-interview-date');
    expect(label_entry_date.exists()).toBe(true);
    expect(label_entry_date.length).toEqual(3);
    expect(label_entry_date.at(1).text()).toBe('HR_LIST.ENTRY_DATE：');

    const entry_date = wrapper.findAll('.interview-date');
    expect(entry_date.exists()).toBe(true);
    expect(entry_date.length).toEqual(3);
    expect(entry_date.at(1).text()).toEqual('');

    wrapper.destroy();
  });

  test('Test 6: test render content data Result History', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(tab5MatchingSituation, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
      propsData: {
        detailData: [],
      },
    });

    expect(wrapper.props().detailData).toEqual([]);
    await wrapper.setProps({ detailData: DATA });

    expect(wrapper.props().detailData).toEqual(DATA);

    // expect(wrapper).toMatchSnapshot();

    const label_entry_applycation = wrapper.find('.label-result');
    expect(label_entry_applycation.exists()).toBe(true);
    expect(label_entry_applycation.text()).toBe('HR_LIST.RESULT');

    const status_entry = wrapper.findAll('.result-status');
    expect(status_entry.exists()).toBe(true);
    expect(status_entry.length).toEqual(1);
    expect(status_entry.at(0).text()).toEqual('内定承諾');

    const entry_title_company = wrapper.findAll('.result-title-company');
    expect(entry_title_company.exists()).toBe(true);
    expect(entry_title_company.length).toEqual(1);
    expect(entry_title_company.at(0).text()).toEqual('php/City Computer Co., Ltd.');

    const label_entry_date = wrapper.findAll('.label-result-date');
    expect(label_entry_date.exists()).toBe(true);
    expect(label_entry_date.length).toEqual(1);
    expect(label_entry_date.at(0).text()).toBe('HR_LIST.ENTRY_DATE：');

    const entry_date = wrapper.findAll('.result-date');
    expect(entry_date.exists()).toBe(true);
    expect(entry_date.length).toEqual(1);
    expect(entry_date.at(0).text()).toEqual('');

    wrapper.destroy();
  });
});
