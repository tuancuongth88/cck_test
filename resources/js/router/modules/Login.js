const Login = {
  path: '/login',
  name: 'Login',
  meta: {
    title: 'ROUTER_LOGIN',
    icon: '',
  },
  hidden: true,
  component: () => import(/* webpackChunkName: "Login" */ '@/pages/Login/index.vue'),
};

export default Login;
