
import { shallowMount, createLocalVue, mount } from '@vue/test-utils';
import UserManagementList from '@/pages/UserManagement/List.vue';
import store from '@/store';
import router from '@/router';

describe('Test Component UserManagementList', () => {
  const LIST_USER = {
    'code': 200,
    'data': {
      'current_page': 1,
      'data': [
        {
          'id': 21,
          'username': 'your.email+fakedata97564',
          'email': 'your.email+fakedata97564@gmail.com',
          'name': 'Andreanne55',
          'email_verified_at': null,
          'status': 1,
          'created_at': '2021-08-27T07:42:09.000000Z',
          'updated_at': '2021-08-27T07:42:09.000000Z',
          'display_name': 'information_systems',
        },
        {
          'id': 20,
          'username': 'your.email+fakedata45214',
          'email': 'your.email+fakedata45214@gmail.com',
          'name': 'Ceasar.Reilly62',
          'email_verified_at': null,
          'status': 1,
          'created_at': '2021-08-27T07:41:57.000000Z',
          'updated_at': '2021-08-27T07:41:57.000000Z',
          'display_name': 'information_systems',
        },
        {
          'id': 19,
          'username': 'your.email+fakedata49389',
          'email': 'your.email+fakedata49389@gmail.com',
          'name': 'Newell.Morar',
          'email_verified_at': null,
          'status': 1,
          'created_at': '2021-08-27T07:41:48.000000Z',
          'updated_at': '2021-08-27T07:41:48.000000Z',
          'display_name': 'general_affairs',
        },
        {
          'id': 18,
          'username': 'your.email+fakedata21550',
          'email': 'your.email+fakedata21550@gmail.com',
          'name': 'Jamison_Jast30',
          'email_verified_at': null,
          'status': 1,
          'created_at': '2021-08-27T07:41:41.000000Z',
          'updated_at': '2021-08-27T07:41:41.000000Z',
          'display_name': 'center_manager',
        },
        {
          'id': 17,
          'username': 'your.email+fakedata52737',
          'email': 'your.email+fakedata52737@gmail.com',
          'name': 'Derrick51',
          'email_verified_at': null,
          'status': 1,
          'created_at': '2021-08-27T07:41:33.000000Z',
          'updated_at': '2021-08-27T07:41:33.000000Z',
          'display_name': 'information_systems',
        },
        {
          'id': 16,
          'username': 'your.email+fakedata68310',
          'email': 'your.email+fakedata68310@gmail.com',
          'name': 'Grover.Streich',
          'email_verified_at': null,
          'status': 1,
          'created_at': '2021-08-27T07:40:57.000000Z',
          'updated_at': '2021-08-27T07:40:57.000000Z',
          'display_name': 'information_systems',
        },
        {
          'id': 15,
          'username': 'your.email+fakedata56022',
          'email': 'your.email+fakedata56022@gmail.com',
          'name': 'Shany_Ritchie',
          'email_verified_at': null,
          'status': 1,
          'created_at': '2021-08-27T07:40:48.000000Z',
          'updated_at': '2021-08-27T07:40:48.000000Z',
          'display_name': 'vehicle_analysis',
        },
        {
          'id': 14,
          'username': 'your.email+fakedata55708',
          'email': 'your.email+fakedata55708@gmail.com',
          'name': 'Paris_Williamson47',
          'email_verified_at': null,
          'status': 1,
          'created_at': '2021-08-27T07:40:33.000000Z',
          'updated_at': '2021-08-27T07:40:33.000000Z',
          'display_name': 'viewer',
        },
        {
          'id': 13,
          'username': 'your.email+fakedata85570',
          'email': 'your.email+fakedata85570@gmail.com',
          'name': 'Roxanne.Hahn56',
          'email_verified_at': null,
          'status': 1,
          'created_at': '2021-08-27T07:40:25.000000Z',
          'updated_at': '2021-08-27T07:40:25.000000Z',
          'display_name': 'center_manager',
        },
        {
          'id': 12,
          'username': 'your.email+fakedata16902',
          'email': 'your.email+fakedata16902@gmail.com',
          'name': 'Leonel.Walker24',
          'email_verified_at': null,
          'status': 1,
          'created_at': '2021-08-27T07:40:16.000000Z',
          'updated_at': '2021-08-27T07:40:16.000000Z',
          'display_name': 'vehicle_analysis',
        },
        {
          'id': 11,
          'username': 'your.email+fakedata74506',
          'email': 'your.email+fakedata74506@gmail.com',
          'name': 'Henderson.Harber',
          'email_verified_at': null,
          'status': 1,
          'created_at': '2021-08-27T07:40:06.000000Z',
          'updated_at': '2021-08-27T07:40:06.000000Z',
          'display_name': 'center_manager',
        },
        {
          'id': 10,
          'username': 'your.email+fakedata56615',
          'email': 'your.email+fakedata56615@gmail.com',
          'name': 'Troy_Stanton',
          'email_verified_at': null,
          'status': 1,
          'created_at': '2021-08-27T07:39:56.000000Z',
          'updated_at': '2021-08-27T07:39:56.000000Z',
          'display_name': 'information_systems',
        },
        {
          'id': 9,
          'username': 'your.email+fakedata93062',
          'email': 'your.email+fakedata93062@gmail.com',
          'name': 'Larissa.Wilderman8',
          'email_verified_at': null,
          'status': 1,
          'created_at': '2021-08-27T04:36:55.000000Z',
          'updated_at': '2021-08-27T04:36:55.000000Z',
          'display_name': 'viewer',
        },
        {
          'id': 8,
          'username': 'your.email+fakedata62947',
          'email': 'your.email+fakedata62947@gmail.com',
          'name': 'Bernhard_Herzog63',
          'email_verified_at': null,
          'status': 1,
          'created_at': '2021-08-27T04:36:42.000000Z',
          'updated_at': '2021-08-27T04:36:42.000000Z',
          'display_name': 'general_affairs',
        },
        {
          'id': 7,
          'username': 'your.email+fakedata59390',
          'email': 'your.email+fakedata59390@gmail.com',
          'name': 'Theron_Howell',
          'email_verified_at': null,
          'status': 1,
          'created_at': '2021-08-27T04:35:51.000000Z',
          'updated_at': '2021-08-27T04:35:51.000000Z',
          'display_name': 'center_manager',
        },
        {
          'id': 6,
          'username': 'your.email+fakedata78447',
          'email': 'your.email+fakedata78447@gmail.com',
          'name': 'Nestor35',
          'email_verified_at': null,
          'status': 1,
          'created_at': '2021-08-27T04:35:38.000000Z',
          'updated_at': '2021-08-27T04:35:38.000000Z',
          'display_name': 'center_manager',
        },
        {
          'id': 5,
          'username': 'your.email+fakedata62510',
          'email': 'your.email+fakedata62510@gmail.com',
          'name': 'Cale.Price83',
          'email_verified_at': null,
          'status': 1,
          'created_at': '2021-08-27T04:35:21.000000Z',
          'updated_at': '2021-08-27T04:35:21.000000Z',
          'display_name': 'general_affairs',
        },
        {
          'id': 4,
          'username': 'your.email+fakedata41505',
          'email': 'your.email+fakedata41505@gmail.com',
          'name': 'Loyce.Lemke21',
          'email_verified_at': null,
          'status': 1,
          'created_at': '2021-08-27T04:35:09.000000Z',
          'updated_at': '2021-08-27T04:35:09.000000Z',
          'display_name': 'viewer',
        },
        {
          'id': 3,
          'username': 'your.email+fakedata61753',
          'email': 'your.email+fakedata61753@gmail.com',
          'name': 'Lamar_Jerde68',
          'email_verified_at': null,
          'status': 1,
          'created_at': '2021-08-27T04:35:02.000000Z',
          'updated_at': '2021-08-27T04:35:02.000000Z',
          'display_name': 'information_systems',
        },
        {
          'id': 2,
          'username': 'your.email+fakedata88040',
          'email': 'your.email+fakedata88040@gmail.com',
          'name': 'Ben74',
          'email_verified_at': null,
          'status': 1,
          'created_at': '2021-08-27T04:34:51.000000Z',
          'updated_at': '2021-08-27T04:34:51.000000Z',
          'display_name': 'viewer',
        },
      ],
      'first_page_url': 'http://127.0.0.1:8000/api/user?page=1',
      'from': 1,
      'last_page': 2,
      'last_page_url': 'http://127.0.0.1:8000/api/user?page=2',
      'links': [
        {
          'url': null,
          'label': '&laquo; Previous',
          'active': false,
        },
        {
          'url': 'http://127.0.0.1:8000/api/user?page=1',
          'label': '1',
          'active': true,
        },
        {
          'url': 'http://127.0.0.1:8000/api/user?page=2',
          'label': '2',
          'active': false,
        },
        {
          'url': 'http://127.0.0.1:8000/api/user?page=2',
          'label': 'Next &raquo;',
          'active': false,
        },
      ],
      'next_page_url': 'http://127.0.0.1:8000/api/user?page=2',
      'path': 'http://127.0.0.1:8000/api/user',
      'per_page': '20',
      'prev_page_url': null,
      'to': 20,
      'total': 21,
    },
  };

  const fields = [
    { key: 'choose', sortable: false, label: '', class: 'choose' },
    { key: 'name', sortable: true, label: 'USER_MANAGEMENT_NAME', class: 'name' },
    { key: 'email', sortable: true, label: 'USER_MANAGEMENT_MAIL', class: 'email' },
    { key: 'display_name', sortable: true, label: 'USER_MANAGEMENT_AUTH', class: 'role' },
    { key: 'result', sortable: false, label: '', class: 'result' },
  ];

  it('Case 1: Check component List in User Management has data', () => {
    expect(typeof UserManagementList.data).toBe('function');
  });

  it('Case 2: Test button Sign up and event function when click button Sign up', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(UserManagementList, {
      localVue,
      store,
      router,
    });

    const createNewUser = jest.spyOn(wrapper.vm, 'createNewUser');
    const BTN_ADD = wrapper.find('.btn-add');
    expect(BTN_ADD.exists()).toBe(true);

    await BTN_ADD.trigger('click');

    expect(createNewUser).toHaveBeenCalled();

    wrapper.destroy();
  });

  it('Case 3: Test button Delete Mutiple and event function when click button Delete Mutiple', async() => {
    const localVue = createLocalVue();
    const wrapper = shallowMount(UserManagementList, {
      localVue,
      store,
      router,
      stubs: {
        BIcon: true,
      },
    });

    const showDelete = jest.spyOn(wrapper.vm, 'showDelete');

    const BTN_ALL_DELETE = wrapper.find('.btn-delete');

    expect(BTN_ALL_DELETE.exists()).toBeTruthy();

    await BTN_ALL_DELETE.trigger('click');

    expect(showDelete).toHaveBeenCalled();

    wrapper.destroy();
  });

  it('Case 4: Test function when search in input', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(UserManagementList, {
      localVue,
      store,
      router,
    });

    const INPUT_SEARCH = wrapper.find('#search-user');
    expect(INPUT_SEARCH.exists()).toBeTruthy();

    await INPUT_SEARCH.trigger('keyup', { keyCode: 13 });

    expect(wrapper.vm.keyup).toBe();

    wrapper.destroy();
  });

  it('Case 5: Test component render Table List User', async() => {
    const localVue = createLocalVue();
    const wrapper = mount(UserManagementList, {
      localVue,
      store,
      router,
      data() {
        return {
          queryData: {
            page: 1,
            per_page: 20,
            total_records: LIST_USER.total,
            search: '',
            order_type: '',
            order_column: '',
          },
        };
      },
    });

    await store.dispatch('userManagement/saveListUser', LIST_USER.data.data);

    const TABLE = wrapper.find('#user-management-list');

    expect(TABLE.exists()).toBe(true);

    expect(TABLE.classes('text-center')).toBe(true);
    expect(TABLE.classes('bg-dx-grey-blur')).toBe(true);
    expect(TABLE.classes('mb-0')).toBe(true);

    for (let field = 0; field < fields.length; field++) {
      expect(TABLE.props('fields')[field].key).toEqual(fields[field].key);
      expect(TABLE.props('fields')[field].sortable).toEqual(fields[field].sortable);
      expect(TABLE.props('fields')[field].label).toEqual(fields[field].label);
      expect(TABLE.props('fields')[field].class).toEqual(fields[field].class);
    }

    for (let item = 0; item < LIST_USER.data.data.length; item++) {
      expect(TABLE.props('items')[item].id).toEqual(LIST_USER.data.data[item].id);
      expect(TABLE.props('items')[item].username).toEqual(LIST_USER.data.data[item].username);
      expect(TABLE.props('items')[item].email).toEqual(LIST_USER.data.data[item].email);
      expect(TABLE.props('items')[item].name).toEqual(LIST_USER.data.data[item].name);
      expect(TABLE.props('items')[item].email_verified_at).toEqual(LIST_USER.data.data[item].email_verified_at);
      expect(TABLE.props('items')[item].status).toEqual(LIST_USER.data.data[item].status);
      expect(TABLE.props('items')[item].created_at).toEqual(LIST_USER.data.data[item].created_at);
      expect(TABLE.props('items')[item].updated_at).toEqual(LIST_USER.data.data[item].updated_at);
      expect(TABLE.props('items')[item].display_name).toEqual(LIST_USER.data.data[item].display_name);
    }

    const TABLE_HEADER = TABLE.findAll('th');
    expect(TABLE_HEADER.length).toEqual(5);

    const TABLE_BODY = TABLE.find('tbody');
    const TABLE_BODY_TR = TABLE_BODY.findAll('tr');
    expect(TABLE_BODY_TR.length).toEqual(20);

    for (let tr = 0; tr < TABLE_BODY_TR.length; tr++) {
      const LIST_TD = TABLE_BODY_TR.at(tr).findAll('td');

      expect(LIST_TD.at(0).find('input[type="checkbox"]').exists()).toBe(true);
      expect(LIST_TD.at(0).find('input[type="checkbox"]').element.checked).toBe(false);
      expect(LIST_TD.at(1).text()).toEqual(LIST_USER.data.data[tr].name);
      expect(LIST_TD.at(2).text()).toEqual(LIST_USER.data.data[tr].email);
      expect(LIST_TD.at(3).text()).toEqual(LIST_USER.data.data[tr].display_name);

      const LIST_BUTTON = LIST_TD.at(4).findAll('button');

      const goToDetailScreen = jest.spyOn(wrapper.vm, 'goToDetailScreen');
      const goToEditScreen = jest.spyOn(wrapper.vm, 'goToEditScreen');
      const confirmationForm = jest.spyOn(wrapper.vm, 'confirmationForm');

      expect(LIST_BUTTON.at(0).exists()).toBe(true);
      expect(LIST_BUTTON.at(0).text()).toEqual('DISPLAY');
      await LIST_BUTTON.at(0).trigger('click');
      expect(goToDetailScreen).toHaveBeenCalledWith(LIST_USER.data.data[tr].id);

      expect(LIST_BUTTON.at(1).exists()).toBe(true);
      expect(LIST_BUTTON.at(1).text()).toEqual('EDIT');
      await LIST_BUTTON.at(1).trigger('click');
      expect(goToEditScreen).toHaveBeenCalledWith(LIST_USER.data.data[tr].id);

      expect(LIST_BUTTON.at(2).exists()).toBe(true);
      expect(LIST_BUTTON.at(2).text()).toEqual('DELETE');
      await LIST_BUTTON.at(2).trigger('click');
      expect(confirmationForm).toHaveBeenCalledWith(LIST_USER.data.data[tr].id, LIST_USER.data.data[tr].name);
    }

    const LIST_TEST_CHECKED = [
      0,
      1,
      2,
      3,
    ];

    for (let input = 0; input < LIST_TEST_CHECKED.length; input++) {
      await TABLE_BODY_TR.at(input).findAll('td').at(0).find('input[type="checkbox"]').setChecked();
    }

    expect(wrapper.vm.listId.length).toEqual(LIST_TEST_CHECKED.length);

    await store.dispatch('userManagement/saveListUser', []);
    expect(wrapper.find('tbody tr').text()).toEqual('TABLE_EMPTY');

    wrapper.destroy();
  });
});

