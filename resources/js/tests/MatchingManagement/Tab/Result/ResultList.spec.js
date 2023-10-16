/* eslint-disable no-unused-vars */
import { createLocalVue, shallowMount, mount } from '@vue/test-utils';
import store from '@/store';
import router from '@/router';
import Result from '@/pages/MatchingManagement/Tab/Result.vue';
import MatchingManagement from '@/pages/MatchingManagement/index';
import ROLE from '@/const/role.js';

import { getListResult } from '@/api/modules/matching.js';

import { login } from '@/api/auth';
import { handleRole } from '@/utils/handleRole';

describe('Test component MatchingManagement Tab Result', () => {
  const fieldsResult = [
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
        width: '80px',
        backgroundColor: '#1D266A',
        color: '#fff',
        textAlign: 'center',
      },
      thClass: 'text-center',
    },
    {
      key: 'code',
      sortable: true,
      label: 'エントリー ID',
      class: 'bg-header',
      thStyle: {
        width: '114px',
        backgroundColor: '#1D266A',
        color: '#fff',
        textAlign: 'center',
      },
      tdClass: 'text-center',
    },
    {
      key: 'updating_date',
      sortable: true,
      label: '更新日',
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
      key: 'full_name',
      sortable: true,
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
      key: 'job_title',
      sortable: true,
      label: '求人名',
      class: 'bg-header',
      thStyle: {
        width: '200px',
        backgroundColor: '#1D266A',
        color: '#fff',
        textAlign: 'center',
      },
    },
    {
      key: 'status_selection',
      sortable: true,
      label: '選考 ステータス',
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
      key: 'time',
      sortable: true,
      label: '期限',
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
      key: 'detail',
      label: '詳細',
      class: 'bg-header',
      thStyle: {
        width: '104px',
        backgroundColor: '#1D266A',
        color: '#fff',
        textAlign: 'center',
      },
      tdClass: 'text-center',
    },
  ];

  const dataResult = [
    {
      id: 7,
      code: '1691053951',
      updating_date: '20230803',
      full_name: 'Dinh',
      job_title: 'IT engineer',
      status_selection: 2,
      time: null,
    },
  ];
  const localVue = createLocalVue();

  test('Case 1: Check render Result Template', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(Result, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    const dataResult = [
      {
        id: 7,
        code: '1691053951',
        updating_date: '20230803',
        full_name: 'Dinh',
        job_title: 'IT engineer',
        status_selection: 2,
        time: null,
      },
    ];
    await wrapper.setProps({ dataResult: dataResult });

    expect(typeof Result.data).toBe('function');
    const tabResultTemplate = wrapper.findComponent({ name: 'Result' });
    expect(tabResultTemplate.exists()).toBe(true);

    wrapper.destroy();
  });

  test('Case 2: Check render component  Matching management tab Result', async() => {
    const wrapper = mount(Result, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });
    const dataResult = [
      {
        id: 7,
        code: '1691053951',
        updating_date: '20230803',
        full_name: 'Dinh',
        job_title: 'IT engineer',
        status_selection: 2,
        time: null,
      },
    ];
    await wrapper.setProps({ dataResult: dataResult });

    const table_hr = wrapper.find('#result-table-list');
    // expect(table_hr).toMatchSnapshot();
    const btnCheckbox = table_hr.find('#checkbox-result');
    expect(btnCheckbox.exists()).toBe(true);

    const TABLE_BODY = table_hr.find('tbody');
    expect(TABLE_BODY.exists()).toBe(true);

    const TABLE_BODY_TR = TABLE_BODY.findAll('tr');
    expect(TABLE_BODY_TR.length).toEqual(1); //

    for (let tr = 0; tr < TABLE_BODY_TR.length; tr++) {
      const LIST_TD = TABLE_BODY_TR.at(tr).findAll('td');
      expect(LIST_TD.at(0).find('input[type="checkbox"]').exists()).toBe(true);
      expect(LIST_TD.at(0).find('input[type="checkbox"]').element.checked).toBe(false);
    }
  });

  test('Case 3: Check API tab Result respone code 200', async() => {
    const wrapper = mount(Result, {
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

    const response = await getListResult();
    const { code } = response.data;
    // console.log('case 5: getListResult :', response);
    expect(code).toBe(200);

    wrapper.destroy();
  });

  // test('Case 4: Check button Delete Mutiple and event function when click button Delete Mutiple', async () => {
  //   const handleDeleteAll = jest.fn();
  //   const wrapper = mount(MatchingManagement, {
  //     localVue,
  //     router,
  //     store,
  //     stubs: {
  //       BIcon: true,
  //     },
  //     methods: {
  //       handleDeleteAll,
  //     },
  //   });

  //   await wrapper.setProps({ dataResult: dataResult });
  //   await wrapper.setData({ tabIndex: 3 });

  //   const BTN_ALL_DELETE = wrapper.find('#btn-delete-all');
  //   expect(BTN_ALL_DELETE.exists()).toBeTruthy();

  //   await BTN_ALL_DELETE.trigger('click');
  //   expect(handleDeleteAll).toHaveBeenCalled();
  // });

  test('Case 5: Check component render Table tab Result have data', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(Result, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });
    const dataResultTest = [
      {
        id: 7,
        code: '1691053951',
        updating_date: '20230803',
        full_name: 'Dinh',
        job_title: 'IT engineer',
        status_selection_name: '不合格',
        status_selection: 2,
        time: null,
      },
      {
        id: 8,
        code: '1691053991',
        updating_date: '20230703',
        full_name: 'Dinh 1',
        job_title: 'IT engineer 1',
        status_selection_name: '内定',
        status_selection: 1,
        time: null,
      },
    ];
    await wrapper.setProps({ dataResult: dataResultTest });

    const table_hr = wrapper.find('#result-table-list');
    expect(table_hr).toMatchSnapshot(); //

    const btnCheckbox = table_hr.find('#checkbox-result');
    expect(btnCheckbox.exists()).toBe(true);

    for (let field = 0; field < fieldsResult.length; field++) {
      expect(table_hr.props('fields')[field].key).toEqual(fieldsResult[field].key);
      expect(table_hr.props('fields')[field].sortable).toEqual(fieldsResult[field].sortable);
      // expect(table_hr.props('fields')[field].label).toEqual(fieldsResult[field].label);
      expect(table_hr.props('fields')[field].class).toEqual(fieldsResult[field].class);
      expect(table_hr.props('fields')[field].thStyle).toEqual(fieldsResult[field].thStyle);
    }

    for (let item = 0; item < dataResultTest.length; item++) {
      expect(table_hr.props('items')[item].id).toEqual(dataResultTest[item].id);
      expect(table_hr.props('items')[item].code).toEqual(dataResultTest[item].code);
      expect(table_hr.props('items')[item].updating_date).toEqual(dataResultTest[item].updating_date);
      expect(table_hr.props('items')[item].full_name).toEqual(dataResultTest[item].full_name);
      expect(table_hr.props('items')[item].job_title).toEqual(dataResultTest[item].job_title);
      expect(table_hr.props('items')[item].status_selection).toEqual(dataResultTest[item].status_selection);
      expect(table_hr.props('items')[item].time).toEqual(dataResultTest[item].time);
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
      // expect(LIST_TD.at(1).text()).toBe(String(dataResultTest[tr].id));
      expect(LIST_TD.at(2).text()).toEqual(String(dataResultTest[tr].code));
      expect(LIST_TD.at(3).text()).toEqual(String(dataResultTest[tr].updating_date));
      expect(LIST_TD.at(4).text()).toEqual(String(dataResultTest[tr].full_name));
      expect(LIST_TD.at(5).text()).toEqual(String(dataResultTest[tr].job_title));
      expect(LIST_TD.at(6).text()).toEqual(String(dataResultTest[tr].status_selection_name));
      expect(LIST_TD.at(7).text()).toEqual('-');

      // const LIST_BUTTON = LIST_TD.at(8).findAll('button');
      // expect(LIST_BUTTON.at(8).exists()).toBe(true);
      // expect(LIST_BUTTON.at(8).text()).toEqual('DISPLAY');
      // const spyGoToDetail = jest.spyOn(wrapper.vm, 'goToDetailScreen');

      // await LIST_BUTTON.at(8).trigger('click');
      // expect(spyGoToDetail).toHaveBeenCalledWith(dataResultTest[tr].id);
    }

    // expect(table_hr).toMatchSnapshot();

    // nav-item
  });
});
