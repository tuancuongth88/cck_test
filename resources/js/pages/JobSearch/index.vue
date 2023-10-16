<template>
  <b-overlay
    :show="overlay.show"
    :blur="overlay.blur"
    :rounded="overlay.sm"
    :variant="overlay.variant"
    :opacity="overlay.opacity"
  >
    <template #overlay>
      <div class="text-center">
        <b-icon icon="arrow-clockwise" font-scale="3" animation="spin" />
        <p style="margin-top: 10px">
          {{ $t('PLEASE_WAIT') }}
        </p>
      </div>
    </template>

    <div class="job-list-hr">
      <div class="job-list-hr-multi-search">
        <JobFormSearch id="job-form-search" @handleSearch="getJobListForHR" />
      </div>

      <div class="job-list-hr-multi-search-wrap">
        <div class="job-list-hr-job-list">
          <div class="job-list-hr-job-list__head">
            <div class="line" />
            <span id="job-list-hr-title" dusk="job_list_hr_title">{{
              $t('JOB_INFORMATION_SEARCH_LIST')
            }}</span>
          </div>

          <div class="job-list-hr-job-list__number-case">
            <span class="number-case" dusk="number_case">{{
              list_job_hr.length
            }}</span>
            <span>{{ $t('JOB_SEARCH.CASE') }}</span>
          </div>

          <div id="dusk_job_list" class="job-list-hr-job-list__list">
            <div class="job-list-wrap">
              <div
                v-for="(item, index) in list_job_hr"
                :key="index"
                class="job-item"
              >
                <div class="job-item__head">
                  <div>
                    <div class="job-item-head-title">{{ item.job_name }}</div>

                    <div v-if="item.is_within_3_days" class="job-item-head-new">
                      <span>{{ $t('JOB_SEARCH.NEW') }}</span>
                    </div>
                  </div>

                  <div class="job-item-head__date-post">
                    <span>{{ item.recruitment_end_date }}</span>
                  </div>
                </div>

                <div class="job-item__content">
                  <div class="job-item-content-form">
                    <div class="job-item-content-form-wrap">
                      <!-- 1 職種 Occupation -->
                      <div class="form-item-row">
                        <div class="form-item-row__title">
                          <div>
                            <span>{{ $t('TITLE.OCCUPATION') }}</span>
                          </div>
                        </div>

                        <div class="form-item-row__inputs">
                          <div>
                            <span id="occupation">{{ item.occupation }}</span>
                          </div>
                        </div>
                      </div>

                      <!-- 2 仕事内容 job description -->
                      <div class="form-item-row border-t">
                        <div class="form-item-row__title">
                          <div>
                            <span>{{ $t('JOB_DETAIL.JOB_DESCRIPTION') }}</span>
                          </div>
                        </div>

                        <div
                          class="form-item-row__inputs"
                          style="overflow-x: hidden"
                        >
                          <div>
                            <span
                              v-for="(line, lineIndex) in item.job_description"
                              :key="lineIndex"
                            >
                              {{ line }}<br>
                            </span>
                          </div>
                        </div>
                      </div>

                      <!-- 3 想定年収 expected income -->
                      <div class="form-item-row border-t">
                        <div class="form-item-row__title">
                          <div>
                            <span> {{ $t('JOB_DETAIL.EXPECTED_INCOME') }}</span>
                          </div>
                        </div>

                        <div class="form-item-row__inputs">
                          <div>
                            <span id="expected_income">
                              {{ item.expected_income }}
                              {{ $t('JOB_SEARCH.TEN_THOUSAND_YEN') }}
                            </span>
                          </div>
                        </div>
                      </div>

                      <!-- 4 勤務地 (都道府県) work palace -->
                      <div class="form-item-row border-t">
                        <div class="form-item-row__title">
                          <div>
                            <span>{{ $t('WORKING_PLACE') }}</span>
                          </div>
                        </div>

                        <div class="form-item-row__inputs">
                          <div>
                            <span id="work_palace">{{ item.work_palace }}</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="job-item-content-btns">
                    <template
                      v-if="
                        [ROLE.TYPE_COMPANY, ROLE.TYPE_HR].includes(
                          permissionCheck
                        )
                      "
                    >
                      <div
                        v-if="item.is_favorite === 1"
                        class="btn-remove-favorite-job"
                        @click="handleRemoveFavoriteJob(item.id)"
                      >
                        <FavoriteBtn :height="'30px'" class="btn" />
                      </div>

                      <div
                        v-if="item.is_favorite === 0"
                        class="btn-add-favorite-job"
                        @click="handleAddFavoriteJob(item.id)"
                      >
                        <FavoriteBtnRemoved :height="'30px'" class="btn" />
                      </div>
                    </template>

                    <div
                      id="go-to-detail-job"
                      class="btn job-detail"
                      @click="goToDetailJob(item.id, item.list_disabled_hrs)"
                    >
                      <span>{{ $t('JOB_SEARCH.SEE_DETAIL') }} </span>
                      <i
                        class="fas fa-chevron-right position-absolute"
                        style="right: 10px"
                      />
                    </div>
                  </div>
                </div>
              </div>
              <!-- Pagination -->

              <div class="w-100 d-flex justify-end align-center">
                <custom-pagination
                  v-if="list_job_hr && list_job_hr.length > 0"
                  :total-rows="totalRows"
                  :current-page="currentPage"
                  :per-page="perPage"
                  @pagechanged="onPageChange"
                  @changeSize="changeSize"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </b-overlay>
</template>

<script>
// import { getListJob } from '@/api/modules/job';
import { listJob } from '@/api/job';
import { MakeToast } from '@/utils/toastMessage';
import { addFavoriteJob, removeFavoriteJob } from '@/api/job';
import ROLE from '@/const/role.js';

import FavoriteBtn from '@/components/FavoriteBtn/index.vue';
import FavoriteBtnRemoved from '@/components/FavoriteBtnRemoved';
import JobFormSearch from '@/layout/components/search/JobFormSearch.vue';
import { PAGINATION_CONSTANT } from '@/const/config.js';
import { pushParamOrQueryToRouter } from '@/utils/routerUtils';

export default {
  name: 'JobListHR',
  components: {
    FavoriteBtn,
    JobFormSearch,
    FavoriteBtnRemoved,
  },
  data() {
    return {
      overlay: {
        opacity: 1,
        show: false,
        blur: '1rem',
        rounded: 'sm',
        variant: 'light',
      },
      currentPage: 1,
      perPage: PAGINATION_CONSTANT.DEFALT_PER_PAGE,
      totalRows: 0,
      list_job_hr: [],
      FORM_DATA: null,
      ROLE: ROLE,
    };
  },

  computed: {
    permissionCheck() {
      return this.$store.getters.permissionCheck;
    },
  },

  watch: {},

  created() {
    // Get query list from store
    const queries = this.$store.getters.routerInfo[this.$route.name]?.queries;
    if (queries) {
      this.currentPage = queries?.page;
      this.perPage = queries?.per_page;
    }

    const is_getSearchStore = this.$store.getters.job_search_data;
    if (is_getSearchStore !== null) {
      this.getJobListForHR({ ...is_getSearchStore, isFirstLoad: true }); // Nếu đầu vào có dữ liệu search
    } else {
      this.getJobListForHR(); // Nếu đầu vào không có
    }
    // Clear after created compenent
    pushParamOrQueryToRouter(this.$route.name);
  },
  methods: {
    async goToDetailJob(id, item) {
      pushParamOrQueryToRouter(this.$route.name, {
        page: this.currentPage,
        per_page: this.perPage,
      });
      await this.$store.dispatch('job/setListDisabledHr', item);
      await this.$store.dispatch('job/setWorkID', id);
      this.$router.push({ path: `/job-search/detail/${id}` });
    },
    async getJobListForHR(query) {
      this.overlay.show = true;
      if (query) {
        // If have action click btn search
        if (!query?.isFirstLoad) {
          this.currentPage = 1;
        }
        // Biến đổi query của middle_classification_id
        this.FORM_DATA = {
          ...query,
          middle_classification_id: query['middle_classification_id'].flatMap(
            (item) => item.childOptions
          ),
          city_id: query['city_id'].map((item) => item.id),
          page: this.currentPage,
          per_page: this.perPage,
          display: 'entry',
        };
      } else {
        this.FORM_DATA = {
          ...this.FORM_DATA,
          page: this.currentPage,
          per_page: this.perPage,
          display: 'entry',
        };
      }

      try {
        const response = await listJob(this.FORM_DATA);
        const { code, message } = response.data;

        if (code === 200) {
          const {
            data: { result, pagination },
          } = response.data;

          this.list_job_hr = [];
          this.totalRows = pagination.total_records;
          this.currentPage = pagination.current_page;
          this.convertlist_job_hr(result);
        } else {
          MakeToast({
            variant: 'warning',
            title: this.$t('WARNING'),
            content: message,
          });
          this.totalRows = 0;
        }
      } catch (error) {
        MakeToast({
          variant: 'danger',
          title: this.$t('DANGER'),
          content: error['message'] || '[ERROR FROM SERVER]',
        });
      }

      this.overlay.show = false;
    },
    convertlist_job_hr(data) {
      for (let i = 0; i < data.length; i++) {
        if (data[i]['status'] !== 3) {
          const item = {};

          item['id'] = data[i]['id'] || '';
          item['job_name'] = data[i]['title'] || '';

          if (data[i]['created_at']) {
            item['created_at'] = data[i]['created_at'];
            item['is_within_3_days'] = this.timestampToDatetimeString(
              data[i]['created_at']
            );
          } else {
            item['created_at'] = data[i]['created_at'] || '';
            item['is_within_3_days'] = data[i]['is_within_3_days'] || '';
          }

          if (data[i]['middle_classification']) {
            item['occupation'] =
              data[i]['middle_classification']['name_ja'] || '';
          } else {
            item['occupation'] = '';
          }

          item['job_description'] =
            this.handleBreakLine(data[i]['job_description']) || '';
          item['expected_income'] = data[i]['expected_income'] || '';

          if (data[i]['city']) {
            item['work_palace'] = data[i]['city']['name_ja'] || '';
          } else {
            item['work_palace'] = '';
          }

          item['is_favorite'] = data[i]['is_favorite'] || 0;

          if (data[i]['application_period_to']) {
            item['recruitment_end_date'] =
              this.handleTransformRecruimentEndDate(
                data[i]['application_period_to']
              );
          } else {
            item['recruitment_end_date'] = '';
          }

          item['list_disabled_hrs'] = data[i]['list_disabled_hrs'] || [];

          this.list_job_hr.push(item);
        }
      }
    },
    timestampToDatetimeString(timestamp) {
      const newTimestamp = timestamp + 3 * 24 * 60 * 60;

      const date = new Date(newTimestamp * 1000);

      const year = date.getFullYear().toString().padStart(4, '0');
      const month = (date.getMonth() + 1).toString().padStart(2, '0');
      const day = date.getDate().toString().padStart(2, '0');

      const hours = date.getHours().toString().padStart(2, '0');
      const minutes = date.getMinutes().toString().padStart(2, '0');
      const seconds = date.getSeconds().toString().padStart(2, '0');

      const datetimeString = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;

      const now = new Date();

      const currentYear = now.getFullYear().toString().padStart(4, '0');
      const currentMonth = (now.getMonth() + 1).toString().padStart(2, '0');
      const currentDay = now.getDate().toString().padStart(2, '0');
      const currentHours = now.getHours().toString().padStart(2, '0');
      const currentMinutes = now.getMinutes().toString().padStart(2, '0');
      const currentSeconds = now.getSeconds().toString().padStart(2, '0');
      const currentDatetimeString = `${currentYear}-${currentMonth}-${currentDay} ${currentHours}:${currentMinutes}:${currentSeconds}`;

      if (currentDatetimeString <= datetimeString) {
        return true;
      } else {
        return false;
      }
    },
    async handleAddFavoriteJob(id) {
      const PARAMS = {
        type: 2,
        relation_id: id,
      };

      try {
        const res = await addFavoriteJob(PARAMS);

        const resCode = res.code;
        const resDataCode = res.data.code;
        const code = resDataCode || resCode;

        const resMessage = res.message_content;
        const resDataMessage = res.data.message_content;
        const message_content = resDataMessage || resMessage;

        if (code === 200) {
          MakeToast({
            variant: 'success',
            title: this.$t('SUCCESS'),
            content: message_content,
          });

          this.getJobListForHR();
        } else {
          MakeToast({
            variant: 'warning',
            title: 'WARNING',
            content: message_content,
          });
        }
      } catch (error) {
        console.log(error);
      }
    },
    async handleRemoveFavoriteJob(id) {
      const PARAMS = {
        type: 2,
        relation_id: id,
      };

      try {
        const res = await removeFavoriteJob(PARAMS);

        const resCode = res.code;
        const resDataCode = res.data.code;
        const code = resDataCode || resCode;

        const resMessage = res.message_content;
        const resDataMessage = res.data.message_content;
        const message_content = resDataMessage || resMessage;

        if (code === 200) {
          MakeToast({
            variant: 'success',
            title: this.$t('SUCCESS'),
            content: message_content,
          });

          this.getJobListForHR();
        } else {
          MakeToast({
            variant: 'warning',
            title: 'WARNING',
            content: message_content,
          });
        }
      } catch (error) {
        console.log(error);
      }
    },
    handleTransformRecruimentEndDate(string) {
      let result = '';

      if (string) {
        const YYYY = string.split('-')[0];
        const MM = string.split('-')[1];
        const DD = string.split('-')[2];

        if (YYYY && MM && DD) {
          result = `${YYYY}年${MM}月${DD}日まで掲載`;
        }
      }

      return result;
    },
    handleBreakLine(paragraph) {
      let result;

      if (paragraph) {
        result = paragraph.split('\n');
      }

      return result;
    },
    onPageChange(page) {
      this.currentPage = page;
      this.getJobListForHR();
    },
    changeSize(size) {
      this.perPage = size;
      this.currentPage = 1;
      this.getJobListForHR();
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/Job/job.scss';

.job-list-hr {
  width: 100%;
  gap: 1.75rem;
  display: flex;
  align-items: flex-start;
  justify-content: flex-start;
}

.job-list-hr-multi-search {
  width: 25%;
  display: flex;
  min-width: 272px;
  justify-content: center;
  align-items: flex-start;
}

.job-list-hr-multi-search-wrap {
  width: 75%;
  display: flex;
  align-items: stretch;
  justify-content: center;
  padding: 0rem 0rem 1.5rem 0.75rem;
}

.job-list-hr-job-list {
  width: 100%;
  gap: 0.5rem;
  display: flex;
  align-items: center;
  flex-direction: column;
  justify-content: flex-start;
}

.job-list-hr-job-list__head {
  width: 100%;
  height: 36px;
  gap: 0.75rem;
  display: flex;
  color: $black;
  font-size: 24px;
  font-weight: 700;
  align-items: stretch;
  justify-content: flex-start;
}

.line {
  width: 4px;
  background: #304cad;
  border-radius: 10px;
  border: 1px solid #304cad;
}

.job-list-hr-job-list__number-case {
  width: 100%;
  color: $case;
  display: flex;
  gap: 0.625rem;
  margin-top: 0.75rem;
  align-items: center;
  justify-content: flex-start;

  & > span:nth-child(2) {
    font-size: 14px;
    font-weight: 400;
  }
}

.number-case {
  font-size: 20px;
  font-weight: 700;
}

.job-list-hr-job-list__list {
  gap: 1rem;
  width: 100%;
  height: 100%;
  display: flex;
  margin-top: 1.25rem;
  align-items: stretch;
  justify-content: flex-start;
}

.job-list-wrap {
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  justify-content: flex-start;
}

.job-item {
  width: 100%;
  display: flex;
  border-radius: 3px;
  align-items: center;
  flex-direction: column;
  border: 1px solid #c6c6c6;
  justify-content: flex-start;
}

.job-item__head {
  gap: 1rem;
  width: 100%;
  display: flex;
  padding: 0 1rem;
  align-items: stretch;
  border-radius: 3px 3px 0 0;
  justify-content: space-between;
  background: linear-gradient(
    90deg,
    #2b3bcb 50.19%,
    rgba(43, 106, 187, 4) 56.33%,
    #89ddfc 72.18%
  );

  & > div:nth-child(1) {
    gap: 1rem;
    display: flex;
    color: $white;
    align-items: center;
    justify-content: center;
  }
}

.job-item-head-title {
  // width: 100%;
  gap: 0.5rem;
  height: 40px;
  display: flex;
  font-size: 14px;
  font-weight: 700;
  align-items: center;
  justify-content: center;
}

.job-item-head-new {
  height: 16px;
  display: flex;
  font-size: 10px;
  font-weight: 400;
  border-radius: 3px;
  background: #ffd74b;
  padding: 0 0.375rem;
  align-items: center;
  justify-content: center;
  text-transform: uppercase;
}

.job-item-head__date-post {
  gap: 1rem;
  display: flex;
  color: #323232;
  font-size: 13px;
  font-weight: 400;
  width: fit-content;
  align-items: center;
  justify-content: center;
}

.job-item__content {
  width: 100%;
  height: 100%;
  gap: 0.75rem;
  padding: 1rem;
  display: flex;
  align-items: center;
  flex-direction: column;
  justify-content: center;
}

.job-item-content-form {
  width: 100%;
  height: 100%;
  gap: 1.25rem;
  display: flex;
  border-radius: 3px;
  align-items: center;
  justify-content: center;
  border: 1px solid #c6c6c6;
}

.job-item-content-form-wrap {
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  justify-content: flex-start;
}

.job-item-content-btns {
  width: 100%;
  gap: 0.75rem;
  display: flex;
  align-items: center;
  justify-content: flex-end;
}

.job-detail {
  height: 100%;
  height: 30px;
  display: flex;
  color: $white;
  font-size: 12px;
  background: blue;
  font-weight: 700;
  padding: 0 2.25rem;
  position: relative;
  align-items: center;
  justify-content: center;
  box-shadow: 0px 4px 4px 0px #00000040;

  & > img {
    transform: translateX(6px);
  }
}

.form-item-row {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.form-item-row__title {
  width: 33%;
  height: 100%;
  display: flex;
  font-size: 12px;
  min-width: 216px;
  font-weight: 600;
  background: #f8f8f8;
  color: $titleClassify;
  align-items: flex-start;
  padding: 0.75rem 1.25rem;
  justify-content: flex-start;
}

.form-item-row__inputs {
  width: 77%;
  display: flex;
  color: $black;
  font-size: 12px;
  font-weight: 400;
  align-items: flex-start;
  padding: 0.75rem 1.25rem;
  justify-content: flex-start;
}
</style>
