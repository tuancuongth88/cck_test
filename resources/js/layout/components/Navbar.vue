<template>
  <div class="main-menu">
    <div class="container">
      <b-navbar toggleable="lg" type="light" class="navbar-app">
        <b-collapse id="nav-collapse" is-nav>
          <Menu />
          <!-- <b-navbar-nav class="ml-auto">
            <MenuRight />
          </b-navbar-nav> -->
        </b-collapse>
        <!-- <b-card class="card" no-body>
          <b-tabs v-model="tabIndex" fill>
            <b-tab :title="$t('HOME')" :title-link-class="linkClass(0)">
              <tab-1 />
            </b-tab>
            <b-tab :title="$t('HR_MANAGEMENT')" :title-link-class="linkClass(1)">
              <tab-3 />
            </b-tab>
            <b-tab :title="$t('COMPANY_MANAGEMENT')" :title-link-class="linkClass(2)">
              <tab-2 />
            </b-tab>
            <b-tab :title="$t('MATCHING_MANAGEMENT')" :title-link-class="linkClass(3)" @click="matchingManagement()">
              <tab-4 />
            </b-tab>
          </b-tabs>
        </b-card> -->
      </b-navbar>
    </div>
  </div>
</template>
<script>
// import Tab1 from '../../pages/Tab1/index.vue';
// import Tab2 from '../../pages/Tab2/index.vue';
// import Tab3 from '../../pages/Tab3/index.vue';
// import Tab4 from '../../pages/Tab4/Tab4.vue';
import Menu from './Menu.vue';
// import MenuRight from './MenuRight.vue';
export default {
  name: 'Navbar',
  components: {
    // Tab1,
    // Tab2,
    // Tab3,
    // Tab4,
    Menu,
    // MenuRight,
  },
  data() {
    return {
      tabIndex: 0,
    };
  },
  methods: {
    matchingManagement() {
      this.$router.push('/matching-management');
    },
    linkClass(idx) {
      if (this.tabIndex === idx) {
        console.log(this.tabIndex);
        return ['bg-primary', 'text-light'];
      } else {
        console.log(this.tabIndex);
        return ['bg-light', 'text-info'];
      }
    },
  },
};
</script>
<style lang="scss" scoped>
@import '../../scss/_variables.scss';
</style>
