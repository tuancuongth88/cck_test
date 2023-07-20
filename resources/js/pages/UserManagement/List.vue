<template>
  <div class="display-user-management-list">
    <!-- Button Sign-up and Select delete -->
    <div class="zone-control">
      <b-row>
        <b-col>
          <b-button class="btn-add" @click="createNewUser()">
            <i class="fas fa-plus" /> {{ $t('SIGN_UP') }}
          </b-button>

          <b-button class="btn-delete" @click="showDelete()">
            <i class="fas fa-trash-alt" /> {{ $t('SELECT_DELETE') }}
          </b-button>
        </b-col>
      </b-row>
    </div>

    <!-- Input Search -->
    <div class="zone-input-search">
      <b-row cols="2" cols-sm="2" cols-md="2" cols-lg="2" cols-xl="2">
        <b-col sm="8">
          <label for="search-user">{{ $t('SEARCH') }}</label>
        </b-col>

        <b-col sm="4">
          <b-form-input id="search-user" v-model="queryData.search" class="searchUser" @keyup="fillterListUser($event)" />
        </b-col>
      </b-row>
    </div>

    <div v-if="closeMess" class="showMess">
      <div v-if="message.isShowMessage" id="error_explanation">
        <div class="error_label d-flex w-70 justify-content-between">
          <b-icon icon="x-circle" class="close-tab" @click="ConfirmClose()" />
        </div>

        <div class="error_content">
          <span>
            {{ $t('isMessage') }}
          </span>
        </div>
      </div>
    </div>

    <!-- Table User List -->
    <div class="display-table">
      <b-table
        id="user-management-list"
        :key="reRender"
        class="text-center bg-dx-grey-blur mb-0"
        :fields="fields"
        :items="listUser"
        :current-page="queryData.page"
        show-empty
        :no-local-sorting="noSort"
        @sort-changed="sortingChanged"
      >
        <template #cell(choose)="choose">
          <b-form-checkbox
            class="checkAll"
            name="checkbox-1"
            value="accepted"
            :disabled="checkbox"
            unchecked-value="not_accepted"
            @change="selectItem(choose.item.id)"
          />
        </template>

        <template #cell(display_name)="data">
          {{ $t(data.item.display_name) }}
        </template>

        <template #cell(result)="result">
          <b-button class="btn btn-detail fs-14" @click="goToDetailScreen(result.item.id)">
            <i class="far fa-eye" /> {{ $t('DISPLAY') }}
          </b-button>

          <b-button class="btn btn-edit fs-14" @click="goToEditScreen(result.item.id)">
            <i class="fas fa-edit" /> {{ $t('EDIT') }}
          </b-button>

          <b-button class="btn btn-delete btn-delete-render fs-14" @click="confirmationForm(result.item.id, result.item.name)">
            <i class="fas fa-trash-alt" />  {{ $t('DELETE') }}
          </b-button>
        </template>

        <template #empty="">
          <span>{{ $t('TABLE_EMPTY') }}</span>
        </template>
      </b-table>
    </div>

    <!-- Pagianation -->
    <div class="pagianation d-flex justify-content-center">
      <b-pagination
        v-model="queryData.page"
        :per-page="queryData.per_page"
        :total-rows="queryData.total_records"
        pills
        first-number
        last-number
        aria-controls="user-management-list"
        page-class="pagination-page-class"
        next-class="pagination-next-class"
        prev-class="pagination-prev-class"
      >
        <template #next-text>
          <i class="fas fa-angle-right" />
        </template>

        <template #prev-text>
          <i class="fas fa-angle-left" />
        </template>
      </b-pagination>
    </div>

    <!-- Modal Confirm -->
    <b-modal v-model="showModal" header-class="modal-custom-header" content-class="modal-custom-body" footer-class="modal-custom-footer">
      <template #modal-header>
        <span>{{ $t('MODAL_CONFIRM_DELETE') }}</span>
      </template>

      <template #default>
        <div v-for="(user, index) in listUser" :key="index">
          <span v-if="listId.includes(user.id)"> {{ $t('MODAL_MESSAGE_CONFIRM_DELETE', { name: user.name }) }} </span>
        </div>
      </template>

      <template #modal-footer>
        <b-button class="modal-btn-cancel" @click="showModal = !showModal">{{ $t('MODAL_BUTTON_CANCEL') }}</b-button>

        <b-button class="modal-btn-delete" @click="DeleteAll()">{{ $t('MODAL_BUTTON_DELETE') }}</b-button>
      </template>
    </b-modal>
  </div>
</template>

<script>
// import { getAllUserManagement, deleteUserManagement, deleteAllUserManagement } from '@/api/modules/userManagement';
// import { MakeToast } from '../../utils/toastMessage';
// import { obj2Path } from '@/utils/obj2Path';

// const urlAPI = {
//   urlGetLisUser: '/user',
//   urlDeleAll: 'user/destroyMany',
// };
export default {
  name: 'UserManagementList',
  data() {
    return {
      noSort: true,
      checkbox: false,
      listId: [],
      closeMess: true,
      showModal: false,

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

      fields: [
        { key: 'choose', sortable: false, label: '', class: 'choose' },
        { key: 'name', sortable: true, label: this.$t('USER_MANAGEMENT_NAME'), class: 'name' },
        { key: 'email', sortable: true, label: this.$t('USER_MANAGEMENT_MAIL'), class: 'email' },
        { key: 'display_name', sortable: true, label: this.$t('USER_MANAGEMENT_AUTH'), class: 'role' },
        { key: 'result', sortable: false, label: '', class: 'result' },
      ],

      reRender: 0,
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
  },

  created() {
    this.getListAllData();
  },

  methods: {
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

    fillterListUser($event){
      this.overlay.show = true;
      this.getListAllData($event);
    },

    selectItem(id){
      if (this.listId.includes(id) && this.listId.length > 0){
        this.listId.splice(this.listId.indexOf(id), 1);
      } else {
        this.listId.push(id);
      }
    },

    showDelete(){
      if (this.listId.length > 0){
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

    goToDetailScreen(id) {
      this.$router.push({ path: `/usermanagement/detail/${id}` }, (onAbort) => {});
    },

    goToEditScreen(id) {
      this.$router.push({ path: `/usermanagement/edit/${id}` }, (onAbort) => {});
    },

    async confirmationForm(id, name) {
      this.$bvModal.msgBoxConfirm((this.$t('MODAL_MESSAGE_CONFIRM_DELETE', { name: name })), {
        title: this.$t('MODAL_CONFIRM_DELETE'),
        okVariant: 'danger',
        okTitle: this.$t('MODAL_BUTTON_DELETE'),
        cancelVariant: 'secondary ',
        cancelTitle: this.$t('MODAL_BUTTON_CANCEL'),
        centered: true,
        size: 'lg',
      })
        .then(value => {
          this.confirmStatus = value;
          if (this.confirmStatus === true) {
            // deleteUserManagement(`${urlAPI.urlGetLisUser}/${id}`)
            //   .then(() => {
            //     MakeToast({
            //       variant: 'success',
            //       title: this.$t('SUCCESS'),
            //       content: this.$t('CONTENT_SUSS'),
            //     });

            //     this.reRender++;
            //     this.getListAllData();
            //   });
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
      this.queryData.order_column = ctx.sortBy === 'role[0].name' ? 'role[0].name' : ctx.sortBy;
      this.queryData.order_column = ctx.sortBy === 'name' ? 'name' : ctx.sortBy;
      this.queryData.order_column = ctx.sortBy === 'email' ? 'email' : ctx.sortBy;
      this.queryData.order_type = (ctx.sortDesc === true) ? 'ASC' : 'DESC';
      this.getListAllData();
    },

    async DeleteAll(){
      // await deleteAllUserManagement(urlAPI.urlDeleAll, { id: this.listId })
      //   .then(() => {
      //     MakeToast({
      //       variant: 'success',
      //       title: this.$t('SUCCESS'),
      //       content: this.$t('CONTENT_SUSS'),
      //     });
      //     this.listId.length = 0;
      //     this.reRender++;
      //     this.showModal = false;
      //     this.getListAllData();
      //   });
    },

  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/UserManagement/UserList.scss';
</style>
