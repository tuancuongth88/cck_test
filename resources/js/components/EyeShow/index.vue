<!-- EyeShow Icon -->
<template>
  <div class="eye-hide-icon">
    <!-- <div class="line-pass" /> -->
    <i
      class="btn fas fa-eye icon-detail"
      style="padding: 0"
    />
  </div>
</template>

<script>

export default {
  name: 'EyeShow',

  computed: {
  },

  methods: {
  },

};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/common/common.scss';
.eye-hide-icon {
	// border: 1px solid red;
	width: 40px;
	padding: 4px;
	position: relative;
	& > i {
		width: 100%;
	}
}
.line-pass {
	border: 0.5px solid $black;
	// border: 0.5px solid red;
	width: 20px;
	transform: rotate(45deg);
	position: absolute;
	top: 46%;
	left: 24.5%;
}
</style>
