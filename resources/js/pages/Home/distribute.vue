<template>
  <div class="distribute_msg_create">
    <div class="distribute-msg-btn-top">
      <b-button variant="secondary" @click="goToBackHomeMsgs">{{
        $t('BUTTON_RETURN')
      }}</b-button>

      <b-button
        variant="warning"
        class="text-white"
        @click="handleSubmitDistribute"
      >{{ $t('BUTTON.DISTRIBUTE') }}</b-button>
    </div>

    <!-- Phần nhập liệu -->
    <div class="distribute-msg-frame">
      <div class="distribute-msg-frame__title-input">
        <!-- title -->
        <div class="title-input-title">
          <label for="edit-title">{{ $t('HOME_MANAGEMENT.TITLE') }}</label>
          <b-badge class="badge-required mx-2" variant="light">必須</b-badge>
        </div>
        <div>
          <b-form-input id="edit-title" type="text" max-lenght="50" />
          <span class="count-character">0/50</span>
        </div>
      </div>
      <!-- Thêm Text và ảnh -->
      <div class="distribute-msg-frame__inputs-datas">
        <!-- title -->
        <div class="distribute-msg-frame__title-input">
          <div class="title-input-title">
            <label for="upload-msg-picture" class="distribute-title">
              {{ $t('HOME_MANAGEMENT.TEXT') }}
            </label>
            <b-badge class="badge-required mx-2" variant="light">必須</b-badge>
          </div>
        </div>
        <!-- area -->
        <div class="distribute-msg-frame__inputs">
          <div class="input-datas-frame">
            <!-- <textarea
                id="upload-msg-picture"
                name="upload-msg-picture"
                maxLength="1000"
              /> -->
            <b-form-textarea id="textarea-large" size="lg" :rows="10" />
            <div class="input-data-btns">
              <div>
                <div>
                  <!-- <label for="file-upload" class="custom-file-upload">{{
                    $t('HOME_MANAGEMENT.PICTURE')
                  }}</label> -->

                  <!-- <input
                    id="upload-img"
                    ref="distributeImg"
                    type="file"
                    style="display: none"
                  > -->
                  <!-- <input id="file-upload" type="file"> -->
                  <label for="file-input" class="custom-file-upload">
                    <!-- <i class="fa fa-cloud-upload" /> Chọn tệp -->
                    <img
                      :src="require('@/assets/images/login/picture-upload.png')"
                      alt="password"
                      style="width: 40px; height: 40px"
                    >
                  </label>
                  <input id="file-input" type="file">
                </div>

                <!-- <img
                  :src="require('@/assets/images/login/picture-upload.png')"
                  alt="password"
                  style="width: 40px; height: 40px"
                > -->
              </div>

              <div class="count-character">0/1000</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Distribute',
  components: {},

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
    goToBackHomeMsgs() {
      this.$router.push('/home');
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
      // this.$router.push('/home/detail');
    },

    handleSubmitDistribute() {
      console.log('handleSubmitDistribute');
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/common/common.scss';
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
// .custom-file-upload {
//   border: 1px solid #7a7a7a;
//   display: inline-block;
//   padding: 12px 24px;
//   cursor: pointer;
//   border-radius: 3px;
//   color: #7a7a7a;
//   font-weight: 600;
// }

.custom-file-upload {
  display: inline-block;
  color: #fff;
  cursor: pointer;
}

input[type='file'] {
  display: none;
}
</style>
