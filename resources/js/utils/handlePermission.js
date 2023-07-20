import store from '@/store';
import CONST_ROLE from '@/const/role';
import CONST_PERMISSION from '@/const/permission';

export function getRole(roleCheck) {
  const ROLE = store.getters.role;

  if (!(CONST_ROLE.LIST_ROLE.includes(roleCheck))) {
    return false;
  }

  return ROLE.includes(roleCheck);
}

export function getPermission(permissionCheck) {
  const PERMISSION = store.getters.permission;

  if (!(CONST_PERMISSION.LIST_PERMISSION.includes(permissionCheck))) {
    return false;
  }

  return PERMISSION.includes(permissionCheck);
}
