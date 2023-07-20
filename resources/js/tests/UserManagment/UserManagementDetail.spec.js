import { shallowMount, createLocalVue, mount } from '@vue/test-utils';
import router from '@/router';
import UserManagementDetail from '@/pages/UserManagement/Detail.vue';

const CONPONENT_NAME = 'User Management Detail';

describe(`COMPONENT: ${CONPONENT_NAME}`, () => {
  test('Case 1: Test component render DATA', async() => {
    const wrapper = shallowMount(UserManagementDetail);

    const USER = {
      id: '',
      name: '',
      email: '',
      authority: null,
    };
    expect(wrapper.vm.User).toStrictEqual(USER);

    const SHOW_MODAL = false;
    expect(wrapper.vm.showModal).toBe(SHOW_MODAL);

    wrapper.destroy();
  });

  test('Case 2: Test component render LAYOUT', () => {
    const wrapper = shallowMount(UserManagementDetail);

    const LAYOUT = wrapper.find('.display-user-management-detail');
    expect(LAYOUT.exists()).toBe(true);

    const BUTTON_BACK = wrapper.find('.btn-return');
    expect(BUTTON_BACK.exists()).toBe(true);

    const ZONE_CONTROL = wrapper.find('.display-control');
    expect(ZONE_CONTROL.exists()).toBe(true);

    const ZONE_DISPLAY = wrapper.find('.display-detail-user');
    expect(ZONE_DISPLAY.exists()).toBe(true);

    wrapper.destroy();
  });

  test('Case 3: Test component render button BACK', () => {
    const wrapper = shallowMount(UserManagementDetail);

    const BUTTON_BACK = wrapper.find('.btn-return');
    expect(BUTTON_BACK.exists()).toBe(true);

    const ICON_RETURN = BUTTON_BACK.find('i');

    expect(ICON_RETURN.exists()).toBe(true);
    expect(ICON_RETURN.classes('fad')).toBe(true);
    expect(ICON_RETURN.classes('fa-backward')).toBe(true);

    const TEXT_RETURN = BUTTON_BACK.find('span');
    expect(TEXT_RETURN.text()).toEqual('BUTTON_RETURN');

    wrapper.destroy();
  });

  test('Case 4: Test component render button EDIT, DELETE', () => {
    const wrapper = shallowMount(UserManagementDetail);

    const ZONE_CONTROL = wrapper.find('.display-control');
    expect(ZONE_CONTROL.exists()).toBe(true);

    const BTN_EDIT = wrapper.find('.btn-edit');
    expect(BTN_EDIT.exists()).toBe(true);
    const ICON_BTN_EDIT = BTN_EDIT.find('i');
    expect(ICON_BTN_EDIT.classes('fas')).toBe(true);
    expect(ICON_BTN_EDIT.classes('fa-edit')).toBe(true);
    const TEXT_BTN_EDIT = BTN_EDIT.find('span');
    expect(TEXT_BTN_EDIT.text()).toEqual('BUTTON_EDIT');

    const BTN_DELETE = wrapper.find('.btn-delete');
    expect(BTN_DELETE.exists()).toBe(true);
    const ICON_BTN_DELETE = BTN_DELETE.find('i');
    expect(ICON_BTN_DELETE.classes('fas')).toBe(true);
    expect(ICON_BTN_DELETE.classes('fa-trash-alt')).toBe(true);
    const TEXT_BTN_DELETE = BTN_DELETE.find('span');
    expect(TEXT_BTN_DELETE.text()).toEqual('BUTTON_DELETE');

    wrapper.destroy();
  });

  test('Case 5: Test component render ZONE DETAIL', () => {
    const wrapper = shallowMount(UserManagementDetail);

    const ZONE_DETAIL = wrapper.find('.zone-detail-user');
    expect(ZONE_DETAIL.exists()).toBe(true);

    const LIST_LABEL_INPUT = wrapper.findAll('label');
    const LIST_LABEL_INPUT_TEXT = [
      'USER_MANAGEMENT_CREATE_NAME_REQUIRED',
      'USER_MANAGEMENT_CREATE_EMAIL_REQUIRED',
      'USER_MANAGEMENT_CREATE_AUTHORITY_REQUIRED',
    ];
    for (let label = 0; label < LIST_LABEL_INPUT.length; label++) {
      expect(LIST_LABEL_INPUT.at(label).text()).toEqual(LIST_LABEL_INPUT_TEXT[label]);
    }

    const INPUT_NAME = wrapper.find('#input-create-user-email');
    expect(INPUT_NAME.props('disabled')).toBe(true);

    const INPUT_EMAIL = wrapper.find('#input-create-user-email');
    expect(INPUT_EMAIL.props('disabled')).toBe(true);

    const INPUT_AUTHORITY = wrapper.find('#input-create-user-authority');
    expect(INPUT_AUTHORITY.props('disabled')).toBe(true);

    const LIST_AUTHORITY_TEXT = [
      'PLEASE_SELECT_TEXT',
      'general_affairs',
      'vehicle_analysis',
      'information_systems',
      'center_manager',
      'viewer',
    ];

    const LIST_AUTHORITY_VALUE = [
      '',
      '1',
      '2',
      '3',
      '4',
      '5',
    ];

    const LIST_AUTHORITY = INPUT_AUTHORITY.findAll('option');
    for (let authority = 0; authority < LIST_AUTHORITY.length; authority++) {
      expect(LIST_AUTHORITY.at(authority).text()).toEqual(LIST_AUTHORITY_TEXT[authority]);
      expect(LIST_AUTHORITY.at(authority).element.value).toEqual(LIST_AUTHORITY_VALUE[authority]);
    }

    wrapper.destroy();
  });

  test('Case 6: Test component when click button BACK', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(UserManagementDetail, {
      localVue,
      router,
    });

    const handleBackList = jest.spyOn(wrapper.vm, 'handleBackList');
    const BTN_BACK = wrapper.find('.btn-return');

    await BTN_BACK.find('span').trigger('click');

    expect(handleBackList).toHaveBeenCalled();

    wrapper.destroy();
  });

  test('Case 7: Test componet when click button EDIT', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(UserManagementDetail, {
      localVue,
      router,
    });

    const handleGoToEdit = jest.spyOn(wrapper.vm, 'handleGoToEdit');
    const BTN_EDIT = wrapper.find('.btn-edit');

    await BTN_EDIT.trigger('click');

    expect(handleGoToEdit).toHaveBeenCalled();

    wrapper.destroy();
  });

  test('Case 8: Test component when click button DELETE show Modal', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(UserManagementDetail, {
      localVue,
      router,
    });

    const BTN_DELETE = wrapper.find('.btn-delete');
    await BTN_DELETE.trigger('click');

    expect(wrapper.vm.showModal).toBe(true);

    wrapper.destroy();
  });

  test('Case 9: Test component when confirm modal delete', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(UserManagementDetail, {
      localVue,
      router,
    });

    const handleDelete = jest.spyOn(wrapper.vm, 'handleDelete');

    const BTN_DELETE = wrapper.find('.btn-delete');
    await BTN_DELETE.trigger('click');

    expect(wrapper.vm.showModal).toBe(true);

    const BTN_CANCEL = wrapper.find('.modal-btn-cancel');

    await BTN_CANCEL.trigger('click');

    expect(wrapper.vm.showModal).toBe(false);

    await BTN_DELETE.trigger('click');

    const BTN_CF_DELETE = wrapper.find('.modal-btn-delete');

    await BTN_CF_DELETE.trigger('click');

    expect(handleDelete).toHaveBeenCalled();

    wrapper.destroy();
  });

  test('Case 10: Test componet render with data demo', async() => {
    const wrapper = mount(UserManagementDetail);
    const USER = {
      id: 1,
      name: 'Vu Duc Viet',
      email: 'vuducviet0131@gmail.com',
      authority: '1',
    };

    await wrapper.setData({ User: USER });

    const INPUT_NAME = wrapper.find('#input-create-user-name');
    expect(INPUT_NAME.element.value).toEqual(USER.name);

    const INPUT_EMAIL = wrapper.find('#input-create-user-email');
    expect(INPUT_EMAIL.element.value).toEqual(USER.email);

    const SELECT_AUTHORITY = wrapper.find('#input-create-user-authority');
    expect(SELECT_AUTHORITY.element.value).toEqual(USER.authority);

    wrapper.destroy();
  });
});
