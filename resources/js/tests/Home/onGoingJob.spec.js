/* eslint-disable no-unused-vars */
import { createLocalVue, shallowMount, mount } from '@vue/test-utils';
import store from '@/store';
import router from '@/router';
import HomeOop from '@/pages/Home/index.vue';
import ROLE from '@/const/role.js';

import newMsglistOop from '@/pages/Home/index.vue';
import { listGoingJob } from '@/api/user.js';
import { login } from '@/api/auth';
import { handleRole } from '@/utils/handleRole';

describe('Test component MatchingManagement Tab Entry', () => {
  const listGoingOnJob = [
    {
      id: 10,
      date: '2023-08-24',
      dateJa: '2023年08月24日 (Thu)',
      id_hrs: 14,
      full_name: 'Dinh',
      full_name_ja: 'ディン',
      id_job: 31,
      job_name: 'IT engineer',
      company_name: 'Takaba Company',
      company_name_ja: 'タカバカンパニー',
      occupation: 'offer',
      occupation_ja: 'オファー',
      to_link: 'interview',
    },
    {
      id: 9,
      date: '2023-08-24',
      dateJa: '2023年08月24日 (Thu)',
      id_hrs: 14,
      full_name: 'Dinh',
      full_name_ja: 'ディン',
      id_job: 32,
      job_name: 'IT engineer',
      company_name: 'Takaba Company',
      company_name_ja: 'タカバカンパニー',
      occupation: 'offer',
      occupation_ja: 'オファー',
      to_link: 'interview',
    },
    {
      id: 8,
      date: '2023-08-24',
      dateJa: '2023年08月24日 (Thu)',
      id_hrs: 14,
      full_name: 'Dinh',
      full_name_ja: 'ディン',
      id_job: 31,
      job_name: 'IT engineer',
      company_name: 'Takaba Company',
      company_name_ja: 'タカバカンパニー',
      occupation: 'offer',
      occupation_ja: 'オファー',
      to_link: 'interview',
    },
    {
      id: 7,
      date: '2023-08-24',
      dateJa: '2023年08月24日 (Thu)',
      id_hrs: 14,
      full_name: 'Dinh',
      full_name_ja: 'ディン',
      id_job: 32,
      job_name: 'IT engineer',
      company_name: 'Takaba Company',
      company_name_ja: 'タカバカンパニー',
      occupation: 'offer',
      occupation_ja: 'オファー',
      to_link: 'interview',
    },
    {
      id: 19,
      date: '2023-08-24',
      dateJa: '2023年08月24日 (Thu)',
      id_hrs: 28,
      full_name: 'Dinh',
      full_name_ja: 'ディン',
      id_job: 59,
      job_name: 'IT engineer',
      company_name: 'Takaba Company',
      company_name_ja: 'タカバカンパニー',
      occupation: 'entry',
      occupation_ja: 'エントリー',
      to_link: 'interview',
    },
  ];

  const table_on_going_job_fields = [
    {
      key: 'date',
      sortable: false,
      label: '日時',
      class: 'date',
      thStyle: {
        width: '152px',
        // width: '18%',
        textAlign: 'center',
        backgroundColor: '#F0ECFF',
        color: '#69609C',
        fontSize: '14px',
      },
    },
    // {
    //   key: 'occupation',
    //   sortable: false,
    //   label: '種類',
    //   class: 'occupation',
    //   thStyle: {
    //     width: '10%',
    //     textAlign: 'center',
    //     backgroundColor: '#F0ECFF',
    //     color: '#69609C',
    //     fontSize: '14px',
    //   },
    // },
    {
      key: 'name',
      sortable: false,
      label: '氏名',
      class: 'name',
      thStyle: {
        // width: '20%',
        width: '208px',
        textAlign: 'center',
        backgroundColor: '#F0ECFF',
        color: '#69609C',
        fontSize: '14px',
      },
    },
    {
      key: 'job_name',
      sortable: false,
      label: '求人名  ',
      class: 'job_name',
      thStyle: {
        // width: '20%',
        width: '316px',
        textAlign: 'center',
        backgroundColor: '#F0ECFF',
        color: '#69609C',
        fontSize: '14px',
      },
    },
    {
      key: 'company_name',
      sortable: false,
      label: '企業名',
      class: 'company_name',
      thStyle: {
        // width: '25%',
        width: '288px',
        textAlign: 'center',
        backgroundColor: '#F0ECFF',
        color: '#69609C',
        fontSize: '14px',
      },
    },
    {
      key: 'detail',
      sortable: false,
      label: 'BUTTON.DETAIL',
      class: 'detail',
      thStyle: {
        // width: '7%',
        width: '76px',
        textAlign: 'center',
        backgroundColor: '#F0ECFF',
        color: '#69609C',
        fontSize: '14px',
      },
      tdClass: 'text-center',
    },
  ];

  const localVue = createLocalVue();

  let table_on_going_job_items_api = []; // !!!

  test('Case 1: Check render on Going Job Template', async() => {
    const getFuction = jest.fn();
    const wrapper = shallowMount(newMsglistOop, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
      created() {
        getFuction();
      },
    });

    expect(typeof HomeOop.data).toBe('function');

    const newMsglistTemplate = wrapper.findComponent({ name: 'Home' });
    expect(newMsglistTemplate.exists()).toBe(true);

    // wrapper.destroy();
  });

  test('Case 2: render properly compoent going on job', async() => {
    // const getFuction = jest.fn();
    const wrapper = shallowMount(newMsglistOop, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
      // created() {
      //   getFuction();
      // },
    });

    await wrapper.setData({ table_on_going_job_items: listGoingOnJob });
    expect(wrapper.vm.table_on_going_job_items).toBe(listGoingOnJob);
    // await expect(wrapper).toMatchSnapshot();

    const newMsgPage = wrapper.find('.display-user-management-list');
    expect(newMsgPage.exists()).toBe(true);

    // wrapper.destroy();
  });

  test('Case 3: code 200 list message going on job', async() => {
    const getFuction = jest.fn();
    const wrapper = shallowMount(newMsglistOop, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
      created() {
        getFuction();
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
        expect(store.getters.token).toBe(TOKEN);
        //
      }
    });
    // expect(wrapper).toMatchSnapshot();
    const table_on_going_job_pagination = {
      current_page: 1,
      total_rows: 0, // null
      per_page: 5,
    };
    const paramsListGoingJob = {
      page: table_on_going_job_pagination.current_page,
      per_page: table_on_going_job_pagination.per_page,
    };
    const response = await listGoingJob(paramsListGoingJob);
    const { code } = response.data;
    expect(code).toBe(200);
    const { result, pagination } = response.data.data;
    table_on_going_job_pagination.total_rows = pagination.total_records;

    // console.log('Case 3: result: ', result);
    // Test length with pagination

    // wrapper.destroy();
  });

  test('Case 4: navigates to the hr detail screen when click hr name text', async() => {
    const getFuction = jest.fn();
    const wrapper = shallowMount(newMsglistOop, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
      created() {
        getFuction();
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
        expect(store.getters.token).toBe(TOKEN);
        //
      }
    });

    const response = await listGoingJob();
    const { code } = response.data;
    expect(code).toBe(200);
    const result = response.data.data.result;
    table_on_going_job_items_api = result.map((item) => {
      return {
        date: item.dateJa,
        occupation: item.occupation,
        occupation_ja: item.occupation_ja,
        name: item.full_name,
        name_ja: item.full_name_ja,
        job_name: item.job_name,
        company_name: item.company_name_ja,
        id_hrs: item.id_hrs,
        id_job: item.id_job,
        to_link: item.to_link,
      };
    });

    console.log('Case 4: result', table_on_going_job_items_api);

    await wrapper.setData({ table_on_going_job_items: table_on_going_job_items_api });
    expect(wrapper.vm.table_on_going_job_items).toBe(table_on_going_job_items_api);

    // await expect(wrapper).toMatchSnapshot(); //

    const tableOnGoingJob = wrapper.find('#on-going-job-table');

    // check field
    for (let field = 0; field < table_on_going_job_fields.length; field++) {
      expect(tableOnGoingJob.props('fields')[field].key).toEqual(table_on_going_job_fields[field].key);
      expect(tableOnGoingJob.props('fields')[field].sortable).toEqual(table_on_going_job_fields[field].sortable);
      expect(tableOnGoingJob.props('fields')[field].label).toEqual(table_on_going_job_fields[field].label);
      expect(tableOnGoingJob.props('fields')[field].class).toEqual(table_on_going_job_fields[field].class);
      expect(tableOnGoingJob.props('fields')[field].thStyle).toEqual(table_on_going_job_fields[field].thStyle);
    }

    // check data
    for (let item = 0; item < table_on_going_job_items_api.length; item++) {
      expect(tableOnGoingJob.props('items')[item].id).toEqual(table_on_going_job_items_api[item].id);

      // expect(tableOnGoingJob.props('items')[item].date).toEqual(table_on_going_job_fields[item].dateJa);
      //
      // expect(tableOnGoingJob.props('items')[item].occupation).toEqual(table_on_going_job_fields[item].occupation);
      //       expect(tableOnGoingJob.props('items')[item].occupation_ja).toEqual(table_on_going_job_fields[item].occupation_ja);
      //
      //       expect(tableOnGoingJob.props('items')[item].name).toEqual(table_on_going_job_fields[item].full_name);
      //       expect(tableOnGoingJob.props('items')[item].name_ja).toEqual(table_on_going_job_fields[item].full_name_ja);
      // 4 求人名 job name
      //       expect(tableOnGoingJob.props('items')[item].job_name).toEqual(table_on_going_job_fields[item].job_name);
      //
      // expect(tableOnGoingJob.props('items')[item].company_name).toEqual(table_on_going_job_fields[item].company_name_ja);
      //
      // expect(tableOnGoingJob.props('items')[item].id_hrs).toEqual(table_on_going_job_fields[item].id_hrs);
      // expect(tableOnGoingJob.props('items')[item].id_job).toEqual(table_on_going_job_fields[item].id_job);
      // 6 詳細 Detail
      //       expect(tableOnGoingJob.props('items')[item].to_link).toEqual(table_on_going_job_fields[item].to_link);
    }
    // wrapper.destroy();

    // check component internal
    // const TABLE_BODY = tableOnGoingJob.find('tbody');
    // expect(TABLE_BODY.exists()).toBe(true);
  });

  test('Case 5: navigates to the job detail screen when click job name text', () => {
    const getFuction = jest.fn();
    const wrapper = shallowMount(newMsglistOop, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
      created() {
        getFuction();
      },
    });

    // wrapper.destroy();
  });

  test('Case 6: when list going on job less than 6 item text show more message been hide', async() => {
    const getFuction = jest.fn();
    const wrapper = shallowMount(newMsglistOop, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
      created() {
        // getFuction();
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
        expect(store.getters.token).toBe(TOKEN);
        //
      }
    });

    if (wrapper.vm.table_on_going_job_pagination.total_rows < 5) {
      console.log('More 5 item');
      const viewMoreOngoingProjects = wrapper.find('#view-more-ongoing-projects');
      expect(viewMoreOngoingProjects.exists()).toBe(false);
    }

    // wrapper.destroy();
  });

  test('Case 7: navigates to the job going on job show more screen when list have more 6 item', async() => {
    // const getFuction = jest.fn();
    const wrapper = shallowMount(newMsglistOop, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
      created() {
        // getFuction();
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
        expect(store.getters.token).toBe(TOKEN);
        //
      }
    });

    if (wrapper.vm.table_on_going_job_pagination.total_rows > 5) {
      console.log('More 5 item');
      const viewMoreOngoingProjects = wrapper.find('#view-more-ongoing-projects');
      expect(viewMoreOngoingProjects.exists()).toBe(true);
    }

    // wrapper.destroy();
  });

  //
});

