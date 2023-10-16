<!-- Button -->
<template>
  <div
    class="btn button-common"
    :style="`
					border: ${border};
					border-radius: ${borderRadius};
					background: ${backGround};
					width: ${width};
					height: ${height};
					padding: ${padding};
					color: ${color};
					font-size: ${fontSize};
					font-weight: ${fontWeight};
					display: ${display};
					justify-content: ${justifyContent};
					align-items: ${alignItems};
					gap: ${gap};
				`"
  >
    <span>{{ text }}</span>
  </div>
</template>

<script>
// import SearchWithConditions from '@/layout/components/.../';

export default {
  name: 'Button',
  components: {},

  props: {
    text: {
      type: String,
      default: '',
    },
    border: {
      type: String,
      default: '',
    },
    borderRadius: {
      type: String,
      default: '',
    },
    backGround: {
      type: String,
      default: '',
    },
    width: {
      type: String,
      default: '',
    },
    height: {
      type: String,
      default: '',
    },
    padding: {
      type: String,
      default: '',
    },
    color: {
      type: String,
      default: 'red',
    },
    fontSize: {
      type: String,
      default: '',
    },
    fontWeight: {
      type: String,
      default: '',
    },
    display: {
      type: String,
      default: '',
    },
    justifyContent: {
      type: String,
      default: 'center',
    },
    alignItems: {
      type: String,
      default: 'center',
    },
    gap: {
      type: String,
      default: '0rem',
    },
    //
  },

  data() {
    return {

    };
  },

  computed: {},

  created() {
    // this.funciton();
  },

  methods: {

  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/Job/job.scss';
.btn {
	cursor: pointer !important;
	user-select: none !important;
	transition: all 0.3s ease !important;
	&:hover {
		opacity: 0.7 !important;
	}
	&:active {
		opacity: 1 !important;
	}
}
// .button-common {
// 	border: 1px solid $back;
// 	border-radius: 50px;
// 	background: $back;
// 	color: $white;
// 	height: 30px;
// 	padding: 0 1.75rem;
// 	font-size: 16px;
// 	font-weight: 400;
// 	display: flex;
// 	justify-content: center;
// 	align-items: center;
// }

// <Button
// 		:text="$t('BUTTON_RETURN')"
// 		:border="'1px solid #8F8F8F'"
// 		:border-radius="'50px'"
// 		:back-ground="'#8F8F8F'"
// 		:width="' '"
// 		:height="'30px'"
// 		:padding="'1.25rem'"
// 		:color="'#ffffff'"
// 		:font-size="'16px'"
// 		:font-weight="'400'"
// 		:display="'flex'"
// 		:justify-content="'center'"
// 		:align-items="'center'"
// 		:gap="'0rem'"
// />
</style>

