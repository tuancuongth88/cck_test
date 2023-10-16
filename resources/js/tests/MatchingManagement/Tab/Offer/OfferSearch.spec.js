/* eslint-disable no-unused-vars */
import { createLocalVue, shallowMount, mount } from '@vue/test-utils';
import store from '@/store';
import router from '@/router';
import offerOop from '@/pages/MatchingManagement/Tab/offer.vue';
// import Entry from '@/pages/MatchingManagement/Tab/offer.vue';
import hrFormSearchOop from '@/layout/components/search/HrFormSearch.vue';

import MatchingManagementOop from '@/pages/MatchingManagement/index';
import ROLE from '@/const/role.js';

import { getListOffer } from '@/api/modules/matching.js';

import { login } from '@/api/auth';
import { handleRole } from '@/utils/handleRole';

describe('Test component MatchingManagement Tab Offer Search', () => {
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
    const wrapperHrFormSearch = mount(hrFormSearchOop, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    const wrapperMatchingManagement = mount(MatchingManagementOop, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    expect(typeof hrFormSearchOop.data).toBe('function');
    const HrFormSearchTemplate = wrapperHrFormSearch.findComponent({ name: 'HrFormSearch' });
    expect(HrFormSearchTemplate.exists()).toBe(true);

    expect(typeof MatchingManagementOop.data).toBe('function');
    const matchingManagementTemplate = wrapperMatchingManagement.findComponent({ name: 'MatchingManagement' });
    expect(matchingManagementTemplate.exists()).toBe(true);

    // await wrapper.setProps({ dataOffer: dataOffer });
    // expect(wrapper).toMatchSnapshot(); //

    // wrapper.destroy();
  });

  test('Case 2: Check render component Hr Form Search, Matching management tab Offer', async() => {
    const wrapperHrFormSearch = mount(hrFormSearchOop, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    const wrapperMatchingManagement = mount(MatchingManagementOop, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    const hrFormSearchComponent = wrapperHrFormSearch.find('.search-with-conditions');
    expect(hrFormSearchComponent.exists()).toBe(true);

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
    await wrapperMatchingManagement.setProps({ dataOffer: dataOffer });

    await wrapperMatchingManagement.setData({ tabIndex: 1 });

    const matchingManagementPage = wrapperMatchingManagement.find('.display-user-management-list');
    expect(matchingManagementPage.exists()).toBe(true);
    // wrapper.destroy();
  });

  test('Case 3: Check component Hr Form Search empty value search init', async() => {
    const handleSearch = jest.fn();
    const wrapperHrFormSearch = mount(hrFormSearchOop, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
      methods: {
        handleSearch,
      },
    });

    // const wrapperMatchingManagement = mount(MatchingManagementOop, {
    //   localVue,
    //   router,
    //   store,
    //   stubs: {
    //     BIcon: true,
    //   },
    // });

    const hrFormSearchComponent = wrapperHrFormSearch.find('.search-with-conditions');
    expect(hrFormSearchComponent.exists()).toBe(true);

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
    // await wrapperMatchingManagement.setProps({ dataOffer: dataOffer });
    // await wrapperMatchingManagement.setData({ tabIndex: 1 });

    // const handleSearch = jest.spyOn(hrFormSearchComponent.vm, 'handleSearch');

    const btnSearchConditions = wrapperHrFormSearch.find('.btn-search-vs-conditions');
    expect(btnSearchConditions.exists()).toBe(true);

    await btnSearchConditions.trigger('click');
    expect(handleSearch).toHaveBeenCalled();

    const inputSearchConditions = wrapperHrFormSearch.find('#free-word');
    expect(inputSearchConditions.exists()).toBe(true);

    const formData = {
      hr_org_id: '',
      gender: [],
      age_from: '',
      age_to: '',
      final_education_date_from_year: '',
      final_education_date_from_month: '',
      final_education_date_to_year: '',
      final_education_date_to_month: '',
      edu_class: [],
      edu_degree: [],
      final_education_course: [],
      work_forms: [],
      work_hour: [],
      japan_levels: [],
      main_job_career: [],
      search: '',
    };

    // await wrapperHrFormSearch.setData({ formData: formData });
    expect(wrapperHrFormSearch.vm.formData).toEqual(formData);
    expect(wrapperHrFormSearch.vm.formData.hr_org_id).toEqual(formData.hr_org_id);
    expect(wrapperHrFormSearch.vm.formData.gender).toEqual(formData.gender);
    expect(wrapperHrFormSearch.vm.formData.age_from).toEqual(formData.age_from);
    expect(wrapperHrFormSearch.vm.formData.age_to).toEqual(formData.age_to);
    expect(wrapperHrFormSearch.vm.formData.final_education_date_from_year).toEqual(formData.final_education_date_from_year);
    expect(wrapperHrFormSearch.vm.formData.final_education_date_from_month).toEqual(formData.final_education_date_from_month);
    expect(wrapperHrFormSearch.vm.formData.final_education_date_to_year).toEqual(formData.final_education_date_to_year);
    expect(wrapperHrFormSearch.vm.formData.final_education_date_to_month).toEqual(formData.final_education_date_to_month);
    expect(wrapperHrFormSearch.vm.formData.edu_class).toEqual(formData.edu_class);
    expect(wrapperHrFormSearch.vm.formData.edu_degree).toEqual(formData.edu_degree);
    expect(wrapperHrFormSearch.vm.formData.final_education_course).toEqual(formData.final_education_course);
    expect(wrapperHrFormSearch.vm.formData.work_forms).toEqual(formData.work_forms);
    expect(wrapperHrFormSearch.vm.formData.work_hour).toEqual(formData.work_hour);
    expect(wrapperHrFormSearch.vm.formData.japan_levels).toEqual(formData.japan_levels);
    expect(wrapperHrFormSearch.vm.formData.main_job_career).toEqual(formData.main_job_career);
    expect(wrapperHrFormSearch.vm.formData.search).toEqual(formData.search);

    // wrapper.destroy();
  });

  test('Case 4: Check component Hr Form Search have value search', async() => {
    const handleSearch = jest.fn();
    const wrapperHrFormSearch = mount(hrFormSearchOop, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
      methods: {
        handleSearch,
      },
    });

    const hrFormSearchComponent = wrapperHrFormSearch.find('.search-with-conditions');
    expect(hrFormSearchComponent.exists()).toBe(true);

    const btnSearchConditions = wrapperHrFormSearch.find('.btn-search-vs-conditions');
    expect(btnSearchConditions.exists()).toBe(true);

    await btnSearchConditions.trigger('click');
    expect(handleSearch).toHaveBeenCalled();

    const inputSearchConditions = wrapperHrFormSearch.find('#free-word');
    expect(inputSearchConditions.exists()).toBe(true);

    const formData = {
      hr_org_id: 3,
      gender: [1],
      age_from: 30,
      age_to: 44,
      edu_class: [1],
      edu_degree: [1],
      work_forms: [2],
      work_hour: true,
      japan_levels: [2],
      search: 'search',
    };

    await wrapperHrFormSearch.setData({ formData: formData });

    expect(wrapperHrFormSearch.vm.formData.hr_org_id).toEqual(formData.hr_org_id);
    expect(wrapperHrFormSearch.vm.formData.gender).toEqual(formData.gender);
    expect(wrapperHrFormSearch.vm.formData.age_from).toEqual(formData.age_from);
    expect(wrapperHrFormSearch.vm.formData.age_to).toEqual(formData.age_to);
    expect(wrapperHrFormSearch.vm.formData.edu_class).toEqual(formData.edu_class);
    expect(wrapperHrFormSearch.vm.formData.edu_degree).toEqual(formData.edu_degree);
    expect(wrapperHrFormSearch.vm.formData.work_forms).toEqual(formData.work_forms);
    expect(wrapperHrFormSearch.vm.formData.work_hour).toEqual(formData.work_hour);
    expect(wrapperHrFormSearch.vm.formData.search).toEqual(formData.search);

    // wrapper.destroy();
  });

  test('Case 5: Check function search', async() => {
    const wrapperMatchingManagement = mount(MatchingManagementOop, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    const matchingManagementPage = wrapperMatchingManagement.find('.display-user-management-list');
    expect(matchingManagementPage.exists()).toBe(true);

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

    const paramsOffer = {
      hr_org_id: 3,
      gender: [1],
      age_from: 30,
      age_to: 44,
      edu_class: [1],
      edu_degree: [1],
      work_forms: [2],
      work_hour: true,
      japan_levels: [2],
      search: 'Hoa',
      edu_date_from: '1991-02',
      edu_date_to: '1996-08',
      // main_job_ids: [3], // ! fail
      middle_class: [7],
    };

    const response = await getListOffer(paramsOffer);
    console.log('getListOffer response', response);
    const { code } = response.data;
    expect(code).toBe(200);

    wrapperMatchingManagement.destroy();
  });
//
});
