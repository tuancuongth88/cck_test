<template>
  <div class="hr-list-head">

    <div class="hr-list-head-title">
      <div class="line" />
      <div>
        <span>{{	title }}</span>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'TitleList',

  props: {
    title: {
      type: String,
      default: '',
    },
  },

  computed: {
  },

  methods: {
  },

};
</script>

<style lang="scss" scoped>
			// @import "../../scss/_variables.scss";

</style>
