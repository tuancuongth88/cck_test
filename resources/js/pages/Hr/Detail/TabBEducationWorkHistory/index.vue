<!-- 2EducationWorkHistory Detail -->
<template>
  <div :key="`edu-work-his-${reRender}`" class="hr-content-tab hr-education-work-history">
    <div class="hr-content-tab-wrap">

      <!-- 1 最終学歴 - Final Education -->
      <div class="hr-content-tab-item border-t border-l border-r">
        <div
          id="hr-type-edit"
          class="hr-content-tab-item__title d-flex justify-start align-start"
        >
          <span class="label-final-edication">{{ $t('HR_LIST_FORM.FINAL_EDUCATION') }}</span>
        </div>

        <div class="hr-content-tab__data">
          <div
            class="w-100 d-flex flex-column py-3"
            style="gap: 1.25rem"
          >
            <!-- 1.1 年月 Graduation date -->
            <div class="w-100 d-flex justify-space-center align-center">
              <span style="width: 100%; max-width: 20%" class="'pl-1 label-graducation-date">{{
                $t('HR_LIST_FORM.GRADUATION_DATE')
              }}</span>

              <div class="d-flex justify-space-between align-center">
                <span class="content-final-education-date">{{ detailForm.final_education_date }}</span>
              </div>
            </div>

            <!-- 1.2 区分 classification / Division -->
            <div class="w-100 d-flex justify-space-center align-center">
              <span style="width: 100%; max-width: 20%" class="'pl-1 label-classification">{{
                $t('HR_LIST_FORM.CLASSIFICATION')
              }}</span>
              <div class="w-100 d-flex justify-space-between align-center content-classification">
                {{
                  detailForm.final_education_classification
                }}
              </div>
            </div>
            <!-- 1.2 学位 - degree (最終学歴（学位）) -->
            <div class="w-100 d-flex justify-space-center align-center">
              <span style="width: 100%; max-width: 20%" class="'pl-1 label-degree">{{
                $t('HR_LIST_FORM.DEGREE')
              }}</span>
              <div class="w-100 d-flex justify-space-between align-center content-degree">
                {{ detailForm.final_education_degree }}
              </div>
            </div>

            <!-- 1.3 大分類 - major classification -->
            <div class="w-100 d-flex justify-space-center align-center">
              <span style="width: 100%; max-width: 20%" class="'pl-1 label-main-category">{{
                $t('HR_LIST_FORM.MAIN_CATEGORY')
              }}</span>
              <div class="w-100 d-flex justify-space-between align-center content-main-category">
                {{ detailForm.major_classification }}
              </div>
            </div>
            <!-- 1.5 中分類 -  Middle classification / Medium category-->
            <div class="w-100 d-flex justify-space-center align-center">
              <span style="width: 100%; max-width: 20%" class="'pl-1 label-department">{{
                $t('HR_LIST_FORM.DEPARTMENT')
              }}</span>
              <div class="w-100 d-flex justify-space-between align-center content-department">
                {{ detailForm.middle_classification }}
              </div>
            </div>
            <!--  -->
          </div>

        </div>
      </div>

      <!-- 2 主な職務経歴① - Main job career1 -->
      <div class="hr-content-tab-item border-t border-l border-r">
        <div
          id="hr-type-edit"
          class="hr-content-tab-item__title d-flex justify-start align-start"
        >
          <span class="label-main-job-career">{{ $t('HR_LIST_FORM.MAIN_JOB_CAREER_1') }}</span>
        </div>

        <div class="hr-content-tab__data">
          <div
            class="w-100 d-flex flex-column py-3"
            style="gap: 1.25rem"
          >
            <!-- 2.1 年月 Graduation date -> 年月 date -->
            <div class="w-100 d-flex justify-space-center align-center">
              <div class="w-100 d-flex justify-space-between align-center">
                <span style="width: 100%; max-width: 20%" class="'pl-1 label-job-career-date">{{
                  $t('HR_LIST_FORM.DATE')
                }}</span>

                <div class="w-100 d-flex flex-column justify-start align-end">
                  <div
                    v-if="!detailForm.main_job_1.is_null"
                    class="w-100 d-flex justify-start align-center"
                    style="gap: 0.25rem"
                  >
                    <!-- From -->
                    <div class="d-flex justify-start align-center">
                      <span class="content-job-career-from">{{ detailForm.main_job_1.main_job_career_date_from }}</span>
                    </div>

                    <span class="approximately">~</span>

                    <!-- To -->
                    <div class="d-flex justify-start align-center">
                      <span class="content-job-career-to">{{ detailForm.main_job_1.main_job_career_date_to }}</span>
                    </div>
                  </div>
                  <!-- Time now -->
                  <!--  -->
                </div>
                <!--  -->
              </div>
            </div>
            <!-- 2.2 所属 department -->
            <div class="w-100 d-flex justify-space-center align-center">
              <span style="width: 100%; max-width: 20%" class="'pl-1 label-main-job-department">{{
                $t('HR_LIST_FORM.DEPARTMENT')
              }}</span>
              <div class="w-100 d-flex justify-space-between align-center">
                <span class="content-main-job-department">{{ detailForm.main_job_1.department }}</span>
              </div>
            </div>
            <!-- 2.3 職名 - Job title -->
            <div class="w-100 d-flex justify-space-center align-center">
              <span style="width: 100%; max-width: 20%" class="'pl-1 label-main-job-title">{{
                $t('HR_LIST_FORM.JOB_TITLE')
              }}</span>
              <div class="w-100 d-flex justify-space-between align-center">
                <span class="content-main-job-title">{{ detailForm.main_job_1.job_id }}</span>
              </div>
            </div>
            <!-- 2.4 詳細 - Detail-->
            <div class="w-100 d-flex justify-start align-start">
              <span style="width: 100%; max-width: 20%" class="'pl-1 label-main-job-detail">{{
                $t('HR_LIST_FORM.DETAIL')
              }}</span>
              <div class="w-100 d-flex justify-start align-start">
                <b-form-textarea
                  id="textarea-main-job-career-1"
                  v-model="detailForm.main_job_1.detail"
                  placeholder=""
                  rows="8"
                  max-rows="28"
                  max-lengh="2000"
                  class="textarea-type-detail content-main-job-detail"
                  disabled
                />
                <!-- {{ educationWorkHistoryInit.main_job_career_1_textarea }} -->
              </div>
            </div>
            <!--  -->
          </div>

        </div>
      </div>
      <!-- 3 主な職務経歴② - Main job career2 -->
      <div class="hr-content-tab-item border-t border-l border-r">
        <div
          id="hr-type-edit"
          class="hr-content-tab-item__title d-flex justify-start align-start"
        >
          <span>{{ $t('HR_LIST_FORM.MAIN_JOB_CAREER_2') }}</span>
        </div>

        <div class="hr-content-tab__data">
          <div
            class="w-100 d-flex flex-column py-3"
            style="gap: 1.25rem"
          >
            <!-- 2.1 年月 Graduation date -> 年月 date -->
            <div class="w-100 d-flex justify-space-center align-center">
              <div class="w-100 d-flex justify-space-between align-center">
                <span style="width: 100%; max-width: 20%" class="'pl-1">{{
                  $t('HR_LIST_FORM.DATE')
                }}</span>

                <div class="w-100 d-flex flex-column justify-start align-end">
                  <div
                    v-if="!detailForm.main_job_2.is_null"
                    class="w-100 d-flex justify-start align-center"
                    style="gap: 0.25rem"
                  >
                    <!-- From -->
                    <div class="d-flex justify-start align-center">
                      <span>{{ detailForm.main_job_2.main_job_career_date_from }}</span>
                    </div>

                    <span class="approximately">~</span>

                    <!-- To -->
                    <div class="d-flex justify-start align-center">
                      <span>{{ detailForm.main_job_2.main_job_career_date_to }}</span>
                    </div>
                  </div>
                  <!-- Time now -->
                  <!--  -->
                </div>
                <!--  -->
              </div>
            </div>
            <!-- 2.2 所属 department -->
            <div class="w-100 d-flex justify-space-center align-center">
              <span style="width: 100%; max-width: 20%" class="'pl-1">{{
                $t('HR_LIST_FORM.DEPARTMENT')
              }}</span>
              <div class="w-100 d-flex justify-space-between align-center">
                {{
                  detailForm.main_job_2.department
                }}
              </div>
            </div>
            <!-- 2.3 職名 - Job title -->
            <div class="w-100 d-flex justify-space-center align-center">
              <span style="width: 100%; max-width: 20%" class="'pl-1">{{
                $t('HR_LIST_FORM.JOB_TITLE')
              }}</span>
              <div class="w-100 d-flex justify-space-between align-center">
                {{
                  detailForm.main_job_2.job_id
                }}
              </div>
            </div>
            <!-- 2.4 詳細 - Detail-->
            <div class="w-100 d-flex justify-start align-start">
              <span style="width: 100%; max-width: 20%" class="'pl-1">{{
                $t('HR_LIST_FORM.DETAIL')
              }}</span>
              <div class="w-100 d-flex justify-start align-start">
                <b-form-textarea
                  id="textarea-main-job-career-2"
                  v-model="detailForm.main_job_2.detail"
                  placeholder=""
                  rows="8"
                  max-rows="28"
                  max-lengh="2000"
                  class="textarea-type-detail"
                  disabled
                />
                <!-- {{ educationWorkHistoryInit.main_job_career_1_textarea }} -->
              </div>
            </div>
            <!--  -->
          </div>
        </div>
      </div>

      <!-- 4 主な職務経歴③ - Main job career3 -->
      <div class="hr-content-tab-item border-t border-l border-r border-b">
        <div
          id="hr-type-edit"
          class="hr-content-tab-item__title d-flex justify-start align-start"
        >
          <span>{{ $t('HR_LIST_FORM.MAIN_JOB_CAREER_3') }}</span>
        </div>

        <div class="hr-content-tab__data">
          <div
            class="w-100 d-flex flex-column py-3"
            style="gap: 1.25rem"
          >
            <!-- 2.1 年月 Graduation date -> 年月 date -->
            <div class="w-100 d-flex justify-space-center align-center">
              <div class="w-100 d-flex justify-space-between align-center">
                <span style="width: 100%; max-width: 20%" class="'pl-1">{{
                  $t('HR_LIST_FORM.DATE')
                }}</span>

                <div class="w-100 d-flex flex-column justify-start align-end">
                  <div
                    v-if="!detailForm.main_job_3.is_null"
                    class="w-100 d-flex justify-start align-center"
                    style="gap: 0.25rem"
                  >
                    <!-- From -->
                    <div class="d-flex justify-start align-center">
                      <span>{{ detailForm.main_job_3.main_job_career_date_from }}</span>
                    </div>

                    <span class="approximately">~</span>

                    <!-- To -->
                    <div class="d-flex justify-start align-center">
                      <span>{{ detailForm.main_job_3.main_job_career_date_to }}</span>
                    </div>
                  </div>
                  <!-- Time now -->
                  <!--  -->
                </div>
                <!--  -->
              </div>
            </div>
            <!-- 2.2 所属 department -->
            <div class="w-100 d-flex justify-space-center align-center">
              <span style="width: 100%; max-width: 20%" class="'pl-1">{{
                $t('HR_LIST_FORM.DEPARTMENT')
              }}</span>
              <div class="w-100 d-flex justify-space-between align-center">
                {{
                  detailForm.main_job_3.department
                }}
              </div>
            </div>
            <!-- 2.3 職名 - Job title -->
            <div class="w-100 d-flex justify-space-center align-center">
              <span style="width: 100%; max-width: 20%" class="'pl-1">{{
                $t('HR_LIST_FORM.JOB_TITLE')
              }}</span>
              <div class="w-100 d-flex justify-space-between align-center">
                {{
                  detailForm.main_job_3.job_id
                }}
              </div>
            </div>
            <!-- 2.4 詳細 - Detail-->
            <div class="w-100 d-flex justify-start align-start">
              <span style="width: 100%; max-width: 20%" class="'pl-1">{{
                $t('HR_LIST_FORM.DETAIL')
              }}</span>
              <div class="w-100 d-flex justify-start align-start">
                <b-form-textarea
                  id="textarea-main-job-career-3"
                  v-model="detailForm.main_job_3.detail"
                  placeholder=""
                  rows="8"
                  max-rows="28"
                  max-lengh="2000"
                  class="textarea-type-detail"
                  disabled
                />
                <!-- {{ educationWorkHistoryInit.main_job_career_3_textarea }} -->
              </div>
            </div>
            <!--  -->
          </div>
        </div>
      </div>

      <!--  -->
    </div>
  </div>
</template>

<script>
import {
  final_education_degree_options,
  final_education_classification_options,
} from '@/pages/Hr/common.js';
import moment from 'moment';
import { getJobTypeHr } from '@/api/hr.js';
// const urlAPI = {
//   urlGetLisUser: '/user',
//   urlDeleAll: 'user/ ',
// };
export default {
  name: 'TabBEducationWorkHistoryDetail',
  props: {
    detailData: {
      type: Object,
      require: true,
      default: function() {
        return {};
      },
    },
  },

  data() {
    return {
      detailForm: {
        final_education_date: '',
        final_education_classification: '',
        final_education_degree: '',
        major_classification: '',
        middle_classification: '',

        main_job_1: {
          is_null: true,
          main_job_career_date_from: '',
          main_job_career_date_to: '',
          department: '',
          job_id: '',
          detail: '',
        },

        main_job_2: {
          is_null: true,
          main_job_career_date_from: '',
          main_job_career_date_to: '',
          department: '',
          job_id: '',
          detail: '',
        },

        main_job_3: {
          is_null: true,
          main_job_career_date_from: '',
          main_job_career_date_to: '',
          department: '',
          job_id: '',
          detail: '',
        },
      },
      reRender: 0,

      final_education_classification_options:
        final_education_classification_options,
      final_education_degree_options: final_education_degree_options,

      major_middle_info: [],
      job_info_list: [],
    };
  },

  watch: {
    detailData: {
      handler: async function(props_data) {
        if (props_data) {
          await this.getDataInfo();
          await this.convertData(props_data);
          this.reloadTable();
        }
      },
      deep: true,
    },
  },

  created() {
    // this.getDataInfo();
  },

  methods: {
    convertData(DATA) {
      if (!DATA) {
        return;
      }
      this.getMajorMiddleClassification(DATA.major_classification_id, DATA.middle_classification_id);

      this.detailForm.final_education_date = this.convertTextYearMonth(DATA.final_education_date);
      this.detailForm.final_education_classification = this.getTextByCode('final_education_classification', DATA.final_education_classification);
      this.detailForm.final_education_degree = this.getTextByCode('final_education_degree', DATA.final_education_degree);

      this.detailForm.main_job_1.is_null = !DATA.main_jobs[0];
      this.detailForm.main_job_1.main_job_career_date_from = DATA.main_jobs[0] ? this.convertTextYearMonth(DATA.main_jobs[0].main_job_career_date_from) : '';
      this.detailForm.main_job_1.main_job_career_date_to = DATA.main_jobs[0] ? this.convertTextYearMonth(DATA.main_jobs[0].main_job_career_date_to) : '';
      this.detailForm.main_job_1.detail = DATA.main_jobs[0] ? DATA.main_jobs[0].detail : '';
      if (DATA.main_jobs[0]) {
        this.converJob1Info(DATA.main_jobs[0].department_id, DATA.main_jobs[0].job_id);
      }

      this.detailForm.main_job_2.is_null = !DATA.main_jobs[1];
      this.detailForm.main_job_2.main_job_career_date_from = DATA.main_jobs[1] ? this.convertTextYearMonth(DATA.main_jobs[1].main_job_career_date_from) : '';
      this.detailForm.main_job_2.main_job_career_date_to = DATA.main_jobs[1] ? this.convertTextYearMonth(DATA.main_jobs[1].main_job_career_date_to) : '';
      this.detailForm.main_job_2.detail = DATA.main_jobs[1] ? DATA.main_jobs[1].detail : '';
      if (DATA.main_jobs[1]) {
        this.converJob2Info(DATA.main_jobs[1].department_id, DATA.main_jobs[1].job_id);
      }

      this.detailForm.main_job_3.is_null = !DATA.main_jobs[2];
      this.detailForm.main_job_3.main_job_career_date_from = DATA.main_jobs[2] ? this.convertTextYearMonth(DATA.main_jobs[2].main_job_career_date_from) : '';
      this.detailForm.main_job_3.main_job_career_date_to = DATA.main_jobs[2] ? this.convertTextYearMonth(DATA.main_jobs[2].main_job_career_date_to) : '';
      this.detailForm.main_job_3.detail = DATA.main_jobs[2] ? DATA.main_jobs[2].detail : '';
      if (DATA.main_jobs[2]) {
        this.converJob3Info(DATA.main_jobs[2].department_id, DATA.main_jobs[2].job_id);
      }
    },

    async getDataInfo() {
      await this.getInfoMajorClass(2);
      await this.getInfoMajorClass(1);
    },

    async getInfoMajorClass(id) {
      if (!id) {
        return '';
      }
      try {
        const PARAM = {
          type: id,
        };
        const res = await getJobTypeHr(PARAM);
        const { code, data } = res.data;
        if (code === 200) {
          if (id === 2) {
            this.major_middle_info = data;
          }
          if (id === 1) {
            this.job_info_list = data;
          }
        }
      } catch (error) {
        console.log(error);
      }
    },

    reloadTable() {
      this.reRender++;
    },
    convertTextYearMonth(date) {
      let text_date = '';
      if (date) {
        text_date = moment(date).format('YYYY年MM月');
      } else {
        text_date = '現在';
      }
      return text_date;
    },
    getTextByCode(type, code) {
      let text = '';
      switch (type) {
        case 'final_education_classification':
          this.final_education_classification_options.map(item => {
            if (item.value.id === code) {
              text = item.value.content;
              return;
            }
          });
          break;
        case 'final_education_degree':
          this.final_education_degree_options.map(item => {
            if (item.value.id === code) {
              text = item.value.content;
              return;
            }
          });
          break;

        default:
          break;
      }

      return text;
    },

    getMajorMiddleClassification(major_id, middle_id) {
      let listMiddleClass = [];
      const major_len = this.major_middle_info.length;
      let idx = 0;
      while (idx < major_len) {
        if (this.major_middle_info[idx].id === major_id) {
          listMiddleClass = this.major_middle_info[idx].job_info;
          this.detailForm.major_classification = this.major_middle_info[idx].name_ja;
          break;
        }
        idx++;
      }

      const middle_len = listMiddleClass.length;
      let middle_idx = 0;
      while (middle_idx < middle_len) {
        if (listMiddleClass[middle_idx].id === middle_id) {
          this.detailForm.middle_classification = listMiddleClass[middle_idx].name_ja;
          break;
        }
        middle_idx++;
      }
    },

    converJob1Info(department_id, job_id) {
      let listJobImnfo = [];
      const department_len = this.job_info_list.length;
      let idx = 0;
      while (idx < department_len) {
        if (this.job_info_list[idx].id === department_id) {
          listJobImnfo = this.job_info_list[idx].job_info;
          this.detailForm.main_job_1.department = this.job_info_list[idx].name_ja;
          break;
        }
        idx++;
      }

      const job_len = listJobImnfo.length;
      let job_idx = 0;
      while (job_idx < job_len) {
        if (listJobImnfo[job_idx].id === job_id) {
          this.detailForm.main_job_1.job_id = listJobImnfo[job_idx].name_ja;
          break;
        }
        job_idx++;
      }
    },

    converJob2Info(department_id, job_id) {
      let listJobImnfo = [];
      const department_len = this.job_info_list.length;
      let idx = 0;
      while (idx < department_len) {
        if (this.job_info_list[idx].id === department_id) {
          listJobImnfo = this.job_info_list[idx].job_info;
          this.detailForm.main_job_2.department = this.job_info_list[idx].name_ja;
          break;
        }
        idx++;
      }

      const job_len = listJobImnfo.length;
      let job_idx = 0;
      while (job_idx < job_len) {
        if (listJobImnfo[job_idx].id === job_id) {
          this.detailForm.main_job_2.job_id = listJobImnfo[job_idx].name_ja;
          break;
        }
        job_idx++;
      }
    },

    converJob3Info(department_id, job_id) {
      let listJobImnfo = [];
      const department_len = this.job_info_list.length;
      let idx = 0;
      while (idx < department_len) {
        if (this.job_info_list[idx].id === department_id) {
          listJobImnfo = this.job_info_list[idx].job_info;
          this.detailForm.main_job_3.department = this.job_info_list[idx].name_ja;
          break;
        }
        idx++;
      }

      const job_len = listJobImnfo.length;
      let job_idx = 0;
      while (job_idx < job_len) {
        if (listJobImnfo[job_idx].id === job_id) {
          this.detailForm.main_job_3.job_id = listJobImnfo[job_idx].name_ja;
          break;
        }
        job_idx++;
      }
    },
  },
};
</script>

<style lang="scss" scoped>
// @import '@/scss/_variables.scss';
// @import '@/scss/modules/common/common.scss';
@import '@/pages/Hr/Detail/TabABasicInformation/TabABasicInformation.scss';
</style>
