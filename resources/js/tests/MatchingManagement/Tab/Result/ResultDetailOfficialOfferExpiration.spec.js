/* eslint-disable no-unused-vars */
import { createLocalVue, shallowMount, mount } from '@vue/test-utils';
import store from '@/store';
import router from '@/router';
import { login } from '@/api/auth';
import { handleRole } from '@/utils/handleRole';
import EventBus from '@/utils/eventBus';
// import offerOop from '@/pages/MatchingManagement/Tab/offer.vue';
import ResultOop from '@/pages/MatchingManagement/Tab/result.vue';
// import MatchingManagement from '@/pages/MatchingManagement/index';
import ROLE from '@/const/role.js';
import { getListResult } from '@/api/modules/matching.js';

import {
  getDetailResult,
  updateResult,
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

  const dataResult = [
    {
      id: 51,
      code: 'E48',
      updating_date: '20230926',
      hr_id: 2635,
      full_name: '氏名-Thuy-Create-2628',
      full_name_ja: '氏名（ﾌﾘｶﾞﾅ）-Thuy-Create-2628',
      job_id: 16,
      job_title: 'Thuy Recuiment 4',
      status_selection: 4,
      status_selection_name: '内定承諾',
      time: 1,
      is_favorite_hrs: 0,
      is_favorite_job: 0,
    },
  ];

  const localVue = createLocalVue();

  test('Check modal result expire open', async() => {
    const localVue = createLocalVue();

    const wrapper = mount(ResultOop, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });
    wrapper.setData(dataResult);

    const spy = jest.spyOn(wrapper.vm, 'goToDetail');
    await wrapper.find('button').trigger('click');
    expect(wrapper.vm.goToDetail).not.toBeCalled();
    // const params = {
    //   mail_address: '1okuridashi_hanoi@gmail.vn',
    //   password: '123456789CCk',
    // };

    // let permissionCheck = null;
    // await login(params).then((response) => {
    //   if (response['data']['code'] === 200) {
    //     const TOKEN = response['data']['data']['access_token'];
    //     const USER = response['data']['data']['profile'];

    //     const { ROLES, PERMISSIONS } = handleRole([]);

    //     const USER_INFO = {
    //       token: TOKEN,
    //       profile: USER,
    //       roles: ROLES,
    //       permissions: PERMISSIONS,
    //     };

    //     store.dispatch('user/saveLogin', USER_INFO);
    //     // expect(store.getters.token).toBe(TOKEN);
    //     permissionCheck = store.getters.permissionCheck;
    //   }
    // });

    // const responseListResult = await getListResult();
    // const codeListResult = responseListResult.data.code;
    // expect(codeListResult).toBe(200);
    // const resultListResult = responseListResult.data.data.result;

    // const dataResultArr = [];
    // resultListResult.map((item) => {
    //   // Check for status id = 1 Re
    //   if (item.status === 4) {
    //     dataResultArr.push({
    //       ...item,
    //       _isSelected: false,
    //     });
    //   }
    // });

    // // console.log('case 5: getListResult :', dataResultArr);
    // await wrapper.setProps({ dataResult: dataResultArr });

    // const table_hr = wrapper.find('#result-table-list');
    // // expect(table_hr).toMatchSnapshot(); //

    // const btnCheckbox = table_hr.find('#checkbox-result');
    // expect(btnCheckbox.exists()).toBe(true);

    // for (let item = 0; item < dataResultArr.length; item++) {
    //   expect(table_hr.props('items')[item].status).toEqual(dataResultArr[item].status);
    // }

    // const TABLE_BODY = table_hr.find('tbody');
    // expect(TABLE_BODY.exists()).toBe(true);

    // const TABLE_BODY_TR = TABLE_BODY.findAll('tr');
    // expect(TABLE_BODY_TR.length).toEqual(dataResultArr.length); // Number item

    // let itemResultClick = {};

    // for (let tr = 0; tr < TABLE_BODY_TR.length; tr++) { // find col
    //   const LIST_TD = TABLE_BODY_TR.at(tr).findAll('td');

    //   const btnShowDetail = LIST_TD.at(8).findAll('#btn-go-detail'); // Col index 8 icon show detail
    //   expect(btnShowDetail.exists()).toBe(true);
    //   expect(btnShowDetail.length).toBe(1);

    //   const goToDetail = jest.spyOn(wrapper.vm, 'goToDetail');

    //   await btnShowDetail.trigger('click');
    //   expect(goToDetail).toHaveBeenCalledWith(dataResultArr[tr]);
    //   itemResultClick = dataResultArr[tr];
    // }

    // const responseGetDetailResult = await getDetailResult(itemResultClick.id);
    // const codeDetailEntry = responseGetDetailResult.data.code;
    // expect(codeDetailEntry).toBe(200);
    // console.log('case 5: getDetailResult :', responseGetDetailResult);

    // // Reject  ![ROLE.TYPE_HR, ROLE.TYPE_HR_MANAGER]
    // const ModalEntryDeclineElement = wrapper.find('#result-modal-offical-offer-decline');
    // expect(ModalEntryDeclineElement.exists()).toBe(true);

    // const handleModalRequestingReject = jest.spyOn(wrapper.vm, 'handleDeclineResult');

    // const btnRequestingReject = wrapper.find('#btn-result-decline');
    // expect(btnRequestingReject.exists()).toBe(true);
    // // btnRequestingReject.trigger('click');
    // // expect(handleModalRequestingReject).toHaveBeenCalled();

    // const paramsReject = {
    //   id: itemResultClick.id, // item được chọn
    //   status: 4, // Hr reject
    // };
    // const responseparamsReject = await updateResult(paramsReject);
    // const codeparamsReject = responseparamsReject.data.code;
    // expect(codeparamsReject).toBe(200);
  });
});
