<template>
  <div class="w-100">
    <div :class="!sidebarExists ? 'over-flow' : ''">
      <div class="hr-list-table-list__table">
        <b-table
          id="entry-table-list"
          :fields="fields"
          :items="dataEntry"
          hover
          no-local-sorting
          show-empty
          @sort-changed="handleSortTable"
        >
          <template #empty="">
            <div class="text-center">
              {{ $t('TABLE_EMPTY') }}
            </div>
          </template>
          <template #head(selected)="">
            <b-form-checkbox
              id="checkbox-entry"
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
          <template #head(status)="">
            <label
              class="mb-0"
            >{{ $t('SELECT_LABEL') }} <br>
              {{ $t('STATUS_LABEL') }}</label>
          </template>
          <template #cell(selected)="row">
            <b-form-checkbox
              v-model="row.item._isSelected"
              :name="'BFormCheckbox'"
              :disabled="
                ![ENTRY_STATUS.REJECT, ENTRY_STATUS.DECLINE].includes(
                  row.item.status
                )
              "
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

          <template #cell(status)="status">
            <span
              v-if="status.value === ENTRY_STATUS.REQUESTING"
              class="btn-status btn-pending"
            >
              {{ $t('STATUS.REQUESTING') }}
            </span>
            <span
              v-if="status.value === ENTRY_STATUS.REJECT"
              class="btn-status btn-decline"
            >
              {{ $t('STATUS.REJECT') }}
            </span>
            <span
              v-if="status.value === ENTRY_STATUS.DECLINE"
              class="btn-status btn-decline"
            >
              {{ $t('STATUS.DECLINE') }}
            </span>
            <span
              v-if="status.value === ENTRY_STATUS.OTHER_COMPANY_OFFICAL_OFFER"
              class="btn-status btn-pending"
            >
              {{ $t('STATUS.ORTHER_COMPANY_OFFICIAL_OFFER') }}
            </span>
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
      v-if="dataEntry && dataEntry.length > 0"
      :total-rows="queryData.total_records"
      :current-page="queryData.current_page"
      :per-page="queryData.per_page"
      :key-tab="'tab_0'"
      @pagechanged="onPageChange"
      @changeSize="changeSize"
    />

    <!-- Modal Requesting -->
    <modal-common
      id="entry-modal-requesting"
      :refs="'entry-modal-requesting'"
      :size="'lg'"
      @reset-modal="resetModal"
    >
      <div slot="body">
        <div class="text-center">
          <h2 class="font-weight-bold">
            {{ itemDetail.full_name }} <br>
            {{ itemDetail.full_name_ja }}
          </h2>
          <div class="text-center mt-4">
            {{ itemDetail.work_title }} <br>
            {{ $t('ENTRY') }}
          </div>
        </div>
        <div class="content-detail">
          <div class="d-flex align-item-centers mt-4">
            <b-col cols="3">
              <label class="font-weight-bold">{{ $t('MOTIVATION') }}</label>
            </b-col>
            <b-col cols="9">
              <b-link target="_blank" :href="motivationLink">
                <u> {{ motivationName }} </u>
              </b-link>
            </b-col>
          </div>
          <div class="d-flex align-item-centers mt-4">
            <b-col cols="3">
              <label class="font-weight-bold">{{
                $t('LETTER_RECOMMENDATION')
              }}</label>
            </b-col>
            <b-col cols="9">
              <b-link target="_blank" :href="recommendationLink">
                <u> {{ recommendationName }} </u>
              </b-link>
            </b-col>
          </div>

          <template v-for="(element, index) in other_file">
            <div :key="index" class="d-flex align-item-centers mt-4">
              <b-col cols="3">
                <label class="font-weight-bold">
                  <!-- {{ $t(`その他ファイル${index + 1}`) }} -->
                  {{ $t('OTHER_FILES') + (fileIndex + 1) }}
                </label>
              </b-col>
              <b-col cols="9">
                <b-link target="_blank" :href="element.link_dowload">
                  <u>{{ element.file_name }}</u>
                  <br>
                </b-link>
              </b-col>
            </div>
          </template>

          <div class="d-flex align-item-centers mt-4">
            <b-col cols="3">
              <label class="font-weight-bold">{{ $t('REMARKS') }}</label>
            </b-col>
            <b-col cols="9">
              {{ itemDetail.remarks }}
            </b-col>
          </div>
          <template
            v-if="
              ![ROLE.TYPE_HR, ROLE.TYPE_HR_MANAGER].includes(permissionCheck)
            "
          >
            <div class="d-flex justify-content-center mt-4 font-weight-bold">
              {{ $t('TEXT_CONFIRM4') }}
            </div>
            <div class="d-flex flex-column align-items-center mt-5">
              <b-button
                id="btn-requesting-confirm"
                dusk="btn_requesting_confirm"
                variant="primary"
                class="w-25"
                @click="handleModalRequestingConfirm"
              >
                {{ $t('STATUS.CONFIRM') }}
              </b-button>
              <b-button
                id="btn-requesting-reject"
                dusk="btn_requesting_reject"
                variant="danger"
                class="w-25 text-white primary mt-2"
                @click="handleModalRequestingReject"
              >
                {{ $t('STATUS.REJECT') }}
              </b-button>
            </div>
          </template>
          <template
            v-if="
              [ROLE.TYPE_HR, ROLE.TYPE_HR_MANAGER].includes(permissionCheck)
            "
          >
            <div class="d-flex flex-column align-items-center mt-5">
              <b-button
                variant="danger"
                class="w-25 text-white primary mt-2"
                dusk="decline"
                @click="handleModalRequestingDecline"
              >
                {{
                  $t('BUTTON.BTN_TAB_4_MODAL_OFFER.BTN_COMFIRM_STATUS_DECLINE')
                }}
              </b-button>
            </div>
          </template>
        </div>
      </div>
    </modal-common>

    <!-- Modal Decline for only HR -->
    <modal-common
      :refs="'entry-modal-requesting-decline'"
      :size="'md'"
      @reset-modal="resetModal"
    >
      <template slot="body">
        <div id="dusk_group" class="content-modal pt-3">
          <div class="text-center">
            <h5 class="font-weight-medium text-danger">
              {{ $t('TITLE.TAB_4.LABEL_MODAL_REASON') }}
            </h5>
          </div>
          <b-form-group
            v-slot="{ ariaDescribedby }"
            :label="$t('TITLE.TAB_4.LABEL_MODAL_CONTENT')"
            class="mt-4 px-4 mb-0"
          >
            <b-form-radio-group
              v-model="declineFormData.fixReasonDecline"
              :options="reasonResultOptions"
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
              dusk="decline_note"
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
            >{{ $t('MODAL_BUTTON_CANCEL') }}</b-button>
            <b-button
              class="my-2 mt-5 mx-2 text-white"
              dusk="btn_decline"
              variant="danger"
              @click="handleDeclineEntry"
            >{{ $t('MODAL_BUTTON_DECLINE') }}
            </b-button>
          </div>
        </div>
      </template>
    </modal-common>

    <!-- entry modal-decline-detail -->
    <modal-common
      :refs="'entry-modal-decline'"
      :size="'md'"
      @reset-modal="resetModal"
    >
      <template slot="body">
        <h3 class="font-weight-bold text-center">
          {{ itemDetail.full_name }} <br>
          {{ itemDetail.full_name_ja }}
        </h3>
        <div class="text-center mt-4">
          {{ itemDetail.work_title }} <br>
          {{ $t('ENTRY') }}
        </div>
        <div class="content-detail text-center">
          <h6>{{ itemDetail.updating_date_ja }}</h6>
          <h3 class="text-danger mt-5">
            {{ $t('BUTTON.BTN_TAB_4_MODAL_OFFER.BTN_COMFIRM_STATUS_DECLINE') }}
          </h3>
          <p class="text-center">
            {{ itemDetail.note }}
          </p>
        </div>
      </template>
    </modal-common>

    <!-- Modal Requesting Confirm -->
    <modal-confirm
      id="entry-modal-requesting-confirm"
      :refs="'entry-modal-requesting-confirm'"
      :size="'md'"
      :type="'entry'"
      @handleSubmitModalConfirm="handleSubmitModalConfirm"
      @reset-modal="resetModal"
    >
      <div slot="body">
        <div class="text-center">
          <h5 class="font-weight-medium">{{ $t('TEXT_CONFIRM3_ENTRY') }}</h5>
        </div>
      </div>
    </modal-confirm>

    <!-- entry-modal-requesting-reject -->
    <modal-common
      :refs="'entry-modal-requesting-reject'"
      :size="'md'"
      @reset-modal="resetModal"
    >
      <div slot="body">
        <div class="text-center">
          <h5 class="text-danger">
            <!-- {{ $t('TEXT_CONFIRM1') }} -->
            {{ $t('INTERVIEW_CANCEL_MODAL_TITLE') }}
          </h5>
        </div>
        <div class="content-detail p-4">
          <!-- <h4 class="text-center mb-4">一次面接</h4> -->
          <form ref="form">
            <div class="d-flex align-items-center">
              <label class="mb-0 mr-2" for="textarea">
                <!-- {{ $t('TEXT_CONFIRM2') }} -->
                {{ $t('INTERVIEW_CANCEL_MODAL_LABEL') }}
              </label>
              <!-- <b-badge class="badge-not-required mx-2" variant="secondary">{{
                $t('ARBITRARY')
              }}</b-badge> -->
              <Arbitrarily />
            </div>
            <b-form-textarea
              id="textarea"
              v-model="rejectFormData.note"
              dusk="textarea"
              placeholder=""
              rows="6"
              max-rows="28"
              max-lengh="2000"
            />
          </form>

          <div class="d-flex align-items-center mt-5 justify-content-center">
            <b-button
              variant="secondary"
              class="mx-1"
              @click="handleCancelRejectEntry"
            >
              {{ $t('MODAL_BUTTON_CANCEL') }}
            </b-button>
            <b-button
              id="btn-handle-reject-entry"
              variant="danger"
              class="text-white mx-1"
              dusk="btn_handle_reject_entry"
              @click="handleRejectEntry"
            >
              {{ $t('STATUS.REJECT') }}
            </b-button>
          </div>
        </div>
      </div>
    </modal-common>

    <!-- entry modal-reject -->
    <modal-common
      id="entry-modal-reject"
      :refs="'entry-modal-reject'"
      :size="'lg'"
      @reset-modal="resetModal"
    >
      <template slot="body">
        <div class="text-center">
          <h3 class="font-weight-bold text-center">
            {{ itemDetail.full_name }} <br>
            {{ itemDetail.full_name_ja }}
          </h3>
        </div>
        <div class="text-center mt-4">
          {{ itemDetail.work_title }} <br>
          {{ $t('ENTRY') }}
        </div>
        <div class="content-detail">
          <h6 class="text-center">{{ itemDetail.updating_date_ja }}</h6>
          <div class="d-flex align-item-centers mt-4">
            <b-col cols="3">
              <label class="font-weight-bold">{{ $t('MOTIVATION') }}</label>
            </b-col>
            <b-col cols="9">
              <b-link target="_blank" :href="motivationLink">
                <u>
                  {{ motivationName }}
                </u>
              </b-link>
            </b-col>
          </div>
          <div class="d-flex align-item-centers mt-4">
            <b-col cols="3">
              <label class="font-weight-bold">{{
                $t('LETTER_RECOMMENDATION')
              }}</label>
            </b-col>
            <b-col cols="9">
              <b-link target="_blank" :href="recommendationLink">
                <u> {{ recommendationName }} </u>
              </b-link>
            </b-col>
          </div>

          <template v-for="(element, index) in other_file">
            <div :key="index" class="d-flex align-item-centers mt-4">
              <b-col cols="3">
                <label class="font-weight-bold">
                  <!-- {{ $t(`その他ファイル${index + 1}`) }} -->
                  {{ $t('OTHER_FILES') + (fileIndex + 1) }}
                </label>
              </b-col>
              <b-col cols="9">
                <b-link target="_blank" :href="element.link_dowload">
                  <u>{{ element.file_name }}</u>
                  <br>
                </b-link>
              </b-col>
            </div>
          </template>
          <div class="d-flex align-item-centers mt-4">
            <b-col cols="3">
              <label class="font-weight-bold">{{ $t('REMARKS') }}</label>
            </b-col>
            <b-col cols="9">
              {{ itemDetail.remarks }}
            </b-col>
          </div>
          <div class="d-flex justify-content-center mt-4 font-weight-bold">
            <h3 class="d-flex flex-column align-items-center mt-5 text-danger">
              {{ $t('STATUS.REJECT') }}
            </h3>
          </div>
          <p class="text-center">
            {{ itemDetail.note }}
          </p>
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
            dusk="btn_accept_delete_entry"
            @click="handleDeleteAllEntry"
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
import ROLE from '@/const/role.js';
import ModalCommon from '@/layout/components/ModalCommon/matching.vue';
import ModalConfirm from '@/layout/components/ModalCommon/confirm.vue';
import {
  deleteMultipleEntry,
  getDetailEntry,
  updateEntry,
} from '@/api/modules/matching.js';
import EventBus from '@/utils/eventBus';
import { reasonResultOptions, ENTRY_STATUS } from '@/const/matching.js';
import { MakeToast } from '@/utils/toastMessage';
import _ from 'lodash';
import Arbitrarily from '@/components/Arbitrarily/Arbitrarily.vue';

const HR_NAME_COL_WIDTH = '200px';
const WORK_TITLE_COL_WIDTH = '200px';

export default {
  name: 'Entry',
  components: {
    ModalCommon,
    ModalConfirm,
    Arbitrarily,
  },

  props: {
    dataEntry: {
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
      ROLE: ROLE,
      ENTRY_STATUS: ENTRY_STATUS,
      itemDetail: {},
      motivationName: null,
      motivationLink: null,
      recommendationName: null,
      recommendationLink: null,
      other_file: null,
      declineFormData: {
        note: '',
        fixReasonDecline: null,
        isShowNote: false,
      },
      rejectFormData: {
        remarks: '',
      },
      noSort: true,
      queryData: {
        // page: 1,
        // per_page: 20,
        // total_records: 0,
        // search: '',
        // order_type: '',
        // order_column: '',
      },

      fields: [
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
          label: this.$t('HEADER_ID'),
          class: 'bg-header',
          thStyle: {
            backgroundColor: '#1D266A',
            width: '80px',
            color: '#fff',
            textAlign: 'center',
          },
          thClass: 'text-center',
        },
        {
          key: 'entry_code',
          sortable: true,
          label: this.$t('HEADER_ID_ENTRY'),
          class: 'bg-header',
          thStyle: {
            backgroundColor: '#1D266A',
            width: '120px',
            color: '#fff',
            textAlign: 'center',
          },
        },
        {
          key: 'request_date',
          sortable: true,
          label: this.$t('HEADER_REQUEST_DATE_ENTRY_MATCHING'),
          class: 'bg-header',
          thStyle: {
            backgroundColor: '#1D266A',
            width: '120px',
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
            width: HR_NAME_COL_WIDTH, // '200px';
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
            width: WORK_TITLE_COL_WIDTH, // '200px',
            color: '#fff',
            textAlign: 'center',
          },
        },
        {
          key: 'updating_date',
          sortable: true,
          label: this.$t('HEADER_UPDATING_DATE_ENTRY_MATCHING'),
          class: 'bg-header',
          thStyle: {
            backgroundColor: '#1D266A',
            width: '120px',
            color: '#fff',
            textAlign: 'center',
          },
        },
        {
          key: 'status',
          sortable: true,
          label: this.$t('HEADER_STATUS_ENTRY_MATCHING'),
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
          label: this.$t('HEADER_DETAIL_ENTRY_MATCHING'),
          class: 'bg-header',
          thStyle: {
            width: '100px',
            backgroundColor: '#1D266A',
            color: '#fff',
            textAlign: 'center',
          },
          tdClass: 'text-center',
        },
      ],

      selectedItems: [],
      selectAll: false,
      reasonResultOptions: reasonResultOptions,
      listId: [],
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
    EventBus.$on('handleDeleteAllEntry', () => {
      this.handleCheckDelete();
      // this.handleDeleteAllEntry();
    });
  },

  destroyed() {
    EventBus.$off('handleDeleteAllEntry');
  },

  methods: {
    async goToDetail(item) {
      try {
        const response = await getDetailEntry(item.id);
        const { code, message } = response.data;
        if (code === 200) {
          const { data } = response.data;
          this.itemDetail = data;
          this.motivationName = _.get(data.file, 'motivation[0].file_name', '');
          this.motivationLink = _.get(
            data.file,
            'motivation[0].link_dowload',
            ''
          );
          this.other_file = data.file.other_file ?? [];
          this.recommendationName = _.get(
            data.file,
            'recommendation[0].file_name',
            ''
          );
          this.recommendationLink = _.get(
            data.file,
            'recommendation[0].link_dowload',
            ''
          );
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
      if (
        [
          this.ENTRY_STATUS.REQUESTING,
          this.ENTRY_STATUS.OTHER_COMPANY_OFFICAL_OFFER,
        ].includes(item.status)
      ) {
        EventBus.$emit('open-modal', 'entry-modal-requesting');
      } else if ([this.ENTRY_STATUS.DECLINE].includes(item.status)) {
        EventBus.$emit('open-modal', 'entry-modal-decline');
      } else if ([this.ENTRY_STATUS.REJECT].includes(item.status)) {
        EventBus.$emit('open-modal', 'entry-modal-reject');
      }
    },
    // Modal
    handleCloseModalRequesting() {
      this.$refs['entry-modal-requesting'].hide();
    },
    handleModalRequestingConfirm() {
      EventBus.$emit('open-modal', 'entry-modal-requesting-confirm');
    },
    handleModalRequestingReject() {
      EventBus.$emit('open-modal', 'entry-modal-requesting-reject');
    },
    handleCloseModalRequestingConfirm() {
      this.$refs['entry-modal-requesting-confirm'].hide();
    },

    selectItem(id) {
      if (this.listId.includes(id) && this.listId.length > 0) {
        this.listId.splice(this.listId.indexOf(id), 1);
      } else {
        this.listId.push(id);
      }
    },

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
      const tempArr = this.dataEntry.filter(
        (item) =>
          ![
            this.ENTRY_STATUS.REQUESTING,
            this.ENTRY_STATUS.OTHER_COMPANY_OFFICAL_OFFER,
          ].includes(item.status)
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
      const newArray = this.dataEntry.filter((element) => {
        return ![
          this.ENTRY_STATUS.REQUESTING,
          this.ENTRY_STATUS.OTHER_COMPANY_OFFICAL_OFFER,
        ].includes(element.status);
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

    async handleDeleteAllEntry() {
      this.statusModalConfirmDelAll = false;
      const ids = this.selectedItems.map((item) => {
        return item.id;
      });
      if (ids.length > 0) {
        try {
          const response = await deleteMultipleEntry({ ids: ids });
          const { code, message } = response.data;
          if (code === 200) {
            MakeToast({
              variant: 'success',
              title: this.$t('SUCCESS'),
              content: '削除しました',
            });
            this.selectAll = false;
            this.$emit('handleGetListEntry');
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
      } else {
        MakeToast({
          variant: 'warning',
          title: '危険',
          content: this.$t('PLEASE_SELECT_DATA'),
        });
      }
    },

    async handleRejectEntry() {
      const formData = {
        ...this.rejectFormData,
        id: this.itemDetail.id,
        // status: 2,
        status: this.ENTRY_STATUS.REJECT,
      };
      // console.log('rejectFormData formData: ', formData);

      const response = await updateEntry(formData);
      const { code, message } = response.data;
      if (code === 200) {
        MakeToast({
          variant: 'success',
          title: this.$t('SUCCESS'),
          content: message,
        });
        EventBus.$emit('close-modal', 'entry-modal-requesting-reject');
        EventBus.$emit('close-modal', 'entry-modal-requesting');
        this.$emit('handleGetListEntry');
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
        status: this.ENTRY_STATUS.CONFIRM,
      };
      const response = await updateEntry(params);
      const { code, message } = response.data;
      if (code === 200) {
        MakeToast({
          variant: 'success',
          title: this.$t('SUCCESS'),
          content: message,
        });
        EventBus.$emit('close-modal', 'entry-modal-requesting-confirm');
        EventBus.$emit('close-modal', 'entry-modal-requesting');
        this.$emit('handleGetListEntry');
        this.$emit('getListInterview');
      } else {
        MakeToast({
          variant: 'warning',
          title: 'warning',
          content: message,
        });
      }
    },

    handleCancelRejectEntry() {
      EventBus.$emit('close-modal', 'entry-modal-requesting-reject');
    },

    handleModalRequestingDecline() {
      EventBus.$emit('open-modal', 'entry-modal-requesting-decline');
    },

    checkvalidateNote() {
      if (!this.declineFormData.note) {
        this.error.note = false;
      } else {
        return true;
      }
    },

    handleChangeReasonDecline() {
      if (this.declineFormData.fixReasonDecline === 'その他') {
        this.declineFormData.isShowNote = true;
      } else {
        this.declineFormData.isShowNote = false;
      }
    },

    handleCancelReason() {
      EventBus.$emit('close-modal', 'entry-modal-requesting-decline');
    },

    async handleDeclineEntry() {
      const formData = {
        id: this.itemDetail.id,
        // status: 3,
        status: this.ENTRY_STATUS.DECLINE,
      };
      if (!this.declineFormData.fixReasonDecline) {
        MakeToast({
          variant: 'warning',
          title: this.$t('WARNING'),
          content: this.$t('WARNING_MESS.NOT_SELECT_DECLINE'),
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

      const response = await updateEntry(formData);
      const { code, message } = response.data;
      if (code === 200) {
        MakeToast({
          variant: 'success',
          title: this.$t('SUCCESS'),
          content: message,
        });
        EventBus.$emit('close-modal', 'entry-modal-requesting-decline');
        EventBus.$emit('close-modal', 'entry-modal-requesting');
        this.$emit('handleGetListEntry');
      } else {
        MakeToast({
          variant: 'warning',
          title: 'warning',
          content: message,
        });
      }
    },

    resetModal() {
      Object.assign(this.declineFormData, {
        note: '',
        fixReasonDecline: null,
        isShowNote: false,
      });
    },
    handleSortTable(ctx) {
      let customSortBy = ctx?.sortBy;
      if (customSortBy === 'updating_date') {
        customSortBy = 'updated_at';
      }
      if (customSortBy === 'id') {
        customSortBy = 'hr_id';
      }
      const sortQuery = {
        field: customSortBy,
        sort_by: ctx.sortDesc ? 'desc' : 'asc',
      };
      this.$emit('handleGetListEntry', sortQuery);
      this.selectAll = false;
      this.selectedItems = [];
    },

    onPageChange(page) {
      if (page) {
        this.queryData.current_page = page;
        this.$emit('handleGetListEntry');
        this.selectAll = false;
        this.selectedItems = [];
      }
    },
    changeSize(size) {
      if (size) {
        this.queryData.per_page = size;
        this.queryData.current_page = 1;
        this.$emit('handleGetListEntry');
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
