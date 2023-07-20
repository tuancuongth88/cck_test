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
      <!-- delivery Message -->
      <div
        id="delivery-message"
        class="btn distribute-btn"
        @click="goTodeliveryMessage"
      >
        <span>{{ $t('TO_DELIVER') }}</span>
      </div>
    </div>
    <!-- 2 Phận nhập liệu -->
    <div class="distribute-msg-frame">
      <!-- Thêm title -->
      <div class="distribute-msg-frame__title-input">
        <!-- タイトル - title -->
        <div class="title-input-title">
          <label for="edit-title" class="distribute-title">{{
            $t('DISTRIBUTE_TITLE')
          }}</label>
          <Require />
        </div>
        <div class="title-input-input">
          <b-form-input
            id="edit-title"
            type="text"
            max-lenght="50"
            :formatter="format50characters"
          />
          <span
            class="count-character"
          >{{ countCharactersContent(characters_title) }}/50</span>
        </div>
      </div>
      <!-- Thêm Text và ảnh -->
      <div class="distribute-msg-frame__inputs-datas">
        <!-- 本文 - This article -->
        <div>
          <label for="upload-msg-picture" class="distribute-title">{{
            $t('THIS_ARTICLE')
          }}</label>
          <Require />
        </div>

        <div class="input-datas-frame">
          <div class="input-data-input">
            <!-- Phẩn hiện ảnh -->
            <!-- <div class="w-100">{{ distribute_img_data }}</div> -->
            <b-img
              thumbnail
              fluid
              src="https://picsum.photos/250/250/?image=54"
              alt="Image 1"
            />
            <!-- Phẩn hiện text -->
            <textarea
              id="upload-msg-picture"
              name="upload-msg-picture"
              maxLength="1000"
            />
          </div>
          <!-- BTN picture -->
          <div class="input-data-btns">
            <div>
              <div class="btn btn-upload-picture">
                <label for="upload-img">{{ $t('EMOJI') }}</label>
                <!-- <input id="upload-img" type="file" style="display: none;"> -->
                <input
                  id="upload-img"
                  ref="distributeImg"
                  type="file"
                  style="display: none"
                  @change="handleImptorDistributeImg"
                >
              </div>

              <img
                :src="require('@/assets/images/login/picture-upload.png')"
                alt="password"
                style="width: 40px; height: 40px"
              >
            </div>

            <div class="count-character">
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
import Require from '@/components/Require/Require.vue';
import { MakeToast } from '@/utils/toastMessage';

export default {
  name: 'DistributeMsgCreate',
  components: {
    Require,
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
      console.log('goToBackHomeMsgs');
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
      //   console.log('goTodeliveryMessage >0 ');
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

    handleImportDistributeImg() {
      console.log('handleImportDistributeImg');
    },

    countCharactersContent(data_string) {
      const string = data_string;
      const count = string.length;
      console.log(count);
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
