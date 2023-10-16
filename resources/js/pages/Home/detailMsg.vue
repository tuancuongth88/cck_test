<template>
  <b-overlay
    :show="overlay.show"
    :blur="overlay.blur"
    :rounded="overlay.sm"
    :variant="overlay.variant"
    :opacity="overlay.opacity"
    :style="'min-height: 100vh; height: 100%'"
  >
    <template #overlay>
      <div class="text-center">
        <b-icon icon="arrow-clockwise" font-scale="3" animation="spin" />
        <p style="margin-top: 10px">{{ $t('PLEASE_WAIT') }}</p>
      </div>
    </template>

    <div class="distribute-msg-btn-top">
      <b-button class="btn btn_back--custom" @click="handleNavigateToHomeScreen()">
        <span>{{ $t('BUTTON.BACK_TO_LIST') }}</span>
      </b-button>
    </div>

    <div class="detail-msg-container">
      <div class="distribute_msg_create">
        <div class="content-render-wrap">
          <div class="w-100" v-html="contentHTML" />
        </div>
      </div>
    </div>
  </b-overlay>
</template>

<script>
import { MakeToast } from '@/utils/toastMessage';
import { detailNoti, updateNoti } from '@/api/user.js';

export default {
  name: 'DetailMessage',
  data() {
    return {
      overlay: {
        opacity: 1,
        show: false,
        blur: '1rem',
        rounded: 'sm',
        variant: 'light',
      },

      contentHTML: null,

      message_id: this.$store.getters.message_id,
    };
  },
  computed: {
    listUser() {
      return this.$store.getters.listUser;
    },
    currChange() {
      return this.queryData.page;
    },
  },
  mounted() {
    if (this.message_id) {
      this.handleGetComponentData();
    }
  },
  methods: {
    async handleGetComponentData() {
      this.overlay.show = true;

      await this.handleGetDetailInformation();
      await this.handleUpdateNotification();

      this.overlay.show = false;
    },
    async handleGetDetailInformation() {
      if (this.message_id !== undefined) {
        try {
          const response = await detailNoti(this.message_id);

          const { data } = response.data.data;
          const { code, message } = response.data;

          if (code === 200) {
            const itemDataParse = JSON.parse(data);
            this.contentHTML = itemDataParse.contentHTML;
          } else {
            MakeToast({
              variant: 'warning',
              title: this.$t('WARNING'),
              content: message || '',
            });
          }
        } catch (error) {
          console.log(error);

          MakeToast({
            variant: 'danger',
            title: this.$t('DANGER'),
            content: error['message'] || '',
          });
        }
      }
    },
    async handleUpdateNotification() {
      if (this.message_id) {
        this.overlay.show = true;

        try {
          const response = await updateNoti(this.message_id);
          const { code, message } = response.data;

          if (code === 200) {
            MakeToast({
              variant: 'success',
              title: this.$t('SUCCESS'),
              content: message || '',
            });
          } else {
            MakeToast({
              variant: 'warning',
              title: this.$t('WARNING'),
              content: message || '',
            });
          }
        } catch (error) {
          console.log(error);

          MakeToast({
            variant: 'danger',
            title: this.$t('DANGER'),
            content: error['message'] || '',
          });
        }
      }
    },
    handleNavigateToHomeScreen() {
      this.$router.push({ path: '/home' });
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import './DistributeMsg/DistributeMsg.scss';

.badge-required {
	color: red;
	width: 28px;
	height: 13px;
	display: flex;
	font-size: 8px;
	align-items: center;
	border: 1px solid red;
	justify-content: center;
}

input[type='file'] {
	display: none;
}

.custom-file-upload {
	color: #7a7a7a;
	cursor: pointer;
	font-weight: 600;
	padding: 12px 24px;
	border-radius: 3px;
	display: inline-block;
	border: 1px solid #7a7a7a;
}

.distribute_msg_create {
  width: 100%;
  margin-top: 0px !important;
}

.distribute-msg-btn-top {
	width: 100%;
	display: flex;
	align-items: center;
	padding: 0 1rem 1rem 0;
	justify-content: flex-start;
}

.detail-msg-container {
	width: 100%;
	display: flex;
	align-items: center;
	border: 1px solid #999;
	justify-content: flex-start;
  overflow-wrap: anywhere;
}

.content-render-wrap {
	width: 100%;
	display: flex;
	padding: 6% 8%;
	align-items: center;
	justify-content: flex-start;
}

.btn-back-to-list {
  height: 39px;
  border: none;
  display: flex;
  outline: none;
  color: #FFFFFF;
  min-width: 150px;
	align-items: center;
  border-radius: 45px;
  padding: 0 !important;
	justify-content: center;
  background-color: #8F8F8F;

  &:hover {
    opacity: .8;
    color: #FFFFFF;
    background-color: #666666;
  }

  & > span {
    font-size: 16px;
    font-weight: 400;
  }
}
</style>
