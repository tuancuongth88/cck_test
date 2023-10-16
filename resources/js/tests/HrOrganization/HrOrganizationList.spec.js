import { shallowMount, mount } from '@vue/test-utils';
import router from '@/router';
// import {
//   validEmptyOrWhiteSpace,
//   validEmail,
//   validPassword,
//   validateNumberMoreThanZero,
// } from '@/utils/validate';
import HROganizationList from '@/pages/HrOrganization/index.vue';

const CONPONENT_NAME = 'HR Organization List';

describe(`COMPONENT: ${CONPONENT_NAME}`, () => {
  test('Case 1: Test component render screen, title', () => {
    const wrapper = shallowMount(HROganizationList, {
      router,
    });

    const title = wrapper.find('.hr-org-title');
    expect(title.exists()).toBe(true);

    expect(title.text()).toEqual('TITLE.ORGANIZATION_LIST');

    // expect(wrapper.vm.User).toStrictEqual(USER);

    wrapper.destroy();
  });
  test('Case 2: Test component render DATA', () => {
    const wrapper = shallowMount(HROganizationList, {
      router,
    });

    const title = wrapper.find('.hr-org-title');
    expect(title.exists()).toBe(true);

    expect(title.text()).toEqual('TITLE.ORGANIZATION_LIST');

    // expect(wrapper.vm.User).toStrictEqual(USER);

    const OVERLAY = {
      opacity: 0,
      show: true,
      blur: '1rem',
      rounded: 'sm',
      variant: 'light',
      fixed: true,
    };
    const FIELDs = [
      {
        key: 'id',
        sortable: true,
        label: 'ID',
        class: 'id',
        thStyle: { textAlign: 'center' },
      },
      {
        key: 'organization_name',
        sortable: true,
        // label: 'ORGANIZATION',
        label: '団体名',
        class: 'organization_name',
        thStyle: { textAlign: 'center' },
      },
      {
        key: 'classification',
        sortable: true,
        // label: 'Classification',
        label: '区分',
        class: 'classification',
        thStyle: { textAlign: 'center' },
      },
      {
        key: 'updating_date',
        sortable: true,
        // label: 'UPDATING DATE',
        label: '更新日',
        class: 'updating_date',
        thStyle: { textAlign: 'center' },
      },
      {
        key: 'status',
        sortable: true,
        // label: 'STATUS',
        label: 'ステータス',
        class: 'status',
        thStyle: { textAlign: 'center' },
        tdClass: 'text-center',
      },
      {
        key: 'detail',
        sortable: false,
        // label: 'DETAIL',
        label: '詳細',
        class: 'detail',
        thStyle: { textAlign: 'center' },
        tdClass: 'text-center',
      },
    ];
    const ITEMS = [];

    expect(wrapper.vm.overlay).toStrictEqual(OVERLAY);
    expect(wrapper.vm.fields).toStrictEqual(FIELDs);
    expect(wrapper.vm.items).toStrictEqual(ITEMS);

    wrapper.destroy();
  });

  test('Case 3: Test component render table hr org list', () => {
    const wrapper = mount(HROganizationList, {
      router,
    });

    const table_hr_org = wrapper.find('#table-listhr-org');
    expect(table_hr_org.exists()).toBe(true);

    wrapper.destroy();
  });

  test('Case 4: Test function sort table hr org list', async() => {
    const wrapper = mount(HROganizationList, {
      router,
    });

    const table_hr_org = wrapper.find('#table-listhr-org');
    expect(table_hr_org.exists()).toBe(true);

    const sort_btn = table_hr_org.findAll('.sr-only');
    expect(sort_btn.exists()).toBe(true);

    if (sort_btn.length > 0) {
      const one_sort_btn = sort_btn.at(0);
      expect(one_sort_btn.exists()).toBe(true);

      one_sort_btn.trigger('click');
    }
    wrapper.destroy();
  });

  test('Case 5: Test function go to detail hr org list', async() => {
    const wrapper = mount(HROganizationList, {
      router,
    });

    const ITEMS = [
      {
        _isSelected: false,
        id: 3,
        organization_name_ja: 'LOD人材開発株式会社',
        classification: '送り出し機関',
        updating_date: '20230623',
        status: '審査待ち',
      },
    ];

    await wrapper.setData({ items: ITEMS });

    const table_hr_org = wrapper.find('#table-listhr-org');
    expect(table_hr_org.exists()).toBe(true);

    const detail_btn = table_hr_org.find('.btn-go-detail');
    expect(detail_btn.exists()).toBe(true);

    const goToDetail = jest.spyOn(wrapper.vm, 'goToDetail');

    await detail_btn.trigger('click').then(() => {
      expect(goToDetail).toHaveBeenCalled();
    });

    // expect(table_hr_org).toMatchSnapshot();

    wrapper.destroy();
  });
});
