import store from '@/store';
import InterviewDecline from '../../../pages/MatchingManagement/InterviewControl/urlSetting.vue';

import { shallowMount, createLocalVue } from '@vue/test-utils';

const localVue = createLocalVue(store);

describe('TEST SCREEN INTERVIEW DECLINE', () => {
  test('Test 1: Renders component with initial data', () => {
    const detailData = {};

    const wrapper = shallowMount(InterviewDecline, {
      localVue,
      propsData: {
        detailData,
      },
    });

    expect(wrapper.exists()).toBe(true);
    expect(wrapper.text()).toContain(detailData.full_name);

    wrapper.destroy();
  });

  test('Test 2: Disables form fields when isOff is true', () => {
    const detailData = {
      status_selection: 3,
    };

    const wrapper = shallowMount(InterviewDecline, {
      localVue,
      propsData: {
        detailData,
      },
    });

    expect(wrapper.find('input[name="url_zoom"]').attributes('disabled')).toBe('disabled');
    expect(wrapper.find('input[name="id_zoom"]').attributes('disabled')).toBe('disabled');
    expect(wrapper.find('input[name="password_zoom"]').attributes('disabled')).toBe('disabled');

    wrapper.destroy();
  });

  test('Test 3: Enables form fields when isOff is false', () => {
    const detailData = {
      status_selection: 1,
    };

    const wrapper = shallowMount(InterviewDecline, {
      localVue,
      propsData: {
        detailData,
      },
    });

    expect(wrapper.find('input[name="url_zoom"]').attributes('disabled')).toBeUndefined();
    expect(wrapper.find('input[name="id_zoom"]').attributes('disabled')).toBeUndefined();
    expect(wrapper.find('input[name="password_zoom"]').attributes('disabled')).toBeUndefined();

    wrapper.destroy();
  });

  test('Test 4: Handles form input changes', async() => {
    const detailData = {};

    const wrapper = shallowMount(InterviewDecline, {
      localVue,
      propsData: {
        detailData,
      },
    });

    await wrapper.setData({
      form_data: {
        url_zoom: 'test-url',
        id_zoom: 'test-id',
        password_zoom: 'test-password',
      },
    });

    expect(wrapper.vm.form_data.url_zoom).toBe('test-url');
    expect(wrapper.vm.form_data.id_zoom).toBe('test-id');
    expect(wrapper.vm.form_data.password_zoom).toBe('test-password');

    wrapper.destroy();
  });

  test('Test 5: Emits event when Save button is clicked', async() => {
    const detailData = {};

    const wrapper = shallowMount(InterviewDecline, {
      localVue,
      propsData: {
        detailData,
      },
    });

    await wrapper.setData({
      form_data: {
        url_zoom: 'test-url',
        id_zoom: 'test-id',
        password_zoom: 'test-password',
      },
    });

    wrapper.find('button').trigger('click');

    expect(wrapper.emitted('save-zoom-settings')).toBeTruthy();
    expect(wrapper.emitted('save-zoom-settings')[0][0]).toEqual({
      url_zoom: 'test-url',
      id_zoom: 'test-id',
      password_zoom: 'test-password',
    });

    wrapper.destroy();
  });

  test('Test 6: Renders component with initial data', () => {
    const detailData = {};

    const wrapper = shallowMount(InterviewDecline, {
      localVue,
      propsData: {
        detailData,
      },
    });

    expect(wrapper.exists()).toBe(true);
    expect(wrapper.text()).toContain(detailData.full_name);

    wrapper.destroy();
  });

  test('Test 7: Disables form fields when isOff is true', () => {
    const detailData = {
      status_selection: 3,
    };

    const wrapper = shallowMount(InterviewDecline, {
      localVue,
      propsData: {
        detailData,
      },
    });

    expect(wrapper.find('input[name="url_zoom"]').attributes('disabled')).toBe('disabled');
    expect(wrapper.find('input[name="id_zoom"]').attributes('disabled')).toBe('disabled');
    expect(wrapper.find('input[name="password_zoom"]').attributes('disabled')).toBe('disabled');

    wrapper.destroy();
  });

  test('Test 8: Enables form fields when isOff is false', () => {
    const detailData = {
      status_selection: 1,
    };

    const wrapper = shallowMount(InterviewDecline, {
      localVue,
      propsData: {
        detailData,
      },
    });

    expect(wrapper.find('input[name="url_zoom"]').attributes('disabled')).toBeUndefined();
    expect(wrapper.find('input[name="id_zoom"]').attributes('disabled')).toBeUndefined();
    expect(wrapper.find('input[name="password_zoom"]').attributes('disabled')).toBeUndefined();

    wrapper.destroy();
  });

  test('Test 9: Handles form input changes', async() => {
    const detailData = {};

    const wrapper = shallowMount(InterviewDecline, {
      localVue,
      propsData: {
        detailData,
      },
    });

    await wrapper.setData({
      form_data: {
        url_zoom: 'test-url',
        id_zoom: 'test-id',
        password_zoom: 'test-password',
      },
    });

    expect(wrapper.vm.form_data.url_zoom).toBe('test-url');
    expect(wrapper.vm.form_data.id_zoom).toBe('test-id');
    expect(wrapper.vm.form_data.password_zoom).toBe('test-password');

    wrapper.destroy();
  });

  test('Test 10: Emits event when Save button is clicked', async() => {
    const detailData = {};

    const wrapper = shallowMount(InterviewDecline, {
      localVue,
      propsData: {
        detailData,
      },
    });

    await wrapper.setData({
      form_data: {
        url_zoom: 'test-url',
        id_zoom: 'test-id',
        password_zoom: 'test-password',
      },
    });

    wrapper.find('button').trigger('click');

    expect(wrapper.emitted('save-zoom-settings')).toBeTruthy();
    expect(wrapper.emitted('save-zoom-settings')[0][0]).toEqual({
      url_zoom: 'test-url',
      id_zoom: 'test-id',
      password_zoom: 'test-password',
    });

    wrapper.destroy();
  });

  test('Test 11: Renders component with initial data', () => {
    const detailData = {};

    const wrapper = shallowMount(InterviewDecline, {
      localVue,
      propsData: {
        detailData,
      },
    });

    expect(wrapper.exists()).toBe(true);
    expect(wrapper.text()).toContain(detailData.full_name);

    wrapper.destroy();
  });

  test('Test 12: Disables form fields when isOff is true', () => {
    const detailData = {
      status_selection: 3,
    };

    const wrapper = shallowMount(InterviewDecline, {
      localVue,
      propsData: {
        detailData,
      },
    });

    expect(wrapper.find('input[name="url_zoom"]').attributes('disabled')).toBe('disabled');
    expect(wrapper.find('input[name="id_zoom"]').attributes('disabled')).toBe('disabled');
    expect(wrapper.find('input[name="password_zoom"]').attributes('disabled')).toBe('disabled');

    wrapper.destroy();
  });

  test('Test 13: Enables form fields when isOff is false', () => {
    const detailData = {
      status_selection: 1,
    };

    const wrapper = shallowMount(InterviewDecline, {
      localVue,
      propsData: {
        detailData,
      },
    });

    expect(wrapper.find('input[name="url_zoom"]').attributes('disabled')).toBeUndefined();
    expect(wrapper.find('input[name="id_zoom"]').attributes('disabled')).toBeUndefined();
    expect(wrapper.find('input[name="password_zoom"]').attributes('disabled')).toBeUndefined();

    wrapper.destroy();
  });

  test('Test 14: Handles form input changes', async() => {
    const detailData = {};

    const wrapper = shallowMount(InterviewDecline, {
      localVue,
      propsData: {
        detailData,
      },
    });

    await wrapper.setData({
      form_data: {
        url_zoom: 'test-url',
        id_zoom: 'test-id',
        password_zoom: 'test-password',
      },
    });

    expect(wrapper.vm.form_data.url_zoom).toBe('test-url');
    expect(wrapper.vm.form_data.id_zoom).toBe('test-id');
    expect(wrapper.vm.form_data.password_zoom).toBe('test-password');

    wrapper.destroy();
  });

  test('Test 15: Emits event when Save button is clicked', async() => {
    const detailData = {};

    const wrapper = shallowMount(InterviewDecline, {
      localVue,
      propsData: {
        detailData,
      },
    });

    await wrapper.setData({
      form_data: {
        url_zoom: 'test-url',
        id_zoom: 'test-id',
        password_zoom: 'test-password',
      },
    });

    wrapper.find('button').trigger('click');

    expect(wrapper.emitted('save-zoom-settings')).toBeTruthy();
    expect(wrapper.emitted('save-zoom-settings')[0][0]).toEqual({
      url_zoom: 'test-url',
      id_zoom: 'test-id',
      password_zoom: 'test-password',
    });

    wrapper.destroy();
  });
});
