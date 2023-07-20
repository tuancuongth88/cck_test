<template>
  <div class="btn btn-move-to-entry mb-3" :style="`background-color: ${bgColor}`">
    <i v-if="direction === 'previous'" class="far fa-caret-circle-left previous" />
    <span>{{ text }}</span>
    <i v-if="direction === 'next'" class="far fa-caret-circle-right next" />
  </div>
</template>

<script>
export default {
  name: 'BtnMoveNext',
  props: {
    text: {
      type: String,
      default: 'エントリーに進む',
    },
    maxWidth: {
      type: String,
      default: '',
    },
    direction: {
      type: String,
      default: '',
    },
    bgColor: {
      type: String,
      default: '#F9BE00',
    },
  },
};
</script>

<style lang="scss" scoped>

@import '@/scss/_variables.scss';
@import '@/scss/modules/common/common.scss';

.far {
  color: #FFFFFF;
  font-size: 24px;
}

.previous {
  float: left;
  left: 1.25rem;
  position: absolute;
}

.next {
  float: right;
  right: 1.25rem;
  position: absolute;
}

.btn-move-to-entry {
	width: 100%;
	height: 52px;
	padding: 1rem;
  display: flex;
	max-width: 359px;
	position: relative;
	border-radius: 50px;
	align-items: center;
	justify-content: center;

	& span {
		color: #FFFFFF;
		font-size: 20px;
		font-weight: 400;
	}
}
</style>

