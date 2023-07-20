<!-- Job List (HR) -->
<!-- /job-search/list -->
<!-- JobListHR -->
<template>
  <b-overlay
    :show="overlay.show"
    :blur="overlay.blur"
    :rounded="overlay.sm"
    :style="'min-height: 100vh; height: 100%'"
    :variant="overlay.variant"
    :opacity="overlay.opacity"
    :no-center="false"
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
    <div class="job-list-hr">
      <div class="job-list-hr-multi-search">
        <JobFormSearch />
      </div>
      <!-- 2 求人情報 検索一覧 Job information search list -->
      <div class="job-list-hr-multi-search-wrap">
        <div class="job-list-hr-job-list">

          <div class="job-list-hr-job-list__head">
            <div class="line" />
            <span>{{ $t('JOB_SEARCH.JOB_LIST_TITLE') }}</span>
          </div>
          <div class="job-list-hr-job-list__number-case">
            <span class="number-case">{{ arrListjobHR.length }}</span>
            <span>{{ $t('JOB_SEARCH.CASE') }}</span>
          </div>
          <div class="job-list-hr-job-list__list">
            <div class="job-list-wrap">
              <div v-for="(item,index) in arrListjobHR" :key="index" class="job-item">
                <div class="job-item__head">
                  <div>
                    <div class="job-item-head-title">{{ item.job_name }}</div>
                    <div v-if="compareTime(item.created_at)" class="job-item-head-new"><span>{{ $t('NEW') }}</span></div>
                  </div>
                  <div class="job-item-head__date-post">
                    {{ convertCreateAt(item.created_at) }}
                    {{ $t('JOB_SEARCH.POSTED_UNTIL') }}</div>
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
                            <span>{{ item.occupation }}</span>
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

                        <div class="form-item-row__inputs">
                          <div>
                            <span>{{ item.job_description }}</span>
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
                            <span>
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
                            <span>{{ $t('JOB_DETAIL.WORKING_PLACE_DETAIL') }}</span>
                          </div>
                        </div>

                        <div class="form-item-row__inputs">
                          <div>
                            <span>{{ item.work_palace }}</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="job-item-content-btns">
                    <div v-if="item.is_favorite === 1" @click="handleRemoveFavoriteJob(item.id)">
                      <FavoriteBtn :height="'28px'" class="btn" />
                    </div>

                    <div v-if="item.is_favorite === 0" @click="handleAddFavoriteJob(item.id)">
                      <FavoriteRemoved :height="'28px'" class="btn" />
                    </div>

                    <div id="go-to-detail-job" class="btn job-detail" @click="goToDetailJob(item.id)">
                      <span>{{ $t('JOB_SEARCH.SEE_DETAIL') }} </span>
                      <img :src="require('@/assets/images/icons/chervon-right.png')" alt="chervon">
                    </div>

                  </div>
                </div>
              </div>

            </div>

          </div>

        </div>

      </div>

    </div>

    <b-overlay />

  </b-overlay>
</template>

<script>
import { MakeToast } from '@/utils/toastMessage';
// import { obj2Path } from '@/utils/obj2Path';
import JobFormSearch from '@/layout/components/search/JobFormSearch.vue';
import FavoriteBtn from '@/components/Favorite';
import FavoriteRemoved from '@/components/FavoriteRemoved';
import { listJob, addFavoriteJob, removeFavoriteJob } from '@/api/job';
// import { formattedDateTimestamp } from '@/utils/formattedDateTimestamp';

// const urlAPI = {
//   urlGetLisUser: '/user',
//   urlDeleAll: 'user/ ',
// };
export default {
  name: 'JobListHR',
  components: {
    JobFormSearch,
    FavoriteBtn,
    FavoriteRemoved,
  },

  data() {
    return {
      overlay: {
        show: false,
        variant: 'light',
        opacity: 0,
        blur: '1rem',
        rounded: 'sm',
        fixed: false,
      },
      reRender: 0,
      arrListjobHR: [
        {
          id: null,
          job_name: '',
          created_at: '',
          occupation: '',
          job_description: '',
          expected_income: '',
          work_palace: '',
          is_favorite: '',
        },
      ],
      //
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

  created() {
    this.getJobListForHR();
  },

  methods: {
    goToDetailJob(id) {
      console.log('goToDetailJob', id);
      this.$router.push({ path: `/job-search/detail/${id}` });
    },
    convertCreateAt(create_at) {
      const newDate = new Date(Number(create_at * 1000));
      const year = newDate.getFullYear();
      const month = newDate.getMonth();
      const day = newDate.getDay();
      const tempDate = `
      ${year}${this.$t('JOB_SEARCH.YEAR')}
      ${month}${this.$t('JOB_SEARCH.MONTH')}
      ${day}${this.$t('JOB_SEARCH.DAY')}
      `;
      return tempDate;
    },

    compareTime(created_at) {
      if (created_at) {
        const epochTimeNow = Math.floor(new Date().getTime() / 1000);
        const compare = Math.abs(epochTimeNow - created_at);
        const threeDaysInSeconds = 3 * 24 * 60 * 60;
        if (compare >= threeDaysInSeconds) {
          return false;
        } else {
          return true;
        }
      }
    },
    //
    async getJobListForHR() {
      try {
        this.overlay.show = true;
        const res = await listJob();
        const code = res.code;
        const codeData = res.data.code;
        const data = res.data.result;
        const dataData = res.data.data.result;
        if (code === 200 || codeData === 200) {
          const dataListJob = dataData || data;
          this.arrListjobHR = [];
          this.convertArrListjobHR(dataListJob);
          this.overlay.show = false;
        }
      } catch (erorr) {
        console.log('erorr', erorr);
      }
    },

    convertArrListjobHR(dataListJob) {
      // console.log('convertArrListjobHR dataListJob: ', dataListJob);
      for (let i = 0; i < dataListJob.length; i++) {
        this.arrListjobHR.push({
          id: dataListJob[i] && dataListJob[i]?.id,
          job_name: dataListJob[i] && dataListJob[i]?.title,
          created_at: dataListJob[i] && dataListJob[i].created_at,
          occupation: dataListJob[i].middle_classification && dataListJob[i].middle_classification.name_ja,
          job_description: dataListJob[i] && dataListJob[i].job_description,
          expected_income: dataListJob[i] && dataListJob[i]?.expected_income,
          work_palace: dataListJob[i].city && dataListJob[i]?.city.name_ja,
          is_favorite: dataListJob[i] && dataListJob[i]?.is_favorite,
        });
      }
    },
    async handleAddFavoriteJob(id) {
      const PARAMS = {
        relation_id: id,
        type: 2, // Fix for list job(hr)
      };
      try {
        const res = await addFavoriteJob(PARAMS);
        // console.log('addFavoriteJob', res);
        const resCode = res.code;
        const resDataCode = res.data.code;
        const code = resDataCode || resCode;
        const resMessage = res.message_content;
        const resDataMessage = res.data.message_content;
        const message_content = resDataMessage || resMessage;
        if (code === 200) {
          MakeToast({
            variant: 'success',
            title: 'SUCCESS',
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
      } catch (erorr) {
        console.log('erorr', erorr);
      }
    },
    async handleRemoveFavoriteJob(id) {
      const PARAMS = {
        relation_id: id,
        type: 2,
      };

      try {
        const res = await removeFavoriteJob(PARAMS);
        // console.log('removeFavoriteJob', res);
        const resCode = res.code;
        const resDataCode = res.data.code;
        const code = resDataCode || resCode;
        const resMessage = res.message_content;
        const resDataMessage = res.data.message_content;
        const message_content = resDataMessage || resMessage;

        if (code === 200) {
          MakeToast({
            variant: 'success',
            title: 'SUCCESS',
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
      } catch (erorr) {
        console.log('erorr', erorr);
      }
    },
    //
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/Job/job.scss';

.job-list-hr {
  width: 100%;
  display: flex;
  justify-content: flex-start;
  align-items: flex-start;
  gap: 1.75rem;
}

.job-list-hr-multi-search {
  width: 25%;
  min-width: 272px;
  display: flex;
  justify-content: center;
  align-items: flex-start;
}

.job-list-hr-multi-search-wrap {
  width: 75%;
  padding: 0rem 0rem 1.5rem 0.75rem;
  display: flex;
  justify-content: center;
  align-items: stretch;
}

.job-list-hr-job-list {
  width: 100%;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: center;
  gap: 0.5rem;
}

.job-list-hr-job-list__head {
  width: 100%;
  height: 36px;
  display: flex;
  justify-content: flex-start;
  align-items: stretch;
  font-weight: 700;
  font-size: 24px;
  color: $black;
  gap: 0.75rem;
}

.line {
  border: 1px solid #304cad;
  background: #304cad;
  border-radius: 10px;
  width: 4px;
}

.job-list-hr-job-list__number-case {
  margin-top: 0.75rem;
  // border: 1px solid GREEN;
  width: 100%;
  display: flex;
  justify-content: flex-start;
  align-items: center;
  gap: 0.625rem;
  color: $case;

  &>span:nth-child(2) {
    font-weight: 400;
    font-size: 14px;
  }
}

.number-case {
  font-weight: 700;
  font-size: 20px;
}

.job-list-hr-job-list__list {
  margin-top: 1.25rem;
  // border: 1px solid blue;
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: flex-start;
  align-items: stretch;
  gap: 1rem;
}

.job-list-wrap {
  width: 100%;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: flex-start;
  gap: 1.75rem;
}

.job-item {
  border: 1px solid #C6C6C6;
  border-radius: 3px;
  width: 100%;
  //
  // height: 400px;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: center;
}

.job-item__head {
  border-radius: 3px 3px 0 0;
  background: linear-gradient(90deg,
      #2b3bcb 50.19%,
      rgba(43, 106, 187, 4) 56.33%,
      #89ddfc 72.18%);
  width: 100%;
  padding: 0 1rem;
  display: flex;
  justify-content: space-between;
  align-items: stretch;
  gap: 1rem;

  &>div:nth-child(1) {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1rem;
    color: $white;
  }
}

.job-item-head-title {
  display: inline-block;
  width: 100%;
  height: 40px;
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 0.5rem;
  font-weight: 700;
  font-size: 14px;

}

.job-item-head-new {
  border-radius: 3px;
  background: #FFD74B;
  height: 16px;
  padding: 0 0.375rem;
  font-weight: 400;
  font-size: 10px;
  display: flex;
  justify-content: center;
  align-items: center;
  text-transform: uppercase;
}

//
.job-item-head__date-post {
  width: fit-content;
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 1rem;
  font-weight: 400;
  font-size: 13px;
  color: #323232;
}

.job-item__content {
  padding: 1rem;
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  gap: 0.75rem;
}

.job-item-content-form {
  border: 1px solid #C6C6C6;
  // padding: 1rem;
  border-radius: 3px;
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 1.25rem;
}

.job-item-content-form-wrap {
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: flex-start;
}

.job-item-content-btns {
  // border: 1px solid brown;
  width: 100%;
  display: flex;
  justify-content: flex-end;
  align-items: center;
  gap: 0.75rem;
}

.job-detail {
  box-shadow: 0px 4px 4px 0px #00000040;
  height: 100%;
  height: 28px;
  padding: 0 2.25rem;
  display: flex;
  justify-content: center;
  align-items: center;
  background: blue;
  color: $white;
  font-weight: 700;
  font-size: 12px;
  position: relative;

  &>img {
    transform: translateX(6px);
  }
}

.form-item-row {
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
}

.form-item-row__title {
  width: 33%;
  display: flex;
  min-width: 216px;
  padding: 0.75rem 1.25rem;
  align-items: flex-start;
  background: #F8F8F8;
  justify-content: flex-start;
  color: $titleClassify;
  font-weight: 600;
  font-size: 12px;
}

.form-item-row__inputs {
  width: 77%;
  display: flex;
  padding: 0.75rem 1.25rem;
  align-items: flex-start;
  justify-content: flex-start;
  color: $black;
  font-weight: 400;
  font-size: 12px;
}</style>

