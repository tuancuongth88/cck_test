<!-- 2EducationWorkHistory Edit -->
<template>
  <div class="hr-content-tab">
    <!-- <button style="position: fixed; background: gray; opacity: 0.1;" class="btn" @click="changeStatus">Change</button> -->

    <div class="hr-content-tab-wrap">
      <!-- 1 最終学歴 - Final Education -->
      <div class="hr-content-tab-item border-t border-l border-r">
        <div
          id="hr-type-edit"
          class="hr-content-tab-item__title d-flex justify-start align-start"
        >
          <span>{{ $t('HR_LIST_FORM.FINAL_EDUCATION') }}</span><Require v-if="type_form === 'edit'" />
        </div>

        <div class="hr-content-tab__data">
          <div
            v-if="type_form === 'detail'"
            class="w-100 d-flex flex-column py-3"
            style="gap: 1.25rem"
          >
            <!-- 1.1 年月 Graduation date -->
            <div class="w-100 d-flex justify-space-center align-center">
              <span style="width: 100%; max-width: 20%" class="'pl-1">{{
                $t('HR_LIST_FORM.GRADUATION_DATE')
              }}</span>

              <div class="d-flex justify-space-between align-center">
                {{ educationWorkHistoryInit.final_education_timing_year_id }}
                <span class="">{{ $t('HR_LIST_FORM.YEAR') }}</span>
                {{ educationWorkHistoryInit.final_education_timing_month_id }}
                <span class="">{{ $t('HR_LIST_FORM.MONTH') }}</span>
              </div>
            </div>

            <!-- 1.2 区分 classification / Division -->
            <div class="w-100 d-flex justify-space-center align-center">
              <span style="width: 100%; max-width: 20%" class="'pl-1">{{
                $t('HR_LIST_FORM.CLASSIFICATION')
              }}</span>
              <div class="w-100 d-flex justify-space-between align-center">
                {{
                  educationWorkHistoryInit.final_education_classification_id
                    .content
                }}
              </div>
            </div>
            <!-- 1.2 学位 - degree (最終学歴（学位）) -->
            <div class="w-100 d-flex justify-space-center align-center">
              <span style="width: 100%; max-width: 20%" class="'pl-1">{{
                $t('HR_LIST_FORM.DEGREE')
              }}</span>
              <div class="w-100 d-flex justify-space-between align-center">
                {{ educationWorkHistoryInit.final_education_degree_id.content }}
              </div>
            </div>

            <!-- 1.3 大分類 - major classification -->
            <div class="w-100 d-flex justify-space-center align-center">
              <span style="width: 100%; max-width: 20%" class="'pl-1">{{
                $t('HR_LIST_FORM.MAIN_CATEGORY')
              }}</span>
              <div class="w-100 d-flex justify-space-between align-center">
                {{ educationWorkHistoryInit.major_classification_id.content }}
              </div>
            </div>
            <!-- 1.5 中分類 -  Middle classification / Medium category-->
            <div class="w-100 d-flex justify-space-center align-center">
              <span style="width: 100%; max-width: 20%" class="'pl-1">{{
                $t('HR_LIST_FORM.DEPARTMENT')
              }}</span>
              <div class="w-100 d-flex justify-space-between align-center">
                {{ educationWorkHistoryInit.middle_classification_id.content }}
              </div>
            </div>
            <!--  -->
          </div>

          <div
            v-if="type_form === 'edit'"
            class="w-100 d-flex flex-column py-3"
            style="gap: 1.25rem"
          >
            <!-- 1.1 年月 Graduation date -->
            <div class="w-100 d-flex justify-space-center align-center">
              <span style="width: 100%; max-width: 20%" class="'pl-1">{{
                $t('HR_LIST_FORM.GRADUATION_DATE')
              }}</span>
              <div class="w-100 d-flex justify-space-between align-center">
                <b-form-select
                  v-model="
                    educationWorkHistoryInit.final_education_timing_year_id
                  "
                  :options="date_of_birth_year_options"
                  style="min-width: 163px"
                  class="w-100"
                />
                <span class="pl-2 pr-4">{{ $t('HR_LIST_FORM.YEAR') }}</span>

                <b-form-select
                  v-model="
                    educationWorkHistoryInit.final_education_timing_month_id
                  "
                  :options="date_of_birth_month_options"
                  style="min-width: 109px"
                  class="w-100"
                />
                <span class="pl-2">{{ $t('HR_LIST_FORM.MONTH') }}</span>
              </div>
            </div>

            <!-- 1.2 区分 classification / Division -->
            <div class="w-100 d-flex justify-space-center align-center">
              <span style="width: 100%; max-width: 20%" class="'pl-1">{{
                $t('HR_LIST_FORM.CLASSIFICATION')
              }}</span>
              <div class="w-100 d-flex justify-space-between align-center">
                <b-form-select
                  v-model="
                    educationWorkHistoryInit.final_education_classification_id
                  "
                  :options="final_education_classification_options"
                />
              </div>
            </div>
            <!-- 1.3 学位 - degree (最終学歴（学位）) -->
            <div class="w-100 d-flex justify-space-center align-center">
              <span style="width: 100%; max-width: 20%" class="'pl-1">{{
                $t('HR_LIST_FORM.DEGREE')
              }}</span>
              <div class="w-100 d-flex justify-space-between align-center">
                <b-form-select
                  v-model="educationWorkHistoryInit.final_education_degree_id"
                  :options="final_education_degree_options"
                />
              </div>
            </div>

            <!-- 1.4 大分類 - major classification -->
            <div class="w-100 d-flex justify-space-center align-center">
              <span style="width: 100%; max-width: 20%" class="'pl-1">{{
                $t('HR_LIST_FORM.MAIN_CATEGORY')
              }}</span>
              <div class="w-100 d-flex justify-space-between align-center">
                <b-form-select
                  v-model="educationWorkHistoryInit.major_classification_id"
                  :options="major_classification_options_api"
                />
              </div>
            </div>
            <!-- 1.5 中分類 -  Middle classification / Medium category-->
            <div class="w-100 d-flex justify-space-center align-center">
              <span style="width: 100%; max-width: 20%" class="'pl-1">{{
                $t('HR_LIST_FORM.DEPARTMENT')
              }}</span>
              <div class="w-100 d-flex justify-space-between align-center">
                <b-form-select
                  v-model="educationWorkHistoryInit.middle_classification_id"
                  :options="middle_classification_options_api"
                  :enabled="
                    educationWorkHistoryInit.major_classification_id.id !== null
                  "
                  :disabled="
                    educationWorkHistoryInit.major_classification_id.id === null
                  "
                />
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
          <span>{{ $t('HR_LIST_FORM.MAIN_JOB_CAREER_1') }}</span><Require v-if="type_form === 'edit'" />
        </div>

        <div class="hr-content-tab__data">
          <div
            v-if="type_form === 'detail'"
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
                    class="w-100 d-flex justify-start align-center"
                    style="gap: 0.25rem"
                  >
                    <!-- From -->
                    <div class="d-flex justify-start align-center">
                      {{
                        educationWorkHistoryInit.main_job_career_1_year_from_id
                      }}
                      <span>{{ $t('HR_LIST_FORM.YEAR') }}</span>
                      {{
                        educationWorkHistoryInit.main_job_career_1_month_from_id
                      }}
                      <span>{{ $t('HR_LIST_FORM.MONTH') }}</span>
                    </div>

                    <span class="approximately">~</span>

                    <!-- To -->
                    <div class="d-flex justify-start align-center">
                      {{
                        educationWorkHistoryInit.main_job_career_1_year_to_id
                      }}
                      <span>{{ $t('HR_LIST_FORM.YEAR') }}</span>
                      {{
                        educationWorkHistoryInit.main_job_career_1_month_to_id
                      }}
                      <span>{{ $t('HR_LIST_FORM.MONTH') }}</span>
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
                  educationWorkHistoryInit.main_job_career_1_department_id
                    .content
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
                  educationWorkHistoryInit.main_job_career_1_job_title_id
                    .content
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
                  id="textarea-main-job-career-1"
                  v-model="educationWorkHistoryInit.main_job_career_1_textarea"
                  placeholder=""
                  rows="8"
                  max-rows="28"
                  max-lengh="2000"
                  class="textarea-type-detail"
                  :formatter="format2000characters"
                  disabled
                />
                <!-- {{ educationWorkHistoryInit.main_job_career_1_textarea }} -->
              </div>
            </div>
            <!--  -->
          </div>
          <div
            v-if="type_form === 'edit'"
            class="w-100 d-flex flex-column py-3"
            style="gap: 1.25rem"
          >
            <!-- 2.1 年月 Graduation date -> 年月 date -->
            <div class="w-100 d-flex justify-space-center align-center">
              <div class="w-100 d-flex justify-space-between align-center">
                <span style="width: 100%; max-width: 20%" class="'pl-1">{{
                  $t('HR_LIST_FORM.DATE')
                }}</span>
                <!-- !!! chật -->
                <div
                  class="w-100 d-flex flex-column justify-start align-end"
                  style="gap: 0.75rem"
                >
                  <div class="w-100 d-flex justify-start align-center">
                    <!-- From -->
                    <div
                      class="w-100 d-flex justify-space-between align-center"
                      style="gap: 0.5rem"
                    >
                      <div
                        class="d-flex justify-space-between align-center"
                        style="width: 54%; gap: 0.25rem"
                      >
                        <b-form-select
                          v-model="
                            educationWorkHistoryInit.main_job_career_1_year_from_id
                          "
                          :options="date_of_birth_year_options"
                        />
                        <span>{{ $t('HR_LIST_FORM.YEAR') }}</span>
                      </div>

                      <div
                        class="d-flex justify-space-between align-center"
                        style="width: 46%; gap: 0.25rem"
                      >
                        <b-form-select
                          v-model="
                            educationWorkHistoryInit.main_job_career_1_month_from_id
                          "
                          :options="date_of_birth_month_options"
                        />
                        <span>{{ $t('HR_LIST_FORM.MONTH') }}</span>
                      </div>
                    </div>
                    <span class="approximately">~</span>
                    <!-- To -->
                    <div
                      class="w-100 d-flex justify-space-between align-center"
                      style="gap: 0.5rem"
                    >
                      <div
                        class="d-flex justify-space-between align-center"
                        style="width: 54%; gap: 0.25rem"
                      >
                        <b-form-select
                          v-model="
                            educationWorkHistoryInit.main_job_career_1_year_to_id
                          "
                          :options="date_of_birth_year_options"
                          :enabled="
                            !educationWorkHistoryInit.main_job_career_1_time_now
                          "
                          :disabled="
                            educationWorkHistoryInit.main_job_career_1_time_now
                          "
                        />
                        <span>{{ $t('HR_LIST_FORM.YEAR') }}</span>
                      </div>

                      <div
                        class="d-flex justify-space-between align-center"
                        style="width: 46%; gap: 0.25rem"
                      >
                        <b-form-select
                          v-model="
                            educationWorkHistoryInit.main_job_career_1_month_to_id
                          "
                          :options="date_of_birth_month_options"
                          :enabled="
                            !educationWorkHistoryInit.main_job_career_1_time_now
                          "
                          :disabled="
                            educationWorkHistoryInit.main_job_career_1_time_now
                          "
                        />
                        <span>{{ $t('HR_LIST_FORM.MONTH') }}</span>
                      </div>
                    </div>
                  </div>
                  <!-- Time now -->
                  <div
                    class="d-flex justify-end align-center"
                    style="min-width: 12%; gap: 4px"
                  >
                    <b-form-checkbox
                      id="main-job-career-1"
                      v-model="
                        educationWorkHistoryInit.main_job_career_1_time_now
                      "
                      name="main-job-career-1"
                      :value="true"
                      @input="
                        ($event) =>
                          cleacValueWhenDisable($event, 'main-job-career-1')
                      "
                      @click="
                        ($event) =>
                          cleacValueWhenDisable($event, 'main-job-career-1')
                      "
                    />
                    <span>{{ $t('CURRENT') }}</span>
                  </div>
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
                <b-form-select
                  v-model="
                    educationWorkHistoryInit.main_job_career_1_department_id
                  "
                  :options="main_job_career_department_api_common"
                  :enabled="
                    !educationWorkHistoryInit.main_job_career_1_time_now
                  "
                  :disabled="
                    educationWorkHistoryInit.main_job_career_1_time_now
                  "
                />
              </div>
            </div>
            <!-- 2.3 職名 - Job title -->
            <div class="w-100 d-flex justify-space-center align-center">
              <span style="width: 100%; max-width: 20%" class="'pl-1">{{
                $t('HR_LIST_FORM.JOB_TITLE')
              }}</span>
              <div class="w-100 d-flex justify-space-between align-center">
                <b-form-select
                  v-model="
                    educationWorkHistoryInit.main_job_career_1_job_title_id
                  "
                  :options="main_job_career_department_api_common"
                  :enabled="
                    educationWorkHistoryInit.main_job_career_1_department_id ||
                      !educationWorkHistoryInit.main_job_career_1_time_now
                  "
                  :disabled="
                    !educationWorkHistoryInit.main_job_career_1_department_id ||
                      educationWorkHistoryInit.main_job_career_1_time_now
                  "
                />
              </div>
            </div>
            <!-- 2.4 詳細 - Detail-->
            <div class="w-100 d-flex justify-start align-start">
              <span style="width: 100%; max-width: 20%" class="'pl-1">{{
                $t('HR_LIST_FORM.DETAIL')
              }}</span>
              <div class="w-100 d-flex justify-start align-start">
                <b-form-textarea
                  id="textarea-main-job-career-1"
                  v-model="educationWorkHistoryInit.main_job_career_1_textarea"
                  placeholder=""
                  rows="8"
                  max-rows="28"
                  max-lengh="2000"
                  :formatter="format2000characters"
                  :enabled="
                    educationWorkHistoryInit.main_job_career_1_department_id ||
                      !educationWorkHistoryInit.main_job_career_1_time_now
                  "
                  :disabled="
                    !educationWorkHistoryInit.main_job_career_1_department_id ||
                      educationWorkHistoryInit.main_job_career_1_time_now
                  "
                />
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
          <span>{{ $t('HR_LIST_FORM.MAIN_JOB_CAREER_2') }}</span><Arbitrarily v-if="type_form === 'edit'" />
        </div>

        <div class="hr-content-tab__data">
          <div
            v-if="type_form === 'detail'"
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
                    class="w-100 d-flex justify-start align-center"
                    style="gap: 0.25rem"
                  >
                    <!-- From -->
                    <div class="d-flex justify-start align-center">
                      {{
                        educationWorkHistoryInit.main_job_career_2_year_from_id
                      }}
                      <span>{{ $t('HR_LIST_FORM.YEAR') }}</span>
                      {{
                        educationWorkHistoryInit.main_job_career_2_month_from_id
                      }}
                      <span>{{ $t('HR_LIST_FORM.MONTH') }}</span>
                    </div>

                    <span class="approximately">~</span>

                    <!-- To -->
                    <div class="d-flex justify-start align-center">
                      {{
                        educationWorkHistoryInit.main_job_career_2_year_to_id
                      }}
                      <span>{{ $t('HR_LIST_FORM.YEAR') }}</span>
                      {{
                        educationWorkHistoryInit.main_job_career_2_month_to_id
                      }}
                      <span>{{ $t('HR_LIST_FORM.MONTH') }}</span>
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
                  educationWorkHistoryInit.main_job_career_2_department_id
                    .content
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
                  educationWorkHistoryInit.main_job_career_2_job_title_id
                    .content
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
                  v-model="educationWorkHistoryInit.main_job_career_2_textarea"
                  placeholder=""
                  rows="8"
                  max-rows="28"
                  max-lengh="2000"
                  class="textarea-type-detail"
                  :formatter="format2000characters"
                  disabled
                />
                <!-- {{ educationWorkHistoryInit.main_job_career_1_textarea }} -->
              </div>
            </div>
            <!--  -->
          </div>
          <div
            v-if="type_form === 'edit'"
            class="w-100 d-flex flex-column py-3"
            style="gap: 1.25rem"
          >
            <!-- 2.1 年月 Graduation date -> 年月 date -->
            <div class="w-100 d-flex justify-space-center align-center">
              <div class="w-100 d-flex justify-space-between align-center">
                <span style="width: 100%; max-width: 20%" class="'pl-1">{{
                  $t('HR_LIST_FORM.DATE')
                }}</span>
                <!-- !!! chật -->
                <div
                  class="w-100 d-flex flex-column justify-start align-end"
                  style="gap: 0.75rem"
                >
                  <div class="w-100 d-flex justify-start align-center">
                    <!-- From -->
                    <div
                      class="w-100 d-flex justify-space-between align-center"
                      style="gap: 0.5rem"
                    >
                      <div
                        class="d-flex justify-space-between align-center"
                        style="width: 54%; gap: 0.25rem"
                      >
                        <b-form-select
                          v-model="
                            educationWorkHistoryInit.main_job_career_2_year_from_id
                          "
                          :options="date_of_birth_year_options"
                        />
                        <span>{{ $t('HR_LIST_FORM.YEAR') }}</span>
                      </div>

                      <div
                        class="d-flex justify-space-between align-center"
                        style="width: 46%; gap: 0.25rem"
                      >
                        <b-form-select
                          v-model="
                            educationWorkHistoryInit.main_job_career_2_month_from_id
                          "
                          :options="date_of_birth_month_options"
                        />
                        <span>{{ $t('HR_LIST_FORM.MONTH') }}</span>
                      </div>
                    </div>
                    <span class="approximately">~</span>
                    <!-- To -->
                    <div
                      class="w-100 d-flex justify-space-between align-center"
                      style="gap: 0.5rem"
                    >
                      <div
                        class="d-flex justify-space-between align-center"
                        style="width: 54%; gap: 0.25rem"
                      >
                        <b-form-select
                          v-model="
                            educationWorkHistoryInit.main_job_career_2_year_to_id
                          "
                          :options="date_of_birth_year_options"
                          :enabled="
                            !educationWorkHistoryInit.main_job_career_2_time_now
                          "
                          :disabled="
                            educationWorkHistoryInit.main_job_career_2_time_now
                          "
                        />
                        <span>{{ $t('HR_LIST_FORM.YEAR') }}</span>
                      </div>

                      <div
                        class="d-flex justify-space-between align-center"
                        style="width: 46%; gap: 0.25rem"
                      >
                        <b-form-select
                          v-model="
                            educationWorkHistoryInit.main_job_career_2_month_to_id
                          "
                          :options="date_of_birth_month_options"
                          :enabled="
                            !educationWorkHistoryInit.main_job_career_2_time_now
                          "
                          :disabled="
                            educationWorkHistoryInit.main_job_career_2_time_now
                          "
                        />
                        <span>{{ $t('HR_LIST_FORM.MONTH') }}</span>
                      </div>
                    </div>
                  </div>
                  <!-- Time now -->
                  <div
                    class="d-flex justify-end align-center"
                    style="min-width: 12%; gap: 4px"
                  >
                    <b-form-checkbox
                      id="main-job-career-2"
                      v-model="
                        educationWorkHistoryInit.main_job_career_2_time_now
                      "
                      name="main-job-career-2"
                      :value="true"
                      @input="
                        ($event) =>
                          cleacValueWhenDisable($event, 'main-job-career-2')
                      "
                      @click="
                        ($event) =>
                          cleacValueWhenDisable($event, 'main-job-career-2')
                      "
                    />
                    <span>{{ $t('CURRENT') }}</span>
                  </div>
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
                <b-form-select
                  v-model="
                    educationWorkHistoryInit.main_job_career_2_department_id
                  "
                  :options="main_job_career_department_api_common"
                  :enabled="
                    !educationWorkHistoryInit.main_job_career_2_time_now
                  "
                  :disabled="
                    educationWorkHistoryInit.main_job_career_2_time_now
                  "
                />
              </div>
            </div>
            <!-- 2.3 職名 - Job title -->
            <div class="w-100 d-flex justify-space-center align-center">
              <span style="width: 100%; max-width: 20%" class="'pl-1">{{
                $t('HR_LIST_FORM.JOB_TITLE')
              }}</span>
              <div class="w-100 d-flex justify-space-between align-center">
                <b-form-select
                  v-model="
                    educationWorkHistoryInit.main_job_career_2_job_title_id
                  "
                  :options="main_job_career_department_api_common"
                  :enabled="
                    educationWorkHistoryInit.main_job_career_2_department_id ||
                      !educationWorkHistoryInit.main_job_career_2_time_now
                  "
                  :disabled="
                    !educationWorkHistoryInit.main_job_career_2_department_id ||
                      educationWorkHistoryInit.main_job_career_2_time_now
                  "
                />
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
                  v-model="educationWorkHistoryInit.main_job_career_2_textarea"
                  placeholder=""
                  rows="8"
                  max-rows="28"
                  max-lengh="2000"
                  :formatter="format2000characters"
                  :enabled="
                    educationWorkHistoryInit.main_job_career_2_department_id ||
                      !educationWorkHistoryInit.main_job_career_2_time_now
                  "
                  :disabled="
                    !educationWorkHistoryInit.main_job_career_2_department_id ||
                      educationWorkHistoryInit.main_job_career_2_time_now
                  "
                />
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
          <span>{{ $t('HR_LIST_FORM.MAIN_JOB_CAREER_3') }}</span><Arbitrarily v-if="type_form === 'edit'" />
        </div>

        <div class="hr-content-tab__data">
          <div
            v-if="type_form === 'detail'"
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
                    class="w-100 d-flex justify-start align-center"
                    style="gap: 0.25rem"
                  >
                    <!-- From -->
                    <div class="d-flex justify-start align-center">
                      {{
                        educationWorkHistoryInit.main_job_career_3_year_from_id
                      }}
                      <span>{{ $t('HR_LIST_FORM.YEAR') }}</span>
                      {{
                        educationWorkHistoryInit.main_job_career_3_month_from_id
                      }}
                      <span>{{ $t('HR_LIST_FORM.MONTH') }}</span>
                    </div>

                    <span class="approximately">~</span>

                    <!-- To -->
                    <div class="d-flex justify-start align-center">
                      {{
                        educationWorkHistoryInit.main_job_career_3_year_to_id
                      }}
                      <span>{{ $t('HR_LIST_FORM.YEAR') }}</span>
                      {{
                        educationWorkHistoryInit.main_job_career_3_month_to_id
                      }}
                      <span>{{ $t('HR_LIST_FORM.MONTH') }}</span>
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
                  educationWorkHistoryInit.main_job_career_3_department_id
                    .content
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
                  educationWorkHistoryInit.main_job_career_3_job_title_id
                    .content
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
                  v-model="educationWorkHistoryInit.main_job_career_3_textarea"
                  placeholder=""
                  rows="8"
                  max-rows="28"
                  max-lengh="2000"
                  class="textarea-type-detail"
                  :formatter="format2000characters"
                  disabled
                />
                <!-- {{ educationWorkHistoryInit.main_job_career_3_textarea }} -->
              </div>
            </div>
            <!--  -->
          </div>
          <div
            v-if="type_form === 'edit'"
            class="w-100 d-flex flex-column py-3"
            style="gap: 1.25rem"
          >
            <!-- 2.1 年月 Graduation date -> 年月 date -->
            <div class="w-100 d-flex justify-space-center align-center">
              <div class="w-100 d-flex justify-space-between align-center">
                <span style="width: 100%; max-width: 20%" class="'pl-1">{{
                  $t('HR_LIST_FORM.DATE')
                }}</span>
                <!-- !!! chật -->
                <div
                  class="w-100 d-flex flex-column justify-start align-end"
                  style="gap: 0.75rem"
                >
                  <div class="w-100 d-flex justify-start align-center">
                    <!-- From -->
                    <div
                      class="w-100 d-flex justify-space-between align-center"
                      style="gap: 0.5rem"
                    >
                      <div
                        class="d-flex justify-space-between align-center"
                        style="width: 54%; gap: 0.25rem"
                      >
                        <b-form-select
                          v-model="
                            educationWorkHistoryInit.main_job_career_3_year_from_id
                          "
                          :options="date_of_birth_year_options"
                        />
                        <span>{{ $t('HR_LIST_FORM.YEAR') }}</span>
                      </div>

                      <div
                        class="d-flex justify-space-between align-center"
                        style="width: 46%; gap: 0.25rem"
                      >
                        <b-form-select
                          v-model="
                            educationWorkHistoryInit.main_job_career_3_month_from_id
                          "
                          :options="date_of_birth_month_options"
                        />
                        <span>{{ $t('HR_LIST_FORM.MONTH') }}</span>
                      </div>
                    </div>
                    <span class="approximately">~</span>
                    <!-- To -->
                    <div
                      class="w-100 d-flex justify-space-between align-center"
                      style="gap: 0.5rem"
                    >
                      <div
                        class="d-flex justify-space-between align-center"
                        style="width: 54%; gap: 0.25rem"
                      >
                        <b-form-select
                          v-model="
                            educationWorkHistoryInit.main_job_career_3_year_to_id
                          "
                          :options="date_of_birth_year_options"
                          :enabled="
                            !educationWorkHistoryInit.main_job_career_3_time_now
                          "
                          :disabled="
                            educationWorkHistoryInit.main_job_career_3_time_now
                          "
                        />
                        <span>{{ $t('HR_LIST_FORM.YEAR') }}</span>
                      </div>

                      <div
                        class="d-flex justify-space-between align-center"
                        style="width: 46%; gap: 0.25rem"
                      >
                        <b-form-select
                          v-model="
                            educationWorkHistoryInit.main_job_career_3_month_to_id
                          "
                          :options="date_of_birth_month_options"
                          :enabled="
                            !educationWorkHistoryInit.main_job_career_3_time_now
                          "
                          :disabled="
                            educationWorkHistoryInit.main_job_career_3_time_now
                          "
                        />
                        <span>{{ $t('HR_LIST_FORM.MONTH') }}</span>
                      </div>
                    </div>
                  </div>
                  <!-- Time now -->
                  <div
                    class="d-flex justify-end align-center"
                    style="min-width: 12%; gap: 4px"
                  >
                    <b-form-checkbox
                      id="main-job-career-3"
                      v-model="
                        educationWorkHistoryInit.main_job_career_3_time_now
                      "
                      name="main-job-career-3"
                      :value="true"
                      @input="
                        ($event) =>
                          cleacValueWhenDisable($event, 'main-job-career-3')
                      "
                      @click="
                        ($event) =>
                          cleacValueWhenDisable($event, 'main-job-career-3')
                      "
                    />
                    <span>{{ $t('CURRENT') }}</span>
                  </div>
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
                <b-form-select
                  v-model="
                    educationWorkHistoryInit.main_job_career_3_department_id
                  "
                  :options="main_job_career_department_api_common"
                  :enabled="
                    !educationWorkHistoryInit.main_job_career_3_time_now
                  "
                  :disabled="
                    educationWorkHistoryInit.main_job_career_3_time_now
                  "
                />
              </div>
            </div>
            <!-- 2.3 職名 - Job title -->
            <div class="w-100 d-flex justify-space-center align-center">
              <span style="width: 100%; max-width: 20%" class="'pl-1">{{
                $t('HR_LIST_FORM.JOB_TITLE')
              }}</span>
              <div class="w-100 d-flex justify-space-between align-center">
                <b-form-select
                  v-model="
                    educationWorkHistoryInit.main_job_career_3_job_title_id
                  "
                  :options="main_job_career_department_api_common"
                  :enabled="
                    educationWorkHistoryInit.main_job_career_3_department_id ||
                      !educationWorkHistoryInit.main_job_career_3_time_now
                  "
                  :disabled="
                    !educationWorkHistoryInit.main_job_career_3_department_id ||
                      educationWorkHistoryInit.main_job_career_3_time_now
                  "
                />
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
                  v-model="educationWorkHistoryInit.main_job_career_3_textarea"
                  placeholder=""
                  rows="8"
                  max-rows="28"
                  max-lengh="2000"
                  :formatter="format2000characters"
                  :enabled="
                    educationWorkHistoryInit.main_job_career_3_department_id ||
                      !educationWorkHistoryInit.main_job_career_3_time_now
                  "
                  :disabled="
                    !educationWorkHistoryInit.main_job_career_3_department_id ||
                      educationWorkHistoryInit.main_job_career_3_time_now
                  "
                />
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
// import { MakeToast } from '../../utils/toastMessage';
// import { obj2Path } from '@/utils/obj2Path';
// import SearchWithConditions from '../../layout/components/search/SearchWithConditions.vue';
import Require from '@/components/Require/Require.vue';
import Arbitrarily from '@/components/Arbitrarily/Arbitrarily.vue';
import {
  renderYears,
  renderMonth,
  final_education_degree_options,
  final_education_classification_options,
  major_classification_options_api,
  middle_classification_options_api,
  deparment_option_api,
  job_title_manufacturingy_api,
} from '@/pages/Hr/common.js';
import { educationWorkHistoryInit } from '@/pages/Hr/dataHrForm.js';

// const urlAPI = {
//   urlGetLisUser: '/user',
//   urlDeleAll: 'user/ ',
// };
export default {
  name: 'TabBEducationWorkHistoryEdit',
  components: {
    Require,
    Arbitrarily,
  },

  data() {
    return {
      // type_form: 'detail',
      type_form: 'edit',

      educationWorkHistoryInit: educationWorkHistoryInit,
      date_of_birth_year_options: renderYears(),
      date_of_birth_month_options: renderMonth(),

      final_education_classification_options:
        final_education_classification_options,
      final_education_degree_options: final_education_degree_options,

      major_classification_options_api: major_classification_options_api,
      middle_classification_options_api: middle_classification_options_api,

      main_job_career_department_api_common: deparment_option_api,
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
    // this.getListAllData();
  },

  methods: {
    changeStatus() {
      if (this.type_form === 'detail') {
        this.type_form = 'edit';
      } else if (this.type_form === 'edit') {
        this.type_form = 'detail';
      }
    },
    //
    format2characters(e) {
      return String(e).substring(0, 2);
    },
    format20characters(e) {
      return String(e).substring(0, 20);
    },
    format50characters(e) {
      return String(e).substring(0, 50);
    },
    format2000characters(e) {
      return String(e).substring(0, 2000);
    },
    getContentSelect(id) {
      if (id) {
        return id.content;
      }
    },
    // 4 生年月日 - date of birth
    renderDateOfBirthDetail() {
      const text = '2000年10月12日';
      return text;
    },
    countAge() {
      const age = 22;
      return age;
    },
    resultOptionJobTitleDynamic(department_id) {
      const init_arr = [
        { value: null, text: '選択してください', translate: 'please select' },
      ];
      if (!department_id) {
        return init_arr;
      } else if (department_id === 1) {
        return job_title_manufacturingy_api;
      } else if (department_id === 2) {
        return job_title_manufacturingy_api;
      }
    },
    //
    clearInputWithOption() {},
    //
    cleacValueWhenDisable(event, area) {
      if (area === 'main-job-career-1') {
        this.educationWorkHistoryInit.main_job_career_1_year_to_id = null;
        this.educationWorkHistoryInit.main_job_career_1_month_to_id = null;
        this.educationWorkHistoryInit.main_job_career_1_department_id = {
          id: null,
          content: '',
        };
        this.educationWorkHistoryInit.main_job_career_1_job_title_id = {
          id: null,
          content: '',
        };
        this.educationWorkHistoryInit.main_job_career_1_textarea = '';
      } else if (area === 'main-job-career-2') {
        this.educationWorkHistoryInit.main_job_career_2_year_to_id = null;
        this.educationWorkHistoryInit.main_job_career_2_month_to_id = null;
        this.educationWorkHistoryInit.main_job_career_2_department_id = {
          id: null,
          content: '',
        };
        this.educationWorkHistoryInit.main_job_career_2_job_title_id = {
          id: null,
          content: '',
        };

        this.educationWorkHistoryInit.main_job_career_2_textarea = '';
      } else if (area === 'main-job-career-3') {
        this.educationWorkHistoryInit.main_job_career_3_year_to_id = null;
        this.educationWorkHistoryInit.main_job_career_3_month_to_id = null;
        this.educationWorkHistoryInit.main_job_career_3_department_id = {
          id: null,
          content: '',
        };
        this.educationWorkHistoryInit.main_job_career_3_job_title_id = {
          id: null,
          content: '',
        };

        this.educationWorkHistoryInit.main_job_career_3_textarea = '';
      }
    },
    //
  },
};
</script>

<style lang="scss" scoped>
// @import '@/scss/_variables.scss';
// @import '@/scss/modules/common/common.scss';
@import '@/pages/Hr/Detail/TabABasicInformation/TabABasicInformation.scss';
</style>
