<template>
  <div>
    <b-table :fields="fields" :items="dataResult">
      <template #head(selected)="">
        <b-form-checkbox
          v-model="selectAll"
          @change="onSelectAllCheckboxChange"
        />
      </template>
      <template #cell(selected)="row">
        <b-form-checkbox
          v-model="row.item._isSelected"
          :disabled="![4].includes(row.item.status_selection)"
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
        </div>
      </template>
      <template #cell(status_selection)="status_selection">
        <span
          v-if="status_selection.value === 1"
          class="btn-status btn-pending"
        >
          {{ '内定' }}
        </span>
        <span
          v-if="status_selection.value === 2"
          class="btn-status btn-pending"
        >
          {{ '不合格' }}
        </span>
        <span
          v-if="status_selection.value === 3"
          class="btn-status btn-confirm"
        >
          {{ '内定承諾' }}
        </span>
        <span
          v-if="status_selection.value === 4"
          class="btn-status btn-decline"
        >
          {{ '内定辞退' }}
        </span>
      </template>
      <template #cell(time)="time">
        <span v-if="time.value === -1" class="btn-status btn-pending">
          {{ '期限切れ' }}
        </span>
        <span v-else>
          {{ time.value }}
        </span>
      </template>
      <template #cell(detail)="row">
        <button class="btn-go-detail" @click="goToDetail(row.item)">
          <i class="fas fa-eye icon-detail" />
        </button>
      </template>
    </b-table>

    <!-- result-modal-offer-confirm -->
    <modal-common :refs="'result-modal-offer-confirm'" :size="'md'">
      <template slot="body">
        <div class="text-center">
          <h2 class="font-weight-bold">
            Nguyen Thi Nhi <br>
            ｸﾞｴﾝ ﾃｨ ﾆｰ
          </h2>
          <h5>
            電気設計エンジニア <br>
            《エントリー》
          </h5>
        </div>

        <div class="content-detail px-4 pt-3 pb-4 text-center">
          <h6 class="text-center">2023年3月27日（月）</h6>
          <h3 class="text-center font-weight-bold">内定</h3>
          <div class="text-center font-weight-bold">
            回答期限：4月10日（月）
          </div>
          <div class="d-flex flex-column align-items-center mt-4">
            <b-button
              variant="primary"
              class="w-25"
              @click="handleModalOfferConfirmApprove"
            >
              {{ $t('BUTTON.BTN_TAB_4_MODAL_OFFER.BTN_COMFIRM_DETAIL') }}
            </b-button>
            <b-button
              variant="danger"
              class="w-25 mt-2"
              @click="handleModalOfferConfirmDecline"
            >
              {{
                $t('BUTTON.BTN_TAB_4_MODAL_OFFER.BTN_COMFIRM_STATUS_DECLINE')
              }}
            </b-button>
          </div>
        </div>

        <div class="content-detail px-4 pt-3 pb-4 mt-3 text-center">
          <h3 class="text-center">最終面接</h3>
          <h6 class="text-center">個別面接</h6>
          <h3 class="text-center font-weight-bold">合格</h3>
          <div class="d-flex flex-column align-items-center mt-4">
            <h5>2023年4月6日（木）11:00〜12:00</h5>
            <b-link>
              <u> https://us02web.zoom.us/j/987654321 </u>
            </b-link>
          </div>
          <div class="d-flex flex-column align-items-center mt-4">
            <p>
              ミーティングID：987654321 <br>
              パスコード：Lr20JAs47
            </p>
          </div>
        </div>
        <!--  -->
        <div class="content-detail px-4 pt-3 pb-4 mt-3 text-center">
          <h3 class="text-center">二次面接</h3>
          <h6 class="text-center">個別面接</h6>
          <h3 class="text-center font-weight-bold">合格</h3>
          <div class="d-flex flex-column align-items-center mt-4">
            <h5>2023年4月6日（木）11:00〜12:00</h5>
            <b-link>
              <u> https://us02web.zoom.us/j/987654321 </u>
            </b-link>
          </div>
          <div class="d-flex flex-column align-items-center mt-4">
            <p>
              ミーティングID：987654321 <br>
              パスコード：Lr20JAs47
            </p>
          </div>
        </div>
        <!--  -->
        <div class="content-detail px-4 pt-3 pb-4 mt-3 text-center">
          <h3 class="text-center">一次面接</h3>
          <h6 class="text-center">個別面接</h6>
          <h3 class="text-center font-weight-bold">合格</h3>
          <div class="d-flex flex-column align-items-center mt-4">
            <h5>2023年4月6日（木）11:00〜12:00</h5>
            <b-link>
              <u> https://us02web.zoom.us/j/987654321 </u>
            </b-link>
          </div>
          <div class="d-flex flex-column align-items-center mt-4">
            <p>
              ミーティングID：987654321 <br>
              パスコード：Lr20JAs47
            </p>
          </div>
        </div>
      </template>
    </modal-common>

    <!-- result-modal-offer-confirm-approve -->
    <modal-confirm :refs="'result-modal-offer-confirm-approve'" :size="'md'">
      <div slot="body">
        <div class="text-center">
          <h4 class="">{{ $t('TEXT_CONFIRM3') }}</h4>
          <h6 class="text-center">
            承諾をすると他の面接等はすべてキャンセルになります。<br>
            他の求人へエントリーもできなくなり、<br>
            企業方のオファーも受け取れなくなります。
          </h6>
        </div>
      </div>
    </modal-confirm>

    <!-- result-modal-offer-confirm-decline -->
    <modal-common :refs="'result-modal-offer-confirm-decline'" :size="'md'">
      <template slot="body">
        <div class="content-modal pt-3">
          <div class="text-center">
            <h4 class="font-weight-medium text-danger">
              {{ $t('TITLE.TAB_4.LABEL_MODAL_REASON') }}
            </h4>
          </div>
          <b-form-group
            v-slot="{ ariaDescribedby }"
            :label="$t('TITLE.TAB_4.LABEL_MODAL_CONTENT')"
            class="mt-4 px-4"
          >
            <b-form-radio-group
              v-model="selectReasonDecline"
              :options="reasonResultOptions"
              :aria-describedby="ariaDescribedby"
              :name="$t('TITLE.TAB_4.LABEL_MODAL_CONTENT')"
              stacked
              @change="handleChangeReasonDecline"
            />
            <b-form-textarea
              v-if="isShowOtherReason"
              class="mt-4"
              size="lg"
              :rows="5"
            />
          </b-form-group>

          <div class="text-center">
            <b-button
              class="mt-5 mx-1 text-white"
              variant="secondary"
              @click="'';"
            >
              {{ $t('MODAL_BUTTON_CANCEL') }}
            </b-button>
            <b-button
              class="mt-5 mx-1 text-white"
              variant="danger"
              @click="'';"
            >
              {{ $t('MODAL_BUTTON_DECLINE') }}
            </b-button>
          </div>
        </div>
      </template>
    </modal-common>

    <!-- result-modal-offer -->
    <modal-common :refs="'result-modal-offer'" :size="'md'">
      <template slot="body">
        <div class="text-center">
          <h2 class="font-weight-bold">
            Nguyen Thi Nhi <br>
            ｸﾞｴﾝ ﾃｨ ﾆｰ
          </h2>
          <h5>
            電気設計エンジニア <br>
            《エントリー》
          </h5>
        </div>

        <div class="content-detail px-4 pt-3 pb-4 text-center">
          <h6 class="text-center">2023年4月8日（土）</h6>
          <h3 class="text-center font-weight-bold text-primary">内定承諾</h3>
          <div class="text-center font-weight-bold">入社日</div>
          <div class="d-flex flex-column align-items-center mt-2">
            <b-form-input v-if="step === 1" type="date" class="w-75" />
            <h4 v-if="step === 2" class="text-center">2023年5月1日（月）</h4>
            <b-button
              v-if="step === 1"
              variant="warning"
              class="w-25 mt-5 text-white"
              @click="handleNextStep"
            >
              {{ $t('BUTTON.SAVE') }}
            </b-button>

            <b-button
              v-if="step === 2"
              variant="warning"
              class="w-25 mt-5 text-white"
              @click="handleBack"
            >
              {{ $t('BUTTON_EDIT') }}
            </b-button>
          </div>
        </div>
        <!--  -->
        <div class="content-detail px-4 pt-3 pb-4 mt-3 text-center">
          <h3 class="text-center">最終面接</h3>
          <h6 class="text-center">個別面接</h6>
          <h3 class="text-center font-weight-bold">合格</h3>
          <div class="d-flex flex-column align-items-center mt-4">
            <h5>2023年4月6日（木）11:00〜12:00</h5>
            <b-link>
              <u> https://us02web.zoom.us/j/987654321 </u>
            </b-link>
          </div>
          <div class="d-flex flex-column align-items-center mt-4">
            <p>
              ミーティングID：987654321 <br>
              パスコード：Lr20JAs47
            </p>
          </div>
        </div>
        <!--  -->
        <div class="content-detail px-4 pt-3 pb-4 mt-3 text-center">
          <h3 class="text-center">二次面接</h3>
          <h6 class="text-center">個別面接</h6>
          <h3 class="text-center font-weight-bold">合格</h3>
          <div class="d-flex flex-column align-items-center mt-4">
            <h5>2023年4月6日（木）11:00〜12:00</h5>
            <b-link>
              <u> https://us02web.zoom.us/j/987654321 </u>
            </b-link>
          </div>
          <div class="d-flex flex-column align-items-center mt-4">
            <p>
              ミーティングID：987654321 <br>
              パスコード：Lr20JAs47
            </p>
          </div>
        </div>
        <!--  -->
        <div class="content-detail px-4 pt-3 pb-4 mt-3 text-center">
          <h3 class="text-center">一次面接</h3>
          <h6 class="text-center">集団面接</h6>
          <h3 class="text-center font-weight-bold">合格</h3>
          <div class="d-flex flex-column align-items-center mt-4">
            <h5>2023年4月6日（木）11:00〜12:00</h5>
            <b-link>
              <u> https://us02web.zoom.us/j/987654321 </u>
            </b-link>
          </div>
          <div class="d-flex flex-column align-items-center mt-4">
            <p>
              ミーティングID：987654321 <br>
              パスコード：Lr20JAs47
            </p>
          </div>
        </div>
      </template>
    </modal-common>
  </div>
</template>

<script>
import ModalConfirm from '@/layout/components/ModalCommon/confirm.vue';
import ModalCommon from '@/layout/components/ModalCommon/matching.vue';
import { MakeToast } from '@/utils/toastMessage';
import { deleteMultipleResult } from '@/api/modules/matching.js';
import { reasonResultOptions } from '@/const/matching.js';

export default {
  name: 'Result',
  components: {
    ModalConfirm,
    ModalCommon,
  },

  props: {
    dataResult: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      itemDetail: '',

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
            width: '120px',
            backgroundColor: '#1D266A',
            color: '#fff',
            textAlign: 'center',
          },
          thClass: 'text-center',
        },
        {
          key: 'code',
          sortable: true,
          label: this.$t('HEADER_ID_ENTRY'),
          class: 'bg-header',
          thStyle: {
            width: '120px',
            backgroundColor: '#1D266A',
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
            width: '120px',
            backgroundColor: '#1D266A',
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
            width: '190px',
            color: '#fff',
            textAlign: 'center',
          },
        },
        {
          key: 'job_title',
          sortable: true,
          label: this.$t('HEADER_JOB_LIST_ENTRY_MATCHING'),
          class: 'bg-header',
          thStyle: {
            width: '190px',
            backgroundColor: '#1D266A',
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
            width: '140px',
            backgroundColor: '#1D266A',
            color: '#fff',
            textAlign: 'center',
          },
          tdClass: 'text-center',
        },
        {
          key: 'time',
          sortable: true,
          // label: this.$t('HEADER_REQUEST_DATE_ENTRY_MATCHING'),
          label: '期限',
          class: 'bg-header',
          thStyle: {
            width: '120px',
            backgroundColor: '#1D266A',
            color: '#fff',
            textAlign: 'center',
          },
        },
        {
          key: 'detail',
          label: this.$t('HEADER_DETAIL_ENTRY_MATCHING'),
          class: 'bg-header',
          thStyle: {
            width: '59px',
            backgroundColor: '#1D266A',
            color: '#fff',
            textAlign: 'center',
          },
          tdClass: 'text-center',
        },
      ],

      reasonResultOptions: reasonResultOptions,
      selectedItems: [],
      selectAll: false,
      selectReasonDecline: null,
      isShowOtherReason: false,
      step: 1,
    };
  },

  computed: {
    // listUser() {
    //   return this.$store.getters.listUser;
    // },
  },

  watch: {
    // currChange() {
    //   this.getListAllData();
    // },
  },

  created() {
    this.$bus.on('handleDeleteAllResult', () => {
      this.handleDeleteAllResult();
    });
  },

  methods: {
    handleNextStep(e) {
      e.preventDefault();
      this.checkvalidate();
      console.log('handleNextStep', this.formData);
      this.step++;
      if (this.checkvalidate() === true) {
        console.log('Vào đây');
      } else {
        e.stopPropagation();
      }
    },

    handleBack() {
      this.step--;
      if (this.step === 0) {
        this.$bus.emit('close-modal');
        this.step++;
      }
    },

    checkvalidate() {
      // if (this.formData.occupation === '') {
      //   this.error.occupation = false;
      // }
    },

    handleChangeReasonDecline() {
      if (this.selectReasonDecline === 'その他') {
        this.isShowOtherReason = true;
      } else {
        this.isShowOtherReason = false;
      }
    },
    goToDetail(item) {
      if ([1].includes(item.status)) {
        this.$bus.emit('open-modal', 'result-modal-offer-confirm');
      }
      if ([3].includes(item.status)) {
        this.$bus.emit('open-modal', 'result-modal-offer');
      }
    },

    handleModalOfferConfirmApprove() {
      this.$bus.emit('open-modal', 'result-modal-offer-confirm-approve');
    },

    handleModalOfferConfirmDecline() {
      this.$bus.emit('open-modal', 'result-modal-offer-confirm-decline');
    },

    onSelectAllCheckboxChange() {
      const tempArr = this.dataResult.filter(
        (item) => ![1, 2, 3].includes(item.status_selection)
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
      const newArray = this.dataResult.filter((element) => {
        return ![1, 2, 3].includes(element.status_selection);
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

    async handleDeleteAllResult() {
      const ids = this.selectedItems.map((item) => {
        return item.id;
      });
      if (ids.length > 0) {
        try {
          const response = await deleteMultipleResult({ ids: ids });
          const { code, message } = response.data;
          if (code === 200) {
            MakeToast({
              variant: 'success',
              title: 'success',
              content: message,
            });
            this.selectAll = false;
            this.$emit('handleGetListResult');
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
@import '@/scss/modules/MatchingManagement/Header.scss';

.content-detail {
  background-color: #f9f9ff;
  padding: 1.5rem 4rem;
  margin-top: 2rem;
}
</style>
