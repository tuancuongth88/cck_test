<template>
  <div id="app">
    <router-view />
  </div>
</template>

<script>
export default {
  name: 'App',
  created() {
    console.log('%cBlueeDirect', 'font-size: 20px; padding: 5px 10px 5px 10px; border-radius: .25rem; color: #FFFFF; background-color: #FB9A09; text-align: center;');
  },
  mounted() {
    this.$store.dispatch('permission/generateRoutes', { roles: [], permissions: [] })
      .then((routes) => {
        for (let route = 0; route < routes.length; route++) {
          this.$router.addRoute(routes[route]);
        }
      });
  },
};
</script>

<style>
body {
  overflow-x: hidden;
}
</style>

