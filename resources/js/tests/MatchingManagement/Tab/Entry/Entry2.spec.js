/* eslint-disable no-unused-vars */
import { createLocalVue, shallowMount, mount } from '@vue/test-utils';
import store from '@/store';
import router from '@/router';
import Entry from '@/pages/MatchingManagement/Tab/Entry.vue';
// import MatchingManagement from '@/pages/MatchingManagement/index';
import ROLE from '@/const/role.js';

import { getListEntry } from '@/api/modules/matching.js';

import { login } from '@/api/auth';
import { handleRole } from '@/utils/handleRole';

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
  ];
  const localVue = createLocalVue();

  test('Case 1: Check render Entry Template', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(Entry, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

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
    ];
    await wrapper.setProps({ dataEntry: dataEntry });

    expect(typeof Entry.data).toBe('function');
    const tabEntryTemplate = wrapper.findComponent({ name: 'Entry' });
    expect(tabEntryTemplate.exists()).toBe(true);

    // wrapper.destroy();
  });
  //
  test('Case 2: Check render component  Matching management tab Entry', async() => {
    const wrapper = mount(Entry, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });
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
    ];
    await wrapper.setProps({ dataEntry: dataEntry });

    const table_hr = wrapper.find('#entry-table-list');
    // expect(table_hr).toMatchSnapshot();
    const btnCheckbox = table_hr.find('#checkbox-entry');
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
    const wrapper = mount(Entry, {
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

    const response = await getListEntry();
    const { code } = response.data;
    // console.log('case 5: getListEntry :', response);
    expect(code).toBe(200);

    wrapper.destroy();
  });

  //   test('Case 4: Check button Delete Mutiple and event function when click button Delete Mutiple', async() => {
  //     const handleDeleteAll = jest.fn();
  //     const wrapper = mount(MatchingManagement, {
  //       localVue,
  //       router,
  //       store,
  //       stubs: {
  //         BIcon: true,
  //       },
  //       methods: {
  //         handleDeleteAll,
  //       },
  //     });
  //
  //     await wrapper.setProps({ dataEntry: dataEntry });
  //     await wrapper.setData({ tabIndex: 0 });
  //
  //     const BTN_ALL_DELETE = wrapper.find('#btn-delete-all');
  //     expect(BTN_ALL_DELETE.exists()).toBeTruthy();
  //
  //     await BTN_ALL_DELETE.trigger('click');
  //     expect(handleDeleteAll).toHaveBeenCalled();
  //   });

  test('Case 5: Check component render Table tab Entry have data', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(Entry, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });
    const dataEntryTest = [
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
    await wrapper.setProps({ dataEntry: dataEntryTest });

    const table_hr = wrapper.find('#entry-table-list');
    // expect(table_hr).toMatchSnapshot(); //

    const btnCheckbox = table_hr.find('#checkbox-entry');
    expect(btnCheckbox.exists()).toBe(true);

    for (let field = 0; field < fieldsEntry.length; field++) {
      expect(table_hr.props('fields')[field].key).toEqual(fieldsEntry[field].key);
      expect(table_hr.props('fields')[field].sortable).toEqual(fieldsEntry[field].sortable);
      expect(table_hr.props('fields')[field].label).toEqual(fieldsEntry[field].label);
      expect(table_hr.props('fields')[field].class).toEqual(fieldsEntry[field].class);
      // expect(table_hr.props('fields')[field].thStyle).toEqual(fieldsEntry[field].thStyle);
    }

    for (let item = 0; item < dataEntryTest.length; item++) {
      expect(table_hr.props('items')[item].id).toEqual(dataEntryTest[item].id);
      expect(table_hr.props('items')[item].entry_code).toEqual(dataEntryTest[item].entry_code);
      expect(table_hr.props('items')[item].request_date).toEqual(dataEntryTest[item].request_date);

      expect(table_hr.props('items')[item].hr_id).toEqual(dataEntryTest[item].hr_id);
      expect(table_hr.props('items')[item].full_name).toEqual(dataEntryTest[item].full_name);
      expect(table_hr.props('items')[item].full_name_ja).toEqual(dataEntryTest[item].full_name_ja);

      expect(table_hr.props('items')[item].work_id).toEqual(dataEntryTest[item].work_id);
      expect(table_hr.props('items')[item].work_title).toEqual(dataEntryTest[item].work_title);
      expect(table_hr.props('items')[item].updating_date).toEqual(dataEntryTest[item].updating_date);

      expect(table_hr.props('items')[item].status).toEqual(dataEntryTest[item].status);
      expect(table_hr.props('items')[item].status_name).toEqual(dataEntryTest[item].status_name);
      expect(table_hr.props('items')[item].note).toEqual(dataEntryTest[item].note);

      expect(table_hr.props('items')[item].is_favorite_hrs).toEqual(dataEntryTest[item].is_favorite_hrs);
      expect(table_hr.props('items')[item].is_favorite_job).toEqual(dataEntryTest[item].is_favorite_job);
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

      // expect(LIST_TD.at(1).text()).toEqual(String(dataEntryTest[tr].id));
      expect(LIST_TD.at(2).text()).toEqual(String(dataEntryTest[tr].entry_code));
      expect(LIST_TD.at(3).text()).toEqual(String(dataEntryTest[tr].request_date));
      // expect(LIST_TD.at(4).text()).toEqual(String(dataEntryTest[tr].full_name));
      expect(LIST_TD.at(5).text()).toEqual(String(dataEntryTest[tr].work_title));
      expect(LIST_TD.at(6).text()).toEqual(String(dataEntryTest[tr].updating_date));

      const LIST_BUTTON = LIST_TD.at(8).findAll('button');
      // expect(LIST_BUTTON.at(8).exists()).toBe(true);
      // expect(LIST_BUTTON.at(8).text()).toEqual('DISPLAY');
      // const spyGoToDetail = jest.spyOn(wrapper.vm, 'goToDetailScreen');

      // await LIST_BUTTON.at(8).trigger('click');
      // expect(spyGoToDetail).toHaveBeenCalledWith(dataEntryTest[tr].id);
    }

    // expect(table_hr).toMatchSnapshot();

    // nav-item
  });
  //
});
