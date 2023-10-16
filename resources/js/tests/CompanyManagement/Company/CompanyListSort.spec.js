/* eslint-disable no-unused-vars */
import { createLocalVue, shallowMount, mount } from '@vue/test-utils';
import store from '@/store';
import router from '@/router';
import CompanyList from '@/pages/CompanyManagement/Company/index.vue';
import { listCompany } from '@/api/company';
import { handleRole } from '@/utils/handleRole';
import { login } from '@/api/auth';

describe('Check Component Company list', () => {
  test('Case 1: Check component render Company List Template', () => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(CompanyList, {
      localVue,
      store,
      router,
    });

    expect(typeof CompanyList.data).toBe('function');

    const CompanyListTemplate = wrapper.findComponent({ name: 'CompanyList' });
    expect(CompanyListTemplate.exists()).toBe(true);

    //
  });

  test('Case 2: Check render component Sort Company List', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(CompanyList, {
      localVue,
      store,
      router,
    });

    const ITEMS = [
      {
        _isSelected: false,
        id: 1,
        company_name: {
          company_name: 'company Veho',
          company_name_jp: 'company Veho Jp',
        },
        field: '農業、林業、漁業',
        updated_at: '20230724',
        status: 3,
      },
    ];
    await wrapper.setData({ arrlistCompany: ITEMS });

    const CompanyListPage = wrapper.find('.list-company');
    expect(CompanyListPage.exists()).toBe(true);

    const title_company_list = wrapper.find('.title-company-list');
    expect(title_company_list.exists()).toBe(true);
    expect(title_company_list.text()).toBe('TITLE.COMPANY_LIST');

    const company_list_table = wrapper.find('#company-list-table');
    expect(company_list_table.exists()).toBe(true);

    const sort_btn = company_list_table.findAll('.sr-only');
    expect(sort_btn.exists()).toBe(true);

    wrapper.destroy();

    //
  });

  test('Case 3: Check the function sort by ID', async() => {
    const handleSortTableCompanyList = jest.fn();
    const localVue = createLocalVue();
    const wrapper = mount(CompanyList, {
      localVue,
      store,
      router,
      methods: {
        handleSortTableCompanyList,
      },
    });

    const ITEMS = [
      {
        _isSelected: false,
        id: 1,
        company_name: {
          company_name: 'company Veho',
          company_name_jp: 'company Veho Jp',
        },
        field: '農業、林業、漁業',
        updated_at: '20230724',
        status: 3,
      },
    ];
    await wrapper.setData({ arrlistCompany: ITEMS });
    // expect(wrapper).toMatchSnapshot();

    const company_list_table = wrapper.find('#company-list-table');
    expect(company_list_table.exists()).toBe(true);

    const sort_btn = company_list_table.findAll('.sr-only');
    expect(sort_btn.exists()).toBe(true);

    if (sort_btn.length > 0) {
      const btn_sort_by_id = sort_btn.at(0);

      expect(btn_sort_by_id.exists()).toBe(true);
      await btn_sort_by_id.trigger('click');

      expect(handleSortTableCompanyList).toHaveBeenCalled();
      expect(handleSortTableCompanyList.mock.lastCall[0].sortBy).toBe('id');
      expect(handleSortTableCompanyList.mock.lastCall[0].sortDesc).toBe(false);

      await btn_sort_by_id.trigger('click');
      expect(handleSortTableCompanyList.mock.lastCall[0].sortDesc).toBe(true);

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

      const PARAMS = {
        field: handleSortTableCompanyList.mock.lastCall[0].sortBy,
        sort_by: handleSortTableCompanyList.mock.lastCall[0].sortDesc,
      };
      await listCompany(PARAMS).then((response) => {
        expect(response['data']['code']).toBe(200);
      });
    }

    wrapper.destroy();
  });

  test('Case 4: Check the function sort by company_name', async() => {
    const handleSortTableCompanyList = jest.fn();
    const localVue = createLocalVue();
    const wrapper = mount(CompanyList, {
      localVue,
      store,
      router,
      methods: {
        handleSortTableCompanyList,
      },
    });

    const ITEMS = [
      {
        _isSelected: false,
        id: 1,
        company_name: {
          company_name: 'company Veho',
          company_name_jp: 'company Veho Jp',
        },
        field: '農業、林業、漁業',
        updated_at: '20230724',
        status: 3,
      },
    ];
    await wrapper.setData({ arrlistCompany: ITEMS });

    const company_list_table = wrapper.find('#company-list-table');
    expect(company_list_table.exists()).toBe(true);

    const sort_btn = company_list_table.findAll('.sr-only');
    expect(sort_btn.exists()).toBe(true);

    if (sort_btn.length > 0) {
      const btn_sort_by_company_name = sort_btn.at(1);

      expect(btn_sort_by_company_name.exists()).toBe(true);
      await btn_sort_by_company_name.trigger('click');

      expect(handleSortTableCompanyList).toHaveBeenCalled();
      expect(handleSortTableCompanyList.mock.lastCall[0].sortBy).toBe('company_name');
      expect(handleSortTableCompanyList.mock.lastCall[0].sortDesc).toBe(false);

      await btn_sort_by_company_name.trigger('click');
      expect(handleSortTableCompanyList.mock.lastCall[0].sortDesc).toBe(true);
    }

    wrapper.destroy();
    //
  });

  test('Case 5: Check the function sort by industry field', async() => {
    const handleSortTableCompanyList = jest.fn();
    const localVue = createLocalVue();
    const wrapper = mount(CompanyList, {
      localVue,
      store,
      router,
      methods: {
        handleSortTableCompanyList,
      },
    });

    const ITEMS = [
      {
        _isSelected: false,
        id: 1,
        company_name: {
          company_name: 'company Veho',
          company_name_jp: 'company Veho Jp',
        },
        field: '農業、林業、漁業',
        updated_at: '20230724',
        status: 3,
      },
    ];
    await wrapper.setData({ arrlistCompany: ITEMS });

    const company_list_table = wrapper.find('#company-list-table');
    expect(company_list_table.exists()).toBe(true);

    const sort_btn = company_list_table.findAll('.sr-only');
    expect(sort_btn.exists()).toBe(true);

    if (sort_btn.length > 0) {
      const btn_sort_by_field = sort_btn.at(2);

      expect(btn_sort_by_field.exists()).toBe(true);
      await btn_sort_by_field.trigger('click');

      expect(handleSortTableCompanyList).toHaveBeenCalled();
      expect(handleSortTableCompanyList.mock.lastCall[0].sortBy).toBe('field');
      expect(handleSortTableCompanyList.mock.lastCall[0].sortDesc).toBe(false);

      await btn_sort_by_field.trigger('click');
      expect(handleSortTableCompanyList.mock.lastCall[0].sortDesc).toBe(true);

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

      const PARAMS = {
        field: handleSortTableCompanyList.mock.lastCall[0].sortBy,
        sort_by: handleSortTableCompanyList.mock.lastCall[0].sortDesc,
      };
      await listCompany(PARAMS).then((response) => {
        expect(response['data']['code']).toBe(200);
      });
    }

    wrapper.destroy();
    //
  });

  test('Case 6: Check the function sort by updating date', async() => {
    const handleSortTableCompanyList = jest.fn();
    const localVue = createLocalVue();
    const wrapper = mount(CompanyList, {
      localVue,
      store,
      router,
      methods: {
        handleSortTableCompanyList,
      },
    });

    const ITEMS = [
      {
        _isSelected: false,
        id: 1,
        company_name: {
          company_name: 'company Veho',
          company_name_jp: 'company Veho Jp',
        },
        field: '農業、林業、漁業',
        updated_at: '20230724',
        status: 3,
      },
    ];
    await wrapper.setData({ arrlistCompany: ITEMS });

    const company_list_table = wrapper.find('#company-list-table');
    expect(company_list_table.exists()).toBe(true);

    const sort_btn = company_list_table.findAll('.sr-only');
    expect(sort_btn.exists()).toBe(true);

    if (sort_btn.length > 0) {
      const btn_sort_by_updated_at = sort_btn.at(3);

      expect(btn_sort_by_updated_at.exists()).toBe(true);
      await btn_sort_by_updated_at.trigger('click');

      expect(handleSortTableCompanyList).toHaveBeenCalled();
      expect(handleSortTableCompanyList.mock.lastCall[0].sortBy).toBe('updated_at');
      expect(handleSortTableCompanyList.mock.lastCall[0].sortDesc).toBe(false);

      await btn_sort_by_updated_at.trigger('click');
      expect(handleSortTableCompanyList.mock.lastCall[0].sortDesc).toBe(true);

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

      const PARAMS = {
        field: handleSortTableCompanyList.mock.lastCall[0].sortBy,
        sort_by: handleSortTableCompanyList.mock.lastCall[0].sortDesc,
      };
      await listCompany(PARAMS).then((response) => {
        expect(response['data']['code']).toBe(200);
      });
    }

    wrapper.destroy();
    //
  });

  test('Case 7: Check the function sort by status', async() => {
    const handleSortTableCompanyList = jest.fn();
    const localVue = createLocalVue();
    const wrapper = mount(CompanyList, {
      localVue,
      store,
      router,
      methods: {
        handleSortTableCompanyList,
      },
    });

    const ITEMS = [
      {
        _isSelected: false,
        id: 1,
        company_name: {
          company_name: 'company Veho',
          company_name_jp: 'company Veho Jp',
        },
        field: '農業、林業、漁業',
        updated_at: '20230724',
        status: 3,
      },
    ];
    await wrapper.setData({ arrlistCompany: ITEMS });

    const company_list_table = wrapper.find('#company-list-table');
    expect(company_list_table.exists()).toBe(true);

    const sort_btn = company_list_table.findAll('.sr-only');
    expect(sort_btn.exists()).toBe(true);

    if (sort_btn.length > 0) {
      const btn_sort_by_status = sort_btn.at(4);

      expect(btn_sort_by_status.exists()).toBe(true);
      await btn_sort_by_status.trigger('click');

      expect(handleSortTableCompanyList).toHaveBeenCalled();
      expect(handleSortTableCompanyList.mock.lastCall[0].sortBy).toBe('status');
      expect(handleSortTableCompanyList.mock.lastCall[0].sortDesc).toBe(false);

      await btn_sort_by_status.trigger('click');
      expect(handleSortTableCompanyList.mock.lastCall[0].sortDesc).toBe(true);

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

      const PARAMS = {
        field: handleSortTableCompanyList.mock.lastCall[0].sortBy,
        sort_by: handleSortTableCompanyList.mock.lastCall[0].sortDesc,
      };
      await listCompany(PARAMS).then((response) => {
        expect(response['data']['code']).toBe(200);
      });
    }

    wrapper.destroy();
    //
  });
  //
});

