<template>
  <div>
    <b-table :fields="fields" :items="dataEntry">
      <template #head(selected)="">
        <b-form-checkbox
          v-model="selectAll"
          @change="onSelectAllCheckboxChange"
        />
      </template>
      <template #cell(selected)="row">
        <b-form-checkbox
          v-model="row.item._isSelected"
          :disabled="![2, 3].includes(row.item.status)"
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
      <template #cell(status)="status">
        <span v-if="status.value === 2" class="btn-status btn-decline">
          {{ $t('STATUS.REJECT') }}
        </span>
        <span v-if="status.value === 1" class="btn-status btn-pending">
          {{ $t('STATUS.REQUESTING') }}
        </span>
        <span v-if="status.value === 3" class="btn-status btn-decline">
          {{ $t('STATUS.DECLINE') }}
        </span>
        <span v-if="status.value === 4" class="btn-status btn-pending">
          {{ $t('STATUS.ORTHER_COMPANY_OFFICIAL_OFFER') }}
        </span>
      </template>
      <template #cell(detail)="row">
        <button class="btn-go-detail" @click="goToDetail(row.item)">
          <i class="fas fa-eye icon-detail" />
        </button>
      </template>
    </b-table>

    <!-- Modal Requesting -->
    <modal-common :refs="'entry-modal-requesting'" :size="'lg'">
      <div slot="body">
        <div class="text-center">
          <h2 class="font-weight-bold">
            {{ itemDetail.full_name }} <br>
            {{ itemDetail.full_name_ja }}
          </h2>
          <h5>
            {{ itemDetail.work_title }} <br>
            《エントリー》
          </h5>
        </div>
        <div class="content-detail">
          <div class="d-flex align-item-centers mt-4">
            <b-col cols="3">
              <label class="font-weight-bold">{{ $t('MOTIVATION') }}</label>
            </b-col>
            <b-col cols="9">
              <b-link>
                <u>
                  {{ itemDetail.file?.motivation[0]['file_name'] }}
                </u>
              </b-link>
            </b-col>
          </div>
          <div class="d-flex align-item-centers mt-4">
            <b-col cols="3">
              <label class="font-weight-bold">{{ $t('TEXT_CONFIRM5') }}</label>
            </b-col>
            <b-col cols="9">
              <b-link
                v-for="(element, index) in itemDetail.file?.other_file"
                :key="index"
              >
                <u> {{ element['file_name'] }} </u>
                <br>
              </b-link>
            </b-col>
          </div>
          <div class="d-flex align-item-centers mt-4">
            <b-col cols="3">
              <label class="font-weight-bold">{{ $t('REMARKS') }}</label>
            </b-col>
            <b-col cols="9" class="font-weight-bold">
              {{ itemDetail.note }}
            </b-col>
          </div>
          <div class="d-flex justify-content-center mt-4 font-weight-bold">
            {{ $t('TEXT_CONFIRM4') }}
          </div>
          <div class="d-flex flex-column align-items-center mt-5">
            <b-button
              variant="primary"
              class="w-25"
              @click="handleModalRequestingConfirm"
            >
              {{ $t('STATUS.CONFIRM') }}
            </b-button>
            <b-button
              variant="danger"
              class="w-25 text-white primary mt-2"
              @click="handleModalRequestingReject"
            >
              {{ $t('STATUS.REJECT') }}
            </b-button>
          </div>
        </div>
      </div>
    </modal-common>

    <!-- Modal Requesting Confirm -->
    <modal-confirm
      :refs="'entry-modal-requesting-confirm'"
      :size="'md'"
      @handleSubmitModalConfirm="handleSubmitModalConfirm"
      @reset-modal="resetModal"
    >
      <div slot="body">
        <div class="text-center">
          <h5 class="font-weight-medium">{{ $t('TEXT_CONFIRM3') }}</h5>
        </div>
      </div>
    </modal-confirm>

    <!-- entry-modal-requesting-reject -->
    <modal-common :refs="'entry-modal-requesting-reject'" :size="'md'">
      <div slot="body">
        <div class="text-center">
          <h5 class="text-danger">
            {{ $t('TEXT_CONFIRM1') }}
          </h5>
        </div>
        <div class="content-detail p-4">
          <h4 class="text-center mb-4">一次面接</h4>
          <form ref="form">
            <div class="d-flex align-items-center">
              <label class="mb-0" for="textarea">{{
                $t('TEXT_CONFIRM2')
              }}</label>
              <b-badge
                class="badge-not-required mx-2"
                variant="secondary"
              >任意</b-badge>
            </div>
            <b-form-textarea
              id="textarea"
              v-model="rejectFormData.note"
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
              variant="danger"
              class="text-white mx-1"
              @click="handleRejectEntry"
            >
              {{ $t('MODAL_BUTTON_CANCEL_INTERVIEW') }}</b-button>
          </div>
        </div>
      </div>
    </modal-common>

    <!-- entry modal-decline -->
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
  </div>
</template>

<script>
import ModalCommon from '@/layout/components/ModalCommon/matching.vue';
import ModalConfirm from '@/layout/components/ModalCommon/confirm.vue';
import {
  deleteMultipleEntry,
  getDetailEntry,
  updateEntry,
} from '@/api/modules/matching.js';
import { MakeToast } from '@/utils/toastMessage';

export default {
  name: 'Entry',
  components: {
    ModalCommon,
    ModalConfirm,
  },

  props: {
    dataEntry: {
      type: Array,
      default: () => [],
    },
  },

  data() {
    return {
      itemDetail: {},

      rejectFormData: {
        note: '',
      },
      noSort: true,

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
            backgroundColor: '#1D266A',
            width: '120px',
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
            width: '200px',
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
            width: '200px',
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
            width: '59px',
            backgroundColor: '#1D266A',
            color: '#fff',
            textAlign: 'center',
          },
          tdClass: 'text-center',
        },
      ],

      selectedItems: [],
      selectAll: false,
      listId: [],
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
    this.$bus.on('handleDeleteAllEntry', () => {
      this.handleDeleteAllEntry();
    });
  },

  methods: {
    async goToDetail(item) {
      try {
        const response = await getDetailEntry(item.id);
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

      if ([1, 4].includes(item.status)) {
        this.$bus.emit('open-modal', 'entry-modal-requesting');
      } else if ([3].includes(item.status)) {
        this.$bus.emit('open-modal', 'entry-modal-decline');
      }
    },
    // Modal
    handleCloseModalRequesting() {
      this.$refs['entry-modal-requesting'].hide();
    },
    handleModalRequestingConfirm() {
      this.$bus.emit('open-modal', 'entry-modal-requesting-confirm');
      // this.$refs['entry-modal-requesting-confirm'].show();
    },
    handleModalRequestingReject() {
      this.$bus.emit('open-modal', 'entry-modal-requesting-reject');
      // this.$refs['entry-modal-requesting-reject'].show();
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
        (item) => ![1, 4].includes(item.status)
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
        return ![1, 4].includes(element.status);
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

    async handleDeleteAllEntry() {
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
              title: 'success',
              content: message,
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

    async handleRejectEntry() {
      const formData = {
        ...this.rejectFormData,
        id: this.itemDetail.id,
        status: 2,
      };
      const response = await updateEntry(formData);
      const { code, message } = response.data;
      if (code === 200) {
        MakeToast({
          variant: 'success',
          title: 'success',
          content: message,
        });
        this.$bus.emit('close-modal');
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
        status: 1,
      };
      const response = await updateEntry(params);
      const { code, message } = response.data;
      if (code === 200) {
        MakeToast({
          variant: 'success',
          title: 'success',
          content: message,
        });
        this.$bus.emit('close-modal');
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
      console.log('resetModal');
    },

    handleCancelRejectEntry() {
      this.$bus.emit('close-modal', 'entry-modal-requesting-reject');
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
