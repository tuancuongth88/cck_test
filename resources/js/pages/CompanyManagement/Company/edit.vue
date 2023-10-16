<template>
  <div class="display-user-management-list">
    <b-row class="mb-2">
      <b-col cols="3" />
      <b-col cols="9" class="d-flex justify-content-between align-items-center">
        <b-col class="border-left-title font-weight-bold">{{
          $t('TITLE.COMPANY_DETAIL')
        }}</b-col>
        <div>
          <b-button variant="outline-dark mx-1">{{
            $t('BUTTON.CANCEL')
          }}</b-button>
          <b-button
            variant="warning"
            class="text-white mx-1"
            @click="onSubmit($event)"
          >{{ $t('BUTTON.SAVE') }}</b-button>
        </div>
      </b-col>
    </b-row>
    <b-row class="mb-4">
      <b-col cols="3">
        <b-card class="text-center p-4">
          <b-card-text class="font-weight-bold">
            シティコンピュータ株式会社
          </b-card-text>
          <b-card-text
            class="font-weight-bold"
          >ｼﾃｨｺﾝﾋﾟｭｰﾀｶﾌﾞｼｷｶﾞｲｼｬ.</b-card-text>
          <b-card-text>（ID : 000123）.</b-card-text>
          <b-form-select
            v-model="statusOption[0].text"
            :options="statusOption"
            class="mb-3"
            disabled-field="notEnabled"
          />
          <!-- <b-button variant="outline-secondary" class="mt-5">{{ $t('STATUS.EXAMINATION_PENDING') }}</b-button> -->
        </b-card>
      </b-col>
      <b-col cols="9">
        <b-tabs v-model="tabIndex" fill>
          <b-form @submit="onSubmit($event)" @reset="onReset($event)">
            <b-tab
              :title="$t('TAB.BASIC_INFORMATION')"
              :title-link-class="linkClass(0)"
            >
              <CompanyBasicInfo
                :form-data="formData"
                :error="error"
                @handle-change-form="handleChangeForm"
                @reset-select="resetSelect"
              />
            </b-tab>
            <b-tab
              :title="$t('TAB.DETAIL_INFORMATION')"
              :title-link-class="linkClass(1)"
            >
              <CompanyDetailInfo :form-data="formData" />
            </b-tab>
          </b-form>
        </b-tabs>
      </b-col>
    </b-row>
  </div>
</template>

<script>
// import {
//   getAllUserManagement,
//   deleteUserManagement,
//   deleteAllUserManagement,
// } from '@/api/modules/userManagement';
// import { MakeToast } from '../../utils/toastMessage';
// import { obj2Path } from '@/utils/obj2Path';
import CompanyBasicInfo from './CompanyBasicInfo';
import CompanyDetailInfo from './CompanyDetailInfo';

// const urlAPI = {
//   urlGetLisUser: '/user',
//   urlDeleAll: 'user/ ',
// };
export default {
  name: 'CompanyEdit',
  components: { CompanyBasicInfo, CompanyDetailInfo },
  data() {
    return {
      tabIndex: 0,
      statusOption: [
        {
          value: '審査待ち ',
          text: '審査待ち ',
        },
        {
          value: '承認 ',
          text: '承認 ',
        },
        {
          value: '却下 ',
          text: '却下 ',
        },
        {
          value: '利用停止 ',
          text: '利用停止 ',
        },
      ],
      noSort: true,
      checkbox: false,
      listId: [],
      closeMess: true,
      showModal: false,
      selectAll: false,
      message: {
        isShowMessage: false,
        isMessage: '',
      },

      overlay: {
        show: false,
        variant: 'light',
        opacity: 1,
        blur: '1rem',
        rounded: 'sm',
      },

      queryData: {
        page: 1,
        per_page: 20,
        total_records: 0,
        search: '',
        order_type: '',
        order_column: '',
      },

      reRender: 0,
      fields: [
        { key: 'choose', sortable: false, label: '', class: 'choose' },
        { key: 'id', sortable: true, label: 'ID', class: 'id' },
        { key: 'hr_name', sortable: true, label: 'HR NAME', class: 'hr_name' },
        {
          key: 'company_name',
          sortable: true,
          label: 'COMPANY NAME',
          class: 'company_name',
        },
        { key: 'status', sortable: false, label: 'STATUS', class: 'status' },
        {
          key: 'updating_date',
          sortable: false,
          label: 'UPDATE DATE',
          class: 'updating_date',
        },
        { key: 'detail', sortable: false, label: 'DETAIL', class: 'detail' },
      ],
      items: [
        {
          id: '0000001',
          hr_name: '電気設計エンジニア',
          company_name: '電気設計エンジニア',
          updating_date: '20230217',
          status: '募集中',
          selected: false,
          detail: '募集中',
        },
        {
          id: '0000002',
          hr_name: '電気設計エンジニア',
          company_name: '電気設計エンジニア',
          updating_date: '20230217',
          status: '一時停止中',
          selected: false,
          detail: '一時停止中',
        },
        {
          id: '0000003',
          hr_name: '電気設計エンジニア',
          company_name: '電気設計エンジニア',
          updating_date: '20230217',
          status: '募集期間外',
          selected: false,
          detail: '募集期間外',
        },
        {
          id: '0000004',
          hr_name: '電気設計エンジニア',
          company_name: '電気設計エンジニア',
          updating_date: '20230217',
          status: '募集期間外',
          selected: false,
          detail: '募集期間外',
        },
        {
          id: '0000005',
          hr_name: '電気設計エンジニア',
          company_name: '電気設計エンジニア',
          updating_date: '20230217',
          status: '募集期間外',
          selected: false,
          detail: '募集期間外',
        },
        {
          id: '0000006',
          hr_name: '電気設計エンジニア',
          company_name: '電気設計エンジニア',
          updating_date: '20230217',
          status: '募集期間外',
          selected: false,
          detail: '募集期間外',
        },
        {
          id: '0000007',
          hr_name: '電気設計エンジニア',
          company_name: '電気設計エンジニア',
          updating_date: '20230217',
          status: '募集期間外',
          selected: false,
          detail: '募集期間外',
        },
      ],

      formData: {
        companyBasicInfo: {
          corporation_name: '',
          company_name_furigana: '',
          major_classification: '',
          middle_classification: '',
          post_code: '',
          prefectures: '',
          municipality: '',
          number: '',
          other: '',
          telephone: '',
          mail_address: '',
          url: '',
          job_title: '',
          full_name: '',
          full_name_furigana: '',
          representative_contact: '',
          assignee_contact: '',
        },
        companyDetailInfo: {
          establishment_year: '',
          startup_year: '',
          capital: '',
          proceeds: '',
          number_of_staffs: '',
          number_of_operations: '',
          number_of_shops: '',
          number_of_factories: '',
          fiscal_year: '',
        },
      },
      error: {
        companyBasicInfo: {
          corporation_name: '',
          company_name_furigana: '',
          major_classification: '',
          middle_classification: '',
          post_code: '',
          prefectures: '',
          municipality: '',
          number: '',
          other: '',
          telephone: '',
          mail_address: '',
          url: '',
          job_title: '',
          full_name: '',
          full_name_furigana: '',
          representative_contact: '',
          assignee_contact: '',
        },
        companyDetailInfo: {},
      },
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
    currChange() {
      this.getListAllData();
    },

    items: {
      handler(newVal, oldVal) {
        // Update selectAll based on items selected status
        const allSelected = newVal.every((item) => item.selected);
        if (allSelected !== this.selectAll) {
          this.selectAll = allSelected;
        }
      },
      deep: true,
    },
  },

  created() {
    this.getListAllData();
  },

  methods: {
    resetSelect() {
      this.formData.companyBasicInfo.middle_classification = null;
    },
    linkClass(idx) {
      if (this.tabIndex === idx) {
        return ['bg-primary', 'text-light'];
      } else {
        return ['bg-light', 'text-info'];
      }
    },

    selectAllChanged() {
      // Update all items to match selectAll
      this.items.forEach((item) => {
        item.selected = this.selectAll;
      });
    },
    async getListAllData(e) {
      // const Search = {
      //   search: this.queryData.search,
      //   order_column: this.queryData.order_column,
      //   order_type: this.queryData.order_type,
      //   page: this.queryData.page,
      //   per_page: this.queryData.per_page,
      // };
      // await getAllUserManagement(`${urlAPI.urlGetLisUser}?${obj2Path(Search)}`)
      //   .then((res) => {
      //     if (res.data.data) {
      //       const listUser = res.data.data;
      //       this.queryData.total_records = res.data.total;
      //       this.$store.dispatch('userManagement/saveListUser', listUser);
      //     }
      //   })
      //   .catch((error) => {
      //     MakeToast({
      //       variant: 'warning',
      //       title: this.$t('DANGER'),
      //       content: error.message,
      //     });
      //     this.overlay.show = false;
      //   });
    },

    fillterListUser($event) {
      this.overlay.show = true;
      this.getListAllData($event);
    },

    selectItem(id) {
      if (this.listId.includes(id) && this.listId.length > 0) {
        this.listId.splice(this.listId.indexOf(id), 1);
      } else {
        this.listId.push(id);
      }
    },

    showDelete() {
      if (this.listId.length > 0) {
        this.showModal = true;
      } else {
        this.closeMess = true;
        this.message.isShowMessage = true;
        this.checkbox = true;
        this.getListAllData();
      }
    },
    createNewUser() {
      this.$router.push('/usermanagement/create');
    },

    goToDetail(id, status) {
      this.$router.push({ path: `/hr/detail/${id}` }, (onAbort) => {});
    },

    goToEditScreen(id) {
      this.$router.push(
        { path: `/usermanagement/edit/${id}` },
        (onAbort) => {}
      );
    },

    async confirmationForm(id, name) {
      this.$bvModal
        .msgBoxConfirm(
          this.$t('MODAL_MESSAGE_CONFIRM_DELETE', { name: name }),
          {
            title: this.$t('MODAL_CONFIRM_DELETE'),
            okVariant: 'danger',
            okTitle: this.$t('MODAL_BUTTON_DELETE'),
            cancelVariant: 'secondary ',
            cancelTitle: this.$t('MODAL_BUTTON_CANCEL'),
            centered: true,
            size: 'lg',
          }
        )
        .then((value) => {
          this.confirmStatus = value;
          if (this.confirmStatus === true) {
            // deleteUserManagement(`${urlAPI.urlGetLisUser}/${id}`).then(() => {
            //   MakeToast({
            //     variant: 'success',
            //     title: this.$t('SUCCESS'),
            //     content: this.$t('CONTENT_SUSS'),
            //   });
            //   this.reRender++;
            //   this.getListAllData();
            // });
          }
          this.getListAllData();
        });
    },

    ConfirmClose() {
      this.checkbox = false;
      this.closeMess = false;
      this.getListAllData();
    },

    sortingChanged(ctx) {
      this.queryData.order_column =
        ctx.sortBy === 'role[0].name' ? 'role[0].name' : ctx.sortBy;
      this.queryData.order_column = ctx.sortBy === 'name' ? 'name' : ctx.sortBy;
      this.queryData.order_column =
        ctx.sortBy === 'email' ? 'email' : ctx.sortBy;
      this.queryData.order_type = ctx.sortDesc === true ? 'ASC' : 'DESC';
      this.getListAllData();
    },

    async DeleteAll() {
      // await deleteAllUserManagement(urlAPI.urlDeleAll, {
      //   id: this.listId,
      // }).then(() => {
      //   MakeToast({
      //     variant: 'success',
      //     title: this.$t('SUCCESS'),
      //     content: this.$t('CONTENT_SUSS'),
      //   });
      //   this.listId.length = 0;
      //   this.reRender++;
      //   this.showModal = false;
      //   this.getListAllData();
      // });
    },

    onSubmit(e) {
      e.preventDefault();
      this.checkvalidate();
      if (!this.checkvalidate()) {
        e.stopPropagation();
      }
    },

    checkvalidate() {
      if (this.formData.companyBasicInfo.corporation_name === '') {
        this.error.companyBasicInfo.corporation_name = false;
      }
      if (this.formData.companyBasicInfo.company_name_furigana === '') {
        this.error.companyBasicInfo.company_name_furigana = false;
      }
      if (this.formData.companyBasicInfo.major_classification === '') {
        this.error.companyBasicInfo.major_classification = false;
      }
      if (this.formData.companyBasicInfo.middle_classification === '') {
        this.error.companyBasicInfo.middle_classification = false;
      }
      if (this.formData.companyBasicInfo.post_code === '') {
        this.error.companyBasicInfo.post_code = false;
      }
      if (this.formData.companyBasicInfo.prefectures === '') {
        this.error.companyBasicInfo.prefectures = false;
      }
      if (this.formData.companyBasicInfo.municipality === '') {
        this.error.companyBasicInfo.municipality = false;
      }
      if (this.formData.companyBasicInfo.number === '') {
        this.error.companyBasicInfo.number = false;
      }
      if (this.formData.companyBasicInfo.other === '') {
        this.error.companyBasicInfo.other = false;
      }
      if (this.formData.companyBasicInfo.telephone === '') {
        this.error.companyBasicInfo.telephone = false;
      }
      if (this.formData.companyBasicInfo.mail_address === '') {
        this.error.companyBasicInfo.mail_address = false;
      }
      if (this.formData.companyBasicInfo.url === '') {
        this.error.companyBasicInfo.url = false;
      }
      if (this.formData.companyBasicInfo.job_title === '') {
        this.error.companyBasicInfo.job_title = false;
      }
      if (this.formData.companyBasicInfo.full_name === '') {
        this.error.companyBasicInfo.full_name = false;
      }
      if (this.formData.companyBasicInfo.full_name_furigana === '') {
        this.error.companyBasicInfo.full_name_furigana = false;
      }
      if (this.formData.companyBasicInfo.representative_contact === '') {
        this.error.companyBasicInfo.representative_contact = false;
      }
      if (this.formData.companyBasicInfo.assignee_contact === '') {
        this.error.companyBasicInfo.assignee_contact = false;
      }
    },

    handleChangeForm(event, field) {
      const newValue = event;
      switch (field) {
        case 'corporation_name':
          this.error.companyBasicInfo.corporation_name = null;
          if (newValue) {
            this.error.companyBasicInfo.corporation_name = true;
          } else {
            this.error.companyBasicInfo.corporation_name = false;
          }
          break;

        case 'company_name_furigana':
          this.error.companyBasicInfo.company_name_furigana = null;
          if (newValue) {
            this.error.companyBasicInfo.company_name_furigana = true;
          } else {
            this.error.companyBasicInfo.company_name_furigana = false;
          }
          break;

        case 'major_classification':
          this.error.companyBasicInfo.major_classification = null;
          if (newValue) {
            this.error.companyBasicInfo.major_classification = true;
          } else {
            this.error.companyBasicInfo.major_classification = false;
          }
          break;

        case 'middle_classification':
          this.error.companyBasicInfo.middle_classification = null;
          if (newValue) {
            this.error.companyBasicInfo.middle_classification = true;
          } else {
            this.error.companyBasicInfo.middle_classification = false;
          }
          break;

        case 'post_code':
          this.error.companyBasicInfo.post_code = null;
          if (newValue) {
            this.error.companyBasicInfo.post_code = true;
          } else {
            this.error.companyBasicInfo.post_code = false;
          }
          break;

        case 'prefectures':
          this.error.companyBasicInfo.prefectures = null;
          if (newValue) {
            this.error.companyBasicInfo.prefectures = true;
          } else {
            this.error.companyBasicInfo.prefectures = false;
          }
          break;

        case 'municipality':
          this.error.companyBasicInfo.municipality = null;
          if (newValue) {
            this.error.companyBasicInfo.municipality = true;
          } else {
            this.error.companyBasicInfo.municipality = false;
          }
          break;

        case 'number':
          this.error.companyBasicInfo.number = null;
          if (newValue) {
            this.error.companyBasicInfo.number = true;
          } else {
            this.error.companyBasicInfo.number = false;
          }
          break;

        case 'other':
          this.error.companyBasicInfo.other = null;
          if (newValue) {
            this.error.companyBasicInfo.other = true;
          } else {
            this.error.companyBasicInfo.other = false;
          }
          break;

        case 'telephone':
          this.error.companyBasicInfo.telephone = null;
          if (newValue) {
            this.error.companyBasicInfo.telephone = true;
          } else {
            this.error.companyBasicInfo.telephone = false;
          }
          break;

        case 'mail_address':
          this.error.companyBasicInfo.mail_address = null;
          if (newValue) {
            this.error.companyBasicInfo.mail_address = true;
          } else {
            this.error.companyBasicInfo.mail_address = false;
          }
          break;

        case 'url':
          this.error.companyBasicInfo.url = null;
          if (newValue) {
            this.error.companyBasicInfo.url = true;
          } else {
            this.error.companyBasicInfo.url = false;
          }
          break;

        case 'job_title':
          this.error.companyBasicInfo.job_title = null;
          if (newValue) {
            this.error.companyBasicInfo.job_title = true;
          } else {
            this.error.companyBasicInfo.job_title = false;
          }
          break;

        case 'full_name':
          this.error.companyBasicInfo.full_name = null;
          if (newValue) {
            this.error.companyBasicInfo.full_name = true;
          } else {
            this.error.companyBasicInfo.full_name = false;
          }
          break;

        case 'full_name_furigana':
          this.error.companyBasicInfo.full_name_furigana = null;
          if (newValue) {
            this.error.companyBasicInfo.full_name_furigana = true;
          } else {
            this.error.companyBasicInfo.full_name_furigana = false;
          }
          break;

        case 'representative_contact':
          this.error.companyBasicInfo.representative_contact = null;
          if (newValue) {
            this.error.companyBasicInfo.representative_contact = true;
          } else {
            this.error.companyBasicInfo.representative_contact = false;
          }
          break;

        case 'assignee_contact':
          this.error.companyBasicInfo.assignee_contact = null;
          if (newValue) {
            this.error.companyBasicInfo.assignee_contact = true;
          } else {
            this.error.companyBasicInfo.assignee_contact = false;
          }
          break;

        default:
          break;
      }
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';

.border-left-title {
  border-left: 4px solid #314cad;
  height: 36px;
  line-height: 36px;
}

.card-body {
  padding: 0;
}
</style>
