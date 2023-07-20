<!-- DistributeMsgCreate -->
<!-- /distribute-msg-create -->
<template>
  <div class="distribute_msg_create">
    <div class="distribute-msg-btn-top">
      <b-button variant="secondary" @click="goToBackHomeMsgs">{{
        $t('BUTTON_RETURN')
      }}</b-button>
    </div>
    <div class="distribute-msg-frame">
      <div class="distribute-msg-frame__title-input">
        <div class="title-input-title" style="justify-content: space-between">
          <span />
          <span>2023/02/06</span>
        </div>
        <div class="title-input-input title-msg-detail">
          <p>海外人材マッチングシステム(仮)システム管理</p>
          <p>システム停止のお知らせ</p>
        </div>
      </div>

      <div class="distribute_msg-line" />
      <div class="distribute-msg-frame__inputs-datas">
        <div class="">
          <b-img
            fluid
            src="https://picsum.photos/250/250/?image=54"
            alt="Image 1"
          />
        </div>
      </div>
      <div class="distribute-msg-content">
        <p>
          システムアップデートのため2023年5月
          1日0:00〜5:00までシステムを停止いたします。
          ご利用者の方々にはご迷惑をお掛けいたしますが、ご理解のほどよろしくお願い致します。
        </p>
      </div>
    </div>
  </div>
</template>

<script>
// import { MakeToast } from '@/utils/toastMessage';

export default {
  name: 'Detail',
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
    format50characters(e) {
      return String(e).substring(0, 50);
    },

    format1000characters(e) {
      return String(e).substring(0, 1000);
    },

    goToBackHomeMsgs() {
      //   console.log('goToBackHomeMsgs');
      //   if (
      //     this.characters_title.length > 0 ||
      //     this.characters_content.length > 0
      //   ) {
      //     MakeToast({
      //       variant: 'danger',
      //       title: this.$t('DANGER'),
      //       content: this.$t('SKIP_CONTENT'),
      //     });
      //   } else {
      //     this.$router.push('/');
      //   }
      this.$router.push('/home');
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
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/common/common.scss';
@import './DistributeMsg/DistributeMsg.scss';

.distribute-msg-frame {
  background: #ffffff;
  border: 1px solid #999;
  margin-top: 1rem;
  padding: 2rem 6rem 4rem;
  display: flex;
  flex-direction: column;
}

.distribute-msg-frame__inputs-datas {
  margin: 32px 0 62px;
}
</style>
