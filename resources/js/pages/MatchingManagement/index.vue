<template>
  <div class="display-user-management-list">
    <b-row class="mb-4">
      <b-col v-if="!sidebarExists" cols="3" class="hr-list__search">
        <HrFormSearch @handleSearch="handleSearch" />
      </b-col>
      <b-col :cols="!sidebarExists ? 9 : 12">
        <div class="btn collapse-bar-btn" @click="checkSideBar">
          <div :class="{ 'rotate-180deg': sidebarExists }">
            <img
              alt="collapse"
              :src="require('@/assets/images/login/chervon-collase-bar.png')"
            >
          </div>
        </div>
        <b-row class="mb-4">
          <b-col
            cols="12"
            class="d-flex justify-content-between align-items-center"
          >
            <div class="hr-list-head-title">
              <div class="line" />
              <div>
                {{ $t('TITLE.JOB_LIST') }}
              </div>
            </div>
            <div>
              <b-button
                variant="outline-dark"
                @click="handleDeleteAll"
              >{{ $t('BUTTON.DELETE_ALL') }}
                <b-icon icon="trash-fill" />
              </b-button>
            </div>
          </b-col>
        </b-row>
        <b-row class="nav-bar">
          <b-col cols="12" class="p-0">
            <b-overlay
              :show="overlay.show"
              :blur="overlay.blur"
              :rounded="overlay.sm"
              :variant="overlay.variant"
              :opacity="overlay.opacity"
            >
              <template #overlay>
                <div class="text-center">
                  <b-icon
                    icon="arrow-clockwise"
                    font-scale="3"
                    animation="spin"
                  />
                  <p style="margin-top: 10px">
                    {{ $t('PLEASE_WAIT') }}
                  </p>
                </div>
              </template>
              <b-tabs v-model="tabIndex" class="tab-select" fill>
                <b-tab
                  :title="$t('TAB.ENTRY')"
                  :title-link-class="linkClass(0)"
                >
                  <div :class="!sidebarExists ? 'over-flow' : ''">
                    <div class="hr-list-table-list__table">
                      <entry
                        :data-entry="dataEntry"
                        @handleGetListEntry="handleGetListEntry"
                      />
                    </div>
                  </div>
                </b-tab>
                <b-tab
                  :title="$t('TAB.OFFER')"
                  :title-link-class="linkClass(1)"
                >
                  <div :class="!sidebarExists ? 'over-flow' : ''">
                    <div class="hr-list-table-list__table">
                      <offer
                        :data-offer="dataOffer"
                        @handleGetListOffer="handleGetListOffer"
                      />
                    </div>
                  </div>
                </b-tab>
                <b-tab
                  :title="$t('TAB.INTERVIEW')"
                  :title-link-class="linkClass(2)"
                >
                  <div :class="!sidebarExists ? 'over-flow' : ''">
                    <div class="hr-list-table-list__table">
                      <interview />
                    </div>
                  </div>
                </b-tab>
                <b-tab
                  :title="$t('TAB.RESULT')"
                  :title-link-class="linkClass(3)"
                >
                  <div :class="!sidebarExists ? 'over-flow' : ''">
                    <div class="hr-list-table-list__table">
                      <result
                        :data-result="dataResult"
                        @handleGetListResult="handleGetListResult"
                      />
                    </div>
                  </div>
                </b-tab>
              </b-tabs>
            </b-overlay>
          </b-col>
        </b-row>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import Entry from './Tab/entry.vue';
import Offer from './Tab/offer.vue';
import Interview from './Tab/interview.vue';
import Result from './Tab/result.vue';
import HrFormSearch from '@/layout/components/search/HrFormSearch.vue';
import { MakeToast } from '@/utils/toastMessage';
import {
  getListEntry,
  getListOffer,
  getListResult,
} from '@/api/modules/matching.js';

export default {
  name: 'MatchingManagement',
  components: {
    HrFormSearch,
    Entry,
    Offer,
    Interview,
    Result,
  },
  data() {
    return {
      dataEntry: [],
      dataOffer: [],
      dataResult: [],
      tabIndex: 0,
      sidebarExists: false,

      message: {
        isShowMessage: false,
        isMessage: '',
      },

      overlay: {
        opacity: 0,
        show: false,
        blur: '1rem',
        rounded: 'sm',
        variant: 'light',
        fixed: true,
      },

      queryData: {
        page: 1,
        per_page: 20,
        total_records: 0,
        search: '',
        order_type: '',
        order_column: '',
      },

      selectAll: false,
    };
  },

  computed: {
    listUser() {
      return this.$store.getters.listUser;
    },
    currChange() {
      return this.queryData.page;
    },
  },

  watch: {
    currChange() {
      // this.getListAllData();
    },
  },

  created() {
    this.handleGetListEntry();
    this.handleGetListOffer();
    this.handleGetListResult();
  },

  methods: {
    async handleGetListEntry() {
      this.overlay.show = true;
      try {
        const response = await getListEntry();
        const { code, message, data } = response.data;
        if (code === 200) {
          const { result } = data;
          this.dataEntry = result.map((item) => {
            return {
              ...item,
              _isSelected: false,
            };
          });
        } else {
          MakeToast({
            variant: 'warning',
            title: 'warning',
            content: message,
          });
        }
      } catch (error) {
        console.log('error');
      }
      this.overlay.show = false;
    },

    async handleGetListOffer() {
      this.overlay.show = true;
      try {
        const response = await getListOffer();
        const { code, message, data } = response.data;
        if (code === 200) {
          const { result } = data;
          this.dataOffer = result.map((item) => {
            return {
              ...item,
              _isSelected: false,
            };
          });
        } else {
          MakeToast({
            variant: 'warning',
            title: 'warning',
            content: message,
          });
        }
      } catch (error) {
        console.log('error');
      }
      this.overlay.show = false;
    },

    async handleGetListResult() {
      this.overlay.show = true;
      try {
        const response = await getListResult();
        const { code, message, data } = response.data;
        if (code === 200) {
          const { result } = data;
          this.dataResult = result.map((item) => {
            return {
              ...item,
              _isSelected: false,
            };
          });
        } else {
          MakeToast({
            variant: 'warning',
            title: 'warning',
            content: message,
          });
        }
      } catch (error) {
        console.log('error');
      }
      this.overlay.show = false;
    },

    async handleSearch(params) {
      // console.log('params tại đây', params);
      this.overlay.show = true;
      switch (this.tabIndex) {
        case 0:
          console.log('tìm kiếm entry');
          try {
            const response = await getListEntry(params);
            const { code, message, data } = response.data;
            if (code === 200) {
              const { result } = data;
              this.dataEntry = result.map((item) => {
                return {
                  ...item,
                  _isSelected: false,
                };
              });
            } else {
              MakeToast({
                variant: 'warning',
                title: 'warning',
                content: message,
              });
            }
          } catch (error) {
            console.log('error');
          }
          break;
        case 1:
          console.log('tìm kiếm offer');
          try {
            const response = await getListOffer(params);
            const { code, message, data } = response.data;
            if (code === 200) {
              const { result } = data;
              this.dataOffer = result.map((item) => {
                return {
                  ...item,
                  _isSelected: false,
                };
              });
            } else {
              MakeToast({
                variant: 'warning',
                title: 'warning',
                content: message,
              });
            }
          } catch (error) {
            console.log('error');
          }
          break;

        case 3:
          console.log('tìm kiếm result');
          try {
            const response = await getListResult(params);
            const { code, message, data } = response.data;
            if (code === 200) {
              const { result } = data;
              this.dataResult = result.map((item) => {
                return {
                  ...item,
                  _isSelected: false,
                };
              });
            } else {
              MakeToast({
                variant: 'warning',
                title: 'warning',
                content: message,
              });
            }
          } catch (error) {
            console.log('error');
          }
          break;

        default:
          break;
      }

      this.overlay.show = false;
    },

    // Modal
    checkSideBar() {
      if (this.sidebarExists === false) {
        this.sidebarExists = true;
      } else {
        this.sidebarExists = false;
      }
    },

    linkClass(idx) {
      if (this.tabIndex === idx) {
        return ['bg-primary', 'text-light'];
      } else {
        return ['bg-default', 'text-dark'];
      }
    },

    async handleDeleteAll() {
      switch (this.tabIndex) {
        case 0:
          this.$bus.emit('handleDeleteAllEntry');
          break;
        case 1:
          this.$bus.emit('handleDeleteAllOffer');
          break;
        case 3:
          this.$bus.emit('handleDeleteAllResult');
          break;

        default:
          break;
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.content-detail-decline {
  width: 765px;
  height: 422px;
  background: #f9f9ff;
}
.decline-status {
  font-size: 18px;
  color: red;
}
.checkbox-to-radio input[type='checkbox'] {
  display: none;
}

.checkbox-to-radio .radio-button {
  display: inline-block;
  width: 16px;
  height: 16px;
  border: 1px solid #ccc;
  border-radius: 50%;
  margin-right: 30px;
  vertical-align: middle;
  cursor: pointer;
}

.checkbox-to-radio input[type='checkbox']:checked + .radio-button {
  background-color: #007bff;
}
.checkbox-like-radio
  .custom-control-input:checked
  ~ .custom-control-label::before {
  background-color: #007bff;
}

.checkbox-like-radio .custom-control-label::before {
  width: 1.25rem;
  height: 1.25rem;
  border-radius: 50%;
  border: 1px solid #ccc;
}
.checkbox-like-radio .custom-control-label::after {
  display: none;
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
.hr-list-head-btns {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 0.75rem;
}
button.btn.btn-warning {
  margin-top: 10px;
  width: 140px;
  border-radius: 20px;
}
button.btn.btn-danger {
  width: 140px;
  border-radius: 20px;
}
.text-center.pb-2.col-7 {
  margin-left: 100px;
}
.text-description {
  font-size: 12px;
  color: #5a5a5a;
}
.text-lable-content-detail-lable-1 {
  padding-left: 90px;
}
::v-deep .hr-list-table-list__table {
  & .b-table {
    & tbody {
      // border: 2px solid blue;
      & tr {
        min-height: 78px;
        height: 78px;
        padding: 1.25rem 0;

        & td {
          line-height: 20px !important;
          height: 25px;
          vertical-align: middle;
          position: relative;
          & > div {
            display: flex;
            justify-content: center;
          }
        }
      }
      & tr:last-child {
        border-bottom: 1px solid #dee2e6;
      }
    }
  }
}

.rotate-180deg {
  transform: rotate(-180deg);
}

.collapse-bar-btn {
  border: 1px solid #ccc;
  margin-bottom: 4px;
}

.hr-list-head-title {
  // height: 100%;
  display: flex;
  justify-content: flex-start;
  align-items: stretch;
  gap: 1rem;
  & div {
    display: flex;
    justify-content: flex-start;
    align-items: center;
    & span {
      font-weight: 700;
      font-size: 24px;
      line-height: 29px;
      color: #000000;
    }
  }
}
.line {
  border: 1px solid #304cad;
  background: #304cad;
  border-radius: 10px;
  width: 4px;
  // height: 36px;
}
// Ghi đè /3x2
@import '@/scss/_variables.scss';
@import '@/scss/modules/MatchingManagement/Header.scss';
</style>
