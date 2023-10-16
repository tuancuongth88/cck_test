import store from '@/store';
import router from '@/router';
import HrSearch from '@/pages/HrSearch/search.vue';
import { mount, createLocalVue, shallowMount } from '@vue/test-utils';

describe('TEST SCREEN HR Edit', () => {
  test('Test 1: Test render component Hr edit', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(HrSearch, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    const render_component = wrapper.find('.display-user-management-list');
    expect(render_component.exists()).toBe(true);

    const title = wrapper.find('.title');
    expect(title.exists()).toBe(true);
    expect(title.text()).toEqual('人材検索');

    wrapper.destroy();
  });

  test('Test 2: test render data', async() => {
    const HR_EDIT_DATA = {
      organization_name: null,
      organizationList: [
        { value: null, text: '選択してください', disabled: true },
      ],

      male: '',
      female: '',

      age_from: null,
      age_to: null,

      final_education_date_from_year: null,
      final_education_date_from_month: null,

      final_education_date_to_year: null,
      final_education_date_to_month: null,

      yearList: [{ value: null, text: '選択してください', disabled: true }],

      monthList: [{ value: null, text: '選択してください', disabled: true }],

      graduation: '',
      finish: '',
      dropout: '',

      bachelor_master: '',
      human_resource: '',

      selected_courses: [],

      work_from_1: '',
      work_from_2: '',
      work_from_3: '',
      work_from_4: '',

      part_time: null,

      japanese_level_1: '',
      japanese_level_2: '',
      japanese_level_3: '',
      japanese_level_4: '',
      japanese_level_5: '',
      japanese_level_6: '',

      selected_job_experiences: [],

      key_word: '',

      modalCourseOptions: [
        {
          id: 1,
          name_ja: '経営学等',
          name_en: 'Business administration',
          childOptions: [
            {
              id: 1,
              name_ja: '会計学',
              name_en: 'Accounting',
            },
            {
              id: 2,
              name_ja: '経営学',
              name_en: 'Business Administration',
            },
            {
              id: 3,
              name_ja: 'マーケティング',
              name_en: 'Marketing',
            },
            {
              id: 4,
              name_ja: 'その他',
              name_en: 'Others',
            },
          ],
        },
        {
          id: 2,
          name_ja: '人文化学',
          name_en: 'Humanities',
          childOptions: [
            {
              id: 1,
              name_ja: '芸術',
              name_en: 'Art',
            },
            {
              id: 2,
              name_ja: '歴史学',
              name_en: 'History',
            },
            {
              id: 3,
              name_ja: '文学',
              name_en: 'Literature',
            },
            {
              id: 4,
              name_ja: 'その他',
              name_en: 'Others',
            },
          ],
        },
        {
          id: 3,
          name_ja: '言語学',
          name_en: 'Linguistics',
          childOptions: [
            {
              id: 1,
              name_ja: '日本語',
              name_en: 'Japanese',
            },
            {
              id: 2,
              name_ja: '英語',
              name_en: 'English',
            },
            {
              id: 3,
              name_ja: 'その他',
              name_en: 'Others',
            },
          ],
        },
        {
          id: 4,
          name_ja: '自然科学',
          name_en: 'Natural sciences',
          childOptions: [
            {
              id: 1,
              name_ja: '建築学',
              name_en: 'Architecture',
            },
            {
              id: 2,
              name_ja: 'コンピュータサイエンス',
              name_en: 'Computer Science',
            },
            {
              id: 3,
              name_ja: '工学',
              name_en: 'Engineering',
            },
            {
              id: 4,
              name_ja: '統計学',
              name_en: 'Statistics',
            },
            {
              id: 5,
              name_ja: 'その他',
              name_en: 'Others',
            },
          ],
        },
        {
          id: 5,
          name_ja: '社会科学 ',
          name_en: 'Social science',
          childOptions: [
            {
              id: 1,
              name_ja: '経済学',
              name_en: 'Economics',
            },
            {
              id: 2,
              name_ja: '教育学',
              name_en: ' Pedagogy',
            },
            {
              id: 3,
              name_ja: '行政学 ',
              name_en: 'Administration',
            },
            {
              id: 4,
              name_ja: '国際関係学',
              name_en: ' International Relations',
            },
            {
              id: 5,
              name_ja: '法律学 ',
              name_en: 'Legal Studies',
            },
            {
              id: 6,
              name_ja: '政治学',
              name_en: ' Political Science',
            },
            {
              id: 7,
              name_ja: 'その他',
              name_en: 'Others',
            },
          ],
        },
      ],

      modalJobExpOptions: [
        {
          id: 1,
          name_ja: '農業・林業・漁業',
          name_en: 'Agriculture/Forestry/Fisheries',
          childOptions: [
            {
              id: 1,
              name_ja: '住宅手当有',
              name_en: ' Housing allowance',
            },
            {
              id: 2,
              name_ja: '福利厚生充実 2',
              name_en: 'Welfare enhancement',
            },
            {
              id: 3,
              name_ja: '福利厚生充実 3',
              name_en: 'Welfare enhancement',
            },
            {
              id: 4,
              name_ja: '福利厚生充実 4',
              name_en: 'Welfare enhancement',
            },
          ],
        },
        {
          id: 2,
          name_ja: '建設業',
          name_en: 'Construction industry',
          childOptions: [
            {
              id: 1,
              name_ja: '建設業 1',
              name_en: ' Housing allowance',
            },
            {
              id: 2,
              name_ja: '建設業 2',
              name_en: 'Welfare enhancement',
            },
            {
              id: 3,
              name_ja: '建設業 3',
              name_en: 'Welfare enhancement',
            },
            {
              id: 4,
              name_ja: '建設業 4',
              name_en: 'Welfare enhancement',
            },
            {
              id: 5,
              name_ja: '建設業 5',
              name_en: 'Welfare enhancement',
            },
          ],
        },
        {
          id: 3,
          name_ja: '製造業',
          name_en: 'manufacturing industry',
          childOptions: [
            {
              id: 1,
              name_ja: '製造業 1',
              name_en: ' manufacturing industry',
            },
            {
              id: 2,
              name_ja: '製造業 2',
              name_en: ' manufacturing industry',
            },
            {
              id: 3,
              name_ja: '製造業 3',
              name_en: ' manufacturing industry',
            },
            {
              id: 4,
              name_ja: '製造業 4',
              name_en: ' manufacturing industry',
            },
          ],
        },
        {
          id: 4,
          name_ja: '電気・ガス・水道業',
          name_en: 'Electricity, gas, water industry',
          childOptions: [
            {
              id: 1,
              name_ja: '住宅手当有 1',
              name_en: ' Housing allowance',
            },
            {
              id: 2,
              name_ja: '住宅手当有 2',
              name_en: ' Housing allowance',
            },
            {
              id: 3,
              name_ja: '住宅手当有 3',
              name_en: ' Housing allowance',
            },
            {
              id: 4,
              name_ja: '住宅手当有 4',
              name_en: ' Housing allowance',
            },
          ],
        },
        {
          id: 5,
          name_ja: '情報通信業',
          name_en: 'Information and communication industry',
          childOptions: [
            {
              id: 1,
              name_ja: '情報通信業 1',
              name_en: ' Housing allowance',
            },
            {
              id: 2,
              name_ja: '情報通信業 2',
              name_en: ' Housing allowance',
            },
            {
              id: 3,
              name_ja: '情報通信業 3',
              name_en: ' Housing allowance',
            },
            {
              id: 4,
              name_ja: '情報通信業 4',
              name_en: ' Housing allowance',
            },
          ],
        },
        {
          id: 6,
          name_ja: '運輸業・郵便業',
          name_en: 'Transportation and postal services',
          childOptions: [
            {
              id: 1,
              name_ja: '運輸業・郵便業 1',
              name_en: ' Housing allowance',
            },
            {
              id: 2,
              name_ja: '運輸業・郵便業 2',
              name_en: 'Welfare enhancement',
            },
          ],
        },
        {
          id: 7,
          name_ja: '卸売業・小売業',
          name_en: 'Wholesale/Retail',
          childOptions: [
            {
              key: 1,
              name_ja: '卸売業・小売業 1',
              name_en: ' Housing allowance',
            },
            {
              key: 2,
              name_ja: '卸売業・小売業 2',
              name_en: 'Welfare enhancement',
            },
          ],
        },
      ],

      isShowModalSepecifyCourse: false,

      isShowModalSepecifyJobExp: false,
    };

    const localVue = createLocalVue();
    const wrapper = shallowMount(HrSearch, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
      mocks: { $t: () => { } },
    });

    expect(wrapper.vm.organization_name).toEqual(HR_EDIT_DATA.organization_name);

    expect(wrapper.vm.organizationList).toEqual(HR_EDIT_DATA.organizationList);

    expect(wrapper.vm.male).toEqual(HR_EDIT_DATA.male);
    expect(wrapper.vm.female).toEqual(HR_EDIT_DATA.female);

    expect(wrapper.vm.age_from).toEqual(HR_EDIT_DATA.age_from);
    expect(wrapper.vm.age_to).toEqual(HR_EDIT_DATA.age_to);
    expect(wrapper.vm.final_education_date_from_year).toEqual(HR_EDIT_DATA.final_education_date_from_year);
    expect(wrapper.vm.final_education_date_from_month).toEqual(HR_EDIT_DATA.final_education_date_from_month);
    expect(wrapper.vm.final_education_date_to_year).toEqual(HR_EDIT_DATA.final_education_date_to_year);
    expect(wrapper.vm.final_education_date_to_month).toEqual(HR_EDIT_DATA.final_education_date_to_month);

    expect(wrapper.vm.yearList).toEqual(HR_EDIT_DATA.yearList);
    expect(wrapper.vm.monthList).toEqual(HR_EDIT_DATA.monthList);

    expect(wrapper.vm.graduation).toEqual(HR_EDIT_DATA.graduation);
    expect(wrapper.vm.finish).toEqual(HR_EDIT_DATA.finish);
    expect(wrapper.vm.dropout).toEqual(HR_EDIT_DATA.dropout);
    expect(wrapper.vm.bachelor_master).toEqual(HR_EDIT_DATA.bachelor_master);
    expect(wrapper.vm.human_resource).toEqual(HR_EDIT_DATA.human_resource);
    expect(wrapper.vm.selected_courses).toEqual(HR_EDIT_DATA.selected_courses);

    expect(wrapper.vm.work_from_1).toEqual(HR_EDIT_DATA.work_from_1);
    expect(wrapper.vm.work_from_2).toEqual(HR_EDIT_DATA.work_from_2);
    expect(wrapper.vm.work_from_3).toEqual(HR_EDIT_DATA.work_from_3);
    expect(wrapper.vm.work_from_4).toEqual(HR_EDIT_DATA.work_from_4);

    expect(wrapper.vm.part_time).toEqual(HR_EDIT_DATA.part_time);

    expect(wrapper.vm.japanese_level_1).toEqual(HR_EDIT_DATA.japanese_level_1);
    expect(wrapper.vm.japanese_level_2).toEqual(HR_EDIT_DATA.japanese_level_2);
    expect(wrapper.vm.japanese_level_3).toEqual(HR_EDIT_DATA.japanese_level_3);
    expect(wrapper.vm.japanese_level_4).toEqual(HR_EDIT_DATA.japanese_level_4);
    expect(wrapper.vm.japanese_level_5).toEqual(HR_EDIT_DATA.japanese_level_5);
    expect(wrapper.vm.japanese_level_6).toEqual(HR_EDIT_DATA.japanese_level_6);

    expect(wrapper.vm.selected_job_experiences).toEqual(HR_EDIT_DATA.selected_job_experiences);
    expect(wrapper.vm.key_word).toEqual(HR_EDIT_DATA.key_word);
    expect(wrapper.vm.modalCourseOptions).toEqual(HR_EDIT_DATA.modalCourseOptions);
    expect(wrapper.vm.modalJobExpOptions).toEqual(HR_EDIT_DATA.modalJobExpOptions);

    expect(wrapper.vm.isShowModalSepecifyCourse).toEqual(HR_EDIT_DATA.isShowModalSepecifyCourse);
    expect(wrapper.vm.isShowModalSepecifyJobExp).toEqual(HR_EDIT_DATA.isShowModalSepecifyJobExp);

    wrapper.destroy();
  });

  test('Test 3: test render field search', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(HrSearch, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    // hr org search
    const hr_org_search = wrapper.find('.hr-org-search');
    expect(hr_org_search.exists()).toBe(true);

    const hr_org_search_label_text = hr_org_search.find('span');
    expect(hr_org_search_label_text.exists()).toBe(true);
    expect(hr_org_search_label_text.text()).toEqual('団体名');

    // gender search
    const gender_search = wrapper.find('.gender-search');
    expect(gender_search.exists()).toBe(true);

    const gender_search_label = gender_search.find('.left-col');
    expect(gender_search_label.exists()).toBe(true);
    const gender_search_label_text = gender_search_label.find('span');
    expect(gender_search_label_text.exists()).toBe(true);
    expect(gender_search_label_text.text()).toEqual('性別');

    // age search
    const age_search = wrapper.find('.age-search');
    expect(age_search.exists()).toBe(true);

    const age_search_label = age_search.find('.left-col');
    expect(age_search_label.exists()).toBe(true);
    const age_search_label_text = age_search_label.find('span');
    expect(age_search_label_text.exists()).toBe(true);
    expect(age_search_label_text.text()).toEqual('年齢');

    // final-education-search
    const final_education_search = wrapper.find('.final-education-search');
    expect(final_education_search.exists()).toBe(true);

    const final_education_search_label = final_education_search.find('.left-col');
    expect(final_education_search_label.exists()).toBe(true);
    const final_education_search_label_text = final_education_search_label.find('span');
    expect(final_education_search_label_text.exists()).toBe(true);
    expect(final_education_search_label_text.text()).toEqual('最終学歴（年月）');

    // final-education-department-search
    const final_education_department_search = wrapper.find('.final-education-department-search');
    expect(final_education_department_search.exists()).toBe(true);

    const final_education_department_search_label = final_education_department_search.find('.left-col');
    expect(final_education_department_search_label.exists()).toBe(true);
    const final_education_department_search_label_text = final_education_department_search_label.find('span');
    expect(final_education_department_search_label_text.exists()).toBe(true);
    expect(final_education_department_search_label_text.text()).toEqual('最終学歴（区分）');

    // degree-search
    const degree_search = wrapper.find('.degree-search');
    expect(degree_search.exists()).toBe(true);

    const degree_search_label = degree_search.find('.left-col');
    expect(degree_search_label.exists()).toBe(true);
    const degree_search_label_text = degree_search_label.find('span');
    expect(degree_search_label_text.exists()).toBe(true);
    expect(degree_search_label_text.text()).toEqual('最終学歴（学位）');

    // final-education-select-department-seaarch
    const final_edu_department_select = wrapper.find('.final-education-select-department-seaarch');
    expect(final_edu_department_select.exists()).toBe(true);

    const final_edu_department_select_label = final_edu_department_select.find('.left-col');
    expect(final_edu_department_select_label.exists()).toBe(true);
    const final_edu_department_select_label_text = final_edu_department_select_label.find('span');
    expect(final_edu_department_select_label_text.exists()).toBe(true);
    expect(final_edu_department_select_label_text.text()).toEqual('最終学歴（選考学科）');

    // work-style-search
    const work_style_search = wrapper.find('.work-style-search');
    expect(work_style_search.exists()).toBe(true);

    const work_style_search_label = work_style_search.find('.left-col');
    expect(work_style_search_label.exists()).toBe(true);
    const work_style_search_label_text = work_style_search_label.find('span');
    expect(work_style_search_label_text.exists()).toBe(true);
    expect(work_style_search_label_text.text()).toEqual('勤務形態');

    // part-time-search
    const part_time_search = wrapper.find('.part-time-search');
    expect(part_time_search.exists()).toBe(true);

    const part_time_search_label = part_time_search.find('.left-col');
    expect(part_time_search_label.exists()).toBe(true);
    const part_time_search_label_text = part_time_search_label.find('span');
    expect(part_time_search_label_text.exists()).toBe(true);
    expect(part_time_search_label_text.text()).toEqual('勤務形態（非常勤）');

    // japan-level-search
    const japan_level_search = wrapper.find('.japan-level-search');
    expect(japan_level_search.exists()).toBe(true);

    const japan_level_search_label = japan_level_search.find('.left-col');
    expect(japan_level_search_label.exists()).toBe(true);
    const japan_level_search_label_text = japan_level_search_label.find('span');
    expect(japan_level_search_label_text.exists()).toBe(true);
    expect(japan_level_search_label_text.text()).toEqual('日本語レベル');

    // job-experience-search
    const job_experience_search = wrapper.find('.job-experience-search');
    expect(job_experience_search.exists()).toBe(true);

    const job_experience_search_label = job_experience_search.find('.left-col');
    expect(job_experience_search_label.exists()).toBe(true);
    const job_experience_search_label_text = job_experience_search_label.find('span');
    expect(job_experience_search_label_text.exists()).toBe(true);
    expect(job_experience_search_label_text.text()).toEqual('主な職務経歴');

    // free-text-search
    const free_text_search = wrapper.find('.free-text-search');
    expect(free_text_search.exists()).toBe(true);

    const free_text_search_label = free_text_search.find('.left-col');
    expect(free_text_search_label.exists()).toBe(true);
    const free_text_search_label_text = free_text_search_label.find('span');
    expect(free_text_search_label_text.exists()).toBe(true);
    expect(free_text_search_label_text.text()).toEqual('キーワード');

    wrapper.destroy();
  });

  test('Test 4: test render check box search', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(HrSearch, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    const check_box = wrapper.findAll('.checkbox-label');
    expect(check_box.exists()).toBe(true);
    expect(check_box.length).toBe(18);

    const TEXT_CHECK_BOX_LABEL = [
      '男性',
      '女性',
      '卒業',
      '中退',
      '卒業見込み',
      '大学卒業以上',
      '大学卒業以外',
      '正社員',
      '契約社員',
      '派遣社員',
      'その他',
      '週28時間以下',
      'N1',
      'N2',
      'N3',
      'N4',
      'N5',
      '資格なし',
    ];

    let idx = 0;
    const len = check_box.length;

    while (idx < len) {
      expect(check_box.at(idx).text()).toEqual(TEXT_CHECK_BOX_LABEL[idx]);

      idx++;
    }

    wrapper.destroy();
  });

  test('Test 5: test function open modal ModalMultipleSelectCourse and  ModalMultipleSelectJobExp', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(HrSearch, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    const btn = wrapper.find('.course-specify');
    expect(btn.exists()).toBe(true);

    const handleOpenModalSpecifyCourse = jest.spyOn(wrapper.vm, 'handleOpenModalSpecifyCourse');

    if (expect(btn.exists()).toBe(true)) {
      await btn.trigger('click');
      expect(handleOpenModalSpecifyCourse).toHaveBeenCalled();
    }

    const btn_job_experience_specify = wrapper.find('.course-specify');
    expect(btn_job_experience_specify.exists()).toBe(true);

    const handleOpenModalJobExperience = jest.spyOn(wrapper.vm, 'handleOpenModalJobExperience');

    if (expect(btn_job_experience_specify.exists()).toBe(true)) {
      await btn_job_experience_specify.trigger('click');
      expect(handleOpenModalJobExperience).toHaveBeenCalled();
    }

    wrapper.destroy();
  });

  test('Test 6: test function clear all search', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(HrSearch, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    const btn = wrapper.find('.clear-setting-button');
    expect(btn.exists()).toBe(true);
    expect(btn.text()).toEqual('設定をクリア');

    const handleClearAllSetting = jest.spyOn(wrapper.vm, 'handleClearAllSetting');

    if (expect(btn.exists()).toBe(true)) {
      await btn.trigger('click');
      expect(handleClearAllSetting).toHaveBeenCalled();
    }

    wrapper.destroy();
  });

  test('Test 7: test function confirm search', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(HrSearch, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    const btn = wrapper.find('.search-button');
    expect(btn.exists()).toBe(true);
    const btn_text = btn.find('span');
    expect(btn_text.exists()).toBe(true);
    expect(btn_text.text()).toEqual('この条件で検索する');

    const handleProcessSearchEngine = jest.spyOn(wrapper.vm, 'handleProcessSearchEngine');

    if (expect(btn.exists()).toBe(true)) {
      await btn.trigger('click');
      expect(handleProcessSearchEngine).toHaveBeenCalled();
    }

    wrapper.destroy();
  });

  //   test('Test 5: test render button back to edit, save and click', async() => {
  //     const localVue = createLocalVue();
  //     const wrapper = mount(HREdit, {
  //       localVue,
  //       router,
  //       store,
  //       stubs: {
  //         BIcon: true,
  //       },
  //     });

  //     const save_btn = wrapper.find('.btn-bold');
  //     expect(save_btn.exists()).toBe(true);
  //     const save_btn_text = save_btn.find('span');
  //     expect(save_btn_text.exists()).toBe(true);
  //     expect(save_btn_text.text()).toEqual('BUTTON.SAVE');

  //     const handleSaveHr = jest.spyOn(wrapper.vm, 'handleSaveHr');

  //     if (expect(save_btn.exists()).toBe(true)) {
  //       await save_btn.trigger('click');
  //       expect(handleSaveHr).toHaveBeenCalled();
  //     }

  //     const btn_control = wrapper.find('.hr-detail-head-btns');
  //     expect(btn_control.exists()).toBe(true);
  //     const find_btn = wrapper.findAll('.btn-light');
  //     expect(find_btn.exists()).toBe(true);
  //     expect(find_btn.length).toBe(2);
  //     const btn_back_to_detail = find_btn.at(0);
  //     expect(btn_back_to_detail.exists()).toBe(true);
  //     expect(btn_back_to_detail.text()).toEqual('BUTTON.CANCEL');

  //     const handleCancelHrEdit = jest.spyOn(wrapper.vm, 'handleCancelHrEdit');

  //     if (expect(btn_back_to_detail.exists()).toBe(true)) {
  //       await btn_back_to_detail.trigger('click');
  //       expect(handleCancelHrEdit).toHaveBeenCalled();
  //     }

  //     wrapper.destroy();
  //   });

  //   test('Test 6: test render field basic infomation (tab 1)', async() => {
  //     const localVue = createLocalVue();
  //     const wrapper = mount(HREdit, {
  //       localVue,
  //       router,
  //       store,
  //       stubs: {
  //         BIcon: true,
  //       },
  //     });

  //     // expect(wrapper).toMatchSnapshot();
  //     const TEXT_FIELD = [
  //       '氏名',
  //       '氏名（ﾌﾘｶﾞﾅ）',
  //       '性別',
  //       '生年月日',
  //       '勤務形態',
  //       '勤務形態（非常勤）',
  //       '日本語レベル',
  //     ];

  //     const basic_tab_1 = wrapper.find('.hr-tab-basic-infomation');
  //     expect(basic_tab_1.exists()).toBe(true);

  //     const all_field_basic_tab_1 = basic_tab_1.findAll('.hr-content-tab-item__title');
  //     expect(all_field_basic_tab_1.exists()).toBe(true);
  //     expect(all_field_basic_tab_1.length).toEqual(7);

  //     let idx = 0;
  //     while (idx < 7) {
  //       const field_text = all_field_basic_tab_1.at(idx).find('span');
  //       expect(field_text.exists()).toBe(true);
  //       expect(field_text.text()).toEqual(TEXT_FIELD[idx]);

  //       idx++;
  //     }

  //     wrapper.destroy();
  //   });

  //   test('Test 7: test render field basic infomation (tab 1) requied', async() => {
  //     const localVue = createLocalVue();
  //     const wrapper = mount(HREdit, {
  //       localVue,
  //       router,
  //       store,
  //       stubs: {
  //         BIcon: true,
  //       },
  //     });

  //     const save_btn = wrapper.find('.btn-bold');
  //     expect(save_btn.exists()).toBe(true);

  //     const handleSaveHr = jest.spyOn(wrapper.vm, 'handleSaveHr');

  //     if (expect(save_btn.exists()).toBe(true)) {
  //       await save_btn.trigger('click');
  //       expect(handleSaveHr).toHaveBeenCalled();
  //     }

  //     const basic_tab_1 = wrapper.find('.hr-tab-basic-infomation');
  //     expect(basic_tab_1.exists()).toBe(true);

  //     const requied_field_basic_tab_1 = basic_tab_1.findAll('.invalid-feedback');
  //     expect(requied_field_basic_tab_1.exists()).toBe(true);
  //     expect(requied_field_basic_tab_1.length).toEqual(6);

  //     let idx = 0;
  //     while (idx < 6) {
  //       const field_text = requied_field_basic_tab_1.at(idx).find('span');
  //       expect(field_text.exists()).toBe(true);
  //       expect(field_text.text()).toEqual('VALIDATE.REQUIRED_TEXT');

  //       idx++;
  //     }
  //     wrapper.destroy();
  //   });

  //   test('Test 8: test render field education work history (tab 2)', async() => {
  //     const localVue = createLocalVue();
  //     const wrapper = mount(HREdit, {
  //       localVue,
  //       router,
  //       store,
  //       stubs: {
  //         BIcon: true,
  //       },
  //     });

  //     // expect(wrapper).toMatchSnapshot();
  //     const TEXT_FIELD = [
  //       'HR_LIST_FORM.FINAL_EDUCATION',
  //       'HR_LIST_FORM.MAIN_JOB_CAREER_1',
  //       'HR_LIST_FORM.MAIN_JOB_CAREER_2',
  //       'HR_LIST_FORM.MAIN_JOB_CAREER_3',
  //     ];

  //     const tab = wrapper.find('.hr-education-work-history');
  //     expect(tab.exists()).toBe(true);

  //     const all_field_tab = tab.findAll('.hr-content-tab-item__title');
  //     expect(all_field_tab.exists()).toBe(true);
  //     expect(all_field_tab.length).toEqual(4);

  //     let idx = 0;
  //     while (idx < 4) {
  //       const field_text = all_field_tab.at(idx).find('span');
  //       expect(field_text.exists()).toBe(true);
  //       expect(field_text.text()).toEqual(TEXT_FIELD[idx]);

  //       idx++;
  //     }

  //     const TEXT_FIELD_1 = [
  //       // 'HR_LIST_FORM.GRADUATION_DATE',
  //       'HR_LIST_FORM.CLASSIFICATION',
  //       'HR_LIST_FORM.DEGREE',
  //       'HR_LIST_FORM.MAIN_CATEGORY',
  //       'HR_LIST_FORM.DEPARTMENT',
  //     ];

  //     const TEXT_FIELD_MAIN_JOB = [
  //       'HR_LIST_FORM.DATE',
  //       'HR_LIST_FORM.DEPARTMENT',
  //       'HR_LIST_FORM.JOB_TITLE',
  //       'HR_LIST_FORM.DETAIL',
  //     ];

  //     const fields_label = tab.findAll('.hr-content-tab__data');
  //     expect(fields_label.exists()).toBe(true);
  //     expect(fields_label.length).toEqual(4);

  //     const final_education = fields_label.at(0);
  //     expect(final_education.exists()).toBe(true);
  //     const final_education_label = final_education.findAll('.pl-1');
  //     expect(final_education_label.exists()).toBe(true);
  //     expect(final_education_label.length).toEqual(4);

  //     let idx_edu = 0;
  //     while (idx_edu < 4) {
  //       const final_education_label_item = final_education_label.at(idx_edu);
  //       expect(final_education_label_item.exists()).toBe(true);
  //       expect(final_education_label_item.text()).toEqual(TEXT_FIELD_1[idx_edu]);

  //       idx_edu++;
  //     }

  //     // main job career
  //     const main_job = fields_label.at(1);
  //     expect(main_job.exists()).toBe(true);
  //     const main_job_label = main_job.findAll('.pl-1');
  //     expect(main_job_label.exists()).toBe(true);
  //     expect(main_job_label.length).toEqual(4);

  //     let idx_main_job = 0;
  //     while (idx_main_job < 4) {
  //       const main_job_label_item = main_job_label.at(idx_main_job);
  //       expect(main_job_label_item.exists()).toBe(true);
  //       expect(main_job_label_item.text()).toEqual(TEXT_FIELD_MAIN_JOB[idx_main_job]);

  //       idx_main_job++;
  //     }

  //     wrapper.destroy();
  //   });

  //   test('Test 9: test render field education work history (tab 2) requied', async() => {
  //     const localVue = createLocalVue();
  //     const wrapper = mount(HREdit, {
  //       localVue,
  //       router,
  //       store,
  //       stubs: {
  //         BIcon: true,
  //       },
  //     });

  //     const save_btn = wrapper.find('.btn-bold');
  //     expect(save_btn.exists()).toBe(true);

  //     const handleSaveHr = jest.spyOn(wrapper.vm, 'handleSaveHr');

  //     if (expect(save_btn.exists()).toBe(true)) {
  //       await save_btn.trigger('click');
  //       expect(handleSaveHr).toHaveBeenCalled();
  //     }

  //     const tab = wrapper.find('.hr-education-work-history');
  //     expect(tab.exists()).toBe(true);

  //     const requied_field_tab = tab.findAll('.invalid-feedback');
  //     expect(requied_field_tab.exists()).toBe(true);
  //     expect(requied_field_tab.length).toEqual(13);

  //     let idx = 0;
  //     while (idx < 13) {
  //       const field_text = requied_field_tab.at(idx).find('span');
  //       expect(field_text.exists()).toBe(true);
  //       expect(field_text.text()).toEqual('VALIDATE.REQUIRED_TEXT');

  //       idx++;
  //     }
  //     wrapper.destroy();
  //   });

  //   test('Test 10: test render field persional PR remarks (tab 3)', async() => {
  //     const localVue = createLocalVue();
  //     const wrapper = mount(HREdit, {
  //       localVue,
  //       router,
  //       store,
  //       stubs: {
  //         BIcon: true,
  //       },
  //     });

  //     // expect(wrapper).toMatchSnapshot();
  //     const TEXT_FIELD = [
  //       'HR_LIST_FORM.PERSONAL_PR_SPECIAL_NOTES',
  //       'HR_LIST_FORM.REMARKS',
  //     ];

  //     const tab = wrapper.find('.hr-persional-pr-remarks');
  //     expect(tab.exists()).toBe(true);

  //     const all_field_tab = tab.findAll('.hr-content-tab-item__title');
  //     expect(all_field_tab.exists()).toBe(true);
  //     expect(all_field_tab.length).toEqual(2);

  //     let idx = 0;
  //     while (idx < 2) {
  //       const field_text = all_field_tab.at(idx).find('span');
  //       expect(field_text.exists()).toBe(true);
  //       expect(field_text.text()).toEqual(TEXT_FIELD[idx]);

  //       idx++;
  //     }

  //     wrapper.destroy();
  //   });

  //   test('Test 11: test render field contact (tab 4)', async() => {
  //     const localVue = createLocalVue();
  //     const wrapper = mount(HREdit, {
  //       localVue,
  //       router,
  //       store,
  //       stubs: {
  //         BIcon: true,
  //       },
  //     });

  //     // expect(wrapper).toMatchSnapshot();
  //     const TEXT_FIELD = [
  //       'HR_LIST_FORM.TELEPHONE_NUMBER',
  //       'HR_LIST_FORM.MOBILE_PHONE_NUMBER',
  //       'HR_LIST_FORM.MAIL_ADDRESS',
  //       'HR_LIST_FORM.ADDRESS',
  //       'HR_LIST_FORM.HOMETOWN_ADDRESS',
  //     ];

  //     const tab = wrapper.find('.hr-contact');
  //     expect(tab.exists()).toBe(true);

  //     const all_field_tab = tab.findAll('.hr-content-tab-item__title');
  //     expect(all_field_tab.exists()).toBe(true);
  //     expect(all_field_tab.length).toEqual(5);

  //     let idx = 0;
  //     while (idx < 4) {
  //       const field_text = all_field_tab.at(idx).find('span');
  //       expect(field_text.exists()).toBe(true);
  //       expect(field_text.text()).toEqual(TEXT_FIELD[idx]);

  //       idx++;
  //     }

  //     const TEXT_FIELD_ADDRESS = [
  //       // 'HR_LIST_FORM.GRADUATION_DATE',
  //       'HR_LIST_FORM.CITY',
  //       'HR_LIST_FORM.DISTRICT',
  //       'HR_LIST_FORM.NUMBER',
  //       'HR_LIST_FORM.ORTHER',
  //     ];

  //     const all_field_tab_check = tab.findAll('.hr-content-tab__data');
  //     expect(all_field_tab_check.exists()).toBe(true);
  //     expect(all_field_tab_check.length).toEqual(5);

  //     const address = all_field_tab_check.at(3);
  //     expect(address.exists()).toBe(true);
  //     const address_label = address.findAll('span');
  //     expect(address_label.exists()).toBe(true);
  //     expect(address_label.length).toEqual(4);

  //     let idx_address = 0;
  //     while (idx_address < 4) {
  //       const address_label_item = address_label.at(idx_address);
  //       expect(address_label_item.exists()).toBe(true);
  //       expect(address_label_item.text()).toEqual(TEXT_FIELD_ADDRESS[idx_address]);

  //       idx_address++;
  //     }

  //     wrapper.destroy();
  //   });

  //   test('Test 12: test render field contact (tab 4) requied', async() => {
  //     const localVue = createLocalVue();
  //     const wrapper = mount(HREdit, {
  //       localVue,
  //       router,
  //       store,
  //       stubs: {
  //         BIcon: true,
  //       },
  //     });

  //     const save_btn = wrapper.find('.btn-bold');
  //     expect(save_btn.exists()).toBe(true);

  //     const handleSaveHr = jest.spyOn(wrapper.vm, 'handleSaveHr');

  //     if (expect(save_btn.exists()).toBe(true)) {
  //       await save_btn.trigger('click');
  //       expect(handleSaveHr).toHaveBeenCalled();
  //     }

  //     const tab = wrapper.find('.hr-contact');
  //     expect(tab.exists()).toBe(true);

  //     const requied_field_tab = tab.findAll('.invalid-feedback');
  //     expect(requied_field_tab.exists()).toBe(true);
  //     expect(requied_field_tab.length).toEqual(1);
  //     expect(requied_field_tab.at(0).text()).toEqual('');

  //     wrapper.destroy();
  //   });
});
