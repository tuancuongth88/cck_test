/* eslint-disable no-unused-vars */
import { createLocalVue, shallowMount, mount } from '@vue/test-utils';
import store from '@/store';
import router from '@/router';
import ROLE from '@/const/role.js';

// import Oop from '@/pages/MatchingManagement/Tab/offer.vue';
import InterviewOop from '@/pages/MatchingManagement/Tab/Interview.vue';
// import MatchingManagement from '@/pages/MatchingManagement/index';
import BeforeAdjustMentOop from '@/pages/MatchingManagement/InterviewControl/beforeAdjustment.vue';

import { login } from '@/api/auth';
import { handleRole } from '@/utils/handleRole';
import { getListInterview } from '@/api/modules/matching.js';
import { putSetupCalender } from '@/api/modules/matching.js';

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
  const dataInterviewFix = [
    {
      id: 24,
      entry_code: 'E00000005',
      interview_date: '20230829',
      hr_id: 14,
      full_name: 'Dinh',
      full_name_ja: 'ディン',
      work_id: 32,
      job_name: 'IT engineer',
      updating_date: '20230814',
      status_selection: 1,
      status_selection_name: '書類通過',
      status_interview_adjustment: 1,
      status_interview_adjustment_name: '調整前',
      _isSelected: false,
    },
    {
      id: 23,
      entry_code: 'E00000004',
      interview_date: '20230828',
      hr_id: 14,
      full_name: 'Dinh',
      full_name_ja: 'ディン',
      work_id: 31,
      job_name: 'IT engineer',
      updating_date: '20230814',
      status_selection: 1,
      status_selection_name: '書類通過',
      status_interview_adjustment: 1,
      status_interview_adjustment_name: '調整前',
      _isSelected: false,
    },
    {
      id: 5,
      entry_code: 'E00000003',
      interview_date: '20230823',
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
      _isSelected: false,
    },
  ];

  // 3
  const paginationInterview = {
    current_page: 1,
    per_page: 20,
    total_records: 0,
    sort_by: '',
    sort_type: '',
  };

  const arr_interview_detail_document_passing_before_adjustment_public = [];

  const localVue = createLocalVue();

  test('Case 1: Check render Tab Interview Template', async() => {
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

  test('Case 2: Check render component Matching management tab Interview', async() => {
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

    await wrapper.setProps({ dataInterview: dataInterviewFix });
    expect(wrapper.vm.dataInterview).toEqual(dataInterviewFix);

    await wrapper.setProps({ pagination: paginationInterview });
    expect(wrapper.vm.pagination).toEqual(paginationInterview);

    // expect(wrapper).toMatchSnapshot(); //

    const table_interview = wrapper.find('#interview-table');
    expect(table_interview.exists()).toBe(true);

    // const btnCheckbox = table_interview.find('#checkbox-interview');
    // expect(btnCheckbox.exists()).toBe(true);
    // const TABLE_BODY = table_interview.find('tbody');
    // expect(TABLE_BODY.exists()).toBe(true);

    // wrapper.destroy();

    //
  });

  test('Case 3: Check API tab Interview respone code 200 has data Document passing', async() => {
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
        expect(store.getters.token).toBe(TOKEN);
        //
      }
    });

    await wrapper.setProps({ pagination: paginationInterview });
    expect(wrapper.vm.pagination).toEqual(paginationInterview);

    const response = await getListInterview();
    const { code, data } = response.data;
    console.log('case 3: getListInterview code :', code);
    expect(code).toBe(200);

    // wrapper.destroy();
  });

  test('Case 4: Check Interviewtab render with data Document passing correct', async() => {
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
        expect(store.getters.token).toBe(TOKEN);
        //
      }
    });

    await wrapper.setProps({ pagination: paginationInterview });
    expect(wrapper.vm.pagination).toEqual(paginationInterview);

    let dataInterviewApi = [];
    const response = await getListInterview();
    const { code, data } = response.data;
    console.log('case 3: getListInterview code :', code);
    expect(code).toBe(200);
    const { result } = data;
    dataInterviewApi = result.map((item) => {
      return {
        ...item,
        _isSelected: false,
      };
    });

    await wrapper.setProps({ dataInterview: dataInterviewApi });
    expect(wrapper.vm.dataInterview).toEqual(dataInterviewApi);

    // console.log('dataInterview: ', dataInterview);

    // expect(wrapper).toMatchSnapshot(); //

    const tableInterview = wrapper.find('#interview-table');
    expect(tableInterview.exists()).toBe(true);

    for (let item = 0; item < dataInterviewApi.length; item++) {
      expect(tableInterview.props('items')[item].id).toEqual(dataInterviewApi[item].id);

      expect(tableInterview.props('items')[item].entry_code).toEqual(dataInterviewApi[item].entry_code);
      expect(tableInterview.props('items')[item].interview_date).toEqual(dataInterviewApi[item].interview_date);
      expect(tableInterview.props('items')[item].hr_id).toEqual(dataInterviewApi[item].hr_id);

      expect(tableInterview.props('items')[item].full_name).toEqual(dataInterviewApi[item].full_name);
      expect(tableInterview.props('items')[item].full_name_ja).toEqual(dataInterviewApi[item].full_name_ja);
      expect(tableInterview.props('items')[item].work_id).toEqual(dataInterviewApi[item].work_id);
      expect(tableInterview.props('items')[item].job_name).toEqual(dataInterviewApi[item].job_name);
      expect(tableInterview.props('items')[item].updating_date).toEqual(dataInterviewApi[item].updating_date);
      expect(tableInterview.props('items')[item].status_selection).toEqual(dataInterviewApi[item].status_selection);
      expect(tableInterview.props('items')[item].status_selection_name).toEqual(dataInterviewApi[item].status_selection_name);
      expect(tableInterview.props('items')[item].status_interview_adjustment).toEqual(dataInterviewApi[item].status_interview_adjustment);
      expect(tableInterview.props('items')[item].status_interview_adjustment_name).toEqual(dataInterviewApi[item].status_interview_adjustment_name);
    }

    // check component internal
    const TABLE_BODY = tableInterview.find('tbody');
    expect(TABLE_BODY.exists()).toBe(true);

    const TABLE_BODY_TR = TABLE_BODY.findAll('tr');
    expect(TABLE_BODY_TR.length).toEqual(dataInterviewApi.length);

    for (let tr = 0; tr < TABLE_BODY_TR.length; tr++) { // find col
      const LIST_TD = TABLE_BODY_TR.at(tr).findAll('td');

      expect(LIST_TD.at(0).find('input[type="checkbox"]').exists()).toBe(true);
      expect(LIST_TD.at(0).find('input[type="checkbox"]').element.checked).toBe(false); // document passing

      expect(LIST_TD.at(1).text()).toEqual(String(dataInterviewApi[tr].id));
      // expect(LIST_TD.at(2).text()).toEqual(String(dataInterviewApi[tr].entry_code));

      expect(LIST_TD.at(6).text()).toEqual(String(dataInterviewApi[tr].status_selection_name));
      expect(LIST_TD.at(7).text()).toEqual(String(dataInterviewApi[tr].status_interview_adjustment_name));

      // col 6 status_selection_name
      // col 7 status_interview_adjustment_name
      // console.log('LIST_TD.at(6).text()', LIST_TD.at(6).text());
      // console.log('LIST_TD.at(7).text()', LIST_TD.at(7).text());

      const btnShowDetail = LIST_TD.at(8).findAll('.btn-go-detail');
      expect(btnShowDetail.exists()).toBe(true);

      expect(btnShowDetail.length).toBe(1);
      const goToDetail = jest.spyOn(wrapper.vm, 'goToDetail');

      // await btnShowDetail.trigger('click');
      goToDetail(dataInterviewApi[tr]);
      expect(goToDetail).toHaveBeenCalledWith(dataInterviewApi[tr]);
    }

    // await wrapperBeforeAdjustMentOop.setData({ step: 1 });

    // wrapper.destroy();
  });

  test('Case 5: Check modal Interview first passing works properly', async() => {
    const wrapper = mount(BeforeAdjustMentOop, {
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
        expect(store.getters.token).toBe(TOKEN);
        //
      }
    });

    // const roleCheck = jest.spyOn(wrapper.vm, 'roleCheck');
    // roleCheck();
    const listRole = [1, 2, 4];

    const role = store.getters.profile.type;
    if (listRole.includes(role)) {
      await wrapper.setData({ roleCheck: true });
    }
    expect(wrapper.vm.roleCheck).toEqual(true);

    await wrapper.setData({ step: 1 });
    expect(wrapper.vm.step).toEqual(1);

    await wrapper.setData({ detail_data: 1 });
    // expect(wrapper.vm.step).toEqual(1);

    const tableScheduleInterview = wrapper.find('#table-calendar-temp');
    expect(tableScheduleInterview.exists()).toBe(true);

    const handleBack = jest.spyOn(wrapper.vm, 'handleBack');

    const btnHandleBback = wrapper.find('#btn-handle-back');
    expect(btnHandleBback.exists()).toBe(true);

    handleBack();
    await btnHandleBback.trigger('click');
    expect(handleBack).toHaveBeenCalled();

    const handleNextStep = jest.spyOn(wrapper.vm, 'handleNextStep');

    const btnHandleNextStep = wrapper.find('#btn-handle-next-step');
    expect(btnHandleNextStep.exists()).toBe(true);

    handleNextStep();
    await btnHandleNextStep.trigger('click');
    expect(handleNextStep).toHaveBeenCalledWith();

    // wrapper.destroy();
  });

  test('Case 6: Check Process from document passing - before adjustment to First pass - before adjustment send Setup Calender', async() => {
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
        expect(store.getters.token).toBe(TOKEN);
        //
      }
    });

    await wrapper.setProps({ pagination: paginationInterview });
    expect(wrapper.vm.pagination).toEqual(paginationInterview);

    // API Interview
    let dataInterviewApi = [];
    const response = await getListInterview();
    const { code, data } = response.data;
    // console.log('case 4: getListInterview code :', code);
    expect(code).toBe(200);
    const { result } = data;
    dataInterviewApi = result.map((item) => {
      return {
        ...item,
        _isSelected: false,
      };
    });

    dataInterviewApi.map(item => {
      if (
        item.status_interview_adjustment === 1 && item.status_selection === 1 ||
            item.status_interview_adjustment === 1 && item.status_selection === 2
      ) {
        console.log('dataInterviewApi item: ', item);
        arr_interview_detail_document_passing_before_adjustment_public.push(item);
      }
    });

    const data_document_passing_before_adjustment_detail = {
      id: 1,
      entry_code: null,
      type_code: 1,
      type_name: '集団面接',
      interview_from: 'offer',
      interview_date: '20230830',
      hr_id: 3,
      full_name: 'Nawa',
      full_name_ja: 'ナワ',
      work_id: 1,
      job_name: 'JAPANESE RESTAURANT SERVER',
      updating_date: '2023-08-25',
      updating_date_ja: '2023年08月25日 (Fri)',
      status_selection: 1,
      status_selection_name: '書類通過',
      status_interview_adjustment: 1,
      status_interview_adjustment_name: '調整前', //
      display: 'on',
      calendarTemporary: [],
      calendar: [
        {
          id: 1,
          type_code: 2,
          type_name: '個人面接',
          number: 1,
          number_en: 'INTERVIEW_ROUND_ONE',
          number_ja: '一次面接',
          interview_id: 1,
          status: 8,
          status_en: 'COMPANY_OFFER',
          status_ja: '内定',
          url_zoom: 'a',
          id_zoom: 'a',
          password_zoom: 'a',
          remark: null,
          calendarInterview: [
            {
              date: '2023-08-30',
              start_time: '12:00',
              end_time: '12:30',
              expected_time: '30',
              at_time: 'AM',
              weekdays: 'Wed',
              timeJa: '2023年08月30日 (Wed) 12:00~12:30',
              timeJaAP: '2023年08月30日 (Wed) 午後12時~午後12時',
            },
          ],
        },
      ],
      candidateList: [],
      optionSelectRound: {
        round_current_number: 1,
        round_current_option: {
          round_number: 1,
          round_text_ja: '一次面接へ',
          round_text_en: 'Move to first interview',
          round_name_current: '一次面接',
        },
        round_next_number: 2,
        round_next_option: {
          round_number: 2,
          round_text_ja: '二次面接へ',
          round_text_en: 'Move to second interview',
          round_name_current: '二次面接',
        },
        round_select_number: [
          2,
          3,
          4,
          5,
        ],
        round_select_option: [
          {
            round_number: 2,
            round_text_ja: '二次面接へ',
            round_text_en: 'Move to second interview',
            round_name_current: '二次面接',
          },
          {
            round_number: 3,
            round_text_ja: '三次面接へ',
            round_text_en: 'Move to third interview',
            round_name_current: '三次面接',
          },
          {
            round_number: 4,
            round_text_ja: '四次合格へ',
            round_text_en: 'Move to fourth interview',
            round_name_current: '四次面接',
          },
          {
            round_number: 5,
            round_text_ja: '最終面接へ',
            round_text_en: 'Move to final interview',
            round_name_current: '最終面接',
          },
        ],
        round_final_number: 5,
        round_final_option: {
          round_number: 5,
          round_text_ja: '最終面接へ',
          round_text_en: 'Move to final interview',
          round_name_current: '最終面接',
        },
        round_option_number: [
          2,
          5,
        ],
        round_option_inteview: [
          {
            round_number: 2,
            round_text_ja: '二次面接へ',
            round_text_en: 'Move to second interview',
            round_name_current: '二次面接',
          },
          {
            round_number: 5,
            round_text_ja: '最終面接へ',
            round_text_en: 'Move to final interview',
            round_name_current: '最終面接',
          },
        ],
      },
    };

    if (arr_interview_detail_document_passing_before_adjustment_public.length > 0) {
      const data = arr_interview_detail_document_passing_before_adjustment_public[0];
      console.log('case 7: data[0]', data);

      const paramsPutSetupCalender = {
        id: data.id, // id item interview index = 0
        calender: {
          interview_type: 2,
          times: [
            {
              date: '2023-09-07',
              start_time: '12:00',
              start_time_at: 'AM',
              expected_time: '30',
            },
            {
              date: '2023-08-31',
              start_time: '12:00',
              start_time_at: 'AM',
              expected_time: '30',
            },
            {
              date: '2023-09-07',
              start_time: '12:00',
              start_time_at: 'AM',
              expected_time: '30',
            },
            {
              date: '2023-09-07',
              start_time: '12:00',
              start_time_at: 'AM',
              expected_time: '30',
            },
            {
              date: '2023-09-07',
              start_time: '12:00',
              start_time_at: 'AM',
              expected_time: '30',
            },
          ],
        },
      };

      const resPutSetupCalender = await putSetupCalender(paramsPutSetupCalender);
      const codePutSetupCalender = resPutSetupCalender.data.code;
      expect(codePutSetupCalender).toBe(200);
      console.log('Case 6: putSetupCalender res: ', resPutSetupCalender);
    }

    // wrapper.destroy();
    //
  });
  //
});
