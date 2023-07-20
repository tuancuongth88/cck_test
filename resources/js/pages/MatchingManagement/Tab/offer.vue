<template>
  <div>
    <b-table :fields="fieldsOffer" :items="dataOffer">
      <template #head(selected)="">
        <b-form-checkbox
          v-model="selectAll"
          @change="onSelectAllCheckboxChange"
        />
      </template>
      <template #cell(selected)="row">
        <b-form-checkbox
          v-model="row.item._isSelected"
          :disabled="row.item.status_selection === 1"
          @change="onItemCheckboxChange(row.item)"
        />
      </template>
      <template #cell(full_name)="row">
        <div class="w-100 justify-space-between flex-column">
          <div class="w-100 justify-space-between flex-column">
            <div class="text-overflow-ellipsis">
              {{ row.item.full_name }}
            </div>
            <div class="text-overflow-ellipsis">
              {{ row.item.full_name_ja }}
            </div>
          </div>
          <!-- <img
            v-if="fullname.item.favorite"
            :src="require('@/assets/images/login/heart.png')"
            alt="heart"
          > -->
        </div>
      </template>
      <template #cell(status_selection)="status_selection">
        <!-- <span v-if="status_selection.status_selection === '却下'" class="btn-status btn-decline">
          {{ $t('STATUS.REJECT') }}
        </span> -->
        <span
          v-if="status_selection.value === 2"
          class="btn-status btn-decline"
        >
          {{ $t('STATUS.DECLINE') }}
        </span>
        <span
          v-if="status_selection.value === 1"
          class="btn-status btn-pending"
        >
          {{ $t('STATUS.REQUESTING') }}
        </span>
        <!-- <span v-if="status.status_selection === '他社内定'" class="btn-status btn-pending">
          {{ $t('STATUS.ORTHER_COMPANY_OFFICIAL_OFFER') }}
        </span> -->
      </template>
      <template #cell(detail)="row">
        <button class="btn-go-detail" @click="goToDetail(row.item)">
          <i class="fas fa-eye icon-detail" />
        </button>
      </template>
    </b-table>

    <!-- Modal Decline -->
    <modal-common :refs="'offer-modal-decline'" :size="'md'">
      <template slot="body">
        <h3 class="font-weight-bold text-center">
          {{ itemDetail.full_name }} <br>
          {{ itemDetail.full_name_ja }}
        </h3>
        <div class="text-center mt-4">
          {{ itemDetail.work_title }} <br>
          <br>
          《オファー》
          <h3 class="text-danger mt-5">辞退</h3>
          <p>辞退理由</p>
        </div>
        <p class="px-5">
          <!-- 私の希望する条件と合わなかったので却下させていただきました。また機会があればよろしくお願いいたします。 -->
          {{ itemDetail.note }}
        </p>
      </template>
    </modal-common>

    <!-- Modal Requesting -->
    <modal-common :refs="'offer-modal-requesting'" :size="'md'">
      <template slot="body">
        <h3 class="font-weight-bold text-center">
          {{ itemDetail.full_name }} <br>
          {{ itemDetail.full_name_ja }}
        </h3>
        <div class="text-center mt-4">
          {{ itemDetail.work_title }} <br>
          《オファー》
        </div>
        <div class="content-detail">
          <h6 class="text-center font-weigth-bold mb-5">
            {{ $t('TEXT_CONFIRM6') }}
          </h6>
          <div class="d-flex flex-column align-items-center">
            <b-button
              variant="primary"
              class="w-25"
              @click="handleModalRequestingConfirm"
            >
              {{ $t('BUTTON.BTN_TAB_4_MODAL_OFFER.BTN_COMFIRM_DETAIL') }}
            </b-button>
            <b-button
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
      </template>
    </modal-common>

    <!-- offer-modal-requesting-confirm -->
    <modal-confirm
      :refs="'offer-modal-requesting-confirm'"
      @handleSubmitModalConfirm="handleSubmitModalConfirm"
      @reset-modal="resetModal"
    >
      <div slot="body">
        <h6 class="mb-5 text-center">{{ $t('TEXT_CONFIRM3') }}</h6>
      </div>
    </modal-confirm>

    <!-- Modal Requesting Decline -->
    <modal-common :refs="'offer-modal-requesting-decline'" :size="'md'">
      <template slot="body">
        <div class="content-modal pt-3">
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
              size="lg"
              :rows="5"
            />
          </b-form-group>

          <div class="text-center">
            <b-button
              class="my-2 mt-5 mx-2"
              variant="secondary"
              @click="handleCancelReason"
            >{{ $t('MODAL_BUTTON_CANCEL') }}</b-button>
            <b-button
              class="my-2 mt-5 mx-2 text-white"
              variant="danger"
              @click="handleDeclineOffer"
            >{{ $t('MODAL_BUTTON_DECLINE') }}
            </b-button>
          </div>
        </div>
      </template>
    </modal-common>
  </div>
</template>

<script>
import ModalConfirm from '@/layout/components/ModalCommon/confirm.vue';
import ModalCommon from '@/layout/components/ModalCommon/matching.vue';
import {
  deleteMultipleOffer,
  getDetailOffer,
  updateOffer,
} from '@/api/modules/matching.js';
import { MakeToast } from '@/utils/toastMessage';
import { reasonOfferOptions } from '@/const/matching.js';

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
  },

  data() {
    return {
      itemDetail: '',

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

      queryData: {
        page: 1,
        per_page: 20,
        total_records: 0,
        search: '',
        order_type: '',
        order_column: '',
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
            width: '60px',
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
            width: '160px',
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
            width: '240px',
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
            width: '240px',
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
            width: '89px',
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
    };
  },

  computed: {
    listUser() {
      return this.$store.getters.listUser;
    },
  },

  watch: {
    currChange() {
      // this.getListAllData();
    },
  },

  created() {
    // this.getListAllData();
    this.$bus.on('handleDeleteAllOffer', () => {
      this.handleDeleteAllOffer();
    });
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
      if ([2].includes(item.status_selection)) {
        this.$bus.emit('open-modal', 'offer-modal-decline');
      } else {
        // 申請中
        this.$bus.emit('open-modal', 'offer-modal-requesting');
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
        (item) => ![1].includes(item.status_selection)
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
        return ![1].includes(element.status_selection);
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

    async handleDeleteAllOffer() {
      console.log('phát ra cho thằng offer');
      const ids = this.selectedItems.map((item) => {
        return item.id;
      });
      if (ids.length > 0) {
        try {
          const response = await deleteMultipleOffer({ offer_id: ids });
          const { code, message } = response.data;
          if (code === 200) {
            MakeToast({
              variant: 'success',
              title: 'success',
              content: message,
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
          content: 'You not have selected any!',
        });
      }
    },

    handleModalRequestingConfirm() {
      // this.$refs['offer-modal-requesting-confirm'].show();
      this.$bus.emit('open-modal', 'offer-modal-requesting-confirm');
    },

    handleModalRequestingDecline() {
      this.$bus.emit('open-modal', 'offer-modal-requesting-decline');
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

    resetModal() {
      console.log('resetModal');
    },

    async handleDeclineOffer() {
      const formData = {
        id: this.itemDetail.id,
        status: 2,
        note: '',
      };
      if (this.declineFormData.fixReasonDecline === 'その他') {
        formData.note = this.declineFormData.note;
      } else {
        formData.note = this.declineFormData.fixReasonDecline;
      }
      const response = await updateOffer(formData);
      const { code, message } = response.data;
      if (code === 200) {
        MakeToast({
          variant: 'success',
          title: 'success',
          content: message,
        });
        this.$bus.emit('close-modal');
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
        status: 3,
      };
      const response = await updateOffer(params);
      const { code, message } = response.data;
      if (code === 200) {
        MakeToast({
          variant: 'success',
          title: 'success',
          content: message,
        });
        this.$bus.emit('close-modal');
        this.$emit('handleGetListOffer');
      } else {
        MakeToast({
          variant: 'warning',
          title: 'warning',
          content: message,
        });
      }
    },

    handleCancelReason() {
      this.$bus.emit('close-modal', 'offer-modal-requesting-decline');
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/common/common.scss';
@import '@/scss/modules/MatchingManagement/Header.scss';

.content-detail {
  background-color: #f9f9ff;
  padding: 1.5rem 4rem;
  margin-top: 2rem;
}
</style>
