/* eslint-disable no-unused-vars */
import { createLocalVue, shallowMount, mount } from '@vue/test-utils';
import store from '@/store';
import router from '@/router';
import { login } from '@/api/auth';
import { handleRole } from '@/utils/handleRole';
import EventBus from '@/utils/eventBus';
import offerOop from '@/pages/MatchingManagement/Tab/offer.vue';

import ROLE from '@/const/role.js';
import { getListOffer } from '@/api/modules/matching.js';

import {
  deleteMultipleOffer,
  getDetailOffer,
  updateOffer,
} from '@/api/modules/matching.js';

describe('Test component Offer Detail Offer Detail Decline by HR', () => {
  const fieldsOffer = [
    {
      key: 'selected',
      sortable: false,
      label: '',
      class: 'bg-header',
      thStyle: {
        backgroundColor: '#1D266A',
        width: '60px',
        color: '#fff',
        textAlign: 'center',
      },
      thClass: 'text-center',
      tdClass: 'text-left',
    },
    {
      key: 'id',
      sortable: true,
      label: 'ID',
      class: 'bg-header',
      thStyle: {
        backgroundColor: '#1D266A',
        width: '160px',
        color: '#fff',
        align: 'center',
      },
      thClass: 'text-center',
    },
    {
      key: 'offer_date',
      sortable: true,
      label: 'HEADER_REQUEST_DATE_ENTRY_MATCHING',
      class: 'bg-header',
      thStyle: {
        backgroundColor: '#1D266A',
        width: '160px',
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
        width: '240px',
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
        width: '240px',
        color: '#fff',
        textAlign: 'center',
      },
    },
    {
      key: 'status_selection',
      sortable: true,
      label: 'HEADER_STATUS_ENTRY_MATCHING',
      class: 'bg-header',
      thStyle: {
        backgroundColor: '#1D266A',
        width: '160px',
        color: '#fff',
        textAlign: 'center',
      },
      tdClass: 'text-center',
    },
    // This one needs a custom template, so we define the key and the label
    {
      key: 'detail',
      label: 'HEADER_DETAIL_ENTRY_MATCHING',
      class: 'bg-header',
      thStyle: {
        width: '89px',
        backgroundColor: '#1D266A',
        color: '#fff',
        textAlign: 'center',
      },
      tdClass: 'text-center',
    },
  ];

  const dataOffer = [
    {
      id: 9,
      offer_date: '2023-08-03',
      hr_id: 2,
      full_name: 'Kiet',
      full_name_ja: 'キエット',
      work_id: 1,
      work_title: 'IT engineer',
      status_selection: 2,
      status_selection_name: '辞退',
      display: 'on',
    },
    {
      id: 1,
      offer_date: '2023-07-26',
      hr_id: 2,
      full_name: 'Kiet',
      full_name_ja: 'キエット',
      work_id: 1,
      work_title: 'IT engineer',
      status_selection: 1,
      status_selection_name: '申請中',
      display: 'on',
    },
  ];

  const localVue = createLocalVue();

  test('Case 1: Check render Offer Template', async() => {
    const wrapper = mount(offerOop, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    expect(typeof offerOop.data).toBe('function');
    const tabOfferTemplate = wrapper.findComponent({ name: 'Offer' });
    expect(tabOfferTemplate.exists()).toBe(true);
    console.log('Offer Detail Company.spec');
    // wrapper.destroy();
  });

  test('Case 2: Check render component tab Offer', async() => {
    const wrapper = mount(offerOop, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    await wrapper.setProps({ dataOffer: dataOffer });

    // expect(wrapper).toMatchSnapshot(); //

    const tableOffer = wrapper.find('#offer-table-list');
    expect(tableOffer.exists()).toBe(true);

    const btnCheckboxEntry = tableOffer.find('#checkbox-offer');
    expect(btnCheckboxEntry.exists()).toBe(true);

    const TABLE_BODY = tableOffer.find('tbody');
    expect(TABLE_BODY.exists()).toBe(true);
    // wrapper.destroy();
  });

  test('Case 3: Check API tab Offer response code 200', async() => {
    const wrapper = mount(offerOop, {
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

    const response = await getListOffer();
    const { code } = response.data;
    // console.log('case 3: getListOffer :', response);
    expect(code).toBe(200);
    // wrapper.destroy();
  });

  test('Case 4: Check component render Table tab Offer have data', async() => {
    const wrapper = mount(offerOop, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
      data() {
        return {
          queryData: {
            page: 1,
            per_page: 20,
            total_records: dataOffer.length, // 2
            search: '',
            order_type: '',
            order_column: '',
          },
        };
      },
    });

    await wrapper.setProps({ dataOffer: dataOffer });

    const tableOffer = wrapper.find('#offer-table-list');
    expect(tableOffer.exists()).toBe(true);

    // expect(tableOffer).toMatchSnapshot(); //

    const btnCheckboxEntry = tableOffer.find('#checkbox-offer');
    expect(btnCheckboxEntry.exists()).toBe(true);

    // check field
    for (let field = 0; field < fieldsOffer.length; field++) {
      expect(tableOffer.props('fields')[field].key).toEqual(fieldsOffer[field].key);
      expect(tableOffer.props('fields')[field].sortable).toEqual(fieldsOffer[field].sortable);
      expect(tableOffer.props('fields')[field].label).toEqual(fieldsOffer[field].label);
      expect(tableOffer.props('fields')[field].class).toEqual(fieldsOffer[field].class);
      // expect(tableOffer.props('fields')[field].thStyle).toEqual(fieldsOffer[field].thStyle);
    }

    // check data
    for (let item = 0; item < dataOffer.length; item++) {
      expect(tableOffer.props('items')[item].id).toEqual(dataOffer[item].id);

      expect(tableOffer.props('items')[item].id).toEqual(dataOffer[item].id);
      expect(tableOffer.props('items')[item].offer_date).toEqual(dataOffer[item].offer_date);
      expect(tableOffer.props('items')[item].hr_id).toEqual(dataOffer[item].hr_id);
      expect(tableOffer.props('items')[item].full_name).toEqual(dataOffer[item].full_name);
      expect(tableOffer.props('items')[item].full_name_ja).toEqual(dataOffer[item].full_name_ja);
      expect(tableOffer.props('items')[item].work_id).toEqual(dataOffer[item].work_id);
      expect(tableOffer.props('items')[item].work_title).toEqual(dataOffer[item].work_title);
      expect(tableOffer.props('items')[item].status_selection).toEqual(dataOffer[item].status_selection);
      expect(tableOffer.props('items')[item].status_selection_name).toEqual(dataOffer[item].status_selection_name);
      expect(tableOffer.props('items')[item].display).toEqual(dataOffer[item].display);
    }
    // wrapper.destroy();
  });

  test('Case 5: Check With Role (HR) Can confirm/Decline', async() => {
    const wrapper = mount(offerOop, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    // 5 Role (Hr) 1 Admin  3 hr_manager
    const params = {
      mail_address: '1okuridashi_hanoi@gmail.vn', // 5 hr
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

    // Get Api Offer
    const responseListOffer = await getListOffer();
    const codeListOffer = responseListOffer.data.code;
    expect(codeListOffer).toBe(200);
    const resultListOffer = responseListOffer.data.data.result;

    const dataOfferArr = [];
    resultListOffer.map((item) => {
      // Check for status_selection id = 1 Mở modal xác nhân và từ chối
      if (item.status_selection === 1) {
        dataOfferArr.push({
          ...item,
          _isSelected: false,
        });
      }
    });

    console.log('Get Api Offer dataOfferArr', dataOfferArr, 'lenght', dataOfferArr.length);
    await wrapper.setProps({ dataOffer: dataOfferArr });

    const permissionCheck = store.getters.permissionCheck; // 5 hr can confirm vs reject
    console.log('Get permissionCheck 5 hr = ', permissionCheck);
    // check role (ROLE.TYPE_SUPER_ADMIN , ROLE.TYPE_HR_MANAGER , ROLE.TYPE_HR)

    if (
      ROLE.TYPE_SUPER_ADMIN === permissionCheck ||
					ROLE.TYPE_HR_MANAGER === permissionCheck ||
					ROLE.TYPE_HR === permissionCheck
    ) {
      const table_hr = wrapper.find('#offer-table-list');
      expect(table_hr.exists()).toBe(true);
      // expect(table_hr).toMatchSnapshot(); //

      const btnCheckbox = table_hr.find('#checkbox-offer');
      expect(btnCheckbox.exists()).toBe(true);

      for (let item = 0; item < dataOfferArr.length; item++) {
        expect(table_hr.props('items')[item].status).toEqual(dataOfferArr[item].status);
      }

      const TABLE_BODY = table_hr.find('tbody');
      expect(TABLE_BODY.exists()).toBe(true);

      const TABLE_BODY_TR = TABLE_BODY.findAll('tr');
      expect(TABLE_BODY_TR.length).toEqual(dataOfferArr.length); // Number item

      let itemEntryClick = {};

      for (let tr = 0; tr < TABLE_BODY_TR.length; tr++) { // find col
        const LIST_TD = TABLE_BODY_TR.at(tr).findAll('td');

        const btnShowDetail = LIST_TD.at(6).findAll('#btn-go-detail'); // Col index 8 icon show detail
        expect(btnShowDetail.exists()).toBe(true);
        expect(btnShowDetail.length).toBe(1);

        const goToDetail = jest.spyOn(wrapper.vm, 'goToDetail');

        await btnShowDetail.trigger('click');
        expect(goToDetail).toHaveBeenCalledWith(dataOfferArr[tr]);
        itemEntryClick = dataOfferArr[tr];
      }

      // Tìm modal cha có 2 nút confirm và reject
      const modalOfferRequesting = wrapper.findAll('#offer-modal-requesting');
      expect(modalOfferRequesting.exists()).toBe(true);

      // Tìm hai nút
      const btnHandleModalRequestingConfirm = wrapper.findAll('#btn-handle-modal-requesting-confirm');
      expect(btnHandleModalRequestingConfirm.exists()).toBe(true);

      const btnHandleModalRequestingDecline = wrapper.findAll('#btn-handle-modal-requesting-decline');
      expect(btnHandleModalRequestingDecline.exists()).toBe(true);

      // Tìm modal xác nhận - khi ấn nút confirm
      const offerModalRequestingConfirm = wrapper.findAll('#offer-modal-requesting-confirm');
      expect(offerModalRequestingConfirm.exists()).toBe(true);

      //       Tìm modal từ chối khi ấn nút decline
      const modalOfferRequestingDecline = wrapper.findAll('#offer-modal-requesting-decline');
      expect(modalOfferRequestingDecline.exists()).toBe(true);

      // API conrifrm
      const formDataConfirm = {
        id: itemEntryClick.id,
        status: 3,
      };

      const responseOfferConfirm = await updateOffer(formDataConfirm);
      const codeOfferConfirm = responseOfferConfirm.data.code;
      console.log('responseOfferConfirm', responseOfferConfirm);
      expect(codeOfferConfirm).toBe(200);

      // API Reject
      //       const formDataReject = {
      //         id: itemEntryClick.id,
      //         status: 2,
      //         note: 'note',
      //       };
      //
      //       const responseOfferReject = await updateOffer(formDataReject);
      //       const codeOfferReject = responseOfferReject.data.code;
      //       console.log('responseOfferReject', responseOfferReject);
      //       expect(codeOfferReject).toBe(200);

      // btn child modal decline send API vs radio select
      //     const btnHandleDeclineOffer = wrapper.findAll('#btn-handle-decline-offer');
      //     expect(btnHandleDeclineOffer.exists()).toBe(true);
      //
      //     const handleDeclineOffer = jest.spyOn(wrapper.vm, 'handleDeclineOffer');
      //
      //     await btnHandleDeclineOffer.trigger('click');
      //     expect(handleDeclineOffer).toHaveBeenCalled();
      //
    }
  });
  //
});

