/* eslint-disable no-unused-vars */
import { createLocalVue, shallowMount, mount } from '@vue/test-utils';
import store from '@/store';
import router from '@/router';
import CompanyDetailOop from '@/pages/CompanyManagement/Company/form.vue';
import CompanyBasicInfoOop from '@/pages/CompanyManagement/Company/CompanyBasicInfo/index.vue';
import CompanyDetailInfoOop from '@/pages/CompanyManagement/Company/CompanyDetailInfo/index.vue';

import { detailCompany } from '@/api/company';
import { companyRegister } from '@/api/company.js';
import { handleRole } from '@/utils/handleRole';
import { login } from '@/api/auth';

describe('Check Component Company list', () => {
  let idCompanyGlobal = null;

  test('Case 1: Check render Company Detail Template', () => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(CompanyDetailOop, {
      localVue,
      store,
      router,
    });

    expect(typeof CompanyDetailOop.data).toBe('function');

    const CompanyListTemplate = wrapper.findComponent({ name: 'CompanyDetail' });
    expect(CompanyListTemplate.exists()).toBe(true);

    //
    wrapper.destroy();
  });

  test('Case 2: Check render components Company Detail', () => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(CompanyDetailOop, {
      localVue,
      store,
      router,
    });

    const CompanyEditPage = wrapper.find('.company-edit');
    expect(CompanyEditPage.exists()).toBe(true);

    wrapper.destroy();
  });

  test('Case 3: Check API Company Detail respone code 200', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(CompanyDetailOop, {
      localVue,
      store,
      router,
    });

    const params = {
      mail_address: '1okuridashi_hanoi@gmail.vn',
      password: '123456789CCk',
    };

    // return;

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

    // Create
    const randomNumbers = Array.from({ length: 1 }, () => Math.floor(Math.random() * 100));
    randomNumbers.sort((a, b) => a - b);
    // console.log('randomNumbers: ', randomNumbers);

    const previewFormData = {
      company_name: `companyName${randomNumbers}`,
      company_name_jp: `comNamejp${randomNumbers}`,
      major_classification: 1,
      middle_classification: 2,
      post_code: `102${randomNumbers}${randomNumbers}`,
      prefectures: 'prefectures',
      municipality: 'municipality',
      number: '1-7-10',
      other: 'other',
      telephone_number: `+84 031234${randomNumbers}${randomNumbers}`,
      mail_address: `mail_address${randomNumbers}${randomNumbers}${randomNumbers}${randomNumbers}@gmail.vn`,
      url: 'https://okuridashi_hanoi.com',
      job_title: 'job_title',
      full_name: 'full_name',
      full_name_furigana: 'full_name_furigana',
      representative_contact: `+84 031234${randomNumbers}${randomNumbers}`,
      assignee_contact: `+84 031234${randomNumbers}${randomNumbers}`,
      is_create: 1,
    };
    // Detail
    let idCompany = null; // Fake
    await companyRegister(previewFormData)
      .then((resCompanyRegister) => {
        const id = resCompanyRegister['data']['data'].id;
        if (id) {
          idCompany = id;
          idCompanyGlobal = id;
          expect(resCompanyRegister['data']['code']).toBe(200);
        }
      });

    if (idCompany) {
      await detailCompany(idCompany)
        .then((response) => {
          console.log('detailCompany response: ', response);
          expect(response['data']['code']).toBe(200);
        });
    }

    wrapper.destroy();
    //
  });

  test('Case 4: Check render component Company Detail Block Change Status', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(CompanyDetailOop, {
      localVue,
      store,
      router,
    });

    const idCompanyComponent = wrapper.find('#id-company');
    expect(idCompanyComponent.exists()).toBe(true);

    const changeStatusCompanyComponent = wrapper.find('#change-status-company');
    expect(changeStatusCompanyComponent.exists()).toBe(true);

    await wrapper.setData({ id_company: idCompanyGlobal });
    expect(wrapper.vm.id_company).toStrictEqual(idCompanyGlobal);

    wrapper.destroy();
    //
  });

  test('Case 5: Check render button back to list and edit', () => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(CompanyDetailOop, {
      localVue,
      store,
      router,
    });

    wrapper.destroy();
    //
  });

  test('Case 6: Check render Company Detail Block Detail info', () => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(CompanyDetailOop, {
      localVue,
      store,
      router,
    });

    const companyEditContentComponent = wrapper.find('.company-edit-content');
    expect(companyEditContentComponent.exists()).toBe(true);

    wrapper.destroy();
    //
  });

  test('Case 7: Check render Company Detail Tab CompanyBasicInfo', () => {
    const localVue = createLocalVue();
    const wrapperCompanyDetailOop = shallowMount(CompanyDetailOop, { localVue, store, router });
    // const wrapperCompanyBasicInfo = shallowMount(CompanyBasicInfoOop, { localVue, store, router });
    // const wrapperCompanyDetailInfo = shallowMount(CompanyDetailInfoOop, { localVue, store, router });

    const CompanyEditPage = wrapperCompanyDetailOop.find('.company-edit');
    expect(CompanyEditPage.exists()).toBe(true);

    // const companyBasicInfoPage = wrapperCompanyBasicInfo.find('#company-basic-info-page');
    // expect(companyBasicInfoPage.exists()).toBe(true);

    // const companydetailInfoPage = wrapperCompanyDetailInfo.find('#company-detail-info-page');
    // expect(companydetailInfoPage.exists()).toBe(true);

    // Set prop

    // wrapperCompanyDetailOop.destroy();
    // wrapperCompanyBasicInfo.destroy();

    const tabCompanybasicInfo = wrapperCompanyDetailOop.find('#company-basic-info');
    expect(tabCompanybasicInfo.exists()).toBe(true);
    //
  });

  test('Case 8: Check render Company Detail Tab CompanyDetailInfo', () => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(CompanyDetailOop, {
      localVue,
      store,
      router,
    });

    const tabCompanyDetailInfo = wrapper.find('#company-detail-info');
    expect(tabCompanyDetailInfo.exists()).toBe(true);

    wrapper.destroy();
    //
  });

  //
});

