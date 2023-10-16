/* eslint-disable no-unused-vars */
import { createLocalVue, shallowMount, mount } from '@vue/test-utils';
// Phần login
import store from '@/store';
import router from '@/router';
import { login } from '@/api/auth';
import { handleRole } from '@/utils/handleRole';
// Common
import ROLE from '@/const/role.js';
import { PAGINATION_CONSTANT } from '@/const/config.js';
// Import File
import InterviewOop from '@/pages/MatchingManagement/Tab/Interview.vue';
// API
import { getListInterview } from '@/api/modules/matching.js';

describe('Test component MatchingManagement Tab Entry', () => {
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

  const paginationInterview = {
    current_page: 1,
    per_page: PAGINATION_CONSTANT.DEFALT_PER_PAGE,
    total_records: 0,
    sort_by: '',
    sort_type: '',
  };

  const paramsAdmin = {
    mail_address: '1okuridashi_hanoi@gmail.vn',
    password: '123456789CCk',
  };

  const paramsCompanyManage = {
    mail_address: '2okuridashi_hanoi@gmail.vn',
    password: '123456789CCk',
  };

  const paramsHrManage = {
    mail_address: '3okuridashi_hanoi@gmail.vn',
    password: '123456789CCk',
  };

  const paramsCompany = {
    mail_address: '4okuridashi_hanoi@gmail.vn',
    password: '123456789CCk',
  };

  const paramsHr = {
    mail_address: '5okuridashi_hanoi@gmail.vn',
    password: '123456789CCk',
  };

  // Role
  // const permissionCheck = store.getters.permissionCheck; //
  // Entry / Offer
  // [ROLE.TYPE_HR, ROLE.TYPE_HR_MANAGER].includes(permissionCheck)
  // handleModalRequestingConfirm
  // handleModalRequestingReject

  // [ROLE.TYPE_HR, ROLE.TYPE_HR_MANAGER].includes(permissionCheck)
  // handleModalRequestingDecline;

  // beforeAdjustment.vue
  const ROLEbeforeAdjustment = store.getters.profile.type;
  const listRolebeforeAdjustment = [1, 2, 4];
  // adjusting.vue
  const ROLEAdjusting = store.getters.profile.type;
  const listRoleAdjusting = [1, 3, 5];
  //  urlSetting.vue
  const RoleUrlSetting = store.getters.profile.type;
  const listRoleUrlSetting = [1, 3];
  // adjusted.vue
  const RoleAdjusted = store.getters.profile.type;
  const listRoleAdjusted = [1, 2, 4];
  // listRole.includes(ROLE);

  const localVue = createLocalVue();
  // console.log('Test second pass');
  //
  test('Case 1: Check render Tab Interview Template', async() => {
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
      data() {
        return {
        // state: 'state,
        };
      },
    });

    expect(typeof InterviewOop.data).toBe('function');
    const tabEntryTemplate = wrapper.findComponent({ name: 'Interview' });
    expect(tabEntryTemplate.exists()).toBe(true);

    await wrapper.setProps({ dataInterview: dataInterviewFix });
    expect(wrapper.vm.dataInterview).toEqual(dataInterviewFix);

    await wrapper.setData({ itemsInterview: dataInterviewFix });
    expect(wrapper.vm.itemsInterview).toEqual(dataInterviewFix);

    await wrapper.setProps({ pagination: paginationInterview });
    expect(wrapper.vm.pagination).toEqual(paginationInterview);

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

    await wrapper.setProps({ dataInterview: dataInterviewFix });
    expect(wrapper.vm.dataInterview).toEqual(dataInterviewFix);

    await wrapper.setData({ itemsInterview: dataInterviewFix });
    expect(wrapper.vm.itemsInterview).toEqual(dataInterviewFix);

    await wrapper.setProps({ pagination: paginationInterview });
    expect(wrapper.vm.pagination).toEqual(paginationInterview);

    // expect(wrapper).toMatchSnapshot(); //

    const table_interview = wrapper.find('#interview-table');
    expect(table_interview.exists()).toBe(true);
  });

  test('Case 3: Get list Interview have data first pass', async() => {
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
      data() {
        return {
          // state: 'state,
        };
      },
    });
    //

    await wrapper.setProps({ dataInterview: dataInterviewFix });
    expect(wrapper.vm.dataInterview).toEqual(dataInterviewFix);

    await wrapper.setData({ itemsInterview: dataInterviewFix });
    expect(wrapper.vm.itemsInterview).toEqual(dataInterviewFix);

    await wrapper.setProps({ pagination: paginationInterview });
    expect(wrapper.vm.pagination).toEqual(paginationInterview);

    //
    const runTestCaseWithRole = async(params) => {
      const resLogin = await login(params);
      if (resLogin['data']['code'] === 200) {
        const TOKEN = resLogin['data']['data']['access_token'];
        const USER = resLogin['data']['data']['profile'];

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
      console.log('Case 3: resLogin', resLogin);
      expect(resLogin['data']['code']).toBe(dataInterviewFix);

      const permissionCheck = store.getters.permissionCheck; //
      console.log(`case 3 Role = ${permissionCheck}`);

      const response = await getListInterview();
      const { code, data } = response.data;
      expect(code).toBe(200);
      console.log('case 3: getListInterview code :', code);
      console.log('case 3: getListInterview data :', data);

      console.log('Case 3: run function end');
    };

    await runTestCaseWithRole(paramsAdmin);
  });
  //
});
// function runTestCaseWithRole() {
// };
// runTestCaseWithRole();
