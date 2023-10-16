/* eslint-disable no-unused-vars */
// eslint-disable-next-line no-unused-vars
import { createLocalVue, shallowMount, mount } from '@vue/test-utils';
import store from '@/store';
import router from '@/router';
import listJobHrOop from '@/pages/JobSearch/index.vue';
import { listJob, addFavoriteJob, removeFavoriteJob } from '@/api/job';
import { handleRole } from '@/utils/handleRole';
import { login } from '@/api/auth';

describe('TEST Component Job List (HR) ', () => {
  //
  test('Case 1: Check render Job List (HR)  Template', () => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(listJobHrOop, {
      localVue,
      store,
      router,
    });

    expect(typeof listJobHrOop.data).toBe('function');

    const listJobHrTemplate = wrapper.findComponent({ name: 'JobListHR' });
    expect(listJobHrTemplate.exists()).toBe(true);

    wrapper.destroy();
  });

  test('Case 2: Check render component Job List (HR)', () => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(listJobHrOop, {
      localVue,
      store,
      router,
    });

    const jobListHrPage = wrapper.find('.job-list-hr');
    expect(jobListHrPage.exists()).toBe(true);

    const JobFormSearch = wrapper.find('#job-form-search');
    expect(JobFormSearch.exists()).toBe(true);

    const jobListHrTitle = wrapper.find('#job-list-hr-title');
    expect(jobListHrTitle.exists()).toBe(true);
    expect(jobListHrTitle.text()).toEqual('求人情報検索一覧');

    wrapper.destroy();
  });

  test('Case 3: Check API Job List (HR) respone code 200', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(listJobHrOop, {
      localVue,
      store,
      router,
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
        expect(store.getters.token).toBe(TOKEN);
      }
    });

    await listJob().then((response) => {
      expect(response['data']['code']).toBe(200);
    });

    wrapper.destroy();
  });

  test('Case 4: Check API Job List (HR) add/remove FavoriteJob respone code 200', async() => {
    const localVue = createLocalVue();
    // const handleRemoveFavoriteJob =	jest.fn();
    // const handleAddFavoriteJob =	jest.fn();

    const wrapper = shallowMount(listJobHrOop, {
      localVue,
      store,
      router,
      methods: {
        // handleRemoveFavoriteJob,
        // handleAddFavoriteJob,
      },
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

    // Crete data befor -> have render the class want to find
    const list_job_hr = [];
    await listJob().then((response) => {
      expect(response['data']['code']).toBe(200);
      const result = response['data']['data']['result'];
      result.map(item => {
        if (item.status === 1) {
          console.log('list_job_hr response item status: ', item.status);
          list_job_hr.push({
            id: item?.id || null,
            job_name: item?.job_name || '',
            created_at: item?.created_at || '',
            is_within_3_days: item?.is_within_3_days || '',
            occupation: item?.occupation || '',
            job_description: item?.job_description || '',
            expected_income: item?.expected_income || '',
            work_palace: item?.work_palace || '',
            is_favorite: item?.is_favorite,
            recruitment_end_date: item?.recruitment_end_date || '',
          });
        }
      });
    });

    await wrapper.setData({ list_job_hr: [] }); // !
    await wrapper.setData({ list_job_hr: list_job_hr }); // !

    const jobTtem = wrapper.find('.job-item');
    expect(jobTtem.exists()).toBe(true);

    console.log('Case4: list_job_hr.push', list_job_hr);

    const id = list_job_hr[0].id;
    const is_favorite = list_job_hr[0].is_favorite;

    console.log('Case4 id: ', id);
    console.log('Case4 is_favorite: ', is_favorite);

    const paramsFavorite = {
      relation_id: id, // id Fix tạm
      type: 2, // Fix for list job(hr)
    };

    if (is_favorite === 1) {
      await removeFavoriteJob(paramsFavorite)
        .then((response) => {
          console.log('removeFavoriteJob response: ');
          expect(response['data']['code']).toBe(200);
        });
    }

    if (is_favorite === 0) {
      await addFavoriteJob(paramsFavorite)
        .then((response) => {
          console.log('addFavoriteJob response: ');
          expect(response['data']['code']).toBe(200);
        });
    }

    // const spyRemoveFavoriteJob = jest.spyOn(wrapper.vm, 'removeFavoriteJob');
    // const spyAddFavoriteJob = jest.spyO (wrapper.vm, 'addFavoriteJob');
    //
    //     const btnbtnRemoveFavorite = wrapper.find('.btn-remove-favorite-job');
    //     expect(btnbtnRemoveFavorite.exists()).toBe(true);
    //     btnbtnRemoveFavorite.trigger('click');

    // expect(spyRemoveFavoriteJob).toHaveBeenCalledWith(paramsFavorite);

    // await removeFavoriteJob(paramsFavorite)
    //   .then((response) => {
    //     console.log('removeFavoriteJob response: ', response);
    //     expect(response['data']['code']).toBe(200);
    //   });

    // btnbtnRemoveFavorite.trigger('click');
    // expect(spyRemoveFavoriteJob).toHaveBeenCalledWith(paramsFavorite);
    // expect(spyAddFavoriteJob).toHaveBeenCalledWith(paramsFavorite);

    //     await addFavoriteJob(paramsFavorite).then((response) => {
    //       console.log('listJob response: ', response);
    //       // expect(response['data']['code']).toBe(200);
    //     });

    wrapper.destroy();
  });

  test('Case 5: Test component render DATA', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(listJobHrOop, {
      localVue,
      store,
      router,
    });

    const list_job_hr = [
      {
        id: 1, //
        job_name: '️求人求人名（タイトル）名（タイトル）',
        created_at: 1689927730,
        is_within_3_days: false,
        occupation: '耕種農業',

        job_description: '仕事内容',
        expected_income: '11111',
        work_palace: '北海道',
        is_favorite: 1,
        recruitment_end_date: '2027年09月18日まで掲載',
      },
    ];

    await wrapper.setData({ list_job_hr: list_job_hr }); // !

    expect(wrapper.vm.list_job_hr[0].id).toEqual(list_job_hr[0].id);
    expect(wrapper.vm.list_job_hr[0].job_name).toEqual(list_job_hr[0].job_name);
    expect(wrapper.vm.list_job_hr[0].created_at).toEqual(list_job_hr[0].created_at);
    expect(wrapper.vm.list_job_hr[0].is_within_3_days).toEqual(list_job_hr[0].is_within_3_days);
    expect(wrapper.vm.list_job_hr[0].occupation).toEqual(list_job_hr[0].occupation);

    expect(wrapper.vm.list_job_hr[0].job_description).toEqual(list_job_hr[0].job_description);
    expect(wrapper.vm.list_job_hr[0].expected_income).toEqual(list_job_hr[0].expected_income);
    expect(wrapper.vm.list_job_hr[0].work_palace).toEqual(list_job_hr[0].work_palace);
    expect(wrapper.vm.list_job_hr[0].is_favorite).toEqual(list_job_hr[0].is_favorite);
    expect(wrapper.vm.list_job_hr[0].recruitment_end_date).toEqual(list_job_hr[0].recruitment_end_date);

    wrapper.destroy();
  });

  test('Case 6: Test component render item Job (hr)', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(listJobHrOop, {
      localVue,
      store,
      router,
    });

    // Crete data befor -> have render the class want to find
    const list_job_hr = [
      {
        id: 1,
        job_name: '️求人求人名（タイトル）名（タイトル）',
        created_at: 1689927730,
        is_within_3_days: false,
        occupation: '耕種農業',
        job_description: '仕事内容',
        expected_income: '11111',
        work_palace: '北海道',
        is_favorite: 1,
        recruitment_end_date: '2027年09月18日まで掲載',
      },
    ];

    await wrapper.setData({ list_job_hr: list_job_hr });

    const jobTtem = wrapper.find('.job-item');
    expect(jobTtem.exists()).toBe(true);

    const occupationItem = wrapper.find('#occupation');
    expect(occupationItem.exists()).toBe(true);

    const jobDescriptionItem = wrapper.find('#job_description');
    expect(jobDescriptionItem.exists()).toBe(true);

    const expectedIncomeItem = wrapper.find('#expected_income');
    expect(expectedIncomeItem.exists()).toBe(true);

    const workPalaceItem = wrapper.find('#work_palace');
    expect(workPalaceItem.exists()).toBe(true);

    // const btnRemoveFavoriteJob = wrapper.find('.btn-remove-favorite-job');
    // expect(btnRemoveFavoriteJob.exists()).toBe(true);

    // const btnAddFavoriteJob = wrapper.find('.btn-add-favorite-job');
    // expect(btnAddFavoriteJob.exists()).toBe(true);

    wrapper.destroy();
  });

  test('Case 7: Test number case item of list Job (hr)', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(listJobHrOop, {
      localVue,
      store,
      router,
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

    const list_job_hr = [];
    await listJob().then((response) => {
      expect(response['data']['code']).toBe(200);
      const result = response['data']['data']['result'];
      result.map(item => {
        list_job_hr.push({
          id: item?.id || null,
          job_name: item?.job_name || '',
          created_at: item?.created_at || '',
          is_within_3_days: item?.is_within_3_days || '',
          occupation: item?.occupation || '',
          job_description: item?.job_description || '',
          expected_income: item?.expected_income || '',
          work_palace: item?.work_palace || '',
          is_favorite: item?.is_favorite || '',
          recruitment_end_date: item?.recruitment_end_date || '',
        });
      });
    });

    // await wrapper.setData({ list_job_hr: list_job_hr }); // !

    expect(wrapper.vm.list_job_hr.length).toEqual(list_job_hr.length);

    const numberCaseNumber = wrapper.find('.number-case');
    expect(numberCaseNumber.exists()).toBe(true);

    wrapper.destroy();
  });

  test('Case 8: Check BTN btnSeeDetail Job', async() => {
    const localVue = createLocalVue();
    // const goToDetailJob =	jest.fn();
    const wrapper = shallowMount(listJobHrOop, {
      localVue,
      store,
      router,
      methods: {
        // goToDetailJob,
      },
    });

    const list_job_hr = [
      {
        id: 1,
        job_name: '️求人求人名（タイトル）名（タイトル）',
        created_at: 1689927730,
        is_within_3_days: false,
        occupation: '耕種農業',
        job_description: '仕事内容',
        expected_income: '11111',
        work_palace: '北海道',
        is_favorite: '',
        recruitment_end_date: '2027年09月18日まで掲載',
      },
    ];

    await wrapper.setData({ list_job_hr: list_job_hr });

    const jobTtem = wrapper.find('.job-item');
    expect(jobTtem.exists()).toBe(true);

    const btnGoToDetailJob = wrapper.find('#go-to-detail-job');
    expect(btnGoToDetailJob.exists()).toBe(true);

    const spyGoToDetailJob = jest.spyOn(wrapper.vm, 'goToDetailJob');

    btnGoToDetailJob.trigger('click');
    expect(spyGoToDetailJob).toHaveBeenCalledWith(1);

    wrapper.destroy();
  });

  //
});
