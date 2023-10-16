/* eslint-disable no-unused-vars */
// eslint-disable-next-line no-unused-vars
import { createLocalVue, shallowMount, mount } from '@vue/test-utils';
import store from '@/store';
import router from '@/router';
import Oop from '@/pages/CompanyManagement/Job/index.vue';

import { listJob } from '@/api/job';
import { handleRole } from '@/utils/handleRole';
import { login } from '@/api/auth';

describe('TEST Component  ', () => {
  test('Case 1: Test', () => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(Oop, {
      localVue,
      store,
      router,
    });

    wrapper.destroy();
  });

  test('Case 6: Check API Delete List Job (Company) Selected respone code 200', () => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(Oop, {
      localVue,
      store,
      router,
    });

    wrapper.destroy();
  });
//
});

