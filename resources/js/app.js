import Vue from 'vue';
import App from './pages/App.vue';
import './permission';
import router from './router';
import store from './store';
import i18n from './lang';
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';
import './assets/css/all.min.css';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';
import './scss/index.scss';

import CustomPagination from '@/components/pagination/index.vue';

Vue.use(BootstrapVue);
Vue.use(IconsPlugin);

Vue.directive('mode', {
  // Directive definition
});

/** Register global components */
Vue.component('CustomPagination', CustomPagination);

// Event bus
import Bus from '@/eventBus';
Vue.use(Bus);
Vue.config.productionTip = false;

new Vue({
  el: '#app',
  components: {
    Pagination: () => import('@/components/pagination/index.vue'),
    // Thêm tên cho thành phần
  },
  router,
  store,
  i18n,
  render: h => h(App),
});
