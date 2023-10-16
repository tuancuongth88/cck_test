/* eslint-disable no-unused-vars */
import { createLocalVue, shallowMount, mount } from '@vue/test-utils';
import store from '@/store';
import router from '@/router';
import { login } from '@/api/auth';
import { handleRole } from '@/utils/handleRole';
import EventBus from '@/utils/eventBus';
// import offerOop from '@/pages/MatchingManagement/Tab/offer.vue';
import EntryOop from '@/pages/MatchingManagement/Tab/entry.vue';
// import MatchingManagement from '@/pages/MatchingManagement/index';
import ROLE from '@/const/role.js';
import { getListEntry } from '@/api/modules/matching.js';

import {
  deleteMultipleEntry,
  getDetailEntry,
  updateEntry,
} from '@/api/modules/matching.js';

describe('Test component MatchingManagement Tab Entry', () => {
  const fieldsEntry = [
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

  const dataEntry = [
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

  const localVue = createLocalVue();

  test('Case 1: Check render Entry Template', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(EntryOop, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    await wrapper.setProps({ dataEntry: dataEntry });

    expect(typeof EntryOop.data).toBe('function');
    const tabEntryTemplate = wrapper.findComponent({ name: 'Entry' });
    expect(tabEntryTemplate.exists()).toBe(true);

    // wrapper.destroy();
  });

  test('Case 2: Check render component  Matching management tab Entry', async() => {
    const wrapper = mount(EntryOop, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });
    await wrapper.setProps({ dataEntry: dataEntry });

    const table_hr = wrapper.find('#entry-table-list');
    // expect(table_hr).toMatchSnapshot();
    const btnCheckbox = table_hr.find('#checkbox-entry');
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

  test('Case 3: Check API tab Entry respone code 200', async() => {
    const wrapper = shallowMount(EntryOop, {
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

    //     const response = await getListEntry();
    //     const { code } = response.data;
    // console.log('case 5: getListEntry :', response);
    // expect(code).toBe(200);

    wrapper.destroy();
  });

  test('Case 4: Check component render Table tab Entry have data', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(EntryOop, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });
    await wrapper.setProps({ dataEntry: dataEntry });

    const table_hr = wrapper.find('#entry-table-list');
    // expect(table_hr).toMatchSnapshot(); //

    const btnCheckbox = table_hr.find('#checkbox-entry');
    expect(btnCheckbox.exists()).toBe(true);

    // check Fields
    for (let field = 0; field < fieldsEntry.length; field++) {
      expect(table_hr.props('fields')[field].key).toEqual(fieldsEntry[field].key);
      expect(table_hr.props('fields')[field].sortable).toEqual(fieldsEntry[field].sortable);
      expect(table_hr.props('fields')[field].label).toEqual(fieldsEntry[field].label);
      expect(table_hr.props('fields')[field].class).toEqual(fieldsEntry[field].class);
      // expect(table_hr.props('fields')[field].thStyle).toEqual(fieldsEntry[field].thStyle);
    }

    for (let item = 0; item < dataEntry.length; item++) {
      expect(table_hr.props('items')[item].id).toEqual(dataEntry[item].id);
      expect(table_hr.props('items')[item].entry_code).toEqual(dataEntry[item].entry_code);
      expect(table_hr.props('items')[item].request_date).toEqual(dataEntry[item].request_date);

      expect(table_hr.props('items')[item].hr_id).toEqual(dataEntry[item].hr_id);
      expect(table_hr.props('items')[item].full_name).toEqual(dataEntry[item].full_name);
      expect(table_hr.props('items')[item].full_name_ja).toEqual(dataEntry[item].full_name_ja);

      expect(table_hr.props('items')[item].work_id).toEqual(dataEntry[item].work_id);
      expect(table_hr.props('items')[item].work_title).toEqual(dataEntry[item].work_title);
      expect(table_hr.props('items')[item].updating_date).toEqual(dataEntry[item].updating_date);

      expect(table_hr.props('items')[item].status).toEqual(dataEntry[item].status);
      expect(table_hr.props('items')[item].status_name).toEqual(dataEntry[item].status_name);
      expect(table_hr.props('items')[item].note).toEqual(dataEntry[item].note);

      expect(table_hr.props('items')[item].is_favorite_hrs).toEqual(dataEntry[item].is_favorite_hrs);
      expect(table_hr.props('items')[item].is_favorite_job).toEqual(dataEntry[item].is_favorite_job);
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

      // expect(LIST_TD.at(1).text()).toEqual(String(dataEntry[tr].id));
      expect(LIST_TD.at(2).text()).toEqual(String(dataEntry[tr].entry_code));
      expect(LIST_TD.at(3).text()).toEqual(String(dataEntry[tr].request_date));
      // expect(LIST_TD.at(4).text()).toEqual(String(dataEntry[tr].full_name));
      expect(LIST_TD.at(5).text()).toEqual(String(dataEntry[tr].work_title));
      expect(LIST_TD.at(6).text()).toEqual(String(dataEntry[tr].updating_date));

      const btnShowDetail = LIST_TD.at(8).findAll('#btn-go-detail');
      expect(btnShowDetail.exists()).toBe(true);

      expect(btnShowDetail.length).toBe(1);
      const goToDetail = jest.spyOn(wrapper.vm, 'goToDetail');

      await btnShowDetail.trigger('click');
      expect(goToDetail).toHaveBeenCalledWith(dataEntry[tr]);
    }

    // nav-item
  });

  test('Case 5: Check modal entry requesting Reject by role Hr & Hr O', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(EntryOop, {
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

    let permissionCheck = null;
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
        permissionCheck = store.getters.permissionCheck; // ! ROLE 5 vs 3 can't confirm or reject
        console.log('permissionCheck', permissionCheck);
        //
      }
    });

    const responseListEntry = await getListEntry();
    const codeListEntry = responseListEntry.data.code;
    expect(codeListEntry).toBe(200);
    const resultListEntry = responseListEntry.data.data.result;

    const dataEntryArr = [];
    resultListEntry.map((item) => {
      // Check for status id = 1 Re
      if (item.status === 1 || item.status === 4) {
        dataEntryArr.push({
          ...item,
          _isSelected: false,
        });
      }
    });

    console.log('case 5: getListEntry :', dataEntryArr);
    await wrapper.setProps({ dataEntry: dataEntryArr });

    const table_hr = wrapper.find('#entry-table-list');
    // expect(table_hr).toMatchSnapshot(); //

    const btnCheckbox = table_hr.find('#checkbox-entry');
    expect(btnCheckbox.exists()).toBe(true);

    for (let item = 0; item < dataEntryArr.length; item++) {
      expect(table_hr.props('items')[item].status).toEqual(dataEntryArr[item].status);
    }

    const TABLE_BODY = table_hr.find('tbody');
    expect(TABLE_BODY.exists()).toBe(true);

    const TABLE_BODY_TR = TABLE_BODY.findAll('tr');
    expect(TABLE_BODY_TR.length).toEqual(dataEntryArr.length); // Number item

    let itemEntryClick = {};

    for (let tr = 0; tr < TABLE_BODY_TR.length; tr++) { // find col
      const LIST_TD = TABLE_BODY_TR.at(tr).findAll('td');

      const btnShowDetail = LIST_TD.at(8).findAll('#btn-go-detail'); // Col index 8 icon show detail
      expect(btnShowDetail.exists()).toBe(true);
      expect(btnShowDetail.length).toBe(1);

      const goToDetail = jest.spyOn(wrapper.vm, 'goToDetail');

      await btnShowDetail.trigger('click');
      expect(goToDetail).toHaveBeenCalledWith(dataEntryArr[tr]);
      itemEntryClick = dataEntryArr[tr];
    }

    const responseGetDetailEntry = await getDetailEntry(itemEntryClick.id);
    const codeDetailEntry = responseGetDetailEntry.data.code;
    expect(codeDetailEntry).toBe(200);
    console.log('case 5: getDetailEntry :', responseGetDetailEntry);

    // Reject  ![ROLE.TYPE_HR, ROLE.TYPE_HR_MANAGER]
    const ModalEntryDeclineElement = wrapper.find('#entry-modal-reject');
    expect(ModalEntryDeclineElement.exists()).toBe(true);

    const handleModalRequestingReject = jest.spyOn(wrapper.vm, 'handleModalRequestingReject');

    const btnRequestingReject = wrapper.find('#btn-requesting-reject');
    expect(btnRequestingReject.exists()).toBe(true);
    // btnRequestingReject.trigger('click');
    // expect(handleModalRequestingReject).toHaveBeenCalled();

    const paramsReject = {
      id: itemEntryClick.id, // item được chọn
      status: 4, // Hr reject
    };
    const responseparamsReject = await updateEntry(paramsReject);
    const codeparamsReject = responseparamsReject.data.code;
    expect(codeparamsReject).toBe(200);
    //
  });

  //
});
