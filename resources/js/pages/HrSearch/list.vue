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
            <span>人材情報 検索一覧</span>
          </div>

          <div
            v-if="
              [
                ROLE.TYPE_SUPER_ADMIN,
                ROLE.TYPE_HR_MANAGER,
                ROLE.TYPE_HR,
              ].includes(permissionCheck)
            "
            class="hr-list-head-btns"
          >
            <div
              class="btn btn-light"
              @click="handleToggleDeleteAllItemModal()"
            >
              <span>チェックしたものを一括削除</span>
              <b-icon icon="trash-fill" style="color: #1d266a" />
            </div>
          </div>
        </div>

        <div
          :class="[
            stateCollaseHrFormSearch
              ? 'hr-list-table-list-wrap'
              : 'pl-0 hr-list-collapse',
          ]"
        >
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
                hover
                no-local-sorting
                @sort-changed="handleSortTableHrList"
              >
                <template #empty="">
                  <span>{{ $t('TABLE_EMPTY_LIST') }}</span>
                </template>

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
                    v-model="stateSelectAllHrItem"
                    @change="onSelectAllCheckboxChange"
                  />
                </template>

                <template #cell(selected)="row">
                  <b-form-checkbox
                    v-model="row.item._isSelected"
                    @change="onItemCheckboxChange(row.item, $event)"
                  />
                </template>

                <template #cell(id)="id">
                  <div class="w-100 d-flex justify-content-center">
                    <span>{{ id.item.id }}</span>
                  </div>
                </template>
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
                      <div class="text-overflow-ellipsis">
                        {{ corporateName.item.corporate_name_en }}
                      </div>
                      <div class="text-overflow-ellipsis">
                        {{ corporateName.item.corporate_name_ja }}
                      </div>
                    </div>
                  </div>
                </template>

                <template #cell(age)="age">
                  <div class="w-100 d-flex justify-content-center">
                    <span>{{ age.item.age }}</span>
                  </div>
                </template>

                <template #cell(japanese_level)="japanese_level">
                  <div class="w-100 justify-content-center">
                    <span>{{
                      renderLevelJa(japanese_level.item.japanese_level)
                    }}</span>
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
            </div>
          </div>

          <!-- Pagination -->
          <div class="w-100 d-flex justify-end align-center">
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
      </div>

      <b-modal
        ref="my-modal"
        v-model="statusModalConfirmDelAll"
        hide-header
        hide-footer
        title=""
      >
        <div class="modal-body-content">
          <div
            class="w-100 modal-content-title-del-hr d-flex justify-center align-center"
          >
            <span>{{ $t('CONFIRM_DELETE') }}</span>
          </div>

          <div class="hr-list-btns">
            <div
              id="delete-all-item-hr"
              class="btn"
              @click="handleToggleDeleteAllItemModal()"
            >
              <span> {{ $t('BUTTON.CANCEL') }}</span>
            </div>

            <div
              id="import-csv"
              class="btn accept"
              @click="handleConfirmDeleteAllHrItem()"
            >
              <span>{{ $t('BUTTON.DELETE') }}</span>
            </div>
          </div>
        </div>
      </b-modal>

      <b-modal
        ref="my-modal"
        v-model="statusModalImportMultiCSV"
        hide-header
        hide-footer
        title="Using Component Methods"
      >
        <div class="modal-body-content">
          <div class="modal-content-title">
            <span>人材情報をCSVで一括インポートする。</span>
          </div>

          <div class="hr-list-import">
            <div class="hr-list-import__title" for="file-CSV-multi">
              ファイル
            </div>

            <div class="btn hr-list-import__btn">
              <label for="upload-img">{{
                $t('HR_REGISTER.SELECT_FILE')
              }}</label>
              <input
                id="upload-img"
                ref="MultiCSV"
                type="file"
                style="display: none"
                @change="handleImportMultiCSV"
              >
            </div>
          </div>

          <div class="hr-list-btns">
            <div class="btn" @click="handleToggleAddGroupModal()">
              <span> {{ $t('BUTTON.CANCEL') }}</span>
            </div>

            <div
              id="import"
              class="btn accept"
              @click="handleConfirmImportMultiCSV()"
            >
              <span>{{ $t('INPUT') }}</span>
            </div>
          </div>
        </div>
      </b-modal>
    </div>
  </b-overlay>
</template>

<script>
// import Cookies from 'js-cookie';
import HrFormSearch from '@/layout/components/search/HrFormSearch.vue';
import { jaLevelOption } from '@/const/hrOrganization';
// import { getAllHr } from '@/api/modules/hr';
import { getHrs } from '@/api/hr';
import { MakeToast } from '../../utils/toastMessage';
import ROLE from '@/const/role.js';
import { PAGINATION_CONSTANT } from '@/const/config.js';
import { pushParamOrQueryToRouter } from '@/utils/routerUtils';
// import { obj2Path } from '@/utils/obj2Path';
// import { cleanObj } from '@/utils/handleObj';

// const urlAPI = {
//   apiGetListHr: '/hr',
// };

export default {
  name: 'HrSearchList',
  components: {
    HrFormSearch,
  },
  data() {
    return {
      overlay: {
        show: false,
        variant: 'light',
        opacity: 0,
        blur: '1rem',
        rounded: 'sm',
      },
      currentPage: 1,
      perPage: PAGINATION_CONSTANT.DEFALT_PER_PAGE,
      totalRows: 0,

      stateCollaseHrFormSearch: false,
      statusModalConfirmDelAll: false,
      statusModalImportMultiCSV: false,

      reRender: 0,

      hr_item_check_box: null,
      jaLevelOption: jaLevelOption,

      list_data: [
        {
          check_box: 'check',
          id: 65,
          fullName: 'fullName',
          organization_name: 'organization_name',
          age: 'age',
          japanese_level: 'japanese_level',
          status: 'status',
          detail: 'detail',
        },
      ],

      hrListFields: [
        { key: 'check_box', label: '', thClass: 'col1' },
        { key: 'id', label: '#', thClass: 'col2', sortable: true },
        {
          key: 'fullName',
          label: '氏名',
          thClass: 'col3',
          sortable: true,
        },
        {
          key: 'organization_name',
          label: '団体名',
        },
        {
          key: 'age',
          label: '年齢',
          thClass: 'col5',
          sortable: true,
        },
        {
          key: 'japanese_level',
          label: '日本語',
          thClass: 'col6',
          sortable: true,
        },
        {
          key: 'status',
          label: 'ステータス',
          thClass: 'col7',
          sortable: true,
        },
        {
          key: 'detail',
          label: '詳細',
          thClass: 'col8',
          sortable: true,
        },
      ],

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

      hrItems: [],

      sortHRs: {
        field: '',
        sort_by: '',
      },

      selectedItems: [],
      stateSelectAllHrItem: false,

      full_name_vn: '',
      full_name_jp: '',

      ROLE: ROLE,
    };
  },
  computed: {
    // query() {
    //   // return Cookies.get('searchParams');
    //   return this.$store.getters.searchParams;
    // },
    permissionCheck() {
      return this.$store.getters.permissionCheck;
    },
  },

  watch: {
    // currentPage: {
    //   handler: function() {
    //     this.handleGetListHr();
    //   },
    //   deep: true,
    // },
  },
  created() {
    const queries = this.$store.getters.routerInfo[this.$route.name]?.queries;
    if (queries) {
      this.currentPage = queries?.page;
      this.perPage = queries?.per_page;
      this.stateCollaseHrFormSearch = !!queries?.openToggle;
      this.sortHRs.sort_by = queries.sort_by;
      this.sortHRs.field = queries.field;
    }

    const is_getSearchStore = this.$store.getters.searchParams;
    if (is_getSearchStore !== null) {
      this.handleGetListHr(is_getSearchStore); // Nếu đầu vào có dữ liệu search
    } else {
      this.handleGetListHr(); // Nếu đầu vào không có
    }
    pushParamOrQueryToRouter(this.$route.name);
  },
  methods: {
    onPageChange(page) {
      this.currentPage = page;
      this.handleGetListHr();
    },
    changeSize(size) {
      this.perPage = size;
      this.currentPage = 1;
      this.handleGetListHr();
    },
    async handleGetListHr(query) {
      this.overlay.show = true;
      let PARAMS = {};
      // let PARAMS = {
      //   field: '',
      //   sort_by: '',
      //   page: '',
      //   per_page: '',
      //   hr_org_id: '',
      //   gender: '',
      //   age_from: '',
      //   age_to: '',
      //   edu_date_from: '',
      //   edu_date_to: '',
      //   edu_class: [],
      //   edu_degree: [],
      //   work_forms: [],
      //   work_hour: '',
      //   japan_levels: [],
      //   search: '',
      // };
      if (query) {
        // Biến đổi query của middle_class[] và main_job_ids[]
        if (
          'final_education_date_from_month' in query ||
          'final_education_date_from_year' in query ||
          'final_education_date_to_month' in query ||
          'final_education_date_to_year' in query
        ) {
          PARAMS = {
            ...query,
            middle_class: query?.middle_class
              ? query.middle_class.flatMap((item) => item.childOptions)
              : null,
            main_job_ids: query?.main_job_ids
              ? query.main_job_ids.flatMap((item) => item.childOptions)
              : null,
            edu_date_from: this.handleMergeYearMonth(
              query['final_education_date_from_year'],
              query['final_education_date_from_month']
            ),
            edu_date_to: this.handleMergeYearMonth(
              query['final_education_date_to_year'],
              query['final_education_date_to_month']
            ),
          };
          if (PARAMS.edu_date_from === '-') {
            delete PARAMS.edu_date_from;
          }
          if (PARAMS.edu_date_to === '-') {
            delete PARAMS.edu_date_to;
          }
        } else {
          PARAMS = {
            ...query,
            middle_class: query?.middle_class
              ? query.middle_class.flatMap((item) => item.childOptions)
              : null,
            main_job_ids: query?.main_job_ids
              ? query.main_job_ids.flatMap((item) => item.childOptions)
              : null,
            edu_date_from: query['edu_date_from']
              ? query['edu_date_from']
              : null,
            edu_date_to: query['edu_date_to'] ? query['edu_date_to'] : null,
          };
        }
      } else {
        console.log('Không có query ở đây');
      }

      PARAMS = {
        ...PARAMS,
        page: this.currentPage,
        per_page: this.perPage,
      };
      if (this.sortHRs.field) {
        PARAMS.field = this.sortHRs.field;
        PARAMS.sort_by = this.sortHRs.sort_by;
      }

      // PARAMS = cleanObj(PARAMS);

      // const URL = `${urlAPI.apiGetListHr}?${obj2Path(PARAMS)}`;

      // console.log('Cuối cùng thì PARAMS GỬI LÊN', PARAMS);
      // PARAMS.

      try {
        const response = await getHrs(PARAMS);
        const { code, message } = response.data;
        if (code === 200) {
          const total_records =
            response['data']['data']['pagination']['total_records'] || 0;
          this.totalRows = total_records;
          const {
            data: { result },
          } = response.data;
          this.hrItems = result.map((item) => {
            return {
              id: item.id,
              full_name: item.full_name,
              full_name_ja: item.full_name_ja,
              corporate_name_en: item.corporate_name_en,
              corporate_name_ja: item.corporate_name_ja,
              age: item.age,
              japanese_level: item.japanese_level,
              status: item.status,
            };
          });
        } else {
          MakeToast({
            variant: 'warning',
            title: 'warning',
            content: message,
          });
        }
      } catch (error) {
        console.log(error);
      }
      this.overlay.show = false;
    },
    toggleHrFormSearch() {
      this.stateCollaseHrFormSearch = !this.stateCollaseHrFormSearch;
    },
    reloadTable() {
      this.reRender++;
    },
    onSelectAllCheckboxChange(value) {
      this.stateSelectAllHrItem = value;
      this.selectedItems = [...this.hrItems];

      this.selectedItems.forEach((item) => {
        item._isSelected = this.stateSelectAllHrItem;
      });
    },
    onItemCheckboxChange(item, value) {
      if (value) {
        this.selectedItems.push(item);
      } else {
        const index = this.selectedItems.findIndex(
          (selectedItem) => selectedItem.id === item.id
        );
        this.selectedItems.splice(index, 1);
      }

      if (this.selectedItems.length === this.hrItems.length) {
        this.stateSelectAllHrItem = true;
      } else {
        this.stateSelectAllHrItem = false;
      }
    },
    async handleSort(ctx) {},
    goToHRDetail(detail_data) {
      pushParamOrQueryToRouter(this.$route.name, {
        page: this.currentPage,
        per_page: this.perPage,
        field: this.sortHRs.field,
        sort_by: this.sortHRs.sort_by,
        openToggle: this.stateCollaseHrFormSearch,
      });

      const HR_DETAIL_ID = detail_data.item.id;
      this.$router.push(
        { path: `/hr/detail/${HR_DETAIL_ID}` },
        (onAbort) => {}
      );
    },
    handleToggleAddGroupModal() {
      this.statusModalImportMultiCSV = !this.statusModalImportMultiCSV;
    },
    handleConfirmDeleteAllHrItem() {
      this.statusModalConfirmDelAll = false;
    },
    handleToggleDeleteAllItemModal() {
      this.statusModalConfirmDelAll = !this.statusModalConfirmDelAll;
    },
    handleImportMultiCSV() {},
    handleConfirmImportMultiCSV() {
      this.handleToggleAddGroupModal();
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
      this.handleGetListHr();
    },

    handleSearch(searchParams) {
      this.handleGetListHr(searchParams);
    },

    renderLevelJa(level) {
      const findItem = this.jaLevelOption.find((item) => item.key === level);
      if (findItem) {
        return findItem.value;
      } else {
        return '';
      }
    },

    handleMergeYearMonth(year, month) {
      let result;
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
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/common/common.scss';
@import '@/components/Modal/ModalStyle.scss';

.hr-list-collapse {
  width: 100%;
}

.fa-eye {
  color: #999999;
}

.hr-list {
  width: 100%;
  height: 100%;
  display: flex;
  min-height: 100vh;
  align-items: stretch;
  justify-content: space-between;
}

.hr-list__search {
  width: 25%;
  min-width: 272px;
}

.hr-list__wrap {
  width: 75%;
  gap: 0.5rem;
  display: flex;
  align-items: stretch;
  flex-direction: column;
  justify-content: flex-start;
  padding: 0rem 0rem 1.5rem 0.75rem;
}

.hr-list-collapse {
  width: 100%;
}

.hr-list-head {
  display: flex;
  align-items: stretch;
  justify-content: space-between;
}

.collapse-bar-btn {
  width: 36px;
  height: 36px;
  display: flex;
  border-radius: 3px;
  background: #ffffff;
  align-items: center;
  padding: 0 !important;
  justify-content: center;
  border: 1px solid #b7b7b7;
  transition: all 0.3s ease;

  & img {
    transform: rotate(180deg);
  }
}

.rotate-180deg {
  transform: rotate(-180deg);
}

.hr-list-head-title {
  gap: 1rem;
  display: flex;
  align-items: stretch;
  justify-content: flex-start;

  & div {
    display: flex;
    align-items: center;
    justify-content: flex-start;
  }

  & span {
    display: flex;
    color: #000000;
    font-size: 24px;
    font-weight: 700;
  }
}

.line {
  width: 4px;
  background: #304cad;
  border-radius: 10px;
  border: 1px solid #304cad;
}

.hr-list-head-btns {
  gap: 0.75rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.btn-light {
  height: 100%;
  height: 36px;
  gap: 0.25rem;
  display: flex;
  border-radius: 5px;
  background: #ffffff;
  align-items: center;
  justify-content: center;
  border: 1px solid #1d266a;

  & span {
    display: flex;
    color: #1d266a;
    font-size: 15px;
    font-weight: 400;
    line-height: 18px;
    text-align: center;
    align-items: center;
  }
}

.btn-bold {
  height: 100%;
  gap: 0.25rem;
  display: flex;
  border-radius: 5px;
  align-items: center;
  justify-content: center;
  background: #f9be00 !important;

  & span {
    color: #ffffff;
    font-size: 14px;
    font-weight: 400;
    line-height: 17px;
  }
}

.hr-list-table-list-wrap {
  width: 100%;
  height: 100%;
}

.list-table-list__auto-flow-x {
  width: 100%;
  overflow-x: auto;

  &::-webkit-scrollbar {
    width: 10px;
    height: 10px;
  }

  &::-webkit-scrollbar-thumb {
    border-radius: 6px;
  }
}

.hr-list-table-list__table {
  height: 100%;
  width: max-content;
  padding-top: 1.25rem;
}

.hr-list-table-list__table-collapse {
  width: 100%;
}

.text-overflow-ellipsis {
  width: 100%;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}

.status-item {
  width: 100%;
  height: 29px;
  cursor: default;
  border-radius: 5px;
  background: #ffffff;
  border: 1px solid #666666;
}

::v-deep .hr-list-table-list__table {
  & .b-table {
    & tbody {
      & tr {
        height: 78px;
        min-height: 78px;
        padding: 1.25rem 0;

        & td {
          position: relative;
          vertical-align: middle;

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
  width: 128px;
  border: 1px solid red;
}

::v-deep .col-7 {
  width: 64px;
  width: 108px;
}

::v-deep .col-8 {
  width: 60px;
}

::v-deep .dropdown-menu {
  left: 0 !important;
  width: 100% !important;
  min-width: 100% !important;
}

::v-deep .btn-secondary {
  background: #f9be00 !important;
  border-color: #f9be00 !important;
}
</style>
