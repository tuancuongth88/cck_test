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
    <!--  -->
    <div class="display-user-management-list">
      <b-row class="mb-4">
        <!-- 1 -->
        <b-col v-if="!sidebarExists" cols="3 pr-0">
          <JobFormSearch @handleSearch="handleSearch" />
        </b-col>
        <!-- 2 -->
        <b-col :cols="!sidebarExists ? 9 : 12" class="pl-3">
          <div class="btn collapse-bar-btn" @click="checkSideBar">
            <div :class="{ 'rotate-180deg': sidebarExists }">
              <img
                alt="collapse"
                :src="require('@/assets/images/login/chervon-collase-bar.png')"
              >
            </div>
          </div>
          <!-- head -->
          <div class="d-flex justify-content-between align-dataJob-center mb-2">
            <b-col class="border-left-title font-weight-bold">{{
              $t('TITLE.JOB_LIST')
            }}</b-col>

            <div>
              <b-button
                variant="outline-dark mx-1"
                active
                @click="handleDeleteAll"
              >
                {{ $t('BUTTON.DELETE_ALL') }}
                <b-icon icon="trash-fill" />
              </b-button>
              <b-button
                variant="warning"
                class="text-white mx-1"
                @click="createNewJob"
              >
                {{ $t('BUTTON.NEW_REGISTER') }}
              </b-button>
            </div>
          </div>

          <!-- <div>selectAll: {{ selectAll }}</div> -->
          <!-- <div>selectedItems: {{ selectedItems }}</div> -->
          <!-- <div>dataJob: {{ dataJob }}</div> -->

          <!-- Table -->
          <b-table
            id="'job-table'"
            :key="`job-table-${reRender}`"
            show-empty
            responsive
            fixed
            :fields="fields"
            :items="dataJob"
            @sort-changed="handleSortTableJobList"
          >
            <template #empty="">
              <div class="w-100 d-flex justify-center align-center">
                <span>{{ $t('該当するデータは存在しません') }}</span>
              </div>
            </template>
            <!-- 0 checkbox all -->
            <template #head(selected)="">
              <b-form-checkbox
                v-model="selectAll"
                @change="onSelectAllCheckboxChange"
              />
            </template>

            <template #cell(selected)="row">
              <b-form-checkbox
                v-model="row.item._isSelected"
                :disabled="[1].includes(row.item.status)"
                @change="onItemCheckboxChange(row.item)"
              />
            </template>
            <!-- 1 ID -->
            <template #cell(id)="data">
              <div>{{ data.item.id }}</div>
            </template>
            <!-- 2 求人名 HR name -->
            <template #cell(job_name)="data">
              <div>{{ data.item.job_name }}</div>
            </template>
            <!-- 3 企業名 Company name -->
            <template #cell(company_name)="data">
              <div>{{ data.item.company_name }}</div>
            </template>
            <!-- 4 ステータス Status -->
            <template #cell(status)="data">
              <span
                v-if="data.item.status === 1"
                class="btn-status btn-pending"
              >{{ $t('STATUS.RECRUITING') }}
              </span>
              <span
                v-if="data.item.status === 2"
                class="btn-status btn-pending"
              >{{ $t('STATUS.PAUSED') }}
              </span>
              <span
                v-if="data.item.status === 3"
                class="btn-status btn-decline"
              >{{ $t('STATUS.OUT_OF_RECRUIMENT_PERIOD') }}
              </span>
            </template>

            <!-- 5 更新日 Updating date -->
            <template #cell(updating_date)="data">
              <div>{{ data.item.updating_date }}</div>
            </template>

            <template #cell(detail)="detail">
              <button
                class="btn-go-detail"
                @click="goToDetailJob(detail.item.id, detail.item.status)"
              >
                <i class="fas fa-eye icon-detail" />
              </button>
            </template>
          </b-table>

          <b-pagination
            v-model="currentPage"
            :aria-controls="'job-table'"
            :next-class="'next'"
            :prev-class="'prev'"
            :total-rows="totalRows"
            :per-page="perPage"
            class="d-flex justify-center"
            @currentPageChange="($event) => getCurrentPage($event)"
          />
        </b-col>
      </b-row>
    </div>
    <b-overlay />
  </b-overlay>
</template>

<script>
import { MakeToast } from '@/utils/toastMessage';
import { formattedDateTimestamp } from '@/utils/formattedDateTimestamp';
import { listJob, deleteJob } from '@/api/job';
import JobFormSearch from '@/layout/components/search/JobFormSearch.vue';

export default {
  name: 'JobList',
  components: {
    JobFormSearch,
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
      sidebarExists: false,

      queryData: {
        page: 1,
        per_page: 20,
        total_records: 0,
        search: '',
        order_type: '',
        // order_column: '',
      },

      reRender: 0,
      fields: [
        {
          key: 'selected',
          sortable: false,
          label: '',
          class: 'choose',
          thStyle: { textAlign: 'center', width: '80px' },
          tdClass: 'col-1 text-center',
        },
        // 1
        {
          key: 'id',
          sortable: true,
          label: 'ID',
          class: 'id',
          thStyle: { textAlign: 'center' },
          tdClass: 'col-2 text-center',
        },
        // 2
        {
          key: 'job_name',
          sortable: true,
          label: this.$t('JOB_DETAIL.HR_NAME'),
          class: 'job_name',
          thStyle: { textAlign: 'center' },
          tdClass: 'col-2',
        },
        // 3
        {
          key: 'company_name',
          sortable: true,
          label: this.$t('JOB_DETAIL.COMPANY_NAME'),
          class: 'company_name',
          thStyle: { textAlign: 'center' },
          tdClass: 'col-3',
        },
        // 4
        {
          key: 'status',
          sortable: true,
          label: this.$t('JOB_DETAIL.STATUS'),
          class: 'status',
          thStyle: { textAlign: 'center' },
          tdClass: 'col-4 text-center',
        },
        // 5
        {
          key: 'updating_date',
          sortable: true,
          label: this.$t('JOB_DETAIL.UPDATE_DATE'),
          class: 'updating_date',
          thStyle: { textAlign: 'center' },
          tdClass: 'col-5 text-center',
        },
        // 6
        {
          key: 'detail',
          sortable: false,
          label: this.$t('BUTTON.DETAIL'),
          class: 'detail',
          thStyle: { textAlign: 'center' },
          tdClass: 'col-6 text-center',
        },
      ],
      dataJob: [],
      // Các item được check
      selectedItems: [],
      selectAll: false,

      filterQuery: {
        order_column: '',
        order_type: '',
      },
      sortJobs: {
        field: '',
        sort_by: '',
      },

      currentPage: 1,
      totalRows: 0,
      perPage: 20,

      // listPerPage: [
      //   { value: 20, text: '20' },
      //   { value: 50, text: '50' },
      //   { value: 100, text: '100' },
      //   { value: 250, text: '250' },
      //   { value: 500, text: '500' },
      // ],
    };
  },

  computed: {
    // listUser() {
    //   return this.$store.getters.listUser;
    // },
  },

  watch: {
    sortJobs: {
      handler: function() {
        this.getListJob();
      },
      deep: true,
    },
    currentPage: {
      handler: function() {
        this.getListJob();
      },
      deep: true,
    },
    paramSearch: {
      handler: function() {
        this.getListJob();
      },
      deep: true,
    },
  },

  created() {
    this.getListJob();
  },

  methods: {
    // Reload Table
    reloadTable() {
      this.reRender++;
    },

    checkSideBar() {
      if (this.sidebarExists === false) {
        this.sidebarExists = true;
      } else {
        this.sidebarExists = false;
      }
    },

    createNewJob() {
      this.$router.push('/job/create');
    },

    goToDetailJob(id, status) {
      console.log('id và status', id, status);
      this.$router.push(
        { path: `/job/detail/${id}`, query: { status }},
        (onAbort) => {}
      );
    },

    onSelectAllCheckboxChange() {
      console.log('onSelectAllCheckboxChange');
      const tempArr = this.dataJob.filter((item) =>
        [2, 3].includes(item.status)
      );
      if (this.selectAll) {
        this.selectedItems = [...tempArr];
      } else {
        this.selectedItems = [];
      }
      tempArr.forEach((item) => {
        item._isSelected = this.selectAll;
      });
    },

    onItemCheckboxChange(item) {
      console.log('onItemCheckboxChange item: ', item);

      const newArray = this.dataJob.filter((element) => {
        return [2, 3].includes(element.status);
      });
      if (item._isSelected) {
        this.selectedItems.push(item);
      } else {
        const index = this.selectedItems.findIndex(
          (selectedItem) => selectedItem.id === item.id
        );
        this.selectedItems.splice(index, 1);
      }
      this.selectAll = this.selectedItems.length === newArray.length;
    },

    async handleSortTableJobList(ctx) {
      console.log('handleSort ctx', ctx);

      this.filterQuery.order_column = ctx.sortBy;
      // this.filterQuery.order_type = (ctx.sortDesc === true) ? 'asc' : 'desc';
      // this.filterQuery.order_type = (this.sortDesc === true) ? 'asc' : 'desc';
      if (!this.sortDesc) {
        this.sortDesc = true;
      } else {
        this.sortDesc = false;
      }
      // console.log('handleSort ctx', ctx);
      // console.log('this.sortDesc', this.sortDesc);
      this.filterQuery.order_type = this.sortDesc === true ? 'asc' : 'desc';
      await this.getListJob();
    },

    async getCurrentPage(value) {
      // console.log('getCurrentPage value: ', value);
      if (value) {
        this.currentPage = Number(value);
        await this.getListJob();
      }
    },

    async handleSearch(params) {
      // this.getListHrData(param);
      this.overlay.show = true;
      try {
        console.log('params==>', params);
        // BE bắt xóa trường không nhập
        for (const key in params) {
          if (['""', 'null', '[]'].includes(JSON.stringify(params[key]))) {
            delete params[key];
          }
        }

        const response = await listJob(params);
        const { code, message, data } = response.data;
        if (code === 200) {
          const { result } = data;
          this.dataJob = result.map((item) => {
            return {
              id: item.id,
              job_name: item.title,
              company_name: item?.company ? item.company.company_name : '',
              updating_date: formattedDateTimestamp(item.updated_at),
              status: item.status,
              _isSelected: false,
            };
          });
          this.totalRows = data.pagination?.total_records || null;
        } else {
          MakeToast({
            variant: 'warning',
            title: 'warning',
            content: message,
          });
        }
      } catch (error) {
        console.log('error', error);
      }
      this.overlay.show = false;
    },

    async getListJob() {
      const params = {
        // per_page: -1,
      };
      if (this.filterQuery.order_column) {
        params['sort_field'] = this.filterQuery.order_column;
        params['sort_by'] = this.filterQuery.order_type;
      }
      params['per_page'] = this.perPage;
      params['page'] = this.currentPage;

      try {
        this.overlay.show = true;
        const response = await listJob(params);
        const { data, code, message } = response.data;
        if (code === 200) {
          // xử lý data
          this.overlay.show = false;
          const { result } = data;
          this.totalRows = data.pagination?.total_records || null;
          //
          this.dataJob = [];
          this.dataJob = result.map((item) => {
            return {
              id: item.id,
              job_name: item.title,
              company_name: item?.company ? item.company.company_name : '',
              updating_date: formattedDateTimestamp(item.updated_at),
              status: item.status,
              _isSelected: false,
            };
          });
        } else {
          this.overlay.show = false;
          this.totalRows = 0;
          MakeToast({
            variant: 'warning',
            title: this.$t('warning'),
            content: message,
          });
        }
      } catch (error) {
        console.log('error', error);
      }
      this.reloadTable();
    },

    async handleDeleteAll() {
      const ids = this.selectedItems.map((item) => {
        return item.id;
      });
      if (ids.length > 0) {
        try {
          const response = await deleteJob({ ids: ids });
          const { code, message } = response.data;
          if (code === 200) {
            MakeToast({
              variant: 'success',
              title: 'success',
              content: message,
            });
            this.selectAll = false;
            this.getListJob();
          } else {
            MakeToast({
              variant: 'warning',
              title: 'warning',
              content: message,
            });
          }
        } catch (error) {
          console.log('error', error);
        }
      } else {
        MakeToast({
          variant: 'warning',
          title: '危険',
          content: 'You not have selected any!',
        });
      }
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/common/common.scss';
@import '@/scss/modules/Job/job.scss';

.border-left-title {
  border-left: 4px solid #314cad;
  height: 36px;
  line-height: 36px;
}

.width-106 {
  width: 106px;
}

.rotate-180deg {
  transform: rotate(-180deg);
}

.collapse-bar-btn {
  border: 1px solid #ccc;
  margin-bottom: 8px;
}

.job-pagination {
  // border: 1px solid red;
  width: 100%;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  align-items: center;
  color: $black;
  // gap: 1rem;
}
.per-page-title {
  font-size: 16px;
  font-weight: 40;
}
::v-deep .item-per-page {
  width: 100px;
}

// ::v-deep .col-1 {
//   border: 1px solid red;
//   width: 100px !important;
// }
// // = flex: 1
// ::v-deep .col-2 {
//   width: 410px !important;
// }
// ::v-deep .col-3, ::v-deep .col-4, ::v-deep .col-5 {
//   width: 156px !important;
//   border: 1px solid red;
// }
//
// ::v-deep .col-6 {
//   width: 80px;
// }
</style>
