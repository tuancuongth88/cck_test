const HEADER_VEHICLE = 'header_vehicle';
const HEADER_DEGI_TACHO = 'header_degi_tacho';
const HEADER_ETC_DEVICE = 'header_etc_device';
const HEADER_MASTER_MANAGEMENT = 'header_master_management';
const HEADER_USER_MANAGEMENT = 'header_user_management';
const MATCHING_MANAGEMENT = 'header_matching_management';
const MATCHING_MANAGEMENT_LIST = 'matching_management_list';
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
  MATCHING_MANAGEMENT,
  MATCHING_MANAGEMENT_LIST,
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
    MATCHING_MANAGEMENT,
    MATCHING_MANAGEMENT_LIST,
    USER_LIST,
    USER_SELECTIVE_DELETE,
    USER_NEW_REGISTRATION,
    USER_DISPLAY,
    USER_DELETE,
    USER_EDIT,
  ],
};

export default PERMISSION;
