<template>
  <div class="display-user-management-list">
    <b-row class="mb-4">
      <b-col v-if="!sidebarExists" cols="3" class="hr-list__search">
        <HrFormSearch @handleSearch="handleSearch" />
      </b-col>
      <b-col :cols="!sidebarExists ? 9 : 12">
        <div class="btn collapse-bar-btn" @click="checkSideBar">
          <!-- <div :class="{ 'rotate-180deg': sidebarExists }"> -->
          <div>
            <img
              alt="collapse"
              :src="require('@/assets/images/login/search-icon.png')"
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
              <div class="d-flex align-items-center mb-0 title-company-list">
                {{ $t('TAB_MATCHING_MANAGEMENT') }}
              </div>
            </div>
            <div>
              <b-button
                id="btn-delete-all"
                dusk="btn_delete_all"
                variant="outline-dark"
                class="btn_del_mul--custom"
                @click="handleDeleteAll"
              >{{ $t('BUTTON.DELETE_ALL') }}
                <b-icon icon="trash-fill" />
              </b-button>
            </div>
          </b-col>
        </b-row>
        <b-row id="dusk_matching" class="nav-bar">
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
                  @click="handleClickTab(0)"
                >
                  <!-- <div :class="!sidebarExists ? 'over-flow' : ''"> -->
                  <!-- <div class="hr-list-table-list__table"> -->
                  <entry
                    :data-entry="dataEntry"
                    :pagination="paginationEntry"
                    :sidebar-exists="sidebarExists"
                    @changePage="handleChangePage"
                    @handleGetListEntry="handleGetListEntry"
                  />
                  <!-- </div> -->
                  <!-- </div> -->
                </b-tab>
                <b-tab
                  :title="$t('TAB.OFFER')"
                  :title-link-class="linkClass(1)"
                  @click="handleClickTab(1)"
                >
                  <!-- <div :class="!sidebarExists ? 'over-flow' : ''"> -->
                  <!-- <div class="hr-list-table-list__table"> -->
                  <offer
                    :data-offer="dataOffer"
                    :pagination="paginationOffer"
                    :sidebar-exists="sidebarExists"
                    @changePage="handleChangePage"
                    @handleGetListOffer="handleGetListOffer"
                  />
                  <!-- </div> -->
                  <!-- </div> -->
                </b-tab>
                <b-tab
                  :title="$t('TAB.INTERVIEW')"
                  :title-link-class="linkClass(2)"
                  @click="handleClickTab(2)"
                >
                  <!-- <div :class="!sidebarExists ? 'over-flow' : ''"> -->
                  <!-- <div class="hr-list-table-list__table"> -->
                  <interview
                    :data-interview="dataInterview"
                    :pagination="paginationInterview"
                    :sidebar-exists="sidebarExists"
                    @changePage="handleChangePage"
                    @getListInterview="handleGetListInterview"
                    @reloadListResult="handleGetListResult"
                  />
                  <!-- </div> -->
                  <!-- </div> -->
                </b-tab>
                <b-tab
                  :title="$t('TAB.RESULT')"
                  :title-link-class="[linkClass(3), 'dusk-result']"
                  @click="handleClickTab(3)"
                >
                  <!-- <div :class="!sidebarExists ? 'over-flow' : ''"> -->
                  <!-- <div class="hr-list-table-list__table"> -->
                  <result
                    :data-result="dataResult"
                    :pagination="paginationResult"
                    :sidebar-exists="sidebarExists"
                    @changePage="handleChangePage"
                    @handleGetListResult="handleGetListResult"
                  />
                  <!-- </div> -->
                  <!-- </div> -->
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
import EventBus from '@/utils/eventBus';
import HrFormSearch from '@/layout/components/search/HrFormSearch.vue';
import { MakeToast } from '@/utils/toastMessage';
import {
  getListEntry,
  getListOffer,
  getListInterview,
  getListResult,
} from '@/api/modules/matching.js';
import { PAGINATION_CONSTANT } from '@/const/config.js';
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
      dataInterview: [],
      dataResult: [],
      // tabIndex: 0,
      sidebarExists: true,

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

      // 1
      paginationEntry: {
        current_page: 1,
        per_page: 20,
        total_records: 0,
        sort_by: '',
        sort_type: '',
      },
      // 2
      paginationOffer: {
        current_page: 1,
        per_page: PAGINATION_CONSTANT.DEFALT_PER_PAGE,
        total_records: 0,
        sort_by: '',
        sort_type: '',
      },
      // 3
      paginationInterview: {
        current_page: 1,
        per_page: PAGINATION_CONSTANT.DEFALT_PER_PAGE,
        total_records: 0,
        sort_by: '',
        sort_type: '',
      },
      // 4
      paginationResult: {
        current_page: 1,
        per_page: PAGINATION_CONSTANT.DEFALT_PER_PAGE,
        total_records: 0,
        sort_by: '',
        sort_type: '',
      },

      param_search: null,
    };
  },

  computed: {
    // listUser() {
    //   return this.$store.getters.listUser;
    // },
    // currChange() {
    //   return this.queryData.page;
    // },

    tabIndex: {
      get() {
        return this.$store.getters.tabIndex ?? 0;
      },
      set(value) {},
    },
  },

  watch: {
    // // 1
    // paginationEntry: {
    //   handler: function() {
    //     this.handleGetListEntry();
    //   },
    //   deep: true,
    // },
    // // 2
    // paginationOffer: {
    //   handler: function() {
    //     this.handleGetListOffer();
    //   },
    //   deep: true,
    // },
    // // 3
    // paginationInterview: {
    //   handler: function() {
    //     this.handleGetListInterview();
    //   },
    //   deep: true,
    // },
    // // 4
    // paginationResult: {
    //   handler: function() {
    //     this.handleGetListResult();
    //   },
    //   deep: true,
    // },

    param_search: {
      handler: function() {
        if (this.tabIndex === 2) {
          this.handleGetListInterview();
        }
      },
      deep: true,
    },
  },

  created() {
    this.handleGetListEntry();
    this.handleGetListOffer();
    this.handleGetListInterview();
    this.handleGetListResult();
  },

  methods: {
    async handleGetListEntry(quueryFromChilds) {
      // Because have sort need default to page 1
      if (quueryFromChilds) {
        this.paginationEntry.current_page = 1;
        this.paginationEntry.sort_by = quueryFromChilds.field;
        this.paginationEntry.sort_type = quueryFromChilds.sort_by;
      }
      let PARAMS = {};
      const storeParam = this.$store.getters.searchParams;
      if (storeParam) {
        PARAMS = {
          ...storeParam,
          main_job_ids: storeParam.main_job_ids.flatMap(
            (item) => item.childOptions
          ),
          middle_class: storeParam.middle_class.flatMap(
            (item) => item.childOptions
          ),
          edu_date_from: storeParam['edu_date_from']
            ? storeParam['edu_date_from']
            : storeParam.final_education_date_from_year +
              '-' +
              storeParam.final_education_date_from_month,
          edu_date_to: storeParam['edu_date_to']
            ? storeParam['edu_date_to']
            : storeParam.final_education_date_to_year +
              '-' +
              storeParam.final_education_date_to_month,
          page: this.paginationEntry.current_page,
          per_page: this.paginationEntry.per_page,
        };

        if (
          PARAMS.edu_date_from === '-' ||
          PARAMS.edu_date_from === 'undefined-undefined'
        ) {
          delete PARAMS.edu_date_from;
        }
        if (
          PARAMS.edu_date_to === '-' ||
          PARAMS.edu_date_to === 'undefined-undefined'
        ) {
          delete PARAMS.edu_date_to;
        }
      } else {
        PARAMS.page = this.paginationEntry.current_page;
        PARAMS.per_page = this.paginationEntry.per_page;
        if (this.paginationEntry.sort_by) {
          PARAMS.field = this.paginationEntry.sort_by;
          PARAMS.sort_by = this.paginationEntry.sort_type;
        }
      }

      try {
        this.overlay.show = true;
        this.dataEntry = [];
        const finalParams = quueryFromChilds
          ? { ...PARAMS, ...quueryFromChilds }
          : { ...PARAMS };
        const response = await getListEntry(finalParams);
        const { code, message, data } = response.data;
        if (code === 200) {
          const { pagination, result } = data;
          this.dataEntry = result.map((item) => {
            return {
              ...item,
              _isSelected: false,
            };
          });
          this.paginationEntry.total_records = pagination.total_records;
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

    async handleGetListOffer(quueryFromChilds) {
      let PARAMS = {};
      if (quueryFromChilds) {
        this.paginationOffer.sort_by = quueryFromChilds.field;
        this.paginationOffer.sort_type = quueryFromChilds.sort_by;
        this.paginationOffer.current_page = 1;
      }

      const storeParam = this.$store.getters.searchParams;
      if (storeParam) {
        PARAMS = {
          ...storeParam,
          main_job_ids: storeParam.main_job_ids.flatMap(
            (item) => item.childOptions
          ),
          middle_class: storeParam.middle_class.flatMap(
            (item) => item.childOptions
          ),
          edu_date_from: storeParam['edu_date_from']
            ? storeParam['edu_date_from']
            : storeParam.final_education_date_from_year +
              '-' +
              storeParam.final_education_date_from_month,
          edu_date_to: storeParam['edu_date_to']
            ? storeParam['edu_date_to']
            : storeParam.final_education_date_to_year +
              '-' +
              storeParam.final_education_date_to_month,
          page: this.paginationOffer.current_page,
          per_page: this.paginationOffer.per_page,
        };

        if (
          PARAMS.edu_date_from === '-' ||
          PARAMS.edu_date_from === 'undefined-undefined'
        ) {
          delete PARAMS.edu_date_from;
        }
        if (
          PARAMS.edu_date_to === '-' ||
          PARAMS.edu_date_to === 'undefined-undefined'
        ) {
          delete PARAMS.edu_date_to;
        }
      } else {
        PARAMS.page = this.paginationOffer.current_page;
        PARAMS.per_page = this.paginationOffer.per_page;
        if (this.paginationOffer.sort_by) {
          PARAMS.field = this.paginationOffer.sort_by;
          PARAMS.sort_by = this.paginationOffer.sort_type;
        }
      }
      try {
        this.overlay.show = true;
        this.dataOffer = [];
        const finalParams = quueryFromChilds
          ? { ...PARAMS, ...quueryFromChilds }
          : { ...PARAMS };
        const response = await getListOffer(finalParams);
        const { code, message, data } = response.data;
        if (code === 200) {
          const { pagination, result } = data;
          this.dataOffer = result.map((item) => {
            return {
              ...item,
              _isSelected: false,
            };
          });
          this.paginationOffer.total_records = pagination.total_records;
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

    async handleGetListInterview(quueryFromChilds) {
      let PARAMS = {};
      if (quueryFromChilds) {
        this.paginationInterview.current_page = 1;
        this.paginationInterview.sort_by = quueryFromChilds.field;
        this.paginationInterview.sort_type = quueryFromChilds.sort_by;
      }
      const storeParam = this.$store.getters.searchParams;
      if (storeParam) {
        PARAMS = {
          ...storeParam,
          main_job_ids: storeParam.main_job_ids.flatMap(
            (item) => item.childOptions
          ),
          middle_class: storeParam.middle_class.flatMap(
            (item) => item.childOptions
          ),
          edu_date_from: storeParam['edu_date_from']
            ? storeParam['edu_date_from']
            : storeParam.final_education_date_from_year +
              '-' +
              storeParam.final_education_date_from_month,
          edu_date_to: storeParam['edu_date_to']
            ? storeParam['edu_date_to']
            : storeParam.final_education_date_to_year +
              '-' +
              storeParam.final_education_date_to_month,
          page: this.paginationInterview.current_page,
          per_page: this.paginationInterview.per_page,
        };

        if (
          PARAMS.edu_date_from === '-' ||
          PARAMS.edu_date_from === 'undefined-undefined'
        ) {
          delete PARAMS.edu_date_from;
        }
        if (
          PARAMS.edu_date_to === '-' ||
          PARAMS.edu_date_to === 'undefined-undefined'
        ) {
          delete PARAMS.edu_date_to;
        }
      } else {
        PARAMS.page = this.paginationInterview.current_page;
        PARAMS.per_page = this.paginationInterview.per_page;
        if (this.paginationInterview.sort_by) {
          PARAMS.field = this.paginationInterview.sort_by;
          PARAMS.sort_by = this.paginationInterview.sort_type;
        }
      }
      // if (this.param_search) {
      //   PARAMS = this.param_search;
      // }
      // if (this.paginationInterview.sort_by) {
      //   PARAMS.field = this.paginationInterview.sort_by;
      //   PARAMS.sort_by = this.paginationInterview.sort_type;
      // }
      try {
        this.overlay.show = true;
        this.dataInterview = [];
        const finalParams = quueryFromChilds
          ? { ...PARAMS, ...quueryFromChilds }
          : { ...PARAMS };
        const response = await getListInterview(finalParams);
        const { code, message, data } = response.data;
        if (code === 200) {
          const { pagination, result } = data;
          this.dataInterview = result.map((item) => {
            return {
              ...item,
              _isSelected: false,
            };
          });
          this.paginationInterview.total_records = pagination.total_records;
        } else {
          MakeToast({
            variant: 'danger',
            title: this.$t('DANGER'),
            content: message,
          });
        }
      } catch (error) {
        console.log('error');
      }
      this.overlay.show = false;
    },

    async handleGetListResult(quueryFromChilds) {
      let PARAMS = {};
      if (quueryFromChilds) {
        this.paginationResult.current_page = 1;
        this.paginationResult.sort_by = quueryFromChilds.field;
        this.paginationResult.sort_type = quueryFromChilds.sort_by;
      }
      const storeParam = this.$store.getters.searchParams;
      if (storeParam) {
        PARAMS = {
          ...storeParam,
          main_job_ids: storeParam.main_job_ids.flatMap(
            (item) => item.childOptions
          ),
          middle_class: storeParam.middle_class.flatMap(
            (item) => item.childOptions
          ),
          edu_date_from: storeParam['edu_date_from']
            ? storeParam['edu_date_from']
            : storeParam.final_education_date_from_year +
              '-' +
              storeParam.final_education_date_from_month,
          edu_date_to: storeParam['edu_date_to']
            ? storeParam['edu_date_to']
            : storeParam.final_education_date_to_year +
              '-' +
              storeParam.final_education_date_to_month,
          page: this.paginationResult.current_page,
          per_page: this.paginationResult.per_page,
        };

        if (
          PARAMS.edu_date_from === '-' ||
          PARAMS.edu_date_from === 'undefined-undefined'
        ) {
          delete PARAMS.edu_date_from;
        }
        if (
          PARAMS.edu_date_to === '-' ||
          PARAMS.edu_date_to === 'undefined-undefined'
        ) {
          delete PARAMS.edu_date_to;
        }
      } else {
        PARAMS.page = this.paginationResult.current_page;
        PARAMS.per_page = this.paginationResult.per_page;
        if (this.paginationResult.sort_by) {
          PARAMS.field = this.paginationResult.sort_by;
          PARAMS.sort_by = this.paginationResult.sort_type;
        }
      }

      try {
        this.overlay.show = true;
        this.dataResult = [];
        const finalParams = quueryFromChilds
          ? { ...PARAMS, ...quueryFromChilds }
          : { ...PARAMS };
        const response = await getListResult(finalParams);
        const { code, message, data } = response.data;
        if (code === 200) {
          const { pagination, result } = data;
          this.dataResult = result.map((item) => {
            return {
              ...item,
              _isSelected: false,
            };
          });
          this.paginationResult.total_records = pagination.total_records;
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
      const finalParams = {
        ...params,
        main_job_ids: params.main_job_ids.flatMap((item) => item.childOptions),
        middle_class: params.middle_class.flatMap((item) => item.childOptions),
        edu_date_from: params['edu_date_from']
          ? params['edu_date_from']
          : params.final_education_date_from_year +
            '-' +
            params.final_education_date_from_month,
        edu_date_to: params['edu_date_to']
          ? params['edu_date_to']
          : params.final_education_date_to_year +
            '-' +
            params.final_education_date_to_month,
        page: 1,
        per_page: 20,
      };
      this.overlay.show = true;

      if (
        finalParams.edu_date_from === '-' ||
        finalParams.edu_date_from === 'undefined-undefined'
      ) {
        delete finalParams.edu_date_from;
      }
      if (
        finalParams.edu_date_to === '-' ||
        finalParams.edu_date_to === 'undefined-undefined'
      ) {
        delete finalParams.edu_date_to;
      }
      // // thực hiện push lên url
      // const queryParams = {};
      // Object.keys(params).map((key) => {
      //   if (params[key] !== '' && params[key] != null) {
      //     queryParams[key] = params[key];
      //   }
      // });

      // await router.push({
      //   name: router.currentRoute.name ?? '',
      //   query: queryParams,
      // });
      switch (this.tabIndex) {
        case 0:
          // finalParams.page = 1;
          // finalParams.per_page = 20;
          this.handleResetPagination(this.paginationEntry);
          try {
            const response = await getListEntry(finalParams);
            const { code, message, data } = response.data;
            if (code === 200) {
              const { result, pagination } = data;
              this.dataEntry = result.map((item) => {
                return {
                  ...item,
                  _isSelected: false,
                };
              });
              this.paginationEntry.total_records = pagination.total_records;
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
          this.handleResetPagination(this.paginationOffer);
          // finalParams.page = this.paginationOffer.current_page;
          // finalParams.per_page = this.paginationOffer.per_page;
          try {
            const response = await getListOffer(finalParams);
            const { code, message, data } = response.data;
            if (code === 200) {
              const { result, pagination } = data;
              this.dataOffer = result.map((item) => {
                return {
                  ...item,
                  _isSelected: false,
                };
              });
              this.paginationOffer.total_records = pagination.total_records;
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

        case 2:
          this.handleResetPagination(this.paginationInterview);
          // finalParams.page = this.paginationInterview.current_page;
          // finalParams.per_page = this.paginationInterview.per_page;
          // this.param_search = finalParams;
          try {
            const response = await getListInterview(finalParams);
            const { code, message, data } = response.data;
            if (code === 200) {
              const { result, pagination } = data;
              this.dataInterview = result.map((item) => {
                return {
                  ...item,
                  _isSelected: false,
                };
              });
              this.paginationInterview.total_records = pagination.total_records;
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
          this.handleResetPagination(this.paginationResult);
          // finalParams.page = this.paginationResult.current_page;
          // finalParams.per_page = this.paginationResult.per_page;
          try {
            const response = await getListResult(finalParams);
            const { code, message, data } = response.data;
            if (code === 200) {
              const { result, pagination } = data;
              this.dataResult = result.map((item) => {
                return {
                  ...item,
                  _isSelected: false,
                };
              });
              this.paginationResult.total_records = pagination.total_records;
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

    handleChangePage(current_page) {
      if (this.tabIndex === 0) {
        this.paginationEntry.current_page = current_page;
      }
      if (this.tabIndex === 1) {
        this.paginationOffer.current_page = current_page;
      }
      if (this.tabIndex === 2) {
        this.paginationInterview.current_page = current_page;
      }
      if (this.tabIndex === 3) {
        this.paginationResult.current_page = current_page;
      }
    },

    // handleSort(sort_data) {
    //   if (this.tabIndex === 0) {
    //     this.paginationEntry.sort_by = sort_data.sort_by;
    //     this.paginationEntry.sort_type = sort_data.sort_type;
    //   }
    //   if (this.tabIndex === 1) {
    //     this.paginationOffer.sort_by = sort_data.sort_by;
    //     this.paginationOffer.sort_type = sort_data.sort_type;
    //   }
    //   if (this.tabIndex === 2) {
    //     this.paginationInterview.sort_by = sort_data.sort_by;
    //     this.paginationInterview.sort_type = sort_data.sort_type;
    //   }
    //   if (this.tabIndex === 3) {
    //     this.paginationResult.sort_by = sort_data.sort_by;
    //     this.paginationResult.sort_type = sort_data.sort_type;
    //   }
    // },

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

    async handleClickTab(tabIndex) {
      await this.$store.dispatch('app/saveTabIndex', tabIndex);
      // await this.$store.dispatch('hrSearchQuery/setSearchParams', null);
      EventBus.$emit('resetSearchParamsNull');
      switch (tabIndex) {
        case 0:
          this.handleResetPagination(this.paginationEntry);
          this.handleGetListEntry();
          break;
        case 1:
          this.handleResetPagination(this.paginationOffer);
          this.handleGetListOffer();
          break;
        case 2:
          this.handleResetPagination(this.paginationInterview);
          this.handleGetListInterview();
          break;
        case 3:
          this.handleResetPagination(this.paginationResult);
          // Object.assign(this.paginationResult, {
          //   current_page: 1,
          //   per_page: 20,
          //   total_records: 0,
          //   sort_by: '',
          //   sort_type: '',
          // });
          this.handleGetListResult();
          break;

        default:
          break;
      }
    },

    async handleResetPagination(pagination) {
      Object.assign(pagination, {
        current_page: 1,
        per_page: 20,
        total_records: 0,
        sort_by: '',
        sort_type: '',
      });
    },

    async handleDeleteAll() {
      switch (this.tabIndex) {
        case 0:
          EventBus.$emit('handleDeleteAllEntry');
          break;
        case 1:
          EventBus.$emit('handleDeleteAllOffer');
          break;
        case 2:
          EventBus.$emit('handleDeleteInterview');
          break;
        case 3:
          EventBus.$emit('handleDeleteAllResult');
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
// .hr-list-table-list__table {
//   width: max-content;
//   height: 100%;
// }
// .over-flow {
//   width: 100%;
//   position: relative;
//   padding-left: 0;
//   overflow-x: auto;
// }
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

.title-company-list {
  font-size: 24px;
  font-weight: 700;
  line-height: 36px;
}
@import '@/scss/_variables.scss';
@import '@/scss/modules/MatchingManagement/Header.scss';
</style>
