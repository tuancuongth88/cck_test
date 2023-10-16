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
      </b-navbar>
    </div>
  </div>
</template>
<script>
import Menu from './Menu.vue';
// import MenuRight from './MenuRight.vue';
export default {
  name: 'Navbar',
  components: {
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
        return ['bg-primary', 'text-light'];
      } else {
        return ['bg-light', 'text-info'];
      }
    },
  },
};
</script>
<style lang="scss" scoped>
@import '../../scss/_variables.scss';
</style>
