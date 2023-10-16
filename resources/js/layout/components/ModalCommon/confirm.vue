<template>
  <b-modal
    id="id-modal-confirm"
    :ref="refs"
    :size="size"
    hide-footer
    static
    no-fade
    centered
    :modal-key="refs"
  >
    <template #modal-header>
      <button type="button" class="close" @click="closeModal">
        <span aria-hidden="true">&times;</span>
      </button>
    </template>
    <div class="content-modal">
      <slot name="body" />
      <div class="d-flex justify-content-center mt-5">
        <b-button variant="secondary" class="mx-1" @click="closeModal()">
          {{ $t('MODAL_BUTTON_CANCEL') }}
        </b-button>
        <b-button
          variant="primary"
          class="mx-1"
          dusk="btn_confirm"
          @click="handleSubmit"
        >
          {{
            type === 'entry'
              ? $t('MODAL_BUTTON_CONFIRM_ENTRY')
              : type === 'offer'
                ? $t('MODAL_BUTTON_CONFIRM_OFFER')
                : type === 'result'
                  ? $t('MODAL_BUTTON_CONFIRM_RESULT')
                  : $t('MODAL_BUTTON_CONFIRM')
          }}
        </b-button>
      </div>
    </div>
  </b-modal>
</template>

<script>
import EventBus from '@/utils/eventBus';

export default {
  name: 'ModalConfirm',
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
      default: 'sm',
      required: false,
    },

    type: {
      type: String,
      default: '',
      required: false,
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

    EventBus.$on('close-modal', () => {
      this.$refs[this.refs].hide();
      this.$emit('reset-modal');
    });
  },

  methods: {
    closeModal() {
      this.$refs[this.refs].hide();
    },
    handleSubmit() {
      this.$emit('handleSubmitModalConfirm');
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/common/common.scss';
</style>
