import store from '@/store';
import { parseToken } from '@/utils/handleToken';
import { handleRole } from '@/utils/handleRole';

describe('TEST STORE', () => {
  test('Case 1: Test store app', async() => {
    const EN = 'en';
    const JA = 'ja';

    await store.dispatch('app/setLanguage', EN);
    expect(store.getters.language).toEqual(EN);

    await store.dispatch('app/setLanguage', JA);
    expect(store.getters.language).toEqual(JA);
  });

  test('Case 2: Test store user', async() => {
    const RESPONSE = {
      'code': 200,
      'data': {
        'access_token': 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTYzMjA0MTEwMywiZXhwIjoxNjMyMTI3NTAzLCJuYmYiOjE2MzIwNDExMDMsImp0aSI6Ind1WVh4UERDUXkydFh5d0wiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjciLCJndWFyZCI6InVzZXIifQ.I9St9SaD38uUTFQXc9OTNEncRmGL_szYxXrUqiyC18k',
        'profile': {
          'id': 1,
          'username': null,
          'email': 'test@gmail.com',
          'name': 'Test',
          'email_verified_at': null,
          'status': null,
          'created_at': 1631680392,
          'updated_at': 1631680392,
          'role': {
            'general_affairs': [
              {
                'id': 1,
                'name': 'header_vehicle',
                'display_name': 'asset page Vehicle',
                'description': 'asset page Vehicle',
                'created_at': '2021-09-15T04:33:07.000000Z',
                'updated_at': '2021-09-15T04:33:07.000000Z',
                'pivot': {
                  'role_id': 1,
                  'permission_id': 1,
                },
              },
              {
                'id': 2,
                'name': 'header_degi_tacho',
                'display_name': 'asset page Degi-tacho',
                'description': 'asset page Degi-tacho',
                'created_at': '2021-09-15T04:33:07.000000Z',
                'updated_at': '2021-09-15T04:33:07.000000Z',
                'pivot': {
                  'role_id': 1,
                  'permission_id': 2,
                },
              },
              {
                'id': 3,
                'name': 'header_etc_device',
                'display_name': 'asset page ETC device',
                'description': 'asset page ETC device',
                'created_at': '2021-09-15T04:33:07.000000Z',
                'updated_at': '2021-09-15T04:33:07.000000Z',
                'pivot': {
                  'role_id': 1,
                  'permission_id': 3,
                },
              },
              {
                'id': 4,
                'name': 'header_master_management',
                'display_name': 'asset page master_management',
                'description': 'asset page master_management',
                'created_at': '2021-09-15T04:33:07.000000Z',
                'updated_at': '2021-09-15T04:33:07.000000Z',
                'pivot': {
                  'role_id': 1,
                  'permission_id': 4,
                },
              },
              {
                'id': 5,
                'name': 'header_user_management',
                'display_name': 'asset page User management',
                'description': 'asset page User management',
                'created_at': '2021-09-15T04:33:07.000000Z',
                'updated_at': '2021-09-15T04:33:07.000000Z',
                'pivot': {
                  'role_id': 1,
                  'permission_id': 5,
                },
              },
            ],
          },
        },
      },
    };

    const TOKEN = RESPONSE.data.access_token;
    const PROFILE = RESPONSE.data.profile;
    const EXP_TOKEN = parseToken(TOKEN);

    const USER = {
      address: PROFILE.address || '',
      avatar: PROFILE.avatar || '',
      email: PROFILE.email || '',
      fax: PROFILE.fax || '',
      gender: PROFILE.gender || '',
      id: PROFILE.id || '',
      name: PROFILE.name || '',
      phone: PROFILE.phone || '',
      status: PROFILE.status || '',
      expToken: EXP_TOKEN.exp || '',
    };

    const { ROLES, PERMISSIONS } = handleRole(PROFILE.role);

    await store.dispatch('user/saveLogin', { USER, ROLES, PERMISSIONS, TOKEN });

    expect(store.getters.name).toEqual(USER.name);
    expect(store.getters.email).toEqual(USER.email);
    expect(store.getters.token).toEqual(TOKEN);
    expect(store.getters.profile).toEqual(USER);
    expect(store.getters.role).toEqual(ROLES);
    expect(store.getters.permission).toEqual(PERMISSIONS);

    await store.dispatch('user/doLogout');
    expect(store.getters.name).toEqual('');
    expect(store.getters.email).toEqual('');
    expect(store.getters.token).toEqual('');
    expect(store.getters.profile).toEqual({
      id: '',
      email: '',
      phone: '',
      name: '',
      fax: '',
      address: '',
      gender: '',
      email_verified_at: '',
      status: '',
      expToken: '',
    });
    expect(store.getters.role).toEqual([]);
    expect(store.getters.permission).toEqual([]);
  });

  test('Case 3: Test store userManagement', async() => {
    const LIST_USER = [
      {
        username: 'Vu',
      },
      {
        username: 'Duc',
      },
      {
        username: 'Viet',
      },
    ];

    await store.dispatch('userManagement/saveListUser', LIST_USER);
    expect(store.getters.listUser).toEqual(LIST_USER);
  });

  test('Case 4: Test store deigtacho', async() => {
    const DEGITACHO = {
      data: 'This data Degitacho',
    };

    await store.dispatch('degitacho/setDegitachoOrigin', DEGITACHO);
    expect(store.getters.degitacho).toEqual(DEGITACHO);
  });

  test('Case 5: Test store ETC', async() => {
    const ETC = {
      data: 'This data ETC',
    };

    await store.dispatch('etc/setETCOrigin', ETC);
    expect(store.getters.etc).toEqual(ETC);
  });
});
