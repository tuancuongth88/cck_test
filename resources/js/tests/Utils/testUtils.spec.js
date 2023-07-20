import store from '@/store';
import { formatMoney } from '@/utils/formatMoney';
import { getLanguage } from '@/utils/getLang';
import getPageTitle from '@/utils/getPageTitle';
import {
  formatDate,
  formatMonth,
  joinYMD,
  formatYMD,
  formatBindingYMD,
  formatBindingYM,
  getObjectYM,
  isCheckDateDiff,
} from '@/utils/handleDate';
import {
  getHistoryNearest,
  getIdVehicle,
  getAfterPain,
  getObjById,
} from '@/utils/handleHistory';
import { getListByKey } from '@/utils/handleListMaster';
import { cleanObj, changeSpace2Null } from '@/utils/handleObj';
import { getRole, getPermission } from '@/utils/handlePermission';
import {
  validEmail,
  validPassword,
  validEmptyOrWhiteSpace,
  validateNumberMoreThanZero,
  validateNumber,
  validateYYYYMMDD,
  validateYYYYMM,
  validateTwoIdDiff,
} from '@/utils/validate';

describe('TEST UTILS', () => {
  test('Case 1: Test function formatMoney()', () => {
    expect(formatMoney(null)).toEqual('');
    expect(formatMoney(undefined)).toEqual('');
    expect(formatMoney(false)).toEqual('');
    expect(formatMoney('000000000')).toEqual('000,000,000');
    expect(formatMoney('123123123')).toEqual('123,123,123');
    expect(formatMoney(1)).toEqual('1');
    expect(formatMoney(12)).toEqual('12');
    expect(formatMoney(123)).toEqual('123');
    expect(formatMoney(1234)).toEqual('1,234');
  });

  test('Case 2: Test function getLanguage()', () => {
    expect(getLanguage()).toEqual(process.env.MIX_LARAVEL_LANG || 'en');
  });

  test('Case 3: Test function getPageTitle()', () => {
    const title = process.env.MIX_APP_TITLE;

    expect(getPageTitle('ROUTER_LOGIN')).toEqual(`Login | ${title}`);
    expect(getPageTitle('ROUTER')).toEqual(`${title}`);
  });

  test('Case 4: Test function formatDate()', () => {
    expect(formatDate(1)).toEqual('01');
    expect(formatDate(10)).toEqual(10);
    expect(formatDate(null)).toEqual('');
    expect(formatDate(false)).toEqual('');
    expect(formatDate(undefined)).toEqual('');
  });

  test('Case 5: Test function formatMonth()', () => {
    expect(formatMonth(1)).toEqual('01');
    expect(formatMonth(10)).toEqual(10);
    expect(formatMonth(null)).toEqual('');
    expect(formatMonth(false)).toEqual('');
    expect(formatMonth(undefined)).toEqual('');
  });

  test('Case 6: Test function joinYMD()', () => {
    expect(joinYMD(2000, 10, 13)).toEqual('2000/10/13');
    expect(joinYMD(null)).toEqual('');
    expect(joinYMD(false)).toEqual('');
    expect(joinYMD(undefined)).toEqual('');
  });

  test('Case 7: Test function formatYMD()', () => {
    expect(formatYMD('2000-10-13')).toEqual('2000/10/13');
    expect(formatYMD(null)).toEqual('');
    expect(formatYMD(false)).toEqual('');
    expect(formatYMD(undefined)).toEqual('');
  });

  test('Case 7: Test function formatBindingYMD()', () => {
    expect(formatBindingYMD('2000/10/13')).toEqual('2000-10-13');
    expect(formatBindingYMD(null)).toEqual('');
    expect(formatBindingYMD(false)).toEqual('');
    expect(formatBindingYMD(undefined)).toEqual('');
  });

  test('Case 8: Test function formatBindingYM()', () => {
    expect(formatBindingYM('2000/10')).toEqual('2000-10');
    expect(formatBindingYM(null)).toEqual('');
    expect(formatBindingYM(false)).toEqual('');
    expect(formatBindingYM(undefined)).toEqual('');
  });

  test('Case 9: Test function getObjectYM()', () => {
    expect(getObjectYM('2000/10')).toEqual({ year: '2000', month: '10' });
    expect(getObjectYM(null)).toEqual('');
    expect(getObjectYM(false)).toEqual('');
    expect(getObjectYM(undefined)).toEqual('');
  });

  test('Case 10: Test function isCheckDateDiff()', () => {
    expect(isCheckDateDiff('2000/10/13', '2000/10/13')).toEqual(false);
    expect(isCheckDateDiff('2000/10/13', '2000/10/14')).toEqual(true);
    expect(isCheckDateDiff(null, '2000/10/13')).toEqual(true);
    expect(isCheckDateDiff(false, '2000/10/13')).toEqual(true);
    expect(isCheckDateDiff(undefined, '2000/10/13')).toEqual(true);
    expect(isCheckDateDiff('2000/10/13', null)).toEqual(false);
    expect(isCheckDateDiff('2000/10/13', false)).toEqual(false);
    expect(isCheckDateDiff('2000/10/13', undefined)).toEqual(false);
    expect(isCheckDateDiff(null, null)).toEqual(false);
    expect(isCheckDateDiff(false, false)).toEqual(false);
    expect(isCheckDateDiff(undefined, undefined)).toEqual(false);
  });

  test('Case 11: Test function getHistoryNearest()', () => {
    const HISTORY = [
      {
        'id': 10,
        'value': '2021/09/23',
        'date_entered': '2021/09/15',
        'application_start_date': '2021/09/15',
        'accounting_month': '2021/09',
      },
      {
        'id': 15,
        'value': '2021/09/30',
        'date_entered': '2021/09/19',
        'application_start_date': '2021/09/30',
        'accounting_month': '2021/12',
      },
    ];

    expect(getHistoryNearest(HISTORY)).toEqual('2021/09/30 : 2021/09/30');
  });

  test('Case 12: Test function getIdVehicle()', () => {
    const TEXT = '1, Vu Duc Viet';

    expect(getIdVehicle(TEXT)).toEqual('1');
  });

  test('Case 13: Test function getAfterPain()', () => {
    const TEXT = '1,Vu Duc Viet';

    expect(getAfterPain(TEXT)).toEqual('Vu Duc Viet');
  });

  test('Case 14: Test function getObjById()', () => {
    const DATA = [
      {
        'id': 70,
        'master_management_id': 10,
        'option_value': 'MT',
        'deleted_at': null,
        'created_at': '2021-09-15T04:33:12.000000Z',
        'updated_at': '2021-09-15T04:33:12.000000Z',
      },
      {
        'id': 71,
        'master_management_id': 10,
        'option_value': 'AT',
        'deleted_at': null,
        'created_at': '2021-09-15T04:33:12.000000Z',
        'updated_at': '2021-09-15T04:33:12.000000Z',
      },
      {
        'id': 72,
        'master_management_id': 10,
        'option_value': 'AMT',
        'deleted_at': null,
        'created_at': '2021-09-15T04:33:12.000000Z',
        'updated_at': '2021-09-15T04:33:12.000000Z',
      },
    ];

    expect(getObjById(70, DATA)).toEqual('MT');
    expect(getObjById(72, DATA)).toEqual('AMT');
  });

  test('Case 15: Test function getListByKey()', () => {
    const DATA = [
      {
        'id': 2,
        'item_name': '所有者',
        'item_key': 'owner',
        'item_type': 'string_array',
        'created_by': null,
        'updated_by': null,
        'created_at': 1631680392,
        'updated_at': 1631680392,
        'master_management_option': [
          {
            'id': 17,
            'master_management_id': 2,
            'option_value': 'JA Mitsui Leasing Co., Ltd.',
            'deleted_at': null,
            'created_at': '2021-09-15T04:33:12.000000Z',
            'updated_at': '2021-09-15T04:33:12.000000Z',
          },
          {
            'id': 18,
            'master_management_id': 2,
            'option_value': 'MOBILOTS Co., Ltd.',
            'deleted_at': null,
            'created_at': '2021-09-15T04:33:12.000000Z',
            'updated_at': '2021-09-15T04:33:12.000000Z',
          },
          {
            'id': 19,
            'master_management_id': 2,
            'option_value': 'Orix Motor Co., Ltd.',
            'deleted_at': null,
            'created_at': '2021-09-15T04:33:12.000000Z',
            'updated_at': '2021-09-15T04:33:12.000000Z',
          },
          {
            'id': 20,
            'master_management_id': 2,
            'option_value': 'Kogin Leasing Co., Ltd.',
            'deleted_at': null,
            'created_at': '2021-09-15T04:33:12.000000Z',
            'updated_at': '2021-09-15T04:33:12.000000Z',
          },
          {
            'id': 21,
            'master_management_id': 2,
            'option_value': 'Showa Leasing Co., Ltd.',
            'deleted_at': null,
            'created_at': '2021-09-15T04:33:12.000000Z',
            'updated_at': '2021-09-15T04:33:12.000000Z',
          },
          {
            'id': 22,
            'master_management_id': 2,
            'option_value': 'Suruga Capital Co., Ltd. Co., Ltd.',
            'deleted_at': null,
            'created_at': '2021-09-15T04:33:12.000000Z',
            'updated_at': '2021-09-15T04:33:12.000000Z',
          },
          {
            'id': 23,
            'master_management_id': 2,
            'option_value': 'Hokugin Leasing Co., Ltd.',
            'deleted_at': null,
            'created_at': '2021-09-15T04:33:12.000000Z',
            'updated_at': '2021-09-15T04:33:12.000000Z',
          },
          {
            'id': 24,
            'master_management_id': 2,
            'option_value': 'Mizuho Leasing Co., Ltd.',
            'deleted_at': null,
            'created_at': '2021-09-15T04:33:12.000000Z',
            'updated_at': '2021-09-15T04:33:12.000000Z',
          },
          {
            'id': 25,
            'master_management_id': 2,
            'option_value': 'Mitsubishi Auto Leasing Co., Ltd.',
            'deleted_at': null,
            'created_at': '2021-09-15T04:33:12.000000Z',
            'updated_at': '2021-09-15T04:33:12.000000Z',
          },
          {
            'id': 26,
            'master_management_id': 2,
            'option_value': 'Mitsubishi Auto Leasing Co., Ltd.',
            'deleted_at': null,
            'created_at': '2021-09-15T04:33:12.000000Z',
            'updated_at': '2021-09-15T04:33:12.000000Z',
          },
          {
            'id': 27,
            'master_management_id': 2,
            'option_value': 'Nomura Auto Leasing Co., Ltd.',
            'deleted_at': null,
            'created_at': '2021-09-15T04:33:12.000000Z',
            'updated_at': '2021-09-15T04:33:12.000000Z',
          },
        ],
      },
      {
        'id': 19,
        'item_name': 'ETC車載器導入方法',
        'item_key': 'etc_device_introduction_method',
        'item_type': 'string_array',
        'created_by': null,
        'updated_by': null,
        'created_at': 1631680393,
        'updated_at': 1631680393,
        'master_management_option': [
          {
            'id': 85,
            'master_management_id': 19,
            'option_value': 'Purchased',
            'deleted_at': null,
            'created_at': '2021-09-15T04:33:13.000000Z',
            'updated_at': '2021-09-15T04:33:13.000000Z',
          },
          {
            'id': 86,
            'master_management_id': 19,
            'option_value': 'leased',
            'deleted_at': null,
            'created_at': '2021-09-15T04:33:13.000000Z',
            'updated_at': '2021-09-15T04:33:13.000000Z',
          },
          {
            'id': 87,
            'master_management_id': 19,
            'option_value': 'purchase',
            'deleted_at': null,
            'created_at': '2021-09-15T04:33:13.000000Z',
            'updated_at': '2021-09-15T04:33:13.000000Z',
          },
        ],
      },
    ];

    expect(getListByKey(DATA, ['etc_device_introduction_method'])).toEqual(
      {
        etc_device_introduction_method: [
          {
            'id': 85,
            'master_management_id': 19,
            'option_value': 'Purchased',
            'deleted_at': null,
            'created_at': '2021-09-15T04:33:13.000000Z',
            'updated_at': '2021-09-15T04:33:13.000000Z',
          },
          {
            'id': 86,
            'master_management_id': 19,
            'option_value': 'leased',
            'deleted_at': null,
            'created_at': '2021-09-15T04:33:13.000000Z',
            'updated_at': '2021-09-15T04:33:13.000000Z',
          },
          {
            'id': 87,
            'master_management_id': 19,
            'option_value': 'purchase',
            'deleted_at': null,
            'created_at': '2021-09-15T04:33:13.000000Z',
            'updated_at': '2021-09-15T04:33:13.000000Z',
          },
        ],
      }
    );
  });

  test('Case 16: Test function cleanObj()', () => {
    const OBJ = {
      name: '',
      age: undefined,
      address: '',
      gender: undefined,
      dob: '2000/10/13',
    };

    expect(cleanObj(OBJ)).toEqual({ dob: '2000/10/13' });
  });

  test('Case 17: Test function changeSpace2Null()', () => {
    const OBJ = {
      name: '',
      age: undefined,
      address: '',
      gender: undefined,
      dob: '2000/10/13',
    };

    expect(changeSpace2Null(OBJ)).toEqual({
      name: null,
      age: null,
      address: null,
      gender: null,
      dob: '2000/10/13',
    });
  });

  test('Case 18: Test function getRole()', () => {
    store.commit('user/SET_ROLE', ['viewer']);

    expect(getRole('viewer')).toBe(true);
    expect(getRole('general_affairs')).toBe(false);
  });

  test('Case 19: Test function getPermission()', () => {
    store.commit('user/SET_PERMISSON', [
      'etc_device_device_list',
      'etc_device_registration',
      'etc_device_edit',
    ]);

    expect(getPermission('etc_device_device_list')).toBe(true);
    expect(getPermission('etc_device_registration')).toBe(true);
    expect(getPermission('master_manager_list')).toBe(false);
    expect(getPermission('user_delete')).toBe(false);
  });

  test('Case 20: Test function validEmail()', () => {
    expect(validEmail('vuducviet0131@gmail.com')).toBe(true);
    expect(validEmail('!#$%^&*&$#%-@gmail.com')).toBe(true);
    expect(validEmail('12312312312@gmail.com')).toBe(true);
    expect(validEmail('vuducviet0131    @  gmail  .  com')).toBe(false);
    expect(validEmail('')).toBe(false);
    expect(validEmail('              ')).toBe(false);
    expect(validEmail(null)).toBe(false);
    expect(validEmail(undefined)).toBe(false);
    expect(validEmail(123123123)).toBe(false);
    expect(validEmail(-1)).toBe(false);
    expect(validEmail(true)).toBe(false);
    expect(validEmail(false)).toBe(false);
    expect(validEmail('@')).toBe(false);
    expect(validEmail('@gmail.com')).toBe(false);
    expect(validEmail('vuducviet0131@gmail')).toBe(false);
    expect(validEmail('vuducviet0131@')).toBe(false);
    expect(validEmail('()@gmail.com')).toBe(false);
    expect(validEmail('@@@@@gmail.com')).toBe(false);
    expect(validEmail('[]@gmail.com')).toBe(false);
    expect(validEmail('***************')).toBe(false);
  });

  test('Case 21: Test function validPassword()', () => {
    expect(validPassword('123456789')).toBe(true);
    expect(validPassword('1234567')).toBe(false);
    expect(validPassword('1234567891234567891')).toBe(false);
    expect(validPassword('********')).toBe(true);
    expect(validPassword('***********************')).toBe(false);
    expect(validPassword(false)).toBe(false);
    expect(validPassword(true)).toBe(false);
    expect(validPassword('123  789')).toBe(false);
    expect(validPassword('        ')).toBe(false);
  });

  test('Case 22: Test function validEmptyOrWhiteSpace()', () => {
    expect(validEmptyOrWhiteSpace('                ')).toBe(true);
    expect(validEmptyOrWhiteSpace('')).toBe(true);
    expect(validEmptyOrWhiteSpace('Vu Duc Viet')).toBe(false);
  });

  test('Case 23: Test function validateNumberMoreThanZero()', () => {
    expect(validateNumberMoreThanZero(1)).toBe(true);
    expect(validateNumberMoreThanZero(2)).toBe(true);
    expect(validateNumberMoreThanZero(1310)).toBe(true);
    expect(validateNumberMoreThanZero(0)).toBe(false);
    expect(validateNumberMoreThanZero(-1)).toBe(false);
    expect(validateNumberMoreThanZero(-2)).toBe(false);
    expect(validateNumberMoreThanZero(-1310)).toBe(false);
  });

  test('Case 24: Test function validateNumber()', () => {
    expect(validateNumber(1)).toBe(true);
    expect(validateNumber(1123123)).toBe(true);
    expect(validateNumber('1123123')).toBe(true);
    expect(validateNumber(0)).toBe(true);
    expect(validateNumber(-1)).toBe(false);
    expect(validateNumber('-1')).toBe(false);
  });

  test('Case 25: Test function validateYYYYMMDD()', () => {
    expect(validateYYYYMMDD('2000/10/13')).toBe(true);
    expect(validateYYYYMMDD('2000-10-13')).toBe(true);
    expect(validateYYYYMMDD('2000/20/10')).toBe(false);
    expect(validateYYYYMMDD('13/10/2000')).toBe(false);
    expect(validateYYYYMMDD('10/20/2000')).toBe(false);
    expect(validateYYYYMMDD('2000/10/aa')).toBe(false);
    expect(validateYYYYMMDD('2000/10')).toBe(false);
    expect(validateYYYYMMDD('13/10')).toBe(false);
    expect(validateYYYYMMDD(null)).toBe(false);
    expect(validateYYYYMMDD(undefined)).toBe(false);
    expect(validateYYYYMMDD('sxcvxcv')).toBe(false);
    expect(validateYYYYMMDD('!!!!!')).toBe(false);
    expect(validateYYYYMMDD('123123')).toBe(false);
  });

  test('Case 26: Test function validateYYYYMM()', () => {
    expect(validateYYYYMM('2000/10')).toBe(true);
    expect(validateYYYYMM('2000-10')).toBe(true);
    expect(validateYYYYMM('2000/13')).toBe(false);
    expect(validateYYYYMM('2000-13')).toBe(false);
    expect(validateYYYYMM(null)).toBe(false);
    expect(validateYYYYMM(undefined)).toBe(false);
    expect(validateYYYYMM('sxcvxcv')).toBe(false);
    expect(validateYYYYMM('!!!!!')).toBe(false);
    expect(validateYYYYMM('123123')).toBe(false);
  });

  test('Case 27: Test function validateTwoIdDiff', () => {
    expect(validateTwoIdDiff(1, 2)).toBe(true);
    expect(validateTwoIdDiff('1', '2')).toBe(true);
    expect(validateTwoIdDiff(null, '2')).toBe(true);
    expect(validateTwoIdDiff(undefined, '2')).toBe(true);
    expect(validateTwoIdDiff('1', '1')).toBe(false);
  });
});
