import { asyncRoutes, constantRoutes } from '@/router';
import { getAddRoutes, getRoutes, getCurrentRoutes } from '@/utils/handleRole';

/**
 * Check if it matches the current user right by meta.role
 * @param {String[]} roles
 * @param {String[]} permissions
 * @param route
 */
function canAccess(roles, permissions, route) {
  if (route.meta) {
    let hasRole = true;
    let hasPermission = true;

    if (route.meta.roles || route.meta.permissions) {
      hasRole = false;
      hasPermission = false;

      if (route.meta.roles) {
        hasRole = roles.some(role => route.meta.roles.includes(role));
      }

      if (route.meta.permissions) {
        hasPermission = permissions.some(permission => route.meta.permissions.includes(permission));
      }
    }

    return hasRole || hasPermission;
  }

  return true;
}

/**
 * Find all routes of this role
 * @param routes asyncRoutes
 * @param roles
 */
function filterAsyncRoutes(routes, roles, permissions) {
  const res = [];

  routes.forEach(route => {
    const tmp = { ...route };
    if (canAccess(roles, permissions, tmp)) {
      if (tmp.children) {
        tmp.children = filterAsyncRoutes(
          tmp.children,
          roles,
          permissions
        );
      }
      res.push(tmp);
    }
  });

  return res;
}

const state = {
  routes: getRoutes(),
  addRoutes: getAddRoutes(),
  currentRoutes: getCurrentRoutes(),
};

const mutations = {
  SET_ROUTES: (state, routes) => {
    state.addRoutes = routes;
    state.routes = constantRoutes.concat(routes);

    localStorage.setItem('addRoutes', JSON.stringify(routes));
    localStorage.setItem('routes', JSON.stringify(constantRoutes.concat(routes)));
  },
  RESET_ROUTES: (state) => {
    state.addRoutes = [];
    state.routes = [];

    localStorage.setItem('addRoutes', JSON.stringify([]));
    localStorage.setItem('routes', JSON.stringify([]));
  },
  SET_CURRENT_ROUTES: (state, currentRoutes) => {
    state.currentRoutes = currentRoutes;

    localStorage.setItem('currentRoutes', currentRoutes);
  },
};

const actions = {
  generateRoutes({ commit }, { roles, permissions }) {
    return new Promise(resolve => {
      const ACCESSED_ROUTES = filterAsyncRoutes(asyncRoutes, roles, permissions);

      commit('SET_ROUTES', ACCESSED_ROUTES);
      resolve(ACCESSED_ROUTES);
    });
  },
  resetRoutes({ commit }) {
    commit('RESET_ROUTES');
  },
  setCurrentRoutes({ commit }, currentRoutes) {
    commit('SET_CURRENT_ROUTES', currentRoutes);
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
