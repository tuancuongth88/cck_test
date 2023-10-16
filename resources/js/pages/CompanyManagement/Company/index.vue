<!-- Company list -->
<!-- /company/list -->
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
    <div class="list-company">
      <!--  企業一覧 Company List -->
      <div class="list-company__head">
        <div class="line" />
        <div class="list-company-head__title">
          <span class="title-company-list">
            {{ $t('TITLE.COMPANY_LIST') }}
          </span>
        </div>
      </div>
      <!--  -->
      <!--  -->
      <div class="list-company-content">
        <div class="list-company-table-overflow">
          <div class="list-company-table">
            <div class="list-company-table-wrap">
              <!-- Table -->
              <b-table
                id="company-list-table"
                :fields="fieldslistCompany"
                :items="arrlistCompany"
                :hover="true"
                :sort-by="convertField()['field']"
                :sort-desc="convertField()['desc']"
                no-sort-reset
                no-local-sorting
                show-empty
                responsive
                fixed
                @sort-changed="handleSortTableCompanyList"
              >
                <template #empty="">
                  <div class="text-center">
                    {{ $t('TABLE_EMPTY') }}
                  </div>
                </template>
                <template #head(selected)="">
                  <b-form-checkbox
                    v-model="selectAll"
                    @change="onSelectAllCheckboxChange"
                  />
                </template>
                <template #cell(selected)="row">
                  <b-form-checkbox
                    v-model="row.item._isSelected"
                    @change="onItemCheckboxChange(row.item)"
                  />
                </template>

                <!-- 1 ID -->
                <template #cell(id)="data">
                  <div class="w-100 h-100 d-flex justify-center align-center" :title="data.item.id">
                    {{ data.item.id }}
                  </div>
                </template>
                <!-- 2 団体名 Organization name -->
                <template #cell(company_name)="data">
                  <div>
                    <span class="one-line-paragraph" :title="data.item.company_name.company_name">{{ data.item.company_name.company_name }}</span>
                  </div>
                  <div>
                    <span class="one-line-paragraph" :title="data.item.company_name.company_name_jp">{{ data.item.company_name.company_name_jp }}</span>
                  </div>
                </template>

                <!-- 3 業種分野 Field -->
                <template #cell(field)="data">
                  <div>
                    <span class="one-line-paragraph" :title="data.item.field">{{ data.item.field }}</span>
                  </div>
                </template>

                <!-- 4 更新日 Updating date-->
                <template #cell(updated_at)="data">
                  <div class="w-100 h-100 d-flex justify-center align-center">
                    <span class="one-line-paragraph" :title="data.item.updated_at">{{ data.item.updated_at }}</span>
                  </div>
                </template>
                <!-- 5 ステータス Status-->
                <template #cell(status)="data">
                  <div
                    v-if="data.item.status === '1' || data.item.status === 1"
                    class="w-100 h-100 d-flex justify-center align-center"
                  >
                    <div id="examination-pending" class="status-cell">
                      <span class="one-line-paragraph">{{ $t('STATUS.EXAMINATION_PENDING') }}</span>
                    </div>
                  </div>
                  <div
                    v-if="data.item.status === '2' || data.item.status === 2"
                    class="w-100 h-100 d-flex justify-center align-center"
                  >
                    <div id="confirm" class="status-cell">
                      <span>{{ $t('STATUS.CONFIRM') }}</span>
                    </div>
                  </div>
                  <div
                    v-if="data.item.status === '3' || data.item.status === 3"
                    class="w-100 h-100 d-flex justify-center align-center"
                  >
                    <div id="decline" class="status-cell">
                      <span>{{ $t('STATUS.REJECT') }}</span>
                    </div>
                  </div>
                  <div
                    v-if="data.item.status === '4' || data.item.status === 4"
                    class="w-100 h-100 d-flex justify-center align-center"
                  >
                    <div id="discontinuance-of-use" class="status-cell">
                      <span>{{ $t('STATUS.DISCONTINUANCE_OF_USE') }}</span>
                    </div>
                  </div>
                </template>
                <!-- 6 詳細 Detail -->
                <template #cell(detail)="detail">
                  <button
                    id="btn-go-detail"
                    class="btn-go-detail"
                    @click="goToDetail(detail.item.id, detail.value)"
                  >
                    <i class="fas fa-eye icon-detail" />
                  </button>
                </template>
                <!--  -->
              </b-table>
              <!--  -->
            </div>
          </div>
        </div>
        <!-- Pagination -->
        <!-- <div class="list-company-pagination">
          <b-pagination
            v-model="paginationNewMsglistCompany.currentPage"
            :total-rows="paginationNewMsglistCompany.totalRows"
            :per-page="paginationNewMsglistCompany.totalRows"
            aria-controls="company-list-table"
            pills
            :next-class="'next'"
            :prev-class="'prev'"
            @change="($event) => getCurrentPagelistCompany($event)"
          />
        </div> -->
        <!--  -->
        <custom-pagination
          v-if="arrlistCompany && arrlistCompany.length > 0"
          :total-rows="paginationNewMsglistCompany.totalRows"
          :current-page="paginationNewMsglistCompany.currentPage"
          :per-page="paginationNewMsglistCompany.perPage"
          @pagechanged="onPageChange"
          @changeSize="changeSize"
        />
      </div>
    </div>
  </b-overlay>
</template>

<script>
import { MakeToast } from '@/utils/toastMessage';
// import { obj2Path } from '@/utils/obj2Path';
// import ModalCommon from '@/components/Modal/index.vue';
import { listCompany } from '@/api/company';
import { formattedDateTimestamp } from '@/utils/formattedDateTimestamp';
import { PAGINATION_CONSTANT } from '@/const/config.js';
import { pushParamOrQueryToRouter } from '@/utils/routerUtils';
export default {
  name: 'CompanyList',
  components: {},

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
      paginationNewMsglistCompany: {
        currentPage: 1,
        totalRows: 0, // null
        perPage: PAGINATION_CONSTANT.DEFALT_PER_PAGE,
      },
      fieldslistCompany: [
        // 1 id
        {
          key: 'id',
          sortable: true,
          label: 'ID',
          class: 'id',
          thStyle: { textAlign: 'center', width: '80px' },
          // thClass: 'col-1',
        },
        // 2 団体名 Organization name
        {
          key: 'company_name',
          sortable: true,
          label: this.$t('JOB_DETAIL.COMPANY_NAME'),
          class: 'company_name',
          thStyle: { textAlign: 'center' },
          thClass: 'col-2',
        },
        // 3 業種分野 Field - Ngành / Lĩnh vực
        {
          key: 'field',
          sortable: true,
          label: this.$t('COMPANY.FIELD'),
          class: 'field',
          thStyle: { textAlign: 'center' },
          thClass: 'col-3 class-dusk-field',
        },
        // 4 更新日 Updating date
        {
          key: 'updated_at',
          sortable: true,
          label: this.$t('COMPANY_TABLE.UPDATING_DATE'),
          class: 'updated_at',
          thStyle: { textAlign: 'center' },
          thClass: 'col-4',
        },
        // 5 Status ステータス
        {
          key: 'status',
          sortable: true,
          label: this.$t('COMPANY_TABLE.STATUS'),
          class: 'status',
          thStyle: { textAlign: 'center' },
          tdClass: 'text-center',
          thClass: 'col-5',
        },
        // 6 詳細 Detail
        {
          key: 'detail',
          sortable: false,
          label: this.$t('COMPANY_TABLE.DETAIL'),
          class: 'detail',
          thStyle: { textAlign: 'center' },
          tdClass: 'text-center',
          thClass: 'col-6',
        },
      ],
      // 1 id
      // 2 団体名 Organization name
      // 3 業種分野 Field - Ngành / Lĩnh vực
      // 4 更新日 Updating date
      // 5 Status ステータス
      // 6 詳細 Detail
      arrlistCompany: [],
      selectedItems: [],
      selectAll: true,
      // sort
      filterQuery: {
        order_column: '',
        order_type: '',
      },
      //
    };
  },

  computed: {

  },

  created() {
    const queries = this.$store.getters.routerInfo[this.$route.name]?.queries;
    if (queries){
      this.paginationNewMsglistCompany.currentPage = queries?.page;
      this.paginationNewMsglistCompany.perPage = queries?.per_page;
      this.stateCollaseHrFormSearch = !!queries?.openToggle;
      this.filterQuery.order_type = queries.sort_by;
      this.filterQuery.order_column = queries.field;
    }
    this.getListComany();
    pushParamOrQueryToRouter(
      this.$route.name
    );
  },

  methods: {
    onSelectAllCheckboxChange() {
      if (this.selectAll) {
        this.selectedItems = [...this.arrlistCompany];
      } else {
        this.selectedItems = [];
      }
      this.arrlistCompany.forEach((item) => {
        item._isSelected = this.selectAll;
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
      this.selectAll = this.selectedItems.length === this.arrlistCompany.length;
    },
    convertField(){
      const desc = this.filterQuery.order_type === 'desc';
      const field = this.filterQuery.order_column;
      return {
        field,
        desc,
      };
    },
    async handleSortTableCompanyList(ctx) {
      this.filterQuery.order_column = ctx.sortBy;
      this.filterQuery.order_type = ctx?.sortDesc ? 'desc' : 'asc';
      this.paginationNewMsglistCompany.currentPage = 1;
      await this.getListComany();
    },
    // Modal
    handleOpenAddGroupModal() {
      this.$refs['my-modal'].show();
    },

    goToDetail(id) {
      pushParamOrQueryToRouter(
        this.$route.name,
        {
          page: this.paginationNewMsglistCompany.currentPage,
          per_page: this.paginationNewMsglistCompany.perPage,
          field: this.filterQuery.order_column,
          sort_by: this.filterQuery.order_type,
          // openToggle: this.stateCollaseHrFormSearch,
        }
      );
      this.$router.push({ path: `/company/detail/${id}` }, (onAbort) => {});
    },

    // pagination
    rows() {
      return this.items.length;
    },

    // Call API
    async getListComany() {
      const params = {
        page: this.paginationNewMsglistCompany.currentPage,
        per_page: this.paginationNewMsglistCompany.perPage,
        field: this.filterQuery.order_column,
        sort_by: this.filterQuery.order_type,
      };

      try {
        this.overlay.show = true;
        this.arrlistCompany = [];
        const response = await listCompany(params);
        const { data, code, message } = response.data;

        if (code === 200) {
          const total_records = response['data']['data']['pagination']['total_records'] || 0;
          this.overlay.show = false;
          this.paginationNewMsglistCompany.totalRows = total_records;
          this.selectAll = false;
          //
          this.convertListCompany(data.result);
          //
        } else {
          this.overlay.show = false;
          MakeToast({
            variant: 'warning',
            title: this.$t('warning'),
            content: message || '',
          });
        }
      } catch (error) {
        console.log(error);
      }
    },
    convertListCompany(dataListCompany) {
      this.arrlistCompany = dataListCompany.map((item) => {
        return {
          _isSelected: false,
          id: item.id,
          company_name: {
            company_name: item.company_name,
            company_name_jp: item.company_name_jp,
          },
          field: item.job_type.name_ja,
          updated_at: formattedDateTimestamp(item.updated_at),
          status: item.status,
        };
      });
    },

    async onPageChange(page) {
      this.paginationNewMsglistCompany.currentPage = page;
      await this.getListComany();
    },
    async changeSize(size) {
      this.paginationNewMsglistCompany.perPage = size;
      this.paginationNewMsglistCompany.currentPage = 1;
      await this.getListComany();
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/common/common.scss';
@import '@/scss/modules/Job/job.scss';

.list-company {
  // border: 1px solid red;
  width: 100%;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: stretch;
  gap: 1.375rem;
}

// ::v-deep .list-company span {
//   word-break: break-all;
// }
.list-company__head {
  // border: 1px solid;
  width: 100%;
  height: 36px;
  display: flex;
  justify-content: flex-start;
  align-items: stretch;
  gap: 1.25rem;
}
.list-company-head__title {
  font-size: 24px;
  font-weight: 700;
  line-height: 36px;
  color: $black;
}
.line {
  border: 1px solid #304cad;
  background: #304cad;
  border-radius: 10px;
  width: 4px;
  // height: 36px;
}
.list-company__content-autox {
  // border: 1px solid;
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: stretch;
  gap: 0.25rem;
}
.list-company-content {
  // border: 1px solid green;
  width: 100%;
  height: 100%;

  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: stretch;
  gap: 0.25rem;
}
.list-company-table-overflow {
  width: 100%;
  //
  height: 100%;
  display: flex;
  justify-content: flex-start;
  align-items: stretch;
  // min-height: 500px;
  // max-height: 800px;
  // overflow-y: auto;
}
.list-company-table {
  width: 100%;
}
.list-company-table-wrap {
  // border: 1px solid #d9d9d9;
  border-bottom: 1px solid #d9d9d9;
  width: 100%;
  height: fit-content;
  display: flex;
  justify-content: flex-start;
  align-items: flex-start;
}
.list-company-pagination {
  margin-top: 1rem;
  // border: 1px solid;
  width: 100%;
  display: flex;
  justify-content: flex-end;
  align-items: center;
}

.btn-light {
  background: #ffffff;
  border: 1px solid #1d266a;
  border-radius: 5px;
  height: 100%;
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

.modal-content-title {
  font-weight: 400;
  font-size: 24px;
  line-height: 29px;
  color: #262626;
}

#import {
  background: #f9be00 !important;
}

.border-left-title {
  border-left: 4px solid #314cad;
  border-radius: 10px;
  height: 36px;
  line-height: 36px;
}
.cell-grid-component {
  // border: 1px solid red;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  gap: 0.15rem;
  font-size: 16px;
  font-weight: 400;
  line-height: 21px;
  color: #333333;
  & > div {
    gap: 0.5rem;
  }
}
.dot {
  border: 1px solid $black;
  border-radius: 50%;
  background: $black;
  width: 4px;
  height: 4px;
  position: relative;
  top: 10%;
  right: 10%;
}
#field {
  color: $black;
}
.field-cell {
  color: $black;
}
.status-cell {
  border: 1px solid;
  border-radius: 5px;
  width: 100%;
  width: fit-content;
  min-width: 75px;
  height: 28px;
  padding: 0 0.5rem;
}
#examination-pending {
  border: 1px solid $applying;
  background: $white;
  color: $applying;
}
#confirm {
  border: 1px solid $approved;
  background: $white;
  color: $approved;
}
#decline {
  border: 1px solid $decline;
  background: $white;
  color: $decline;
}
#discontinuance-of-use {
  border: 1px solid $cancel;
  background: $white;
  color: $cancel;
}
// Ghi đè: Độ rộng cột
::v-deep .col-1 {
  width: 100px !important;
}
// = flex: 1
::v-deep .col-2 {
  width: 410px !important;
}
::v-deep .col-3,
::v-deep .col-4,
::v-deep .col-5 {
  width: 156px !important;
  border: 1px solid red;
}

::v-deep .col-6 {
  width: 80px;
}

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
// Moddal
::v-deep .modal-content {
  width: 652px;
  transform: translateX(-14.5%);
  padding: 4.5rem 5rem;
}
::v-deep .position-absolute {
  position: fixed !important;
}
// Ghi đè Table
::v-deep .table-responsive {
  margin-bottom: 0;
}

::v-deep .field {
  text-align: center;
}
</style>
