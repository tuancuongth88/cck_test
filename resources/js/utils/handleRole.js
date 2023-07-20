import Cookies from 'js-cookie';
import constAuthority from '@/const/authority';

/**
 * Change object roles BE response -> Object { role, permission }
 * @param {Object} LIST_ROLE
 * @returns Object
 */
export function handleRole(LIST_ROLE) {
  const ROLES = [];
  const PERMISSIONS = [];
  for (const ROLE in LIST_ROLE) {
    ROLES.push(ROLE);

    for (const PERMISSION in LIST_ROLE[ROLE]) {
      PERMISSIONS.push(LIST_ROLE[ROLE][PERMISSION]['name']);
    }
  }

  return {
    ROLES,
    PERMISSIONS,
  };
}

/**
 * Function get Roles in Cookies
 * @returns List Roles
 */
export function getRole() {
  const ROLE = Cookies.get('role');

  if (ROLE) {
    return JSON.parse(ROLE);
  }

  return [];
}

/**
 * Function get Permission in Cookies
 * @returns List Permissions
 */
export function getPermission() {
  const PERMISSION = Cookies.get('permission');

  if (PERMISSION) {
    return JSON.parse(PERMISSION);
  }

  return [];
}

/**
 * Function get List Add Routes in Local Storage
 * @returns List Add Routes
 */
export function getAddRoutes() {
  const ADD_ROUTES = localStorage.getItem('addRoutes');

  if (ADD_ROUTES) {
    return JSON.parse(ADD_ROUTES);
  }

  return [];
}

/**
 * Function get list Routes in Local Storage
 * @returns List Routes
 */
export function getRoutes() {
  const ROUTES = localStorage.getItem('routes');

  if (ROUTES) {
    return JSON.parse(ROUTES);
  }

  return [];
}

/**
 * Function get Current Routes in Local Storage
 * @returns Current Routes
 */
export function getCurrentRoutes() {
  const CURRENT_ROUTES = localStorage.getItem('currentRoutes');

  if (CURRENT_ROUTES) {
    return CURRENT_ROUTES;
  }

  return '/vehicle/list';
}

/**
 * Function get index of authority
 * @param {String} authority
 * @returns Index of authority
 */
export function getAuthority(authority) {
  const LIST_AUTHORITY = constAuthority.AUTHORITY;

  return (LIST_AUTHORITY.indexOf(authority) + 1);
}
