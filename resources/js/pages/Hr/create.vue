<template>
  <b-overlay
    :show="overlay.show"
    :blur="overlay.blur"
    :rounded="overlay.sm"
    :style="'min-height: 100vh; height: 100%'"
    :variant="overlay.variant"
    :opacity="overlay.opacity"
  >
    <template #overlay>
      <div class="text-center">
        <b-icon icon="arrow-clockwise" font-scale="3" animation="spin" />
        <p style="margin-top: 10px">{{ $t('PLEASE_WAIT') }}</p>
      </div>
    </template>

    <div class="hr-registration">
      <div class="hr-registration-container">
        <div class="hr-registration-wrap">
          <div class="hr-registration-head">
            <div class="hr-registration-head-title">
              <div class="line" />

              <div>
                <span>{{ $t('HR_REGISTER.TITLE') }}</span>
              </div>
            </div>
          </div>

          <div class="hr-registration-form-autox">
            <div class="hr-registration-category-list">
              <div class="hr-registration-category-item">
                <div class="hr-registration-form-item-title">
                  <span
                    class="hr-basic-information"
                  >◾️ {{ $t('HR_REGISTER.BASIC_INFORMATION') }}</span>
                  <span class="hr-register-title">{{
                    $t('HR_REGISTER.TITLE')
                  }}</span>
                </div>

                <div class="hr-registration-form-item-from">
                  <div class="item-from-wrap border-t border-l border-r">
                    <div class="item-from-title">
                      <span>{{ $t('HR_REGISTER.ORGANIZATION_NAME') }}</span><Require />
                    </div>

                    <div class="item-from-inputs border-l d-flex flex-column" style="width: 60%">
                      <!-- <b-form-input
                        v-if="[2, 4, 5].includes(profile['type'])"
                        v-model="hrCreate.organization_name"
                        class="form-input px-2"
                        :name="'organization-name'"
                        :placeholder="''"
                        disabled
                      /> -->

                      <b-form-select
                        v-model="hrCreate.hr_organization_id"
                        class="form-input px-2"
                        :name="'organization-name'"
                        :placeholder="''"
                        :options="organizationOptions"
                        :text-field="'text_en'"
                        :value="'value'"
                        :disabled-field="'disabled'"
                        @change="handleAutoFillCompanyNameJapanese"
                      />
                      <b-form-invalid-feedback :state="error.hr_organization_id">
                        {{ $t('VALIDATE.REQUIRED_TEXT') }}
                      </b-form-invalid-feedback>
                    </div>
                  </div>

                  <div class="item-from-wrap border-t border-l border-r">
                    <div class="item-from-title">
                      <span>{{
                        $t('HR_REGISTER.ORGANIZATION_NAME_JAPANESE')
                      }}</span><Require />
                    </div>

                    <div class="item-from-inputs border-l" style="width: 60%">
                      <b-form-input
                        v-model="hrCreate.organization_japanese_name"
                        class="form-input px-2"
                        :name="'rganization-japanese-name'"
                        :placeholder="''"
                        disabled
                      />
                    </div>
                  </div>

                  <div class="item-from-wrap border-t border-l border-r">
                    <div class="item-from-title">
                      <span>{{ $t('HR_REGISTER.COUNTRY') }}</span><Require />
                    </div>

                    <div class="item-from-inputs border-l d-flex flex-column" style="width: 60%">
                      <b-form-input
                        v-model="hrCreate.country_name"
                        class="form-input px-2"
                        :name="'country-name'"
                        :placeholder="''"
                        :class="error.country_name === false ? ' is-invalid' : ''"
                        :disabled="!checkRoleAdmin"
                        @input="handleChangeFormData($event, 'country_name')"
                      />
                      <b-form-invalid-feedback :state="error.country_name">
                        {{ $t('VALIDATE.REQUIRED_TEXT') }}
                      </b-form-invalid-feedback>
                    </div>
                  </div>

                  <div class="item-from-wrap border-t border-l border-r">
                    <div class="item-from-title">
                      <span>{{ $t('HR_REGISTER.FULL_NAME') }}</span><Require />
                    </div>

                    <div
                      class="item-from-inputs border-l d-flex flex-column"
                      style="width: 60%"
                    >
                      <b-form-input
                        v-model="hrCreate.full_name"
                        enabled
                        max-lenght="50"
                        :placeholder="''"
                        :name="'full_name'"
                        class="form-input px-2"
                        :formatter="format50characters"
                        :class="error.full_name === false ? ' is-invalid' : ''"
                        @input="handleChangeFormData($event, 'full_name')"
                      />

                      <b-form-invalid-feedback :state="error.full_name">
                        {{ $t('VALIDATE.REQUIRED_TEXT') }}
                      </b-form-invalid-feedback>
                    </div>
                  </div>

                  <div class="item-from-wrap border-t border-l border-r">
                    <div class="item-from-title">
                      <span>{{ $t('HR_REGISTER.FULL_NAMEFURIGANA') }}</span><Require />
                    </div>

                    <div
                      class="item-from-inputs flex-column border-l"
                      style="width: 60%"
                    >
                      <b-form-input
                        v-model="hrCreate.full_name_furigana"
                        enabled
                        max-lenght="50"
                        :placeholder="''"
                        class="form-input px-2"
                        :name="'full_name_furigana'"
                        :formatter="format50characters"
                        :class="
                          error.full_name_furigana === false
                            ? ' is-invalid'
                            : ''
                        "
                        @input="
                          handleChangeFormData($event, 'full_name_furigana')
                        "
                      />

                      <b-form-invalid-feedback
                        :state="error.full_name_furigana"
                      >
                        {{ $t('VALIDATE.REQUIRED_TEXT') }}
                      </b-form-invalid-feedback>
                    </div>
                  </div>

                  <div class="item-from-wrap border-t border-l border-r">
                    <div class="item-from-title">
                      <span>{{ $t('HR_REGISTER.GENDER') }}</span><Arbitrarily />
                    </div>

                    <div class="item-from-inputs border-l" style="width: 60%">
                      <b-form-select
                        v-model="hrCreate.gender_id"
                        :options="gender_option"
                      />
                    </div>
                  </div>

                  <div class="item-from-wrap border-t border-l border-r">
                    <div class="item-from-title">
                      <span>{{ $t('HR_REGISTER.DATE_OF_BIRTH_') }}</span><Require />
                    </div>

                    <div class="item-from-inputs border-l" style="width: 60%">
                      <b-form-select
                        v-model="hrCreate.date_of_birth_year"
                        style="width: 34%; min-width: 150px"
                        :options="year_options"
                        :class="
                          error.date_of_birth_year === false
                            ? ' is-invalid'
                            : ''
                        "
                        @change="
                          handleChangeFormData($event, 'date_of_birth_year')
                        "
                      />

                      <span class="pl-2 pr-5">年</span>

                      <b-form-select
                        v-model="hrCreate.date_of_birth_month"
                        style="width: 33%; min-width: 92px"
                        :options="month_options"
                        :disabled="hrCreate.date_of_birth_year ? false : true"
                        :class="
                          error.date_of_birth_month === false
                            ? ' is-invalid'
                            : ''
                        "
                        @change="
                          renderDay(
                            hrCreate.date_of_birth_year,
                            hrCreate.date_of_birth_month,
                            $event
                          )
                        "
                      />

                      <span class="pl-2 pr-5">月</span>

                      <b-form-select
                        v-model="hrCreate.date_of_birth_day"
                        style="width: 33%; min-width: 92px"
                        :options="day_options"
                        :class="
                          error.date_of_birth_day === false ? ' is-invalid' : ''
                        "
                        :disabled="
                          hrCreate.date_of_birth_year &&
                            hrCreate.date_of_birth_month
                            ? false
                            : true
                        "
                        @change="
                          handleChangeFormData($event, 'date_of_birth_day')
                        "
                      />

                      <span class="pl-2">日</span>
                    </div>
                  </div>

                  <div class="item-from-wrap border-t border-l border-r">
                    <div class="item-from-title">
                      <span>{{ $t('HR_REGISTER.WORK_FORM') }}</span><Arbitrarily />
                    </div>

                    <div class="item-from-inputs border-l" style="width: 60%">
                      <b-form-select
                        v-model="hrCreate.work_form"
                        :options="work_form_option"
                      />
                    </div>
                  </div>

                  <div class="item-from-wrap border-t border-l border-r">
                    <div class="item-from-title">
                      <span>{{ $t('HR_REGISTER.PREFERRED_WORKING_HOUR') }}</span><Arbitrarily />
                    </div>

                    <div
                      class="item-from-inputs border-l d-flex"
                      style="width: 60%"
                    >
                      <span class="pr-4">週</span>

                      <b-form-input
                        v-model="hrCreate.preferred_working_hours"
                        enabled
                        type="number"
                        max-lenght="2"
                        :placeholder="''"
                        style="width: 88%"
                        :name="'full_name'"
                        class="form-input px-2"
                        :formatter="format2characters"
                        :disabled="
                          hrCreate.work_form.content === '正社員' ||
                            !hrCreate.work_form.id
                        "
                      />

                      <span class="pl-4" style="min-width: 12%">時間</span>
                    </div>
                  </div>

                  <div
                    class="item-from-wrap border-t border-l border-r border-b"
                  >
                    <div class="item-from-title">
                      <span>{{ $t('HR_REGISTER.JAPANESE_LEVEL') }}</span><Require />
                    </div>

                    <div class="item-from-inputs border-l" style="width: 60%">
                      <b-form-select
                        v-model="hrCreate.japanese_level"
                        :options="language_requirement_options"
                        :class="
                          error.japanese_level === false ? ' is-invalid' : ''
                        "
                        @change="handleChangeFormData($event, 'japanese_level')"
                      />
                    </div>
                  </div>
                </div>
              </div>

              <div class="hr-registration-category-item">
                <div class="hr-registration-form-item-title">
                  <span>◾️ 学歴・職歴</span>
                </div>

                <div class="hr-registration-form-item-from">
                  <div class="item-from-wrap border-t border-l border-r">
                    <div class="item-from-title">
                      <span>最終学歴（年月）</span><Require />
                    </div>

                    <div class="item-from-inputs border-l" style="width: 40%">
                      <b-form-select
                        v-model="hrCreate.final_education_timing_year"
                        :options="final_education_timing_option"
                        style="min-width: 163px"
                        :class="
                          error.final_education_timing_year === false
                            ? 'is-invalid'
                            : ''
                        "
                        @change="
                          handleChangeFormData(
                            $event,
                            'final_education_timing_year'
                          )
                        "
                      />

                      <span class="pl-2 pr-2">年</span>

                      <b-form-select
                        v-model="hrCreate.final_education_timing_month"
                        :options="month_options"
                        style="min-width: 109px"
                        :class="
                          error.final_education_timing_month === false
                            ? 'is-invalid'
                            : ''
                        "
                        @change="
                          handleChangeFormData(
                            $event,
                            'final_education_timing_month'
                          )
                        "
                      />

                      <span class="pl-2 pr-2">月</span>
                    </div>
                  </div>

                  <div class="item-from-wrap border-t border-l border-r">
                    <div class="item-from-title">
                      <span>最終学歴（区分）</span><Require />
                    </div>

                    <div class="item-from-inputs border-l" style="width: 60%">
                      <b-form-select
                        v-model="hrCreate.final_education_classification"
                        :options="final_education_classification_options"
                        :class="
                          error.final_education_classification === false
                            ? ' is-invalid'
                            : ''
                        "
                        @change="
                          handleChangeFormData(
                            $event,
                            'final_education_classification'
                          )
                        "
                      />
                    </div>
                  </div>

                  <div class="item-from-wrap border-t border-l border-r">
                    <div class="item-from-title">
                      <span>最終学歴（学位）</span><Require />
                    </div>

                    <div class="item-from-inputs border-l" style="width: 60%">
                      <b-form-select
                        v-model="hrCreate.final_education_degree"
                        :options="final_education_degree_options"
                        :class="
                          error.final_education_degree === false
                            ? ' is-invalid'
                            : ''
                        "
                        @change="
                          handleChangeFormData($event, 'final_education_degree')
                        "
                      />
                    </div>
                  </div>

                  <div class="item-from-wrap border-t border-l border-r">
                    <div class="item-from-title">
                      <span>最終学歴（学科）</span><Require />
                    </div>

                    <div class="item-from-inputs border-l" style="width: 100%">
                      <div class="w-100 d-flex flex-column justify-space-between align-center" style="gap: 0.751rem">
                        <div class="w-100 d-flex justify-space-between align-center" style="gap: 0.751rem">
                          <span style="width: 80px">大分類</span>

                          <b-form-select
                            v-model="hrCreate.major_classification"
                            :options="listMainJobDepartment"
                            :class="error.major_classification === false ? ' is-invalid' : ''"
                            @change="handleChangeFormData($event, 'major_classification')"
                          />
                        </div>

                        <div class="w-100 d-flex justify-space-between align-center" style="gap: 0.751rem">
                          <span style="width: 80px">中分類</span>

                          <b-form-select
                            v-model="hrCreate.middle_classification"
                            :options="listSubJobDepartment"
                            :disabled="(hrCreate.major_classification === null || hrCreate.major_classification === 23) ? true : false"
                            :class="error.middle_classification === false ? ' is-invalid' : ''"
                            @change="handleChangeFormData($event, 'middle_classification')"
                          />
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="item-from-wrap border-t border-l border-r">
                    <div class="item-from-title">
                      <span>主な職務経歴 ①</span><Require />
                    </div>

                    <div class="item-from-inputs border-l" style="width: 100%">
                      <div
                        class="w-100 d-flex flex-column justify-space-between align-center"
                        style="gap: 0.751rem"
                      >
                        <div
                          class="w-100 d-flex justify-space-between align-center"
                          style="gap: 0.751rem"
                        >
                          <span style="width: 80px">年月</span>

                          <div class="w-100 d-flex">
                            <div
                              class="w-100 d-flex justify-space-between align-center"
                              style="gap: 1rem"
                            >
                              <div
                                class="w-100 d-flex justify-space-between align-center"
                                style="gap: 1rem"
                              >
                                <div
                                  class="d-flex justify-space-between align-center"
                                  style="width: 54%; gap: 0.5rem"
                                >
                                  <b-form-select
                                    v-model="
                                      hrCreate.main_job_career_1_year_from
                                    "
                                    :options="year_options"
                                    :class="
                                      error.main_job_career_1_year_from ===
                                        false
                                        ? ' is-invalid'
                                        : ''
                                    "
                                    @change="
                                      handleChangeFormData(
                                        $event,
                                        'main_job_career_1_year_from'
                                      )
                                    "
                                  />

                                  <span>年</span>
                                </div>

                                <div
                                  class="d-flex justify-space-between align-center"
                                  style="width: 46%; gap: 0.5rem"
                                >
                                  <b-form-select
                                    v-model="
                                      hrCreate.main_job_career_1_month_from
                                    "
                                    :options="month_options"
                                    :class="
                                      error.main_job_career_1_month_from ===
                                        false
                                        ? ' is-invalid'
                                        : ''
                                    "
                                    @change="
                                      handleChangeFormData(
                                        $event,
                                        'main_job_career_1_month_from'
                                      )
                                    "
                                  />

                                  <span>月</span>
                                </div>
                              </div>

                              <span class="approximately">~</span>

                              <div
                                class="w-100 d-flex justify-space-between align-center"
                                style="gap: 1rem"
                              >
                                <div
                                  class="d-flex justify-space-between align-center"
                                  style="width: 54%; gap: 0.5rem"
                                >
                                  <b-form-select
                                    v-model="hrCreate.main_job_career_1_year_to"
                                    :options="year_options"
                                    :disabled="
                                      hrCreate.main_job_career_1_time_now
                                        ? true
                                        : false
                                    "
                                    :class="
                                      error.main_job_career_1_year_to === false
                                        ? ' is-invalid'
                                        : ''
                                    "
                                    @change="
                                      handleChangeFormData(
                                        $event,
                                        'main_job_career_1_year_to'
                                      )
                                    "
                                  />

                                  <span>年</span>
                                </div>

                                <div
                                  class="d-flex justify-space-between align-center pr-1"
                                  style="width: 46%; gap: 0.5rem"
                                >
                                  <b-form-select
                                    v-model="
                                      hrCreate.main_job_career_1_month_to
                                    "
                                    :options="month_options"
                                    :disabled="
                                      hrCreate.main_job_career_1_time_now
                                        ? true
                                        : false
                                    "
                                    :class="
                                      error.main_job_career_1_month_to === false
                                        ? ' is-invalid'
                                        : ''
                                    "
                                    @change="
                                      handleChangeFormData(
                                        $event,
                                        'main_job_career_1_month_to'
                                      )
                                    "
                                  />

                                  <span>月</span>
                                </div>
                              </div>
                            </div>

                            <div
                              class="d-flex justify-end align-center"
                              style="min-width: 12%; gap: 4px"
                            >
                              <b-form-checkbox
                                id="main-job-career-1"
                                v-model="hrCreate.main_job_career_1_time_now"
                                name="main-job-career-1"
                                @change="
                                  ($event) =>
                                    clearValueWhenDisable(
                                      $event,
                                      'main-job-career-1'
                                    )
                                "
                              />

                              <span>現在</span>
                            </div>
                          </div>
                        </div>

                        <div
                          class="w-100 d-flex justify-space-between align-center"
                          style="gap: 0.751rem"
                        >
                          <span style="width: 80px">所属</span>

                          <b-form-select
                            v-model="hrCreate.main_job_career_1_department"
                            :options="listMainJobOccupation"
                            @change="handleChangeMainJob(1, 1)"
                          />
                        </div>

                        <div
                          class="w-100 d-flex justify-space-between align-center"
                          style="gap: 0.751rem"
                        >
                          <span style="width: 80px">職名</span>

                          <b-form-select
                            v-model="hrCreate.main_job_career_1_job_title"
                            :disabled="hrCreate.main_job_career_1_department === null ? true : false"
                            :options="listSubJobOccupation1"
                          />
                        </div>

                        <div
                          class="w-100 d-flex justify-space-between align-start"
                          style="gap: 0.751rem"
                        >
                          <span style="width: 80px">詳細</span>

                          <b-form-textarea
                            id="textarea"
                            v-model="hrCreate.main_job_career_1_textarea"
                            rows="6"
                            max-rows="28"
                            placeholder=""
                            max-lengh="2000"
                            :formatter="format2000characters"
                          />
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="item-from-wrap border-t border-l border-r">
                    <div class="item-from-title">
                      <span>主な職務経歴 ②</span><Arbitrarily />
                    </div>

                    <div class="item-from-inputs border-l" style="width: 100%">
                      <div
                        class="w-100 d-flex flex-column justify-space-between align-center"
                        style="gap: 0.751rem"
                      >
                        <div
                          class="w-100 d-flex justify-space-between align-center"
                          style="gap: 0.751rem"
                        >
                          <span style="width: 80px">年月</span>

                          <div class="w-100 d-flex">
                            <div
                              class="w-100 d-flex justify-space-between align-center"
                              style="gap: 1rem"
                            >
                              <div
                                class="w-100 d-flex justify-space-between align-center"
                                style="gap: 1rem"
                              >
                                <div
                                  class="d-flex justify-space-between align-center"
                                  style="width: 54%; gap: 0.5rem"
                                >
                                  <b-form-select
                                    v-model="
                                      hrCreate.main_job_career_2_year_from
                                    "
                                    :options="year_options"
                                  />

                                  <span>年</span>
                                </div>

                                <div
                                  class="d-flex justify-space-between align-center"
                                  style="width: 46%; gap: 0.5rem"
                                >
                                  <b-form-select
                                    v-model="
                                      hrCreate.main_job_career_2_month_from
                                    "
                                    :options="month_options"
                                  />

                                  <span>月</span>
                                </div>
                              </div>

                              <span class="approximately">~</span>

                              <div
                                class="w-100 d-flex justify-space-between align-center"
                                style="gap: 1rem"
                              >
                                <div
                                  class="d-flex justify-space-between align-center"
                                  style="width: 54%; gap: 0.5rem"
                                >
                                  <b-form-select
                                    v-model="hrCreate.main_job_career_2_year_to"
                                    :options="year_options"
                                    :disabled="
                                      hrCreate.main_job_career_2_time_now
                                        ? true
                                        : false
                                    "
                                  />

                                  <span>年</span>
                                </div>

                                <div
                                  class="d-flex justify-space-between align-center"
                                  style="width: 46%; gap: 0.5rem"
                                >
                                  <b-form-select
                                    v-model="
                                      hrCreate.main_job_career_2_month_to
                                    "
                                    :options="month_options"
                                    :disabled="
                                      hrCreate.main_job_career_2_time_now
                                        ? true
                                        : false
                                    "
                                  />

                                  <span>月</span>
                                </div>
                              </div>
                            </div>

                            <div
                              class="d-flex justify-end align-center"
                              style="min-width: 12%; gap: 4px"
                            >
                              <b-form-checkbox
                                id="main-job-career-2"
                                v-model="hrCreate.main_job_career_2_time_now"
                                :value="true"
                                name="main-job-career-2"
                                @change="
                                  ($event) =>
                                    clearValueWhenDisable(
                                      $event,
                                      'main-job-career-2'
                                    )
                                "
                              />

                              <span>現在</span>
                            </div>
                          </div>
                        </div>

                        <div
                          class="w-100 d-flex justify-space-between align-center"
                          style="gap: 0.751rem"
                        >
                          <span style="width: 80px">所属</span>

                          <b-form-select
                            v-model="hrCreate.main_job_career_2_department"
                            :options="listMainJobOccupation"
                            @change="handleChangeMainJob(1, 2)"
                          />
                        </div>

                        <div
                          class="w-100 d-flex justify-space-between align-center"
                          style="gap: 0.751rem"
                        >
                          <span style="width: 80px">職名</span>

                          <b-form-select
                            v-model="hrCreate.main_job_career_2_job_title"
                            :disabled="hrCreate.main_job_career_2_department === null ? true : false"
                            :options="listSubJobOccupation2"
                          />
                        </div>

                        <div
                          class="w-100 d-flex justify-space-between align-start"
                          style="gap: 0.751rem"
                        >
                          <span style="width: 80px">詳細</span>

                          <b-form-textarea
                            id="textarea"
                            v-model="hrCreate.main_job_career_2_textarea"
                            rows="6"
                            max-rows="28"
                            placeholder=""
                            max-lengh="2000"
                            :formatter="format2000characters"
                          />
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="item-from-wrap border-t border-l border-r border-b">
                    <div class="item-from-title">
                      <span>主な職務経歴 ③</span><Arbitrarily />
                    </div>

                    <div class="item-from-inputs border-l" style="width: 100%">
                      <div
                        class="w-100 d-flex flex-column justify-space-between align-center"
                        style="gap: 0.751rem"
                      >
                        <div
                          class="w-100 d-flex justify-space-between align-center"
                          style="gap: 0.751rem"
                        >
                          <span style="width: 80px">年月</span>

                          <div class="w-100 d-flex">
                            <div
                              class="w-100 d-flex justify-space-between align-center"
                              style="gap: 1rem"
                            >
                              <div
                                class="w-100 d-flex justify-space-between align-center"
                                style="gap: 1rem"
                              >
                                <div
                                  class="d-flex justify-space-between align-center"
                                  style="width: 54%; gap: 0.5rem"
                                >
                                  <b-form-select
                                    v-model="
                                      hrCreate.main_job_career_3_year_from
                                    "
                                    :options="year_options"
                                  />

                                  <span>年</span>
                                </div>

                                <div
                                  class="d-flex justify-space-between align-center"
                                  style="width: 46%; gap: 0.5rem"
                                >
                                  <b-form-select
                                    v-model="
                                      hrCreate.main_job_career_3_month_from
                                    "
                                    :options="month_options"
                                  />

                                  <span>月</span>
                                </div>
                              </div>

                              <span class="approximately">~</span>

                              <div
                                class="w-100 d-flex justify-space-between align-center"
                                style="gap: 1rem"
                              >
                                <div
                                  class="d-flex justify-space-between align-center"
                                  style="width: 54%; gap: 0.5rem"
                                >
                                  <b-form-select
                                    v-model="hrCreate.main_job_career_3_year_to"
                                    :options="year_options"
                                    :disabled="
                                      hrCreate.main_job_career_3_time_now
                                        ? true
                                        : false
                                    "
                                  />

                                  <span>年</span>
                                </div>

                                <div
                                  class="d-flex justify-space-between align-center"
                                  style="width: 46%; gap: 0.5rem"
                                >
                                  <b-form-select
                                    v-model="
                                      hrCreate.main_job_career_3_month_to
                                    "
                                    :options="month_options"
                                    :disabled="
                                      hrCreate.main_job_career_3_time_now
                                        ? true
                                        : false
                                    "
                                  />

                                  <span>月</span>
                                </div>
                              </div>
                            </div>

                            <div
                              class="d-flex justify-end align-center"
                              style="min-width: 12%; gap: 4px"
                            >
                              <b-form-checkbox
                                id="main-job-career-3"
                                v-model="hrCreate.main_job_career_3_time_now"
                                :value="true"
                                name="main-job-career-3"
                                @change="
                                  ($event) =>
                                    clearValueWhenDisable(
                                      $event,
                                      'main-job-career-3'
                                    )
                                "
                              />

                              <span>現在</span>
                            </div>
                          </div>
                        </div>

                        <div
                          class="w-100 d-flex justify-space-between align-center"
                          style="gap: 0.751rem"
                        >
                          <span style="width: 80px">所属</span>

                          <b-form-select
                            v-model="hrCreate.main_job_career_3_department"
                            :options="listMainJobOccupation"
                            @change="handleChangeMainJob(1, 3)"
                          />
                        </div>

                        <div
                          class="w-100 d-flex justify-space-between align-center"
                          style="gap: 0.751rem"
                        >
                          <span style="width: 80px">職名</span>

                          <b-form-select
                            v-model="hrCreate.main_job_career_3_job_title"
                            :disabled="hrCreate.main_job_career_3_department === null ? true : false"
                            :options="listSubJobOccupation3"
                          />
                        </div>

                        <div
                          class="w-100 d-flex justify-space-between align-start"
                          style="gap: 0.751rem"
                        >
                          <span style="width: 80px">詳細</span>

                          <b-form-textarea
                            id="textarea"
                            v-model="hrCreate.main_job_career_3_textarea"
                            rows="6"
                            max-rows="28"
                            placeholder=""
                            max-lengh="2000"
                            :formatter="format2000characters"
                          />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="hr-registration-category-item">
                <div class="hr-registration-form-item-title">
                  <span>◾️ 自己PR・備考</span>
                </div>

                <div class="hr-registration-form-item-from">
                  <div class="item-from-wrap border-t border-l border-r">
                    <div class="item-from-title">
                      <span>自己PR・特記事項</span><Arbitrarily />
                    </div>

                    <div class="w-100 item-from-inputs border-l">
                      <div
                        class="w-100 h-100 d-flex justify-space-between align-center"
                        style="gap: 0.751rem"
                      >
                        <b-form-textarea
                          id="textarea"
                          v-model="hrCreate.personal_pr_special_textarea"
                          rows="8"
                          max-rows="28"
                          placeholder=""
                          max-lengh="2000"
                          :formatter="format2000characters"
                        />
                      </div>
                    </div>
                  </div>

                  <div
                    class="item-from-wrap border-t border-l border-r border-b"
                  >
                    <div class="item-from-title">
                      <span>備考</span><Arbitrarily />
                    </div>

                    <div class="w-100 item-from-inputs border-l">
                      <div
                        class="w-100 h-100 d-flex justify-space-between align-center"
                        style="gap: 0.751rem"
                      >
                        <b-form-textarea
                          id="textarea"
                          v-model="hrCreate.remarks_textarea"
                          rows="8"
                          max-rows="28"
                          placeholder=""
                          max-lengh="2000"
                          :formatter="format2000characters"
                        />
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="hr-registration-category-item">
                <div class="hr-registration-form-item-title">
                  <span>◾️ 連絡先</span>
                </div>

                <div class="hr-registration-form-item-from">
                  <div class="item-from-wrap border-t border-l border-r">
                    <div class="item-from-title">
                      <span>連絡先電話番号</span><Arbitrarily />
                    </div>

                    <div class="w-100 item-from-inputs border-l">
                      <div
                        class="w-100 d-flex flex-column justify-space-between align-center"
                        style="gap: 0.751rem"
                      >
                        <div
                          class="w-100 d-flex justify-space-between align-center"
                          style="gap: 0.751rem"
                        >
                          <b-form-select
                            v-model="hrCreate.telephone_phone_number_id"
                            :options="phone_number_options_common"
                            style="min-width: 153px; width: 28%"
                            @change="
                              pushAreaCode(
                                hrCreate.telephone_phone_number_id,
                                'telephone_phone_number'
                              )
                            "
                          />

                          <b-form-input
                            v-model="hrCreate.telephone_phone_number"
                            max-lenght="50"
                            type="number"
                            class="form-input px-2"
                            :name="'mail address'"
                            :placeholder="'例) 0312345678 ハイフン無しで入力'"
                            :disabled="
                              hrCreate.telephone_phone_number_id == null
                                ? true
                                : false
                            "
                            style="width: 100%"
                          />
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="item-from-wrap border-t border-l border-r">
                    <div class="item-from-title">
                      <span>携帯電話番号</span><Arbitrarily />
                    </div>

                    <div class="w-100 item-from-inputs border-l">
                      <div
                        class="w-100 d-flex flex-column justify-space-between align-center"
                        style="gap: 0.751rem"
                      >
                        <div
                          class="w-100 d-flex justify-space-between align-center"
                          style="gap: 0.751rem"
                        >
                          <b-form-select
                            v-model="hrCreate.mobile_phone_number_id"
                            :options="phone_number_options_common"
                            style="min-width: 153px; width: 28%"
                            @change="
                              pushAreaCode(
                                hrCreate.mobile_phone_number_id,
                                'mobile_phone_number'
                              )
                            "
                          />

                          <b-form-input
                            v-model="hrCreate.mobile_phone_number"
                            max-lenght="50"
                            type="number"
                            class="form-input px-2"
                            :name="'mail address'"
                            :placeholder="'例) 09012345678 ハイフン無しで入力'"
                            :disabled="
                              hrCreate.mobile_phone_number_id === null
                                ? true
                                : false
                            "
                            style="width: 100%"
                          />
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="item-from-wrap border-t border-l border-r">
                    <div class="item-from-title">
                      <span>メールアドレス</span><Require />
                    </div>

                    <div class="w-100 item-from-inputs border-l">
                      <b-form-input
                        v-model="hrCreate.mail_address"
                        max-lenght="50"
                        :placeholder="''"
                        style="width: 100%"
                        :name="'mail address'"
                        class="form-input px-2"
                        :formatter="format50characters"
                        :class="
                          error.mail_address === false ? 'is-invalid' : ''
                        "
                        @change="handleChangeFormData($event, 'mail_address')"
                      />
                    </div>
                  </div>

                  <div class="item-from-wrap border-t border-l border-r">
                    <div class="item-from-title">
                      <span>現住所</span><Arbitrarily />
                    </div>

                    <div
                      class="w-100 item-from-inputs border-l"
                      style="padding-top: 1.751rem; padding-bottom: 1.751rem"
                    >
                      <div
                        class="w-100 d-flex flex-column justify-space-between align-center"
                        style="gap: 1.751rem"
                      >
                        <div
                          class="w-100 d-flex justify-space-between align-center"
                          style="gap: 0.751rem"
                        >
                          <span style="width: 80px">市</span>

                          <b-form-input
                            v-model="hrCreate.address_city"
                            max-lenght="50"
                            :formatter="format50characters"
                            style="width: 85%"
                          />
                        </div>

                        <div
                          class="w-100 d-flex justify-space-between align-center"
                          style="gap: 0.751rem"
                        >
                          <span style="width: 80px">町</span>

                          <b-form-input
                            v-model="hrCreate.address_district"
                            max-lenght="50"
                            :formatter="format50characters"
                            style="width: 85%"
                          />
                        </div>

                        <div
                          class="w-100 d-flex justify-space-between align-center"
                          style="gap: 0.751rem"
                        >
                          <span style="width: 80px">番地</span>

                          <b-form-input
                            v-model="hrCreate.address_number"
                            max-lenght="50"
                            :formatter="format50characters"
                            style="width: 85%"
                          />
                        </div>

                        <div
                          class="w-100 d-flex justify-space-between align-center"
                          style="gap: 0.751rem"
                        >
                          <span style="width: 80px">その他</span>

                          <b-form-input
                            v-model="hrCreate.address_other"
                            max-lenght="50"
                            :formatter="format50characters"
                            style="width: 85%"
                          />
                        </div>
                      </div>
                    </div>
                  </div>

                  <div
                    class="item-from-wrap border-t border-l border-r border-b"
                  >
                    <div class="item-from-title">
                      <span>現住所</span><Arbitrarily />
                    </div>

                    <div
                      class="w-100 item-from-inputs border-l"
                      style="padding-top: 1.751rem; padding-bottom: 1.751rem"
                    >
                      <div
                        class="w-100 d-flex flex-column justify-space-between align-center"
                        style="gap: 1.751rem"
                      >
                        <div
                          class="w-100 d-flex justify-space-between align-center"
                          style="gap: 0.751rem"
                        >
                          <span style="width: 80px">市</span>

                          <b-form-input
                            v-model="hrCreate.hometown_address_city"
                            max-lenght="50"
                            :formatter="format50characters"
                            style="width: 85%"
                          />
                        </div>

                        <div
                          class="w-100 d-flex justify-space-between align-center"
                          style="gap: 0.751rem"
                        >
                          <span style="width: 80px">町</span>

                          <b-form-input
                            v-model="hrCreate.hometown_address_district"
                            max-lenght="50"
                            :formatter="format50characters"
                            style="width: 85%"
                          />
                        </div>

                        <div
                          class="w-100 d-flex justify-space-between align-center"
                          style="gap: 0.751rem"
                        >
                          <span style="width: 80px">番地</span>

                          <b-form-input
                            v-model="hrCreate.hometown_address_number"
                            max-lenght="50"
                            :formatter="format50characters"
                            style="width: 85%"
                          />
                        </div>

                        <div
                          class="w-100 d-flex justify-space-between align-center"
                          style="gap: 0.751rem"
                        >
                          <span style="width: 80px">その他</span>

                          <b-form-input
                            v-model="hrCreate.hometown_address_other"
                            max-lenght="50"
                            :formatter="format50characters"
                            style="width: 85%"
                          />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="hr-registration-form-item-btn">
                <div
                  id="hr-create-cancel"
                  class="cancel-btn btn"
                  @click="handleToggleConfirmLeavingModal"
                >
                  <span class="cancel-text">キャンセル</span>
                </div>

                <div
                  id="hr-create-save"
                  class="register-btn btn"
                  @click="handleRegisterHr()"
                >
                  <span class="register-text">保存</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <b-modal
        ref="confirm modal"
        v-model="statusModalConfirmLeaving"
        hide-header
        hide-footer
        title="Using Component Methods"
      >
        <div class="modal-body-content">
          <div
            class="modal-content-title-del-hr"
            style="font-size: 22px; line-height: 24px"
          >
            <span>入力内容が破棄されます。</span>
            <span>このページから移動してもよろしいですか？</span>
          </div>

          <div class="hr-list-btns">
            <div class="btn" @click="handleToggleConfirmLeavingModal">
              <span>キャンセル</span>
            </div>

            <div
              id="leaving-create-hr"
              class="btn accept"
              @click="handleConfirmStilConfirmLeaving"
            >
              <span>取り込み</span>
            </div>
          </div>
        </div>
      </b-modal>
    </div>
  </b-overlay>
</template>

<script>
import i18n from '@/lang';
import Require from '@/components/Require/Require.vue';
import Arbitrarily from '@/components/Arbitrarily/Arbitrarily.vue';

import { postHr } from '@/api/modules/hr';
import { MakeToast } from '@/utils/toastMessage';
import { getListCompany } from '@/api/modules/company';
import { getClassificationListDepartment, getClassificationListOccupation } from '@/api/modules/hr';
import {
  deparment_option_api,
  job_title_agriculture_forestry_fishery_api,
  job_title_manufacturingy_api,
} from '@/pages/Hr/common.js';
import {
  renderYears,
  renderMonth,
  gender_option,
  work_form_option,
  provincesr_option,
  format50characters,
  renderYearsEducationTiming,
  language_requirement_options,
  final_education_degree_options,
  major_classification_options_api,
  middle_classification_options_api,
  final_education_classification_options,
} from '@/pages/Hr/common.js';

const urlAPI = {
  urlCreateHr: '/hr',
  urlGetListCompany: '/company',
  urlGetJobList: '/job-type',
};

export default {
  name: 'HrCreate',
  components: {
    Require,
    Arbitrarily,
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

      organizationOptions: [
        { value: null, text_en: '選択してください', text_ja: '', disabled: true },
      ],

      statusModalConfirmLeaving: false,

      hrCreate: {
        hr_organization_id: '',

        organization_name: '',
        organization_japanese_name: '',

        country_id: null,
        country_name: '',

        full_name: '',
        full_name_furigana: '',
        gender_id: { id: null, content: '' },
        date_of_birth_year: null,
        date_of_birth_month: null,
        date_of_birth_day: null,
        work_form: { id: null, content: '' },
        preferred_working_hours: '',
        japanese_level: { id: null, content: '' },

        final_education_timing_year: null,
        final_education_timing_month: null,
        final_education_classification: { id: null, content: '' },
        final_education_degree: { id: null, content: '' },

        major_classification: null,
        middle_classification: null,

        main_job_career_1_year_from: null,
        main_job_career_1_month_from: null,
        main_job_career_1_year_to: null,
        main_job_career_1_month_to: null,
        main_job_career_1_time_now: false,
        main_job_career_1_department: null,
        main_job_career_1_job_title: null,
        main_job_career_1_textarea: '',

        main_job_career_2_year_from: null,
        main_job_career_2_month_from: null,
        main_job_career_2_year_to: null,
        main_job_career_2_month_to: null,
        main_job_career_2_time_now: false,
        main_job_career_2_department: null,
        main_job_career_2_job_title: null,
        main_job_career_2_textarea: '',

        main_job_career_3_year_from: null,
        main_job_career_3_month_from: null,
        main_job_career_3_year_to: null,
        main_job_career_3_month_to: null,
        main_job_career_3_time_now: false,
        main_job_career_3_department: null,
        main_job_career_3_job_title: null,
        main_job_career_3_textarea: '',

        personal_pr_special_textarea: '',
        remarks_textarea: '',

        telephone_phone_number_id: null,
        telephone_phone_number: '',
        mobile_phone_number_id: null,
        mobile_phone_number: '',
        mail_address: '',
        address_city: '',
        address_district: '',
        address_number: '',
        address_other: '',
        hometown_address_city: '',
        hometown_address_district: '',
        hometown_address_number: '',
        hometown_address_other: '',
      },

      COUNTRY_LIST: provincesr_option,
      gender_option: gender_option,
      year_options: renderYears(),
      month_options: renderMonth(),
      day_options: [],
      work_form_option: work_form_option,
      language_requirement_options: language_requirement_options,

      final_education_timing_option: renderYearsEducationTiming(),
      final_education_classification_options:
      final_education_classification_options,
      final_education_degree_options: final_education_degree_options,
      major_classification_options_api: major_classification_options_api,
      middle_classification_options_api: middle_classification_options_api,
      main_job_career_department_api_common: deparment_option_api,
      job_title_agriculture_forestry_fishery_api:
      job_title_agriculture_forestry_fishery_api,

      phone_number_options_common: [
        { value: null, text: '選択してください' },
        { value: 1, text: '+84' },
        { value: 2, text: '+81' },
      ],

      error: {
        full_name: '',
        country_name: '',
        full_name_furigana: '',
        date_of_birth_year: '',
        date_of_birth_month: '',
        date_of_birth_day: '',
        japanese_level: '',
        final_education_timing_year: '',
        final_education_timing_month: '',
        final_education_classification: '',
        final_education_degree: '',
        major_classification: '',
        middle_classification: '',
        main_job_career_1_year_from: '',
        main_job_career_1_month_from: '',
        main_job_career_1_year_to: '',
        main_job_career_1_month_to: '',
        mail_address: '',
      },

      listMainJobDepartment: [{ value: null, text: '選択してください', sub_options: [], disabled: true }],
      listSubJobDepartment: [{ value: null, text: '選択してください', sub_options: [], disabled: true }],

      listMainJobOccupation: [{ value: null, text: '選択してください', sub_options: [], disabled: true }],
      listSubJobOccupation1: [{ value: null, text: '選択してください', sub_options: [], disabled: true }],
      listSubJobOccupation2: [{ value: null, text: '選択してください', sub_options: [], disabled: true }],
      listSubJobOccupation3: [{ value: null, text: '選択してください', sub_options: [], disabled: true }],
    };
  },
  computed: {
    profile() {
      return this.$store.getters.profile;
    },
    checkRoleAdmin() {
      const PROFILE = this.$store.getters.profile;
      const list_role_admin = [1, 3];
      return list_role_admin.includes(PROFILE.type);
    },
    job_title_manufacturingy_api() {
      const JOB_TITLE_MANUFACTURINGY_API = job_title_manufacturingy_api;
      JOB_TITLE_MANUFACTURINGY_API.unshift({
        value: null,
        text: '選択してください',
        translate: 'Please select',
      });
      return JOB_TITLE_MANUFACTURINGY_API;
    },
  },
  mounted() {},
  created() {
    this.handleInitComponent();
  },
  methods: {
    async handleInitComponent() {
      await this.handleGetListCompany();
      await this.handleFillInDefaultData();
      await this.handleGetListJobDepartment();
      await this.handleGetListJobOccupation();
    },
    handleFillInDefaultData() {
      if (this.profile['hr_organization']) {
        const COUNTRY = provincesr_option.find((item) => {
          return item
            ? item['value']['id'] === this.profile['hr_organization']['country']
            : 'asca';
        });

        this.hrCreate.hr_organization_id = this.profile['hr_organization']['id'];

        this.hrCreate.organization_name = this.profile['hr_organization']['corporate_name_en'];
        this.hrCreate.organization_japanese_name = this.profile['hr_organization']['corporate_name_ja'];

        this.hrCreate.country_id = COUNTRY['value']['id'];
        this.hrCreate.country_name = COUNTRY['text'];
      }
    },
    async handleGetListCompany() {
      const URL = `${urlAPI.urlGetListCompany}`;

      try {
        const response = await getListCompany(URL);

        if (response['code'] === 200) {
          const DATA = response['data']['result'];

          DATA.forEach((item) => {
            this.organizationOptions.push(
              { value: item['id'], text_en: `${item['company_name']}`, text_ja: `${item['company_name_jp']}`, disabled: false }
            );
          });
        }
      } catch (error) {
        console.log(error);
      }
    },
    format2characters(e) {
      return String(e).substring(0, 2);
    },
    format20characters(e) {
      return String(e).substring(0, 20);
    },
    format50characters(e) {
      const maxLength = format50characters(e);
      return maxLength;
    },
    format2000characters(e) {
      return String(e).substring(0, 2000);
    },
    resultOptionJobTitleDynamic(department_id) {
      const init_arr = [
        { value: null, text: '選択してください', translate: 'Please select' },
      ];

      if (department_id) {
        if (department_id === 1) {
          return this.job_title_manufacturingy_api;
        } else if (department_id === 2) {
          return this.job_title_manufacturingy_api;
        }
      } else {
        return init_arr;
      }
    },
    pushAreaCode(number_id_option, type_number_phone) {
      if (
        number_id_option.id === 1 &&
        type_number_phone === 'telephone_phone_number'
      ) {
        this.hrCreate.telephone_phone_number = number_id_option.content;
      } else if (
        number_id_option.id === 2 &&
        type_number_phone === 'telephone_phone_number'
      ) {
        this.hrCreate.telephone_phone_number = number_id_option.content;
      } else if (
        number_id_option.id === 1 &&
        type_number_phone === 'mobile_phone_number'
      ) {
        this.hrCreate.mobile_phone_number = number_id_option.content;
      } else if (
        number_id_option.id === 2 &&
        type_number_phone === 'mobile_phone_number'
      ) {
        this.hrCreate.mobile_phone_number = number_id_option.content;
      }
    },
    clearValueWhenDisable(event, area) {
      if (area === 'main-job-career-1') {
        this.hrCreate.main_job_career_1_year_to = '';
        this.hrCreate.main_job_career_1_month_to = '';
      } else if (area === 'main-job-career-2') {
        this.hrCreate.main_job_career_2_year_to = '';
        this.hrCreate.main_job_career_2_month_to = '';
      } else if (area === 'main-job-career-3') {
        this.hrCreate.main_job_career_3_year_to = '';
        this.hrCreate.main_job_career_3_month_to = '';
      }
    },
    async handleRegisterHr() {
      if (this.handleValidateFormData()) {
        try {
          let main_job_1;
          let main_job_2;
          let main_job_3;

          let main_job_career_date_to_1;
          let main_job_career_date_to_2;
          let main_job_career_date_to_3;

          if (this.hrCreate.main_job_career_1_year_to && this.formatDateTime(this.hrCreate.main_job_career_1_month_to)) {
            main_job_career_date_to_1 = `${this.hrCreate.main_job_career_1_year_to}-${this.formatDateTime(this.hrCreate.main_job_career_1_month_to)}`;
          }

          if (this.hrCreate.main_job_career_2_year_to && this.formatDateTime(this.hrCreate.main_job_career_2_month_to)) {
            main_job_career_date_to_2 = `${this.hrCreate.main_job_career_2_year_to}-${this.formatDateTime(this.hrCreate.main_job_career_2_month_to)}`;
          }

          if (this.hrCreate.main_job_career_3_year_to && this.formatDateTime(this.hrCreate.main_job_career_3_month_to)) {
            main_job_career_date_to_3 = `${this.hrCreate.main_job_career_3_year_to}-${this.formatDateTime(this.hrCreate.main_job_career_3_month_to)}`;
          }

          if (this.hrCreate.main_job_career_1_year_from && this.hrCreate.main_job_career_1_month_from) {
            main_job_1 = {
              main_job_career_date_from: `${this.hrCreate.main_job_career_1_year_from}-${this.formatDateTime(this.hrCreate.main_job_career_1_month_from)}`,
              to_now: this.hrCreate.main_job_career_1_time_now ? 'yes' : 'no',
              main_job_career_date_to: main_job_career_date_to_1 || '',
              department_id: this.hrCreate.main_job_career_1_department,
              job_id: this.hrCreate.main_job_career_1_job_title,
              detail: this.hrCreate.main_job_career_1_textarea,
            };
          } else {
            main_job_1 = null;
          }

          if (this.hrCreate.main_job_career_2_year_from && this.hrCreate.main_job_career_2_month_from) {
            main_job_2 = {
              main_job_career_date_from: `${this.hrCreate.main_job_career_2_year_from}-${this.formatDateTime(this.hrCreate.main_job_career_2_month_from)}`,
              to_now: this.hrCreate.main_job_career_2_time_now ? 'yes' : 'no',
              main_job_career_date_to: main_job_career_date_to_2 || '',
              department_id: this.hrCreate.main_job_career_2_department,
              job_id: this.hrCreate.main_job_career_2_job_title,
              detail: this.hrCreate.main_job_career_2_textarea,
            };
          } else {
            main_job_2 = null;
          }

          if (this.hrCreate.main_job_career_3_year_from && this.hrCreate.main_job_career_3_month_from) {
            main_job_3 = {
              main_job_career_date_from: `${this.hrCreate.main_job_career_3_year_from}-${this.formatDateTime(this.hrCreate.main_job_career_3_month_from)}`,
              to_now: this.hrCreate.main_job_career_3_time_now ? 'yes' : 'no',
              main_job_career_date_to: main_job_career_date_to_3 || '',
              department_id: this.hrCreate.main_job_career_3_department,
              job_id: this.hrCreate.main_job_career_3_job_title,
              detail: this.hrCreate.main_job_career_3_textarea,
            };
          } else {
            main_job_3 = null;
          }

          const TEMP_ARR = [];

          if (main_job_1) {
            TEMP_ARR.push(main_job_1);
          }

          if (main_job_2) {
            TEMP_ARR.push(main_job_2);
          }

          if (main_job_3) {
            TEMP_ARR.push(main_job_3);
          }

          const MAIN_JOBS = JSON.stringify(TEMP_ARR);

          const DATA = {
            hr_organization_id: this.hrCreate.hr_organization_id,
            country_id: this.hrCreate.country_id,
            full_name: this.hrCreate.full_name,
            full_name_ja: this.hrCreate.full_name_furigana,
            gender: this.hrCreate.gender_id['id'],
            date_of_birth: `${this.hrCreate.date_of_birth_year}-${this.formatDateTime(this.hrCreate.date_of_birth_month)}-${this.formatDateTime(this.hrCreate.date_of_birth_day)}`,
            work_form: this.hrCreate.work_form['id'],
            preferred_working_hours: this.hrCreate.preferred_working_hours,
            japanese_level: this.hrCreate.japanese_level['id'],

            final_education_date: `${this.hrCreate.final_education_timing_year}-${this.formatDateTime(this.hrCreate.final_education_timing_month)}`,
            final_education_classification: this.hrCreate.final_education_classification['id'],
            final_education_degree: this.hrCreate.final_education_degree['id'],
            major_classification_id: this.hrCreate.major_classification,
            middle_classification_id: this.hrCreate.middle_classification,

            main_jobs: MAIN_JOBS,

            personal_pr_special_notes: this.hrCreate.personal_pr_special_textarea,
            remarks: this.hrCreate.remarks_textarea,

            telephone_number: this.hrCreate.telephone_phone_number,
            mobile_phone_number: this.hrCreate.mobile_phone_number,
            mail_address: this.hrCreate.mail_address,
            address_city: this.hrCreate.address_city,
            address_district: this.hrCreate.address_district,
            address_number: this.hrCreate.address_number,
            address_other: this.hrCreate.address_other,
            hometown_city: this.hrCreate.hometown_address_city,
            home_town_district: this.hrCreate.hometown_address_district,
            home_town_number: this.hrCreate.hometown_address_number,
            home_town_other: this.hrCreate.hometown_address_other,
          };

          const URL = `${urlAPI.urlCreateHr}`;

          const response = await postHr(URL, DATA);

          if (response.code === 200) {
            MakeToast({
              variant: 'success',
              title: this.$t('SUCCESS'),
              content: this.$t('人事情報の登録が完了しました'),
            });

            this.$router.push({ path: `/hr/list` }, (onAbort) => {});
          } else {
            MakeToast({
              variant: 'warning',
              title: this.$t('WARNING'),
              content: response.message || 'ERROR',
            });
          }
        } catch (error) {
          console.log(error);
        }
      } else {
        MakeToast({
          variant: 'warning',
          title: this.$t('WARNING'),
          content: 'VALIDATION FAIL',
        });
      }
    },
    handleValidateFormData() {
      let result = false;

      if (this.hrCreate.country_name === '') {
        this.error.country_name = false;
      } else if (this.hrCreate.full_name === '') {
        this.error.full_name = false;
      } else if (this.hrCreate.full_name_furigana === '') {
        this.error.full_name_furigana = false;
      } else if (this.hrCreate.date_of_birth_year === null) {
        this.error.date_of_birth_year = false;
      } else if (this.hrCreate.date_of_birth_month === null) {
        this.error.date_of_birth_month = false;
      } else if (this.hrCreate.date_of_birth_day === null) {
        this.error.date_of_birth_day = false;
      } else if (this.hrCreate.japanese_level['id'] === null) {
        this.error.japanese_level = false;
      } else if (this.hrCreate.final_education_timing_year === null) {
        this.error.final_education_timing_year = false;
      } else if (this.hrCreate.final_education_timing_month === null) {
        this.error.final_education_timing_month = false;
      } else if (this.hrCreate.final_education_classification['id'] === null) {
        this.error.final_education_classification = false;
      } else if (this.hrCreate.final_education_degree['id'] === null) {
        this.error.final_education_degree = false;
      } else if (this.hrCreate.major_classification === null) {
        this.error.major_classification = false;
      } else if (this.hrCreate.middle_classification === null) {
        this.error.middle_classification = false;
      } else if (this.hrCreate.main_job_career_1_year_from === null) {
        this.error.main_job_career_1_year_from = false;
      } else if (this.hrCreate.main_job_career_1_month_from === null) {
        this.error.main_job_career_1_month_from = false;
      } else if (
        this.hrCreate.main_job_career_1_year_from !== null &&
        this.hrCreate.main_job_career_1_month_from !== null &&
        !this.hrCreate.main_job_career_1_time_now
      ) {
        if (this.hrCreate.main_job_career_1_year_to === null) {
          this.error.main_job_career_1_year_to = false;
        } else if (this.hrCreate.main_job_career_1_month_to === null) {
          this.error.main_job_career_1_month_to = false;
        } else if (this.hrCreate.mail_address === '') {
          this.error.mail_address = false;
        } else {
          return true;
        }
      } else if (this.hrCreate.mail_address === '') {
        this.error.mail_address = false;
      } else {
        result = true;
      }

      return result;
    },
    handleChangeFormData(event, field) {
      const newValue = event;

      switch (field) {
        case 'full_name':
          this.error.full_name = '';
          if (newValue) {
            this.error.full_name = true;
          } else {
            this.error.full_name = false;
          }
          break;
        case 'country_name':
          this.error.country_name = '';
          if (newValue) {
            this.error.country_name = true;
          } else {
            this.error.country_name = false;
          }
          break;
        case 'full_name_furigana':
          this.error.full_name_furigana = '';
          if (newValue) {
            this.error.full_name_furigana = true;
          } else {
            this.error.full_name_furigana = false;
          }
          break;
        case 'date_of_birth_year':
          this.error.date_of_birth_year = '';
          if (newValue) {
            this.error.date_of_birth_year = true;
          } else {
            this.error.date_of_birth_year = false;
          }
          break;
        case 'date_of_birth_month':
          this.error.date_of_birth_month = '';
          if (newValue) {
            this.error.date_of_birth_month = true;
          } else {
            this.error.date_of_birth_month = false;
          }
          break;
        case 'date_of_birth_day':
          this.error.date_of_birth_day = '';
          if (newValue) {
            this.error.date_of_birth_day = true;
          } else {
            this.error.date_of_birth_day = false;
          }
          break;
        case 'japanese_level':
          this.error.japanese_level = '';
          if (newValue) {
            this.error.japanese_level = true;
          } else {
            this.error.japanese_level = false;
          }
          break;
        case 'final_education_timing_year':
          this.error.final_education_timing_year = '';
          if (newValue) {
            this.error.final_education_timing_year = true;
          } else {
            this.error.final_education_timing_year = false;
          }
          break;
        case 'final_education_timing_month':
          this.error.final_education_timing_month = '';
          if (newValue) {
            this.error.final_education_timing_month = true;
          } else {
            this.error.final_education_timing_month = false;
          }
          break;
        case 'final_education_classification':
          this.error.final_education_classification = '';
          if (newValue) {
            this.error.final_education_classification = true;
          } else {
            this.error.final_education_classification = false;
          }
          break;
        case 'final_education_degree':
          this.error.final_education_degree = '';
          if (newValue) {
            this.error.final_education_degree = true;
          } else {
            this.error.final_education_degree = false;
          }
          break;
        case 'major_classification':
          this.handleChangeMainJob(2);

          this.error.major_classification = '';

          if (newValue) {
            this.error.major_classification = true;
          } else {
            this.error.major_classification = false;
          }
          break;
        case 'middle_classification':
          this.error.middle_classification = '';
          if (newValue) {
            this.error.middle_classification = true;
          } else {
            this.error.middle_classification = false;
          }
          break;
        case 'main_job_career_1_year_from':
          this.error.main_job_career_1_year_from = '';
          if (newValue) {
            this.error.main_job_career_1_year_from = true;
          } else {
            this.error.main_job_career_1_year_from = false;
          }
          break;
        case 'main_job_career_1_month_from':
          this.error.main_job_career_1_month_from = '';
          if (newValue) {
            this.error.main_job_career_1_month_from = true;
          } else {
            this.error.main_job_career_1_month_from = false;
          }
          break;
        case 'main_job_career_1_year_to':
          this.error.main_job_career_1_year_to = '';
          if (newValue) {
            this.error.main_job_career_1_year_to = true;
          } else {
            this.error.main_job_career_1_year_to = false;
          }
          break;
        case 'main_job_career_1_month_to':
          this.error.main_job_career_1_month_to = '';
          if (newValue) {
            this.error.main_job_career_1_month_to = true;
          } else {
            this.error.main_job_career_1_month_to = false;
          }
          break;
        case 'mail_address':
          this.error.mail_address = '';
          if (newValue) {
            this.error.mail_address = true;
          } else {
            this.error.mail_address = false;
          }
          break;
        default:
          break;
      }
    },
    handCancelCreateHr() {
      const editting = true;

      if (editting) {
        MakeToast({
          variant: 'warning',
          title: i18n.t('警告'),
          content: i18n.t(
            '入力内容が破棄されます。, このページから移動してもよろしいですか？'
          ),
        });
      }

      this.$router.push({ path: `/hr/list` }, (onAbort) => {});
    },
    handleToggleConfirmLeavingModal() {
      if (this.statusModalConfirmLeaving === true) {
        this.statusModalConfirmLeaving = false;
      } else {
        this.statusModalConfirmLeaving = true;
      }
    },
    handleConfirmStilConfirmLeaving() {
      console.log('handleConfirmStilConfirmLeaving');
    },
    renderDay(year, month, event) {
      let result;

      if (year && month) {
        this.handleChangeFormData(event, 'date_of_birth_month');
        const daysInMonth = new Date(year, month, 0).getDate();
        const daysArray = Array.from({ length: daysInMonth }, (_, i) => i + 1);
        result = daysArray;
      } else {
        result = [];
      }

      this.day_options = result;
    },
    formatDateTime(month) {
      return parseInt(month) < 10 ? `0${month}` : month;
    },
    handleAutoFillCompanyNameJapanese(event) {
      if (event) {
        this.organizationOptions.forEach((item) => {
          if (item.value === event) {
            this.hrCreate.organization_japanese_name = item.text_ja;
          }
        });
      }
    },
    async handleGetListJobDepartment() {
      this.listMainJobDepartment = [{ value: null, text: '選択してください', sub_options: [], disabled: true }];

      const TYPE = 2;

      const URL = `${urlAPI.urlGetJobList}?type=${TYPE}`;

      try {
        const response = await getClassificationListDepartment(URL);

        if (response['code'] === 200) {
          const DATA = response['data'];

          DATA.forEach((item) => {
            this.listMainJobDepartment.push(
              { value: item['id'], text: item['name_ja'], sub_options: item['job_info'], disabled: false }
            );
          });
        }
      } catch (error) {
        console.log(error);
      }
    },
    async handleGetListJobOccupation() {
      this.listMainJobOccupation = [{ value: null, text: '選択してください', sub_options: [], disabled: true }];

      const TYPE = 1;

      const URL = `${urlAPI.urlGetJobList}?type=${TYPE}`;

      try {
        const response = await getClassificationListOccupation(URL);

        if (response['code'] === 200) {
          const DATA = response['data'];

          DATA.forEach((item) => {
            this.listMainJobOccupation.push(
              { value: item['id'], text: item['name_ja'], sub_options: item['job_info'], disabled: false }
            );
          });
        }
      } catch (error) {
        console.log(error);
      }
    },
    handleChangeMainJob(type, index) {
      let SUB_OPTIONS = [];

      if (type === 1) {
        switch (index) {
          case 1:
            this.listMainJobOccupation.find((item) => {
              if (item['value'] === this.hrCreate.main_job_career_1_department) {
                SUB_OPTIONS = item['sub_options'];
              }
            });

            this.hrCreate.main_job_career_1_job_title = null;

            break;
          case 2:
            this.listMainJobOccupation.find((item) => {
              if (item['value'] === this.hrCreate.main_job_career_2_department) {
                SUB_OPTIONS = item['sub_options'];
              }
            });

            this.hrCreate.main_job_career_2_job_title = null;

            break;
          case 3:
            this.listMainJobOccupation.find((item) => {
              if (item['value'] === this.hrCreate.main_job_career_3_department) {
                SUB_OPTIONS = item['sub_options'];
              }
            });

            this.hrCreate.main_job_career_3_job_title = null;

            break;
          default:
            break;
        }
      } else if (type === 2) {
        this.listMainJobDepartment.find((item) => {
          if (item['value'] === this.hrCreate.major_classification) {
            SUB_OPTIONS = item['sub_options'];
          }
        });

        this.hrCreate.middle_classification = null;
      }

      switch (type) {
        case 1:
          this.listSubJobOccupation = [{ value: null, text: '選択してください', disabled: true }];

          if (SUB_OPTIONS.length > 0) {
            switch (index) {
              case 1:
                SUB_OPTIONS.forEach((item) => {
                  this.listSubJobOccupation1.push(
                    { value: item['id'], text: item['name_ja'], disabled: false }
                  );
                });
                break;
              case 2:
                SUB_OPTIONS.forEach((item) => {
                  this.listSubJobOccupation2.push(
                    { value: item['id'], text: item['name_ja'], disabled: false }
                  );
                });
                break;
              case 3:
                SUB_OPTIONS.forEach((item) => {
                  this.listSubJobOccupation3.push(
                    { value: item['id'], text: item['name_ja'], disabled: false }
                  );
                });
                break;
              default:
                break;
            }
          } else {
            this.listSubJobOccupation = [{ value: null, text: '選択してください', disabled: true }];
          }
          break;
        case 2:
          this.listSubJobDepartment = [{ value: null, text: '選択してください', disabled: true }];

          if (SUB_OPTIONS.length > 0) {
            SUB_OPTIONS.forEach((item) => {
              this.listSubJobDepartment.push(
                { value: item['id'], text: item['name_ja'], disabled: false }
              );
            });
          } else {
            this.listSubJobDepartment = [{ value: null, text: '選択してください', disabled: true }];
          }
          break;
        default:
          break;
      }
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/common/common.scss';
@import '@/components/Modal/ModalStyle.scss';

.btn {
  width: 230px !important;
  min-width: 230px !important;
}

.hr-registration {
  width: 100%;
  display: flex;
  min-height: 100vh;
  align-items: stretch;
  justify-content: center;
}

.hr-registration-container {
  width: 100%;
  display: flex;
  align-items: stretch;
  justify-content: center;
}

.hr-registration-wrap {
  width: 100%;
  display: flex;
  align-items: stretch;
  padding: 3.25rem 0rem;
  flex-direction: column;
  justify-content: flex-start;
}

.hr-registration-head {
  display: flex;
  align-items: stretch;
  justify-content: space-between;
}

.hr-registration-head-title {
  gap: 1rem;
  display: flex;
  align-items: stretch;
  justify-content: flex-start;

  & div {
    display: flex;
    align-items: center;
    justify-content: flex-start;

    & span {
      color: #000000;
      font-size: 24px;
      font-weight: 700;
      line-height: 29px;
    }
  }
}

.line {
  width: 4px;
  background: #304cad;
  border-radius: 10px;
  border: 1px solid #304cad;
}

.hr-registration-form-autox {
  flex: 1;
  display: flex;
  margin-top: 1rem;
  overflow-x: auto;
  align-items: stretch;
  flex-direction: column;
  justify-content: flex-start;
}

.hr-registration-category-list {
  flex: 1;
  display: flex;
  gap: 1.875rem;
  margin-top: 1rem;
  align-items: stretch;
  flex-direction: column;
  justify-content: flex-start;
}

.hr-registration-category-item {
  flex: 1%;
  width: 100%;
  gap: 1.25rem;
  display: flex;
  min-width: 1052px;
  align-items: stretch;
  flex-direction: column;
  justify-content: flex-start;
}

.hr-registration-form-item-title {
  border-radius: 3px;
  background: #f7f7f7;
  padding: 1rem 0.75rem;
  border: 1px solid #adadad;
  box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
}

.hr-registration-form-item-from {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  justify-content: flex-start;
}

.item-from-wrap {
  width: 100%;
  display: flex;
  align-items: stretch;
  justify-content: flex-start;
}

.item-from-title {
  width: 280px;
  gap: 1.25rem;
  display: flex;
  min-width: 280px;
  min-height: 80px;
  padding: 1.5rem 1.25rem;
  align-items: flex-start;
  justify-content: flex-start;

  & span {
    color: $black;
    font-size: 16px;
    font-weight: 400;
    line-height: 18px;
  }
}

.item-from-inputs {
  width: 100%;
  color: $black;
  display: flex;
  font-size: 16px;
  font-weight: 400;
  line-height: 18px;
  align-items: center;
  justify-content: center;
  padding: 0.5rem 2.281rem;
}

.hr-registration-form-item-btn {
  width: 100%;
  gap: 0.75rem;
  display: flex;
  align-items: center;
  justify-content: center;

  & div {
    height: 40px;
    display: flex;
    margin-top: 5rem;
    width: fit-content;
    border-radius: 40px;
    align-items: center;
    flex-direction: column;
    padding: 0.5rem 3.75rem;
    justify-content: center;

    & span {
      color: #ffffff;
      font-size: 20px;
      font-weight: 400;
      line-height: 24px;
      text-align: center;
    }
  }
}

#hr-create-cancel {
  background: #c1c1c1;
}

#hr-create-save {
  background: #f9be00;
}

.border-t {
  border-top: 1px solid #adadad;
}

.border-r {
  border-right: 1px solid #adadad;
}

.border-l {
  border-left: 1px solid #adadad;
}

.border-b {
  border-bottom: 1px solid #adadad;
}

.approximately {
  color: #000000;
  font-size: 24px;
  font-weight: 400;
  line-height: 36px;
}

::v-deep .custom-select:disabled,
.form-control:disabled,
.form-control[readonly] {
  background: #ededed;
}

::v-deep .dropdown-menu {
  right: 0;
}

@media only screen and (max-width: 576px) {
  .hr-registration-wrap {
    padding: 0.5rem 0.25rem;
  }

  .item-from-title {
    width: 168px;
  }
}
</style>
