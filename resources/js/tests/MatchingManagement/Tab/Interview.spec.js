/* eslint-disable no-unused-vars */
import { createLocalVue, mount } from '@vue/test-utils';
import store from '@/store';
import router from '@/router';
import Interview from '@/pages/MatchingManagement/Tab/Interview.vue';
import MatchingManagement from '@/pages/MatchingManagement/index';

import { login } from '@/api/auth';
import { getListInterview } from '@/api/modules/matching.js';
import { handleRole } from '@/utils/handleRole';

describe('Test Component UserManagementList', () => {
  // const LIST_INTERVIEW = {
  //   'code': 200,
  //   'data': {
  //     'result': [
  //       {
  //         'id': 3,
  //         'entry_code': '1687250069',
  //         'interview_date': '20300101',
  //         'hr_id': 2,
  //         'full_name': 'Kiet',
  //         'full_name_ja': '\u30ad\u30a8\u30c3\u30c8',
  //         'work_id': 1,
  //         'job_name': 'IT engineer',
  //         'updating_date': '20230803',
  //         'status_selection': 1,
  //         'status_selection_name': '\u66f8\u985e\u901a\u904e',
  //         'status_interview_adjustment': 1,
  //         'status_interview_adjustment_name': '\u8abf\u6574\u524d',
  //       },
  //       {
  //         'id': 2,
  //         'entry_code': '1687248047',
  //         'interview_date': '20300104',
  //         'hr_id': 1,
  //         'full_name': '1',
  //         'full_name_ja': '1',
  //         'work_id': 1,
  //         'job_name': 'IT engineer',
  //         'updating_date': '20230803',
  //         'status_selection': 5,
  //         'status_selection_name': '\u4e00\u6b21\u5408\u683c',
  //         'status_interview_adjustment': 2,
  //         'status_interview_adjustment_name': '\u8abf\u6574\u4e2d',
  //       },
  //       {
  //         'id': 1,
  //         'entry_code': null,
  //         'interview_date': '20230803',
  //         'hr_id': 9,
  //         'full_name': 'Hoa',
  //         'full_name_ja': '\u30db\u30a2',
  //         'work_id': 3,
  //         'job_name': 'IT\u30aa\u30d5\u30b7\u30e7\u30a2\u958b\u767a\u30de\u30cd\u30fc\u30b8\u30e3\u30fc',
  //         'updating_date': '20230803',
  //         'status_selection': 2,
  //         'status_selection_name': '\u30aa\u30d5\u30a1\u30fc\u627f\u8a8d',
  //         'status_interview_adjustment': 1,
  //         'status_interview_adjustment_name': '\u8abf\u6574\u524d',
  //       },
  //     ],
  //     'pagination': {
  //       'display': 3,
  //       'total_records': 3,
  //       'per_page': 20,
  //       'current_page': 1,
  //       'total_pages': 1,
  //     },
  //   },
  // };

  const dataInterview = [
    {
      id: 7,
      entry_code: '1691053951',
      interview_date: '20230803',
      full_name: 'Dinh',
      job_name: 'ディン',
      status_selection: 9,
      status_interview_adjustment: 1,
    },
  ];

  const fieldsInterview = [
    {
      key: 'selected',
      sortable: false,
      label: '',
      class: 'bg-header',
      thStyle: {
        backgroundColor: '#1D266A',
        width: '50px',
        color: '#fff',
        textAlign: 'center',
      },
      thClass: 'text-center',
      tdClass: 'text-center',
    },
    {
      key: 'id',
      sortable: true,
      label: 'ID',
      class: 'bg-header',
      thStyle: {
        backgroundColor: '#1D266A',
        width: '80px',
        color: '#fff',
        textAlign: 'center',
      },
      thClass: 'text-center',
      tdClass: 'text-center',
    },
    {
      key: 'entry_code',
      sortable: true,
      label: 'エントリーID',
      class: 'bg-header',
      thStyle: {
        width: '120px',
        backgroundColor: '#1D266A',
        color: '#fff',
        textAlign: 'center',
      },
      tdClass: 'text-center',
    },
    {
      key: 'interview_date',
      sortable: true,
      // label: this.$t('HEADER_REQUEST_DATE_ENTRY_MATCHING'),
      label: '面接日',
      class: 'bg-header',
      thStyle: {
        backgroundColor: '#1D266A',
        width: '120px',
        color: '#fff',
        textAlign: 'center',
      },
      tdClass: 'text-center',
    },
    {
      key: 'full_name',
      sortable: true,
      // label: this.$t('HEADER_FULL_NAME_ENTRY_MATCHING'),
      label: '氏名',
      class: 'bg-header',
      thStyle: {
        backgroundColor: '#1D266A',
        width: '200px',
        color: '#fff',
        textAlign: 'center',
      },
    },
    {
      key: 'job_name',
      sortable: true,
      // label: this.$t('HEADER_JOB_LIST_ENTRY_MATCHING'),
      label: '求人名',
      class: 'bg-header',
      thStyle: {
        backgroundColor: '#1D266A',
        width: '200px',
        color: '#fff',
        textAlign: 'center',
      },
    },
    {
      key: 'status_selection',
      sortable: true,
      label: '選考 ｽﾃｰﾀｽ',
      class: 'bg-header',
      thStyle: {
        backgroundColor: '#1D266A',
        width: '140px',
        color: '#fff',
        textAlign: 'center',
      },
      tdClass: 'text-center',
    },
    {
      key: 'status_interview_adjustment',
      sortable: true,
      label: '面接調整ｽﾃｰﾀｽ',
      class: 'bg-header',
      thStyle: {
        backgroundColor: '#1D266A',
        width: '140px',
        color: '#fff',
        textAlign: 'center',
      },
      tdClass: 'text-center',
    },
    // This one needs a custom template, so we define the key and the label
    {
      key: 'detail',
      label: '詳細',
      class: 'bg-header',
      thStyle: {
        width: '59px',
        backgroundColor: '#1D266A',
        color: '#fff',
        textAlign: 'center',
      },
      tdClass: 'text-center',
    },
  ];

  it('Case 1: Check render Interview Template', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(Interview, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    const dataInterview = [
      {
        id: 7,
        entry_code: '1691053951',
        interview_date: '20230803',
        full_name: 'Dinh',
        job_name: 'ディン',
        status_selection: 1,
        status_interview_adjustment: 1,
      },
    ];
    await wrapper.setProps({ dataInterview: dataInterview });

    expect(typeof Interview.data).toBe('function');
    const tabEntryTemplate = wrapper.findComponent({ name: 'Interview' });
    expect(tabEntryTemplate.exists()).toBe(true);
  });

  test('Case 2: Check render component  Matching management tab Entry', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(Interview, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });
    const dataInterview = [
      {
        id: 7,
        entry_code: '1691053951',
        interview_date: '20230803',
        full_name: 'Dinh',
        job_name: 'ディン',
        status_selection: 9,
        status_interview_adjustment: 1,
      },
    ];
    await wrapper.setProps({ dataInterview: dataInterview });

    const table_hr = wrapper.find('#interview-table');
    // expect(table_hr).toMatchSnapshot();
    const btnCheckbox = table_hr.find('#checkbox-interview');
    expect(btnCheckbox.exists()).toBe(true);

    const TABLE_BODY = table_hr.find('tbody');
    expect(TABLE_BODY.exists()).toBe(true);

    const TABLE_BODY_TR = TABLE_BODY.findAll('tr');
    expect(TABLE_BODY_TR.length).toEqual(1); //
    // :disabled="![2, 3].includes(row.item.status)"

    for (let tr = 0; tr < TABLE_BODY_TR.length; tr++) {
      const LIST_TD = TABLE_BODY_TR.at(tr).findAll('td');
      expect(LIST_TD.at(0).find('input[type="checkbox"]').exists()).toBe(true);
      expect(LIST_TD.at(0).find('input[type="checkbox"]').element.checked).toBe(false);
    }

    // nav-item
  });

  test('Case 3: Check API tab Entry respone code 200', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(Interview, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    const params = {
      mail_address: '1okuridashi_hanoi@gmail.vn',
      password: '123456789CCk',
    };

    await login(params).then((response) => {
      if (response['data']['code'] === 200) {
        const TOKEN = response['data']['data']['access_token'];
        const USER = response['data']['data']['profile'];

        const { ROLES, PERMISSIONS } = handleRole([]);

        const USER_INFO = {
          token: TOKEN,
          profile: USER,
          roles: ROLES,
          permissions: PERMISSIONS,
        };

        store.dispatch('user/saveLogin', USER_INFO);
        // expect(store.getters.token).toBe(TOKEN);
        //
      }
    });

    const response = await getListInterview();
    const { code } = response.data;
    // console.log('case 5: getListEntry :', response);
    expect(code).toBe(200);

    wrapper.destroy();
  });

  test('Case 4: Check button Delete Mutiple and event function when click button Delete Mutiple', async() => {
    const localVue = createLocalVue();
    const handleDeleteAll = jest.fn();
    const wrapper = mount(MatchingManagement, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
      methods: {
        handleDeleteAll,
      },
    });

    await wrapper.setProps({ dataInterview: dataInterview });
    await wrapper.setData({ tabIndex: 2 });

    const BTN_ALL_DELETE = wrapper.find('#btn-delete-all');
    expect(BTN_ALL_DELETE.exists()).toBeTruthy();

    await BTN_ALL_DELETE.trigger('click');
    expect(handleDeleteAll).toHaveBeenCalled();
  });

  test('Case 5: Check component render Table tab Interview have data', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(Interview, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });
    const dataInterviewTest = [
      {
        id: 7,
        entry_code: '1691053951',
        request_date: '20230803',
        hr_id: 6,
        full_name: 'Dinh',
        full_name_ja: 'ディン',
        work_id: 1,
        work_title: 'IT engineer',
        updating_date: '20230803',
        status: 2,
        status_name: '却下',
        note: null,
        is_favorite_hrs: 0,
        is_favorite_job: 0,
      },
      {
        id: 11,
        entry_code: '1691145741',
        request_date: '20230804',
        hr_id: 7,
        full_name: 'Cuong',
        full_name_ja: 'クォン',
        work_id: 1,
        work_title: 'IT engineer',
        updating_date: '20230804',
        status: 1,
        status_name: '申請中',
        note: null,
        is_favorite_hrs: 0,
        is_favorite_job: 0,
      },
    ];
    await wrapper.setProps({ dataInterview: dataInterviewTest });

    const table_hr = wrapper.find('#interview-table');
    expect(table_hr).toMatchSnapshot(); //

    const btnCheckbox = table_hr.find('#checkbox-interview');
    expect(btnCheckbox.exists()).toBe(true);

    for (let field = 0; field < fieldsInterview.length; field++) {
      expect(table_hr.props('fields')[field].key).toEqual(fieldsInterview[field].key);
      expect(table_hr.props('fields')[field].sortable).toEqual(fieldsInterview[field].sortable);
      expect(table_hr.props('fields')[field].label).toEqual(fieldsInterview[field].label);
      expect(table_hr.props('fields')[field].class).toEqual(fieldsInterview[field].class);
      expect(table_hr.props('fields')[field].thStyle).toEqual(fieldsInterview[field].thStyle);
    }

    for (let item = 0; item < dataInterviewTest.length; item++) {
      expect(table_hr.props('items')[item].id).toEqual(dataInterviewTest[item].id);
      expect(table_hr.props('items')[item].entry_code).toEqual(dataInterviewTest[item].entry_code);
      expect(table_hr.props('items')[item].interview_date).toEqual(dataInterviewTest[item].interview_date);

      expect(table_hr.props('items')[item].full_name).toEqual(dataInterviewTest[item].full_name);

      expect(table_hr.props('items')[item].job_name).toEqual(dataInterviewTest[item].job_name);
      expect(table_hr.props('items')[item].status_selection).toEqual(dataInterviewTest[item].status_selection);

      expect(table_hr.props('items')[item].status_interview_adjustment).toEqual(dataInterviewTest[item].status_interview_adjustment);
    }

    const TABLE_BODY = table_hr.find('tbody');
    expect(TABLE_BODY.exists()).toBe(true);

    const TABLE_BODY_TR = TABLE_BODY.findAll('tr');
    expect(TABLE_BODY_TR.length).toEqual(2); // 2 item
    // :disabled="![2, 3].includes(row.item.status)"

    for (let tr = 0; tr < TABLE_BODY_TR.length; tr++) { // find col
      const LIST_TD = TABLE_BODY_TR.at(tr).findAll('td');

      expect(LIST_TD.at(0).find('input[type="checkbox"]').exists()).toBe(true);
      expect(LIST_TD.at(0).find('input[type="checkbox"]').element.checked).toBe(false);

      expect(LIST_TD.at(1).text()).toEqual(String(dataInterviewTest[tr].id));
      expect(LIST_TD.at(2).text()).toEqual(String(dataInterviewTest[tr].entry_code));
      // expect(LIST_TD.at(3).text()).toEqual(String(dataInterviewTest[tr].interview_date));
      // expect(LIST_TD.at(4).text()).toEqual(String(dataInterviewTest[tr].full_name));
      // expect(LIST_TD.at(5).text()).toEqual(String(dataInterviewTest[tr].job_name));
      // expect(LIST_TD.at(6).text()).toEqual(String(dataInterviewTest[tr].status_selection));
      // expect(LIST_TD.at(7).text()).toEqual(String(dataInterviewTest[tr].status_interview_adjustment));

      const LIST_BUTTON = LIST_TD.at(8).findAll('button');
      expect(LIST_BUTTON.at(8).exists()).toBe(true);
      expect(LIST_BUTTON.at(8).text()).toEqual('DISPLAY');
      const spyGoToDetail = jest.spyOn(wrapper.vm, 'goToDetailScreen');

      await LIST_BUTTON.at(8).trigger('click');
      expect(spyGoToDetail).toHaveBeenCalledWith(dataInterviewTest[tr].id);
    }

    expect(table_hr).toMatchSnapshot();

    // nav-item
  });
});
