/* eslint-disable no-unused-vars */
import { createLocalVue, shallowMount, mount } from '@vue/test-utils';
import store from '@/store';
import router from '@/router';
import ROLE from '@/const/role.js';

// import Oop from '@/pages/MatchingManagement/Tab/offer.vue';
import InterviewOop from '@/pages/MatchingManagement/Tab/Interview.vue';
// import MatchingManagement from '@/pages/MatchingManagement/index';
import BeforeAdjustMentOop from '@/pages/MatchingManagement/InterviewControl/beforeAdjustment.vue';
// import adjustedOop from '@/pages/MatchingManagement/InterviewControl/adjusted.vue';
import beforeAdjustmentOop from '@/pages/MatchingManagement/InterviewControl/beforeAdjustment.vue';

import { getListEntry } from '@/api/modules/matching.js';
import { getListOffer } from '@/api/modules/matching.js';
import { getListInterview } from '@/api/modules/matching.js';

import { deleteMultipleEntry, getDetailEntry, updateEntry } from '@/api/modules/matching.js';
import { deleteMultipleOffer, getDetailOffer, updateOffer } from '@/api/modules/matching.js';
import { putConfirmCalender, putDeclineInterview } from '@/api/modules/matching.js';

import { deleteMultipleInterview, getDetailInterview } from '@/api/modules/matching';
import { putSetupCalender } from '@/api/modules/matching.js';
import { putConfirmReview } from '@/api/modules/matching.js';

import { putSetupZoom } from '@/api/modules/matching.js';

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
  //
  const dataInterviewApiGlobal = [];

  const permissionCheck = null;
  const listRole = [1, 3, 5]; // 1, 3, 5 (Ad, HrO, HR)
  const listRoleSettingURL = [1, 3]; // (Ad, HrO)
  //
  const localVue = createLocalVue();

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

  // test('Case 3: Check get init role vs type account', async () => {
  //   // const getFuction = jest.fn();
  //   const wrapper = shallowMount(InterviewOop, {
  //     localVue,
  //     router,
  //     store,
  //     stubs: {
  //       BIcon: true,
  //     },
  //     created() {
  //       // getFuction();
  //     },
  //     data() {
  //       return {
  //         // state: 'state,
  //       };
  //     },
  //   });

  //   const params = {
  //     mail_address: '1okuridashi_hanoi@gmail.vn',
  //     password: '123456789CCk',
  //   };

  //   await login(params).then((response) => {
  //     if (response['data']['code'] === 200) {
  //       const TOKEN = response['data']['data']['access_token'];
  //       const USER = response['data']['data']['profile'];

  //       const { ROLES, PERMISSIONS } = handleRole([]);

  //       const USER_INFO = {
  //         token: TOKEN,
  //         profile: USER,
  //         roles: ROLES,
  //         permissions: PERMISSIONS,
  //       };

  //       store.dispatch('user/saveLogin', USER_INFO);
  //       expect(store.getters.token).toBe(TOKEN);
  //       //
  //     }
  //   });

  //   permissionCheck = store.getters.permissionCheck; //

  //   // wrapper.destroy();
  // });

  // test('Case 3: Check code 200 has data fifth passing', async () => {
  //   const wrapper = mount(InterviewOop, {
  //     localVue,
  //     router,
  //     store,
  //     stubs: {
  //       BIcon: true,
  //     },
  //   });

  //   const params = {
  //     mail_address: '1okuridashi_hanoi@gmail.vn',
  //     password: '123456789CCk',
  //   };

  //   await login(params).then((response) => {
  //     if (response['data']['code'] === 200) {
  //       const TOKEN = response['data']['data']['access_token'];
  //       const USER = response['data']['data']['profile'];

  //       const { ROLES, PERMISSIONS } = handleRole([]);

  //       const USER_INFO = {
  //         token: TOKEN,
  //         profile: USER,
  //         roles: ROLES,
  //         permissions: PERMISSIONS,
  //       };

  //       store.dispatch('user/saveLogin', USER_INFO);
  //       expect(store.getters.token).toBe(TOKEN);
  //       //
  //     }
  //   });

  //   await wrapper.setProps({ pagination: paginationInterview });
  //   expect(wrapper.vm.pagination).toEqual(paginationInterview);

  //   const response = await getListInterview();
  //   const { code, data } = response.data;
  //   // console.log('case 3: getListInterview code :', code);
  //   expect(code).toBe(200);

  //   // wrapper.destroy();
  // });

  // test('Case 4: Check render interview fifth passing - before adjustment data correct', async () => {
  //   const wrapper = mount(InterviewOop, {
  //     localVue,
  //     router,
  //     store,
  //     stubs: {
  //       BIcon: true,
  //     },
  //   });

  //   const params = {
  //     mail_address: '1okuridashi_hanoi@gmail.vn',
  //     password: '123456789CCk',
  //   };

  //   await login(params).then((response) => {
  //     if (response['data']['code'] === 200) {
  //       const TOKEN = response['data']['data']['access_token'];
  //       const USER = response['data']['data']['profile'];

  //       const { ROLES, PERMISSIONS } = handleRole([]);

  //       const USER_INFO = {
  //         token: TOKEN,
  //         profile: USER,
  //         roles: ROLES,
  //         permissions: PERMISSIONS,
  //       };

  //       store.dispatch('user/saveLogin', USER_INFO);
  //       expect(store.getters.token).toBe(TOKEN);
  //       //
  //     }
  //   });

  //   await wrapper.setProps({ pagination: paginationInterview });
  //   expect(wrapper.vm.pagination).toEqual(paginationInterview);

  //   // API Interview
  //   let dataInterviewApi = [];
  //   const response = await getListInterview();
  //   const { code, data } = response.data;
  //   // console.log('case 4: getListInterview code :', code);
  //   expect(code).toBe(200);
  //   const { result } = data;
  //   dataInterviewApi = result.map((item) => {
  //     return {
  //       ...item,
  //       _isSelected: false,
  //     };
  //   });

  //   const dataInterviewFirstPassing = {
  //     id: 8,
  //     entry_code: null,
  //     interview_date: '20230830',
  //     hr_id: 3,
  //     full_name: 'Nawa',
  //     full_name_ja: 'ナワ',
  //     work_id: 1,
  //     job_name: 'JAPANESE RESTAURANT SERVER',
  //     updating_date: '20230828',
  //     status_selection: 5,
  //     status_selection_name: '一次合格',
  //     status_interview_adjustment: 1,
  //     status_interview_adjustment_name: '調整前',
  //     _isSelected: false,
  //   };

  //   await wrapper.setProps({ dataInterview: dataInterviewApi });
  //   expect(wrapper.vm.dataInterview).toEqual(dataInterviewApi);

  //   // console.log('wrapper.vm.dataInterview: ', wrapper.vm.dataInterview);

  //   // expect(wrapper).toMatchSnapshot(); //

  //   if (dataInterviewApi.length > 0) {
  //     const tableInterview = wrapper.find('#interview-table');
  //     expect(tableInterview.exists()).toBe(true);

  //     // 1: Document passing, 2: Offer confirm, 3: First pass, 4: Second pass, 5: Third pass, 6: Fourth pass, 7: Fifth pass, 8: Interview cancel, 9: Interview decline
  //     // 1: Before adjustment, 2: Adjusting, 3: URL setting, 4: Adjusted

  //     // status_interview_adjustment: 1
  //     // status_interview_adjustment_name: "調整前"
  //     // status_selection: 5
  //     // status_selection_name: "一次合格"

  //     for (let item = 0; item < dataInterviewApi.length; item++) {
  //       expect(tableInterview.props('items')[item].id).toEqual(dataInterviewApi[item].id);
  //       expect(tableInterview.props('items')[item].entry_code).toEqual(dataInterviewApi[item].entry_code);
  //       expect(tableInterview.props('items')[item].interview_date).toEqual(dataInterviewApi[item].interview_date);
  //       expect(tableInterview.props('items')[item].hr_id).toEqual(dataInterviewApi[item].hr_id);

  //       expect(tableInterview.props('items')[item].full_name).toEqual(dataInterviewApi[item].full_name);
  //       expect(tableInterview.props('items')[item].full_name_ja).toEqual(dataInterviewApi[item].full_name_ja);
  //       expect(tableInterview.props('items')[item].work_id).toEqual(dataInterviewApi[item].work_id);
  //       expect(tableInterview.props('items')[item].job_name).toEqual(dataInterviewApi[item].job_name);
  //       expect(tableInterview.props('items')[item].updating_date).toEqual(dataInterviewApi[item].updating_date);
  //       expect(tableInterview.props('items')[item].status_selection).toEqual(dataInterviewApi[item].status_selection);
  //       expect(tableInterview.props('items')[item].status_selection_name).toEqual(dataInterviewApi[item].status_selection_name);
  //       expect(tableInterview.props('items')[item].status_interview_adjustment).toEqual(dataInterviewApi[item].status_interview_adjustment);
  //       expect(tableInterview.props('items')[item].status_interview_adjustment_name).toEqual(dataInterviewApi[item].status_interview_adjustment_name);
  //     }

  //     // check component internal
  //     const TABLE_HEADER = tableInterview.findAll('th');
  //     expect(TABLE_HEADER.length).toEqual(9);

  //     const TABLE_BODY = tableInterview.find('tbody');
  //     expect(TABLE_BODY.exists()).toBe(true);

  //     const TABLE_BODY_TR = TABLE_BODY.findAll('tr');
  //     // console.log('Case 4 chek data TABLE_BODY_TR: ', TABLE_BODY_TR.length);

  //     for (let tr = 0; tr < TABLE_BODY_TR.length; tr++) { // find col
  //       const LIST_TD = TABLE_BODY_TR.at(tr).findAll('td');

  //       expect(LIST_TD.at(0).find('input[type="checkbox"]').exists()).toBe(true);
  //       expect(LIST_TD.at(0).find('input[type="checkbox"]').element.checked).toBe(false); // document passing

  //       expect(LIST_TD.at(1).text()).toEqual(String(dataInterviewApi[tr].id));
  //       // expect(LIST_TD.at(2).text()).toEqual(String(dataInterviewApi[tr].entry_code));

  //       expect(LIST_TD.at(6).text()).toEqual(String(dataInterviewApi[tr].status_selection_name)); // !
  //       expect(LIST_TD.at(7).text()).toEqual(String(dataInterviewApi[tr].status_interview_adjustment_name));

  //       // col 6 status_selection_name
  //       // col 7 status_interview_adjustment_name
  //       // console.log('LIST_TD.at(6).text()', LIST_TD.at(6).text());
  //       // console.log('LIST_TD.at(7).text()', LIST_TD.at(7).text());

  //       const btnShowDetail = LIST_TD.at(8).findAll('.btn-go-detail');
  //       expect(btnShowDetail.exists()).toBe(true);

  //       expect(btnShowDetail.length).toBe(1);
  //       const goToDetail = jest.spyOn(wrapper.vm, 'goToDetail');

  //       // await btnShowDetail.trigger('click');
  //       goToDetail(dataInterviewApi[tr]);
  //       expect(goToDetail).toHaveBeenCalledWith(dataInterviewApi[tr]);
  //       const res = await getDetailInterview(dataInterviewApi[tr].id);
  //       const codeGetDetailInterview = res.data.code;
  //       expect(codeGetDetailInterview).toBe(200);
  //     }
  //   }

  //   // wrapper.destroy();
  // });

  // test('Case 4.1: Check API move to interview list', async () => {
  //   // const getFuction = jest.fn();
  //   const wrapper = shallowMount(InterviewOop, {
  //     localVue,
  //     router,
  //     store,
  //     stubs: {
  //       BIcon: true,
  //     },
  //     created() {
  //       // getFuction();
  //     },
  //     data() {
  //       return {
  //         // state: 'state,
  //       };
  //     },
  //   });

  //   await wrapper.setProps({ pagination: paginationInterview });
  //   expect(wrapper.vm.pagination).toEqual(paginationInterview);

  //   // List Entry
  //   const resGetListEntry = await getListEntry();
  //   const codeGetListEntry = resGetListEntry.data.code;
  //   const resultGetListEntry = resGetListEntry.data.data.result;

  //   expect(codeGetListEntry).toBe(200);
  //   console.log('Case 5: resultGetListEntry: ', resultGetListEntry);

  //   if (resultGetListEntry.length > 0) {
  //     // Entry Detail
  //     const responseGetDetailEntry = await getDetailEntry(resultGetListEntry[0].id);
  //     const codeDetailEntry = responseGetDetailEntry.data.code;
  //     expect(codeDetailEntry).toBe(200);
  //     // console.log('case 5: res getDetailEntry :', responseGetDetailEntry);

  //     // Entry confirm
  //     const paramsConfirm = {
  //       id: resultGetListEntry[0].id, // item được chọn
  //       status: 4, // Other company
  //     };

  //     const responseConfirm = await updateEntry(paramsConfirm);
  //     const codeEntryConfirm = responseConfirm.data.code;
  //     expect(codeEntryConfirm).toBe(200);
  //     console.log('case 5: code updateEntry: ', codeEntryConfirm);
  //   }

  //   // -----------------------------------------------------------------------
  //   // API List Offer
  //   const resGetListOffer = await getListOffer();
  //   const codeGetListOffer = resGetListOffer.data.code;
  //   expect(codeGetListOffer).toBe(200);

  //   const resultGetListOffer = resGetListOffer.data.data.result;
  //   console.log('case 5: res getListOffer :', resGetListOffer);

  //   if (resultGetListEntry.length > 0) {
  //     // Offer confirm
  //     const formDataOfferConfirm = {
  //       id: resultGetListOffer[0].id,
  //       status: 3, //
  //     };

  //     const responseOfferConfirm = await updateOffer(formDataOfferConfirm);
  //     const codeOfferConfirm = responseOfferConfirm.data.code;
  //     console.log('codeOfferConfirm', codeOfferConfirm);
  //     expect(codeOfferConfirm).toBe(200);
  //   }

  //   // wrapper.destroy();
  // });

  const dataInterviewApi = []; // !

  // test('Case 4.2: Check API List Interview', async () => {
  //   // const getFuction = jest.fn();
  //   const wrapper = shallowMount(InterviewOop, {
  //     localVue,
  //     router,
  //     store,
  //     stubs: {
  //       BIcon: true,
  //     },
  //     created() {
  //       // getFuction();
  //     },
  //     data() {
  //       return {
  //         // state: 'state,
  //       };
  //     },
  //   });

  //   const response = await getListInterview();
  //   const { code, data } = response.data;
  //   // console.log('case 4: getListInterview code :', code);
  //   expect(code).toBe(200);
  //   const { result } = data;
  //   dataInterviewApi = result.map((item) => {
  //     return {
  //       ...item,
  //       _isSelected: false,
  //     };
  //   });

  //   await wrapper.setData({ step: 1 });
  //   expect(wrapper.vm.step).toEqual(1);

  //   wrapper.destroy();
  // });

  test('Case 4.3: Check API document_pass & approval_before setup Calender status 1', async() => {
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

    const arr_document_pass_before = [];
    const arr_offer_approval_before = [];

    const typeStatus = 2; // personal interview
    const CALENDER = [
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
    ];

    // -----------------------------------------------------------------------
    dataInterviewApi.map(item => {
      if (item.status_interview_adjustment === 1 && item.status_selection === 1) { // 1
        arr_document_pass_before.push(item);
        console.log('status_interview_adjustment vs status_selection = 1');
      }
      if (item.status_interview_adjustment === 1 && item.status_selection === 2) { // 1
        arr_offer_approval_before.push(item);
      }
    });
    console.log('data arr_document_pass_before : ', arr_document_pass_before, arr_document_pass_before.length);
    console.log('data arr_offer_approval_before : ', arr_offer_approval_before, arr_offer_approval_before.length);

    // 1 DocumentPass Post Calender
    if (arr_document_pass_before.length > 0) {
      const firstDataDocumentPass = arr_document_pass_before[0];
      console.log('Case 6: firstDataDocumentPass: ', firstDataDocumentPass.id);

      const paramsDocumentPassSetupCalender = {
        id: firstDataDocumentPass.id, // id item interview index = 0
        interview_type: typeStatus,
        times: CALENDER,
      };
      const resDocumentPassSetupCalender = await putSetupCalender(paramsDocumentPassSetupCalender);
      const codeDocumentPassSetupCalender = resDocumentPassSetupCalender.data.code;
      expect(codeDocumentPassSetupCalender).toBe(200);
      console.log('Case 6: resDocumentPassSetupCalender: ', resDocumentPassSetupCalender);
    }

    // 2 offer approval Post Calender
    if (arr_offer_approval_before.length > 0) {
      const firstDataOfferApproval = arr_offer_approval_before[0];
      console.log('Case 6: firstDataOfferApproval: ', firstDataOfferApproval.id);

      const paramsOfferApprovalSetupCalender = {
        id: firstDataOfferApproval.id, // id item interview index = 0
        interview_type: typeStatus,
        times: CALENDER,
      };
      const resOfferApprovalSetupCalender = await putSetupCalender(paramsOfferApprovalSetupCalender);
      const codeOfferApprovalSetupCalender = resOfferApprovalSetupCalender.data.code;
      expect(codeOfferApprovalSetupCalender).toBe(200);
      console.log('Case 6: resOfferApprovalSetupCalender: ', resOfferApprovalSetupCalender);
    }

    // wrapper.destroy();
  });

  test('Case 4.4: Check API change status Under adjustment -> Setting URL status 2', async() => {
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

    if (listRole.includes(permissionCheck)) { // 1, 3, 5 (Ad, HrO, HR)
      const arr_document_pass_under = [];
      const arr_offer_approval_under = [];
      dataInterviewApi.map(item => {
        if (item.status_interview_adjustment === 2 && item.status_selection === 1) { // 2
          arr_document_pass_under.push(item);
        }
        if (item.status_interview_adjustment === 2 && item.status_selection === 2) { // 2
          arr_offer_approval_under.push(item);
        }
      });
      console.log('data arr_document_pass_under : ', arr_document_pass_under, arr_document_pass_under.length);
      console.log('data arr_offer_approval_under : ', arr_offer_approval_under, arr_offer_approval_under.length);

      if (arr_document_pass_under.length > 0) {
        const PARAMS = {
          id: arr_document_pass_under[0].id,
          time: 1, // option
        };
        const resPutConfirmCalender = await putConfirmCalender(PARAMS);
        const codePutConfirmCalender = resPutConfirmCalender.data.code;
        expect(codePutConfirmCalender).toBe(200);
        console.log('codePutConfirmCalender: ', codePutConfirmCalender);
      }

      if (arr_document_pass_under.length > 0) {
        const PARAMS = {
          id: arr_offer_approval_under[0].id,
          time: 1, // option
        };
        const resPutConfirmCalender = await putConfirmCalender(PARAMS);
        const codePutConfirmCalender = resPutConfirmCalender.data.code;
        expect(codePutConfirmCalender).toBe(200);
        console.log('codePutConfirmCalender: ', codePutConfirmCalender);
      }
    }

    wrapper.destroy();
  });

  const arr_document_pass_setting_url = [];
  const arr_offer_approval_setting_url = [];

  test('Case 4.5: Check API change status Setting URL -> First Pass', async() => {
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

    if (listRoleSettingURL.includes(permissionCheck)) { // (Ad, HrO)
      dataInterviewApi.map(item => {
        if (item.status_interview_adjustment === 5) {
          console.log('status Setting URL');
        }
        if (item.status_interview_adjustment === 5 && item.status_selection === 1) { // 3
          arr_document_pass_setting_url.push(item);
        }
        if (item.status_interview_adjustment === 5 && item.status_selection === 2) { // 3
          arr_offer_approval_setting_url.push(item);
        }
      });
      console.log('data arr_document_pass_setting_url : ', arr_document_pass_setting_url, arr_document_pass_setting_url.length);
      console.log('data arr_offer_approval_setting_url : ', arr_offer_approval_setting_url, arr_offer_approval_setting_url.length);

      if (arr_document_pass_setting_url.length > 0) {
        const PARAMS = {
          url_zoom: 'https://www.google.com.vn/',
          id_zoom: '123',
          password_zoom: '123',
          id: arr_document_pass_setting_url[0].id,
        };
        const resDocumentPassPutSetupZoom = await putSetupZoom(PARAMS);
        const codeDocumentPassPuPutSetupZoom = resDocumentPassPutSetupZoom.data.code;
        expect(codeDocumentPassPuPutSetupZoom).toBe(200);
        console.log('codeDocumentPassPuPutSetupZoom: ', codeDocumentPassPuPutSetupZoom);
      }

      if (arr_offer_approval_setting_url.length > 0) {
        const PARAMS = {
          url_zoom: 'https://www.google.com.vn/',
          id_zoom: '456',
          password_zoom: '456',
          id: arr_offer_approval_setting_url[0].id,
        };
        const resOfferApprovalPutSetupZoom = await putSetupZoom(PARAMS);
        const codeOfferApprovalPutSetupZoom = resOfferApprovalPutSetupZoom.data.code;
        expect(codeOfferApprovalPutSetupZoom).toBe(200);
        console.log('codeOfferApprovalPutSetupZoom: ', codeOfferApprovalPutSetupZoom);
      }
      // Adjusted Done ==> interview complete (pass, no, unoffical offer)
      dataInterviewApi.map(item => {
        expect(item.status_interview_adjustment).toBe(4); // Adjusted
        console.log('Case 5: Adjusted: ', item.status_interview_adjustment);
      });
    }

    wrapper.destroy();
  });

  test('Case 4.6: Check API change Status 2: Under adjustment -> Setting URL', async() => {
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

    if (listRole.includes(permissionCheck)) { // 1, 3, 5 (Ad, HrO, HR)
      const arr_document_pass_under = [];
      const arr_offer_approval_under = [];

      dataInterviewApi.map(item => {
        if (item.status_interview_adjustment === 2 && item.status_selection === 1) { // 2
          arr_document_pass_under.push(item);
        }
        if (item.status_interview_adjustment === 2 && item.status_selection === 2) { // 2
          arr_offer_approval_under.push(item);
        }
      });
      console.log('data arr_document_pass_under : ', arr_document_pass_under, arr_document_pass_under.length);
      console.log('data arr_offer_approval_under : ', arr_offer_approval_under, arr_offer_approval_under.length);

      if (arr_document_pass_under.length > 0) {
        const PARAMS = {
          id: arr_document_pass_under[0].id,
          time: 1, // option
        };
        const resPutConfirmCalender = await putConfirmCalender(PARAMS);
        const codePutConfirmCalender = resPutConfirmCalender.data.code;
        expect(codePutConfirmCalender).toBe(200);
        console.log('codePutConfirmCalender: ', codePutConfirmCalender);
      }

      if (arr_offer_approval_under.length > 0) {
        const PARAMS = {
          id: arr_offer_approval_under[0].id,
          time: 1, // option
        };
        const resPutConfirmCalender = await putConfirmCalender(PARAMS);
        const codePutConfirmCalender = resPutConfirmCalender.data.code;
        expect(codePutConfirmCalender).toBe(200);
        console.log('codePutConfirmCalender: ', codePutConfirmCalender);
      }
    }

    // wrapper.destroy();
  });

  // Init step
  test('Case 5: Check document passing - before adjustment Setup Calender ', async() => {
    const wrapper = mount(InterviewOop, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });
    console.log('Case 5 run');

    await wrapper.setProps({ pagination: paginationInterview });
    expect(wrapper.vm.pagination).toEqual(paginationInterview);
    // -----------------------------------------------------------------------

    // -----------------------------------------------------------------------
    // Status 2: Under -> Setting URL
    //     if (listRole.includes(permissionCheck)) { // 1, 3, 5 (Ad, HrO, HR)
    //       const arr_document_pass_under = [];
    //       const arr_offer_approval_under = [];
    //       dataInterviewApi.map(item => {
    //         if (item.status_interview_adjustment === 2 && item.status_selection === 1) { // 2
    //           arr_document_pass_under.push(item);
    //         }
    //         if (item.status_interview_adjustment === 2 && item.status_selection === 2) { // 2
    //           arr_offer_approval_under.push(item);
    //         }
    //       });
    //       console.log('data arr_document_pass_under : ', arr_document_pass_under, arr_document_pass_under.length);
    //       console.log('data arr_offer_approval_under : ', arr_offer_approval_under, arr_offer_approval_under.length);
    //
    //       if (arr_document_pass_under.length > 0) {
    //         const PARAMS = {
    //           id: arr_document_pass_under[0].id,
    //           time: 1, // option
    //         };
    //         const resPutConfirmCalender = await putConfirmCalender(PARAMS);
    //         const codePutConfirmCalender = resPutConfirmCalender.data.code;
    //         expect(codePutConfirmCalender).toBe(200);
    //         console.log('codePutConfirmCalender: ', codePutConfirmCalender);
    //       }
    //
    //       if (arr_document_pass_under.length > 0) {
    //         const PARAMS = {
    //           id: arr_offer_approval_under[0].id,
    //           time: 1, // option
    //         };
    //         const resPutConfirmCalender = await putConfirmCalender(PARAMS);
    //         const codePutConfirmCalender = resPutConfirmCalender.data.code;
    //         expect(codePutConfirmCalender).toBe(200);
    //         console.log('codePutConfirmCalender: ', codePutConfirmCalender);
    //       }
    //     }

    // -----------------------------------------------------------------------
    // Status 3:  Setting URL -> First Pass
    if (listRoleSettingURL.includes(permissionCheck)) { // (Ad, HrO)
      const arr_document_pass_setting_url = [];
      const arr_offer_approval_setting_url = [];
      //       dataInterviewApi.map(item => {
      //         if (item.status_interview_adjustment === 3 && item.status_selection === 1) { // 3
      //           arr_document_pass_setting_url.push(item);
      //         }
      //         if (item.status_interview_adjustment === 3 && item.status_selection === 2) { // 3
      //           arr_offer_approval_setting_url.push(item);
      //         }
      //       });
      //       console.log('data arr_document_pass_setting_url : ', arr_document_pass_setting_url, arr_document_pass_setting_url.length);
      //       console.log('data arr_offer_approval_setting_url : ', arr_offer_approval_setting_url, arr_offer_approval_setting_url.length);
      //
      //       if (arr_document_pass_setting_url.length > 0) {
      //         const PARAMS = {
      //           url_zoom: 'https://www.google.com.vn/',
      //           id_zoom: '123',
      //           password_zoom: '123',
      //           id: arr_document_pass_setting_url[0].id,
      //         };
      //         const resDocumentPassPutSetupZoom = await putSetupZoom(PARAMS);
      //         const codeDocumentPassPuPutSetupZoom = resDocumentPassPutSetupZoom.data.code;
      //         expect(codeDocumentPassPuPutSetupZoom).toBe(200);
      //         console.log('codeDocumentPassPuPutSetupZoom: ', codeDocumentPassPuPutSetupZoom);
      //       }
      //
      //       if (arr_offer_approval_setting_url.length > 0) {
      //         const PARAMS = {
      //           url_zoom: 'https://www.google.com.vn/',
      //           id_zoom: '456',
      //           password_zoom: '456',
      //           id: arr_offer_approval_setting_url[0].id,
      //         };
      //         const resOfferApprovalPutSetupZoom = await putSetupZoom(PARAMS);
      //         const codeOfferApprovalPutSetupZoom = resOfferApprovalPutSetupZoom.data.code;
      //         expect(codeOfferApprovalPutSetupZoom).toBe(200);
      //         console.log('codeOfferApprovalPutSetupZoom: ', codeOfferApprovalPutSetupZoom);
      //       }
      //       // Adjusted Done ==> interview complete (pass, no, unoffical offer)
      //       dataInterviewApi.map(item => {
      //         expect(item.status_interview_adjustment).toBe(4); // Adjusted
      //         console.log('Case 5: Adjusted: ', item.status_interview_adjustment);
      //       });
      // -----------------------------------------------------------------------
      // Status 4: Adjusted -> first pass
      const arr_document_pass_adjusted = [];
      const arr_offer_approval_adjusted = [];
      dataInterviewApi.map(item => {
        if (item.status_interview_adjustment === 4 && item.status_selection === 1) { // 3
          arr_document_pass_adjusted.push(item);
        }
        if (item.status_interview_adjustment === 4 && item.status_selection === 2) { // 3
          arr_offer_approval_adjusted.push(item);
        }
      });
      console.log('data arr_document_pass_adjusted : ', arr_document_pass_adjusted, arr_document_pass_adjusted.length);
      console.log('data arr_offer_approval_adjusted : ', arr_offer_approval_adjusted, arr_offer_approval_adjusted.length);

      if (arr_document_pass_adjusted.length > 0) {
        const PARAMS = {
          id: arr_document_pass_adjusted[0].id,
          status: 1,
          date_offer: '',
          action: 2,
        };
        // const resDocumentPassPutConfirmReview = await putConfirmReview(PARAMS); // PARAMS
        // const codeDocumentPassPutConfirmReview = resDocumentPassPutConfirmReview.data.code;
        // expect(codeDocumentPassPutConfirmReview).toBe(200);
        // console.log('codeDocumentPassPutConfirmReview: ', codeDocumentPassPutConfirmReview);
      }
      if (arr_offer_approval_adjusted.length > 0) {
        const PARAMS = {
          id: arr_offer_approval_adjusted[0].id,
          status: 1,
          date_offer: '',
          action: 2,
        };
        // const resOfferApprovalPutConfirmReview = await putConfirmReview(PARAMS); // PARAMS
        // const codeOfferApprovalPutConfirmReview = resOfferApprovalPutConfirmReview.data.code;
        // expect(codeOfferApprovalPutConfirmReview).toBe(200);
        // console.log('codeOfferApprovalPutConfirmReview: ', codeOfferApprovalPutConfirmReview);
      }

      // -----------------------------------------------------------------------
      // Status 5: Adjusted -> first pass Before adjustment -> first pass Under =>
      const arr_document_pass_first_pass = [];
      const arr_offer_approval_first_pass = [];
      dataInterviewApi.map(item => {
        if (item.status_interview_adjustment === 1 && item.status_selection === 5) { // 5
          arr_document_pass_first_pass.push(item);
        }
        if (item.status_interview_adjustment === 1 && item.status_selection === 5) { // 5
          arr_offer_approval_first_pass.push(item);
        }
      });
      console.log('data arr_document_pass_first_pass: ', arr_document_pass_first_pass, arr_document_pass_first_pass.length);
      console.log('data arr_offer_approval_first_pass: ', arr_offer_approval_first_pass, arr_offer_approval_first_pass.length);

      dataInterviewApi.map(item => {
        expect(item.status_interview_adjustment).toBe(1); // First pass
        expect(item.status_selection).toBe(5); //
        console.log('Case 5: First pass: ', item.status_interview_adjustment);
      });

      //
    }
    //
  });

  test('Case 6: Check modals Interview status firstpassing works properly', async() => {
    const wrapper = mount(beforeAdjustmentOop, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    // Adjusted Done ==> First Pass
    dataInterviewApiGlobal.map(item => {
      if (item.status_selection === 5 && item.status_interview_adjustment === 1) {
        expect(item.status_selection).toBe(5); // firstpassing
        expect(item.status_interview_adjustment).toBe(1); // firstpassing
      }
      console.log('Case 6: Adjusted: ', item.status_interview_adjustment);
    });

    // #endregionadjusted-modal
    const beforeAdjustmentModal = wrapper.find('#before-adjustment-modal');
    expect(beforeAdjustmentModal.exists()).not.toBe(true);

    wrapper.destroy();
  });

  // test('Case 6: Check modal Interview status firstpassing works properly', async () => {
  //   const wrapper = mount(BeforeAdjustMentOop, {
  //     localVue,
  //     router,
  //     store,
  //     stubs: {
  //       BIcon: true,
  //     },
  //   });
  //   // Data documentpassing
  //   const detailDataFix = {
  //     id: 2,
  //     entry_code: null,
  //     type_code: 1,
  //     type_name: '集団面接',
  //     interview_from: 'offer',
  //     interview_date: '20230830',
  //     hr_id: 3,
  //     full_name: 'Nawa',
  //     full_name_ja: 'ナワ',
  //     work_id: 1,
  //     job_name: 'JAPANESE RESTAURANT SERVER',
  //     updating_date: '2023-08-25',
  //     updating_date_ja: '2023年08月25日 (Fri)',
  //     status_selection: 1, // !
  //     status_selection_name: '書類通過', // !
  //     status_interview_adjustment: 1, // !
  //     status_interview_adjustment_name: '調整前', // !
  //     display: 'on',
  //     calendarTemporary: [],
  //     calendar: [],
  //     candidateList: [],
  //     optionSelectRound: {
  //       round_current_number: 0,
  //       round_current_option: {
  //         round_number: 1,
  //         round_text_ja: '一次面接へ',
  //         round_text_en: 'Move to first interview',
  //         round_name_current: '一次面接',
  //       },
  //       round_next_number: 1,
  //       round_next_option: {
  //         round_number: 1,
  //         round_text_ja: '一次面接へ',
  //         round_text_en: 'Move to first interview',
  //         round_name_current: '一次面接',
  //       },
  //       round_select_number: [
  //         1,
  //         2,
  //         3,
  //         4,
  //         5,
  //       ],
  //       round_select_option: [
  //         {
  //           round_number: 1,
  //           round_text_ja: '一次面接へ',
  //           round_text_en: 'Move to first interview',
  //           round_name_current: '一次面接',
  //         },
  //         {
  //           round_number: 2,
  //           round_text_ja: '二次面接へ',
  //           round_text_en: 'Move to second interview',
  //           round_name_current: '二次面接',
  //         },
  //         {
  //           round_number: 3,
  //           round_text_ja: '三次面接へ',
  //           round_text_en: 'Move to third interview',
  //           round_name_current: '三次面接',
  //         },
  //         {
  //           round_number: 4,
  //           round_text_ja: '四次合格へ',
  //           round_text_en: 'Move to fourth interview',
  //           round_name_current: '四次面接',
  //         },
  //         {
  //           round_number: 5,
  //           round_text_ja: '最終面接へ',
  //           round_text_en: 'Move to final interview',
  //           round_name_current: '最終面接',
  //         },
  //       ],
  //       round_final_number: 5,
  //       round_final_option: {
  //         round_number: 5,
  //         round_text_ja: '最終面接へ',
  //         round_text_en: 'Move to final interview',
  //         round_name_current: '最終面接',
  //       },
  //       round_option_number: [
  //         1,
  //         5,
  //       ],
  //       round_option_inteview: [
  //         {
  //           round_number: 1,
  //           round_text_ja: '一次面接へ',
  //           round_text_en: 'Move to first interview',
  //           round_name_current: '一次面接',
  //         },
  //         {
  //           round_number: 5,
  //           round_text_ja: '最終面接へ',
  //           round_text_en: 'Move to final interview',
  //           round_name_current: '最終面接',
  //         },
  //       ],
  //     },
  //   };
  //   await wrapper.setProps({ detailData: detailDataFix });

  //   const params = {
  //     mail_address: '1okuridashi_hanoi@gmail.vn',
  //     password: '123456789CCk',
  //   };

  //   await login(params).then((response) => {
  //     if (response['data']['code'] === 200) {
  //       const TOKEN = response['data']['data']['access_token'];
  //       const USER = response['data']['data']['profile'];

  //       const { ROLES, PERMISSIONS } = handleRole([]);

  //       const USER_INFO = {
  //         token: TOKEN,
  //         profile: USER,
  //         roles: ROLES,
  //         permissions: PERMISSIONS,
  //       };

  //       store.dispatch('user/saveLogin', USER_INFO);
  //       expect(store.getters.token).toBe(TOKEN);
  //       //
  //     }
  //   });

  //   // const roleCheck = jest.spyOn(wrapper.vm, 'roleCheck');
  //   // roleCheck();
  //   let roleCheck = false;
  //   const listRole = [1, 2, 4];
  //   const role = store.getters.profile.type;
  //   if (listRole.includes(role)) {
  //     // console.log('Case 5 role = true');
  //     roleCheck = true;
  //   }

  //   if (roleCheck) {
  //     expect(wrapper.vm.roleCheck).toEqual(true);

  //     // 1 document passing - before adjustment

  //     await wrapper.setData({ step: 1 });
  //     expect(wrapper.vm.step).toEqual(1);

  //     await wrapper.setData({ detail_data: 1 });
  //     // expect(wrapper.vm.step).toEqual(1);

  //     const tableScheduleInterview = wrapper.find('#table-calendar-temp');
  //     expect(tableScheduleInterview.exists()).toBe(true);

  //     const handleBack = jest.spyOn(wrapper.vm, 'handleBack');

  //     const btnHandleBback = wrapper.find('#btn-handle-back');
  //     expect(btnHandleBback.exists()).toBe(true);

  //     handleBack();
  //     await btnHandleBback.trigger('click');
  //     expect(handleBack).toHaveBeenCalled();

  //     const handleNextStep = jest.spyOn(wrapper.vm, 'handleNextStep');

  //     const btnHandleNextStep = wrapper.find('#btn-handle-next-step');
  //     expect(btnHandleNextStep.exists()).toBe(true);

  //     handleNextStep();
  //     await btnHandleNextStep.trigger('click');
  //     expect(handleNextStep).toHaveBeenCalledWith();

  //     // group interview time send data
  //     const groupInterviewTime = {
  //       interview_type: 2,
  //       times: [
  //         {
  //           date: '2023-09-06',
  //           start_time: '12:30',
  //           start_time_at: 'AM',
  //           expected_time: '90',
  //         },
  //         {
  //           date: '2023-09-06',
  //           start_time: '12:30',
  //           start_time_at: 'PM',
  //           expected_time: '30',
  //         },
  //         {
  //           date: '2023-09-06',
  //           start_time: '12:00',
  //           start_time_at: 'AM',
  //           expected_time: '30',
  //         },
  //         {
  //           date: '2023-08-30',
  //           start_time: '12:00',
  //           start_time_at: 'AM',
  //           expected_time: '30',
  //         },
  //         {
  //           date: '2023-08-30',
  //           start_time: '12:00',
  //           start_time_at: 'AM',
  //           expected_time: '30',
  //         },
  //       ],
  //     };

  //     // 2 document passing - under adjustment
  //   }

  //   wrapper.destroy();
  // });

  //

  //
});

