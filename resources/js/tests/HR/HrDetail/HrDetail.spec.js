import store from '@/store';
import router from '@/router';
// import HrDetail from '@/pages/Hr/Detail/index.vue';
// import HrDetaiBasic from '@/pages/Hr/Detail/TabABasicInformation/index.vue';
import tab1BasicInformation from '@/pages/Hr/Detail/TabABasicInformation';
import tab2EducationWorkHistory from '@/pages/Hr/Detail/TabBEducationWorkHistory';
import tab3PersonalPrRemarks from '@/pages/Hr/Detail/TabCPersonalPrRemarks';
import tab4Contact from '@/pages/Hr/Detail/TabDContact';
// import tab5MatchingSituation from '@/pages/Hr/Detail/TabEMatchingSituation';
// import HrBasicInformation from '@/pages/Hr/HrBasicInformation';

import {
  final_education_degree_options,
  final_education_classification_options,
} from '@/pages/Hr/common.js';
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
    const wrapper = shallowMount(tab1BasicInformation, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    const render_component = wrapper.find('.hr-detail-basic-info');
    expect(render_component.exists()).toBe(true);

    wrapper.destroy();
  });

  test('Test 2: test render data Basic Infomation', async() => {
    const DATA = {
      basicForm: {},
      gender_option: [
        { value: { id: null, content: '' }, text: '選択してください', translate: 'please select',
        }, // Không xác định
        { value: { id: 1, content: '男' }, text: '男', translate: 'male' },
        { value: { id: 2, content: '女' }, text: '女', translate: 'female' },
      ],
      work_form_option: [
        {
          value: { id: null, content: '' },
          text: '選択してください',
          translate: 'please select',
        },
        {
          value: { id: 1, content: '正社員' },
          text: '正社員',
          translate: 'full-time employee',
        },
        {
          value: { id: 2, content: '契約社員' },
          text: '契約社員',
          translate: 'contract employee',
        },
        {
          value: { id: 3, content: '派遣社員' },
          text: '派遣社員',
          translate: 'Temporary staff',
        },
        { value: { id: 4, content: 'その他' }, text: 'その他', translate: 'others' },
      ],
    };

    const localVue = createLocalVue();
    const wrapper = shallowMount(tab1BasicInformation, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
      mocks: { $t: () => {} },
    });

    expect(wrapper.vm.basicForm).toEqual(DATA.basicForm);

    expect(wrapper.vm.gender_option).toEqual(DATA.gender_option);
    expect(wrapper.vm.work_form_option).toEqual(DATA.work_form_option);

    wrapper.destroy();
  });

  test('Test 3: test render content data Basic Infomation', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(tab1BasicInformation, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    await wrapper.setData({ basicForm: DATA });
    // expect(wrapper).toMatchSnapshot();

    const label_full_name = wrapper.find('.full-name-hr-label');
    expect(label_full_name.exists()).toBe(true);
    expect(label_full_name.text()).toBe('氏名');
    const content_full_name = wrapper.find('.full-name-hr-content');
    expect(content_full_name.exists()).toBe(true);
    expect(content_full_name.text()).toEqual('Hoa 11');

    const label_full_name_ja = wrapper.find('.full-name-ja-label');
    expect(label_full_name_ja.exists()).toBe(true);
    expect(label_full_name_ja.text()).toBe('氏名（ﾌﾘｶﾞﾅ）');
    const content_full_name_ja = wrapper.find('.full-name-ja-content');
    expect(content_full_name_ja.exists()).toBe(true);
    expect(content_full_name_ja.text()).toEqual('Hoa 11ja');

    const label_gender = wrapper.find('.gender-label');
    expect(label_gender.exists()).toBe(true);
    expect(label_gender.text()).toBe('性別');
    const content_gender = wrapper.find('.gender-content');
    expect(content_gender.exists()).toBe(true);
    expect(content_gender.text()).toEqual('男');

    const label_date_of_birth = wrapper.find('.date-of-birth-label');
    expect(label_date_of_birth.exists()).toBe(true);
    expect(label_date_of_birth.text()).toBe('生年月日');
    const content_date_of_birth = wrapper.find('.date-of-birth-content');
    expect(content_date_of_birth.exists()).toBe(true);
    expect(content_date_of_birth.text()).toEqual('2000年05月11日');
    const content_count_age = wrapper.find('.count-age-content');
    expect(content_count_age.exists()).toBe(true);
    expect(content_count_age.text()).toEqual('(23歳)');

    const label_basic_work_form = wrapper.find('.label-basic-work-form');
    expect(label_basic_work_form.exists()).toBe(true);
    expect(label_basic_work_form.text()).toBe('勤務形態');
    const content_basic_work_form = wrapper.find('.content-basic-work-form');
    expect(content_basic_work_form.exists()).toBe(true);
    expect(content_basic_work_form.text()).toEqual('正社員');

    const label_preferred_working_hours = wrapper.find('.label-preferred-working-hours');
    expect(label_preferred_working_hours.exists()).toBe(true);
    expect(label_preferred_working_hours.text()).toBe('勤務形態（非常勤）');
    const content_preferred_working_hours = wrapper.find('.content-preferred-working-hours');
    expect(content_preferred_working_hours.exists()).toBe(false);
    // expect(content_preferred_working_hours.text()).toEqual('');

    const label_japan_level = wrapper.find('.label-japan-level');
    expect(label_japan_level.exists()).toBe(true);
    expect(label_japan_level.text()).toBe('生年月日');
    const content_japan_level = wrapper.find('.content-japan-level');
    expect(content_japan_level.exists()).toBe(true);
    expect(content_japan_level.text()).toEqual('N1');

    wrapper.destroy();
  });

  // EducationWorkHistory
  const DATA_EDU_WORK = {
    EDU_WORK: {
      final_education_date: '2023年08月',
      final_education_classification: '卒業',
      final_education_degree: '博士',
      major_classification: '',
      middle_classification: '',
      main_job_1: {
        is_null: false,
        main_job_career_date_from: '2021年01月',
        main_job_career_date_to: '2021年02月',
        department: '',
        job_id: '',
        detail: 'gsfdfg',
      },
      main_job_2: {
        is_null: true,
        main_job_career_date_from: '',
        main_job_career_date_to: '',
        department: '',
        job_id: '',
        detail: '',
      },
      main_job_3: {
        is_null: true,
        main_job_career_date_from: '',
        main_job_career_date_to: '',
        department: '',
        job_id: '',
        detail: '',
      },
    },

    major_middle_info: [
      {
        id: 18,
        name_ja: '経営学等',
        name_en: 'Business administration, etc',
        type: 2,
        job_info: [
          {
            id: 79,
            job_type_id: 18,
            name_ja: '会計学',
            name_en: 'Accounting',
          },
          {
            id: 80,
            job_type_id: 18,
            name_ja: '経営学',
            name_en: 'Business Administration',
          },
          {
            id: 81,
            job_type_id: 18,
            name_ja: 'マーケティング',
            name_en: 'Marketing',
          },
          {
            id: 82,
            job_type_id: 18,
            name_ja: 'その他',
            name_en: 'Others',
          },
        ],
      },
      {
        id: 19,
        name_ja: '人文化学',
        name_en: 'Humanities',
        type: 2,
        job_info: [
          {
            id: 83,
            job_type_id: 19,
            name_ja: '芸術',
            name_en: 'Art',
          },
          {
            id: 84,
            job_type_id: 19,
            name_ja: '歴史学',
            name_en: 'History',
          },
          {
            id: 85,
            job_type_id: 19,
            name_ja: '文学',
            name_en: 'Literature',
          },
          {
            id: 86,
            job_type_id: 19,
            name_ja: 'その他',
            name_en: 'Others',
          },
        ],
      },
      {
        id: 20,
        name_ja: '言語学',
        name_en: 'Linguistics',
        type: 2,
        job_info: [
          {
            id: 87,
            job_type_id: 20,
            name_ja: '日本語',
            name_en: 'Japanese',
          },
          {
            id: 88,
            job_type_id: 20,
            name_ja: '英語',
            name_en: 'English',
          },
          {
            id: 89,
            job_type_id: 20,
            name_ja: 'その他',
            name_en: 'Others',
          },
        ],
      },
      {
        id: 21,
        name_ja: '自然科学',
        name_en: 'Natural sciences',
        type: 2,
        job_info: [
          {
            id: 90,
            job_type_id: 21,
            name_ja: '建築学',
            name_en: 'Architecture',
          },
          {
            id: 91,
            job_type_id: 21,
            name_ja: 'コンピュータサイエンス',
            name_en: 'Computer Science',
          },
          {
            id: 92,
            job_type_id: 21,
            name_ja: '工学',
            name_en: 'Engineering',
          },
          {
            id: 93,
            job_type_id: 21,
            name_ja: '統計学',
            name_en: 'Statistics',
          },
          {
            id: 94,
            job_type_id: 21,
            name_ja: 'その他',
            name_en: 'Others',
          },
        ],
      },
      {
        id: 22,
        name_ja: '社会科学',
        name_en: 'Social science',
        type: 2,
        job_info: [
          {
            id: 95,
            job_type_id: 22,
            name_ja: '経済学',
            name_en: 'Economics',
          },
          {
            id: 96,
            job_type_id: 22,
            name_ja: '教育学',
            name_en: 'Pedagogy',
          },
          {
            id: 97,
            job_type_id: 22,
            name_ja: '行政学',
            name_en: 'Administration',
          },
          {
            id: 98,
            job_type_id: 22,
            name_ja: '国際関係学',
            name_en: 'International Relations',
          },
          {
            id: 99,
            job_type_id: 22,
            name_ja: '法律学',
            name_en: 'Legal Studies',
          },
          {
            id: 100,
            job_type_id: 22,
            name_ja: '政治学',
            name_en: 'Political Science',
          },
          {
            id: 101,
            job_type_id: 22,
            name_ja: 'その他',
            name_en: 'Others',
          },
        ],
      },
      {
        id: 23,
        name_ja: 'その他',
        name_en: 'Others',
        type: 2,
        job_info: [],
      },
    ],

    job_info_list: [
      {
        id: 1,
        name_ja: '農業、林業、漁業',
        name_en: 'Agriculture, Forestry, Fishery',
        type: 1,
        job_info: [
          {
            id: 1,
            job_type_id: 1,
            name_ja: '耕種農業',
            name_en: 'Crop farming',
          },
          {
            id: 2,
            job_type_id: 1,
            name_ja: '畜産農業',
            name_en: 'Livestock farming',
          },
          {
            id: 3,
            job_type_id: 1,
            name_ja: '育林業',
            name_en: 'Forestry expansion',
          },
          {
            id: 4,
            job_type_id: 1,
            name_ja: '素材生産業',
            name_en: 'Material production industry',
          },
          {
            id: 5,
            job_type_id: 1,
            name_ja: 'その他林業',
            name_en: 'Other forestry business',
          },
          {
            id: 6,
            job_type_id: 1,
            name_ja: '海面漁業（海での漁師）',
            name_en: 'Sea fishery(fishermen at sea)',
          },
          {
            id: 7,
            job_type_id: 1,
            name_ja: '内水面漁業（淡水での漁師）',
            name_en: 'Inland fishery (Fishermen at freshwater)',
          },
          {
            id: 8,
            job_type_id: 1,
            name_ja: '海面養殖業（海での養殖）',
            name_en: 'Aquaculture (In the sea)',
          },
          {
            id: 9,
            job_type_id: 1,
            name_ja: '内水面養殖業（淡水での養殖）',
            name_en: 'Inland water culture (In fresh water)',
          },
          {
            id: 10,
            job_type_id: 1,
            name_ja: 'その他',
            name_en: 'Others',
          },
        ],
      },
      {
        id: 2,
        name_ja: '建設業',
        name_en: 'Construction industry',
        type: 1,
        job_info: [
          {
            id: 11,
            job_type_id: 2,
            name_ja: '土木工事業',
            name_en: 'Civil engineering business',
          },
          {
            id: 12,
            job_type_id: 2,
            name_ja: '舗装工事業',
            name_en: 'Pavement construction business',
          },
          {
            id: 13,
            job_type_id: 2,
            name_ja: '建築工事業',
            name_en: 'Construction work',
          },
          {
            id: 14,
            job_type_id: 2,
            name_ja: 'その他',
            name_en: 'Others',
          },
        ],
      },
      {
        id: 3,
        name_ja: '製造業',
        name_en: 'Manufacturing industry',
        type: 1,
        job_info: [
          {
            id: 15,
            job_type_id: 3,
            name_ja: '飲食料品製造業',
            name_en: 'Food and beverage manufacturing industry',
          },
          {
            id: 16,
            job_type_id: 3,
            name_ja: '繊維・縫製業',
            name_en: 'Textile・Sewing industry',
          },
          {
            id: 17,
            job_type_id: 3,
            name_ja: '木材・木製品製造業',
            name_en: 'Lumber・Wood products manufacturing industry',
          },
          {
            id: 18,
            job_type_id: 3,
            name_ja: '印刷関連業',
            name_en: 'Printing industry',
          },
          {
            id: 19,
            job_type_id: 3,
            name_ja: 'プラスチック製品製造業',
            name_en: 'Plastic product manufacturing industry',
          },
          {
            id: 20,
            job_type_id: 3,
            name_ja: '鉄鋼業',
            name_en: 'Steel industry',
          },
          {
            id: 21,
            job_type_id: 3,
            name_ja: '非鉄金属製造業',
            name_en: 'Non-ferrous metal manufacturing industry　',
          },
          {
            id: 22,
            job_type_id: 3,
            name_ja: '金属製品製造業',
            name_en: 'Fabricated metal product manufacturing industry',
          },
          {
            id: 23,
            job_type_id: 3,
            name_ja: '機械器具製造業 ',
            name_en: 'Equipment manufacturing industry',
          },
          {
            id: 24,
            job_type_id: 3,
            name_ja: '電気・電子製造業',
            name_en: 'Electric・Electronic manufacturing industry',
          },
          {
            id: 25,
            job_type_id: 3,
            name_ja: 'その他',
            name_en: 'Others',
          },
        ],
      },
      {
        id: 4,
        name_ja: '電気・ガス・水道業',
        name_en: 'Electricity・Gas・Water industry',
        type: 1,
        job_info: [
          {
            id: 26,
            job_type_id: 4,
            name_ja: '電気業',
            name_en: 'Electric industry',
          },
          {
            id: 27,
            job_type_id: 4,
            name_ja: 'ガス業',
            name_en: 'Gas industry',
          },
          {
            id: 28,
            job_type_id: 4,
            name_ja: '水道業',
            name_en: 'Waterworks',
          },
          {
            id: 29,
            job_type_id: 4,
            name_ja: 'その他',
            name_en: 'Others',
          },
        ],
      },
      {
        id: 5,
        name_ja: '情報通信業',
        name_en: 'Telecommunications industry',
        type: 1,
        job_info: [
          {
            id: 30,
            job_type_id: 5,
            name_ja: '通信業',
            name_en: 'Communications industry',
          },
          {
            id: 31,
            job_type_id: 5,
            name_ja: '放送業',
            name_en: 'Broadcasting industry',
          },
          {
            id: 32,
            job_type_id: 5,
            name_ja: '情報サービス業',
            name_en: 'Information service industry',
          },
          {
            id: 33,
            job_type_id: 5,
            name_ja: '映像・音声・文字情報制作業',
            name_en: 'Video・Audio・Text data production',
          },
          {
            id: 34,
            job_type_id: 5,
            name_ja: 'その他',
            name_en: 'Others',
          },
        ],
      },
      {
        id: 6,
        name_ja: '運輸業、郵便業',
        name_en: 'Transportation industry, Postal industry',
        type: 1,
        job_info: [
          {
            id: 35,
            job_type_id: 6,
            name_ja: '鉄道業',
            name_en: 'Railway industry',
          },
          {
            id: 36,
            job_type_id: 6,
            name_ja: '旅客運送業',
            name_en: 'Passenger transportation business',
          },
          {
            id: 37,
            job_type_id: 6,
            name_ja: '貨物運送業',
            name_en: 'Freight transportation business',
          },
          {
            id: 38,
            job_type_id: 6,
            name_ja: '倉庫業',
            name_en: 'Warehouse business',
          },
          {
            id: 39,
            job_type_id: 6,
            name_ja: '郵便業',
            name_en: 'Postal business',
          },
          {
            id: 40,
            job_type_id: 6,
            name_ja: 'その他',
            name_en: 'Others',
          },
        ],
      },
      {
        id: 7,
        name_ja: '卸売業、小売業',
        name_en: 'Wholesale business,Retail business',
        type: 1,
        job_info: [
          {
            id: 41,
            job_type_id: 7,
            name_ja: '飲食料品卸売業',
            name_en: 'Beverage wholesale business',
          },
          {
            id: 42,
            job_type_id: 7,
            name_ja: '繊維・衣服等卸売業',
            name_en: 'Fiber・Clothing wholesales business',
          },
          {
            id: 43,
            job_type_id: 7,
            name_ja: '材料卸売業（建築・鉱物・金属）',
            name_en: 'Material wholesale trade (Construction・minerals・metals)',
          },
          {
            id: 44,
            job_type_id: 7,
            name_ja: '機械器具卸売業',
            name_en: 'Machines instruments wholesales business',
          },
          {
            id: 45,
            job_type_id: 7,
            name_ja: 'その他',
            name_en: 'Others',
          },
        ],
      },
      {
        id: 8,
        name_ja: '金融業、保険業',
        name_en: 'Finance industry, Insurance industry',
        type: 1,
        job_info: [
          {
            id: 46,
            job_type_id: 8,
            name_ja: '銀行業',
            name_en: 'Banking',
          },
          {
            id: 47,
            job_type_id: 8,
            name_ja: '貸金・クレジットカード業',
            name_en: 'Debt・Credit card business',
          },
          {
            id: 48,
            job_type_id: 8,
            name_ja: '保険業',
            name_en: 'Insurance business',
          },
          {
            id: 49,
            job_type_id: 8,
            name_ja: 'その他',
            name_en: 'Others',
          },
        ],
      },
      {
        id: 9,
        name_ja: '不動産業',
        name_en: 'Real estate business',
        type: 1,
        job_info: [
          {
            id: 50,
            job_type_id: 9,
            name_ja: '不動産売買業',
            name_en: 'Real estate trading business',
          },
          {
            id: 51,
            job_type_id: 9,
            name_ja: '不動産賃貸業',
            name_en: 'Real estate rental business',
          },
          {
            id: 52,
            job_type_id: 9,
            name_ja: 'その他',
            name_en: 'Others',
          },
        ],
      },
      {
        id: 10,
        name_ja: '学術研究、専門・技術',
        name_en: 'Academic research, expertise and technology',
        type: 1,
        job_info: [
          {
            id: 53,
            job_type_id: 10,
            name_ja: '学術・開発研究機関',
            name_en: 'Academic・development research organization',
          },
          {
            id: 54,
            job_type_id: 10,
            name_ja: '広告業',
            name_en: 'Advertising industry',
          },
          {
            id: 55,
            job_type_id: 10,
            name_ja: '専門サービス業（司法、行政、デザイン）',
            name_en: 'Professional service industry (judicial, administrative, design)',
          },
          {
            id: 56,
            job_type_id: 10,
            name_ja: 'その他',
            name_en: 'Others',
          },
        ],
      },
      {
        id: 11,
        name_ja: '宿泊業、飲食サービス業',
        name_en: 'Accommodation business, food service business',
        type: 1,
        job_info: [
          {
            id: 57,
            job_type_id: 11,
            name_ja: '宿泊業',
            name_en: 'Accommodation business',
          },
          {
            id: 58,
            job_type_id: 11,
            name_ja: '飲食店業',
            name_en: 'Food  business',
          },
          {
            id: 59,
            job_type_id: 11,
            name_ja: 'その他',
            name_en: 'Others',
          },
        ],
      },
      {
        id: 12,
        name_ja: '生活関連サービス業',
        name_en: 'Life - related service industry',
        type: 1,
        job_info: [
          {
            id: 60,
            job_type_id: 12,
            name_ja: '美容師業',
            name_en: 'Beautician business',
          },
          {
            id: 61,
            job_type_id: 12,
            name_ja: 'カジノ業',
            name_en: 'Casino business',
          },
          {
            id: 62,
            job_type_id: 12,
            name_ja: '映画館・公園・遊園地等',
            name_en: 'Movie theaters・ Parks・ Amusement parks etc',
          },
          {
            id: 63,
            job_type_id: 12,
            name_ja: 'その他',
            name_en: 'Others',
          },
        ],
      },
      {
        id: 13,
        name_ja: '教育、学習支援業',
        name_en: 'Education, Learning support industry',
        type: 1,
        job_info: [
          {
            id: 64,
            job_type_id: 13,
            name_ja: '学校教育業',
            name_en: 'School education business',
          },
          {
            id: 65,
            job_type_id: 13,
            name_ja: '学校教育支援機関',
            name_en: 'School education support organization ',
          },
          {
            id: 66,
            job_type_id: 13,
            name_ja: '学習塾',
            name_en: 'Tutoring school',
          },
          {
            id: 67,
            job_type_id: 13,
            name_ja: '職業・教育支援施設',
            name_en: 'Occupation・Educational support facilities',
          },
          {
            id: 68,
            job_type_id: 13,
            name_ja: 'その他',
            name_en: 'Others',
          },
        ],
      },
      {
        id: 14,
        name_ja: '医療',
        name_en: 'Medical treatment',
        type: 1,
        job_info: [
          {
            id: 69,
            job_type_id: 14,
            name_ja: '病院',
            name_en: 'Hospitals',
          },
          {
            id: 70,
            job_type_id: 14,
            name_ja: 'その他',
            name_en: 'Others',
          },
        ],
      },
      {
        id: 15,
        name_ja: '公務',
        name_en: 'Public service',
        type: 1,
        job_info: [
          {
            id: 71,
            job_type_id: 15,
            name_ja: '公務員',
            name_en: 'Public employee',
          },
          {
            id: 72,
            job_type_id: 15,
            name_ja: 'その他',
            name_en: 'Others',
          },
        ],
      },
      {
        id: 16,
        name_ja: 'サービス業',
        name_en: 'Service industry',
        type: 1,
        job_info: [
          {
            id: 73,
            job_type_id: 16,
            name_ja: 'クリーニング',
            name_en: 'Cleaning',
          },
          {
            id: 74,
            job_type_id: 16,
            name_ja: '自動車整備',
            name_en: 'Car maintenance',
          },
          {
            id: 75,
            job_type_id: 16,
            name_ja: '人材事業 ',
            name_en: 'Human resource',
          },
          {
            id: 76,
            job_type_id: 16,
            name_ja: '協同組合',
            name_en: 'Cooperative',
          },
          {
            id: 77,
            job_type_id: 16,
            name_ja: 'その他',
            name_en: 'Others',
          },
        ],
      },
      {
        id: 17,
        name_ja: 'その他事業',
        name_en: 'Other business',
        type: 1,
        job_info: [
          {
            id: 78,
            job_type_id: 17,
            name_ja: 'その他',
            name_en: 'Others',
          },
        ],
      },
    ],
  };

  test('Test 4: Test render tab EducationWorkHistory (component)', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(tab2EducationWorkHistory, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    const render_component = wrapper.find('.hr-education-work-history');
    expect(render_component.exists()).toBe(true);

    wrapper.destroy();
  });

  test('Test 5: test render data EducationWorkHistory', async() => {
    const DATA = {
      detailForm: {
        final_education_date: '',
        final_education_classification: '',
        final_education_degree: '',
        major_classification: '',
        middle_classification: '',

        main_job_1: {
          is_null: true,
          main_job_career_date_from: '',
          main_job_career_date_to: '',
          department: '',
          job_id: '',
          detail: '',
        },

        main_job_2: {
          is_null: true,
          main_job_career_date_from: '',
          main_job_career_date_to: '',
          department: '',
          job_id: '',
          detail: '',
        },

        main_job_3: {
          is_null: true,
          main_job_career_date_from: '',
          main_job_career_date_to: '',
          department: '',
          job_id: '',
          detail: '',
        },
      },
      reRender: 0,

      final_education_classification_options:
        final_education_classification_options,
      final_education_degree_options: final_education_degree_options,

      major_middle_info: [],
      job_info_list: [],
    };

    const localVue = createLocalVue();
    const wrapper = shallowMount(tab2EducationWorkHistory, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
      mocks: { $t: () => {} },
    });

    expect(wrapper.vm.detailForm).toEqual(DATA.detailForm);

    expect(wrapper.vm.reRender).toEqual(DATA.reRender);
    expect(wrapper.vm.final_education_classification_options).toEqual(DATA.final_education_classification_options);
    expect(wrapper.vm.final_education_degree_options).toEqual(DATA.final_education_degree_options);
    expect(wrapper.vm.major_middle_info).toEqual(DATA.major_middle_info);
    expect(wrapper.vm.job_info_list).toEqual(DATA.job_info_list);

    wrapper.destroy();
  });

  test('Test 6: test render content data EducationWorkHistory', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(tab2EducationWorkHistory, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    wrapper.setData({ detailForm: DATA_EDU_WORK.EDU_WORK, major_middle_info: DATA_EDU_WORK.major_middle_info, job_info_list: DATA_EDU_WORK.job_info_list });
    // expect(wrapper).toMatchSnapshot();

    const label_final_education = wrapper.find('.label-final-edication');
    expect(label_final_education.exists()).toBe(true);
    expect(label_final_education.text()).toBe('HR_LIST_FORM.FINAL_EDUCATION');
    const label_graducation_date = wrapper.find('.label-graducation-date');
    expect(label_graducation_date.exists()).toBe(true);
    expect(label_graducation_date.text()).toBe('HR_LIST_FORM.GRADUATION_DATE');
    const content_final_education_date = wrapper.find('.content-final-education-date');
    expect(content_final_education_date.exists()).toBe(true);
    expect(content_final_education_date.text()).toEqual('');

    const label_classification = wrapper.find('.label-classification');
    expect(label_classification.exists()).toBe(true);
    expect(label_classification.text()).toBe('HR_LIST_FORM.CLASSIFICATION');
    const content_classification = wrapper.find('.content-classification');
    expect(content_classification.exists()).toBe(true);
    expect(content_classification.text()).toEqual('');

    const label_degree = wrapper.find('.label-degree');
    expect(label_degree.exists()).toBe(true);
    expect(label_degree.text()).toBe('HR_LIST_FORM.DEGREE');
    const content_degree = wrapper.find('.content-degree');
    expect(content_degree.exists()).toBe(true);
    expect(content_degree.text()).toEqual('');

    const label_main_category = wrapper.find('.label-main-category');
    expect(label_main_category.exists()).toBe(true);
    expect(label_main_category.text()).toBe('HR_LIST_FORM.MAIN_CATEGORY');
    const content_main_category = wrapper.find('.content-main-category');
    expect(content_main_category.exists()).toBe(true);
    expect(content_main_category.text()).toEqual('');

    const label_department = wrapper.find('.label-department');
    expect(label_department.exists()).toBe(true);
    expect(label_department.text()).toBe('HR_LIST_FORM.DEPARTMENT');
    const content_department = wrapper.find('.content-department');
    expect(content_department.exists()).toBe(true);
    expect(content_department.text()).toEqual('');

    const label_main_job_career = wrapper.find('.label-main-job-career');
    expect(label_main_job_career.exists()).toBe(true);
    expect(label_main_job_career.text()).toBe('HR_LIST_FORM.MAIN_JOB_CAREER_1');
    const label_job_career_date = wrapper.find('.label-job-career-date');
    expect(label_job_career_date.exists()).toBe(true);
    expect(label_job_career_date.text()).toBe('HR_LIST_FORM.DATE');
    const content_content_job_career_from = wrapper.find('.content-job-career-from');
    expect(content_content_job_career_from.exists()).toBe(false);
    const content_content_job_career_to = wrapper.find('.content-job-career-to');
    expect(content_content_job_career_to.exists()).toBe(false);
    // expect(content_preferred_working_hours.text()).toEqual('');

    const label_main_job_department = wrapper.find('.label-main-job-department');
    expect(label_main_job_department.exists()).toBe(true);
    expect(label_main_job_department.text()).toBe('HR_LIST_FORM.DEPARTMENT');
    const content_main_job_department = wrapper.find('.content-main-job-department');
    expect(content_main_job_department.exists()).toBe(true);
    expect(content_main_job_department.text()).toEqual('');

    const label_main_job_title = wrapper.find('.label-main-job-title');
    expect(label_main_job_title.exists()).toBe(true);
    expect(label_main_job_title.text()).toBe('HR_LIST_FORM.JOB_TITLE');
    const content_main_job_title = wrapper.find('.content-main-job-title');
    expect(content_main_job_title.exists()).toBe(true);
    expect(content_main_job_title.text()).toEqual('');

    const label_main_job_detail = wrapper.find('.label-main-job-detail');
    expect(label_main_job_detail.exists()).toBe(true);
    expect(label_main_job_detail.text()).toBe('HR_LIST_FORM.DETAIL');
    const content_main_job_detail = wrapper.find('.content-main-job-detail');
    expect(content_main_job_detail.exists()).toBe(true);
    expect(content_main_job_detail.text()).toEqual('');

    wrapper.destroy();
  });

  test('Test 7: Test render tab PersonalPrRemarks (component)', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(tab3PersonalPrRemarks, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    const render_component = wrapper.find('.hr-persional-remarks');
    expect(render_component.exists()).toBe(true);

    wrapper.destroy();
  });

  test('Test 8: test render data PersonalPrRemarks', async() => {
    const DATA_PersonalPrRemarks = { formDetail: {}};

    const localVue = createLocalVue();
    const wrapper = shallowMount(tab3PersonalPrRemarks, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
      mocks: { $t: () => {} },
    });

    expect(wrapper.vm.formDetail).toEqual(DATA_PersonalPrRemarks.formDetail);

    wrapper.destroy();
  });

  test('Test 9: test render content data PersonalPrRemarks', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(tab3PersonalPrRemarks, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    const PROPS_DATA = {
      personalPrSpecialNotes: 'personalPrSpecialNotes test',
      remarks: 'remarks test',
    };

    await wrapper.setProps({ personalPrSpecialNotes: PROPS_DATA.personalPrSpecialNotes, remarks: PROPS_DATA.remarks });
    // expect(wrapper).toMatchSnapshot();

    const label_persional_pr_special_note = wrapper.find('.label-persional-pr-special-note');
    expect(label_persional_pr_special_note.exists()).toBe(true);
    expect(label_persional_pr_special_note.text()).toBe('HR_LIST_FORM.PERSONAL_PR_SPECIAL_NOTES');
    const content_persional_pr_special_note = wrapper.find('.contenr-persional-pr-special-note');
    expect(content_persional_pr_special_note.exists()).toBe(true);
    expect(content_persional_pr_special_note.text()).toEqual('personalPrSpecialNotes test');

    const label_remaks = wrapper.find('.label-remaks');
    expect(label_remaks.exists()).toBe(true);
    expect(label_remaks.text()).toBe('HR_LIST_FORM.REMARKS');
    const content_remaks = wrapper.find('.content-remaks');
    expect(content_remaks.exists()).toBe(true);
    expect(content_remaks.text()).toEqual('remarks test');

    wrapper.destroy();
  });

  test('Test 10: Test render tab Contact (component)', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(tab4Contact, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    const render_component = wrapper.find('.hr-detail-contact');
    expect(render_component.exists()).toBe(true);

    wrapper.destroy();
  });

  test('Test 11: test render data Contact', async() => {
    const DATA_Contact = {
      formData: {
        telephone_number: '',
        mobile_phone_number: '',
        mail_address: '',

        address_city: '',
        address_district: '',
        address_number: '',
        address_other: '',

        home_town_district: '',
        home_town_number: '',
        home_town_other: '',
        hometown_city: '',
      },
    };

    const localVue = createLocalVue();
    const wrapper = shallowMount(tab4Contact, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
      mocks: { $t: () => {} },
    });

    expect(wrapper.vm.formData).toEqual(DATA_Contact.formData);

    wrapper.destroy();
  });

  test('Test 12: test render content data Contact', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(tab4Contact, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    await wrapper.setProps({ detailData: DATA });
    // expect(wrapper).toMatchSnapshot();

    const label_telephone_number = wrapper.find('.label-telephone-number');
    expect(label_telephone_number.exists()).toBe(true);
    expect(label_telephone_number.text()).toBe('HR_LIST_FORM.TELEPHONE_NUMBER');
    const content_telephone_number = wrapper.find('.content-telephone-number');
    expect(content_telephone_number.exists()).toBe(true);
    expect(content_telephone_number.text()).toEqual('+84 0987654321');

    const label_mobile_phone_number = wrapper.find('.label-mobile-phone-number');
    expect(label_mobile_phone_number.exists()).toBe(true);
    expect(label_mobile_phone_number.text()).toBe('HR_LIST_FORM.MOBILE_PHONE_NUMBER');
    const content_mobile_phone_number = wrapper.find('.content-mobile-phone-number');
    expect(content_mobile_phone_number.exists()).toBe(true);
    expect(content_mobile_phone_number.text()).toEqual('+81 0123456789');

    const label_mail_address = wrapper.find('.label-mail-address');
    expect(label_mail_address.exists()).toBe(true);
    expect(label_mail_address.text()).toBe('HR_LIST_FORM.MAIL_ADDRESS');
    const content_mail_address = wrapper.find('.content-mail-address');
    expect(content_mail_address.exists()).toBe(true);
    expect(content_mail_address.text()).toEqual('vuhoa@gmail.com');

    const label_address = wrapper.find('.label-address');
    expect(label_address.exists()).toBe(true);
    expect(label_address.text()).toBe('HR_LIST_FORM.ADDRESS');
    const label_address_city = wrapper.find('.label-address-city');
    expect(label_address_city.exists()).toBe(true);
    expect(label_address_city.text()).toBe('HR_LIST_FORM.CITY');
    const content_address_city = wrapper.find('.content-address-city');
    expect(content_address_city.exists()).toBe(true);
    expect(content_address_city.text()).toEqual('Ha Noi city');

    const label_district = wrapper.find('.label-district');
    expect(label_district.exists()).toBe(true);
    expect(label_district.text()).toBe('HR_LIST_FORM.DISTRICT');
    const content_district = wrapper.find('.content-district');
    expect(content_district.exists()).toBe(true);
    expect(content_district.text()).toEqual('266 Doi Can address district');

    const label_address_number = wrapper.find('.label-address-number');
    expect(label_address_number.exists()).toBe(true);
    expect(label_address_number.text()).toBe('HR_LIST_FORM.NUMBER');
    const content_address_number = wrapper.find('.content-address-number');
    expect(content_address_number.exists()).toBe(true);
    expect(content_address_number.text()).toEqual('266 address number');

    const label_address_other = wrapper.find('.label-address-other');
    expect(label_address_other.exists()).toBe(true);
    expect(label_address_other.text()).toBe('HR_LIST_FORM.ORTHER');
    const content_address_other = wrapper.find('.content-address-other');
    expect(content_address_other.exists()).toBe(true);
    expect(content_address_other.text()).toEqual('other address');

    const label_hometown_address = wrapper.find('.label-hometown-address');
    expect(label_hometown_address.exists()).toBe(true);
    expect(label_hometown_address.text()).toBe('HR_LIST_FORM.HOMETOWN_ADDRESS');
    const label_hometown_city = wrapper.find('.label-hometown-city');
    expect(label_hometown_city.exists()).toBe(true);
    expect(label_hometown_city.text()).toBe('HR_LIST_FORM.CITY');
    const content_hometown_city = wrapper.find('.content-hometown-city');
    expect(content_hometown_city.exists()).toBe(true);
    expect(content_hometown_city.text()).toEqual('Ha Tay hometown');

    const label_hometown_district = wrapper.find('.label-hometown-district');
    expect(label_hometown_district.exists()).toBe(true);
    expect(label_hometown_district.text()).toBe('HR_LIST_FORM.DISTRICT');
    const content_hometown_district = wrapper.find('.content-hometown-district');
    expect(content_hometown_district.exists()).toBe(true);
    expect(content_hometown_district.text()).toEqual('Ung Hoa homtown district');

    const label_hometown_number = wrapper.find('.label-hometown-number');
    expect(label_hometown_number.exists()).toBe(true);
    expect(label_hometown_number.text()).toBe('HR_LIST_FORM.NUMBER');
    const content_hometown_number = wrapper.find('.content-hometown-number');
    expect(content_hometown_number.exists()).toBe(true);
    expect(content_hometown_number.text()).toEqual('144 homtown number');

    const label_hometown_other = wrapper.find('.label-hometown-nother');
    expect(label_hometown_other.exists()).toBe(true);
    expect(label_hometown_other.text()).toBe('HR_LIST_FORM.ORTHER');
    const content_hometown_other = wrapper.find('.content-hometown-nother');
    expect(content_hometown_other.exists()).toBe(true);
    expect(content_hometown_other.text()).toEqual('other homtown');

    wrapper.destroy();
  });
});
