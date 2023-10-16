<template>
  <div class="w-100">
    <div :class="!sidebarExists ? 'over-flow' : ''">
      <div class="hr-list-table-list__table">
        <b-table
          id="offer-table-list"
          :fields="fieldsOffer"
          :items="dataOffer"
          hover
          show-empty
          no-local-sorting
          @sort-changed="handleSortTable"
        >
          <template #empty="">
            <div class="text-center">
              {{ $t('TABLE_EMPTY') }}
            </div>
          </template>
          <template #head(selected)="">
            <b-form-checkbox
              id="checkbox-offer"
              v-model="selectAll"
              @change="onSelectAllCheckboxChange"
            />
          </template>
          <template #head(status_selection)="">
            <label
              class="mb-0"
            >{{ $t('SELECT_LABEL') }} <br>
              {{ $t('STATUS_LABEL') }}</label>
          </template>
          <template #cell(selected)="row">
            <b-form-checkbox
              v-model="row.item._isSelected"
              :disabled="row.item.status_selection === OFFER_STATUS.REQUESTING"
              @change="onItemCheckboxChange(row.item)"
            />
          </template>
          <template #cell(id)="row">
            <span>{{ row.item.hr_id }}</span>
          </template>
          <template #cell(full_name)="row">
            <div class="w-100 justify-space-between flex-column">
              <b-link
                :to="`hr/detail/${row.item.hr_id}`"
                class="w-100 justify-space-between flex-column text-dark"
              >
                <div
                  class="one-line-paragraph"
                  :style="{ width: `calc(${hrFullNameColWidth} - 1.5rem)` }"
                  :title="row.item.full_name"
                >
                  {{ row.item.full_name }}
                </div>
                <div
                  class="one-line-paragraph"
                  :style="{ width: `calc(${hrFullNameColWidth} - 1.5rem)` }"
                  :title="row.item.full_name_ja"
                >
                  {{ row.item.full_name_ja }}
                </div>
              </b-link>
              <!-- <img
            v-if="fullname.item.favorite"
            :src="require('@/assets/images/login/favorite-removed.png')"
            alt="favorite-removed"
          > -->
            </div>
          </template>
          <template #cell(work_title)="row">
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
                  :title="row.item.work_title"
                >
                  {{ row.item.work_title }}
                </div>
              </b-link>
            </div>
          </template>

          <template #cell(status_selection)="status_selection">
            <!-- <span v-if="status_selection.status_selection === '却下'" class="btn-status btn-decline">
          {{ $t('STATUS.REJECT') }}
        </span> -->
            <span
              v-if="status_selection.value === OFFER_STATUS.DECLINE"
              class="btn-status btn-decline"
            >
              {{ $t('STATUS.DECLINE') }}
            </span>
            <span
              v-if="status_selection.value === OFFER_STATUS.REQUESTING"
              class="btn-status btn-pending"
            >
              {{ $t('STATUS.REQUESTING') }}
            </span>
            <!-- <span v-if="status.status_selection === '他社内定'" class="btn-status btn-pending">
          {{ $t('STATUS.ORTHER_COMPANY_OFFICIAL_OFFER') }}
        </span> -->
          </template>
          <template #cell(detail)="row">
            <button
              id="btn-go-detail"
              class="btn-go-detail"
              @click="goToDetail(row.item)"
            >
              <i class="fas fa-eye icon-detail" />
            </button>
          </template>
        </b-table>
      </div>
    </div>
    <!-- Pagination -->

    <custom-pagination
      v-if="dataOffer && dataOffer.length > 0"
      :total-rows="queryData.total_records"
      :current-page="queryData.current_page"
      :per-page="queryData.per_page"
      :key-tab="'tab_1'"
      @pagechanged="onPageChange"
      @changeSize="changeSize"
    />
    <!-- Modal Decline -->
    <modal-common
      id="offer-modal-decline"
      :refs="'offer-modal-decline'"
      :size="'md'"
    >
      <template slot="body">
        <h3 class="font-weight-bold text-center">
          {{ itemDetail.full_name }} <br>
          {{ itemDetail.full_name_ja }}
        </h3>
        <div class="text-center mt-4">
          {{ itemDetail.work_title }} <br>
          {{ $t('OFFER') }}
        </div>
        <div class="content-detail text-center">
          <h6>
            {{ itemDetail.updating_date_ja }}
          </h6>
          <h3 class="text-danger mt-5">辞退</h3>
          <p class="text-center">
            {{ itemDetail.note }}
          </p>
        </div>
      </template>
    </modal-common>

    <!-- Modal Requesting -->
    <modal-common
      id="offer-modal-requesting"
      :refs="'offer-modal-requesting'"
      :size="'md'"
    >
      <template slot="body">
        <h3 class="font-weight-bold text-center">
          {{ itemDetail.full_name }} <br>
          {{ itemDetail.full_name_ja }}
        </h3>
        <div class="text-center mt-4">
          {{ itemDetail.work_title }} <br>
          {{ $t('OFFER') }}
        </div>
        <div
          v-if="
            [
              ROLE.TYPE_SUPER_ADMIN,
              ROLE.TYPE_HR_MANAGER,
              ROLE.TYPE_HR,
            ].includes(permissionCheck)
          "
          class="content-detail"
        >
          <h6 class="text-center font-weigth-bold mb-5">
            {{ $t('TEXT_CONFIRM6') }}
          </h6>
          <div class="d-flex flex-column align-items-center">
            <b-button
              id="btn-handle-modal-requesting-confirm"
              dusk="btn_confirm_offer"
              variant="primary"
              class="w-25"
              @click="handleModalRequestingConfirm"
            >
              {{ $t('BUTTON.BTN_TAB_4_MODAL_OFFER.BTN_COMFIRM_DETAIL') }}
            </b-button>
            <b-button
              id="btn-handle-modal-requesting-decline"
              dusk="btn_decline_offer"
              variant="danger"
              class="w-25 mt-2"
              @click="handleModalRequestingDecline"
            >
              {{
                $t('BUTTON.BTN_TAB_4_MODAL_OFFER.BTN_COMFIRM_STATUS_DECLINE')
              }}
            </b-button>
          </div>
        </div>
        <div
          v-if="
            [ROLE.TYPE_COMPANY_ADMIN, ROLE.TYPE_COMPANY].includes(
              permissionCheck
            )
          "
          class="content-detail"
        >
          <h3 class="text-center font-weigth-bold mb-5">
            {{ $t('STATUS.REQUESTING') }}
          </h3>
        </div>
      </template>
    </modal-common>

    <!-- offer-modal-requesting-confirm -->
    <modal-confirm
      id="offer-modal-requesting-confirm"
      :refs="'offer-modal-requesting-confirm'"
      :type="'offer'"
      @handleSubmitModalConfirm="handleSubmitModalConfirm"
      @reset-modal="resetModal"
    >
      <div slot="body">
        <h6 class="mb-5 text-center">{{ $t('TEXT_CONFIRM3_OFFER') }}</h6>
      </div>
    </modal-confirm>

    <!-- Modal Requesting Decline -->
    <modal-common
      id="offer-modal-requesting-decline"
      :refs="'offer-modal-requesting-decline'"
      :size="'md'"
    >
      <template slot="body">
        <div class="content-modal pt-3">
          <div class="text-center">
            <h5 class="font-weight-medium text-danger">
              {{ $t('TITLE.TAB_4.LABEL_MODAL_REASON') }}
            </h5>
          </div>
          <b-form-group
            id="dusk_reason_offer"
            v-slot="{ ariaDescribedby }"
            :label="$t('TITLE.TAB_4.LABEL_MODAL_CONTENT')"
            class="mt-4 px-4 mb-0"
          >
            <b-form-radio-group
              v-model="declineFormData.fixReasonDecline"
              :options="reasonOfferOptions"
              :aria-describedby="ariaDescribedby"
              :name="$t('TITLE.TAB_4.LABEL_MODAL_CONTENT')"
              value-field="key"
              text-field="value"
              stacked
              @change="handleChangeReasonDecline"
            />
            <b-form-textarea
              v-if="declineFormData.isShowNote"
              v-model="declineFormData.note"
              class="mt-4"
              dusk="decline_note_offer"
              :rows="5"
              :class="error.note === false ? 'is-invalid' : ''"
              @input="handleChangeForm($event, 'note')"
            />
            <b-form-invalid-feedback id="note" :state="error.note">
              {{ $t('VALIDATE.REQUIRED_TEXT') }}
            </b-form-invalid-feedback>
          </b-form-group>

          <div class="text-center">
            <b-button
              class="my-2 mt-5 mx-2"
              variant="secondary"
              @click="handleCancelReason"
            >
              {{ $t('MODAL_BUTTON_CANCEL') }}
            </b-button>
            <b-button
              id="btn-handle-decline-offer"
              class="my-2 mt-5 mx-2 text-white"
              dusk="btn_decline_offer"
              variant="danger"
              @click="handleDeclineOffer"
            >{{ $t('MODAL_BUTTON_DECLINE') }}
            </b-button>
          </div>
        </div>
      </template>
    </modal-common>

    <!-- confirm delete -->
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
            dusk="btn_accept_delete"
            @click="handleDeleteAllOffer"
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
import ModalConfirm from '@/layout/components/ModalCommon/confirm.vue';
import ModalCommon from '@/layout/components/ModalCommon/matching.vue';
import EventBus from '@/utils/eventBus';
import ROLE from '@/const/role.js';
import { OFFER_STATUS } from '@/const/matching.js';

import {
  deleteMultipleOffer,
  getDetailOffer,
  updateOffer,
} from '@/api/modules/matching.js';
import { MakeToast } from '@/utils/toastMessage';
import { reasonOfferOptions } from '@/const/matching.js';
const HR_NAME_COL_WIDTH = '265px';
const WORK_TITLE_COL_WIDTH = '265px';
export default {
  name: 'Offer',
  components: {
    ModalConfirm,
    ModalCommon,
  },

  props: {
    dataOffer: {
      type: Array,
      default: () => [],
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
      itemDetail: '',
      ROLE: ROLE,
      OFFER_STATUS: OFFER_STATUS,
      declineFormData: {
        note: '',
        fixReasonDecline: null,
        isShowNote: false,
      },

      overlay: {
        show: false,
        variant: 'light',
        opacity: 1,
        blur: '1rem',
        rounded: 'sm',
      },
      // queryData empty  like interview
      queryData: {
        // page: 1,
        // per_page: 20,
        // total_records: 0,
        // search: '',
        // order_type: '',
        // order_column: '',
      },

      // table offer
      fieldsOffer: [
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
          tdClass: 'text-left',
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
            align: 'center',
          },
          thClass: 'text-center',
        },
        {
          key: 'offer_date',
          sortable: true,
          label: this.$t('HEADER_REQUEST_DATE_ENTRY_MATCHING'),
          class: 'bg-header',
          thStyle: {
            backgroundColor: '#1D266A',
            width: '160px',
            color: '#fff',
            textAlign: 'center',
          },
        },
        {
          key: 'full_name',
          sortable: true,
          label: this.$t('HEADER_FULL_NAME_ENTRY_MATCHING'),
          class: 'bg-header',
          thStyle: {
            backgroundColor: '#1D266A',
            width: HR_NAME_COL_WIDTH,
            color: '#fff',
            textAlign: 'center',
          },
        },
        {
          key: 'work_title',
          sortable: true,
          label: this.$t('HEADER_JOB_LIST_ENTRY_MATCHING'),
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
          label: this.$t('HEADER_STATUS_ENTRY_MATCHING'),
          class: 'bg-header',
          thStyle: {
            backgroundColor: '#1D266A',
            width: '160px',
            color: '#fff',
            textAlign: 'center',
          },
          tdClass: 'text-center',
        },
        // This one needs a custom template, so we define the key and the label
        {
          key: 'detail',
          label: this.$t('HEADER_DETAIL_ENTRY_MATCHING'),
          class: 'bg-header',
          thStyle: {
            width: '130px',
            backgroundColor: '#1D266A',
            color: '#fff',
            textAlign: 'center',
          },
          tdClass: 'text-center',
        },
      ],

      reasonOfferOptions: reasonOfferOptions,
      selectedItems: [],
      selectAll: false,
      error: {
        note: true,
      },

      statusModalConfirmDelAll: false,
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
    declineFormData: {
      handler: function(newVal, oldVal) {
        this.error.note = true;
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
    EventBus.$on('handleDeleteAllOffer', () => {
      // this.handleDeleteAllOffer();
      this.handleCheckDelete();
    });
  },

  destroyed() {
    EventBus.$off('handleDeleteAllOffer');
  },

  methods: {
    handleChangeReasonDecline() {
      if (this.declineFormData.fixReasonDecline === 'その他') {
        this.declineFormData.isShowNote = true;
      } else {
        this.declineFormData.isShowNote = false;
      }
    },
    async goToDetail(item) {
      try {
        const response = await getDetailOffer(item.id);
        const { code, message, data } = response.data;
        if (code === 200) {
          this.itemDetail = data;
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
      if ([this.OFFER_STATUS.DECLINE].includes(item.status_selection)) {
        EventBus.$emit('open-modal', 'offer-modal-decline');
      } else {
        // 申請中
        EventBus.$emit('open-modal', 'offer-modal-requesting');
      }
    },
    // Modal
    sortingChanged(ctx) {
      this.queryData.order_column =
        ctx.sortBy === 'role[0].name' ? 'role[0].name' : ctx.sortBy;
      this.queryData.order_column = ctx.sortBy === 'name' ? 'name' : ctx.sortBy;
      this.queryData.order_column =
        ctx.sortBy === 'email' ? 'email' : ctx.sortBy;
      this.queryData.order_type = ctx.sortDesc === true ? 'ASC' : 'DESC';
      this.getListAllData();
    },

    onSelectAllCheckboxChange() {
      const tempArr = this.dataOffer.filter(
        (item) =>
          ![this.OFFER_STATUS.REQUESTING].includes(item.status_selection)
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
      const mangMoi = this.dataOffer.filter((element) => {
        return ![this.OFFER_STATUS.REQUESTING].includes(
          element.status_selection
        );
      });
      if (item._isSelected) {
        this.selectedItems.push(item);
      } else {
        const index = this.selectedItems.findIndex(
          (selectedItem) => selectedItem.id === item.id
        );
        this.selectedItems.splice(index, 1);
      }
      this.selectAll = this.selectedItems.length === mangMoi.length;
    },

    handleCheckDelete() {
      if (this.selectedItems.length > 0) {
        this.statusModalConfirmDelAll = true;
      } else {
        MakeToast({
          variant: 'warning',
          title: this.$t('WARNING'),
          content: this.$t('PLEASE_SELECT_DATA'),
        });
      }
    },

    async handleDeleteAllOffer() {
      this.statusModalConfirmDelAll = false;
      const ids = this.selectedItems.map((item) => {
        return item.id;
      });
      if (ids.length > 0) {
        try {
          const response = await deleteMultipleOffer({ ids: ids });
          const { code, message } = response.data;
          if (code === 200) {
            MakeToast({
              variant: 'success',
              title: this.$t('SUCCESS'),
              content: '削除しました',
            });
            this.selectAll = false;
            this.$emit('handleGetListOffer');
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
          content: this.$t('PLEASE_SELECT_DATA'),
        });
      }
    },

    handleModalRequestingConfirm() {
      // this.$refs['offer-modal-requesting-confirm'].show();
      EventBus.$emit('open-modal', 'offer-modal-requesting-confirm');
    },

    handleModalRequestingDecline() {
      EventBus.$emit('open-modal', 'offer-modal-requesting-decline');
    },

    handleCloseModalDecline() {
      this.$refs['offer-modal-decline'].hide();
    },

    handleCloseModalRequesting() {
      this.$refs['offer-modal-requesting'].hide();
    },

    handleCloseModalRequestingConfirm() {
      this.$refs['offer-modal-requesting-confirm'].hide();
    },

    handleCloseModalRequestingDecline() {
      this.$refs['offer-modal-requesting-decline'].hide();
    },

    checkvalidateNote() {
      if (!this.declineFormData.note) {
        this.error.note = false;
      } else {
        return true;
      }
    },

    resetModal() {
      Object.assign(this.declineFormData, {
        note: '',
        fixReasonDecline: null,
        isShowNote: false,
      });
    },

    async handleDeclineOffer() {
      const formData = {
        id: this.itemDetail.id,
        status: this.OFFER_STATUS.DECLINE,
        note: '',
      };
      if (!this.declineFormData.fixReasonDecline) {
        MakeToast({
          variant: 'warning',
          title: 'warning',
          content: '選択してください',
        });
        return 0;
      } else if (this.declineFormData.fixReasonDecline === 'その他') {
        this.checkvalidateNote();
        if (!this.checkvalidateNote()) {
          return;
        }
        formData.note = this.declineFormData.note;
      } else {
        formData.note = this.declineFormData.fixReasonDecline;
      }
      const response = await updateOffer(formData);
      const { code, message } = response.data;
      if (code === 200) {
        MakeToast({
          variant: 'success',
          title: this.$t('SUCCESS'),
          content: message,
        });
        EventBus.$emit('close-modal', 'offer-modal-requesting-decline');
        EventBus.$emit('close-modal', 'offer-modal-requesting');
        this.$emit('handleGetListOffer');
      } else {
        MakeToast({
          variant: 'warning',
          title: 'warning',
          content: message,
        });
      }
    },

    async handleSubmitModalConfirm() {
      const params = {
        id: this.itemDetail.id,
        status: this.OFFER_STATUS.CONFIRM,
      };
      const response = await updateOffer(params);
      const { code, message } = response.data;
      if (code === 200) {
        MakeToast({
          variant: 'success',
          title: this.$t('SUCCESS'),
          content: message,
        });
        EventBus.$emit('close-modal', 'offer-modal-requesting-confirm');
        EventBus.$emit('close-modal', 'offer-modal-requesting');
        this.$emit('handleGetListOffer');
        this.$emit('getListInterview');
      } else {
        MakeToast({
          variant: 'warning',
          title: 'warning',
          content: message,
        });
      }
    },

    handleCancelReason() {
      EventBus.$emit('close-modal', 'offer-modal-requesting-decline');
    },

    // getCurrentPage(value) {
    //   if (value) {
    //     this.queryData.current_page = parseInt(value);
    //     this.$emit('handleGetListOffer');
    //   }
    // },
    handleSortTable(ctx) {
      let customSortBy = ctx?.sortBy;
      if (customSortBy === 'status_selection') {
        customSortBy = 'status';
      }
      if (customSortBy === 'id') {
        customSortBy = 'hr_id';
      }
      const sortQuery = {
        field: customSortBy,
        sort_by: ctx.sortDesc ? 'desc' : 'asc',
      };
      this.$emit('handleGetListOffer', sortQuery);
      this.selectAll = false;
      this.selectedItems = [];
    },
    onPageChange(page) {
      if (page) {
        this.queryData.current_page = page;
        this.$emit('handleGetListOffer');
        this.selectAll = false;
        this.selectedItems = [];
      }
    },
    changeSize(size) {
      if (size) {
        this.queryData.per_page = size;
        this.queryData.current_page = 1;
        this.$emit('handleGetListOffer');
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

.content-detail {
  background-color: #f9f9ff;
  padding: 1.5rem 4rem;
  margin-top: 2rem;
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
