<template>
  <b-modal
    id="id-modal-common"
    :ref="refs"
    :size="size"
    hide-footer
    static
    no-fade
    centered
    :modal-key="refs"
    @hidden="closeModal"
  >
    <template #modal-header>
      <button type="button" class="close" @click="closeModal">
        <span aria-hidden="true">&times;</span>
      </button>
    </template>
    <div class="content-modal">
      <slot name="body" />
    </div>
  </b-modal>
</template>

<script>
import EventBus from '@/utils/eventBus';

export default {
  name: 'ModalCommon',
  components: {},

  props: {
    refs: {
      type: String,
      default: '',
      required: true,
    },
    // show: {
    //   type: Boolean,
    //   default: false,
    //   required: true,
    // },
    size: {
      type: String,
      default: '',
      required: true,
    },
  },

  data() {
    return {};
  },

  computed: {},

  created() {
    EventBus.$on('open-modal', (param) => {
      if (Object.keys(this.$refs)[0] === param) {
        if (!this.$refs[param]) {
          return 0;
        } else {
          this.$refs[param].show();
        }
      }
    });

    EventBus.$on('close-modal', (param) => {
      // this.$refs[this.refs].hide();
      // this.$emit('reset-modal');
      if (Object.keys(this.$refs)[0] === param) {
        if (!this.$refs[param]) {
          return 0;
        } else {
          this.$refs[param].hide();
          this.$emit('reset-modal');
        }
      }
    });
  },

  methods: {
    closeModal() {
      this.$refs[this.refs].hide();
      this.$emit('reset-modal');
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/common/common.scss';
</style>
