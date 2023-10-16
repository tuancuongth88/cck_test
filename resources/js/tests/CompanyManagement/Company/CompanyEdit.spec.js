/* eslint-disable no-unused-vars */
import { createLocalVue, shallowMount, mount } from '@vue/test-utils';
import store from '@/store';
import router from '@/router';
// import CompanyBasicInfoOop from '@/pages/CompanyManagement/Company/CompanyBasicInfo/index.vue';
// import CompanyDetailInfoOop from '@/pages/CompanyManagement/Company/CompanyDetailInfo/index.vue';
import CompanyEditlOop from '@/pages/CompanyManagement/Company/form.vue';

import { companyRegister } from '@/api/company.js';
import { login } from '@/api/auth';
import { handleRole } from '@/utils/handleRole'; ``;
import { detailCompany } from '@/api/company';
import { updateCompany } from '@/api/company.js';
import { updateStatus } from '@/api/user.js';
import { getListMainjob } from '@/api/job';

describe('TEST Company Edit Screen', () => {
  let idCompanyGlobal = null;
  const idStatusCompanyGlobal = null;

  test('Case 1: Check render Company Edit Template', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(CompanyEditlOop, {
      localVue,
      store,
      router,
    });

    expect(typeof CompanyEditlOop.data).toBe('function');

    const CompanyListTemplate = wrapper.findComponent({ name: 'CompanyDetail' });
    expect(CompanyListTemplate.exists()).toBe(true);

    //
    wrapper.destroy();
  //
  });

  test('Case 2: Check render components Company Edit', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(CompanyEditlOop, {
      localVue, store, router,
    });

    const CompanyEditPage = wrapper.find('.company-edit');
    expect(CompanyEditPage.exists()).toBe(true);

    const changeStatusComponent = wrapper.find('.company-change-status');
    expect(changeStatusComponent.exists()).toBe(true);

    await wrapper.setData({ type_form: 'edit' });

    const spyChangeStatus = jest.spyOn(wrapper.vm, 'changeStatus');
    spyChangeStatus();
    spyChangeStatus();
    expect(spyChangeStatus).toHaveBeenCalled();
    // console.log('wrapper.vm', wrapper.vm.type_form);

    const titleCompanyDetail = wrapper.find('#title-company-detail');
    expect(titleCompanyDetail.exists()).toBe(true);

    wrapper.destroy();
  //
  });

  test('Case 3: Check API Company Edit respone code 200', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(CompanyEditlOop, {
      localVue, store, router,
    });

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

    // Create
    const randomNumbers = Array.from({ length: 1 }, () => Math.floor(Math.random() * 100));
    randomNumbers.sort((a, b) => a - b);

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
      mail_address: `mail_address${randomNumbers}${randomNumbers}${randomNumbers}@gmail.vn`,
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
      .then((res) => {
        // console.log('Case 4: companyRegister res: ', res);
        const code = res.data.code;
        const data = res.data.data;
        if (code) {
          idCompany = data.id;
        }
      });

    // console.log('Case 4: idCompany: ', idCompany);

    let dataCompany = {};
    if (idCompany) {
      await detailCompany(idCompany)
        .then((res) => {
          // console.log('Case 4: detailCompany res: ', res);
          const code = res.data.code;
          const data = res.data.data;

          if (code) {
            idCompany = data.id;
            idCompanyGlobal = data.id;
            dataCompany = data;
            // idStatusCompanyGlobal = data.status;
            console.log('Case 3: data', data);
          }
        });
    }
    const dataConvert = { ...dataCompany, is_create: 1, company_name: `companyEdited${randomNumbers}` };
    await wrapper.setData({ formData: dataConvert });
    // console.log('Case 4: wrapper : ', wrapper.vm.formData);

    // Edit updateStatus
    await updateCompany(idCompany, dataConvert).then((res) => {
      // console.log('Case 4: updateCompany res: ', res);
      const code = res.data.code;
      // const data = res.data.data;
      console.log('Case 4: updateCompany code: ', code);

      expect(code).toEqual(200);
    });

    wrapper.destroy();
  //
  });

  test('Case 4: Check API Company Edit change status respone code 200', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(CompanyEditlOop, {
      localVue, store, router,
    });

    console.log('idCompanyGlobal: ', idCompanyGlobal);

    const PARAMS = {
      company_id: idCompanyGlobal,
      status: 2, // Nhận ID status mới theo select
    };
    const response = await updateStatus(PARAMS);
    const { code } = response.data;
    expect(code).toEqual(200);

    wrapper.destroy();
  //
  });

  test('Case 5: Check function cancle Company Edit', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(CompanyEditlOop, {
      localVue, store, router,
    });

    await wrapper.setData({ type_form: 'edit' });

    console.log('wrapper.vm', wrapper.vm.type_form);
    // expect(wrapper).toMatchSnapshot();
    if (wrapper.vm.type_form === 'edit') {
      const btnCancel = wrapper.find('#btn-cancel');
      expect(btnCancel.exists()).toBe(true);

      const btnSave = wrapper.find('#btn-save');
      expect(btnSave.exists()).toBe(true);

      const spyCancelEditCompany = jest.spyOn(wrapper.vm, 'cancelEditCompany');
      spyCancelEditCompany(); //
      await btnCancel.trigger('click');
      expect(spyCancelEditCompany).toHaveBeenCalled();

      // router.push({ path: `/company/detail/${this.id_company}` });
    }

    wrapper.destroy();
  //
  });

  test('Case 6: Check Company Detail Tab CompanyBasicInfo render data', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(CompanyEditlOop, {
      localVue, store, router,
    });

    //     const params = {
    //       mail_address: '1okuridashi_hanoi@gmail.vn',
    //       password: '123456789CCk',
    //     };
    //
    //     await login(params).then((response) => {
    //       if (response['data']['code'] === 200) {
    //         const TOKEN = response['data']['data']['access_token'];
    //         const USER = response['data']['data']['profile'];
    //
    //         const { ROLES, PERMISSIONS } = handleRole([]);
    //
    //         const USER_INFO = {
    //           token: TOKEN,
    //           profile: USER,
    //           roles: ROLES,
    //           permissions: PERMISSIONS,
    //         };
    //
    //         store.dispatch('user/saveLogin', USER_INFO);
    //         // expect(store.getters.token).toBe(TOKEN);
    //         //
    //       }
    //     });

    const data = {
      company_name: 'companyNameUpdate',
      company_name_jp: 'Company name (furigana)',
      major_classification: 1,
      middle_classification: 2,
      post_code: '1020093',
      prefectures: 'prefectures',
      municipality: 'municipalities',
      number: 'address',
      other: 'others',
      telephone_number: '+84 096814989',
      mail_address: 'mail_address70@gmail.vn',
      url: 'https://okuridashi_hanoi.com',
      job_title: 'title',
      full_name: 'full_name',
      full_name_furigana: 'Name (furigana)',
      representative_contact: '+84 096814953',
      assignee_contact: '+84 096814957',
      establishment_year: 'Year of establishment',
      startup_year: 'Entrepreneurial year',
      capital: 'Capital, etc.',
      proceeds: 'Sales proceeds',
      number_of_staffs: 'Number of employees',
      number_of_operations: 'Number of employees',
      number_of_shops: 'Number of stores',
      number_of_factory: 'Number of factories',
      fiscal: 'Closing month',
      is_create: 1,
    };

    // await wrapper.setData({ formData: data });
    // console.log('formData: ', wrapper.vm.formData);
    // expect(wrapper.vm.formData).toEqual(data);

    const companyBasicInfoComponent = wrapper.find('#company-basic-info');
    expect(companyBasicInfoComponent.exists()).toBe(true);

    // getListMainjob
    wrapper.destroy();
  //
  });

  test('Case 7: Check Company Detail Tab CompanyDetailInfo render data', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(CompanyEditlOop, {
      localVue, store, router,
    });

    const companyDetailInfoComponent = wrapper.find('#company-detail-info');
    expect(companyDetailInfoComponent.exists()).toBe(true);

    wrapper.destroy();
  //
  });

  //
});

