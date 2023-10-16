/* eslint-disable no-unused-vars */
import { createLocalVue, shallowMount, mount } from '@vue/test-utils';
import store from '@/store';
import router from '@/router';
import entryOop from '@/pages/MatchingManagement/Tab/Entry.vue';
import hrFormSearchOop from '@/layout/components/search/HrFormSearch.vue';

// import matchingManagementOop from '@/pages/MatchingManagement/index';
import ROLE from '@/const/role.js';

import { getListEntry } from '@/api/modules/matching.js';

import { login } from '@/api/auth';
import { handleRole } from '@/utils/handleRole';

describe('Test component MatchingManagement Tab Offer Search', () => {
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

  const dataEntry = [
    {
      id: 7,
      entry_code: '1691053951',
      request_date: '20230803',
      hr_id: 6,
      full_name: 'Dinh',
      full_name_ja: 'ディン',
      work_id: 1,
      work_title: 'IT engineer',
      updating_date: '20230803',
      status: 2,
      status_name: '却下',
      note: null,
      is_favorite_hrs: 0,
      is_favorite_job: 0,
    },
  ];
  const localVue = createLocalVue();

  test('Case 1: Check render Entry Template', async() => {
    const wrapperHrFormSearch = mount(hrFormSearchOop, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    // const wrapperMatchingManagement = mount(matchingManagementOop, {
    //   localVue,
    //   router,
    //   store,
    //   stubs: {
    //     BIcon: true,
    //   },
    // });

    expect(typeof hrFormSearchOop.data).toBe('function');
    const HrFormSearchTemplate = wrapperHrFormSearch.findComponent({ name: 'HrFormSearch' });
    expect(HrFormSearchTemplate.exists()).toBe(true);

    // expect(typeof matchingManagementOop.data).toBe('function');
    // const matchingManagementTemplate = wrapperMatchingManagement.findComponent({ name: 'MatchingManagement' });
    // expect(matchingManagementTemplate.exists()).toBe(true);

    // expect(wrapper).toMatchSnapshot(); //

    // wrapper.destroy();
  });

  test('Case 2: Check render component Hr Form Search, Matching management tab Entry', async() => {
    const wrapperHrFormSearch = mount(hrFormSearchOop, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    // const wrapperMatchingManagement = mount(matchingManagementOop, {
    //   localVue,
    //   router,
    //   store,
    //   stubs: {
    //     BIcon: true,
    //   },
    // });

    const wrapperEntry = mount(entryOop, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    // const hrFormSearchComponent = wrapperHrFormSearch.find('.search-with-conditions');
    // expect(hrFormSearchComponent.exists()).toBe(true);
    // await wrapperMatchingManagement.setData({ tabIndex: 1 });

    // const matchingManagementPage = wrapperMatchingManagement.find('.display-user-management-list');
    // expect(matchingManagementPage.exists()).toBe(true);

    await wrapperEntry.setProps({ dataEntry: dataEntry });
    const table_hr = wrapperEntry.find('#entry-table-list');
    expect(table_hr.exists()).toBe(true);

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

    const hrFormSearchComponent = wrapperHrFormSearch.find('.search-with-conditions');
    expect(hrFormSearchComponent.exists()).toBe(true);

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
    // const wrapperMatchingManagement = mount(matchingManagementOop, {
    //   localVue,
    //   router,
    //   store,
    //   stubs: {
    //     BIcon: true,
    //   },
    // });

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

    const response = await getListEntry(paramsOffer);
    console.log('getListEntry response', response);
    const { code } = response.data;
    expect(code).toBe(200);

    // wrapperMatchingManagement.destroy();
  });
//
});
