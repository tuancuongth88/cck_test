/* eslint-disable no-unused-vars */
import { createLocalVue, shallowMount, mount } from '@vue/test-utils';
import store from '@/store';
import router from '@/router';
import { login } from '@/api/auth';
import { handleRole } from '@/utils/handleRole';
import EventBus from '@/utils/eventBus';
// import offerOop from '@/pages/MatchingManagement/Tab/offer.vue';
import InterviewOop from '@/pages/MatchingManagement/Tab/interview.vue';
// import MatchingManagement from '@/pages/MatchingManagement/index';
import ROLE from '@/const/role.js';
import { getListInterview } from '@/api/modules/matching.js';

import {
  deleteMultipleInterview,
  getDetailInterview,
  updateInterview,
} from '@/api/modules/matching.js';

describe('Test component Tab Interview Detail', () => {
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
      label: 'HEADER_ID',
      class: 'bg-header',
      thStyle: {
        backgroundColor: '#1D266A',
        width: '120px',
        color: '#fff',
        textAlign: 'center',
      },
      thClass: 'text-center',
    },
    {
      key: 'entry_code',
      sortable: true,
      label: 'HEADER_ID_ENTRY',
      class: 'bg-header',
      thStyle: {
        backgroundColor: '#1D266A',
        width: '120px',
        color: '#fff',
        textAlign: 'center',
      },
    },
    {
      key: 'request_date',
      sortable: true,
      label: 'HEADER_REQUEST_DATE_ENTRY_MATCHING',
      class: 'bg-header',
      thStyle: {
        backgroundColor: '#1D266A',
        width: '120px',
        color: '#fff',
        textAlign: 'center',
      },
    },
    {
      key: 'full_name',
      sortable: true,
      label: 'HEADER_FULL_NAME_ENTRY_MATCHING',
      class: 'bg-header',
      thStyle: {
        backgroundColor: '#1D266A',
        width: '200px',
        color: '#fff',
        textAlign: 'center',
      },
    },
    {
      key: 'work_title',
      sortable: true,
      label: 'HEADER_JOB_LIST_ENTRY_MATCHING',
      class: 'bg-header',
      thStyle: {
        backgroundColor: '#1D266A',
        width: '200px',
        color: '#fff',
        textAlign: 'center',
      },
    },
    {
      key: 'updating_date',
      sortable: true,
      label: 'HEADER_UPDATING_DATE_ENTRY_MATCHING',
      class: 'bg-header',
      thStyle: {
        backgroundColor: '#1D266A',
        width: '120px',
        color: '#fff',
        textAlign: 'center',
      },
    },
    {
      key: 'status',
      sortable: true,
      label: 'HEADER_STATUS_ENTRY_MATCHING',
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
      key: 'detail',
      label: 'HEADER_DETAIL_ENTRY_MATCHING',
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
    {
      id: 8,
      entry_code: '1691053958',
      interview_date: '20230806',
      full_name: 'Dinh',
      job_name: 'ディン',
      status_selection: 8,
      status_interview_adjustment: 1,
    },
  ];

  const localVue = createLocalVue();

  test('Case 1: Check render Interview Template detail', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(InterviewOop, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    await wrapper.setProps({ dataInterview: dataInterview });

    expect(typeof InterviewOop.data).toBe('function');
    const tabEntryTemplate = wrapper.findComponent({ name: 'Interview' });
    expect(tabEntryTemplate.exists()).toBe(true);

    // wrapper.destroy();
  });

  test('Case 2: Check render component  Matching management tab Interview', async() => {
    const wrapper = mount(InterviewOop, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });
    await wrapper.setProps({ dataInterview: dataInterview });

    const table_hr = wrapper.find('#interview-table');
    // expect(table_hr).toMatchSnapshot();
    const btnCheckbox = table_hr.find('#checkbox-interview');
    expect(btnCheckbox.exists()).toBe(true);

    const TABLE_BODY = table_hr.find('tbody');
    expect(TABLE_BODY.exists()).toBe(true);

    const TABLE_BODY_TR = TABLE_BODY.findAll('tr');
    expect(TABLE_BODY_TR.length).toEqual(2); //

    for (let tr = 0; tr < TABLE_BODY_TR.length; tr++) {
      const LIST_TD = TABLE_BODY_TR.at(tr).findAll('td');
      expect(LIST_TD.at(0).find('input[type="checkbox"]').exists()).toBe(true);
      expect(LIST_TD.at(0).find('input[type="checkbox"]').element.checked).toBe(false);
    }

    // nav-item
  });

  test('Case 3: Check API tab Interview respone code 200', async() => {
    const wrapper = shallowMount(InterviewOop, {
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
    expect(code).toBe(200);

    wrapper.destroy();
  });

  test('Case 4: Check component render Table tab Interview have data', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(InterviewOop, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });
    await wrapper.setProps({ dataInterview: dataInterview });

    const table_hr = wrapper.find('#interview-table');
    // expect(table_hr).toMatchSnapshot(); //

    const btnCheckbox = table_hr.find('#checkbox-interview');
    expect(btnCheckbox.exists()).toBe(true);

    // check Fields
    for (let field = 0; field < fieldsInterview.length; field++) {
      expect(table_hr.props('fields')[field].key).toEqual(fieldsInterview[field].key);
      expect(table_hr.props('fields')[field].sortable).toEqual(fieldsInterview[field].sortable);
      expect(table_hr.props('fields')[field].label).toEqual(fieldsInterview[field].label);
      expect(table_hr.props('fields')[field].class).toEqual(fieldsInterview[field].class);
      expect(table_hr.props('fields')[field].thStyle).toEqual(fieldsInterview[field].thStyle);
    }

    for (let item = 0; item < dataInterview.length; item++) {
      expect(table_hr.props('items')[item].id).toEqual(dataInterview[item].id);
      expect(table_hr.props('items')[item].entry_code).toEqual(dataInterview[item].entry_code);
      expect(table_hr.props('items')[item].request_date).toEqual(dataInterview[item].request_date);

      expect(table_hr.props('items')[item].hr_id).toEqual(dataInterview[item].hr_id);
      expect(table_hr.props('items')[item].full_name).toEqual(dataInterview[item].full_name);
      expect(table_hr.props('items')[item].full_name_ja).toEqual(dataInterview[item].full_name_ja);

      expect(table_hr.props('items')[item].work_id).toEqual(dataInterview[item].work_id);
      expect(table_hr.props('items')[item].work_title).toEqual(dataInterview[item].work_title);
      expect(table_hr.props('items')[item].updating_date).toEqual(dataInterview[item].updating_date);

      expect(table_hr.props('items')[item].status).toEqual(dataInterview[item].status);
      expect(table_hr.props('items')[item].status_name).toEqual(dataInterview[item].status_name);
      expect(table_hr.props('items')[item].note).toEqual(dataInterview[item].note);

      expect(table_hr.props('items')[item].is_favorite_hrs).toEqual(dataInterview[item].is_favorite_hrs);
      expect(table_hr.props('items')[item].is_favorite_job).toEqual(dataInterview[item].is_favorite_job);
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

      expect(LIST_TD.at(1).text()).toEqual(String(dataInterview[tr].id));
      expect(LIST_TD.at(2).text()).toEqual(String(dataInterview[tr].entry_code));
      expect(LIST_TD.at(3).text()).toEqual(String(dataInterview[tr].request_date));
      // expect(LIST_TD.at(4).text()).toEqual(String(dataInterview[tr].full_name));
      expect(LIST_TD.at(5).text()).toEqual(String(dataInterview[tr].work_title));
      expect(LIST_TD.at(6).text()).toEqual(String(dataInterview[tr].updating_date));

      const btnShowDetail = LIST_TD.at(8).findAll('#btn-go-detail');
      expect(btnShowDetail.exists()).toBe(true);

      expect(btnShowDetail.length).toBe(1);
      const goToDetail = jest.spyOn(wrapper.vm, 'goToDetail');

      await btnShowDetail.trigger('click');
      expect(goToDetail).toHaveBeenCalledWith(dataInterview[tr]);
    }

    // nav-item
  });

  test('Case 5: Check modal interview requesting Confirm', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(InterviewOop, {
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
        const permissionCheck = store.getters.permissionCheck; // ! ROLE 5 vs 3 can't confirm or reject
        console.log('permissionCheck', permissionCheck);
        //
      }
    });

    const responseListInterview = await getListInterview();
    const codeListInterview = responseListInterview.data.code;
    expect(codeListInterview).toBe(200);
    const resultListInterview = responseListInterview.data.data.result;

    const dataInterviewArr = [];
    resultListInterview.map((item) => {
      // Check for status id = 1 Re
      if (item.status === 1 || item.status === 4) {
        dataInterviewArr.push({
          ...item,
          _isSelected: false,
        });
      }
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
    // expect(table_hr).toMatchSnapshot(); //

    const btnCheckbox = table_hr.find('#checkbox-interview');
    expect(btnCheckbox.exists()).toBe(true);

    for (let item = 0; item < dataInterview.length; item++) {
      expect(table_hr.props('items')[item].status).toEqual(dataInterviewArr[item].status);
    }

    const TABLE_BODY = table_hr.find('tbody');
    expect(TABLE_BODY.exists()).toBe(true);

    const TABLE_BODY_TR = TABLE_BODY.findAll('tr');
    expect(TABLE_BODY_TR.length).toEqual(dataInterviewArr.length); // Number item

    let itemInterviewClick = {};

    for (let tr = 0; tr < TABLE_BODY_TR.length; tr++) { // find col
      const LIST_TD = TABLE_BODY_TR.at(tr).findAll('td');

      const btnShowDetail = LIST_TD.at(8).findAll('#btn-go-detail'); // Col index 8 icon show detail
      expect(btnShowDetail.exists()).toBe(true);
      expect(btnShowDetail.length).toBe(1);

      const goToDetail = jest.spyOn(wrapper.vm, 'goToDetail');

      await btnShowDetail.trigger('click');
      expect(goToDetail).toHaveBeenCalledWith(dataInterviewArr[tr]);
      itemInterviewClick = dataInterviewArr[tr];
    }

    const responseGetDetailInterview = await getDetailInterview(itemInterviewClick.id);
    const codeDetailInterview = responseGetDetailInterview.data.code;
    expect(codeDetailInterview).toBe(200);
    console.log('case 5: getDetailInterview :', responseGetDetailInterview);

    // Requesting  ![ROLE.TYPE_HR, ROLE.TYPE_HR_MANAGER]
    const ModalRequestingElement = wrapper.find('#interview-modal-requesting');
    expect(ModalRequestingElement.exists()).toBe(true);
    expect((wrapper.find('.content-detail')).exists()).toBe(true);

    const handleModalRequestingConfirm = jest.spyOn(wrapper.vm, 'handleModalRequestingConfirm');

    const btnRequestingConfirm = wrapper.find('#btn-requesting-confirm');
    expect(btnRequestingConfirm.exists()).toBe(true);

    // btnRequestingConfirm.trigger('click');
    // expect(handleModalRequestingConfirm).toHaveBeenCalled();

    const entryModalRequestingConfirm = wrapper.find('#interview-modal-requesting-confirm');
    expect(entryModalRequestingConfirm.exists()).toBe(true);

    const paramsConfirm = {
      id: itemInterviewClick.id, // item được chọn
      status: 4, // Other company
    };
    const responseConfirm = await updateInterview(paramsConfirm);
    const codeConfirm = responseConfirm.data.code;
    expect(codeConfirm).toBe(200);
    //
  });
});

