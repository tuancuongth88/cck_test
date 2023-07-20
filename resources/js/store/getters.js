const getters = {
  language: state => state.app.language,
  loading: state => state.app.loading,
  name: state => state.user.profile.name,
  email: state => state.user.profile.email,
  token: state => state.user.access_token,
  profile: state => state.user.profile,
  expToken: state => state.user.profile.expToken,
  role: state => state.user.role,
  permission: state => state.user.permission,
  routes: state => state.permission.routes,
  addRoutes: state => state.permission.addRoutes,
  permissionRoutes: state => state.permission.routes,
  isReload: state => state.permission.isReload,
  currentRoutes: state => state.permission.currentRoutes,
  listUser: state => state.userManagement.listUser,
  degitacho: state => state.degitacho.degitachoOrigin,
  etc: state => state.etc.etcOrigin,
  permissionCheck: state => state.user.profile.type,
  hrSearchParams: state => state.hrSearchParams.searchParams,
};

export default getters;
