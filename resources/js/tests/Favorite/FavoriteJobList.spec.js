/* eslint-disable no-unused-vars */
import { createLocalVue, shallowMount, mount } from '@vue/test-utils';
import store from '@/store';
import router from '@/router';

import favoriteOop from '@/pages/Favorite/index.vue';
// import favoriteOop from '@/pages/Favorite/Favorite.vue';

import JobFavoriteItemOop from '@/components/FavoriteItem/JobFavoriteItem.vue';
// import HrFavoriteItemOop from '@/components/FavoriteItem/HrFavoriteItem.vue';

import { login } from '@/api/auth';
import { handleRole } from '@/utils/handleRole';
import { getListJob } from '@/api/modules/job';
import { addFavoriteJob } from '@/api/job';

import { listFavourite, removeFavourite } from '@/api/user.js';

describe('TEST Favorite (Favorite Job List) Screen ', () => {
  // type = 1: ListFavourite Hr
  const listFavouriteHr = [
    {
      favorites_id: 11,
      id: 7,
      country_id: null,
      hr_organization_id: 3,
      user_id: '0',
      full_name: 'Cuong',
      full_name_ja: 'クォン',
      gender: 1,
      date_of_birth: '1970-03-04',
      work_form: 3,
      preferred_working_hours: 20,
      japanese_level: 3,
      final_education_date: '2022-02-01',
      final_education_classification: 2,
      final_education_degree: 3,
      major_classification_id: 21,
      middle_classification_id: 91,
      personal_pr_special_notes: null,
      remarks: null,
      telephone_number: null,
      mobile_phone_number: null,
      mail_address: 'cuongvn@gmail.com',
      address_city: null,
      address_district: null,
      address_number: null,
      address_other: null,
      hometown_city: null,
      home_town_district: null,
      home_town_number: null,
      home_town_other: null,
      file_cv_id: 65,
      file_job_id: 66,
      file_others: null,
      status: 1,
      created_by: 1,
      updated_by: null,
      deleted_at: null,
      created_at: 1690953388,
      updated_at: 1690954988,
      language_requirement: {
        id: 3,
        name: 'N3',
      },
    },
    {
      favorites_id: 1,
      id: 2,
      country_id: null,
      hr_organization_id: 2,
      user_id: '0',
      full_name: 'Kiet',
      full_name_ja: 'キエット',
      gender: 1,
      date_of_birth: '2001-02-03',
      work_form: 1,
      preferred_working_hours: null,
      japanese_level: 6,
      final_education_date: '1961-03-01',
      final_education_classification: 1,
      final_education_degree: 3,
      major_classification_id: 18,
      middle_classification_id: 81,
      personal_pr_special_notes: 'dqwd',
      remarks: 'dqw',
      telephone_number: null,
      mobile_phone_number: null,
      mail_address: 'nguyenbaolinh@gmail.com',
      address_city: null,
      address_district: null,
      address_number: null,
      address_other: null,
      hometown_city: null,
      home_town_district: null,
      home_town_number: null,
      home_town_other: null,
      file_cv_id: 59,
      file_job_id: 60,
      file_others: null,
      status: 1,
      created_by: 1,
      updated_by: null,
      deleted_at: null,
      created_at: 1689733415,
      updated_at: 1691553573,
      language_requirement: {
        id: 6,
        name: '資格なし',
      },
    },
  ];
  // type = 2: ListFavourite Company
  const listFavouriteJob = [
    {
      favorites_id: 32,
      id: 3,
      user_id: '1',
      company_id: 1,
      title: 'ITオフショア開発マネージャー',
      major_classification_id: 13,
      middle_classification_id: '66',
      application_period_from: '2023-07-31',
      application_period_to: '2023-09-09',
      job_description: 'オフショア開発　ベトナム勤務',
      application_condition: 'あ',
      application_requirement: 'あ',
      other_skill: 'あ',
      preferred_skill: 'あ',
      form_of_employment: 4,
      working_time_from: '10:30',
      working_time_to: '19:00',
      vacation: 'あ',
      expected_income: '450',
      assumed_annual_income: null,
      city_id: 6,
      working_palace_detail: 'あ',
      treatment_welfare: 'あ',
      company_pr_appeal: 'あ',
      bonus_pay_rise: 2,
      over_time: 2,
      transfer: 2,
      passive_smoking: 2,
      interview_follow: 2,
      status: 1,
      created_by: 1,
      updated_by: '1',
      deleted_at: null,
      created_at: 1690455779,
      updated_at: 1691946004,
      company: {
        id: 1,
        user_id: 15,
        company_name: 'Daisei VEHO Works',
        company_name_jp: 'ダイセイヴェーホーワークス',
        major_classification: 5,
        middle_classification: 31,
        post_code: '1000000',
        prefectures: 'Hanoi',
        municipality: 'Ba Dinh',
        number: '266 Lieu Giai',
        other: 'Ladeco building',
        telephone_number: '+84 123',
        mail_address: 'daisei-veho-works-vn@vn.com',
        url: 'http://kaigaimatching.vw-dev.com/',
        job_title: 'Co-CEO',
        full_name: '茂木　秀彦',
        full_name_furigana: 'モテギ　ヒデヒコ',
        representative_contact: null,
        assignee_contact: '+81 09012345678',
        establishment_year: '2021年',
        startup_year: '11',
        capital: '11',
        proceeds: '11',
        number_of_staffs: '11',
        number_of_operations: '30名',
        number_of_shops: '11',
        number_of_factory: '11',
        fiscal: '12月',
        status: 2,
        deleted_at: null,
        created_at: '2023-07-24T09:49:31.000000Z',
        updated_at: '2023-08-18T08:06:39.000000Z',
      },
    },
    {
      favorites_id: 31,
      id: 2,
      user_id: '1',
      company_id: 1,
      title: 'Sales',
      major_classification_id: 2,
      middle_classification_id: '12',
      application_period_from: '2023-08-12',
      application_period_to: '2023-08-31',
      job_description: 'dqwd',
      application_condition: 'qwd',
      application_requirement: 'qdqw',
      other_skill: 'dqwd',
      preferred_skill: 'wqd',
      form_of_employment: 1,
      working_time_from: '00:30',
      working_time_to: '01:00',
      vacation: 'dqwd',
      expected_income: '12312',
      assumed_annual_income: null,
      city_id: 3,
      working_palace_detail: 'qdasd',
      treatment_welfare: 'asdasd',
      company_pr_appeal: 'asdasd',
      bonus_pay_rise: 2,
      over_time: 2,
      transfer: 1,
      passive_smoking: 1,
      interview_follow: 3,
      status: 1,
      created_by: 1,
      updated_by: '1',
      deleted_at: null,
      created_at: 1689932432,
      updated_at: 1691946004,
      company: {
        id: 1,
        user_id: 15,
        company_name: 'Daisei VEHO Works',
        company_name_jp: 'ダイセイヴェーホーワークス',
        major_classification: 5,
        middle_classification: 31,
        post_code: '1000000',
        prefectures: 'Hanoi',
        municipality: 'Ba Dinh',
        number: '266 Lieu Giai',
        other: 'Ladeco building',
        telephone_number: '+84 123',
        mail_address: 'daisei-veho-works-vn@vn.com',
        url: 'http://kaigaimatching.vw-dev.com/',
        job_title: 'Co-CEO',
        full_name: '茂木　秀彦',
        full_name_furigana: 'モテギ　ヒデヒコ',
        representative_contact: null,
        assignee_contact: '+81 09012345678',
        establishment_year: '2021年',
        startup_year: '11',
        capital: '11',
        proceeds: '11',
        number_of_staffs: '11',
        number_of_operations: '30名',
        number_of_shops: '11',
        number_of_factory: '11',
        fiscal: '12月',
        status: 2,
        deleted_at: null,
        created_at: '2023-07-24T09:49:31.000000Z',
        updated_at: '2023-08-18T08:06:39.000000Z',
      },
    },
  ];

  const localVue = createLocalVue();
  //
  test('Case 1: Test component favourite Template', async() => {
    const wrapper = mount(favoriteOop, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });
    expect(typeof favoriteOop.data).toBe('function');
    const favoritetOopTemplate = wrapper.findComponent({ name: 'Favorite' });
    expect(favoritetOopTemplate.exists()).toBe(true);

    //
    wrapper.destroy();
  });

  test('Case 2: Check render component favourite corectly', async() => {
    const wrapper = mount(favoriteOop, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
    });

    const favoritePage = wrapper.findComponent('.favorite-wrapper');
    expect(favoritePage.exists()).toBe(true);
    wrapper.destroy();
  });

  test('Case 3: Check call API favourite HR vs Company with permission ADMIN', async() => {
    const getListFavouriteHr = jest.fn();
    const getListFavouriteJob = jest.fn();
    const wrapper = mount(favoriteOop, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
      created() {
        getListFavouriteHr();
        getListFavouriteJob();
      },
    });

    const params = {
      mail_address: '5okuridashi_hanoi@gmail.vn', // 1 admin call all
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
        expect(store.getters.token).toBe(TOKEN);
        //
      }
    });
    //  Check permission
    const permission_id = store.getters.permissionCheck;

    if (permission_id === 1) {
      console.log('Case 4: Call ALL API vs Admin');
      expect(getListFavouriteHr).toHaveBeenCalled();
      expect(getListFavouriteJob).toHaveBeenCalled();
    } else if (permission_id === 4) {
      // console.log('Case 4: Call API getListFavouriteHr');
      expect(getListFavouriteHr).toHaveBeenCalled();
    } else if (permission_id === 5) {
      // console.log('Case 4: Call API getListFavouriteJob');
      expect(getListFavouriteJob).toHaveBeenCalled();
    }
    wrapper.destroy();
  });

  test('Case 4: Check call API list favourite Company with permission HR(5)', async() => {
    const getListFavouriteHr = jest.fn();
    const getListFavouriteJob = jest.fn();
    const wrapper = mount(favoriteOop, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
      created() {
        getListFavouriteHr();
        getListFavouriteJob();
      },
    });

    const params = {
      mail_address: '5okuridashi_hanoi@gmail.vn', // 5 Hr
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
    //  Check permission
    const permission_id = store.getters.permissionCheck;

    if (permission_id === 1) {
      // console.log('Case 4: Call ALL API vs Admin');
      expect(getListFavouriteHr).toHaveBeenCalled();
      expect(getListFavouriteJob).toHaveBeenCalled();
    } else if (permission_id === 4) {
      // console.log('Case 4: Call API getListFavouriteHr');
      expect(getListFavouriteHr).toHaveBeenCalled(); ``;
    } else if (permission_id === 5) {
      console.log('Case 4: Call API getListFavouriteJob');
      expect(getListFavouriteJob).toHaveBeenCalled();
    }
    wrapper.destroy();
  });

  test('Case 5: Check call API Remove favourite Job for HR with permission HR/Admin', async() => {
    // const function = jest.fn();
    const wrapperfavoriteJobItem = mount(JobFavoriteItemOop, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
      created() {
        // function();
      },
    });

    const wrapperFavoriteIndex = mount(favoriteOop, {
      localVue,
      router,
      store,
      stubs: {
        BIcon: true,
      },
      created() {
        // function();
      },
    });

    // Get list Favourite Job for HR -> 5?
    const paramsMail = {
      mail_address: '5okuridashi_hanoi@gmail.vn', // 5 Hr
      password: '123456789CCk',
    };

    await login(paramsMail).then((response) => {
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

    const URL = '/work?';
    const responseGetListJob = await getListJob(URL);
    const codeGetListJob = responseGetListJob.code;
    expect(codeGetListJob).toBe(200);
    const resultList = responseGetListJob.data.result;
    const id0 = Number(resultList[0].id);
    console.log('Case 5 id0 :', id0);

    const PARAMS = {
      type: 2,
      relation_id: id0,
    };
    // Add
    if (resultList.length > 0) {
      const resAddFavoriteJob = await addFavoriteJob(PARAMS);
      const codeAddFavoriteJob = resAddFavoriteJob.code;
      expect(codeAddFavoriteJob).toBe(200);
      console.log('Case 5 addFavoriteJob', resAddFavoriteJob);
    }

    // Get list
    // 1:hrs -> Com, 2:job -> HR (required when user is admin)
    const paramslistFavourite = {
      type: 2,
    };

    const arrConvert = [];
    const response = await listFavourite(paramslistFavourite);
    const code = response.data.code;
    expect(code).toBe(200);
    const result = response.data.data.result;

    result.map((item) => {
      arrConvert.push({
        id: item.id,
        title: item.title,
        application_period_to: item.application_period_to,
        job_description: item.job_description,
        expected_income: item.expected_income,
      });
    });

    console.log('Case 5 arrConvert.length: ', arrConvert.length);
    // Have data
    await wrapperFavoriteIndex.setData({ listFavouriteJob: arrConvert }); // !!!
    expect(wrapperFavoriteIndex.vm.listFavouriteJob).toBe(arrConvert);

    // expect(wrapperFavoriteIndex).toMatchSnapshot(); //

    // Parent
    const favoriteIndexComponent = wrapperFavoriteIndex.findComponent('.favorite-wrapper');
    expect(favoriteIndexComponent.exists()).toBe(true);
    // Child
    const favoriteJobItem = favoriteIndexComponent.findAll('.favorite-job-item');
    console.log('Case 5 favoriteJobItem.length :', favoriteJobItem.length);
    expect(favoriteJobItem.length).toBe(arrConvert.length); //

    for (let i = 0; i < arrConvert.length; i++) {
      const btnRemoveStatusFavouriteJob = wrapperFavoriteIndex.findAll('#btn-remove-status-favourite-job');
      expect(btnRemoveStatusFavouriteJob.exists()).toBe(true);

      const removeStatusFavouriteJob = jest.spyOn(wrapperfavoriteJobItem.vm, 'removeStatusFavouriteJob');

      if (arrConvert[i].id === id0) {
        removeStatusFavouriteJob(arrConvert[i].id);
        expect(removeStatusFavouriteJob).toHaveBeenCalledWith(arrConvert[i].id);
        // const responseRemoveFavourite = await removeFavourite(Number(arrConvert[i].id));
        // const codeRemoveFavourite = responseRemoveFavourite.data.code;
        // expect(codeRemoveFavourite).toBe(200);
        // console.log('removeFavourite 200');
      }
    }

    const jobNameItem = wrapperfavoriteJobItem.findComponent('.job-name');
    expect(jobNameItem.exists()).toBe(true);

    const jobDateItem = wrapperfavoriteJobItem.findComponent('.job-date');
    expect(jobDateItem.exists()).toBe(true);

    const jobInfoCityIdItem = wrapperfavoriteJobItem.findComponent('.job-info-left');
    expect(jobInfoCityIdItem.exists()).toBe(true);

    const jobInfoExpectedIncomeItem = wrapperfavoriteJobItem.findComponent('.job-info-right');
    expect(jobInfoExpectedIncomeItem.exists()).toBe(true);

    // Del like
    const btnRemoveStatusFavouriteJob = wrapperfavoriteJobItem.findComponent('#btn-remove-status-favourite-job');
    expect(btnRemoveStatusFavouriteJob.exists()).toBe(true);

    const btnHandleClearAllSetting = wrapperfavoriteJobItem.findComponent('#btn-handle-clear-all-setting');
    expect(btnHandleClearAllSetting.exists()).toBe(true);
  });

  //
});
