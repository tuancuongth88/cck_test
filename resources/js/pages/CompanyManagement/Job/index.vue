<template>
  <b-overlay
    :show="overlay.show"
    :variant="overlay.variant"
    :opacity="overlay.opacity"
    :blur="overlay.blur"
    :rounded="overlay.sm"
  >
    <template #overlay>
      <div class="text-center">
        <b-icon icon="arrow-clockwise" font-scale="3" animation="spin" />
        <p style="margin-top: 10px">{{ $t('PLEASE_WAIT') }}</p>
      </div>
    </template>

    <div class="display-user-management-list">
      <b-row class="mb-4">
        <b-col v-if="!sidebarExists" cols="3 pr-0">
          <JobFormSearch
            @handleSearch="
              (query) => handleGetListJob({ ...query, isSearch: true })
            "
          />
        </b-col>

        <b-col :cols="!sidebarExists ? 9 : 12" class="pl-3">
          <div
            class="btn collapse-bar-btn"
            @click="handleChangeSidebarStatus()"
          >
            <!-- <div :class="{ 'rotate-180deg': sidebarExists }"> -->
            <div>
              <img
                alt="collapse"
                :src="require('@/assets/images/login/search-icon.png')"
              >
            </div>
          </div>

          <div class="d-flex justify-content-between align-items-center mb-2">
            <b-col class="border-left-title">{{ $t('TITLE.JOB_LIST') }} </b-col>

            <div>
              <b-button
                id="delete-all-selected-checkbox"
                class="btn_del_mul--custom mx-1"
                active
                @click="handleModalConfirmDelete()"
              >
                <span>{{ $t('BUTTON.DELETE_ALL') }}</span>
                <b-icon icon="trash-fill" />
              </b-button>

              <b-button
                id="navigate-to-job-registration"
                class="btn_save--custom mx-1"
                @click="navigateToJobRegistration()"
              >
                <span>{{ $t('BUTTON.NEW_REGISTER') }}</span>
              </b-button>
            </div>
          </div>

          <div class="border-bottom">
            <b-table
              id="dusk_job_table"
              dusk="job_table"
              class="job-list-table over-flow"
              hover
              fixed
              show-empty
              responsive
              :items="items"
              :fields="fields"
              :no-sort-reset="true"
              :no-local-sorting="true"
              :sort-by="convertField()['field']"
              :sort-desc="convertField()['desc']"
              @sort-changed="handleSortTableJobList"
            >
              <template #empty="">
                <div class="w-100 d-flex justify-center align-center">
                  <span>{{ $t('NOT_DATA') }}</span>
                </div>
              </template>

              <template #head(selected)="">
                <b-form-checkbox
                  v-model="selectAll"
                  size="lg"
                  @change="handleSelectAllCheckbox"
                />
              </template>

              <template #cell(selected)="row">
                <b-form-checkbox
                  v-model="row.item.is_selected"
                  size="lg"
                  :disabled="row.item.disabled"
                  @change="onItemCheckboxChange(row.item)"
                />
              </template>
              <!-- 1 ID -->
              <template #cell(id)="data">
                <div>
                  {{ data.item.id }}
                </div>
              </template>
              <!-- 2 求人名 HR name -->
              <template #cell(job_name)="data">
                <div :title="data.item.job_name">
                  <span class="one-line-paragraph">{{ data.item.job_name }}</span>
                </div>
              </template>
              <!-- 3 企業名 Company name -->
              <template #cell(company_name)="data">
                <div :title="data.item.company_name">
                  <span class="one-line-paragraph">{{
                    data.item.company_name
                  }}</span>
                </div>
              </template>
              <!-- 4 Status ステータス -->
              <template #cell(status)="data">
                <span
                  v-if="data.item.status === STATUS_JOB.RECRUITING"
                  class="btn-status btn-pending"
                >{{ $t('STATUS.RECRUITING') }}
                </span>
                <span
                  v-else-if="data.item.status === STATUS_JOB.PAUSE"
                  class="btn-status btn-pending"
                >{{ $t('STATUS.PAUSED') }}
                </span>
                <span
                  v-else-if="data.item.status === STATUS_JOB.OUT_OF_RECRUITMENT"
                  class="btn-status btn-decline"
                >{{ $t('STATUS.OUT_OF_RECRUIMENT_PERIOD') }}
                </span>
              </template>
              <!-- 5 更新日 Updating date -->
              <template #cell(updating_date)="data">
                <div class="one-line-paragraph">
                  <span>{{ data.item.updating_date }}</span>
                </div>
              </template>
              <!-- 6 詳細 Detail -->
              <template #cell(detail)="detail">
                <button
                  class="btn-go-detail"
                  @click="navigateToJobDetail(detail.item.id, detail.item.status)"
                >
                  <i class="fas fa-eye icon-detail" />
                </button>
              </template>
              <!--  -->
            </b-table>
          </div>

          <custom-pagination
            v-if="items && items.length > 0"
            :total-rows="pagination.total_records"
            :current-page="pagination.current_page"
            :per-page="pagination.per_page"
            @pagechanged="onPageChange"
            @changeSize="changeSize"
          />
        </b-col>
      </b-row>

      <!-- Modal Delete -->
      <b-modal
        id="confirm-delete-job"
        v-model="statusModalConfirmDelAll"
        hide-header
        hide-footer
        static
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
              @click="statusModalConfirmDelAll = false"
            >
              <span> {{ $t('BUTTON.CANCEL') }}</span>
            </div>
            <!-- Cancel -->
            <div
              id="import-csv"
              class="btn accept"
              @click="handleDeleteAllSelectedCheckbox"
            >
              <span>{{ $t('BUTTON.DELETE') }}</span>
            </div>
            <!-- delete -->
          </div>
        </div>
      </b-modal>
    </div>
  </b-overlay>
</template>

<script>
import JobFormSearch from '@/layout/components/search/JobFormSearch.vue';

import { MakeToast } from '@/utils/toastMessage';
import { listJob } from '@/api/job';
import { removeJob } from '@/api/modules/job';
import { formattedDateTimestamp } from '@/utils/formattedDateTimestamp';
import { PAGINATION_CONSTANT } from '@/const/config.js';
import { STATUS_JOB } from '@/const/job.js';
import { pushParamOrQueryToRouter } from '@/utils/routerUtils';
const urlAPI = {
  urlGetListJob: '/work',
  urlRemoveJob: '/work',
};

export default {
  name: 'JobList',
  components: {
    JobFormSearch,
  },
  data() {
    return {
      overlay: {
        opacity: 1,
        show: false,
        blur: '1rem',
        rounded: 'sm',
        variant: 'light',
      },

      STATUS_JOB: STATUS_JOB,

      statusModalConfirmDelAll: false,

      sidebarExists: true,

      pagination: {
        current_page: 1,
        per_page: PAGINATION_CONSTANT.DEFALT_PER_PAGE,
        total_records: 0,
      },

      items: [],

      selectedItems: [],

      selectAll: false,

      filterQuery: {
        field: '',
        sort_by: '',
      },
      FORM_DATA: null,

      sidebarFilterQuery: {
        city_id: [],
        income_to: '',
        company_id: '',
        key_search: '',
        income_from: '',
        passion_works: [],
        form_of_employment: [],
        language_requirements: [],
        middle_classification_id: [],
      },
    };
  },
  computed: {
    fields() {
      return [
        {
          key: 'selected',
          sortable: false,
          label: '',
          class: 'choose',
          thStyle: { textAlign: 'center', width: '45px' },
          tdClass: 'col-1 text-center',
        },
        {
          key: 'id',
          sortable: true,
          label: 'ID',
          class: 'id',
          thStyle: { textAlign: 'center', width: '80px' },
          tdClass: 'col-2 text-center',
        },
        {
          key: 'job_name',
          sortable: true,
          label: this.$t('JOB_DETAIL.HR_NAME'),
          class: 'job_name',
          thStyle: { textAlign: 'center' },
          tdClass: 'col-2',
        },
        {
          key: 'company_name',
          sortable: true,
          label: this.$t('JOB_DETAIL.COMPANY_NAME'),
          class: 'company_name',
          thStyle: { textAlign: 'center' },
          tdClass: 'col-3',
        },
        {
          key: 'status',
          sortable: true,
          label: this.$t('JOB_DETAIL.STATUS'),
          class: 'status',
          thStyle: { textAlign: 'center', width: '140px' },
          tdClass: 'col-4 text-center',
        },
        {
          key: 'updating_date',
          sortable: true,
          label: this.$t('JOB_DETAIL.UPDATE_DATE'),
          class: 'updating_date',
          thStyle: { textAlign: 'center', width: '140px' },
          tdClass: 'col-5 text-center',
        },
        {
          key: 'detail',
          sortable: false,
          label: this.$t('BUTTON.DETAIL'),
          class: 'detail',
          thStyle: { textAlign: 'center', width: '100px' },
          tdClass: 'col-6 text-center',
        },
      ];
    },
    setJobSearchData() {
      return this.$store.getters.job_search_data;
    },
  },
  created() {
    const queries = this.$store.getters.routerInfo[this.$route.name]?.queries;

    if (queries) {
      this.pagination.current_page = queries?.page;
      this.pagination.per_page = queries?.per_page;
      this.sidebarExists = !!queries?.openToggle;
      this.filterQuery.sort_by = queries.sort_by;
      this.filterQuery.field = queries.field;
    }
    this.handleGetListJob(this.setJobSearchData);
    pushParamOrQueryToRouter(this.$route.name);
  },
  methods: {
    handleChangeSidebarStatus() {
      this.sidebarExists = !this.sidebarExists;
    },
    navigateToJobRegistration() {
      // Save list query
      pushParamOrQueryToRouter(this.$route.name, {
        page: this.pagination.current_page,
        per_page: this.pagination.per_page,
        field: this.filterQuery.field,
        sort_by: this.filterQuery.sort_by,
        openToggle: this.sidebarExists,
      });
      this.$router.push('/job/create');
    },
    navigateToJobDetail(id) {
      // Save list query
      pushParamOrQueryToRouter(this.$route.name, {
        page: this.pagination.current_page,
        per_page: this.pagination.per_page,
        field: this.filterQuery.field,
        sort_by: this.filterQuery.sort_by,
        openToggle: this.sidebarExists,
      });
      this.$router.push({ path: `/job/detail/${id}` });
    },
    handleSelectAllCheckbox() {
      // const tempArr = this.items.filter((item) => [2, 3].includes(item.status));
      const tempArr = this.items.filter((item) => !item.disabled);

      if (this.selectAll) {
        this.selectedItems = [...tempArr];
      } else {
        this.selectedItems = [];
      }

      tempArr.forEach((item) => {
        item.is_selected = this.selectAll;
      });
    },
    onItemCheckboxChange(item) {
      const newArray = this.items.filter((element) => {
        // return [2, 3].includes(element.status);
        return element.disabled === false;
      });

      if (item.is_selected) {
        this.selectedItems.push(item);
      } else {
        const index = this.selectedItems.findIndex(
          (selectedItem) => selectedItem.id === item.id
        );

        this.selectedItems.splice(index, 1);
      }

      this.selectAll = this.selectedItems.length === newArray.length;
    },
    convertField() {
      const desc = this.filterQuery?.sort_by === 'desc';
      let field = this.filterQuery?.field;
      if (field === 'title') {
        field = 'job_name';
      }
      if (field === 'updated_at') {
        field = 'updating_date';
      }
      return {
        field,
        desc,
      };
    },
    handleSortTableJobList(ctx) {
      if (ctx.sortBy === 'job_name') {
        this.filterQuery.field = 'title';
      } else if (ctx.sortBy === 'updating_date') {
        this.filterQuery.field = 'updated_at';
      } else {
        this.filterQuery.field = ctx.sortBy;
      }
      this.filterQuery.sort_by = ctx.sortDesc ? 'desc' : 'asc';
      this.pagination.current_page = 1;
      this.selectedItems = [];
      this.handleGetListJob(this.setJobSearchData);
    },
    // getCurrentPage(value) {
    //   if (value) {
    //     this.pagination.current_page = parseInt(value);
    //     this.handleGetListJob();
    //   }
    // },
    onPageChange(page) {
      this.selectedItems = [];
      this.pagination.current_page = page;
      this.handleGetListJob(this.setJobSearchData);
    },
    changeSize(size) {
      this.selectedItems = [];
      this.pagination.per_page = size;
      this.pagination.current_page = 1;
      this.handleGetListJob(this.setJobSearchData);
    },
    async handleGetListJob(query) {
      this.items = [];

      if (query) {
        if (query?.isSearch) {
          this.pagination.current_page = 1;
        }
        this.FORM_DATA = {
          ...query,
          middle_classification_id: query.middle_classification_id.flatMap(
            (item) => item.childOptions
          ),
          city_id: query['city_id'].map((item) => item.id),
          page: this.pagination.current_page,
          per_page: this.pagination.per_page,
        };
      } else {
        this.FORM_DATA = {
          ...this.FORM_DATA,
          page: this.pagination.current_page,
          per_page: this.pagination.per_page,
        };
      }

      try {
        this.overlay.show = true;

        const response = await listJob({
          ...this.FORM_DATA,
          ...this.filterQuery,
        });
        const { code, message } = response.data;
        if (code === 200) {
          const {
            data: { result, pagination },
          } = response.data;

          this.items = result.map((item) => {
            return {
              id: item['id'],
              job_name: item['title'],
              company_name:
                item['company'] && item['company']['company_name_jp']
                  ? item['company']['company_name_jp']
                  : '',
              updating_date: formattedDateTimestamp(item['updated_at']),
              status: item['status'],
              disabled: item['on_job'],
              is_selected: false,
            };
          });

          this.pagination.current_page = pagination['current_page'];
          this.pagination.total_records = pagination['total_records'];
          this.selectAll = false;
        } else {
          MakeToast({
            variant: 'warning',
            title: this.$t('WARNING'),
            content: message,
          });
        }
      } catch (error) {
        console.log(error);

        MakeToast({
          variant: 'warning',
          title: this.$t('WARNING'),
          content: error.message,
        });
      }

      this.overlay.show = false;
    },
    handleModalConfirmDelete() {
      if (this.selectedItems.length === 0) {
        MakeToast({
          variant: 'warning',
          title: this.$t('WARNING'),
          content: this.$t('PLEASE_SELECT_DATA'),
        });
        return;
      } else {
        this.statusModalConfirmDelAll = !this.statusModalConfirmDelAll;
      }
    },

    async handleDeleteAllSelectedCheckbox() {
      this.statusModalConfirmDelAll = false;
      const ids = this.selectedItems.map((item) => {
        return item.id;
      });

      if (ids.length > 0) {
        const PARAMS = {
          ids: ids,
        };

        const URL = `${urlAPI.urlRemoveJob}`;

        try {
          const response = await removeJob(URL, PARAMS);

          if (response['code'] === 200) {
            MakeToast({
              variant: 'success',
              title: this.$t('SUCCESS'),
              // content: response['message'] || '削除が成功しました。',
              content: '削除しました',
            });

            this.selectAll = false;

            this.handleGetListJob();
          }
        } catch (error) {
          console.log(error);

          MakeToast({
            variant: 'warning',
            title: this.$t('WARNING'),
            content: error.message,
          });
        }
      } else {
        MakeToast({
          variant: 'warning',
          title: this.$t('WARNING'),
          content: this.$t('PLEASE_SELECT_DATA'),
        });
      }
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/Job/job.scss';
@import '@/scss/modules/common/common.scss';

::v-deep #confirm-delete-job {
  // Moddal
  .modal-content {
    width: 450px;
    // transform: translate(-14.5%, 12%);
    // padding: 4.5rem 5rem;
  }

  .modal-content-title {
    font-weight: 400;
    font-size: 24px;
    line-height: 29px;
    color: #262626;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 0.5rem;
  }

  .modal-content-title-del-hr {
    font-weight: 400;
    font-size: 16px;
    line-height: 24px;
    color: $black;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 0.5rem;
  }

  .hr-list-btns {
    margin-top: 5.5rem;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.5rem;
    font-weight: 400;
    font-size: 20px;
    line-height: 30px;
    text-align: center;
    & span {
      color: #ffffff !important;
    }
    & div:nth-child(1),
    & div:nth-child(2) {
      background: #acacac;
      border-radius: 40px;
      padding: 0.5rem 1.75rem;
    }
  }

  .accept {
    background: #f9be00 !important;
  }
}

.border-left-title {
  height: 36px;
  line-height: 36px;
  border-left: 4px solid #314cad;
  font-size: 24px;
  font-weight: 700;
}

.width-106 {
  width: 106px;
}

.rotate-180deg {
  transform: rotate(-180deg);
}

.collapse-bar-btn {
  margin-bottom: 8px;
  border: 1px solid #ccc;
}

.job-pagination {
  width: 100%;
  display: flex;
  color: $black;
  align-items: center;
  flex-direction: column;
  justify-content: space-between;
}

.per-page-title {
  font-size: 16px;
  font-weight: 40;
}

::v-deep .item-per-page {
  width: 100px;
}

::v-deep .btn-outline-dark {
  &:hover {
    opacity: 0.6;
    color: #1d266a !important;
    background-color: #ffffff !important;
  }
}

::v-deep .btn-status {
  width: 90px !important;
}

.over-flow {
  width: 100%;
  position: relative;
  padding-left: 0;
  overflow-x: auto;
}

// ::v-deep .job-list-table span {
//   word-break: break-all;
// }
</style>
