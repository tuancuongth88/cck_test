import Layout from '@/layout';

const userManagement = {
  path: '/usermanagement',
  name: 'UserManagement',
  meta: {
    title: 'ROUTER_USER_MANAGEMENT',
    breadcrumb: 'BREADCRUMB_USER_MANAGEMENT',
    // permissions: [
    //   'header_user_management',
    // ],
  },
  component: Layout,
  redirect: { name: 'UserManagementList' },
  children: [
    {
      path: 'list',
      name: 'UserManagementList',
      meta: {
        title: 'ROUTER_USER_MANAGEMENT_LIST',
        breadcrumb: 'BREADCRUMB_USER_MANAGEMENT_LIST',
        // permissions: [
        //   'user_list',
        // ],
      },
      component: () => import(/* webpackChunkName: "UserManagementList" */ '@/pages/UserManagement/List.vue'),
    },
    {
      path: 'create',
      name: 'UserManagementCreate',
      meta: {
        title: 'ROUTER_USER_MANAGEMENT_CREATE',
        breadcrumb: 'BREADCRUMB_USER_MANAGEMENT_CREATE',
        // permissions: [
        //   'user_new_registration',
        // ],
      },
      component: () => import(/* webpackChunkName: "UserManagementCreate" */ '@/pages/UserManagement/Create.vue'),
    },
    {
      path: 'detail/:id',
      name: 'UserManagementDetail',
      meta: {
        title: 'ROUTER_USER_MANAGEMENT_DETAIL',
        breadcrumb: 'BREADCRUMB_USER_MANAGEMENT_DETAIL',
        // permissions: [
        //   'user_display',
        // ],
      },
      component: () => import(/* webpackChunkName: "UserManagementDetail" */ '@/pages/UserManagement/Detail.vue'),
    },
    {
      path: 'edit/:id',
      name: 'UserManagementEdit',
      meta: {
        title: 'ROUTER_USER_MANAGEMENT_EDIT',
        breadcrumb: 'BREADCRUMB_USER_MANAGEMENT_EDIT',
        // permissions: [
        //   'user_edit',
        // ],
      },
      component: () => import(/* webpackChunkName: "UserManagementEdit" */ '@/pages/UserManagement/Edit.vue'),
    },
    //
    // {
    //   path: 'distribute-msg-create',
    //   name: 'DistributeMsgCreate',
    //   meta: {
    //     title: 'DISTRIBUTE_MSG_CREATE',
    //     breadcrumb: 'BREADCRUMB_DISTRIBUTE_MSG_CREATE',
    //     permissions: [
    //       'user_list',
    //     ],
    //   },
    //   component: () => import(/* webpackChunkName: "DistributeMsgCreate" */ '@/pages/Tab1/DistributeMsgCreate/index.vue'),
    // },
  ],
};

export default userManagement;
