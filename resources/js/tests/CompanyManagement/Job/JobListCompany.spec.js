/* eslint-disable no-unused-vars */
// eslint-disable-next-line no-unused-vars
import { createLocalVue, shallowMount, mount } from '@vue/test-utils';
import store from '@/store';
import router from '@/router';
import JobListCompanyOop from '@/pages/CompanyManagement/Job/index.vue';

import { getListJob, removeJob } from '@/api/modules/job';
import { listJob } from '@/api/job';

import { handleRole } from '@/utils/handleRole';
import { login } from '@/api/auth';

describe('TEST Component Job list company ', () => {
  //
  const fieldsGlobal = [
    { key: 'selected', sortable: false, label: '', class: 'choose', thStyle: { textAlign: 'center', width: '45px' }, tdClass: 'col-1 text-center' },
    { key: 'id', sortable: true, label: 'ID', class: 'id', thStyle: { textAlign: 'center', width: '60px' }, tdClass: 'col-2 text-center' },
    { key: 'job_name', sortable: true, label: '求人名', class: 'job_name', thStyle: { textAlign: 'center' }, tdClass: 'col-2' },
    { key: 'company_name', sortable: true, label: '企業名', class: 'company_name', thStyle: { textAlign: 'center' }, tdClass: 'col-3' },
    { key: 'status', sortable: true, label: 'ステータス', class: 'status', thStyle: { textAlign: 'center', width: '140px' }, tdClass: 'col-4 text-center' },
    { key: 'updating_date', sortable: true, label: '更新日', class: 'updating_date', thStyle: { textAlign: 'center' }, tdClass: 'col-5 text-center' },
    { key: 'detail', sortable: false, label: '詳細', class: 'detail', thStyle: { textAlign: 'center', width: '100px' }, tdClass: 'col-6 text-center' },
  ];

  const arrListJobGlobal = [
    {
      id: 2,
      job_name: 'job_name 2',
      company_name: 'company_name 2',
      updating_date: '20230721',
      status: 2,
      is_selected: false,
    },
    {
      id: 3,
      job_name: 'job_name 3',
      company_name: 'company_name 3',
      updating_date: '20230751',
      status: 3,
      is_selected: true,
    },
  ];

  const paginationGlobal = {
    current_page: 1,
    per_page: 20,
    total_records: 10,
  };

  //
  test('Case 1: Check render Job List (Company)  Template', () => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(JobListCompanyOop, {
      localVue,
      store,
      router,
    });

    expect(typeof JobListCompanyOop.data).toBe('function');

    const JobListCompanyListTemplate = wrapper.findComponent({ name: 'JobList' });
    expect(JobListCompanyListTemplate.exists()).toBe(true);

    wrapper.destroy();
  });

  test('Case 2: Check render component Job List (Company)', () => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(JobListCompanyOop, {
      localVue,
      store,
      router,
    });

    const jobListCompanyPage = wrapper.find('.display-user-management-list');
    expect(jobListCompanyPage.exists()).toBe(true);

    const jobTableComponent = wrapper.find(`#job-table`);
    expect(jobTableComponent.exists()).toBe(true);

    const paginationComponent = wrapper.find(`#job-list-company-pagination`);
    expect(paginationComponent.exists()).toBe(true);

    const btnDeleteAllSelectedCheckbox = wrapper.find(`#delete-all-selected-checkbox`);
    expect(btnDeleteAllSelectedCheckbox.exists()).toBe(true);

    const btnNavigateToJobRegistration = wrapper.find(`#navigate-to-job-registration`);
    expect(btnNavigateToJobRegistration.exists()).toBe(true);

    wrapper.destroy();
  });

  test('Case 3: Test component render Title Job list company ', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(JobListCompanyOop, {
      localVue,
      store,
      router,
    });
    await wrapper.setData({ fields: fieldsGlobal });

    await wrapper.setData({ items: arrListJobGlobal });

    await wrapper.setData({ pagination: paginationGlobal });
    // expect(wrapper).toMatchSnapshot();

    const jobTableComponent = wrapper.find(`.job-list-table`);
    expect(jobTableComponent.exists()).toBe(true);

    // const sort_btn = jobTableComponent.findAll('.sr-only');
    // expect(sort_btn.exists()).toBe(true);

    // const one_sort_btn = sort_btn.at(2);
    // expect(one_sort_btn.exists()).toBe(true);

    wrapper.destroy();
  });

  test('Case 4: Check API Job List (Company) respone code 200', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(JobListCompanyOop, {
      localVue,
      store,
      router,
    });

    const params = {
      mail_address: '1okuridashi_hanoi@gmail.vn',
      password: '123456789CCk',
    };

    await login(params).then((response) => {
      // console.log('Case 4 Login response: ', response);
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
        router.push({ path: `/job/list` });
        //
      }
    });
    const URL = '/work?page=1&per_page=20';
    await getListJob(URL)
      .then((response) => {
        // console.log('Case 4 getListJob response: ', response);
        router.push({ path: `/job/list` });
        expect(response['code']).toBe(200);
      });

    wrapper.destroy();
  });

  test('Case 5: Check render Data Job List (Company)', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(JobListCompanyOop, {
      localVue,
      store,
      router,
    });

    await wrapper.setData({ fields: fieldsGlobal });

    await wrapper.setData({ items: arrListJobGlobal });

    await wrapper.setData({ pagination: paginationGlobal });

    // expect(wrapper).toMatchSnapshot();
    // console.log('Case 5 wrapper.vm: ', wrapper.vm.items);

    // const jobTableComponent = wrapper.find(`.job-table`);
    // expect(jobTableComponent.exists()).toBe(true);

    // const btnGoDetail = wrapper.find(`.btn-go-detail`);
    // expect(btnGoDetail.exists()).toBe(true);

    // const sort_btn = jobTableComponent.findAll('.sr-only');
    // expect(sort_btn.exists()).toBe(true);

    wrapper.destroy();
  });

  test('Case 6: Check Select item Job', () => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(JobListCompanyOop, {
      localVue,
      store,
      router,
    });

    wrapper.destroy();
  });

  test('Case 7: Check pagination Job List (Company)', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(JobListCompanyOop, {
      localVue,
      store,
      router,
    });

    await wrapper.setData({ fields: fieldsGlobal });

    await wrapper.setData({ items: arrListJobGlobal });

    await wrapper.setData({ pagination: paginationGlobal });
    // expect(wrapper).toMatchSnapshot();

    console.log('Case 7 wrapper.vm fields: ', wrapper.vm.fields);
    console.log('Case 7 wrapper.vm items: ', wrapper.vm.items);
    console.log('Case 7 wrapper.vm pagination: ', wrapper.vm.pagination);

    const paginationComponent = wrapper.find(`#job-list-company-pagination`);
    expect(paginationComponent.exists()).toBe(true);

    const funcGetCurrentPage = jest.spyOn(wrapper.vm, 'getCurrentPage');

    // const btnCurrenPage = wrapper.find(`.page-link`);
    // expect(btnCurrenPage.exists()).toBe(true);
    // btnCurrenPage.trigger;
    // await btnCurrenPage.trigger('click');
    // expect(funcGetCurrentPage).toHaveBeenCalled();

    wrapper.destroy();
  });

//
});

