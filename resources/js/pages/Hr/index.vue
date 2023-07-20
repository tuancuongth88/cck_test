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
        <p style="margin-top: 10px">{{ $t('PLEASE_WAIT') }}</p>
      </div>
    </template>
    <div class="hr-list">
      <div v-if="stateCollaseHrFormSearch" class="hr-list__search">
        <HrFormSearch @handleSearch="handleSearch" />
      </div>
      <div
        class="hr-list__wrap"
        :class="{ 'pl-0 hr-list-collapse': !stateCollaseHrFormSearch }"
      >
        <div class="btn collapse-bar-btn" @click="toggleHrFormSearch">
          <div :class="{ 'rotate-180deg': stateCollaseHrFormSearch }">
            <img
              :src="require('@/assets/images/login/chervon-collase-bar.png')"
              alt="collapse"
            >
          </div>
        </div>
        <div class="hr-list-head">
          <div class="hr-list-head-title">
            <div class="line" />
            <div><span>人材一覧</span></div>
            <!--  HR List -->
          </div>

          <div class="hr-list-head-btns">
            <div>
              <div
                class="btn btn-light"
                @click="handleToggleDeleteAllItemModal"
              >
                <span>チェックしたものを一括削除</span>
                <b-icon icon="trash-fill" />
              </div>
            </div>
            <!-- Download -->
            <div class="btn btn-bold" @click="handleExportCsv">
              <span>ダウンロード</span>
            </div>
            <!-- New registration -->
            <b-dropdown
              id="dropdown-1"
              text="新規登録"
              class="btn-bold"
              style="padding: 0"
            >
              <b-dropdown-item
                @click="handleOpenRegisterForm"
              >一人追加</b-dropdown-item>
              <!-- add 1 person -->
              <b-dropdown-item
                id="show-btn"
                @click="handleToggleAddGroupModal"
              >一括追加</b-dropdown-item>
              <!-- add group -->
              <!--  ▼ <b-dropdown-divider /> -->
            </b-dropdown>
          </div>
        </div>
        <!--  -->
        <div class="hr-list-table-list-wrap">
          <div class="list-table-list__auto-flow-x">
            <div
              class="hr-list-table-list__table"
              :class="{
                'hr-list-table-list__table-collapse': !stateCollaseHrFormSearch,
              }"
            >
              <b-table
                :fields="hrFields"
                :items="hrItems"
                @sort-changed="handleSortTableHrList"
              >
                <template #empty="">
                  <span>{{ $t('TABLE_EMPTY_LIST') }}</span>
                </template>
                <!-- 0 - Check box all -->
                <template #head(selected)="">
                  <b-form-checkbox
                    v-model="satateSelectAllHrItem"
                    @change="onSelectAllCheckboxChange"
                  />
                </template>
                <!-- 1 - Check box single -->
                <template #cell(selected)="row">
                  <!-- <div class="w-100 d-flex" style="flex-direction: center; align-items: center; border: 1px solid red">
                  </div> -->
                  <b-form-checkbox
                    v-model="row.item._isSelected"
                    @change="onItemCheckboxChange(row.item)"
                  />
                </template>

                <!-- 2 - ID -->
                <template #cell(id)="id">
                  <div class="w-100 justify-start" style="align-items: center">
                    {{ id.item.id }}
                  </div>
                </template>

                <!-- <template #cell(status)="status"></template> -->
                <!-- 3 - 氏名 - full name  -->
                <template #cell(full_name)="fullname">
                  <div
                    class="w-100 justify-space-between flex-column"
                    style="align-items: flex-start"
                  >
                    <div class="w-100 justify-space-between flex-column">
                      <div class="text-overflow-ellipsis">
                        {{ fullname.item.full_name }}
                      </div>
                      <div class="text-overflow-ellipsis">
                        {{ fullname.item.full_name_ja }}
                      </div>
                    </div>
                    <!-- favorite -->
                    <img
                      v-if="fullname.item.is_favorite"
                      :src="require('@/assets/images/login/heart.png')"
                      alt="heart"
                      style="
                        height: 20px;
                        width: 20px;
                        position: absolute;
                        bottom: 0.5rem;
                        right: 0.5rem;
                      "
                    >
                  </div>
                </template>

                <!-- 4 - 団体名- organization_name -->
                <template #cell(corporate_name)="corporateName">
                  <div
                    class="w-100 justify-space-between flex-column"
                    style="align-items: flex-start"
                  >
                    <div class="w-100 justify-space-between flex-column">
                      <div class="text-overflow-ellipsis">
                        {{ corporateName.item.corporate_name_en }}
                      </div>
                      <div class="text-overflow-ellipsis">
                        {{ corporateName.item.corporate_name_ja }}
                      </div>
                    </div>
                  </div>
                </template>

                <!-- 5 - 年齢 - Age -->
                <template #cell(age)="age">
                  <div class="w-100 justify-end" style="align-items: center">
                    {{ age.item.age }}
                  </div>
                </template>

                <!-- 6 - 日本語 - Japanese Level -->
                <template #cell(japanese_level)="japanese_level">
                  <div class="w-100 justify-center" style="align-items: center">
                    N{{ japanese_level.item.japanese_level }}
                  </div>
                </template>

                <!-- 7 ステータス - status -->
                <template #cell(status)="status">
                  <div
                    class="status-item w-100 justify-center"
                    style="align-items: center"
                  >
                    <span v-if="status.item.status === 1">{{
                      $t('HR_LIST.STATUS_PUBLIC')
                    }}</span>
                    <span
                      v-if="status.item.status === 2"
                      style="color: #b1b1b1"
                    >{{ $t('HR_LIST.STATUS_PRIVATE') }}</span>
                    <span v-if="status.item.status === 3">{{
                      $t('HR_LIST.STATUS_OFFICIAL_OFFER_CONFIRM')
                    }}</span>
                  </div>
                </template>

                <!-- 公開 public | 非公開 private | 内定承諾 Official offer confirm => disabled -->
                <template #cell(detail)="detail">
                  <div class="w-100 justify-center" style="align-items: center">
                    <i
                      class="btn fas fa-eye icon-detail"
                      @click="goToHRDetail(detail)"
                    />
                  </div>
                </template>
              </b-table>
              <!-- <div class="hr-list-table-list-paggination">paggination</div> -->
            </div>
          </div>
          <b-pagination
            v-model="currentPage"
            style="padding-top: 20px"
            :total-rows="totalRows"
            :per-page="perPage"
            aria-controls="hr-list-table"
          />
        </div>
        <!--  -->
      </div>
      <!-- <ModalCommon /> -->
      <!-- Modal Delete -->
      <b-modal
        ref="my-modal"
        v-model="statusModalConfirmDelAll"
        hide-header
        hide-footer
        title=""
      >
        <div class="modal-body-content">
          <!--  -->
          <div
            class="w-100 modal-content-title-del-hr d-flex justify-center align-center"
          >
            <span>本当に削除しますか？</span>
            <!-- Do you really want to delete this? -->
          </div>
          <!-- File - Select file -->
          <!-- <div class="hr-list-import"> -->
          <!-- <div class="hr-list-import__title" for="file-CSV-multi">ファイル</div> -->
          <!--  -->
          <!-- <div class="btn  hr-list-import__btn"></div> -->
          <!--  -->
          <!-- </div> -->
          <!--  -->
          <div class="hr-list-btns">
            <div
              id="delete-all-item-hr"
              class="btn"
              @click="handleToggleDeleteAllItemModal"
            >
              <span>キャンセル</span>
            </div>
            <!-- Cancel -->
            <div
              id="import-csv"
              class="btn accept"
              @click="handleConfirmDeleteAllHRitem"
            >
              <span>削除する</span>
            </div>
            <!-- delete -->
          </div>
        </div>
      </b-modal>
      <!-- Modal Import CSV -->
      <b-modal
        ref="my-modal"
        v-model="statusModalImportMultiCSV"
        hide-header
        hide-footer
        title="Using Component Methods"
      >
        <div class="modal-body-content">
          <!--  -->
          <div class="modal-content-title">
            <span>人材情報をCSVで一括インポートする。</span>
          </div>
          <!-- File - Select file -->
          <div class="hr-list-import">
            <div class="hr-list-import__title" for="file-CSV-multi">
              ファイル
            </div>
            <!--  -->
            <div class="btn hr-list-import__btn">
              <label for="upload-img">ファイルを選択</label>
              <input
                id="upload-img"
                ref="MultiCSV"
                type="file"
                style="display: none"
                @change="handleImportMultiCSV"
              >
            </div>
            <!--  -->
          </div>
          <!--  -->
          <div class="hr-list-btns">
            <div class="btn" @click="handleToggleAddGroupModal">
              <span>キャンセル</span>
            </div>
            <div
              id="import"
              class="btn accept"
              @click="handleConfirmImportMultiCSV"
            >
              <span>取り込み</span>
            </div>
          </div>
        </div>
      </b-modal>
      <!--  -->

      <!-- Data error import -->
      <b-modal
        id="modal-error-importCSV"
        v-model="showModalErrorImportCSV"
        hide-footer
        title=""
        size="lg"
      >
        <div class="modal-body-content">
          <!--  -->
          <div v-if="!is_data_success" style="padding-bottom: 30px">
            <!-- <div
              id="import-csv"
              class="btn accept"
              @click="showModalErrorImportCSV = false"
            >
              <span>OK</span>
            </div> -->
            <div class="w-100">
              <span>インポートに失敗しました。</span>
            </div>
          </div>
          <div v-if="is_data_success" style="padding-bottom: 30px">
            <div id="import-csv" class="btn accept" @click="handleImportCSV">
              <span>インポート実行</span>
            </div>
            <div class="w-100">
              <span>{{ dateSuccessLength }}件中{{
                totalDataImport
              }}件のインポートに成功しました。</span>
            </div>
          </div>
          <b-table :fields="hrFieldsError" :items="error_data_import">
            <template #cell(message)="message">
              <span style="color: #ff0000">
                {{ message.item.message }}
              </span>
            </template>
          </b-table>
        </div>
      </b-modal>
    </div>
  </b-overlay>
</template>

<script>
import Cookies from 'js-cookie';

import HrFormSearch from '@/layout/components/search/HrFormSearch.vue';
import { getHrs, downloadHrCsv, checkFileImportHr, ImportHrCSV, hideHr } from '@/api/hr.js';
import { MakeToast } from '../../utils/toastMessage';
import { uploadFile } from '@/api/uploadFile';
// const urlAPI = {
//   urlGetLisUser: '/user',
//   urlDeleAll: 'user/ ',
// };
export default {
  name: 'HrList',
  components: {
    // SearchWithConditions,
    // ModalCommon,
    HrFormSearch,
  },

  data() {
    return {
      overlay: {
        opacity: 0,
        show: false,
        blur: '1rem',
        rounded: 'sm',
        variant: 'light',
        fixed: true,
      },
      // status_select_jobs_to_offer: status_select_jobs_to_offer,
      stateCollaseHrFormSearch: true,
      statusModalConfirmDelAll: false,
      statusModalImportMultiCSV: false,

      showModalErrorImportCSV: false,
      error_data_import: [],
      data_success: [],
      is_data_success: false,
      reRender: 0,

      currentPage: 1,
      totalRows: 0,
      perPage: 20,

      hrFields: [
        {
          key: 'selected',
          sortable: false,
          label: '',
          class: 'choose',
          thClass: 'col-1',
        },
        {
          key: 'id',
          sortable: true,
          label: 'ID',
          class: 'id',
          thClass: 'col-2',
        },
        {
          key: 'full_name',
          sortable: true,
          label: this.$t('HR_REGISTER.FULL_NAME'),
          class: 'fullName',
          thClass: 'col-3',
        },

        {
          key: 'corporate_name',
          sortable: true,
          label: this.$t('HR_REGISTER.ORGANIZATION_NAME'),
          class: 'organization_name',
          thClass: 'col-4',
        },
        {
          key: 'age',
          sortable: true,
          label: this.$t('HR_REGISTER.AGE'),
          class: 'age',
          thClass: 'col-5',
        },
        {
          key: 'japanese_level',
          sortable: true,
          label: this.$t('HR_REGISTER.JAPANESE_LEVEL'),
          class: 'japanese_level',
          thClass: 'col-6',
        },

        {
          key: 'status',
          sortable: true,
          label: this.$t('HR_REGISTER.STATUS'),
          class: 'status',
          thClass: 'col-7',
        },
        {
          key: 'detail',
          sortable: false,
          label: this.$t('HR_LIST_FORM.DETAIL'),
          class: 'detail',
          thClass: 'col-8',
        },
      ],
      hrFieldsError: [
        {
          key: 'full_name',
          label: this.$t('HR_REGISTER.FULL_NAME'),
          class: 'fullName',
          thClass: 'col-3',
        },
        {
          key: 'full_name_ja',
          label: this.$t('HR_REGISTER.FULL_NAME'),
          class: 'fullName',
          thClass: 'col-3',
        },

        {
          key: 'message',
          label: 'エラー',
          class: 'organization_name',
          thClass: 'col-4',
        },
      ],
      hrItems: [],
      selectedItems: [],
      satateSelectAllHrItem: false,

      sortHRs: {
        field: '',
        sort_by: '',
      },
      paramSearch: null,
      file_import_id: 0,
      file_import_name: '',
    };
  },

  computed: {
    dateSuccessLength() {
      return this.data_success.length;
    },
    totalDataImport() {
      const total = this.data_success.length + this.error_data_import.length;
      return total;
    },
    query() {
      return Cookies.get('searchParams');
    },
  },

  watch: {
    sortHRs: {
      handler: function() {
        this.getListHrData();
      },
      deep: true,
    },
    currentPage: {
      handler: function() {
        this.getListHrData();
      },
      deep: true,
    },
    paramSearch: {
      handler: function() {
        this.currentPage = 1;
        this.getListHrData();
      },
      deep: true,
    },
  },

  created() {
    this.getListHrData();
  },

  methods: {
    toggleHrFormSearch() {
      if (this.stateCollaseHrFormSearch) {
        this.stateCollaseHrFormSearch = false;
      } else {
        this.stateCollaseHrFormSearch = true;
      }
    },
    // Tải lại bảng - table list gp
    reloadTable() {
      this.reRender++;
    },

    onSelectAllCheckboxChange() {
      if (this.satateSelectAllHrItem) {
        this.selectedItems = [...this.hrItems];
      } else {
        this.selectedItems = [];
      }
      this.hrItems.forEach((item) => {
        item._isSelected = this.satateSelectAllHrItem;
      });
    },

    onItemCheckboxChange(item) {
      if (item._isSelected) {
        this.selectedItems.push(item);
      } else {
        const index = this.selectedItems.findIndex(
          (selectedItem) => selectedItem.id === item.id
        );
        this.selectedItems.splice(index, 1);
      }
      this.satateSelectAllHrItem =
        this.selectedItems.length === this.hrItems.length;
    },

    async handleSort(ctx) {
      // console.log('handleSort');
    },
    // this.id_hr
    goToHRDetail(detail_data) {
      const hr_detail_id = detail_data.item.id;
      this.$router.push(
        { path: `/hr/detail/${hr_detail_id}` },
        (onAbort) => {}
      );
    },

    // Register HR / CV
    handleOpenRegisterForm() {
      console.log('handleOpenRegisterForm');
      this.$router.push({ path: `/hr/create` }, (onAbort) => {});
    },
    // Import multi CSV
    handleToggleAddGroupModal() {
      console.log('handleToggleAddGroupModal 222 ', this.selectedItems);
      // this.$refs['my-modal'].show();
      if (this.statusModalImportMultiCSV === true) {
        this.statusModalImportMultiCSV = false;
      } else {
        this.statusModalImportMultiCSV = true;
      }
    },
    // Delete
    async handleConfirmDeleteAllHRitem() {
      console.log('handleDeleteAllHRitem ===== ', this.selectedItems);
      if (this.selectedItems.length === 0) {
        MakeToast({
          variant: 'warning',
          title: this.$t('WARNING'),
          content: this.$t('PLEASE_SELECT_DATA'),
        });
        return;
      }
      try {
        const array = [];
        this.selectedItems.map(item => {
          array.push(item.id);
        });
        console.log('array delete: ', array);
        const PARAM = {
          ids: array,
        };
        const res = await hideHr(PARAM);
        const { code, message } = res.data;
        if (code === 200) {
          MakeToast({
            variant: 'success',
            title: this.$t('SUCCESS'),
            content: message,
          });
          this.getListHrData();
        } else {
          MakeToast({
            variant: 'warning',
            title: this.$t('WARNING'),
            content: message,
          });
        }
      } catch (error) {
        console.log(error);
      }
      // if
      this.statusModalConfirmDelAll = false;
    },

    handleToggleDeleteAllItemModal() {
      if (!this.selectedItems.length) {
        MakeToast({
          variant: 'warning',
          title: this.$t('WARNING'),
          content: this.$t('PLEASE_SELECT_DATA'),
        });
        return;
      }
      if (this.statusModalConfirmDelAll === true) {
        this.statusModalConfirmDelAll = false;
      } else {
        this.statusModalConfirmDelAll = true;
      }
    },

    // toggleModal() {
    //   this.$refs['my-modal'].toggle('#toggle-btn');
    // },
    async handleImportMultiCSV(event) {
      const rowFileData = event.target.files[0];
      if (!rowFileData) {
        return 0;
      }
      const formDataCertificate = new FormData();
      formDataCertificate.append('file', rowFileData);
      try {
        const res = await uploadFile(formDataCertificate);
        // console.log('res ==>', res);
        const { code, data } = res.data;
        if (code === 200) {
          this.file_import_id = data.id;
          this.file_import_name = data.file_name;
        }
      } catch (error) {
        console.log(' uploadFile error ==>', error);
      }
    },

    async handleConfirmImportMultiCSV() {
      // this.handleToggleAddGroupModal();
      // return;
      if (!this.file_import_id) {
        MakeToast({
          variant: 'warning',
          title: this.$t('WARNING'),
          content: this.$t('PLEASE_CHOOSE_FILE'),
        });
        return;
      }
      try {
        const PARAM = {
          file_id: this.file_import_id,
        };
        const res = await checkFileImportHr(PARAM);
        const { code, message, data } = res.data;
        if (code === 200) {
          if (data.success.length > 0) {
            this.is_data_success = true;
            this.data_success = data.success;
          }
          if (data.error.length > 0) {
            this.error_data_import = data.error;
            this.handleShowErrorImportCSV();
          }
        } else {
          MakeToast({
            variant: 'warning',
            title: this.$t('WARNING'),
            content: message,
          });
        }
      } catch (error) {
        console.log('check file import error: ', error);
      }
      console.log('handleConfirmImportMultiCSV');
      this.handleToggleAddGroupModal();
    },

    async handleImportCSV() {
      this.showModalErrorImportCSV = false;
      if (!this.file_import_id) {
        MakeToast({
          variant: 'warning',
          title: this.$t('WARNING'),
          content: this.$t('PLEASE_CHOOSE_FILE'),
        });
        return;
      }
      try {
        const PARAM = {
          file_id: this.file_import_id,
        };
        const res = await ImportHrCSV(PARAM);
        const { code, message } = res.data;
        if (code === 200) {
          MakeToast({
            variant: 'success',
            title: this.$t('SUCCESS'),
            content: this.$t('HR_LIST.IMPORT_SUCCESS'),
          });
          this.getListHrData();
        } else {
          MakeToast({
            variant: 'warning',
            title: this.$t('WARNING'),
            content: message,
          });
        }
      } catch (error) {
        console.log('import error: ', error);
      }
    },

    handleShowErrorImportCSV() {
      this.showModalErrorImportCSV = true;
    },
    // Table
    async handleSortTableHrList(ctx) {
      switch (ctx.sortBy) {
        case 'id':
          this.sortHRs.field = 'id';
          this.sortHRs.sort_by = ctx.sortDesc ? 'desc' : 'asc';
          break;
        case 'full_name':
          this.sortHRs.field = 'full_name';
          this.sortHRs.sort_by = ctx.sortDesc ? 'desc' : 'asc';
          break;
        case 'corporate_name':
          this.sortHRs.field = 'corporate_name_en';
          this.sortHRs.sort_by = ctx.sortDesc ? 'desc' : 'asc';
          break;
        case 'age':
          this.sortHRs.field = 'date_of_birth';
          this.sortHRs.sort_by = ctx.sortDesc ? 'desc' : 'asc';
          break;
        case 'japanese_level':
          this.sortHRs.field = 'japanese_level';
          this.sortHRs.sort_by = ctx.sortDesc ? 'desc' : 'asc';
          break;
        case 'status':
          this.sortHRs.field = 'status';
          this.sortHRs.sort_by = ctx.sortDesc ? 'desc' : 'asc';
          break;

        default:
          break;
      }
    },

    async handleExportCsv() {
      try {
        const PARAM = this.paramSearch || {};
        if (this.sortHRs.field) {
          PARAM.field = this.sortHRs.field;
          PARAM.sort_by = this.sortHRs.sort_by;
        }
        const res = await downloadHrCsv(PARAM);
        let filename = res.headers['content-disposition'].split(`filename=`)[1] || 'HR_List.csv';
        console.log('file Name: ', filename);

        filename = filename.replaceAll('"', '');

        // await res.data.then((res) => {
        //   FILE_DOWNLOAD = res;
        // });
        const FILE_DOWNLOAD = res.data;
        const fileURL = window.URL.createObjectURL(FILE_DOWNLOAD);
        const fileLink = document.createElement('a');

        fileLink.href = fileURL;
        fileLink.setAttribute('download', filename);
        document.body.appendChild(fileLink);

        fileLink.click();
      } catch (error) {
        console.log(error);
      }
    },

    handleSearch(param) {
      this.paramSearch = param;
      // this.getListHrData(param);
    },

    async getListHrData() {
      this.overlay.show = true;
      this.hrItems = [];
      try {
        const PARAM = this.paramSearch || {};
        // if (this.paramSearch) {
        //   this.currentPage = 1;
        //   PARAM.page = 1;
        // } else {
        //   PARAM.page = this.currentPage;
        // }
        PARAM.page = this.currentPage;
        PARAM.per_page = this.perPage;
        if (this.sortHRs.field) {
          PARAM.field = this.sortHRs.field;
          PARAM.sort_by = this.sortHRs.sort_by;
        }
        const response = await getHrs(PARAM);
        const { code, data } = response.data;
        // console.log('response list HRs: ', response);
        if (code === 200) {
          this.hrItems = data.result.map((item) => {
            return {
              ...item,
              _isSelected: false,
            };
          });
          console.log('panigation: ', data.pagination);
          this.totalRows = data.pagination.total_records;
        } else {
          MakeToast({
            variant: 'warning',
            title: this.$t('WARNING'),
            content: response.data.message,
          });
          this.totalRows = 0;
        }
      } catch (error) {
        console.log('get list HRs error: ', error);
      }
      this.overlay.show = false;
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/common/common.scss';
@import '@/components/Modal/ModalStyle.scss';

.hr-list {
  width: 100%;
  height: 100%;
  min-height: 100vh;
  display: flex;
  justify-content: space-between;
  align-items: stretch;
}
.hr-list__search {
  // border: 1px solid;
  width: 25%;
  min-width: 272px;
  // padding-top: 5rem;
}
// .import {}
// 2 Table HR list
.hr-list__wrap {
  // border: 1px solid red;
  // flex: 1;
  width: 75%;
  padding: 0rem 0rem 1.5rem 0.75rem;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: stretch;
  gap: 0.5rem;
}
.hr-list-collapse {
  width: 100%;
}
.hr-list-head {
  // border: 1px solid red;
  display: flex;
  justify-content: space-between;
  align-items: stretch;
}
.collapse-bar-btn {
  background: #ffffff;
  border: 1px solid #b7b7b7;
  border-radius: 3px;
  padding: 0 !important;
  width: 36px;
  height: 36px;
  display: flex;
  justify-content: center;
  align-items: center;
  transition: all 0.3s ease;
  & img {
    transform: rotate(180deg);
  }
}
.rotate-180deg {
  transform: rotate(-180deg);
}
.hr-list-head-title {
  // height: 100%;
  display: flex;
  justify-content: flex-start;
  align-items: stretch;
  gap: 1rem;
  & div {
    display: flex;
    justify-content: flex-start;
    align-items: center;
    & span {
      font-weight: 700;
      font-size: 24px;
      line-height: 29px;
      color: #000000;
    }
  }
}
.line {
  border: 1px solid #304cad;
  background: #304cad;
  border-radius: 10px;
  width: 4px;
  // height: 36px;
}
.hr-list-head-btns {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 0.75rem;
}
.btn-light {
  background: #ffffff;
  border: 1px solid #1d266a;
  border-radius: 5px;
  height: 100%;
  height: 36px;
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 0.25rem;
  & span {
    font-weight: 400;
    font-size: 15px;
    line-height: 18px;
    display: flex;
    align-items: center;
    text-align: center;
    color: #1d266a;
  }
}
.btn-bold {
  background: #f9be00 !important;
  border-radius: 5px;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 0.25rem;
  & span {
    font-weight: 400;
    font-size: 14px;
    line-height: 17px;
    color: #ffffff;
  }
}

// .delete_selected_item {}
// .down-hr-list {}
// .option {}
// .hr-registration {}
// .hr-add-group {}
.hr-list-table-list-wrap {
  // border: 1px solid red;
  width: 100%;
  height: 100%;
}

.list-table-list__auto-flow-x {
  // border: 1px solid blue;
  width: 100%;
  overflow-x: auto;
}
.hr-list-table-list__table {
  // width: 100%;
  width: max-content;
  height: 100%;
  padding-top: 1.25rem;
}
.hr-list-table-list__table-collapse {
  width: 100%;
}
.text-overflow-ellipsis {
  // border: 1px solid #000000;
  // width: 50px;
  width: 100%;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.status-item {
  border: 1px solid #666666;
  border-radius: 5px;
  background: #ffffff;
  width: 100%;
  height: 29px;
  cursor: default;
}
// Ghi đè row item
::v-deep .hr-list-table-list__table {
  & .b-table {
    & tbody {
      // border: 2px solid blue;
      & tr {
        // border: 1px solid red;
        min-height: 78px;
        height: 78px;
        padding: 1.25rem 0;
        & td {
          // border: 1px solid green;
          vertical-align: middle;
          position: relative;
          // display: flex;
          // flex-direction: center;
          // align-items: center;
          & > div {
            display: flex;
            justify-content: center;
          }
        }
      }
      & tr:last-child {
        border-bottom: 1px solid #dee2e6;
      }
    }
  }
}
// Ghi đè /3x2
::v-deep .col-1 {
  width: 52px;
}
::v-deep .col-2 {
  width: 38px;
}
::v-deep .col-3 {
  width: 240px;
}
::v-deep .col-4 {
  width: 240px;
}
::v-deep .col-5,
::v-deep .col-6 {
  border: 1px solid red;
  width: 128px;
}
// status
::v-deep .col-7 {
  width: 64px;
  width: 108px;
}

::v-deep .col-8 {
  width: 60px;
}

// ::v-deep  .col8, ::v-deep  .col9 {
//     border: 1px solid green;
//     width: 172px;
//     width: 150x;
//     max-width: 212px;
// }

// .paggination {}
// Ghi đè option
::v-deep .dropdown-menu {
  left: 0 !important;
  min-width: 100% !important;
  width: 100% !important;
}
// Ghi đè button
::v-deep .btn-secondary {
  background: #f9be00 !important;
  border-color: #f9be00 !important;
}

//  Ghi đè Moddal  - import CSV
// End modal - import CSV
</style>
