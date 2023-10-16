<!-- DistributeMsgCreate -->
<!-- /distribute-msg-create -->
<template>
  <div class="distribute_msg_create">
    <!-- characters_title: {{ characters_title }} {{ characters_title.length }} -->
    <!-- characters_content: {{ characters_content }} {{ characters_content.length }} -->
    <!-- 1 BTN -->
    <div class="distribute-msg-btn-top">
      <!-- Back -->
      <div id="back" class="btn distribute-btn" @click="goToBackHomeMsgs">
        <span>{{ $t('DISTRIBUTE_RETURN') }}</span>
      </div>
    </div>
    <!-- 2 Phận nhập liệu -->
    <div class="distribute-msg-frame" style="border: 1px solid #999999">
      <!-- Thêm title -->
      <div class="distribute-msg-frame__title-input">
        <!-- Tiêu đề chi tiết tin nhắn -->
        <div class="title-input-title" style="justify-content: space-between">
          <span />
          <span>2023/02/06</span>
        </div>
        <div class="title-input-input title-msg-detail">
          <p>海外人材マッチングシステム(仮)システム管理</p>
          <p>システム停止のお知らせ</p>
        </div>
        <span
          class="count-character d-none"
        >{{ countCharactersContent(characters_title) }}/50</span>
      </div>

      <div class="distribute_msg-line" />
      <!-- Thêm Text và ảnh -->
      <div class="distribute-msg-frame__inputs-datas">
        <!-- 本文 - This article -->
        <div class="input-datas-frame">
          <div class="input-data-input distribute-msg-content-wrapper">
            <!-- Phẩn hiện ảnh -->
            <!-- <div class="w-100">{{ distribute_img_data }}</div> -->
            <div class="distribute-msg-content__img">
              <b-img
                thumbnail
                fluid
                src="https://picsum.photos/250/250/?image=54"
                alt="Image 1"
              />
            </div>
            <!-- Phẩn hiện text -->
            <!-- <textarea id="upload-msg-picture" name="upload-msg-picture" maxLength="1000" /> -->
            <div class="distribute-msg-content">
              <p>
                システムアップデートのため2023年5月1日0:00〜5:00までシステムを停止いたします。
              </p>
              <p>
                ご利用者の方々にはご迷惑をお掛けいたしますが、ご理解のほどよろしくお願い致します。
              </p>
            </div>
          </div>
          <!-- BTN picture -->
          <div class="input-data-btns d-none">
            <div>
              <div class="btn btn-upload-picture">
                <label for="upload-img">{{ $t('EMOJI') }}</label>
                <!-- <input id="upload-img" type="file" style="display: none;"> -->
                <input
                  id="upload-img"
                  ref="distributeImg"
                  type="file"
                  style="display: none"
                >
              </div>

              <img
                :src="require('@/assets/images/login/picture-upload.png')"
                alt="password"
                style="width: 40px; height: 40px"
              >
            </div>

            <div class="count-character d-none">
              {{ countCharactersContent(characters_content) }}/1000
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--  -->
  </div>
</template>

<script>
// import Require from '@/components/Require/Require.vue';
import { MakeToast } from '@/utils/toastMessage';

export default {
  name: 'DistributeMsgCreate',
  components: {
    // Require,
  },

  data() {
    return {
      characters_title: '',
      characters_content: '',
      distribute_img_data:
        'https://upload.wikimedia.org/wikipedia/commons/thumb/4/43/Red_flag.svg/1280px-Red_flag.svg.png',
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
  },

  methods: {
    format50characters(e) {
      return String(e).substring(0, 50);
    },

    format1000characters(e) {
      return String(e).substring(0, 1000);
    },

    goToBackHomeMsgs() {
      if (
        this.characters_title.length > 0 ||
        this.characters_content.length > 0
      ) {
        MakeToast({
          variant: 'danger',
          title: this.$t('DANGER'),
          content: this.$t('SKIP_CONTENT'),
        });
      } else {
        this.$router.push('/');
      }
    },

    goTodeliveryMessage() {
      // if (this.characters_title.length > 0 && this.characters_content.length > 0) {
      //   MakeToast({
      //     variant: 'success',
      //     title: this.$t('SUCCESS'),
      //     content: this.$t('DISTRIBUTE_MSG_SEND_SUCCESS'),
      //   });
      // } else if (this.characters_title.length <= 0 && this.characters_content.length <= 0) {
      //   MakeToast({
      //     variant: 'danger',
      //     title: this.$t('DANGER'),
      //     content: this.$t('DISTRIBUTE_MSG_BLANK'),
      //   });
      // }
      this.$router.push('/distribute-msg-detail');
    },

    countCharactersContent(data_string) {
      const string = data_string;
      const count = string.length;
      return count;
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/common/common.scss';
@import './DistributeMsg.scss';
</style>
