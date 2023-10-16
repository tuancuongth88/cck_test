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

    <div class="display-user-management-list">
      <b-row class="mb-2 pb-3">
        <b-col
          cols="12"
          class="d-flex justify-content-between align-items-center"
        >
          <div class="border-left-title" />
          <b-col class="hr-org-title">{{
            $t('TITLE.ORGANIZATION_LIST')
          }}</b-col>
        </b-col>
      </b-row>
      <b-row class="mb-4">
        <b-col cols="12">
          <!-- <div style="border: 1px solid #d9d9d9"> -->
          <div style="border-bottom: 1px solid #d9d9d9">
            <b-table
              id="table-lib-tablesthr-org"
              :fields="fields"
              :items="items"
              :hover="true"
              :sort-by="convertField()['field']"
              :sort-desc="convertField()['desc']"
              no-sort-reset
              no-local-sorting
              show-empty
              responsive
              fixed
              @sort-changed="handleSortTableHrOrg"
            >
              <template #empty="">
                <div class="text-center">
                  {{ $t('TABLE_EMPTY') }}
                </div>
              </template>
              <template #cell(id)="data">
                <span
                  class="d-block text-center cell-grid-component"
                  :title="data.item.id"
                >{{ data.item.id }}</span>
              </template>
              <template #cell(organization_name)="data">
                <span
                  class="d-block cell-grid-component one-line-paragraph"
                  :title="data.item.organization_name"
                >{{ data.item.organization_name }}</span>
                <span
                  class="d-block cell-grid-component one-line-paragraph"
                  :title="data.item.organization_name_ja"
                >{{ data.item.organization_name_ja }}</span>
              </template>
              <template #cell(classification)="data">
                <span
                  class="d-block text-center cell-grid-component one-line-paragraph"
                  :title="data.item.classification"
                >{{ data.item.classification }}</span>
              </template>
              <template #cell(updating_date)="data">
                <span
                  class="d-block text-center cell-grid-component one-line-paragraph"
                  :title="data.item.updating_date"
                >{{ data.item.updating_date }}</span>
              </template>
              <template #cell(status)="status">
                <span
                  v-if="status.value === '審査待ち'"
                  class="btn-status btn-pending cell-grid-component"
                >
                  {{ $t('STATUS.EXAMINATION_PENDING') }}
                </span>
                <span
                  v-if="status.value === '承認'"
                  class="btn-status btn-confirm cell-grid-component"
                  style="color: #0500ff"
                >
                  {{ $t('STATUS.CONFIRM') }}
                </span>
                <span
                  v-if="status.value === '却下'"
                  class="btn-status btn-decline cell-grid-component"
                  style="color: #f00"
                >
                  {{ $t('STATUS.REJECT') }}
                </span>
                <span
                  v-if="status.value === '利用停止'"
                  class="btn-status btn-decline cell-grid-component"
                  style="color: #f00"
                >
                  {{ $t('STATUS.DISCONTINUANCE_OF_USE') }}
                </span>
              </template>
              <template #cell(detail)="detail">
                <button
                  class="btn-go-detail"
                  @click="goToDetail(detail.item.id, detail.value)"
                >
                  <i class="fas fa-eye icon-detail" />
                </button>
              </template>
            </b-table>
          </div>
          <custom-pagination
            v-if="items && items.length > 0"
            :total-rows="totalRows"
            :current-page="currentPage"
            :per-page="perPage"
            @pagechanged="onPageChange"
            @changeSize="changeSize"
          />
          <!--  -->
        </b-col>
      </b-row>
    </div>
  </b-overlay>
</template>

<script>
// import { MakeToast } from '../../utils/toastMessage';
// import { obj2Path } from '@/utils/obj2Path';
// import ModalCommon from '@/components/Modal/index.vue';
import { getAllHrOrganization } from '@/api/hrOrganization.js';
import moment from 'moment';
import * as HR_ORG from '@/const/hrOrganization.js';
import { PAGINATION_CONSTANT } from '@/const/config.js';
import { pushParamOrQueryToRouter } from '@/utils/routerUtils';

export default {
  name: 'HrOrganizationList',
  components: {},

  data() {
    return {
      HR_ORG,
      currentPage: 1,
      totalRows: 0,
      perPage: PAGINATION_CONSTANT.DEFALT_PER_PAGE,

      overlay: {
        opacity: 0,
        show: false,
        blur: '1rem',
        rounded: 'sm',
        variant: 'light',
        fixed: true,
      },
      fields: [
        {
          key: 'id',
          sortable: true,
          label: 'ID',
          class: 'id',
          thStyle: { textAlign: 'center', width: '80px' },
          // thClass: 'col-1',
        },
        {
          key: 'organization_name',
          sortable: true,
          label: this.$t('COMPANY_TABLE.ORGANIZATION_NAME'),
          class: 'organization-name',
          thStyle: { textAlign: 'center' },
          thClass: 'col-2',
        },
        {
          key: 'classification',
          sortable: true,
          label: this.$t('HR_LIST_FORM.CLASSIFICATION'),
          class: 'classification',
          thStyle: { textAlign: 'center' },
          thClass: 'col-3',
        },
        {
          key: 'updating_date',
          sortable: true,
          label: this.$t('COMPANY_TABLE.UPDATING_DATE'),
          class: 'updated_at',
          thStyle: { textAlign: 'center' },
          thClass: 'col-4',
        },
        {
          key: 'status',
          sortable: true,
          label: this.$t('COMPANY_TABLE.STATUS'),
          class: 'status',
          thStyle: { textAlign: 'center' },
          tdClass: 'text-center',
          thClass: 'col-5',
        },
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
      items: [],
      sortHROrg: {
        field: '',
        sort_by: '',
      },
    };
  },

  computed: {
    // listUser() {
    //   return this.$store.getters.listUser;
    // },
    // currChange() {
    //   return this.queryData.page;
    // },
  },

  watch: {
    // currentPage: {
    //   handler: function() {
    //     this.getListAllData();
    //   },
    //   deep: true,
    // },
    // perPage: {
    //   handler: function() {
    //     this.getListAllData();
    //   },
    //   deep: true,
    // },
    // sortHROrg: {
    //   handler: function() {
    //     this.currentPage = 1;
    //     this.getListAllData();
    //   },
    //   deep: true,
    // },
  },

  created() {
    // Get query list from store
    const queries = this.$store.getters.routerInfo[this.$route.name]?.queries;
    if (queries) {
      this.currentPage = queries?.page;
      this.perPage = queries?.per_page;
      // this.stateCollaseHrFormSearch = !!queries?.openToggle;
      this.sortHROrg.sort_by = queries.sort_by;
      this.sortHROrg.field = queries.field;
    }
    this.getListAllData();
    // Clear after created compenent
    pushParamOrQueryToRouter(this.$route.name);
  },

  methods: {
    convertField() {
      const desc = this.sortHROrg.sort_by === 'desc';
      let field = this.sortHROrg.field;
      if (field === 'corporate_name_en') {
        field = 'organization_name';
      }
      if (field === 'account_classification') {
        field = 'classification';
      }
      if (field === 'updated_at') {
        field = 'updating_date';
      }
      return {
        field,
        desc,
      };
    },
    handleSortTableHrOrg(ctx) {
      switch (ctx.sortBy) {
        case 'id':
          this.sortHROrg.field = 'id';
          this.sortHROrg.sort_by = ctx.sortDesc ? 'desc' : 'asc';
          break;
        case 'organization_name':
          this.sortHROrg.field = 'corporate_name_en';
          this.sortHROrg.sort_by = ctx.sortDesc ? 'desc' : 'asc';
          break;
        case 'classification':
          this.sortHROrg.field = 'account_classification';
          this.sortHROrg.sort_by = ctx.sortDesc ? 'desc' : 'asc';
          break;
        case 'updating_date':
          this.sortHROrg.field = 'updated_at';
          this.sortHROrg.sort_by = ctx.sortDesc ? 'desc' : 'asc';
          break;
        case 'status':
          this.sortHROrg.field = 'status';
          this.sortHROrg.sort_by = ctx.sortDesc ? 'desc' : 'asc';
          break;

        default:
          break;
      }
      this.currentPage = 1;
      this.getListAllData();
    },
    async getListAllData() {
      this.items = [];
      this.overlay.show = true;
      const PARAM = {
        page: this.currentPage,
        per_page: this.perPage,
      };
      if (this.sortHROrg.field) {
        PARAM.field = this.sortHROrg.field;
        PARAM.sort_by = this.sortHROrg.sort_by;
      }
      await getAllHrOrganization(PARAM).then((response) => {
        const { code, data } = response.data;
        if (code === 200) {
          this.items = data.result.map((item) => {
            return {
              _isSelected: false,
              id: item.id,
              organization_name: item.corporate_name_en,
              organization_name_ja: item.corporate_name_ja,
              classification: this.converTextClassifocation(
                item.account_classification
              ),
              updating_date: moment.unix(item.updated_at).format('YYYYMMDD'),
              status: this.genderStatus(item.status),
            };
          });
          this.totalRows = data.pagination.total_records;
        }
      });
      this.overlay.show = false;
    },

    converTextClassifocation(classifocation_id) {
      let CLASSIFICATION = '';
      HR_ORG.account_classification_option.map((option) => {
        if (option.key.toString() === classifocation_id.toString()) {
          CLASSIFICATION = option.value;
          return;
        }
      });
      return CLASSIFICATION;
    },

    genderStatus(id) {
      switch (id) {
        case 1:
          return '審査待ち';
        case 2:
          return '承認';
        case 3:
          return '却下';
        case 4:
          return '利用停止';
        default:
          break;
      }
    },

    async goToDetail(id) {
      await pushParamOrQueryToRouter(this.$route.name, {
        page: this.currentPage,
        per_page: this.perPage,
        field: this.sortHROrg.field,
        sort_by: this.sortHROrg.sort_by,
        // openToggle: this.stateCollaseHrFormSearch,
      });
      this.$router.push(
        { path: `/hr-organization/detail/${id}` },
        (onAbort) => {}
      );
    },
    onPageChange(page) {
      this.currentPage = page;
      this.getListAllData();
    },
    changeSize(size) {
      this.perPage = size;
      this.currentPage = 1;
      this.getListAllData();
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/common/common.scss';

.line {
  border: 1px solid #304cad;
  background: #304cad;
  border-radius: 10px;
  width: 4px;
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

::v-deep .table-responsive {
  margin-bottom: 0;
}

.hr-org-title {
  font-size: 24px;
  font-weight: 700;
  line-height: 36px;
  padding-left: 20px;
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

// Ghi đè
// ::v-deep .col-1 {
//   width: 40px;
// }
// ::v-deep .col-2 {
//   width: 112px;
// }
// ::v-deep .col-3,
// ::v-deep .col-4 {
//   // border: 1px solid red;
//   width: 208px;
// }

// ::v-deep .col-5,
// ::v-deep .col-6 {
//   // border: 1px solid red;
//   width: 84px;
// }

// ::v-deep .col-7 {
//   // border: 1px solid red;
//   width: 96px;
// }

// ::v-deep .col-8 {
//   border: 1px solid red;
//   width: 59px;
// }

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
// Moddal
::v-deep .modal-content {
  width: 652px;
  transform: translateX(-14.5%);
  padding: 4.5rem 5rem;
}
// .modal-body-content {
//   border: 1px solid;
// }

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
  // border-left: 4px solid #314cad;
  // height: 36px;
  // line-height: 36px;
  border: 1px solid #304cad;
  background: #304cad;
  border-radius: 10px;
  width: 4px;
  padding-left: 0;
}
</style>
