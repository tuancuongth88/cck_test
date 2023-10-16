
/* eslint-disable no-undef */
import { shallowMount, createLocalVue } from '@vue/test-utils';
import store from '@/store';
import Header from '@/layout/components/Header.vue';

describe('Test Component Logout', () => {
  const localVue = createLocalVue();

  const handleLogout = jest.fn();
  const wrapper = shallowMount(Header, {
    localVue,
    store,
    methods: {
      handleLogout,
    },
  });
  const TOKEN = '';

  test('Case 1: Check render UI', async() => {
    const btnLogOut = wrapper.find('.btn-logout');
    expect(btnLogOut.exists());
  });

  test('Case 2: Check call function handleLogout', async() => {
    wrapper.find('.btn-logout').trigger('click');
    expect(handleLogout).toHaveBeenCalled();
  });

  test('Case 3: Check function dispatch logout', async() => {
    await store.dispatch('user/logout');
    expect(store.getters.token).toBe(TOKEN);
  });
});
