/* eslint-disable no-unused-vars */
import { createLocalVue, shallowMount, mount } from '@vue/test-utils';
import store from '@/store';
import router from '@/router';
import ROLE from '@/const/role.js';

// import Oop from '@/pages/MatchingManagement/Tab/offer.vue';
import InterviewOop from '@/pages/MatchingManagement/Tab/Interview.vue';
import MatchingManagement from '@/pages/MatchingManagement/index';

import { getListInterview } from '@/api/modules/matching.js';

import { login } from '@/api/auth';
import { handleRole } from '@/utils/handleRole';

describe('Test component MatchingManagement Tab Entry', () => {
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
  const dataInterview = [
    {
      id: 22,
      entry_code: null,
      interview_date: '20230831',
      hr_id: 14,
      full_name: 'Dinh',
      full_name_ja: 'ディン',
      work_id: 32,
      job_name: 'IT engineer',
      updating_date: '20230814',
      status_selection: 2,
      status_selection_name: 'オファー承認',
      status_interview_adjustment: 4,
      status_interview_adjustment_name: '調整済',
    },
    {
      id: 21,
      entry_code: null,
      interview_date: '20230824',
      hr_id: 14,
      full_name: 'Dinh',
      full_name_ja: 'ディン',
      work_id: 31,
      job_name: 'IT engineer',
      updating_date: '20230814',
      status_selection: 2,
      status_selection_name: 'オファー承認',
      status_interview_adjustment: 2,
      status_interview_adjustment_name: '調整中',
    },
    {
      id: 20,
      entry_code: 'E00000005',
      interview_date: '20230824',
      hr_id: 28,
      full_name: 'Dinh',
      full_name_ja: 'ディン',
      work_id: 59,
      job_name: 'IT engineer',
      updating_date: '20230814',
      status_selection: 1,
      status_selection_name: '書類通過',
      status_interview_adjustment: 1,
      status_interview_adjustment_name: '調整前',
    },
    {
      id: 7,
      entry_code: 'E00000003',
      interview_date: '20230823',
      hr_id: 7,
      full_name: 'Cuong',
      full_name_ja: 'クォン',
      work_id: 1,
      job_name: 'IT engineer',
      updating_date: '20230814',
      status_selection: 5,
      status_selection_name: '一次合格',
      status_interview_adjustment: 1,
      status_interview_adjustment_name: '調整前',
    },
    {
      id: 2,
      entry_code: 'E00000002',
      interview_date: '20230809',
      hr_id: 2,
      full_name: 'Kiet',
      full_name_ja: 'キエット',
      work_id: 1,
      job_name: 'IT engineer',
      updating_date: '20230814',
      status_selection: 1,
      status_selection_name: '書類通過',
      status_interview_adjustment: 1,
      status_interview_adjustment_name: '調整前',
    },
  ];
  const localVue = createLocalVue();

  test('Case 1: Check render Interview Template', async() => {
    const getFuction = jest.fn();
    const wrapper = shallowMount(InterviewOop, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
      created() {
        getFuction();
      },
      data() {
        return {
          // state: 'state,
        };
      },
    });

    expect(typeof InterviewOop.data).toBe('function');
    const tabEntryTemplate = wrapper.findComponent({ name: 'Interview' });
    expect(tabEntryTemplate.exists()).toBe(true);

    // wrapper.destroy();
  });

  test('Case 2: Check render component  Matching management tab Interview', async() => {
    // const getFuction = jest.fn();
    const wrapper = shallowMount(InterviewOop, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
      created() {
        // getFuction();
      },
      // data() {
      //   return {
      //     // state: 'state,
      //   };
      // },
    });

    const dataInterview1 = [
      {
        id: 23,
        entry_code: null,
        interview_date: '20230828',
        hr_id: 14,
        full_name: 'Dinh',
        full_name_ja: 'ディン',
        work_id: 31,
        job_name: 'IT engineer',
        updating_date: '20230814',
        status_selection: 2,
        status_selection_name: 'オファー承認',
        status_interview_adjustment: 1,
        status_interview_adjustment_name: '調整前',
      },
    ];

    await wrapper.setProps({ dataInterview: dataInterview });
    expect(wrapper.vm.dataInterview).toEqual(dataInterview);

    // expect(wrapper).toMatchSnapshot();

    const table_interview = wrapper.find('#interview-table');
    expect(table_interview.exists()).toBe(true);

    // const btnCheckbox = table_interview.find('#checkbox-interview');
    // expect(btnCheckbox.exists()).toBe(true);
    // const TABLE_BODY = table_interview.find('tbody');
    // expect(TABLE_BODY.exists()).toBe(true);

    // wrapper.destroy();

    //
  });

  test('Case 3: Check API tab Offer respone code 200', async() => {
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
        //
      }
    });

    const response = await getListInterview();
    const { code } = response.data;
    console.log('case 3: getListInterview :', response);
    expect(code).toBe(200);

    wrapper.destroy();
  });

  //
});
