/* eslint-disable no-unused-vars */
import { createLocalVue, shallowMount, mount } from '@vue/test-utils';
import store from '@/store';
import router from '@/router';
import offerOop from '@/pages/MatchingManagement/Tab/offer.vue';
// import Entry from '@/pages/MatchingManagement/Tab/offer.vue';
// import MatchingManagement from '@/pages/MatchingManagement/index';
import ROLE from '@/const/role.js';

import { getListOffer } from '@/api/modules/matching.js';

import { login } from '@/api/auth';
import { handleRole } from '@/utils/handleRole';

describe('Test component MatchingManagement Tab Entry', () => {
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

    // wrapper.destroy();
  });

  test('Case 2: Check render component Matching management tab Offer', async() => {
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

  test('Case 3: Check API tab Offer respone code 200', async() => {
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
    console.log('case 3: getListOffer :', response);
    expect(code).toBe(200);

    wrapper.destroy();
  });

  test('Case 4: Check button Delete Mutiple and event function when click button Delete Mutiple', async() => {
    const handleDeleteAll = jest.fn();
    // const wrapper = mount(MatchingManagement, {
    //   localVue,
    //   router,
    //   store,
    //   stubs: {
    //     BIcon: true,
    //   },
    //   methods: {
    //     handleDeleteAll,
    //   },
    // });

    // await wrapper.setProps({ dataOffer: dataOffer });
    // await wrapper.setData({ tabIndex: 0 });
    //
    //     const BTN_ALL_DELETE = wrapper.find('#btn-delete-all');
    //     expect(BTN_ALL_DELETE.exists()).toBeTruthy();

    // await BTN_ALL_DELETE.trigger('click');
    // expect(handleDeleteAll).toHaveBeenCalled();

    // wrapper.destroy();
  });

  test('Case 5: Check component render Table tab Offer have data', async() => {
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
      expect(tableOffer.props('fields')[field].thStyle).toEqual(fieldsOffer[field].thStyle);
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

    // check component internal
    const TABLE_BODY = tableOffer.find('tbody');
    expect(TABLE_BODY.exists()).toBe(true);

    const TABLE_BODY_TR = TABLE_BODY.findAll('tr');
    expect(TABLE_BODY_TR.length).toEqual(2); // 2 item

    for (let tr = 0; tr < TABLE_BODY_TR.length; tr++) { // find col
      const LIST_TD = TABLE_BODY_TR.at(tr).findAll('td');

      expect(LIST_TD.at(0).find('input[type="checkbox"]').exists()).toBe(true);
      expect(LIST_TD.at(0).find('input[type="checkbox"]').element.checked).toBe(false);

      expect(LIST_TD.at(1).text()).toEqual(String(dataOffer[tr].id));
      expect(LIST_TD.at(2).text()).toEqual(String(dataOffer[tr].offer_date));
      expect(LIST_TD.at(4).text()).toEqual(String(dataOffer[tr].work_title));
      const link = LIST_TD.at(4).findAll('a');
      expect(link.exists()).toBe(true);

      // const btnShowDetail = LIST_TD.at(6).findAll('button');
      const btnShowDetail = LIST_TD.at(6).findAll('#btn-go-detail');
      expect(btnShowDetail.exists()).toBe(true);

      expect(btnShowDetail.length).toBe(1);
      const goToDetail = jest.spyOn(wrapper.vm, 'goToDetail');

      await btnShowDetail.trigger('click');
      expect(goToDetail).toHaveBeenCalledWith(dataOffer[tr]);
    }

    const LIST_TEST_CHECKED = [0];

    for (let input = 0; input < LIST_TEST_CHECKED.length; input++) {
      await TABLE_BODY_TR.at(input).findAll('td').at(0).find('input[type="checkbox"]').setChecked();
    }
    // status_selection = 1 => can't check
    expect(wrapper.vm.selectedItems.length).toEqual(LIST_TEST_CHECKED.length);

    // wrapper.destroy();
  });

  test('Case 6: Check component page navigation when click name hr, name job', async() => {
    const wrapper = mount(offerOop, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    await wrapper.setProps({ dataOffer: dataOffer });
    const tableOffer = wrapper.find('#offer-table-list');
    expect(tableOffer.exists()).toBe(true);

    const TABLE_BODY = tableOffer.find('tbody');
    expect(TABLE_BODY.exists()).toBe(true);

    const TABLE_BODY_TR = TABLE_BODY.findAll('tr');

    for (let tr = 0; tr < TABLE_BODY_TR.length; tr++) {
      const LIST_TD = TABLE_BODY_TR.at(tr).findAll('td');

      const link = LIST_TD.at(4).findAll('a');
      expect(link.exists()).toBe(true);
    }

    // wrapper.destroy();
  });

  //
});

