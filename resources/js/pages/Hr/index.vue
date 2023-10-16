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
    <div class="hr-list" :style="overlay.show ? 'opacity: 0.5;' : ''">
      <div
        v-if="stateCollaseHrFormSearch"
        class="hr-list__search"
        dusk="hr_search"
      >
        <HrFormSearch @handleSearch="handleSearch" />
      </div>
      <div
        class="hr-list__wrap"
        :class="{ 'pl-0 hr-list-collapse': !stateCollaseHrFormSearch }"
      >
        <div class="btn collapse-bar-btn" @click="toggleHrFormSearch">
          <!-- <div :class="{ 'rotate-180deg': stateCollaseHrFormSearch }"> -->
          <div class="rotate-180deg">
            <img
              :src="require('@/assets/images/login/search-icon.png')"
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
            <div
              v-if="
                [
                  ROLE.TYPE_SUPER_ADMIN,
                  ROLE.TYPE_HR_MANAGER,
                  ROLE.TYPE_HR,
                ].includes(permissionCheck)
              "
            >
              <button
                class="btn btn_del_mul--custom"
                dusk="btn_delete_hr"
                @click="handleToggleDeleteAllItemModal"
              >
                <span>チェックしたものを一括削除</span>
                <b-icon icon="trash-fill" />
              </button>
            </div>
            <!-- Download -->
            <b-button
              v-if="
                [
                  ROLE.TYPE_SUPER_ADMIN,
                  ROLE.TYPE_HR_MANAGER,
                  ROLE.TYPE_HR,
                ].includes(permissionCheck)
              "
              class="btn btn_export--custom"
              dusk="btn_export_csv_hr"
              @click="handleExportCsv"
            >
              <span>ダウンロード</span>
            </b-button>
            <!-- New registration -->
            <b-dropdown
              v-if="
                [
                  ROLE.TYPE_SUPER_ADMIN,
                  ROLE.TYPE_HR_MANAGER,
                  ROLE.TYPE_HR,
                ].includes(permissionCheck)
              "
              id="dropdown-1"
              text="新規登録"
              class="btn-bold"
              style="padding: 0"
            >
              <b-dropdown-item
                class="btn-to-register-hr"
                dusk="btn_to_register_hr"
                @click="handleOpenRegisterForm"
              >一人追加</b-dropdown-item>
              <!-- add 1 person -->
              <b-dropdown-item
                id="show-btn"
                class="btn-import-csv-hr"
                dusk="btn_import_csv_hr"
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
                id="hr-table-list"
                dusk="hr_table_list"
                :fields="
                  [
                    ROLE.TYPE_SUPER_ADMIN,
                    ROLE.TYPE_HR_MANAGER,
                    ROLE.TYPE_HR,
                  ].includes(permissionCheck)
                    ? hrFields
                    : hrFieldsNotCheckbox
                "
                :items="hrItems"
                aria-controls="hr-list-table"
                show-empty
                pills
                hover
                no-local-sorting
                :next-class="'next'"
                :prev-class="'prev'"
                :sort-by="convertField()['field']"
                :sort-desc="convertField()['desc']"
                @sort-changed="handleSortTableHrList"
              >
                <template #empty="">
                  <div class="text-center">
                    {{ $t('TABLE_EMPTY') }}
                  </div>
                </template>
                <!-- 0 - Check box all -->
                <template
                  v-if="
                    [
                      ROLE.TYPE_SUPER_ADMIN,
                      ROLE.TYPE_HR_MANAGER,
                      ROLE.TYPE_HR,
                    ].includes(permissionCheck)
                  "
                  #head(selected)=""
                >
                  <b-form-checkbox
                    v-model="satateSelectAllHrItem"
                    @change="onSelectAllCheckboxChange"
                  />
                </template>
                <!-- 1 - Check box single -->
                <template
                  v-if="
                    [
                      ROLE.TYPE_SUPER_ADMIN,
                      ROLE.TYPE_HR_MANAGER,
                      ROLE.TYPE_HR,
                    ].includes(permissionCheck)
                  "
                  #cell(selected)="row"
                >
                  <!-- <div class="w-100 d-flex" style="flex-direction: center; align-items: center; border: 1px solid red">
                  </div> -->
                  <b-form-checkbox
                    v-model="row.item._isSelected"
                    :disabled="row.item.on_job"
                    @change="onItemCheckboxChange(row.item)"
                  />
                </template>

                <!-- 2 - ID -->
                <template #cell(id)="data">
                  <div
                    class="w-100 justify-center"
                    style="align-items: center"
                    :title="data.item.id"
                  >
                    {{ data.item.id }}
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
                      <div
                        class="text-overflow-ellipsis hr-fullname-col"
                        :title="fullname.item.full_name"
                      >
                        {{ fullname.item.full_name }}
                      </div>
                      <div
                        class="text-overflow-ellipsis hr-fullname-col"
                        :title="fullname.item.full_name_ja"
                      >
                        {{ fullname.item.full_name_ja }}
                      </div>
                    </div>
                    <!-- favorite -->
                    <img
                      v-if="fullname.item.is_favorite"
                      :src="
                        require('@/assets/images/login/favorite-removed.png')
                      "
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
                      <div
                        class="text-overflow-ellipsis hr-ogranization-col"
                        :title="corporateName.item.corporate_name_en"
                      >
                        {{ corporateName.item.corporate_name_en }}
                      </div>
                      <div
                        class="text-overflow-ellipsis hr-ogranization-col"
                        :title="corporateName.item.corporate_name_ja"
                      >
                        {{ corporateName.item.corporate_name_ja }}
                      </div>
                    </div>
                  </div>
                </template>

                <!-- 5 - 年齢 - Age -->
                <template #cell(age)="age">
                  <div
                    class="w-100 justify-center"
                    style="align-items: center"
                    :title="age.item.age"
                  >
                    {{ age.item.age }}
                  </div>
                </template>

                <!-- 6 - 日本語 - Japanese Level -->
                <template #cell(japanese_level)="japanese_level">
                  <div class="w-100 justify-center" style="align-items: center">
                    <span
                      v-if="japanese_level.item.japanese_level === 6"
                    >資格なし</span>
                    <span
                      v-else
                    >N{{ japanese_level.item.japanese_level }}</span>
                  </div>
                </template>

                <!-- 7 ステータス - status -->
                <template #cell(status)="status">
                  <!-- <div
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
                  </div> -->
                  <div
                    v-if="status.item.status === 1"
                    class="status-item w-100 justify-center"
                    style="align-items: center"
                  >
                    <span>{{ $t('HR_LIST.STATUS_PUBLIC') }}</span>
                  </div>
                  <div
                    v-if="status.item.status === 2"
                    class="status-item w-100 justify-center"
                    style="
                      align-items: center;
                      color: #b1b1b1;
                      border-color: #b1b1b1;
                    "
                  >
                    <span>{{ $t('HR_LIST.STATUS_PRIVATE') }}</span>
                  </div>
                  <div
                    v-if="status.item.status === 3"
                    class="status-item w-100 justify-center"
                    style="
                      align-items: center;
                      color: #0500ff;
                      border-color: #0500ff;
                    "
                  >
                    <span>{{
                      $t('HR_LIST.STATUS_OFFICIAL_OFFER_CONFIRM')
                    }}</span>
                  </div>
                </template>

                <!-- 公開 public | 非公開 private | 内定承諾 Official offer confirm => disabled -->
                <template #cell(detail)="detail">
                  <div class="w-100 justify-center" style="align-items: center">
                    <i
                      class="btn fas fa-eye icon-detail"
                      dusk="btn_detail_hr"
                      @click="goToHRDetail(detail)"
                    />
                  </div>
                </template>
              </b-table>
              <!-- <div class="hr-list-table-list-paggination">paggination</div> -->
            </div>
          </div>
          <div
            v-show="hrItems && hrItems.length"
            class="w-100 d-flex justify-end align-center"
          >
            <!-- <b-pagination
              v-model="currentPage"
              style="padding-top: 20px"
              :total-rows="totalRows"
              :per-page="perPage"
              aria-controls="hr-list-table"
              pills
              :next-class="'next'"
              :prev-class="'prev'"
            /> -->
            <custom-pagination
              v-if="hrItems && hrItems.length > 0"
              :total-rows="totalRows"
              :current-page="currentPage"
              :per-page="perPage"
              @pagechanged="onPageChange"
              @changeSize="changeSize"
            />
          </div>
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
            <span>{{ $t('CONFIRM_DELETE') }}</span>
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
              <span> {{ $t('BUTTON.CANCEL') }}</span>
            </div>
            <!-- Cancel -->
            <div
              id="import-csv"
              class="btn accept"
              dusk="btn_accept"
              @click="handleConfirmDeleteAllHRitem"
            >
              <span>{{ $t('BUTTON.DELETE') }}</span>
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
        <b-overlay
          :show="overlay.show"
          :blur="overlay.blur"
          :rounded="overlay.sm"
          :variant="overlay.variant"
          :opacity="overlay.opacity"
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
                <label
                  v-if="file_import_name_select"
                  for="upload-img"
                  class="import-csv-file"
                >{{ file_import_name_select }}</label>
                <label v-else for="upload-img">{{
                  $t('HR_REGISTER.SELECT_FILE')
                }}</label>
                <input
                  id="upload-img"
                  ref="MultiCSV"
                  dusk="btn_file_import_hr"
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
                <span>{{ $t('BUTTON.CANCEL') }}</span>
              </div>
              <div
                id="import"
                class="btn accept"
                dusk="import"
                @click="handleConfirmImportMultiCSV"
              >
                <span>{{ $t('INPUT') }}</span>
              </div>
            </div>
          </div>
        </b-overlay>
      </b-modal>
      <!--  -->

      <!-- Data error import -->
      <b-modal
        id="modal-error-importCSV"
        v-model="showModalErrorImportCSV"
        hide-footer
        title=""
        size="xl"
      >
        <div class="modal-body-content">
          <!--  -->
          <div v-if="!is_data_success" style="padding-bottom: 30px">
            <!-- <div
              id="import-csv"
              class="btn accept"
              @click="showModalErrorImportCSV = false"
            >
              <span>{{ $t('OK') }}</span>
            </div> -->
            <div class="w-100">
              <span>インポートに失敗しました。</span>
            </div>
          </div>
          <div v-if="is_data_success" style="padding-bottom: 30px">
            <div id="import-csv" class="btn accept" @click="handleImportCSV">
              インポート実行
            </div>
            <div class="w-100">
              ファイルを選択してください。
              <span>
                {{ dateSuccessLength }} 件中
                {{ totalDataImport }} 件のインポートに成功しました。
              </span>
            </div>
          </div>
          <b-table :fields="hrFieldsError" :items="error_data_import">
            <template #cell(full_name)="item">
              <div
                class="text-overflow-ellipsis"
                style="color: #000; width: 305px"
              >
                {{ item.item.full_name }}
              </div>
            </template>
            <template #cell(full_name_ja)="item">
              <div
                class="text-overflow-ellipsis"
                style="color: #000; width: 305px"
              >
                {{ item.item.full_name_ja }}
              </div>
            </template>
            <template #cell(message)="message">
              <div
                class="text-overflow-ellipsis"
                style="color: #ff0000; width: 421px"
              >
                {{ message.item.message }}
              </div>
            </template>
          </b-table>
        </div>
      </b-modal>
    </div>
  </b-overlay>
</template>

<script>
import HrFormSearch from '@/layout/components/search/HrFormSearch.vue';
import {
  getHrs,
  // downloadHrCsv,
  checkFileImportHr,
  ImportHrCSV,
  hideHr,
} from '@/api/hr.js';
import { MakeToast } from '../../utils/toastMessage';
import { uploadFile } from '@/api/uploadFile';
import ROLE from '@/const/role.js';
import { PAGINATION_CONSTANT } from '@/const/config.js';
import { pushParamOrQueryToRouter } from '@/utils/routerUtils';
import { LIMIT_FILE_SIZE, FILE_TYPE } from '@/const/config.js';
const FILE_CAN_UPLOAD = [FILE_TYPE.CSV];
export default {
  name: 'HrList',
  components: {
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

      ROLE: ROLE,

      stateCollaseHrFormSearch: false,
      statusModalConfirmDelAll: false,
      statusModalImportMultiCSV: false,

      showModalErrorImportCSV: false,

      error_data_import: [],

      data_success: [],

      is_data_success: false,

      reRender: 0,

      currentPage: 1,

      totalRows: 0,

      perPage: PAGINATION_CONSTANT.DEFALT_PER_PAGE,

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

      hrFieldsNotCheckbox: [
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
          // thClass: 'col-3',
          thStyle: {
            width: '338px',
          },
        },
        {
          key: 'full_name_ja',
          label: this.$t('HR_REGISTER.FULL_NAMEFURIGANA'),
          class: 'fullName',
          // thClass: 'col-3',
          thStyle: {
            width: '338px',
          },
        },

        {
          key: 'message',
          label: 'エラー',
          class: 'organization_name',
          // thClass: 'col-4',
          thStyle: {
            width: '453px',
          },
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
      file_import_name_select: '',
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
    permissionCheck() {
      return this.$store.getters.permissionCheck;
    },
  },
  watch: {},
  created() {
    // Get query list from store
    const queries = this.$store.getters.routerInfo[this.$route.name]?.queries;
    if (queries) {
      this.currentPage = queries?.page;
      this.perPage = queries?.per_page;
      this.stateCollaseHrFormSearch = !!queries?.openToggle;
      this.sortHRs.sort_by = queries.sort_by;
      this.sortHRs.field = queries.field;
    }
    this.getListHrData();
    // Clear after created compenent
    pushParamOrQueryToRouter(this.$route.name);
  },
  methods: {
    toggleHrFormSearch() {
      if (this.stateCollaseHrFormSearch) {
        this.stateCollaseHrFormSearch = false;
      } else {
        this.stateCollaseHrFormSearch = true;
      }
    },
    reloadTable() {
      this.reRender++;
    },
    onSelectAllCheckboxChange() {
      const tempArr = this.hrItems.filter((item) => !item.on_job);
      if (this.satateSelectAllHrItem) {
        this.selectedItems = [...tempArr];
      } else {
        this.selectedItems = [];
      }
      tempArr.forEach((item) => {
        item._isSelected = this.satateSelectAllHrItem;
      });
    },
    onItemCheckboxChange(item) {
      const newArray = this.hrItems.filter((element) => {
        return !element.on_job;
      });
      if (item._isSelected) {
        this.selectedItems.push(item);
      } else {
        const index = this.selectedItems.findIndex(
          (selectedItem) => selectedItem.id === item.id
        );
        this.selectedItems.splice(index, 1);
      }
      this.satateSelectAllHrItem =
        this.selectedItems.length === newArray.length;
    },

    async goToHRDetail(detail_data) {
      // Save list query
      pushParamOrQueryToRouter(this.$route.name, {
        page: this.currentPage,
        per_page: this.perPage,
        field: this.sortHRs.field,
        sort_by: this.sortHRs.sort_by,
        openToggle: this.stateCollaseHrFormSearch,
      });
      const hr_detail_id = detail_data.item.id;
      this.$router.push(
        { path: `/hr/detail/${hr_detail_id}` },
        (onAbort) => {}
      );
    },
    handleOpenRegisterForm() {
      this.$router.push({ path: `/hr/create` }, (onAbort) => {});
    },
    handleToggleAddGroupModal() {
      // this.$refs['my-modal'].show();
      if (this.statusModalImportMultiCSV === true) {
        this.statusModalImportMultiCSV = false;
        this.file_import_name_select = '';
      } else {
        this.statusModalImportMultiCSV = true;
      }
    },
    async handleConfirmDeleteAllHRitem() {
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
        this.selectedItems.map((item) => {
          array.push(item.id);
        });
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
    async handleImportMultiCSV(event) {
      const rowFileData = event.target.files[0];
      if (!rowFileData) {
        return 0;
      }
      if (
        !FILE_CAN_UPLOAD.includes(rowFileData.type) ||
        rowFileData.size > LIMIT_FILE_SIZE.NORMAL_UPLOAD_FILE
      ) {
        MakeToast({
          variant: 'warning',
          title: this.$t('WARNING'),
          content: this.$t('VALIDATE.FILE_CSV_UPLOAD_ERORR'),
        });
        return;
      }
      const formDataCertificate = new FormData();
      formDataCertificate.append('file', rowFileData);
      try {
        this.overlay.show = true;
        const res = await uploadFile(formDataCertificate);
        const { code, data, message } = res.data;
        if (code === 200) {
          this.file_import_id = data.id;
          this.file_import_name = data.file_name;
          this.file_import_name_select = data.file_name;
          this.overlay.show = false;
        } else {
          MakeToast({
            variant: 'danger',
            title: this.$t('DANGER'),
            content: message,
          });
          this.overlay.show = false;
        }
      } catch (error) {
        console.log(error);
        this.overlay.show = false;
      }
    },
    async handleConfirmImportMultiCSV() {
      // this.handleToggleAddGroupModal();
      // return;
      if (!this.file_import_id) {
        // MakeToast({
        //   variant: 'warning',
        //   title: this.$t('WARNING'),
        //   content: this.$t('PLEASE_CHOOSE_FILE'),
        // });
        return;
      }
      try {
        this.overlay.show = true;
        const PARAM = {
          file_id: this.file_import_id,
        };
        const res = await checkFileImportHr(PARAM);
        const { code, message, data } = res.data;
        if (code === 200) {
          if (data.success.length > 0) {
            this.is_data_success = true;
            this.data_success = data.success;
            if (data.error.length < 1) {
              this.handleImportCSV();
            }
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
        console.log(error);
      }
      this.overlay.show = false;
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
        console.log(error);
      }
    },
    handleShowErrorImportCSV() {
      this.showModalErrorImportCSV = true;
    },
    convertField() {
      const desc = this.sortHRs.sort_by === 'desc';
      let field = this.sortHRs.field;
      if (field === 'corporate_name_en') {
        field = 'corporate_name';
      }
      if (field === 'date_of_birth') {
        field = 'age';
      }
      return {
        field,
        desc,
      };
    },
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
      this.currentPage = 1;
      this.getListHrData();
    },
    async handleExportCsv() {
      this.overlay.show = true;

      let finalParamSearch = {};

      this.paramSearch = this.$store.getters.searchParams;
      console.log('this.paramSearch: ', this.paramSearch);

      if (this.paramSearch) {
        if (
          ('final_education_date_from_year' in this.paramSearch &&
            'final_education_date_from_month' in this.paramSearch) ||
          ('final_education_date_to_year' in this.paramSearch &&
            'final_education_date_to_month' in this.paramSearch)
        ) {
          finalParamSearch = {
            ...this.paramSearch,
            middle_class: this.paramSearch?.middle_class
              ? this.paramSearch.middle_class.flatMap(
                (item) => item.childOptions
              )
              : null,
            main_job_ids: this.paramSearch?.main_job_ids
              ? this.paramSearch.main_job_ids.flatMap(
                (item) => item.childOptions
              )
              : null,
            edu_date_from: this.handleMergeYearMonth(
              this.paramSearch['final_education_date_from_year'],
              this.paramSearch['final_education_date_from_month']
            ),
            edu_date_to: this.handleMergeYearMonth(
              this.paramSearch['final_education_date_to_year'],
              this.paramSearch['final_education_date_to_month']
            ),
          };

          if (finalParamSearch.edu_date_from === '') {
            delete finalParamSearch.edu_date_from;
          }

          if (finalParamSearch.edu_date_to === '') {
            delete finalParamSearch.edu_date_to;
          }
        } else {
          finalParamSearch = {
            ...this.paramSearch,
            middle_class: this.paramSearch?.middle_class
              ? this.paramSearch.middle_class.flatMap(
                (item) => item.childOptions
              )
              : null,
            main_job_ids: this.paramSearch?.main_job_ids
              ? this.paramSearch.main_job_ids.flatMap(
                (item) => item.childOptions
              )
              : null,
            edu_date_from: this.paramSearch['edu_date_from']
              ? this.paramSearch['edu_date_from']
              : null,
            edu_date_to: this.paramSearch['edu_date_to']
              ? this.paramSearch['edu_date_to']
              : null,
          };
        }
      }

      const PARAM = finalParamSearch || {};

      if (this.sortHRs.field) {
        PARAM.field = this.sortHRs.field;
        PARAM.sort_by = this.sortHRs.sort_by;
      }

      // PARAM = this.cleanObject(PARAM);

      const STRING_QUERY = this.obj2Param(PARAM);
      console.log('STRING_QUERY: ', STRING_QUERY);
      const user_id = this.$store.getters.profile.id;

      const fileLink = document.createElement('a');

      fileLink.href = `${process.env.MIX_LARAVEL_TEST_URL}api/hr/download/${user_id}?${STRING_QUERY}`;
      fileLink.setAttribute('target', '_blank');
      document.body.appendChild(fileLink);

      fileLink.click();

      document.body.removeChild(fileLink);
      this.overlay.show = false;

      // try {
      //   const PARAM = this.paramSearch || {};
      //   if (this.sortHRs.field) {
      //     PARAM.field = this.sortHRs.field;
      //     PARAM.sort_by = this.sortHRs.sort_by;
      //   }
      //   const res = await downloadHrCsv(PARAM);
      //   const headers =
      //     res.headers['content-disposition'] &&
      //     res.headers['content-disposition'].split(`filename=`)[1];
      //   let filename = headers ?? 'HR_List.csv';

      //   filename = filename.replaceAll('"', '');

      //   await res.data.then((res) => {
      //     FILE_DOWNLOAD = res;
      //   });
      //   const FILE_DOWNLOAD = res.data;
      //   const fileURL = window.URL.createObjectURL(FILE_DOWNLOAD);
      //   const fileLink = document.createElement('a');
      //   console.log('fileURL: ', fileURL);

      //   fileLink.href = fileURL;
      //   fileLink.setAttribute('download', filename);
      //   fileLink.setAttribute('target', '_blank');
      //   console.log('fileLink: ', fileLink);
      //   document.body.appendChild(fileLink);

      //   fileLink.click();
      // } catch (error) {
      //   console.log(error);
      // }
      // this.overlay.show = false;
    },

    obj2Param(obj) {
      var param = '';

      for (var key in obj) {
        if (obj[key]) {
          if (Array.isArray(obj[key])) {
            let idx = 0;
            const len = obj[key].length;
            while (idx < len) {
              param += key + '[]=' + obj[key][idx] + '&';
              idx++;
            }
          } else {
            param += key + '=' + obj[key] + '&';
          }
        }
      }

      return param.substring(0, param.length - 1);
    },

    handleSearch(param) {
      this.paramSearch = param;
      this.currentPage = 1;
      this.getListHrData();
    },
    async getListHrData() {
      this.overlay.show = true;

      this.hrItems = [];

      let finalParamSearch = {};

      this.paramSearch = this.$store.getters.searchParams;

      if (this.paramSearch) {
        if (
          ('final_education_date_from_year' in this.paramSearch &&
            'final_education_date_from_month' in this.paramSearch) ||
          ('final_education_date_to_year' in this.paramSearch &&
            'final_education_date_to_month' in this.paramSearch)
        ) {
          finalParamSearch = {
            ...this.paramSearch,
            middle_class: this.paramSearch?.middle_class
              ? this.paramSearch.middle_class.flatMap(
                (item) => item.childOptions
              )
              : null,
            main_job_ids: this.paramSearch?.main_job_ids
              ? this.paramSearch.main_job_ids.flatMap(
                (item) => item.childOptions
              )
              : null,
            edu_date_from: this.handleMergeYearMonth(
              this.paramSearch['final_education_date_from_year'],
              this.paramSearch['final_education_date_from_month']
            ),
            edu_date_to: this.handleMergeYearMonth(
              this.paramSearch['final_education_date_to_year'],
              this.paramSearch['final_education_date_to_month']
            ),
          };

          if (finalParamSearch.edu_date_from === '-') {
            delete finalParamSearch.edu_date_from;
          }

          if (finalParamSearch.edu_date_to === '-') {
            delete finalParamSearch.edu_date_to;
          }
        } else {
          finalParamSearch = {
            ...this.paramSearch,
            middle_class: this.paramSearch?.middle_class
              ? this.paramSearch.middle_class.flatMap(
                (item) => item.childOptions
              )
              : null,
            main_job_ids: this.paramSearch?.main_job_ids
              ? this.paramSearch.main_job_ids.flatMap(
                (item) => item.childOptions
              )
              : null,
            edu_date_from: this.paramSearch['edu_date_from']
              ? this.paramSearch['edu_date_from']
              : null,
            edu_date_to: this.paramSearch['edu_date_to']
              ? this.paramSearch['edu_date_to']
              : null,
          };
        }
      }

      try {
        const PARAM = finalParamSearch || {};

        PARAM.page = this.currentPage;
        PARAM.per_page = this.perPage;

        if (this.sortHRs.field) {
          PARAM.field = this.sortHRs.field;
          PARAM.sort_by = this.sortHRs.sort_by;
        }

        const response = await getHrs(PARAM);

        const { code, data } = response.data;

        if (code === 200) {
          this.hrItems = data.result.map((item) => {
            return {
              ...item,
              _isSelected: false,
            };
          });

          this.totalRows = data.pagination.total_records;
          this.satateSelectAllHrItem = false;
        } else {
          MakeToast({
            variant: 'warning',
            title: this.$t('WARNING'),
            content: response.data.message,
          });

          this.totalRows = 0;
        }
      } catch (error) {
        console.log(error);
      }

      this.overlay.show = false;
    },

    handleMergeYearMonth(year, month) {
      let result = '';
      if (year && month) {
        result = `${year}-${this.handleFormatMonthDate(month)}`;
      }
      return result;
      // return `${year}-${this.handleFormatMonthDate(month)}`;
    },

    handleFormatMonthDate(string) {
      // let result;
      // if (string) {
      //   if (parseInt(string) < 10) {
      //     result = `${string}`;
      //   } else {
      //     result = string;
      //   }
      // }
      // return result;

      if (string) {
        if (parseInt(string) < 10) {
          return `${string}`;
        } else {
          return string;
        }
      } else {
        return '';
      }
    },
    onPageChange(page) {
      this.currentPage = page;
      this.getListHrData();
    },
    changeSize(size) {
      this.perPage = size;
      this.currentPage = 1;
      this.getListHrData();
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/common/common.scss';
@import '@/components/Modal/ModalStyle.scss';

.import-csv-file {
  max-width: 200px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

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
$-col-1-width: 52px;
// $-col-2-width: 52px;
$-col-2-width: 80px;
$-col-3-width: 240px;
$-col-4-width: 240px;
$-col-5-6-width: 128px;
$-col-7-width: 120px;
$-col-8-width: 60px;
.hr-fullname-col {
  width: $-col-3-width;
}
.hr-ogranization-col {
  width: $-col-4-width;
}

::v-deep .col-1 {
  width: $-col-1-width;
}
::v-deep .col-2 {
  width: $-col-2-width;
}
::v-deep .col-3 {
  width: $-col-3-width;
}
::v-deep .col-4 {
  width: $-col-4-width;
}
::v-deep .col-5,
::v-deep .col-6 {
  border: 1px solid red;
  width: $-col-5-6-width;
}
// status
::v-deep .col-7 {
  width: $-col-7-width;
}

::v-deep .col-8 {
  width: $-col-8-width;
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
::v-deep .dropdown-item:active {
  background-color: #e9ecef !important;
}
//  Ghi đè Moddal  - import CSV
// End modal - import CSV
</style>
