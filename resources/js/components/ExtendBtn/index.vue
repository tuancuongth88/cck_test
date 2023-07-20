<template>
  <div class="btn entry-hr-select-btn">
    <div>
      <span style="font-weight: bold;">+</span>
      <span style="font-weight: bold;">人材を選ぶ</span>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ExtendBtn',
  components: {},
  props: {
    text: {
      type: String,
      default: '',
    },
    maxWidth: {
      type: String,
      default: '',
    },
  },
  data() {
    return {};
  },
  computed: {},
  created() {},
  methods: {},
};
</script>

<style lang="scss" scoped>

@import '@/scss/_variables.scss';
@import '@/scss/modules/common/common.scss';

.entry-hr-select-btn {
  height: 34px;
  display: flex;
  padding: 0 1.25rem;
  border-radius: 5px;
  align-items: center;
  background: #FFFFFF;
  justify-content: center;
  border: 1px solid $btn-extend;
  box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);

  & > div {
    gap: 0.25rem;
    display: flex;
    align-items: center;
    justify-content: center;

    & > span {
      color: #304CAD;
    }

    & > span:nth-child(1) {
      font-size: 32px;
      font-weight: 100;
      line-height: 48px;
      transform: translateY(-2.5px);
    }

    & > span:nth-child(2) {
      font-size: 16px;
      font-weight: 400;
      line-height: 19px;
    }
  }
}
</style>

