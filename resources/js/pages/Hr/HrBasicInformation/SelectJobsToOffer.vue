<!-- Select Jobs To Offer - Modal -->
<template>
  <b-overlay
    :show="overlaySelectJobsToOffer.show"
    :blur="overlaySelectJobsToOffer.blur"
    :rounded="overlaySelectJobsToOffer.sm"
    :style="'min-height: 100vh; height: 100%'"
    :variant="overlaySelectJobsToOffer.variant"
    :opacity="overlaySelectJobsToOffer.opacity"
  >
    <template #overlay>
      <div class="text-center">
        <b-icon icon="arrow-clockwise" font-scale="3" animation="spin" />
        <p style="margin-top: 10px">
          {{ $t('PLEASE_WAIT') }}
        </p>
      </div>
    </template>
    <!--  -->
    <div>
      <div class="select-jobs-to-offer-modal">
        <div class="select-jobs-to-offer-modal-container">
          <!-- <div class="btn btn-close" @click="handleToggleModalSelectJobsToOffer">
          <img
            :src="require('@/assets/images/login/close.png')"
            alt="heart"
            style="height: 20px; width: 20px;"
          >
        </div> -->

          <!-- <div>data_make_an_offer: {{ data_make_an_offer }}</div> -->

          <div class="select-jobs-to-offer-modal__title">
            <span>{{ $t('SELECT_JOB_TO_OFFER.TITLE') }}</span>
          </div>
          <!--  -->
          <div class="select-jobs-to-offer-modal-content">
            <!--  -->
            <div class="w-100 d-flex justify-center align-start">
              <div class="select-jobs-to-offer-search-job">
                <JobFormSearch
                  :has-company="true"
                  @handleSearch="handleSearchJob"
                />
              </div>

              <div class="select-jobs-to-offer-modal-list">
                <div class="select-jobs-to-offer-wrapper">
                  <!--  -->
                  <!-- no-sort-reset -->
                  <!-- no-local-sorting -->
                  <div class="table-list-auto-x">
                    <div class="table-list">
                      <b-table
                        id="dusk_job_to_offer"
                        :key="`select-job-to-offer-table-${reRender}`"
                        :items="listJobSelect"
                        :fields="selectJobFields"
                        hover
                        responsive
                        fixed
                        show-empty
                        @sort-changed="handleSortSelectToOfferList"
                      >
                        <template v-if="listJobSelect.length <= 0" #empty="">
                          <div class="w-100 d-flex justify-center align-center">
                            <span>{{ $t('TABLE_EMPTY') }}</span>
                          </div>
                        </template>
                        <!-- 1 ID -->
                        <template #cell(id)="data">
                          <div
                            class="table-cell-wrap d-flex justify-center align-center blue"
                            :class="[
                              {
                                'make-an-offer-item-selected':
                                  data.item.id === id_offer_data_selected,
                              },
                              {
                                'disable-select': data.item.disabled,
                              },
                            ]"
                            @click="handleSelectRowJob(data.item)"
                          >
                            <span>{{ data.item.id }}</span>
                          </div>
                        </template>
                        <!-- 2 求人名 Job name -->
                        <template #cell(title)="data">
                          <div
                            :class="[
                              {
                                'make-an-offer-item-selected':
                                  data.item.id === id_offer_data_selected,
                              },
                              {
                                'disable-select': data.item.disabled,
                              },
                            ]"
                            class="table-cell-wrap d-flex justify-start align-center blue"
                            @click="handleSelectRowJob(data.item)"
                          >
                            {{ data.item.title }}
                          </div>
                        </template>
                        <!-- 3 企業名 Company name -->
                        <template #cell(company_name_jp)="data">
                          <div
                            :class="[
                              {
                                'make-an-offer-item-selected':
                                  data.item.id === id_offer_data_selected,
                              },
                              {
                                'disable-select': data.item.disabled,
                              },
                            ]"
                            class="table-cell-wrap d-flex justify-start align-center blue"
                            @click="handleSelectRowJob(data.item)"
                          >
                            {{ data.item.company_name_jp }}
                          </div>
                        </template>
                        <!-- 4 ステータス Status -->
                        <template #cell(status)="data">
                          <!-- 1 募集中 recruiting -->
                          <div
                            v-if="data.item.status === 'recruiting'"
                            :class="[
                              {
                                'make-an-offer-item-selected':
                                  data.item.id === id_offer_data_selected,
                              },
                              {
                                'disable-select': data.item.disabled,
                              },
                            ]"
                            class="table-cell-wrap blue"
                          >
                            <div
                              class="d-flex status-item w-100 justify-center align-center status-recruiting"
                              @click="handleSelectRowJob(data.item)"
                            >
                              <span>{{ $t('STATUS.RECRUITING') }}</span>
                            </div>
                          </div>
                          <!-- 2 一時停止中 paused -->
                          <div
                            v-if="data.item.status === 'paused'"
                            :class="[
                              {
                                'make-an-offer-item-selected':
                                  data.item.id === id_offer_data_selected,
                              },
                              {
                                'disable-select': data.item.disabled,
                              },
                            ]"
                            class="table-cell-wrap blue"
                          >
                            <div
                              class="d-flex status-item w-100 justify-center align-center status-paused"
                              @click="handleSelectRowJob(data.item)"
                            >
                              <span>{{ $t('STATUS.PAUSED') }}</span>
                            </div>
                          </div>
                          <!-- 3 - 募集終了 recruitment-completed -->
                          <div
                            v-if="data.item.status === 'recruitment-completed'"
                            :class="[
                              {
                                'make-an-offer-item-selected':
                                  data.item.id === id_offer_data_selected,
                              },
                              {
                                'disable-select': data.item.disabled,
                              },
                            ]"
                            class="table-cell-wrap blue"
                          >
                            <div
                              class="d-flex status-item w-100 justify-center align-center status-recruitment-completed"
                              @click="handleSelectRowJob(data.item)"
                            >
                              <span>{{
                                $t('STATUS.RECRUITMENT_COMPLETED')
                              }}</span>
                            </div>
                          </div>
                          <div
                            v-if="data.item.status === ''"
                            :class="[
                              {
                                'make-an-offer-item-selected':
                                  data.item.id === id_offer_data_selected,
                              },
                              {
                                'disable-select': data.item.disabled,
                              },
                            ]"
                            class="table-cell-wrap blue"
                          />
                        </template>
                      </b-table>
                      <div class="w-100 d-flex justify-end align-center">
                        <!-- <b-pagination
                          v-model="currentPage"
                          style="padding-top: 20px"
                          :total-rows="totalRows"
                          :per-page="perPage"
                          aria-controls="job-select-table"
                          pills
                          :next-class="'next'"
                          :prev-class="'prev'"
                          @change="
                            ($event) => getCurrentPageSelectJobsToOffer($event)
                          "
                        /> -->
                        <custom-pagination
                          v-if="listJobSelect && listJobSelect.length > 0"
                          :total-rows="totalRows"
                          :current-page="currentPage"
                          :per-page="perPage"
                          @pagechanged="onPageChange"
                          @changeSize="changeSize"
                        />
                      </div>
                    </div>
                  </div>
                  <!--  -->

                  <!--  -->
                </div>
              </div>
            </div>
            <!--  -->
            <div class="select-jobs-to-offer-modal-list__btn">
              <!-- Cancel -->
              <div
                class="btn btn-cancle btn-cancel-offer"
                @click="cancleFormSelectJob"
              >
                <span>{{ $t('MODAL_BUTTON_CANCEL') }}</span>
              </div>
              <div
                class="btn btn-ok btn-confirm-offer"
                @click="goToConfirmSelectJobToOffer"
              >
                <span>{{ $t('OK') }}</span>
              </div>
              <!-- <ButtonRadius /> -->
            </div>
            <!-- BTN -->
          </div>
        </div>
      </div>
      <!-- Modal make an offer -->
      <b-modal
        ref="modal"
        v-model="statusModalMakeAnOffer"
        hide-footer
        aria-describedby="select-jobs-to-offer"
        no-close-on-backdrop
        hide-backdrop
        dialog-class
        :no-fade="false"
        centered
        :size="'lg'"
        title=""
        modal-key="select-jobs-to-offer-confirm"
      >
        <!-- BODY MODAL -->
        <div class="make-an-offer">
          <div class="w-100 d-flex justify-center align-center title">
            <span>{{ $t('SELECT_JOB_TO_OFFER.MAKE_AN_OFFER_TITLE') }}</span>
          </div>
          <!--  -->
          <div class="make-an-offer-content">
            <div class="make-an-offer-content-wrap">
              <!-- 1 Offer HR -->
              <b-row class="w-100 d-flex justify-start align-start px-0">
                <b-col
                  cols="4"
                  class="d-flex justify-start align-center px-0 text-left"
                >
                  <span>{{ $t('SELECT_JOB_TO_OFFER.OFFER_JOB') }}</span>
                </b-col>
                <b-col cols="8" class="px-0 pl-2 text-left">
                  <span>{{ hrFullName + ' ' + hrFullNameJp }}</span>
                </b-col>
              </b-row>

              <!-- 2 Offer Job -->
              <b-row class="w-100 d-flex justify-start align-start px-0">
                <b-col
                  cols="4"
                  class="d-flex justify-start align-center px-0 text-left"
                >
                  <span>{{ $t('SELECT_JOB_TO_OFFER.OFFER_HR') }}</span>
                </b-col>
                <b-col cols="8" class="px-0 pl-2 text-left">
                  <span>{{ offer_job }}</span>
                </b-col>
              </b-row>
              <!--  -->
              <!--  -->
            </div>
          </div>
          <!-- Back Offer -->
          <div
            class="select-jobs-to-offer-modal-list__btn"
            style="gap: 0.5rem; margin-top: 6.25rem"
          >
            <!-- Back -->
            <div class="btn btn-cancle" @click="toggleModalMakeAnOffer">
              <span>{{ $t('RETURN') }}</span>
            </div>
            <div class="btn btn-ok" @click="handleConfirmThisJobToOffer">
              <span>{{ $t('SELECT_JOB_TO_OFFER.OFFER') }}</span>
            </div>
            <!-- <ButtonRadius /> -->
          </div>
          <!--  -->
        </div>
        <!-- BODY MODAL -->
      </b-modal>
      <!--  -->
    </div>
  </b-overlay>
</template>

<script>
import { MakeToast } from '@/utils/toastMessage';
// import i18n from '@/lang';
// import { obj2Path } from '@/utils/obj2Path';
import JobFormSearch from '@/layout/components/search/JobFormSearch.vue';
// import ButtonRadius from '@/components/ButtonRadius';
import { status_select_jobs_to_offer } from '@/pages/Hr/common.js';
import { listJob } from '@/api/job.js';
import { offerJob } from '@/api/hr.js';
import { PAGINATION_CONSTANT } from '@/const/config.js';

// const urlAPI = {
//   urlGetLisUser: '/user',
//   urlDeleAll: 'user/ ',
// };
export default {
  name: 'SelectJobsToOffer',
  components: {
    JobFormSearch,
    // ButtonRadius,
  },
  props: {
    status: {
      type: Boolean,
      default: false,
    },
    hrId: {
      type: Number,
      require: true,
      default: function() {
        return null;
      },
    },
    hrFullName: {
      type: String,
      require: true,
      default: function() {
        return '';
      },
    },
    hrFullNameJp: {
      type: String,
      require: true,
      default: function() {
        return '';
      },
    },
    propG: {
      type: Function,
      // Unlike object or array default, this is not a factory
      // function - this is a function to serve as a default value
      default() {
        return 'Default function';
      },
    },
    //
  },

  data() {
    return {
      overlaySelectJobsToOffer: {
        show: true,
        variant: 'light',
        opacity: 0,
        blur: '1rem',
        rounded: 'sm',
      },
      statusModalSelectJobsToOffer: false,
      statusModalMakeAnOffer: false,
      // Reload Table
      reRender: 0,
      // status_select_jobs_to_offer: this.status,
      status_select_jobs_to_offer: status_select_jobs_to_offer,
      selectJobFields: [
        // 1 ID
        {
          key: 'id',
          sortable: true,
          label: 'ID',

          thClass: 'col-1',
        },
        // 2 求人名 Job name
        {
          key: 'title',
          sortable: true,
          label: this.$t('SELECT_JOB_TO_OFFER.JOB_NAME'),
          thClass: 'col-2',
        },
        // 3 企業名 Company name
        {
          key: 'company_name_jp',
          sortable: true,
          label: this.$t('SELECT_JOB_TO_OFFER.COMPANY_NAME'),
          thClass: 'col-3',
        },
        // 4 ステータス Status
        {
          key: 'status',
          sortable: true,
          label: this.$t('SELECT_JOB_TO_OFFER.STATUS'),
          thClass: 'col-4',
        },
      ],
      listJobSelect: [],
      id_offer_data_selected: null,
      offer_job: '',

      currentPage: 1,
      totalRows: 0,
      perPage: PAGINATION_CONSTANT.DEFALT_PER_PAGE,
      //
      paramsSearch: null,
      sort: {
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
    sort: {
      handler: function() {
        this.getListWork();
      },
      deep: true,
    },
    // currentPage: {
    //   handler: function() {
    //     this.getListWork();
    //   },
    //   deep: true,
    // },
    paramsSearch: {
      handler: function() {
        this.currentPage = 1;
        this.getListWork();
      },
      deep: true,
    },
  },

  created() {
    this.getListWork();
  },

  methods: {
    // getCurrentPageSelectJobsToOffer(value) {
    //   if (value) {
    //     this.currentPage = parseInt(value);
    //   }
    // },
    onPageChange(page) {
      this.currentPage = page;
      this.getListWork();
    },
    changeSize(size) {
      this.perPage = size;
      this.currentPage = 1;
      this.getListWork();
    },
    handleSearchJob(data) {
      this.paramsSearch = data;
    },
    async getListWork() {
      this.overlaySelectJobsToOffer.show = true;
      this.listJobSelect = [];
      try {
        let PARAM = {};
        if (this.paramsSearch) {
          PARAM = this.paramsSearch;
        }
        PARAM.display = 'offer';
        PARAM.hrs_id = this.hrId ? this.hrId : this.$route.params.id;
        PARAM.page = this.currentPage;
        PARAM.per_page = this.perPage;

        if (this.sort.field) {
          PARAM.field = this.sort.field;
          PARAM.sort_by = this.sort.sort_by;
        }
        const finalParams = {
          ...PARAM,
          middle_classification_id: PARAM['middle_classification_id']
            ? PARAM['middle_classification_id'].flatMap(
              (item) => item.childOptions
            )
            : null,
          city_id: PARAM['city_id']
            ? PARAM['city_id'].map((item) => item.id)
            : null,
        };
        const res = await listJob(finalParams);
        const { code, data } = res.data;

        if (code === 200) {
          this.overlaySelectJobsToOffer.show = false;
          this.listJobSelect = data.result.map((item) => {
            return {
              id: item.id,
              title: item.title,
              company_name: item.company.company_name,
              company_name_jp: item.company.company_name_jp,
              status: this.converStatusJob(item.status),
              disabled: this.checkDisabled(item.status, item.list_disabled_hrs),
            };
          });
          this.totalRows = data.pagination.total_records;
        }
      } catch (error) {
        this.overlaySelectJobsToOffer.show = false;
        console.log(error);
      }
      this.reloadTable();
    },

    checkDisabled(status_id, list_disabled_hrs) {
      if (status_id !== 1) {
        return true;
      }
      // if (list_disabled_hrs.includes(this.hrId)) {
      //   return true;
      // }
      return false;
    },

    converStatusJob(status_id) {
      if (!status_id) {
        return '';
      }
      if (status_id === 1) {
        return 'recruiting';
      }
      if (status_id === 2) {
        return 'paused';
      }
      if (status_id === 3) {
        return 'recruitment-completed';
      }
    },

    handleCloseModalSelectJobToOffer: function() {
      this.$emit('clicked-something', this.statusModalMakeAnOffer);
    },
    // Nút close riêmg
    handleToggleModalSelectJobsToOffer() {
      if (this.status_select_jobs_to_offer === true) {
        this.status_select_jobs_to_offer = false;
      } else {
        this.status_select_jobs_to_offer = true;
      }
    },
    // Tải lại bảng - table list Job
    reloadTable() {
      this.reRender++;
    },
    // Tắt chính modal đó - định truyền prop
    cancleFormSelectJob() {
      this.handleCloseModalSelectJobToOffer();
    },
    // On Modal child confirm => GET MakeAnOffer
    toggleModalMakeAnOffer() {
      if (this.statusModalMakeAnOffer === true) {
        this.statusModalMakeAnOffer = false;
      } else {
        this.statusModalMakeAnOffer = true;
      }
    },
    // Modal cha ok - gọi con khi đã select 1 row - có data
    goToConfirmSelectJobToOffer() {
      if (!this.id_offer_data_selected) {
        MakeToast({
          variant: 'warning',
          title: this.$t('WARNING'),
          content: this.$t('VALIDATE.NOT_SELECT_JOB_WHEN_CREATE_OFFER'),
        });
        return;
      }
      const offer_job = this.offer_job;
      const offer_hr = this.hrFullName + ' ' + this.hrFullNameJp;
      if (offer_job !== '' && offer_hr !== '') {
        this.toggleModalMakeAnOffer();
      } else {
        MakeToast({
          variant: 'warning',
          title: this.$t('WARNING'),
          content: this.$t('VALIDATE.NOT_SELECT_JOB_WHEN_CREATE_OFFER'),
        });
      }
    },

    async handleConfirmThisJobToOffer() {
      if (!this.hrId || !this.id_offer_data_selected) {
        MakeToast({
          variant: 'warning',
          title: this.$t('WARNING'),
          content: this.$t('VALIDATE.REQUIRED_TEXT'),
        });
        return;
      }
      try {
        const PARAM = {
          hr_id: this.hrId,
          work_id: this.id_offer_data_selected,
        };
        const res = await offerJob(PARAM);
        const { code, message } = res.data;
        if (code === 200) {
          MakeToast({
            variant: 'success',
            title: this.$t('SUCCESS'),
            content: this.$t('SELECT_JOB_TO_OFFER.CHOOSE_JOB_TO_OFFER_SUCCESS'),
          });
          this.$emit('close-modal');
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
      this.toggleModalMakeAnOffer();
    },
    // Sắp xếp theo trường - cột
    handleSortSelectToOfferList(ctx) {
      switch (ctx.sortBy) {
        case 'id':
          this.sort.field = 'id';
          this.sort.sort_by = ctx.sortDesc ? 'desc' : 'asc';
          break;
        case 'title':
          this.sort.field = 'title';
          this.sort.sort_by = ctx.sortDesc ? 'desc' : 'asc';
          break;
        case 'company_name_jp':
          this.sort.field = 'company_name';
          this.sort.sort_by = ctx.sortDesc ? 'desc' : 'asc';
          break;
        case 'status':
          this.sort.field = 'status';
          this.sort.sort_by = ctx.sortDesc ? 'desc' : 'asc';
          break;

        default:
          break;
      }
    },
    // Chọn 1 row
    handleSelectRowJob(data) {
      if (data.status !== 'recruiting') {
        return;
      }
      this.id_offer_data_selected = data.id;
      this.offer_job = data.title;
      this.reloadTable();
    },
    //
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/common/common.scss';

.disable-select {
  background-color: #dedede;
}

.select-jobs-to-offer-modal {
  // border: 1px solid blue;
  background: rgba(206, 184, 184, 0.1);
  width: 100%;
  min-height: 500px;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: stretch;
  // position: fixed;
  // top: 0;
  // left: 0;
  // z-index: 2;
}
.select-jobs-to-offer-modal-container {
  // border: 1px solid red;
  background: rgba(255, 255, 255, 1);
  width: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: stretch;
  position: relative;
}
.btn-close {
  // border: 1px solid red;
  padding: 0.25rem;
  position: absolute;
  top: 1.2rem;
  right: 1.2rem;
}
.select-jobs-to-offer-modal__title {
  // border: 1px solid red;
  display: flex;
  justify-content: center;
  align-items: center;
  & span {
    font-weight: 700;
    font-size: 24px;
    line-height: 36px;
    color: $black;
  }
}
.select-jobs-to-offer-modal-content {
  // border: 1px solid red;
  margin-top: 2.5rem;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: stretch;
  // gap: 1.5rem;
  & > div:nth-child(1) {
    display: flex;
    justify-content: flex-start;
    align-items: stretch;
    gap: 1.5rem;
  }
}
.select-jobs-to-offer-search-job {
  width: max-content;
}
//
.select-jobs-to-offer-modal-list {
  flex: 1;
  width: 68%;
  display: flex;
  justify-content: flex-start;
  align-items: stretch;
  gap: 1.5rem;
}
.select-jobs-to-offer-wrapper {
  width: 100%;
  padding: 0 0 2.25rem 0;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: stretch;
  gap: 2.5rem;
}
.table-list-auto-x {
  width: 100%;
  display: flex;
  justify-content: flex-start;
  align-items: stretch;
  height: 100%;
}
.table-list {
  width: 100%;
  height: 100%;
}
.table-cell-wrap {
  // border: 1px solid blue;
  width: 100%;
  padding: 0.5rem;
  height: 64px;
  font-weight: 400;
  font-size: 14px;
  line-height: 21px;
  text-align: center;
  color: $titleClassify;
  display: flex;
  justify-content: center;
  align-items: center;
}
.status-item {
  border: 1px solid;
  background: transparent;
  border-radius: 5px;
  width: 100%;
  height: 29px;
  cursor: default;
}
// BTN
.select-jobs-to-offer-modal-list__btn {
  margin: 1rem 0 2.5rem 0;
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 0.5rem;
  & > div {
    border-radius: 50px;
    height: 32px;
    padding: 0 0.75rem;
    display: flex;
    justify-content: center;
    align-items: center;
    & span {
      font-weight: 400;
      font-size: 16px;
      line-height: 24px;
      color: #ffffff;
    }
  }
}
.btn-cancle {
  background: #acacac;
}
.btn-ok {
  background: #f9be00;
}
.status-recruiting {
  border-color: $preparing !important;
  color: $preparing !important;
}
.status-paused {
  border-color: $status-pause !important;
  color: $status-pause !important;
}
.status-recruitment-completed {
  border-color: $decline !important;
  // color: $decline !important;
  color: #ff0000 !important;
}

// Modal make an offer
.make-an-offer {
  // w-100 d-flex flex-column
  background: $white;
  width: 100%;
  padding: 3.1rem 3.25rem 2.5rem 3.25rem;
  font-weight: 400;
  font-size: 16px;
  line-height: 21px;
  text-align: center;

  color: #000000;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}
.make-an-offer-content {
  width: 100%;
  margin-top: 3.75rem;
}
.make-an-offer-content-wrap {
  display: flex;
  justify-content: center;
  flex-direction: column;
  align-items: center;
  gap: 1rem;
}
.make-an-offer-item-selected {
  background-color: #c6e3ff;
}
// Ghi đè css table
::v-deep table > tbody > tr > td {
  // font-weight: 400;
  // font-size: 14px;
  // line-height: 17px;
  // text-align: center;
  // color: #333333;
  // border: 1px solid red;
  padding: 0;
}

::v-deep .table-hover tbody tr:hover {
  color: #212529;
  background-color: #c6e3ff;
  cursor: pointer;
}
::v-deep .col-1 {
  width: 96px;
}
::v-deep .col-2 {
  width: 236px;
}
::v-deep .col-3 {
  width: 224px;
}
::v-deep .col-4 {
  width: 104px;
}
</style>
