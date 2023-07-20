<template>
  <b-overlay
    :show="overlay.show"
    :blur="overlay.blur"
    :rounded="overlay.sm"
    :style="'min-height: 100vh; height: 100%'"
    :variant="overlay.variant"
    :opacity="overlay.opacity"
  >
    <template #overlay>
      <div class="text-center">
        <b-icon icon="arrow-clockwise" font-scale="3" animation="spin" />
        <p style="margin-top: 10px">
          {{ $t('PLEASE_WAIT') }}
        </p>
      </div>
    </template>
    <!--  -->
    <div class="detail-msg-container">
      <div class="distribute_msg_create">
        <div class="distribute-msg-btn-top">
          <b-button variant="secondary" @click="goToBackHomeMsgs">{{
            $t('BUTTON.BACK_TO_LIST')
          }}</b-button>
        </div>
        <!--  -->
        <div class="content-render-wrap">
          <div class="w-100" v-html="contentHTML" />
        </div>
      </div>

    </div>
    <b-overlay />
  </b-overlay></template>

<script>
import { MakeToast } from '@/utils/toastMessage';
import { detailNoti, updateNoti } from '@/api/user.js';

export default {
  name: 'DetailMessage',
  components: {},
  data() {
    return {
      overlay: {
        show: false,
        variant: 'light',
        opacity: 0,
        blur: '1rem',
        rounded: 'sm',
      },
      contentHTML: null,
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

  watch: {
    //     currChange() {
    //       this.getListAllData();
    //     },
    //
    //     items: {
    //       handler(newVal, oldVal) {
    //         // Update selectAll based on items selected status
    //         const allSelected = newVal.every(item => item.selected);
    //         if (allSelected !== this.selectAll) {
    //           this.selectAll = allSelected;
    //         }
    //       },
    //       deep: true,
    //     },
  },

  created() {
    // this.getListAllData();
    const id = this.$route.params.id;
    this.getDetail(id);
    this.updateNoti(id);
  },

  methods: {

    async getDetail(id) {
      // console.log('getDetail id: ', id);
      try {
        this.overlay.show = true;
        const response = await detailNoti(id);
        const { code, message } = response.data;
        const { data } = response.data.data;
        if (code === 200) {
          const itemDataParse = JSON.parse(data);
          this.contentHTML = itemDataParse.contentHTML;
          this.overlay.show = false;
        } else {
          this.overlay.show = false;
          MakeToast({
            variant: 'warning',
            title: 'WARNING',
            content: message || '',
          });
        }
      } catch (error) {
        this.overlay.show = false;
        console.log(error);
      }
    },

    async updateNoti(id) {
      try {
        this.overlay.show = true;
        const response = await updateNoti(id);
        const { code, message } = response.data;
        if (code === 200) {
          this.overlay.show = false;
        } else {
          this.overlay.show = false;
          MakeToast({
            variant: 'warning',
            title: 'WARNING',
            content: message || '',
          });
        }
      } catch (error) {
        this.overlay.show = false;
        console.log(error);
      }
    },

    goToBackHomeMsgs() {
      this.$router.push('/home');
    },

    handleSubmitDistribute() {
      console.log('handleSubmitDistribute');
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
// @import '@/scss/modules/common/common.scss';
@import './DistributeMsg/DistributeMsg.scss';

.badge-required {
	color: red;
	border: 1px solid red;
	width: 28px;
	font-size: 8px;
	height: 13px;
	display: flex;
	align-items: center;
	justify-content: center;
}

input[type='file'] {
	display: none;
}
.custom-file-upload {
	border: 1px solid #7a7a7a;
	display: inline-block;
	padding: 12px 24px;
	cursor: pointer;
	border-radius: 3px;
	color: #7a7a7a;
	font-weight: 600;
}

// ::v-deep .distribute_msg_create[data-v-7d16570d]{
// 		border: 1px solid red;
// 		background: #FFFFFF;
// 		// border: 1px solid #999;
// 		margin-top: 1rem;
// 		padding: 4rem 8rem;
// 		display: flex;
// 		flex-direction: column;
// }

.distribute_msg_create {
	// border: 1px solid red;
	width: 100%;
}
.distribute-msg-btn-top {
	width: 100%;
	padding: 0 1rem 1rem 5%;
	display: flex;
	justify-content: flex-start;
	align-items: center;
}

.detail-msg-container {
	border: 1px solid #999;
	width: 100%;
	display: flex;
	justify-content: flex-start;
	align-items: center;
}
.content-render-wrap {
	border-top: 1px solid #999;
	width: 100%;
	padding: 5% 10%;
	display: flex;
	justify-content: flex-start;
	align-items: center;
}
</style>
