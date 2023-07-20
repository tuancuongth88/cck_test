import CONST_AUTH from '@/const/auth';
import CONST_AUTHORITY from '@/const/authority';
import CONST_DEGITACHO from '@/const/degitacho';
import CONST_ETC from '@/const/etc';
import CONST_LANGUAGE from '@/const/language';
import CONST_MONTH_PICKER from '@/const/monthPicker';
import CONST_PERMISSION from '@/const/permission';
import CONST_ROLE from '@/const/role';
import CONST_USER_MANAGEMENT from '@/const/userManagement';

describe('TEST CONST', () => {
  test('Case 1: Test const AUTH', () => {
    const USER_NOT_FOUND = {
      code: 404,
      message: 'user not found',
      data: '',
    };

    expect(CONST_AUTH.USER_NOT_FOUND.code).toEqual(USER_NOT_FOUND.code);
    expect(CONST_AUTH.USER_NOT_FOUND.message).toEqual(USER_NOT_FOUND.message);
    expect(CONST_AUTH.USER_NOT_FOUND.data).toEqual(USER_NOT_FOUND.data);

    const PROFILE = {
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
    };

    expect(CONST_AUTH.PROFILE.id).toEqual(PROFILE.id);
    expect(CONST_AUTH.PROFILE.email).toEqual(PROFILE.email);
    expect(CONST_AUTH.PROFILE.phone).toEqual(PROFILE.phone);
    expect(CONST_AUTH.PROFILE.name).toEqual(PROFILE.name);
    expect(CONST_AUTH.PROFILE.fax).toEqual(PROFILE.fax);
    expect(CONST_AUTH.PROFILE.address).toEqual(PROFILE.address);
    expect(CONST_AUTH.PROFILE.gender).toEqual(PROFILE.gender);
    expect(CONST_AUTH.PROFILE.email_verified_at).toEqual(PROFILE.email_verified_at);
    expect(CONST_AUTH.PROFILE.status).toEqual(PROFILE.status);
    expect(CONST_AUTH.PROFILE.expToken).toEqual(PROFILE.expToken);
  });

  test('Case 2: Test const AUTHORITY', () => {
    const AUTHORITY = [
      'general_affairs',
      'vehicle_analysis',
      'information_systems',
      'center_manager',
      'viewer',
    ];

    for (let indexAuthority = 0; indexAuthority < AUTHORITY.length; indexAuthority++) {
      const TEST = AUTHORITY[indexAuthority];

      expect(CONST_AUTHORITY.AUTHORITY[indexAuthority]).toEqual(TEST);
    }
  });

  test('Case 3: Test const DEGITACHO', () => {
    const DEGITACHO = {
      FIRST_REGISTRATION_MONTH: 'first_registration_month',
      FIRST_APPLICATION_START_DATE: 'first_application_start_date',
      FIRST_ENTRY_DATE: 'first_entry_date',
      DEGITACHO_DEVICE_NUMBER: 'degitacho_device_number',
      OPERATION_STATUS: 'operation_status',
      DEGITACHO_DEVICE_MANUFACTURER: 'degitacho_device_manufacturer',
      DEGITACHO_DEVICE_NAME: 'degitacho_device_name',
      INITIAL_INTRODUCTION_DATE: 'initial_introduction_date',
      DEVICE_INSTALLATION_DATE: 'device_installation_date',
      DEVICE_REMOVAL_DATE: 'device_removal_date',
      DEGITACHO_INTRODUCTION_METHOD: 'degitacho_introduction_method',
      BASE_PRICE: 'base_price',
      ID: 'id',
      VEHICLE_ID: 'vehicle_id',
      REGISTRATION_NUMBER: 'registration_number',
      DOOR_NO: 'door_no',
      CENTER_CODE: 'center_code',

      // TABLE
      HISTORY_DEVICE_INSTALLATION_DATE: 'history_device_installation_date',
      HISTORY_DEVICE_REMOVAL_DATE: 'history_device_removal_date',
      HISTORY_DEGITACHO_INTRODUCTION_METHOD: 'history_degitacho_introduction_method',
      HISTORY_VEHICLE_ID: 'history_vehicle_id',
      HISTORY_DELETE: 'history_delete',

      // Key Edit
      VALUE: 'value',
      APPLICATION_START_DATE: 'application_start_date',
      ACCOUNTING_MONTH: 'accounting_month',

      // Item key
      ITEM_KEY: 'item_key',
      MASTER_MANAGEMENT_OPTION: 'master_management_option',
      LIST_MANUFACTURER: 'refrigerator_manufacturer',
      LIST_NAME: 'degi_tacho_device_name',
      LIST_METHOD: 'degi_tacho_introduction_method',
    };

    for (const key in DEGITACHO) {
      if (Object.hasOwnProperty.call(DEGITACHO, key)) {
        expect(CONST_DEGITACHO[key]).toEqual(DEGITACHO[key]);
      }
    }
  });

  test('Case 4: Test const ETC', () => {
    const ETC = {
      FIRST_REGISTRATION_MONTH: 'first_registration_month',
      FIRST_APPLICATION_START_DATE: 'first_application_start_date',
      FIRST_ENTRY_DATE: 'first_entry_date',
      ETC_DEVICE_NUMBER: 'etc_device_management_number',
      OPERATION_STATUS: 'operation_status',
      ETC_DEVICE_MANUFACTURER: 'etc_device_manufacturer',
      ETC_DEVICE_NAME: 'etc_device_name',
      INITIAL_INTRODUCTION_DATE: 'initial_introduction_date',
      DEVICE_INSTALLATION_DATE: 'device_installation_date',
      DEVICE_REMOVAL_DATE: 'device_removal_date',
      ETC_INTRODUCTION_METHOD: 'etc_device_introduction_method',
      BASE_PRICE: 'base_price',
      ID: 'id',
      VEHICLE_ID: 'vehicle_id',
      REGISTRATION_NUMBER: 'registration_number',
      DOOR_NO: 'door_no',
      CENTER_CODE: 'center_code',

      // TABLE
      HISTORY_DEVICE_INSTALLATION_DATE: 'history_device_installation_date',
      HISTORY_DEVICE_REMOVAL_DATE: 'history_device_removal_date',
      HISTORY_ETC_INTRODUCTION_METHOD: 'history_etc_device_introduction_method',
      HISTORY_VEHICLE_ID: 'history_vehicle_id',
      HISTORY_DELETE: 'history_delete',

      // Key Edit
      VALUE: 'value',
      APPLICATION_START_DATE: 'application_start_date',
      ACCOUNTING_MONTH: 'accounting_month',

      // Item key
      ITEM_KEY: 'item_key',
      MASTER_MANAGEMENT_OPTION: 'master_management_option',
      LIST_MANUFACTURER: 'etc_device_manufacturer',
      LIST_NAME: 'etc_device_name',
      LIST_METHOD: 'etc_device_introduction_method',
    };

    for (const key in ETC) {
      if (Object.hasOwnProperty.call(ETC, key)) {
        expect(CONST_ETC[key]).toEqual(ETC[key]);
      }
    }
  });

  test('Case 5: Test const LANGUAGE', () => {
    const LANG = {
      ENGLISH: 'en',
      JAPANESE: 'ja',
    };

    expect(CONST_LANGUAGE.ENGLISH).toEqual(LANG.ENGLISH);
    expect(CONST_LANGUAGE.JAPANESE).toEqual(LANG.JAPANESE);
  });

  test('Case 6: Test const MONTH PICKER', () => {
    const MONTHS = [
      'MONTH_1',
      'MONTH_2',
      'MONTH_3',
      'MONTH_4',
      'MONTH_5',
      'MONTH_6',
      'MONTH_7',
      'MONTH_8',
      'MONTH_9',
      'MONTH_10',
      'MONTH_11',
      'MONTH_12',
    ];

    for (let index = 0; index < MONTHS.length; index++) {
      const element = MONTHS[index];
      expect(CONST_MONTH_PICKER.LIST_MONTHS[index]).toEqual(element);
    }
  });

  test('Case 7: Test const Permission', () => {
    const HEADER_VEHICLE = 'header_vehicle';
    const HEADER_DEGI_TACHO = 'header_degi_tacho';
    const HEADER_ETC_DEVICE = 'header_etc_device';
    const HEADER_MASTER_MANAGEMENT = 'header_master_management';
    const HEADER_USER_MANAGEMENT = 'header_user_management';
    const VEHICLE_LIST = 'vehicle_list';
    const VEHICLE_DOWNLOAD_LIST = 'vehicle_download_list';
    const VEHICLE_NEW_REGISTRATION = 'vehicle_new_registration';
    const VEHICLE_REGISTRATION_IMAGE_UPLOAD = 'vehicle_registration_image_upload';
    const VEHICLE_NEW_REGISTRATION_DEVICE_MASTER_COOPERATION = 'vehicle_new_registration_device_master_cooperation';
    const VEHICLE_NEW_REGISTRATION_OPERATION_STATUS_AUTOMATIC_REFLECTION = 'vehicle_new_registration_operation_status_automatic_reflection';
    const VEHICLE_DETAILED_DISPLAY = 'vehicle_detailed_display';
    const VEHICLE_DETAILED_DISPLAY_UPDATE_SCHEDULE_INFORMATION_DISPLAY = 'vehicle_detailed_display_update_schedule_information_display';
    const VEHICLE_DETAILED_DISPLAY_HISTORY_DISPLAY = 'vehicle_detailed_display_history_display';
    const VEHICLE_DETAILED_DISPLAY_IMAGE_DOWNLOAD_ALL = 'vehicle_detailed_display_image_download_all';
    const VEHICLE_DETAILED_DISPLAY_IMAGE_DOWNLOAD_OTHER_THAN_CONTRACT = 'vehicle_detailed_display_image_download_other_than_contract';
    const VEHICLE_DETAILED_VIEW_LINK = 'vehicle_detailed_view_link';
    const VEHICLE_DOWNLOAD = 'vehicle_download';
    const VEHICLE_EDIT = 'vehicle_edit';
    const VEHICLE_EDIT_HISTORY_DELETE = 'vehicle_edit_history_delete';
    const VEHICLE_EDIT_REGISTRATION_IMAGE_UPLOAD = 'vehicle_edit_registration_image_upload';
    const VEHICLE_EDIT_VEHICLE_DEVICE_MASTER_COOPERATION = 'vehicle_edit_vehicle_device_master_cooperation';
    const VEHICLE_EDIT_OPERATION_STATUS_AUTOMATIC_REFLECTION = 'vehicle_edit_operation_status_automatic_reflection';
    const VEHICLE_EDIT_DISPLAY_UPDATE_SCHEDULE_INFORMATION_DISPLAY = 'vehicle_edit_display_update_schedule_information_display';
    const VEHICLE_EDIT_DISPLAY_HISTORY_DISPLAY = 'vehicle_edit_display_history_display';
    const VEHICLE_EDIT_VEHICLE_ANALYSIS = 'vehicle_edit_vehicle_analysis';
    const DEGI_TACHO_LIST = 'degi_tacho_list';
    const DEGI_TACHO_REGISTRATION = 'degi_tacho_registration';
    const DEGI_TACHO_REGISTRATION_VEHICLE_MASTER_COOPERATION = 'degi_tacho_registration_vehicle_master_cooperation';
    const DEGI_TACHO_DETAIL_VIEW = 'degi_tacho_detail_view';
    const DEGI_TACHO_DETAILED_DISPLAY_UPDATE_SCHEDULE_INFORMATION_DISPLAY = 'degi_tacho_detailed_display_update_schedule_information_display';
    const DEGI_TACHO_DETAILED_DISPLAY_HISTORY_DISPLAY = 'degi_tacho_detailed_display_history_display';
    const DEGI_TACHO_DETAILED_DISPLAY_LINK = 'degi_tacho_detailed_display_link';
    const DEGI_TACHO_EDIT = 'degi_tacho_edit';
    const DEGI_TACHO_EDIT_DELETE_HISTORY = 'degi_tacho_edit_delete_history';
    const DEGI_TACHO_EDIT_MASTER_COOPERATION = 'degi_tacho_edit_master_cooperation';
    const DEGI_TACHO_EDIT_DISPLAY_UPDATE_SCHEDULE_INFORMATION_DISPLAY = 'degi_tacho_edit_display_update_schedule_information_display';
    const DEGI_TACHO_EDIT_DISPLAY_HISTORY_DISPLAY = 'degi_tacho_edit_display_history_display';
    const ETC_DEVICE_DEVICE_LIST = 'etc_device_device_list';
    const ETC_DEVICE_REGISTRATION = 'etc_device_registration';
    const ETC_DEVICE_REGISTRATION_VEHICLE_MASTER_COOPERATION = 'etc_device_registration_vehicle_master_cooperation';
    const ETC_DEVICE_DETAIL_VIEW = 'etc_device_detail_view';
    const ETC_DEVICE_DETAILED_DISPLAY_UPDATE_SCHEDULE_INFORMATION_DISPLAY = 'etc_device_detailed_display_update_schedule_information_display';
    const ETC_DEVICE_DETAILED_DISPLAY_HISTORY_DISPLAY = 'etc_device_detailed_display_history_display';
    const ETC_DEVICE_DETAILED_VIEW_LINK = 'etc_device_detailed_view_link';
    const ETC_DEVICE_EDIT = 'etc_device_edit';
    const ETC_DEVICE_EDIT_DELETE_HISTORY = 'etc_device_edit_delete_history';
    const ETC_DEVICE_EDIT_REGISTRATION_ETC_MASTER_COOPERATION = 'etc_device_edit_registration_etc_master_cooperation';
    const ETC_DEVICE_EDIT_DISPLAY_UPDATE_SCHEDULE_INFORMATION_DISPLAY = 'etc_device_edit_display_update_schedule_information_display';
    const ETC_DEVICE_EDIT_DISPLAY_HISTORY_DISPLAY = 'etc_device_edit_display_history_display';
    const MASTER_MANAGER_LIST = 'master_manager_list';
    const MASTER_MANAGER_DETAILED_DISPLAY = 'master_manager_detailed_display';
    const MASTER_MANAGER_ADD = 'master_manager_add';
    const MASTER_MANAGER_DELETE = 'master_manager_delete';
    const MASTER_MANAGER_EDIT = 'master_manager_edit';
    const USER_LIST = 'user_list';
    const USER_SELECTIVE_DELETE = 'user_selective_delete';
    const USER_NEW_REGISTRATION = 'user_new_registration';
    const USER_DISPLAY = 'user_display';
    const USER_DELETE = 'user_delete';
    const USER_EDIT = 'user_edit';

    const PERMISSION = {
      // Key permission
      HEADER_VEHICLE,
      HEADER_DEGI_TACHO,
      HEADER_ETC_DEVICE,
      HEADER_MASTER_MANAGEMENT,
      HEADER_USER_MANAGEMENT,
      VEHICLE_LIST,
      VEHICLE_DOWNLOAD_LIST,
      VEHICLE_NEW_REGISTRATION,
      VEHICLE_REGISTRATION_IMAGE_UPLOAD,
      VEHICLE_NEW_REGISTRATION_DEVICE_MASTER_COOPERATION,
      VEHICLE_NEW_REGISTRATION_OPERATION_STATUS_AUTOMATIC_REFLECTION,
      VEHICLE_DETAILED_DISPLAY,
      VEHICLE_DETAILED_DISPLAY_UPDATE_SCHEDULE_INFORMATION_DISPLAY,
      VEHICLE_DETAILED_DISPLAY_HISTORY_DISPLAY,
      VEHICLE_DETAILED_DISPLAY_IMAGE_DOWNLOAD_ALL,
      VEHICLE_DETAILED_DISPLAY_IMAGE_DOWNLOAD_OTHER_THAN_CONTRACT,
      VEHICLE_DETAILED_VIEW_LINK,
      VEHICLE_DOWNLOAD,
      VEHICLE_EDIT,
      VEHICLE_EDIT_HISTORY_DELETE,
      VEHICLE_EDIT_REGISTRATION_IMAGE_UPLOAD,
      VEHICLE_EDIT_VEHICLE_DEVICE_MASTER_COOPERATION,
      VEHICLE_EDIT_OPERATION_STATUS_AUTOMATIC_REFLECTION,
      VEHICLE_EDIT_DISPLAY_UPDATE_SCHEDULE_INFORMATION_DISPLAY,
      VEHICLE_EDIT_DISPLAY_HISTORY_DISPLAY,
      VEHICLE_EDIT_VEHICLE_ANALYSIS,
      DEGI_TACHO_LIST,
      DEGI_TACHO_REGISTRATION,
      DEGI_TACHO_REGISTRATION_VEHICLE_MASTER_COOPERATION,
      DEGI_TACHO_DETAIL_VIEW,
      DEGI_TACHO_DETAILED_DISPLAY_UPDATE_SCHEDULE_INFORMATION_DISPLAY,
      DEGI_TACHO_DETAILED_DISPLAY_HISTORY_DISPLAY,
      DEGI_TACHO_DETAILED_DISPLAY_LINK,
      DEGI_TACHO_EDIT,
      DEGI_TACHO_EDIT_DELETE_HISTORY,
      DEGI_TACHO_EDIT_MASTER_COOPERATION,
      DEGI_TACHO_EDIT_DISPLAY_UPDATE_SCHEDULE_INFORMATION_DISPLAY,
      DEGI_TACHO_EDIT_DISPLAY_HISTORY_DISPLAY,
      ETC_DEVICE_DEVICE_LIST,
      ETC_DEVICE_REGISTRATION,
      ETC_DEVICE_REGISTRATION_VEHICLE_MASTER_COOPERATION,
      ETC_DEVICE_DETAIL_VIEW,
      ETC_DEVICE_DETAILED_DISPLAY_UPDATE_SCHEDULE_INFORMATION_DISPLAY,
      ETC_DEVICE_DETAILED_DISPLAY_HISTORY_DISPLAY,
      ETC_DEVICE_DETAILED_VIEW_LINK,
      ETC_DEVICE_EDIT,
      ETC_DEVICE_EDIT_DELETE_HISTORY,
      ETC_DEVICE_EDIT_REGISTRATION_ETC_MASTER_COOPERATION,
      ETC_DEVICE_EDIT_DISPLAY_UPDATE_SCHEDULE_INFORMATION_DISPLAY,
      ETC_DEVICE_EDIT_DISPLAY_HISTORY_DISPLAY,
      MASTER_MANAGER_LIST,
      MASTER_MANAGER_DETAILED_DISPLAY,
      MASTER_MANAGER_ADD,
      MASTER_MANAGER_DELETE,
      MASTER_MANAGER_EDIT,
      USER_LIST,
      USER_SELECTIVE_DELETE,
      USER_NEW_REGISTRATION,
      USER_DISPLAY,
      USER_DELETE,
      USER_EDIT,

      // List permission
      LIST_PERMISSION: [
        HEADER_VEHICLE,
        HEADER_DEGI_TACHO,
        HEADER_ETC_DEVICE,
        HEADER_MASTER_MANAGEMENT,
        HEADER_USER_MANAGEMENT,
        VEHICLE_LIST,
        VEHICLE_DOWNLOAD_LIST,
        VEHICLE_NEW_REGISTRATION,
        VEHICLE_REGISTRATION_IMAGE_UPLOAD,
        VEHICLE_NEW_REGISTRATION_DEVICE_MASTER_COOPERATION,
        VEHICLE_NEW_REGISTRATION_OPERATION_STATUS_AUTOMATIC_REFLECTION,
        VEHICLE_DETAILED_DISPLAY,
        VEHICLE_DETAILED_DISPLAY_UPDATE_SCHEDULE_INFORMATION_DISPLAY,
        VEHICLE_DETAILED_DISPLAY_HISTORY_DISPLAY,
        VEHICLE_DETAILED_DISPLAY_IMAGE_DOWNLOAD_ALL,
        VEHICLE_DETAILED_DISPLAY_IMAGE_DOWNLOAD_OTHER_THAN_CONTRACT,
        VEHICLE_DETAILED_VIEW_LINK,
        VEHICLE_DOWNLOAD,
        VEHICLE_EDIT,
        VEHICLE_EDIT_HISTORY_DELETE,
        VEHICLE_EDIT_REGISTRATION_IMAGE_UPLOAD,
        VEHICLE_EDIT_VEHICLE_DEVICE_MASTER_COOPERATION,
        VEHICLE_EDIT_OPERATION_STATUS_AUTOMATIC_REFLECTION,
        VEHICLE_EDIT_DISPLAY_UPDATE_SCHEDULE_INFORMATION_DISPLAY,
        VEHICLE_EDIT_DISPLAY_HISTORY_DISPLAY,
        VEHICLE_EDIT_VEHICLE_ANALYSIS,
        DEGI_TACHO_LIST,
        DEGI_TACHO_REGISTRATION,
        DEGI_TACHO_REGISTRATION_VEHICLE_MASTER_COOPERATION,
        DEGI_TACHO_DETAIL_VIEW,
        DEGI_TACHO_DETAILED_DISPLAY_UPDATE_SCHEDULE_INFORMATION_DISPLAY,
        DEGI_TACHO_DETAILED_DISPLAY_HISTORY_DISPLAY,
        DEGI_TACHO_DETAILED_DISPLAY_LINK,
        DEGI_TACHO_EDIT,
        DEGI_TACHO_EDIT_DELETE_HISTORY,
        DEGI_TACHO_EDIT_MASTER_COOPERATION,
        DEGI_TACHO_EDIT_DISPLAY_UPDATE_SCHEDULE_INFORMATION_DISPLAY,
        DEGI_TACHO_EDIT_DISPLAY_HISTORY_DISPLAY,
        ETC_DEVICE_DEVICE_LIST,
        ETC_DEVICE_REGISTRATION,
        ETC_DEVICE_REGISTRATION_VEHICLE_MASTER_COOPERATION,
        ETC_DEVICE_DETAIL_VIEW,
        ETC_DEVICE_DETAILED_DISPLAY_UPDATE_SCHEDULE_INFORMATION_DISPLAY,
        ETC_DEVICE_DETAILED_DISPLAY_HISTORY_DISPLAY,
        ETC_DEVICE_DETAILED_VIEW_LINK,
        ETC_DEVICE_EDIT,
        ETC_DEVICE_EDIT_DELETE_HISTORY,
        ETC_DEVICE_EDIT_REGISTRATION_ETC_MASTER_COOPERATION,
        ETC_DEVICE_EDIT_DISPLAY_UPDATE_SCHEDULE_INFORMATION_DISPLAY,
        ETC_DEVICE_EDIT_DISPLAY_HISTORY_DISPLAY,
        MASTER_MANAGER_LIST,
        MASTER_MANAGER_DETAILED_DISPLAY,
        MASTER_MANAGER_ADD,
        MASTER_MANAGER_DELETE,
        MASTER_MANAGER_EDIT,
        USER_LIST,
        USER_SELECTIVE_DELETE,
        USER_NEW_REGISTRATION,
        USER_DISPLAY,
        USER_DELETE,
        USER_EDIT,
      ],
    };

    for (const key in PERMISSION) {
      if (Object.hasOwnProperty.call(PERMISSION, key)) {
        const element = PERMISSION[key];

        expect(CONST_PERMISSION[key]).toEqual(element);
      }
    }
  });

  test('Case 8: Test const ROLE', () => {
    const GENERAL_AFFAIRS = 'general_affairs';
    const VEHICLE_ANALYSIS = 'vehicle_analysis';
    const INFORMATION_SYSTEMS = 'information_systems';
    const CENTER_MANAGER = 'center_manager';
    const VIEWER = 'viewer';

    const ROLE = {
      // Key role
      GENERAL_AFFAIRS,
      VEHICLE_ANALYSIS,
      INFORMATION_SYSTEMS,
      CENTER_MANAGER,
      VIEWER,

      // List role
      LIST_ROLE: [
        GENERAL_AFFAIRS,
        VEHICLE_ANALYSIS,
        INFORMATION_SYSTEMS,
        CENTER_MANAGER,
        VIEWER,
      ],
    };

    for (const key in ROLE) {
      if (Object.hasOwnProperty.call(ROLE, key)) {
        const element = ROLE[key];
        expect(CONST_ROLE[key]).toEqual(element);
      }
    }
  });

  test('Case 9: Test const USER MANAGEMENT', () => {
    const USER_MANAGEMENT = {
      ID: 'id',
      USERNAME: 'username',
      EMAIL: 'email',
      NAME: 'name',
      PASSWORD: 'password',
      STATUS: 'status',
      ROLE: 'role',
    };

    for (const key in USER_MANAGEMENT) {
      if (Object.hasOwnProperty.call(USER_MANAGEMENT, key)) {
        const element = USER_MANAGEMENT[key];
        expect(CONST_USER_MANAGEMENT[key]).toEqual(element);
      }
    }
  });
});
