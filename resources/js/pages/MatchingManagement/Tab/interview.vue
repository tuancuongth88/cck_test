<template>
  <div class="w-100">
    <div :class="!sidebarExists ? 'over-flow' : ''">
      <div class="hr-list-table-list__table">
        <b-table
          id="interview-table"
          :fields="fieldsInterview"
          :items="itemsInterview"
          hover
          no-local-sorting
          show-empty
          @sort-changed="handleSort"
        >
          <template #empty="">
            <div class="text-center">
              {{ $t('TABLE_EMPTY') }}
            </div>
          </template>
          <template #head(selected)="">
            <b-form-checkbox
              id="checkbox-interview"
              v-model="selectAll"
              @change="onSelectAllCheckboxChange"
            />
          </template>
          <template #head(entry_code)="">
            <label
              class="mb-0"
            >{{ $t('ENTRY_LABEL') }} <br>
              ID</label>
          </template>
          <template #head(status_selection)="">
            <label
              class="mb-0"
            >{{ $t('SELECT_LABEL') }} <br>
              {{ $t('STATUS_LABEL') }}</label>
          </template>
          <template #head(status_interview_adjustment)="">
            <label
              class="mb-0"
            >{{ $t('INTERVIEW_ARRANGEMENT') }} <br>
              {{ $t('STATUS_LABEL') }}</label>
          </template>
          <template #cell(selected)="row">
            <b-form-checkbox
              v-model="row.item._isSelected"
              :disabled="![3, 4].includes(row.item.status_selection)"
              @change="onItemCheckboxChange(row.item)"
            />
          </template>
          <template #cell(id)="row">
            <span>{{ row.item.hr_id }}</span>
          </template>
          <template #cell(entry_code)="data">
            <span v-if="!data.item.entry_code"> - </span>
            <span v-else>
              {{ data.item.entry_code }}
            </span>
          </template>
          <template #cell(interview_date)="data">
            <span v-if="[3, 4].includes(data.item.status_interview_adjustment)">
              {{ data.item.interview_date }}
            </span>
            <span v-else> - </span>
          </template>
          <template #cell(full_name)="fullname">
            <b-link
              :to="`hr/detail/${fullname.item.hr_id}`"
              class="w-100 justify-space-between flex-column text-dark"
              style="align-items: flex-start"
            >
              <!-- <div
            v-for="(item, index) in fullname.item.full_name"
            :key="index"
            class="w-100 justify-space-between flex-column"
          > -->
              <div class="w-100 justify-space-between flex-column">
                <div
                  class="one-line-paragraph"
                  :style="{ width: `calc(${hrFullNameColWidth} - 1.5rem)` }"
                  :title="fullname.item.full_name"
                >
                  {{ fullname.item.full_name }}
                </div>
                <div
                  class="one-line-paragraph"
                  :style="{ width: `calc(${hrFullNameColWidth} - 1.5rem)` }"
                  :title="fullname.item.full_name_ja"
                >
                  {{ fullname.item.full_name_ja }}
                </div>
              </div>
            </b-link>
          </template>
          <!-- 6 求人名 Job name -->
          <template #cell(job_name)="row">
            <div class="w-100 justify-space-between flex-column">
              <b-link
                :to="
                  [
                    ROLE.TYPE_SUPER_ADMIN,
                    ROLE.TYPE_COMPANY_ADMIN,
                    ROLE.TYPE_COMPANY,
                  ].includes(permissionCheck)
                    ? `job/detail/${row.item.work_id}`
                    : `job-search/detail/${row.item.work_id}`
                "
                class="w-100 justify-space-between flex-column text-dark"
              >
                <div
                  class="one-line-paragraph"
                  :style="{ width: `calc(${workTitleColWidth} - 1.5rem)` }"
                  :title="row.item.job_name"
                >
                  {{ row.item.job_name }}
                </div>
              </b-link>
            </div>
          </template>
          <template #cell(status_selection)="data">
            <!-- <span
          v-if="
            data.item.status_selection_name === 'オファー承認' ||
              data.item.status_selection_name === '書類通過' ||
              data.item.status_selection_name === '一次合格' ||
              data.item.status_selection_name === '二次合格' ||
              data.item.status_selection_name === '三次合格' ||
              data.item.status_selection_name === '四次合格' ||
              data.item.status_selection_name === '五次合格'
          "
          class="btn-status btn-confirm"
        > -->
            <span
              v-if="[1, 2, 5, 6, 7, 8, 9].includes(data.item.status_selection)"
              class="btn-status btn-confirm"
            >
              {{ data.item.status_selection_name }}
            </span>
            <!-- <span
          v-if="
            data.item.status_selection_name === '面接中止' ||
              data.item.status_selection_name === '面接辞退'
          "
          class="btn-status btn-decline"
        > -->
            <span
              v-if="[3, 4].includes(data.item.status_selection)"
              class="btn-status btn-decline"
            >
              {{ data.item.status_selection_name }}
            </span>
          </template>
          <template #cell(status_interview_adjustment)="data">
            <span class="btn-status btn-pending">
              {{ data.item.status_interview_adjustment_name }}
            </span>
          </template>
          <template #cell(detail)="row">
            <button class="btn-go-detail" @click="handleGetDetail(row.item)">
              <i class="fas fa-eye icon-detail" />
            </button>
          </template>
        </b-table>
      </div>
    </div>
    <!-- Pagination -->
    <!-- <div class="w-100 d-flex justify-end align-center">
      <b-pagination
        v-model="queryData.current_page"
        style="padding-top: 20px"
        :total-rows="queryData.total_records"
        :per-page="queryData.per_page"
        aria-controls="interview-table"
        pills
        :next-class="'next'"
        :prev-class="'prev'"
        @change="($event) => getCurrentPage($event)"
      />
    </div> -->
    <custom-pagination
      v-if="itemsInterview && itemsInterview.length > 0"
      :total-rows="queryData.total_records"
      :current-page="queryData.current_page"
      :per-page="queryData.per_page"
      :key-tab="'tab_2'"
      @pagechanged="onPageChange"
      @changeSize="changeSize"
    />
    <!-- before adjustment -->
    <BeforeAdjustMent
      :detail-data="detail_data"
      @reloadList="onGetListInterview"
    />

    <!-- adjusting -->
    <Adjusting :detail-data="detail_data" @reloadList="onGetListInterview" />

    <!-- URL setting -->
    <url-setting :detail-data="detail_data" @reloadList="onGetListInterview" />

    <!-- adjusted-->
    <adjusted
      :detail-data="detail_data"
      @reloadList="onGetListInterview"
      @reloadResult="onReloadResult"
    />

    <!-- Confirm again -->
    <modal-common :refs="'adjusted-stop'" :size="'md'">
      <div slot="body" class="p-4">
        <h4 class="text-center text-red">
          {{ $t('CANCEL_INTERVIEW_TITLE') }}
        </h4>
        <form ref="form">
          <div class="d-flex align-items-center">
            <label class="mb-0 mr-2" for="textarea">{{ $t('TEXT_CONFIRM2') }}</label>
            <!-- <b-badge class="badge-not-required mx-2" variant="secondary">
              {{ $t('ARBITRARY') }}
            </b-badge> -->
            <Arbitrarily />
          </div>
          <b-form-textarea
            id="textarea"
            placeholder=""
            rows="6"
            max-rows="28"
            max-lengh="2000"
          />
        </form>
        <div class="d-flex align-items-center mt-5 justify-content-center">
          <b-button variant="secondary" class="mx-1">
            {{ $t('MODAL_BUTTON_CANCEL') }}
          </b-button>
          <b-button variant="danger" class="text-white mx-1">
            {{ $t('MODAL_BUTTON_CANCEL_INTERVIEW') }}
          </b-button>
        </div>
      </div>
    </modal-common>

    <!-- Modal Delete -->
    <b-modal
      id="confirm-delete-interview"
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
            @click="handleDeleteInterview"
          >
            <span>{{ $t('BUTTON.DELETE') }}</span>
          </div>
          <!-- delete -->
        </div>
      </div>
    </b-modal>
  </div>
</template>

<script>
import EventBus from '@/utils/eventBus';

import ModalCommon from '../../../layout/components/ModalCommon/matching.vue';
import {
  deleteMultipleInterview,
  getDetailInterview,
} from '@/api/modules/matching';
import { MakeToast } from '@/utils/toastMessage';
import BeforeAdjustMent from '../InterviewControl/beforeAdjustment.vue';
import Adjusting from '../InterviewControl/adjusting.vue';
import UrlSetting from '../InterviewControl/urlSetting.vue';
import Adjusted from '../InterviewControl/adjusted.vue';
import ROLE from '@/const/role.js';
import Arbitrarily from '@/components/Arbitrarily/Arbitrarily.vue';
// import { obj2Path } from '@/utils/obj2Path';
const HR_NAME_COL_WIDTH = '200px';
const WORK_TITLE_COL_WIDTH = '200px';

export default {
  name: 'Interview',
  components: {
    ModalCommon,
    BeforeAdjustMent,
    Adjusting,
    UrlSetting,
    Adjusted,
    Arbitrarily,
  },
  props: {
    dataInterview: {
      required: true,
      type: Array,
      default: function() {
        return [];
      },
    },
    pagination: {
      required: true,
      type: Object,
      default: function() {
        return {};
      },
    },
    sidebarExists: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      statusModalConfirmDelAll: false,
      typeStatus: 0,
      typeStatusOptions: [
        { key: 1, value: '集団面接' },
        { key: 2, value: '個人面接' },
      ],
      tabIndex: 0,
      itemDetail: '',
      nameState: '',
      name: '',
      ROLE: ROLE,

      overlay: {
        show: false,
        variant: 'light',
        opacity: 1,
        blur: '1rem',
        rounded: 'sm',
      },
      queryData: {},

      reRender: 0,
      // table
      fieldsInterview: [
        {
          key: 'selected',
          sortable: false,
          label: '',
          class: 'bg-header',
          thStyle: {
            backgroundColor: '#1D266A',
            width: '50px',
            color: '#fff',
            textAlign: 'center',
          },
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'id',
          sortable: true,
          label: 'ID',
          class: 'bg-header',
          thStyle: {
            backgroundColor: '#1D266A',
            width: '80px',
            color: '#fff',
            textAlign: 'center',
          },
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'entry_code',
          sortable: true,
          label: 'エントリーID',
          class: 'bg-header',
          thStyle: {
            width: '114px',
            backgroundColor: '#1D266A',
            color: '#fff',
            textAlign: 'center',
          },
          tdClass: 'text-center',
        },
        {
          key: 'interview_date',
          sortable: true,
          // label: this.$t('HEADER_REQUEST_DATE_ENTRY_MATCHING'),
          label: '面接日',
          class: 'bg-header',
          thStyle: {
            backgroundColor: '#1D266A',
            width: '120px',
            color: '#fff',
            textAlign: 'center',
          },
          tdClass: 'text-center',
        },
        {
          key: 'full_name',
          sortable: true,
          // label: this.$t('HEADER_FULL_NAME_ENTRY_MATCHING'),
          label: '氏名',
          class: 'bg-header',
          thStyle: {
            backgroundColor: '#1D266A',
            width: HR_NAME_COL_WIDTH,
            color: '#fff',
            textAlign: 'center',
          },
        },
        {
          key: 'job_name',
          sortable: true,
          // label: this.$t('HEADER_JOB_LIST_ENTRY_MATCHING'),
          label: '求人名',
          class: 'bg-header',
          thStyle: {
            backgroundColor: '#1D266A',
            width: WORK_TITLE_COL_WIDTH,
            color: '#fff',
            textAlign: 'center',
          },
        },
        {
          key: 'status_selection',
          sortable: true,
          label: '選考 ｽﾃｰﾀｽ',
          class: 'bg-header',
          thStyle: {
            backgroundColor: '#1D266A',
            width: '120px',
            color: '#fff',
            textAlign: 'center',
          },
          tdClass: 'text-center',
        },
        {
          key: 'status_interview_adjustment',
          sortable: true,
          label: '面接調整ｽﾃｰﾀｽ',
          class: 'bg-header',
          thStyle: {
            backgroundColor: '#1D266A',
            width: '120px',
            color: '#fff',
            textAlign: 'center',
          },
          tdClass: 'text-center',
        },
        // This one needs a custom template, so we define the key and the label
        {
          key: 'detail',
          label: '詳細',
          class: 'bg-header',
          thStyle: {
            width: '104px',
            backgroundColor: '#1D266A',
            color: '#fff',
            textAlign: 'center',
          },
          tdClass: 'text-center',
        },
      ],
      itemsInterview: [],
      itemsModal: [
        {
          no: '候補1',
          candidate_date: '',
          starting_time: '',
          expected_time: '',
        },
        {
          no: '候補2',
          candidate_date: '',
          starting_time: '',
          expected_time: '',
        },
        {
          no: '候補3',
          candidate_date: '',
          starting_time: '',
          expected_time: '',
        },
        {
          no: '候補4',
          candidate_date: '',
          starting_time: '',
          expected_time: '',
        },
        {
          no: '候補5',
          candidate_date: '',
          starting_time: '',
          expected_time: '',
        },
      ],
      fieldsModal: [
        {
          key: 'no',
          sortable: false,
          label: 'No.',
          class: 'bg-header',
          thStyle: {
            width: '10%',
            color: '#fff',
            textAlign: 'center',
          },
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'candidate_date',
          sortable: false,
          label: '候補日',
          class: 'bg-header',
          thStyle: {
            width: '35%',
            color: '#fff',
            textAlign: 'center',
          },
          thClass: 'text-center',
          tdClass: 'text-center',
          tdStyle: 'padding-left: 5px',
        },
        {
          key: 'starting_time',
          sortable: false,
          label: '開始時間',
          class: 'bg-header',
          thStyle: {
            width: '35%',
            color: '#fff',
            textAlign: 'center',
          },
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'expected_time',
          sortable: false,
          label: '想定所要時間',
          class: 'bg-header',
          thStyle: {
            width: '20%',
            color: '#fff',
            textAlign: 'center',
          },
          thClass: 'text-center',
          tdClass: 'text-center',
        },
      ],

      // clockOptions: ['AM', 'PM'],
      clockOptions: [
        {
          value: 'AM',
          text: '午前',
        },
        {
          value: 'PM',
          text: '午後',
        },
      ],
      hourOptions: [
        '12:00',
        '12:30',
        '1:00',
        '1:30',
        '2:00',
        '2:30',
        '3:00',
        '3:30',
        '4:00',
        '4:30',
        '5:00',
        '5:30',
        '6:00',
        '6:30',
        '7:00',
        '7:30',
        '8:00',
        '8:30',
        '9:00',
        '9:30',
        '10:00',
        '10:30',
        '11:00',
        '11:30',
      ],
      minuteOptions: ['30', '60', '90'],
      selectedItems: [],
      selectAll: false,
      step: 1,
      optionSelectStatus: '',
      isDisplaySecond: false,
      isDisplaySecondOffical: '',

      detail_data: {},
    };
  },

  computed: {
    permissionCheck() {
      return this.$store.getters.permissionCheck;
    },
    hrFullNameColWidth() {
      return HR_NAME_COL_WIDTH;
    },
    workTitleColWidth() {
      return WORK_TITLE_COL_WIDTH;
    },
  },

  watch: {
    dataInterview: {
      handler: function(props_data) {
        if (props_data) {
          this.itemsInterview = props_data;
        }
      },
      deep: true,
    },
    pagination: {
      handler: function(props_data) {
        if (props_data) {
          this.queryData = props_data;
        }
      },
      deep: true,
    },
  },

  created() {
    // this.getListAllData();
    EventBus.$on('handleDeleteInterview', () => {
      // this.handleDeleteInterview();
      this.checkDeleteAll();
    });
  },

  destroyed() {
    EventBus.$off('handleDeleteInterview');
  },

  methods: {
    checkDeleteAll() {
      if (this.selectedItems.length !== 0) {
        this.statusModalConfirmDelAll = true;
      } else {
        MakeToast({
          variant: 'warning',
          title: this.$t('WARNING'),
          content: this.$t('PLEASE_SELECT_DATA'),
        });
      }
    },
    handleSort(ctx) {
      let customSortBy = ctx?.sortBy;
      if (customSortBy === 'entry_code') {
        customSortBy = 'code';
      }
      if (customSortBy === 'id') {
        customSortBy = 'hr_id';
      }
      const sortQuery = {
        field: customSortBy,
        sort_by: ctx?.sortDesc ? 'desc' : 'asc',
      };

      this.$emit('getListInterview', sortQuery);
      this.selectAll = false;
      this.selectedItems = [];
    },
    resetModal() {
      this.step = 1;
      this.typeStatus = 0;
      this.isDisplaySecond = false;
      this.isDisplaySecondOffical = false;
      this.optionSelectStatus = '';
      this.optionSelectSecondStatus = '';
    },
    async handleGetDetail(item) {
      console.log('call detail');
      try {
        const res = await getDetailInterview(item.id);
        const { code, message, data } = res.data;
        if (code === 200) {
          this.detail_data = data;

          const DETAIL_DATA = {
            status_selection: data.status_selection,
            status_selection_name: data.status_selection_name,
            status_interview_adjustment: data.status_interview_adjustment,
            status_interview_adjustment_name:
              data.status_interview_adjustment_name,
          };
          this.goToDetail(DETAIL_DATA);
        } else {
          MakeToast({
            variant: 'danger',
            title: this.$t('DANGER'),
            content: message,
          });
        }
      } catch (error) {
        console.log(error);
      }
    },
    goToDetail(item) {
      // adjust|adjusting|URL-setting|adjusted
      if (item.status_interview_adjustment === 1) {
        EventBus.$emit('open-modal-before-adjustment');
        return;
      } else if (item.status_interview_adjustment === 2) {
        EventBus.$emit('open-modal-adjusting');
        return;
      } else if (item.status_interview_adjustment === 3) {
        EventBus.$emit('open-modal-url-setting');
        return;
      } else if (item.status_interview_adjustment === 4) {
        EventBus.$emit('open-modal-adjusted');
        // EventBus.$emit('open-modal', 'document-passing_adjusted');
        return;
      }
    },

    handleSelectStatus() {
      if (this.optionSelectStatus === '合格') {
        this.isDisplaySecond = true;
      } else {
        this.isDisplaySecond = false;
      }
      if (this.optionSelectStatus === '内定') {
        this.isDisplaySecondOffical = true;
      } else {
        this.isDisplaySecondOffical = false;
      }
    },

    handleNextStep(e) {
      e.preventDefault();
      this.checkvalidate();
      this.step++;
      if (!this.checkvalidate()) {
        e.stopPropagation();
      }
    },

    handleStopInterview() {
      EventBus.$emit('open-modal', 'adjusted-stop');
    },

    handleBack() {
      this.step--;
      if (this.step === 0) {
        EventBus.$emit('close-modal');
        this.step = 1;
      }
    },

    checkvalidate() {},

    onSelectAllCheckboxChange() {
      const tempArr = this.itemsInterview.filter(
        (item) => ![1, 2, 5, 6, 7, 8, 9].includes(item.status_selection)
      );
      if (this.selectAll) {
        this.selectedItems = [...tempArr];
      } else {
        this.selectedItems = [];
      }
      tempArr.forEach((item) => {
        item._isSelected = this.selectAll;
      });
      // if (this.selectAll) {
      //   this.selectedItems = [...this.itemsInterview];
      // } else {
      //   this.selectedItems = [];
      // }
      // this.itemsInterview.forEach((item) => {
      //   item._isSelected = this.selectAll;
      // });
    },

    onItemCheckboxChange(item) {
      const newArray = this.itemsInterview.filter((element) => {
        return ![1, 2, 5, 6, 7, 8, 9].includes(element.status_selection);
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
      // if (item._isSelected) {
      //   this.selectedItems.push(item);
      // } else {
      //   const index = this.selectedItems.findIndex(
      //     (selectedItem) => selectedItem.id === item.id
      //   );
      //   this.selectedItems.splice(index, 1);
      // }
      // this.selectAll = this.selectedItems.length === this.itemsInterview.length;
    },

    // handleDeleteAll() {
    //   const mangIds = this.selectedItems.map((item, id) => {
    //     return item.id;
    //   });
    //   console.log('handleDeleteAll', mangIds);
    // },

    onGetListInterview() {
      this.$emit('getListInterview');
    },

    onReloadResult() {
      this.$emit('reloadListResult');
    },

    async handleDeleteInterview() {
      this.statusModalConfirmDelAll = false;
      const ids = this.selectedItems.map((item) => {
        return item.id;
      });
      if (ids.length > 0) {
        try {
          const response = await deleteMultipleInterview({ ids: ids });
          const { code, message } = response.data;
          if (code === 200) {
            MakeToast({
              variant: 'success',
              title: this.$t('SUCCESS'),
              content: '削除しました',
            });
            this.selectAll = false;
            this.$emit('getListInterview');
          } else {
            MakeToast({
              variant: 'danger',
              title: this.$t('DANGER'),
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
          content: this.$t('PLEASE_SELECT_DATA'),
        });
      }
    },
    onPageChange(page) {
      if (page) {
        this.queryData.current_page = page;
        this.$emit('getListInterview');
        this.selectAll = false;
        this.selectedItems = [];
      }
    },
    changeSize(size) {
      if (size) {
        this.queryData.per_page = size;
        this.queryData.current_page = 1;
        this.$emit('getListInterview');
        this.selectAll = false;
        this.selectedItems = [];
      }
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/common/common.scss';
@import '@/scss/modules/MatchingManagement/Header.scss';
// @import '@/components/Modal/ModalStyle.scss';

::v-deep #confirm-delete-interview {
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
::v-deep .is-invalid-type-check {
  label {
    color: #dc3545;
  }
  label::before {
    border-color: #dc3545;
  }
}
.content-detail {
  background-color: #f9f9ff;
  padding: 1rem 2rem;
  margin-top: 2rem;
}

.w-137 {
  min-width: 137px;
}

.bg-white {
  background-color: #fff;
}

.btn-status {
  min-width: 100px;
}

.text-red {
  color: red;
}

.hr-list-table-list__table {
  width: max-content;
  height: 100%;
}
.over-flow {
  width: 100%;
  position: relative;
  padding-left: 0;
  overflow-x: auto;
}
</style>
