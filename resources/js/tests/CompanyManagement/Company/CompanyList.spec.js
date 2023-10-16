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

    wrapper.destroy();
    //
  });

  test('Case 2: Check render component Company List', () => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(CompanyList, {
      localVue,
      store,
      router,
    });

    const CompanyListPage = wrapper.find('.list-company');
    expect(CompanyListPage.exists()).toBe(true);

    const companyTable = wrapper.find('.list-company-table-wrap');
    expect(companyTable.exists()).toBe(true);

    const title_company_list = wrapper.find('.title-company-list');
    expect(title_company_list.exists()).toBe(true);
    expect(title_company_list.text()).toBe('TITLE.COMPANY_LIST');

    wrapper.destroy();

    //
  });

  test('Case 3: Check function get list company respone code 200', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(CompanyList, {
      localVue,
      store,
      router,
    });
    // eslint-disable-next-line no-unused-vars
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

    // eslint-disable-next-line no-unused-vars
    // const api = listCompany;

    await listCompany()
      .then((response) => {
        // console.log('listCompany response: ', response.data.code);
        expect(response['data']['code']).toBe(200);
      });

    wrapper.destroy();
    //
  });

  test('Case 4: Test component render DATA', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(CompanyList, {
      localVue,
      store,
      router,
    });

    const overlay = {
      opacity: 0,
      show: false,
      blur: '1rem',
      rounded: 'sm',
      variant: 'light',
      fixed: true,
    };

    const fieldslistCompany = [
      // 1 id
      {
        key: 'id',
        sortable: true,
        label: 'ID',
        class: 'id',
        thStyle: { textAlign: 'center' },
        thClass: 'col-1',
      },
      // 2 団体名 Organization name
      {
        key: 'company_name',
        sortable: true,
        label: '企業名',
        class: 'company_name',
        thStyle: { textAlign: 'center' },
        thClass: 'col-2',
      },
      // 3 業種分野 Field - Ngành / Lĩnh vực
      {
        key: 'field',
        sortable: true,
        label: '業種分野',
        class: 'field',
        thStyle: { textAlign: 'center' },
        thClass: 'col-3',
      },
      // 4 更新日 Updating date
      {
        key: 'updated_at',
        sortable: true,
        label: '更新日',
        class: 'updated_at',
        thStyle: { textAlign: 'center' },
        thClass: 'col-4',
      },
      // 5 Status ステータス
      {
        key: 'status',
        sortable: true,
        label: 'ステータス',
        class: 'status',
        thStyle: { textAlign: 'center' },
        tdClass: 'text-center',
        thClass: 'col-5',
      },
      // 6 詳細 Detail
      {
        key: 'detail',
        sortable: false,
        label: '詳細',
        class: 'detail',
        thStyle: { textAlign: 'center' },
        tdClass: 'text-center',
        thClass: 'col-6',
      },
    ];

    const arrlistCompany = [];

    await wrapper.setData({ overlay: overlay });
    await wrapper.setData({ fieldslistCompany: fieldslistCompany });
    await wrapper.setData({ arrlistCompany: arrlistCompany });

    expect(wrapper.vm.overlay).toEqual(overlay);
    expect(wrapper.vm.fieldslistCompany).toEqual(fieldslistCompany);
    expect(wrapper.vm.arrlistCompany).toEqual(arrlistCompany);

    //
  });

  test('Case 5: Test component render table hr org list', async() => {
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
    // expect(wrapper).toMatchSnapshot();

    const company_list_table = wrapper.find('#company-list-table');
    expect(company_list_table.exists()).toBe(true);

    wrapper.destroy();
  });

  test('Case 6: Check sort table', async() => {
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
    // expect(wrapper).toMatchSnapshot();

    const company_list_table = wrapper.find('#company-list-table');
    expect(company_list_table.exists()).toBe(true);

    const sort_btn = company_list_table.findAll('.sr-only');
    expect(sort_btn.exists()).toBe(true);

    if (sort_btn.length > 0) {
      const one_sort_btn = sort_btn.at(0);
      expect(one_sort_btn.exists()).toBe(true);

      one_sort_btn.trigger('click');
    }

    wrapper.destroy();
    //
  });

  test('Case 7: Check render component btnSeeDetail', async() => {
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
    // expect(wrapper).toMatchSnapshot();

    const company_list_table = wrapper.find('#company-list-table');
    expect(company_list_table.exists()).toBe(true);

    const detail_btn = company_list_table.find('.btn-go-detail');
    expect(detail_btn.exists()).toBe(true);

    const goToDetail = jest.spyOn(wrapper.vm, 'goToDetail');

    await detail_btn.trigger('click').then(() => {
      expect(goToDetail).toHaveBeenCalled();
    });

    wrapper.destroy();

    //
  });
  //
});

