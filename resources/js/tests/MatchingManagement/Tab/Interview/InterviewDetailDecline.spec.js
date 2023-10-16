/* eslint-disable no-unused-vars */
import { createLocalVue, shallowMount, mount } from '@vue/test-utils';
import store from '@/store';
import router from '@/router';
import ROLE from '@/const/role.js';

import Oop from '@/pages/MatchingManagement/Tab/offer.vue';
import { getListOffer } from '@/api/modules/matching.js';

import { login } from '@/api/auth';
import { handleRole } from '@/utils/handleRole';

describe('Test component MatchingManagement Tab Entry', () => {
  const fields = [];
  const data = [];
  const localVue = createLocalVue();

  test('Case 1: Check render File Template', async() => {
    const getFuction = jest.fn();
    const wrapper = shallowMount(Oop, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
      created() {
        getFuction();
      },
      data() {
        return {
          // state: 'state,
        };
      },
    });

    // wrapper.destroy();
  });
});
