import { shallowMount, createLocalVue, mount } from '@vue/test-utils';
import router from '@/router';
import {
  validEmptyOrWhiteSpace,
  validEmail,
  validPassword,
  validateNumberMoreThanZero,
} from '@/utils/validate';
import UserManagementCreate from '@/pages/UserManagement/Create.vue';

const CONPONENT_NAME = 'User Management Create';

describe(`COMPONENT: ${CONPONENT_NAME}`, () => {
  test('Case 1: Test component render DATA', () => {
    const wrapper = shallowMount(UserManagementCreate);

    const USER = {
      name: '',
      email: '',
      password: '',
      authority: null,
    };

    expect(wrapper.vm.User).toStrictEqual(USER);

    const OVERLAY = {
      show: false,
      variant: 'light',
      opacity: 1,
      blur: '1rem',
      rounded: 'sm',
    };

    expect(wrapper.vm.overlay).toStrictEqual(OVERLAY);

    wrapper.destroy();
  });

  test('Case 2: Test component render LAYOUT - HTML', () => {
    const wrapper = shallowMount(UserManagementCreate);

    const CLASS_COMPONENT = wrapper.find('.display-user-management-create');
    expect(CLASS_COMPONENT.exists()).toBe(true);

    const BUTTON_BACK = CLASS_COMPONENT.find('.btn-return');
    expect(BUTTON_BACK.exists()).toBe(true);

    const ZONE_INPUT = CLASS_COMPONENT.find('.display-create-user');
    expect(ZONE_INPUT.exists()).toBe(true);

    const BUTTON_SIGN_UP = CLASS_COMPONENT.find('.btn-sign-up');
    expect(BUTTON_SIGN_UP.exists()).toBe(true);

    wrapper.destroy();
  });

  test('Case 3: Test component render BUTTON BACK', () => {
    const wrapper = shallowMount(UserManagementCreate);

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

  test('Case 4: Test component when click BUTTON BACK', () => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(UserManagementCreate, {
      localVue,
      router,
    });

    const handleBackList = jest.spyOn(wrapper.vm, 'handleBackList');

    const BUTTON_BACK = wrapper.find('.btn-return');
    BUTTON_BACK.trigger('click');

    expect(handleBackList).toHaveBeenCalled();

    wrapper.destroy();
  });

  test('Case 5: Test component render FORM CREATE', () => {
    const wrapper = mount(UserManagementCreate);

    const ZONE_INPUT = wrapper.find('.zone-create-user');
    expect(ZONE_INPUT.exists()).toBe(true);

    const LIST_LABEL = ZONE_INPUT.findAll('label');

    const LIST_LABEL_TEXT = [
      'USER_MANAGEMENT_CREATE_NAME_REQUIRED',
      'USER_MANAGEMENT_CREATE_EMAIL_REQUIRED',
      'USER_MANAGEMENT_CREATE_PASSWORD_REQUIRED',
      'USER_MANAGEMENT_CREATE_AUTHORITY_REQUIRED',
    ];

    for (let label = 0; label < LIST_LABEL.length; label++) {
      expect(LIST_LABEL.at(label).text()).toEqual(LIST_LABEL_TEXT[label]);
    }

    const LIST_INPUT = wrapper.findAll('input');
    expect(LIST_INPUT.length).toEqual(3);

    const INPUT_NAME = wrapper.find('#input-create-user-name');
    expect(INPUT_NAME.exists()).toBe(true);
    expect(INPUT_NAME.props('type')).toEqual('text');
    expect(INPUT_NAME.props('disabled')).toBe(false);
    expect(INPUT_NAME.classes('validate-warning')).toBe(true);

    const INPUT_EMAIL = wrapper.find('#input-create-user-email');
    expect(INPUT_EMAIL.exists()).toBe(true);
    expect(INPUT_EMAIL.props('type')).toEqual('text');
    expect(INPUT_EMAIL.props('disabled')).toBe(false);
    expect(INPUT_EMAIL.classes('validate-warning')).toBe(true);

    const INPUT_PASSWORD = wrapper.find('#input-create-user-password');
    expect(INPUT_PASSWORD.exists()).toBe(true);
    expect(INPUT_PASSWORD.props('type')).toEqual('password');
    expect(INPUT_PASSWORD.props('disabled')).toBe(false);
    expect(INPUT_PASSWORD.classes('validate-warning')).toBe(true);

    const SELECT_AUTHORITY = wrapper.find('#input-create-user-authority');
    expect(SELECT_AUTHORITY.exists()).toBe(true);
    expect(SELECT_AUTHORITY.props('disabled')).toBe(false);
    expect(SELECT_AUTHORITY.classes('validate-warning')).toBe(true);

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

    const LIST_AUTHORITY = SELECT_AUTHORITY.findAll('option');
    for (let authority = 0; authority < LIST_AUTHORITY.length; authority++) {
      expect(LIST_AUTHORITY.at(authority).text()).toEqual(LIST_AUTHORITY_TEXT[authority]);
      expect(LIST_AUTHORITY.at(authority).element.value).toEqual(LIST_AUTHORITY_VALUE[authority]);
    }

    wrapper.destroy();
  });

  test('Case 6: Test function validate input', () => {
    expect(validEmptyOrWhiteSpace('')).toBe(true);
    expect(validEmptyOrWhiteSpace('     ')).toBe(true);
    expect(validEmptyOrWhiteSpace('Duck')).toBe(false);
    expect(validEmptyOrWhiteSpace('Vu Duck')).toBe(false);

    expect(validEmail('')).toBe(false);
    expect(validEmail('   ')).toBe(false);
    expect(validEmail('test')).toBe(false);
    expect(validEmail('123456789')).toBe(false);
    expect(validEmail('test@')).toBe(false);
    expect(validEmail('test@gmail')).toBe(false);
    expect(validEmail('test@gamil.')).toBe(false);
    expect(validEmail('test@gmail.123')).toBe(false);
    expect(validEmail('test@123.123')).toBe(false);
    expect(validEmail('test#gamil.com')).toBe(false);
    expect(validEmail('test@gmail.com')).toBe(true);
    expect(validEmail('test@outlook.com')).toBe(true);
    expect(validEmail('test@apple.com')).toBe(true);
    expect(validEmail('test@gmail.uk')).toBe(true);
    expect(validEmail('test@veho.edu.vn')).toBe(true);

    expect(validPassword('')).toBe(false);
    expect(validPassword('   ')).toBe(false);
    expect(validPassword(' * ')).toBe(false);
    expect(validPassword('1')).toBe(false);
    expect(validPassword('12')).toBe(false);
    expect(validPassword('123')).toBe(false);
    expect(validPassword('1234')).toBe(false);
    expect(validPassword('12345')).toBe(false);
    expect(validPassword('123456')).toBe(false);
    expect(validPassword('1234567')).toBe(false);
    expect(validPassword('12345678901234567')).toBe(false);
    expect(validPassword('123456789012345678')).toBe(false);
    expect(validPassword('1234567890123456789')).toBe(false);
    expect(validPassword('123 456 789')).toBe(false);
    expect(validPassword('12345678')).toBe(true);
    expect(validPassword('123456789')).toBe(true);
    expect(validPassword('1234567890')).toBe(true);
    expect(validPassword('12345678901')).toBe(true);
    expect(validPassword('123456789012')).toBe(true);
    expect(validPassword('1234567890123')).toBe(true);
    expect(validPassword('12345678901234')).toBe(true);
    expect(validPassword('123456789012345')).toBe(true);
    expect(validPassword('1234567890123456')).toBe(true);
    expect(validPassword('!@#$%^&*%%%%%')).toBe(true);

    expect(validateNumberMoreThanZero(0)).toBe(false);
    expect(validateNumberMoreThanZero(-1)).toBe(false);
    expect(validateNumberMoreThanZero(-1000)).toBe(false);
    expect(validateNumberMoreThanZero(1)).toBe(true);
    expect(validateNumberMoreThanZero(1310)).toBe(true);
  });

  test('Case 7: Test component render button SIGN UP', () => {
    const wrapper = mount(UserManagementCreate);

    const BUTTON_SIGN_UP = wrapper.find('.btn-sign-up');
    expect(BUTTON_SIGN_UP.exists()).toBe(true);
    expect(BUTTON_SIGN_UP.find('button').text()).toEqual('USER_MANAGEMENT_CREATE_BUTTON_SIGN_UP');

    wrapper.destroy();
  });

  test('Case 8: Test component when click button SIGN UP', async() => {
    const mockRouter = {
      push: jest.fn(),
    };

    const wrapper = mount(UserManagementCreate, {
      User: {
        name: 'Vu Duc Viet',
        email: 'vuducviet0131@gmail.com',
        password: '123456789',
        authority: 1,
      },
      global: {
        mocks: {
          $router: mockRouter,
        },
      },
    });

    const ZONE_BUTTON_SIGN_UP = wrapper.find('.btn-sign-up');
    const BUTTON_SIGN_UP = ZONE_BUTTON_SIGN_UP.find('button');
    expect(BUTTON_SIGN_UP.exists()).toBe(true);

    const handleCreate = jest.spyOn(wrapper.vm, 'handleCreate');

    await BUTTON_SIGN_UP.trigger('click');
    expect(handleCreate).toHaveBeenCalled();

    wrapper.destroy();
  });
});
