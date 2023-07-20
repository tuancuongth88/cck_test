<template>
  <div class="display-app-main">
    <router-view :key="key" />
  </div>
</template>

<script>
export default {
  name: 'AppMain',
  computed: {
    key() {
      return this.$route.fullPath;
    },
  },
};
</script>

<style lang="scss" scoped>
    .display-app-main {
        margin-bottom: 10px;
        overflow: hidden;
    }
</style>
