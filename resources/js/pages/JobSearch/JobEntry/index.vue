<template>
  <div class="job-detail-for-hr">
    <div class="job-detail-for-hr-container">
      <div class="job-detail-for-hr-form">
        <div class="job-detail-for-hr-form__head">
          <div class="h-100 d-flex justify-start align-stretch">
            <div class="line" />

            <div class="job-detail-for-hr-form__head-title pl-4">
              <span>{{ $t('JOB_ENTRY.TITLE') }}</span>
            </div>
          </div>

          <div class="job-detail-for-hr-form__head-btn">
            <div class="btn btn-back" @click="navigateToJobDetail()">
              <span>{{ $t('RETURN') }}</span>
            </div>
          </div>
        </div>

        <div class="job-detail-for-hr-form-autox">
          <div class="job-detail-for-hr-form-page">
            <div class="form-page-area">
              <div class="form-page-area__head">
                <span style="font-weight: bold">{{
                  $t('JOB_ENTRY.MODAL_ENTRY_TITME')
                }}</span>
              </div>

              <div class="form-page-area__content">
                <div class="form-page-area-content-wrap">
                  <div class="form-item-row">
                    <div class="form-item-row__title">
                      <div>
                        <span>{{ $t('JOB_ENTRY.FULL_NAME') }}</span>
                      </div>
                    </div>

                    <div class="form-item-row__inputs" style="padding: 1rem">
                      <div
                        class="entry-hr-select"
                        style="align-items: flex-start !important"
                      >
                        <div @click="handleToggleModalSelectHR()">
                          <ExtendBtn />
                        </div>
                        <div class="entry-hr-selected-list">
                          <div
                            v-for="(item, index) in list_hr_selected"
                            :key="index"
                            class="entry-hr-selected-item"
                          >
                            <span
                              class="btn-effect"
                              @click="removeThisHr(item['id'])"
                            >
                              <i class="fas fa-times" />
                            </span>

                            <div class="entry-hr-selected-item__name">
                              <span style="font-size: 16px">{{
                                `${item['full_name']} ${item['full_name_ja']} (ID:${item['id']})`
                              }}</span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div
            class="job-detail-for-hr-form-btn"
            style="margin-top: 2rem; margin-bottom: 2rem"
          >
            <div style="width: 360px" @click="navigateToInformation()">
              <BtnMoveNext
                :min-width="'360px'"
                :text="$t('JOB_ENTRY.MODAL_ENTRY_TITME')"
                :direction="'next'"
              />
            </div>
          </div>
        </div>
      </div>
    </div>

    <b-modal
      ref="modal-select-hr-to-entry"
      v-model="statusModalSelectHR"
      hide-footer
      dialog-class
      :size="'xl'"
      :title="$t('JOB_ENTRY.MODAL_ENTRY_TITME')"
      :no-fade="false"
      no-close-on-backdrop
      aria-describedby="select-hr-to-entry"
      @close="handleClickCloseModalButton()"
    >
      <b-overlay
        :show="overlay.show"
        :variant="overlay.variant"
        :opacity="overlay.opacity"
        :blur="overlay.blur"
        :rounded="overlay.sm"
      >
        <template #overlay>
          <div class="text-center">
            <b-icon icon="arrow-clockwise" font-scale="3" animation="spin" />
            <p style="margin-top: 10px">{{ $t('PLEASE_WAIT') }}</p>
          </div>
        </template>
        <div class="modal-select-hr">
          <div class="modal-select-hr-container">
            <div class="modal-select-hr-search">
              <HrFormSearch
                @handleSearch="handleSearchResultFromSidebar($event)"
              />
            </div>

            <div class="modal-select-hr-list">
              <div class="modal-select-hr-wrap">
                <div class="select-hr-head">
                  <span>{{ $t('ROUTER_HR_LIST') }}</span>
                </div>

                <div class="select-hr-list">
                  <div v-if="vItems.length > 0" class="select-hr-list-wrap">
                    <div
                      v-for="(item, index) in vItems"
                      :key="`item-${index}`"
                      class="select-hr-item"
                    >
                      <b-form-checkbox
                        v-model="item['is_check']"
                        size="lg"
                        :disabled="item['is_disabled']"
                        @change="handleChangeCheckboxValue(item, $event)"
                      >
                        <span style="font-size: 16px">{{
                          `${item['full_name']} ${item['full_name_ja']} (ID:${item['id']})`
                        }}</span>
                      </b-form-checkbox>
                    </div>
                  </div>

                  <div
                    v-else
                    class="d-flex w-100 h-100 justify-content-center align-items-center"
                  >
                    <span>{{ $t('TABLE_EMPTY') }}</span>
                  </div>
                </div>
              </div>

              <!-- Pagination -->
              <div class="w-100 d-flex justify-end align-center mt-4">
                <!-- <b-pagination
                  v-model="paginationSelectEntryHr.currentPage"
                  :total-rows="paginationSelectEntryHr.totalRows"
                  :per-page="paginationSelectEntryHr.perPage"
                  aria-controls="select-entry-hr"
                  pills
                  :next-class="'next'"
                  :prev-class="'prev'"
                  @change="($event) => getCurrentPageSelectEntryHr($event)"
                /> -->
                <custom-pagination
                  v-if="vItems && vItems.length > 0"
                  :total-rows="paginationSelectEntryHr.totalRows"
                  :current-page="paginationSelectEntryHr.currentPage"
                  :per-page="paginationSelectEntryHr.perPage"
                  @pagechanged="onPageChange"
                  @changeSize="changeSize"
                />
              </div>

              <div class="modal-select-hr-btn">
                <div class="btn btn-confirm" @click="handleTurnOffModal()">
                  <span>{{ $t('DECISION') }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </b-overlay>
    </b-modal>

    <b-modal
      v-model="isShowModalWarning"
      hide-footer
      :no-fade="false"
      no-close-on-backdrop
      centered
      size="lg"
    >
      <template #modal-title>
        <span>{{ $t('TITLE_QUESTION1') }}</span>
      </template>

      <template #default>
        <div class="d-flex justify-content-end" style="gap: 10px">
          <div class="btn btn-cancel" @click="isShowModalWarning = false">
            <span>{{ $t('STAY_ON') }}</span>
          </div>

          <div class="btn btn-confirm" @click="handleConfirmLeavePage()">
            <span>{{ $t('MOVE_AWAY') }}</span>
          </div>
        </div>
      </template>
    </b-modal>
  </div>
</template>

<script>
import { getAllHr } from '@/api/modules/hr';
import { MakeToast } from '@/utils/toastMessage';
import _ from 'lodash';
import ExtendBtn from '@/components/ExtendBtn';
import BtnMoveNext from '@/components/BtnMoveNext';
import HrFormSearch from '@/layout/components/search/HrFormSearch.vue';
import { PAGINATION_CONSTANT } from '@/const/config.js';

const urlAPI = {
  apiGetHr: '/hr',
};

export default {
  name: 'JobEntry',
  components: {
    HrFormSearch,
    ExtendBtn,
    BtnMoveNext,
  },
  data() {
    return {
      overlay: {
        opacity: 0,
        show: false,
        blur: '1rem',
        rounded: 'sm',
        variant: 'light',
      },

      paginationSelectEntryHr: {
        currentPage: 1,
        totalRows: null, // null
        perPage: PAGINATION_CONSTANT.DEFALT_PER_PAGE,
      },

      statusModalSelectHR: false,

      isShowModalWarning: false,

      vItems: [],

      list_hr_selected: [],

      list_temp_hr_selected: [],

      is_return: false,

      work_id: this.$store.getters.work_id,
    };
  },

  created() {
    this.handleReRenderListSelectedHr();
    // Clear localstorage
    localStorage.removeItem('checkboxChoosed');
  },
  methods: {
    // getCurrentPageSelectEntryHr(value) {
    //   if (value) {
    //     this.paginationSelectEntryHr.currentPage = parseInt(value);
    //     this.handleSearchResultFromSidebar();
    //   }
    // },

    handleReRenderListSelectedHr() {
      const DATA = this.$store.getters.list_selected_hr;
      if (DATA) {
        this.list_hr_selected = DATA;
      } else {
        this.list_hr_selected = [];
      }
    },
    async navigateToJobDetail() {
      if (this.list_hr_selected.length > 0) {
        this.isShowModalWarning = true;
      } else {
        this.$router.push({ path: `/job-search/detail/${this.work_id}` });
      }
    },
    handleConfirmLeavePage() {
      this.isShowModalWarning = false;
      this.$router.push({ path: `/job-search/detail/${this.work_id}` });
    },
    async navigateToInformation() {
      if (this.list_hr_selected.length > 0) {
        await this.$store.dispatch(
          'job/setListSelectedHr',
          this.list_hr_selected
        );

        this.$router.push({ name: 'JobInformationEntry' });
        // Xóa local
        localStorage.removeItem('checkboxChoosed');
      } else {
        MakeToast({
          variant: 'warning',
          title: this.$t('WARNING'),
          content: this.$t('VALIDATE.NOT_SELECT_HR_WHEN_CREATE_ENTRY'),
        });
      }
    },
    removeThisHr(hr_id) {
      const getItemFrop = JSON.parse(localStorage.getItem('checkboxChoosed'));
      const result = getItemFrop.filter((item) => item['id'] !== hr_id);
      this.list_hr_selected = this.list_hr_selected.filter(
        (item) => item['id'] !== hr_id
      );

      this.$store.dispatch('job/setListSelectedHr', this.list_hr_selected);
      // Cập nhật lại storage
      localStorage.setItem('checkboxChoosed', JSON.stringify(result));
    },
    handleToggleModalSelectHR() {
      this.statusModalSelectHR = !this.statusModalSelectHR;

      if (this.statusModalSelectHR) {
        this.handleSearchResultFromSidebar();
      }
    },
    handleChangeCheckboxValue(item, event) {
      const LIST_PRE_SELECTED_HR = this.$store.getters.list_selected_hr;

      if (LIST_PRE_SELECTED_HR) {
        this.list_temp_hr_selected = LIST_PRE_SELECTED_HR;
      }

      if (this.list_temp_hr_selected.length > 0) {
        const isExisted = this.list_temp_hr_selected.find(
          (data) => data['id'] === item['id']
        );

        if (isExisted) {
          const index = this.list_temp_hr_selected.findIndex(
            (dataIndex) => dataIndex['id'] === item['id']
          );
          this.list_temp_hr_selected.splice(index, 1);
        } else {
          if (event) {
            this.list_temp_hr_selected.push(item);
          } else {
            const index = this.list_temp_hr_selected.findIndex(
              (dataIndex) => dataIndex['id'] === item['id']
            );
            this.list_temp_hr_selected.splice(index, 1);
          }
        }
      } else {
        if (event) {
          this.list_temp_hr_selected.push(item);
        } else {
          const index = this.list_temp_hr_selected.findIndex(
            (dataIndex) => dataIndex['id'] === item['id']
          );
          this.list_temp_hr_selected.splice(index, 1);
        }
      }

      if (this.list_temp_hr_selected.length === 10) {
        this.vItems.forEach((item) => {
          if (item['is_check']) {
            item['is_disabled'] = false;
          } else {
            item['is_disabled'] = true;
          }
        });
      } else {
        this.handleResetDisabledStatus();
      }
    },
    handleResetDisabledStatus() {
      this.vItems.forEach((item) => {
        item['is_disabled'] = false;
      });
    },
    async handleTurnOffModal() {
      this.statusModalSelectHR = false;

      await this.$store.dispatch(
        'job/setListSelectedHr',
        this.list_hr_selected
      );

      const tempCheckboxChoosed = _.cloneDeep(this.list_temp_hr_selected);
      localStorage.setItem(
        'checkboxChoosed',
        JSON.stringify(tempCheckboxChoosed)
      );

      this.list_temp_hr_selected = [];
    },
    async handleClickCloseModalButton() {
      const getItgem = JSON.parse(localStorage.getItem('checkboxChoosed'));
      if (getItgem && getItgem.length > 0) {
        await this.$store.dispatch('job/setListSelectedHr', getItgem);
      } else {
        await this.$store.dispatch('job/setListSelectedHr', []);
      }
      // this.list_temp_hr_selected = [];
      this.handleReRenderListSelectedHr();
    },

    onPageChange(page) {
      this.paginationSelectEntryHr.currentPage = page;
      this.handleSearchResultFromSidebar();
    },
    changeSize(size) {
      this.paginationSelectEntryHr.perPage = size;
      this.paginationSelectEntryHr.currentPage = 1;
      this.handleSearchResultFromSidebar();
    },

    async handleSearchResultFromSidebar(query) {
      this.overlay.show = true;

      const LIST_DISABLED_HR = this.$store.getters.list_disabled_hr;

      const FORM_DATA = new FormData();

      if (query) {
        // Custom query
        if (
          ('final_education_date_from_month' in query &&
            'final_education_date_from_year' in query) ||
          ('final_education_date_to_year' in query &&
            'final_education_date_to_month' in query)
        ) {
          query = {
            ...query,
            middle_class: query?.middle_class
              ? query.middle_class.flatMap((item) => item.childOptions)
              : null,
            main_job_ids: query?.main_job_ids
              ? query.main_job_ids.flatMap((item) => item.childOptions)
              : null,
            edu_date_from: this.handleMergeYearMonth(
              query['final_education_date_from_year'],
              query['final_education_date_from_month']
            ),
            edu_date_to: this.handleMergeYearMonth(
              query['final_education_date_to_year'],
              query['final_education_date_to_month']
            ),
          };
          if (query.edu_date_from === '-') {
            delete query.edu_date_from;
          }
          if (query.edu_date_to === '-') {
            delete query.edu_date_to;
          }
        } else {
          query = {
            ...query,
            middle_class: query?.middle_class
              ? query.middle_class.flatMap((item) => item.childOptions)
              : null,
            main_job_ids: query?.main_job_ids
              ? query.main_job_ids.flatMap((item) => item.childOptions)
              : null,
            edu_date_from: query['edu_date_from']
              ? query['edu_date_from']
              : null,
            edu_date_to: query['edu_date_to'] ? query['edu_date_to'] : null,
          };
        }
        // query = {
        //   ...query,
        //   edu_date_from: query.edu_date_from
        //     ? query.edu_date_from
        //     : query.final_education_date_from_year +
        //       '-' +
        //       query.final_education_date_from_month,
        //   edu_date_to: query.edu_date_to
        //     ? query.edu_date_to
        //     : query.final_education_date_to_year +
        //       '-' +
        //       query.final_education_date_to_month,
        // };
        if (query['hr_org_id']) {
          FORM_DATA.append('hr_org_id', query['hr_org_id']);
        }

        if (query['gender'].length > 0) {
          query['gender'].forEach((item) => {
            FORM_DATA.append('gender[]', item);
          });
        }

        if (query['age_from']) {
          FORM_DATA.append('age_from', query['age_from']);
        }

        if (query['age_to']) {
          FORM_DATA.append('age_to', query['age_to']);
        }

        if (query['edu_date_from']) {
          FORM_DATA.append('edu_date_from', query['edu_date_from']);
        }

        if (query['edu_date_to']) {
          FORM_DATA.append('edu_date_to', query['edu_date_to']);
        }

        if (query['edu_class'].length > 0) {
          query['edu_class'].forEach((item) => {
            FORM_DATA.append('edu_class[]', item);
          });
        }

        if (query['edu_degree'].length > 0) {
          query['edu_degree'].forEach((item) => {
            FORM_DATA.append('edu_degree[]', item);
          });
        }

        if (query['japan_levels'].length > 0) {
          query['japan_levels'].forEach((item) => {
            FORM_DATA.append('japan_levels[]', item);
          });
        }

        if (query['work_forms'].length > 0) {
          query['work_forms'].forEach((item) => {
            FORM_DATA.append('work_forms[]', item);
          });
        }

        if (query['work_hour']) {
          FORM_DATA.append('work_hour', query['work_hour']);
        }

        if (query['main_job_ids'].length > 0) {
          const main_job_ids = query['main_job_ids'].flatMap(
            (item) => item.childOptions
          );
          main_job_ids.forEach((item) => {
            FORM_DATA.append('main_job_ids[]', item);
          });
        }

        if (query['middle_class'].length > 0) {
          const middle_class = query['main_job_ids'].flatMap(
            (item) => item.childOptions
          );
          middle_class.forEach((item) => {
            FORM_DATA.append('middle_class[]', item);
          });
        }

        if (query['search']) {
          FORM_DATA.append('search', query['search']);
        }
      }

      FORM_DATA.append('page', this.paginationSelectEntryHr.currentPage);
      FORM_DATA.append('per_page', this.paginationSelectEntryHr.perPage);

      const SEARCH_PARAMS = new URLSearchParams();

      for (const [key, value] of FORM_DATA.entries()) {
        if (value) {
          SEARCH_PARAMS.append(key, value);
        }
      }
      SEARCH_PARAMS.append('display', 'entry');
      SEARCH_PARAMS.append('work_id', this.work_id);

      const URL = `${urlAPI.apiGetHr}?${SEARCH_PARAMS.toString()}`;

      try {
        const response = await getAllHr(URL);

        if (response['code'] === 200) {
          const total_records =
            response['data']['pagination']['total_records'] || 0;
          const DATA = response['data']['result'];
          this.paginationSelectEntryHr.totalRows = total_records;

          const result = [];

          DATA.forEach((item) => {
            if (LIST_DISABLED_HR.includes(item['id'])) {
              item['is_disabled'] = true;
            } else {
              item['is_disabled'] = false;
            }

            item['is_check'] = false;

            if (item['status'] === 1) {
              result.push(item);
            }
          });

          const LIST_PRE_SELECTED_HR = this.$store.getters.list_selected_hr;

          if (LIST_PRE_SELECTED_HR) {
            result.forEach((item) => {
              const isExisted = LIST_PRE_SELECTED_HR.find(
                (data) => data['id'] === item['id']
              );

              if (isExisted) {
                item['is_check'] = true;
              }
            });
          }

          this.vItems = result;
        } else {
          MakeToast({
            variant: 'warning',
            title: this.$t('WARNING'),
            content: response['message'] || '[ERROR FROM SERVER]',
          });
        }
      } catch (error) {
        MakeToast({
          variant: 'warning',
          title: this.$t('WARNING'),
          content: error['message'] || '[ERROR FROM SERVER]',
        });
      }

      this.overlay.show = false;
    },

    handleMergeYearMonth(year, month) {
      // let result;

      // if (year && month) {
      //   result = `${year}-${this.handleFormatMonthDate(month)}`;
      // }

      // return result || null;
      return `${year}-${this.handleFormatMonthDate(month)}`;
    },

    handleFormatMonthDate(string) {
      // let result;

      // if (string) {
      //   if (parseInt(string) < 10) {
      //     result = `${string}`;
      //   } else {
      //     result = string;
      //   }
      // }

      // return result;
      if (string) {
        if (parseInt(string) < 10) {
          return `${string}`;
        } else {
          return string;
        }
      } else {
        return '';
      }
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/common/common.scss';
@import '@/pages/JobSearch/JobDetailForHr/JobDetailForHr.scss';

.btn-cancel {
  font-size: 16px;
  color: #333333;
  font-weight: bold;
  border-radius: 6px;
  background-color: #d8d9da;

  & > span {
    line-height: 30px;
  }

  &:hover {
    opacity: 0.6;
  }
}

.btn-confirm {
  color: white;
  border-radius: 6px;
  background-color: #007bff;

  &:hover {
    opacity: 0.6;
  }
}

.entry-hr-select {
  width: 100%;
  gap: 0.75rem;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  justify-content: flex-start;
}

.entry-hr-selected-list {
  width: 100%;
  gap: 0.75rem;
  display: flex;
  padding: 0 0.5rem;
  flex-direction: column;
  align-items: flex-start;
  justify-content: flex-start;

  .btn-effect {
    color: red;
  }
}

.entry-hr-selected-item {
  gap: 1rem;
  width: 100%;
  display: flex;
  align-items: flex-start;
  justify-content: flex-start;
}

.entry-hr-selected-item__name {
  width: 100%;
  display: flex;
  align-items: flex-start;
  justify-content: flex-start;
}

.select-hr-item {
  display: flex;
  margin-bottom: 10px;
  flex-direction: column;
}

.modal-select-hr {
  width: 100%;
  display: flex;
  min-height: 500px;
  align-items: stretch;
  justify-content: flex-start;
}

.modal-select-hr-container {
  width: 100%;
  gap: 0.75rem;
  display: flex;
  align-items: stretch;
  justify-content: flex-start;
}

.modal-select-hr-search {
  width: 100%;
  display: flex;
  max-width: 23%;
  border-radius: 5px;
  background: #ffffff;
  align-items: stretch;
  justify-content: flex-start;
}

.modal-select-hr-list {
  width: 100%;
  // gap: 2.1rem;
  display: flex;
  max-width: 77%;
  align-items: center;
  flex-direction: column;
  padding: 0 0 6.25rem 0;
  justify-content: flex-start;
}

.modal-select-hr-wrap {
  flex: 1;
  width: 100%;
  display: flex;
  gap: 1.375rem;
  border-radius: 5px;
  background: #ffffff;
  align-items: center;
  flex-direction: column;
  border: 1px solid #c6c6c6;
  justify-content: flex-start;
  padding: 0.5rem 0.75rem 1.625rem 0.75rem;
}

.select-hr-head {
  width: 100%;
  height: 34px;
  display: flex;
  padding: 20px;
  background: #f1f1f1;
  align-items: center;
  justify-content: flex-start;
  box-shadow: 0px 2px 4px rgba(61, 61, 61, 0.25);

  & > div {
    display: flex;
    align-items: center;
    justify-content: center;
  }

  & > span {
    color: #304cad;
    font-size: 18px;
    font-weight: bold;
  }
}

.select-hr-list {
  flex: 1;
  width: 100%;
  display: flex;
  align-items: stretch;
  justify-content: flex-start;
}

.select-hr-list-wrap {
  padding-left: 10px;
}

.btn-confirm {
  height: 45px;
  display: flex;
  min-width: 130px;
  padding: 0 2.8rem;
  border-radius: 6px;
  background: #304cad;
  align-items: center;
  justify-content: center;
  border: 1px solid #c6c6c6;
  box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.25);

  & span {
    color: $white;
    font-size: 16px;
    font-weight: bold;
  }
}

.modal-select-hr-btn {
  margin-top: 2.1rem;
}
</style>
