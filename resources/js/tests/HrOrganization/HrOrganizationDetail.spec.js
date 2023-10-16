import { shallowMount, mount } from '@vue/test-utils';
import router from '@/router';
// import {
//   validEmptyOrWhiteSpace,
//   validEmail,
//   validPassword,
//   validateNumberMoreThanZero,
// } from '@/utils/validate';
import HROganizationDetail from '@/pages/HrOrganization/detail.vue';

const CONPONENT_NAME = 'HR Organization Detail';

describe(`COMPONENT: ${CONPONENT_NAME}`, () => {
  test('Case 1: Test component render screen, title', () => {
    const wrapper = shallowMount(HROganizationDetail, {
      router,
    });

    const title = wrapper.find('.hr-org-detail-title');
    expect(title.exists()).toBe(true);

    expect(title.text()).toEqual('TITLE.ORGANIZATION_DETAIL');

    // expect(wrapper.vm.User).toStrictEqual(USER);

    wrapper.destroy();
  });
  test('Case 2: Test component render DATA', () => {
    const wrapper = shallowMount(HROganizationDetail, {
      router,
    });

    const OVERLAY = {
      opacity: 0,
      show: true,
      blur: '1rem',
      rounded: 'sm',
      variant: 'light',
      fixed: true,
    };

    const ITEM_DETAIL = {
      account_classification: '',
      account_classification_name: '',
      assignee_contact: '',
      certificate_file_id: 0,
      corporate_name_en: '',
      corporate_name_ja: '',
      country: 0,
      country_name: '',
      // created_at: "",
      // deleted_at: null,
      file: {
        id: 0,
        file_name: '',
        file_path: '',
      },
      license_no: '',
      mail_address: '',
      municipality: '',
      number: '',
      other: '',
      post_code: '',
      prefectures: '',
      representative_contact: '',
      representative_full_name: '',
      representative_full_name_furigana: '',
      status: 0,
      status_text: '',
      updated_at: '',
      url: '',
      user_id: 0,
    };

    expect(wrapper.vm.overlay).toStrictEqual(OVERLAY);
    expect(wrapper.vm.itemDetail).toStrictEqual(ITEM_DETAIL);

    wrapper.destroy();
  });

  test('Case 3: Test component render button back to list', () => {
    const wrapper = mount(HROganizationDetail, {
      router,
    });

    const btn_back_to_list = wrapper.find('.back-to-hr-org-list');
    expect(btn_back_to_list.exists()).toBe(true);

    wrapper.destroy();
  });

  test('Case 4: Test click button back to list', async() => {
    const wrapper = mount(HROganizationDetail, {
      router,
    });

    const btn_back_to_list = wrapper.find('.back-to-hr-org-list');
    expect(btn_back_to_list.exists()).toBe(true);

    const handleBackList = jest.spyOn(wrapper.vm, 'handleBackList');

    if (expect(btn_back_to_list.exists()).toBe(true)) {
      await btn_back_to_list.trigger('click');
      expect(handleBackList).toHaveBeenCalled();
    }
    wrapper.destroy();
  });

  test('Case 5: Test component render button to edit', () => {
    const wrapper = mount(HROganizationDetail, {
      router,
    });

    const to_edit = wrapper.find('.edit-hr-org');
    expect(to_edit.exists()).toBe(true);

    wrapper.destroy();
  });

  test('Case 6: Test click button go to edit', async() => {
    const wrapper = mount(HROganizationDetail, {
      router,
    });

    const to_edit = wrapper.find('.edit-hr-org');
    expect(to_edit.exists()).toBe(true);

    const handleGoToEdit = jest.spyOn(wrapper.vm, 'handleGoToEdit');

    if (expect(to_edit.exists()).toBe(true)) {
      await to_edit.trigger('click');
      expect(handleGoToEdit).toHaveBeenCalled();
    }
    wrapper.destroy();
  });
});
