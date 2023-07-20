<template>
  <div class="distribute_msg_create">
    <div class="distribute-msg-btn-top">
      <b-button variant="secondary" @click="goToBackHomeMsgs">{{
        $t('BUTTON.BACK_TO_LIST')
      }}</b-button>
    </div>

    <div class="distribute-msg-frame">
      <div class="d-flex justify-content-between">
        <div>
          <h6 class="font-weight-bold">海外マッチングシステム(仮)運営事務局</h6>
          <h6 class="font-weight-bold">
            オンライン面接 URL発行/Online interview URL issue
          </h6>
        </div>
        <span>2023/02/10</span>
      </div>

      <div class="distribute_msg-line" />
      <div class="distribute-msg-frame__inputs-datas">
        <h6 class="my-4">シティコンピュータ株式会社 &nbsp;&nbsp;様</h6>
        <div>こんにちは、海外人材マッチングシステム(仮)運営事務局です。</div>
        <div>
          オンライン面接のURLが発行されました。
          <b-link>こちら</b-link>より詳細を確認してください。
        </div>
      </div>
      <!-- エントリー求人情報 / Entry job information -->
      <div class="distribute-msg-frame__inputs-datas px-5">
        <h5 class="mt-4 font-weight-medium">エントリーID</h5>
        <span class="ml-3">23</span>
      </div>
      <div class="distribute-msg-frame__inputs-datas px-5">
        <h5 class="mt-4 font-weight-medium">
          エントリー求人情報 Entry job information
        </h5>
        <b-table bordered :items="items" :fields="fields" />
      </div>
      <div class="distribute-msg-frame__inputs-datas px-5">
        <h5 class="mt-4 font-weight-medium">
          エントリー人材承認可否/Confirmation of HR entry
        </h5>
        <b-table bordered :items="items2" :fields="fields2" />
      </div>
      <div class="distribute-msg-frame__inputs-datas px-5">
        <h5 class="mt-4 font-weight-medium">面接形式 /interview format</h5>
        <span class="ml-3">個別面接 /private interview</span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'InterviewSchedule',
  components: {},

  data() {
    return {
      characters_title: '',
      characters_content: '',
      fields: [
        {
          key: 'job_name',
          label: '求人名',
          thStyle: {
            width: '50%',
            textAlign: 'center',
          },
        },
        {
          key: 'company_name',
          label: '企業名',
          thStyle: {
            width: '50%',
            textAlign: 'center',
          },
        },
      ],
      items: [
        {
          job_name: '電気設計/制御設計',
          company_name: 'シティコンピュータ株式会社',
        },
      ],
      fields2: [
        {
          key: 'full_name',
          label: '企業名',
          thStyle: {
            width: '50%',
            textAlign: 'center',
          },
        },
        {
          key: 'interview_date',
          label: '面接日',
          thStyle: {
            width: '50%',
            textAlign: 'center',
          },
        },
      ],
      items2: [
        {
          full_name: 'Nguyễn Chí Thiện　ｸﾞｴﾝ ﾁ- ﾃｨｴﾝ',
          interview_date: '2023年3月2日（木）午後2時〜3時',
        },
        {
          full_name: 'Dương Thu Hương　ｽﾞｵﾝ ﾄｩ ﾌｵﾝ',
          interview_date: '2023年3月1日（水）午後3時〜4時',
        },
        {
          full_name: 'Nông Đức Mạn　ﾉﾝ ｽﾞｯｸ ﾏﾝ',
          interview_date: '2023年3月3日（金）午後3時〜4時',
        },
      ],
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

.distribute-msg-frame {
  padding: 4rem 8rem;
}
</style>
