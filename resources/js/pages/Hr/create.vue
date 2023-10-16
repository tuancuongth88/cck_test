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
        <div class="hr-registration-wrap pt-2">
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
                  >◾️ {{ $t('HR_REGISTER.BASIC_INFORMATION') }}
                  </span>
                </div>

                <div class="hr-registration-form-item-from">
                  <div class="item-from-wrap border-t border-l border-r">
                    <div class="item-from-title d-flex justify-content-between bg-gray">
                      <span>{{ $t('HR_REGISTER.ORGANIZATION_NAME') }}</span><Require />
                    </div>

                    <div
                      class="item-from-inputs d-flex flex-column"
                      style="width: 60%"
                    >
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
                        dusk="organization_name"
                        :name="'organization-name'"
                        :placeholder="''"
                        :class="
                          error.hr_organization_id === false ? 'is-invalid' : ''
                        "
                        :options="organizationOptions"
                        :text-field="'text_en'"
                        :value="'value'"
                        :disabled-field="'disabled'"
                        @change="handleAutoFillCompanyNameJapanese"
                      />
                      <b-form-invalid-feedback
                        :state="error.hr_organization_id"
                      >
                        {{ $t('VALIDATE.REQUIRED_SELECT') }}
                      </b-form-invalid-feedback>
                    </div>
                  </div>

                  <div class="item-from-wrap border-t border-l border-r">
                    <div class="item-from-title d-flex justify-content-between bg-gray">
                      <span>{{
                        $t('HR_REGISTER.ORGANIZATION_NAME_JAPANESE')
                      }}</span><Require />
                    </div>

                    <div class="item-from-inputs" style="width: 60%">
                      <div class="full-width">
                        <b-form-input
                          v-model="hrCreate.organization_japanese_name"
                          class="form-input px-2"
                          dusk="organization_name_ja"
                          :name="'rganization-japanese-name'"
                          :placeholder="''"
                          :class="
                            error.hr_organization_id === false
                              ? 'is-invalid'
                              : ''
                          "
                          :disabled="profile['type'] !== 3"
                        />
                        <b-form-invalid-feedback
                          :state="error.hr_organization_id"
                        >
                          {{ $t('VALIDATE.REQUIRED_TEXT') }}
                        </b-form-invalid-feedback>
                      </div>
                    </div>
                  </div>
                  <div class="item-from-wrap border-t border-l border-r">
                    <div class="item-from-title d-flex justify-content-between bg-gray">
                      <span>{{ $t('HR_REGISTER.COUNTRY') }}</span><Arbitrarily />
                    </div>

                    <div
                      class="item-from-inputs d-flex flex-column"
                      style="width: 60%"
                    >
                      <b-form-input
                        v-model="hrCreate.country_name"
                        class="form-input px-2"
                        dusk="country_name"
                        :name="'country-name'"
                        :placeholder="''"
                        @input="handleChangeFormData($event, 'country_name')"
                      />
                    </div>
                  </div>

                  <div class="item-from-wrap border-t border-l border-r">
                    <div class="item-from-title d-flex justify-content-between bg-gray">
                      <span>{{ $t('HR_REGISTER.FULL_NAME') }}</span><Require />
                    </div>

                    <div
                      class="item-from-inputs d-flex flex-column"
                      style="width: 60%"
                    >
                      <b-form-input
                        v-model="hrCreate.full_name"
                        enabled
                        max-lenght="50"
                        :placeholder="''"
                        :name="'full_name'"
                        class="form-input px-2"
                        dusk="full_name"
                        :formatter="format50characters"
                        :class="error.full_name === false ? 'is-invalid' : ''"
                        @input="handleChangeFormData($event, 'full_name')"
                      />

                      <b-form-invalid-feedback :state="error.full_name">
                        {{ $t('VALIDATE.REQUIRED_TEXT') }}
                      </b-form-invalid-feedback>
                    </div>
                  </div>

                  <div class="item-from-wrap border-t border-l border-r">
                    <div class="item-from-title d-flex justify-content-between bg-gray">
                      <span>{{ $t('HR_REGISTER.FULL_NAMEFURIGANA') }}</span><Require />
                    </div>

                    <div
                      class="item-from-inputs flex-column"
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
                          error.full_name_furigana === false ? 'is-invalid' : ''
                        "
                        dusk="full_name_furigana"
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
                    <div class="item-from-title d-flex justify-content-between bg-gray">
                      <span>{{ $t('HR_REGISTER.GENDER') }}</span><Arbitrarily />
                    </div>

                    <div
                      id="dusk_gender"
                      class="item-from-inputs"
                      style="width: 60%"
                    >
                      <b-form-select
                        v-model="hrCreate.gender_id"
                        dusk="gender"
                        :options="gender_option"
                      />
                    </div>
                  </div>

                  <div class="item-from-wrap border-t border-l border-r">
                    <div class="item-from-title d-flex justify-content-between bg-gray">
                      <span>{{ $t('HR_REGISTER.DATE_OF_BIRTH_') }}</span><Require />
                    </div>

                    <div class="item-from-inputs" style="width: 60%">
                      <div class="full-width">
                        <div class="d-flex" style="align-items: center">
                          <b-form-select
                            v-model="hrCreate.date_of_birth_year"
                            dusk="date_of_birth_year"
                            style="width: 34%; min-width: 150px"
                            :options="year_options"
                            :class="
                              error.date_of_birth_year === false
                                ? 'is-invalid'
                                : ''
                            "
                            @change="
                              handleChangeFormData($event, 'date_of_birth_year')
                            "
                          />

                          <span class="pl-2 pr-5">{{
                            $t('HR_LIST_FORM.YEAR')
                          }}</span>
                        </div>
                        <b-form-invalid-feedback
                          :state="error.date_of_birth_year"
                        >
                          {{ $t('VALIDATE.REQUIRED_SELECT') }}
                        </b-form-invalid-feedback>
                      </div>

                      <div class="full-width">
                        <div class="d-flex" style="align-items: center">
                          <b-form-select
                            v-model="hrCreate.date_of_birth_month"
                            dusk="date_of_birth_month"
                            style="width: 33%; min-width: 92px"
                            :options="month_options"
                            :disabled="
                              hrCreate.date_of_birth_year ? false : true
                            "
                            :class="
                              error.date_of_birth_month === false
                                ? 'is-invalid'
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

                          <span class="pl-2 pr-5">{{
                            $t('JOB_SEARCH.MONTH')
                          }}</span>
                        </div>
                        <b-form-invalid-feedback
                          :state="error.date_of_birth_month"
                        >
                          {{ $t('VALIDATE.REQUIRED_SELECT') }}
                        </b-form-invalid-feedback>
                      </div>

                      <div class="full-width">
                        <div class="d-flex" style="align-items: center">
                          <b-form-select
                            v-model="hrCreate.date_of_birth_day"
                            dusk="date_of_birth_day"
                            style="width: 33%; min-width: 92px"
                            :options="day_options"
                            :class="
                              error.date_of_birth_day === false
                                ? 'is-invalid'
                                : ''
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
                        <b-form-invalid-feedback
                          :state="error.date_of_birth_day"
                        >
                          {{ $t('VALIDATE.REQUIRED_SELECT') }}
                        </b-form-invalid-feedback>
                      </div>
                    </div>
                  </div>

                  <div class="item-from-wrap border-t border-l border-r">
                    <div class="item-from-title d-flex justify-content-between bg-gray">
                      <span>{{ $t('HR_REGISTER.WORK_FORM') }}</span><Arbitrarily />
                    </div>

                    <div
                      id="dusk_work_form"
                      class="item-from-inputs"
                      style="width: 60%"
                    >
                      <b-form-select
                        v-model="hrCreate.work_form"
                        dusk="work_form"
                        :options="work_form_option"
                      />
                    </div>
                  </div>

                  <div class="item-from-wrap border-t border-l border-r">
                    <div class="item-from-title d-flex justify-content-between bg-gray">
                      <span>{{ $t('HR_REGISTER.PREFERRED_WORKING_HOUR') }}</span><Arbitrarily />
                    </div>

                    <div
                      class="item-from-inputs d-flex"
                      style="width: 60%"
                    >
                      <span class="pr-4">週</span>

                      <b-form-input
                        v-model="hrCreate.preferred_working_hours"
                        dusk="work_form_part_time"
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
                    <div class="item-from-title d-flex justify-content-between bg-gray">
                      <span>{{ $t('HR_REGISTER.JAPANESE_LEVEL') }}</span><Require />
                    </div>

                    <div class="item-from-inputs" style="width: 60%">
                      <div id="dusk_japanese_level" class="full-width">
                        <b-form-select
                          v-model="hrCreate.japanese_level"
                          dusk="japanese_level"
                          :options="language_requirement_options"
                          :class="
                            error.japanese_level === false ? 'is-invalid' : ''
                          "
                          @change="
                            handleChangeFormData($event, 'japanese_level')
                          "
                        />
                        <b-form-invalid-feedback :state="error.japanese_level">
                          {{ $t('VALIDATE.REQUIRED_SELECT') }}
                        </b-form-invalid-feedback>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="hr-registration-category-item">
                <div class="hr-registration-form-item-title">
                  <span>◾️ {{ $t('HR_LIST_FORM.ACADEMIC_BACKGROUND') }}</span>
                </div>

                <div class="hr-registration-form-item-from">
                  <div class="item-from-wrap border-t border-l border-r">
                    <div class="item-from-title d-flex justify-content-between bg-gray">
                      <span>
                        {{
                          $t('HR_LIST_FORM.FINAL_EDUCATION_CLASSIFICATION')
                        }}</span><Require />
                    </div>

                    <div class="item-from-inputs" style="width: 40%">
                      <div class="full-width">
                        <div class="d-flex" style="align-items: center">
                          <b-form-select
                            v-model="hrCreate.final_education_timing_year"
                            dusk="final_education_timing_year"
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

                          <span class="pl-2 pr-2">{{
                            $t('HR_LIST_FORM.YEAR')
                          }}</span>
                        </div>
                        <b-form-invalid-feedback
                          :state="error.final_education_timing_year"
                        >
                          {{ $t('VALIDATE.REQUIRED_SELECT') }}
                        </b-form-invalid-feedback>
                      </div>

                      <div class="full-width">
                        <div class="d-flex" style="align-items: center">
                          <b-form-select
                            v-model="hrCreate.final_education_timing_month"
                            dusk="final_education_timing_month"
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

                          <span class="pl-2 pr-2">{{
                            $t('JOB_SEARCH.MONTH')
                          }}</span>
                        </div>
                        <b-form-invalid-feedback
                          :state="error.final_education_timing_month"
                        >
                          {{ $t('VALIDATE.REQUIRED_SELECT') }}
                        </b-form-invalid-feedback>
                      </div>
                    </div>
                  </div>

                  <div class="item-from-wrap border-t border-l border-r">
                    <div class="item-from-title d-flex justify-content-between bg-gray">
                      <span>{{
                        $t('HR_LIST_FORM.FINAL_EDUCATION_CLASSIFICATION')
                      }}</span><Require />
                    </div>

                    <div class="item-from-inputs" style="width: 60%">
                      <div id="dusk_final_education_class" class="full-width">
                        <b-form-select
                          v-model="hrCreate.final_education_classification"
                          dusk="final_education_classification"
                          :options="final_education_classification_options"
                          :class="
                            error.final_education_classification === false
                              ? 'is-invalid'
                              : ''
                          "
                          @change="
                            handleChangeFormData(
                              $event,
                              'final_education_classification'
                            )
                          "
                        />
                        <b-form-invalid-feedback
                          :state="error.final_education_classification"
                        >
                          {{ $t('VALIDATE.REQUIRED_SELECT') }}
                        </b-form-invalid-feedback>
                      </div>
                    </div>
                  </div>

                  <div class="item-from-wrap border-t border-l border-r">
                    <div class="item-from-title d-flex justify-content-between bg-gray">
                      <span>最終学歴（学位）</span><Require />
                    </div>

                    <div class="item-from-inputs" style="width: 60%">
                      <div id="dusk_final_education_degree" class="full-width">
                        <b-form-select
                          v-model="hrCreate.final_education_degree"
                          dusk="final_education_degree"
                          :options="final_education_degree_options"
                          :class="
                            error.final_education_degree === false
                              ? 'is-invalid'
                              : ''
                          "
                          @change="
                            handleChangeFormData(
                              $event,
                              'final_education_degree'
                            )
                          "
                        />
                        <b-form-invalid-feedback
                          :state="error.final_education_degree"
                        >
                          {{ $t('VALIDATE.REQUIRED_SELECT') }}
                        </b-form-invalid-feedback>
                      </div>
                    </div>
                  </div>

                  <div class="item-from-wrap border-t border-l border-r">
                    <div class="item-from-title d-flex justify-content-between bg-gray">
                      <span>最終学歴（学科）</span><Require />
                    </div>

                    <div class="item-from-inputs" style="width: 100%">
                      <div
                        class="w-100 d-flex flex-column justify-space-between align-center"
                        style="gap: 0.751rem"
                      >
                        <div
                          class="w-100 d-flex justify-space-between align-center"
                          style="gap: 0.751rem"
                        >
                          <span style="width: 80px">{{
                            $t('COMPANY_REGISTER.MAJOR_CLASSIFICATION')
                          }}</span>

                          <div class="full-width">
                            <b-form-select
                              v-model="hrCreate.major_classification"
                              dusk="major_classification"
                              :options="listMainJobDepartment"
                              :class="
                                error.major_classification === false
                                  ? 'is-invalid'
                                  : ''
                              "
                              @change="
                                handleChangeFormData(
                                  $event,
                                  'major_classification'
                                )
                              "
                            />
                            <b-form-invalid-feedback
                              :state="error.major_classification"
                            >
                              {{ $t('VALIDATE.REQUIRED_SELECT') }}
                            </b-form-invalid-feedback>
                          </div>
                        </div>

                        <div
                          class="w-100 d-flex justify-space-between align-center"
                          style="gap: 0.751rem"
                        >
                          <span style="width: 80px">{{
                            $t('COMPANY_REGISTER.MIDDLE_CLASSIFICATION')
                          }}</span>

                          <div class="full-width">
                            <b-form-select
                              v-model="hrCreate.middle_classification"
                              dusk="middle_classification"
                              :options="listSubJobDepartment"
                              :disabled="hrCreate.major_classification === null"
                              :class="
                                error.middle_classification === false
                                  ? 'is-invalid'
                                  : ''
                              "
                              @change="
                                handleChangeFormData(
                                  $event,
                                  'middle_classification'
                                )
                              "
                            />
                            <b-form-invalid-feedback
                              :state="error.middle_classification"
                            >
                              {{ $t('VALIDATE.REQUIRED_SELECT') }}
                            </b-form-invalid-feedback>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="item-from-wrap border-t border-l border-r">
                    <div class="item-from-title d-flex justify-content-between bg-gray">
                      <span>主な職務経歴 ①</span><Arbitrarily />
                    </div>

                    <div class="item-from-inputs" style="width: 100%">
                      <div
                        class="w-100 d-flex flex-column justify-space-between align-center"
                        style="gap: 0.751rem"
                      >
                        <div
                          class="w-100 d-flex justify-space-between align-center"
                          style="gap: 0.751rem"
                        >
                          <span style="width: 80px">{{
                            $t('HR_LIST_FORM.YEAR_MONTH')
                          }}</span>

                          <div class="w-100 d-flex">
                            <div
                              class="w-85 d-flex justify-space-between align-center"
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
                                  <div class="full-width">
                                    <div
                                      class="d-flex"
                                      style="align-items: center; gap: 0.5rem"
                                    >
                                      <b-form-select
                                        v-model="
                                          hrCreate.main_job_career_1_year_from
                                        "
                                        dusk="main_job_career_1_year_from"
                                        :options="year_not_requied_options"
                                        :class="
                                          error.main_job_career_1_year_from ===
                                            false
                                            ? 'is-invalid'
                                            : ''
                                        "
                                        @change="
                                          handleChangeFormData(
                                            $event,
                                            'main_job_career_1_year_from'
                                          )
                                        "
                                      />

                                      <span>{{ $t('HR_LIST_FORM.YEAR') }}</span>
                                    </div>
                                    <b-form-invalid-feedback
                                      :state="error.main_job_career_1_year_from"
                                    >
                                      {{ $t('VALIDATE.REQUIRED_SELECT') }}
                                    </b-form-invalid-feedback>
                                  </div>
                                </div>

                                <div
                                  class="d-flex justify-space-between align-center"
                                  style="width: 46%; gap: 0.5rem"
                                >
                                  <div class="full-width">
                                    <div
                                      class="d-flex"
                                      style="align-items: center; gap: 0.5rem"
                                    >
                                      <b-form-select
                                        v-model="
                                          hrCreate.main_job_career_1_month_from
                                        "
                                        dusk="main_job_career_1_month_from"
                                        :options="month_not_requied_options"
                                        :class="
                                          error.main_job_career_1_month_from ===
                                            false
                                            ? 'is-invalid'
                                            : ''
                                        "
                                        @change="
                                          handleChangeFormData(
                                            $event,
                                            'main_job_career_1_month_from'
                                          )
                                        "
                                      />

                                      <span>{{ $t('JOB_SEARCH.MONTH') }}</span>
                                    </div>
                                    <b-form-invalid-feedback
                                      :state="
                                        error.main_job_career_1_month_from
                                      "
                                    >
                                      {{ $t('VALIDATE.REQUIRED_SELECT') }}
                                    </b-form-invalid-feedback>
                                  </div>
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
                                    dusk="main_job_career_1_year_to"
                                    :options="year_not_requied_options"
                                    :disabled="
                                      hrCreate.main_job_career_1_time_now
                                        ? true
                                        : false
                                    "
                                    :class="
                                      error.main_job_career_1_year_to === false
                                        ? 'is-invalid'
                                        : ''
                                    "
                                    @change="
                                      handleChangeFormData(
                                        $event,
                                        'main_job_career_1_year_to'
                                      )
                                    "
                                  />

                                  <span>{{ $t('HR_LIST_FORM.YEAR') }}</span>
                                </div>

                                <div
                                  class="d-flex justify-space-between align-center pr-1"
                                  style="width: 46%; gap: 0.5rem"
                                >
                                  <b-form-select
                                    v-model="
                                      hrCreate.main_job_career_1_month_to
                                    "
                                    dusk="main_job_career_1_month_to"
                                    :options="month_not_requied_options"
                                    :disabled="
                                      hrCreate.main_job_career_1_time_now
                                        ? true
                                        : false
                                    "
                                    :class="
                                      error.main_job_career_1_month_to === false
                                        ? 'is-invalid'
                                        : ''
                                    "
                                    @change="
                                      handleChangeFormData(
                                        $event,
                                        'main_job_career_1_month_to'
                                      )
                                    "
                                  />

                                  <span>{{ $t('JOB_SEARCH.MONTH') }}</span>
                                </div>
                              </div>
                            </div>

                            <div
                              class="w-15 d-flex justify-content-end align-items-center"
                            >
                              <b-form-checkbox
                                id="main-job-career-1"
                                v-model="hrCreate.main_job_career_1_time_now"
                                dusk="main-job-career-1"
                                name="main-job-career-1"
                                size="lg"
                                style="top: 4px"
                                @change="
                                  ($event) =>
                                    clearValueWhenDisable(
                                      $event,
                                      'main-job-career-1'
                                    )
                                "
                              />

                              <span>{{ $t('CURRENT') }}</span>
                            </div>
                          </div>
                        </div>

                        <div
                          class="w-100 d-flex justify-space-between align-center"
                          style="gap: 0.751rem"
                        >
                          <span style="width: 80px">{{
                            $t('AFFILIATION')
                          }}</span>

                          <div class="full-width">
                            <b-form-select
                              v-model="hrCreate.main_job_career_1_department"
                              dusk="main_job_career_1_department"
                              :class="
                                error.main_job_career_1_department === false
                                  ? 'is-invalid'
                                  : ''
                              "
                              :options="listMainJobOccupationNotRequied"
                              @change="handleChangeMainJob(1, 1)"
                            />
                            <b-form-invalid-feedback
                              :state="error.main_job_career_1_department"
                            >
                              {{ $t('VALIDATE.REQUIRED_SELECT') }}
                            </b-form-invalid-feedback>
                          </div>
                        </div>

                        <div
                          class="w-100 d-flex justify-space-between align-center"
                          style="gap: 0.751rem"
                        >
                          <span style="width: 80px">{{
                            $t('HR_LIST_FORM.JOB_TITLE')
                          }}</span>

                          <div class="full-width">
                            <b-form-select
                              v-model="hrCreate.main_job_career_1_job_title"
                              dusk="main_job_career_1_job_title"
                              :class="
                                error.main_job_career_1_job_title === false
                                  ? 'is-invalid'
                                  : ''
                              "
                              :disabled="
                                hrCreate.main_job_career_1_department === null
                                  ? true
                                  : false
                              "
                              :options="listSubJobOccupation1"
                              @change="
                                handleChangeFormData(
                                  $event,
                                  'main_job_career_1_job_title'
                                )
                              "
                            />
                            <b-form-invalid-feedback
                              :state="error.main_job_career_1_job_title"
                            >
                              {{ $t('VALIDATE.REQUIRED_SELECT') }}
                            </b-form-invalid-feedback>
                          </div>
                        </div>

                        <div
                          class="w-100 d-flex justify-space-between align-start"
                          style="gap: 0.751rem"
                        >
                          <span style="width: 80px">詳細</span>

                          <div class="full-width">
                            <b-form-textarea
                              id="textarea"
                              v-model="hrCreate.main_job_career_1_textarea"
                              dusk="main_job_career_1_detail"
                              :class="
                                error.main_job_career_1_textarea === false
                                  ? 'is-invalid'
                                  : ''
                              "
                              rows="6"
                              max-rows="28"
                              placeholder=""
                              max-lengh="2000"
                              :formatter="format2000characters"
                              @input="
                                handleChangeFormData(
                                  $event,
                                  'main_job_career_1_textarea'
                                )
                              "
                            />
                            <b-form-invalid-feedback
                              :state="error.main_job_career_1_textarea"
                            >
                              {{ $t('VALIDATE.REQUIRED_TEXT') }}
                            </b-form-invalid-feedback>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="item-from-wrap border-t border-l border-r">
                    <div class="item-from-title d-flex justify-content-between bg-gray">
                      <span>主な職務経歴 ②</span><Arbitrarily />
                    </div>

                    <div class="item-from-inputs" style="width: 100%">
                      <div
                        class="w-100 d-flex flex-column justify-space-between align-center"
                        style="gap: 0.751rem"
                      >
                        <div
                          class="w-100 d-flex justify-space-between align-center"
                          style="gap: 0.751rem"
                        >
                          <span style="width: 80px">{{
                            $t('HR_LIST_FORM.YEAR_MONTH')
                          }}</span>

                          <div class="w-100 d-flex">
                            <div
                              class="w-85 d-flex justify-space-between align-center"
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
                                    :options="year_not_requied_options"
                                  />

                                  <span>{{ $t('HR_LIST_FORM.YEAR') }}</span>
                                </div>

                                <div
                                  class="d-flex justify-space-between align-center"
                                  style="width: 46%; gap: 0.5rem"
                                >
                                  <b-form-select
                                    v-model="
                                      hrCreate.main_job_career_2_month_from
                                    "
                                    :options="month_not_requied_options"
                                  />

                                  <span>{{ $t('JOB_SEARCH.MONTH') }}</span>
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
                                    :options="year_not_requied_options"
                                    :disabled="
                                      hrCreate.main_job_career_2_time_now
                                        ? true
                                        : false
                                    "
                                  />

                                  <span>{{ $t('HR_LIST_FORM.YEAR') }}</span>
                                </div>

                                <div
                                  class="d-flex justify-space-between align-center"
                                  style="width: 46%; gap: 0.5rem"
                                >
                                  <b-form-select
                                    v-model="
                                      hrCreate.main_job_career_2_month_to
                                    "
                                    :options="month_not_requied_options"
                                    :disabled="
                                      hrCreate.main_job_career_2_time_now
                                        ? true
                                        : false
                                    "
                                  />

                                  <span>{{ $t('JOB_SEARCH.MONTH') }}</span>
                                </div>
                              </div>
                            </div>

                            <div
                              class="w-15 d-flex justify-content-end align-items-center"
                            >
                              <b-form-checkbox
                                id="main-job-career-2"
                                v-model="hrCreate.main_job_career_2_time_now"
                                :value="true"
                                size="lg"
                                style="top: 4px"
                                name="main-job-career-2"
                                @change="
                                  ($event) =>
                                    clearValueWhenDisable(
                                      $event,
                                      'main-job-career-2'
                                    )
                                "
                              />

                              <span>{{ $t('CURRENT') }}</span>
                            </div>
                          </div>
                        </div>

                        <div
                          class="w-100 d-flex justify-space-between align-center"
                          style="gap: 0.751rem"
                        >
                          <span style="width: 80px">{{
                            $t('AFFILIATION')
                          }}</span>

                          <b-form-select
                            v-model="hrCreate.main_job_career_2_department"
                            :options="listMainJobOccupationNotRequied"
                            @change="handleChangeMainJob(1, 2)"
                          />
                        </div>

                        <div
                          class="w-100 d-flex justify-space-between align-center"
                          style="gap: 0.751rem"
                        >
                          <span style="width: 80px">{{
                            $t('HR_LIST_FORM.JOB_TITLE')
                          }}</span>

                          <b-form-select
                            v-model="hrCreate.main_job_career_2_job_title"
                            :disabled="
                              hrCreate.main_job_career_2_department === null
                                ? true
                                : false
                            "
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

                  <div
                    class="item-from-wrap border-t border-l border-r border-b"
                  >
                    <div class="item-from-title d-flex justify-content-between bg-gray">
                      <span>主な職務経歴 ③</span><Arbitrarily />
                    </div>

                    <div class="item-from-inputs" style="width: 100%">
                      <div
                        class="w-100 d-flex flex-column justify-space-between align-center"
                        style="gap: 0.751rem"
                      >
                        <div
                          class="w-100 d-flex justify-space-between align-center"
                          style="gap: 0.751rem"
                        >
                          <span style="width: 80px">{{
                            $t('HR_LIST_FORM.YEAR_MONTH')
                          }}</span>

                          <div class="w-100 d-flex">
                            <div
                              class="w-85 d-flex justify-space-between align-center"
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
                                    :options="year_not_requied_options"
                                  />

                                  <span>{{ $t('HR_LIST_FORM.YEAR') }}</span>
                                </div>

                                <div
                                  class="d-flex justify-space-between align-center"
                                  style="width: 46%; gap: 0.5rem"
                                >
                                  <b-form-select
                                    v-model="
                                      hrCreate.main_job_career_3_month_from
                                    "
                                    :options="month_not_requied_options"
                                  />

                                  <span>{{ $t('JOB_SEARCH.MONTH') }}</span>
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
                                    :options="year_not_requied_options"
                                    :disabled="
                                      hrCreate.main_job_career_3_time_now
                                        ? true
                                        : false
                                    "
                                  />

                                  <span>{{ $t('HR_LIST_FORM.YEAR') }}</span>
                                </div>

                                <div
                                  class="d-flex justify-space-between align-center"
                                  style="width: 46%; gap: 0.5rem"
                                >
                                  <b-form-select
                                    v-model="
                                      hrCreate.main_job_career_3_month_to
                                    "
                                    :options="month_not_requied_options"
                                    :disabled="
                                      hrCreate.main_job_career_3_time_now
                                        ? true
                                        : false
                                    "
                                  />

                                  <span>{{ $t('JOB_SEARCH.MONTH') }}</span>
                                </div>
                              </div>
                            </div>

                            <div
                              class="w-15 d-flex justify-content-end align-items-center"
                            >
                              <b-form-checkbox
                                id="main-job-career-3"
                                v-model="hrCreate.main_job_career_3_time_now"
                                :value="true"
                                size="lg"
                                style="top: 4px"
                                name="main-job-career-3"
                                @change="
                                  ($event) =>
                                    clearValueWhenDisable(
                                      $event,
                                      'main-job-career-3'
                                    )
                                "
                              />

                              <span>{{ $t('CURRENT') }}</span>
                            </div>
                          </div>
                        </div>

                        <div
                          class="w-100 d-flex justify-space-between align-center"
                          style="gap: 0.751rem"
                        >
                          <span style="width: 80px">{{
                            $t('AFFILIATION')
                          }}</span>

                          <b-form-select
                            v-model="hrCreate.main_job_career_3_department"
                            :options="listMainJobOccupationNotRequied"
                            @change="handleChangeMainJob(1, 3)"
                          />
                        </div>

                        <div
                          class="w-100 d-flex justify-space-between align-center"
                          style="gap: 0.751rem"
                        >
                          <span style="width: 80px">{{
                            $t('HR_LIST_FORM.JOB_TITLE')
                          }}</span>

                          <b-form-select
                            v-model="hrCreate.main_job_career_3_job_title"
                            :disabled="
                              hrCreate.main_job_career_3_department === null
                                ? true
                                : false
                            "
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
                    <div class="item-from-title d-flex justify-content-between bg-gray">
                      <span>自己PR・特記事項</span><Arbitrarily />
                    </div>

                    <div class="w-100 item-from-inputs">
                      <div
                        class="w-100 h-100 d-flex justify-space-between align-center"
                        dusk="personal_pr_special"
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
                    <div class="item-from-title d-flex justify-content-between bg-gray">
                      <span>備考</span><Arbitrarily />
                    </div>

                    <div class="w-100 item-from-inputs">
                      <div
                        class="w-100 h-100 d-flex justify-space-between align-center"
                        dusk="remarks"
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
                    <div class="item-from-title d-flex justify-content-between bg-gray">
                      <span>連絡先電話番号</span><Arbitrarily />
                    </div>

                    <div class="w-100 item-from-inputs">
                      <div
                        class="w-100 d-flex flex-column justify-space-between align-center"
                        style="gap: 0.751rem"
                      >
                        <div
                          class="w-100 d-flex justify-space-between align-center"
                          style="gap: 0.751rem"
                        >
                          <!-- Dropdown -->
                          <div class="h-100" style="height: 40px">
                            <div class="option-validate">
                              <div class="option-area-code">
                                <b-dropdown
                                  id="telephone_phone"
                                  dusk="telephone_phone_option"
                                  class="w-100 h-100"
                                  :text="hrCreate.telephone_phone_number_id"
                                >
                                  <!-- BLANK -->
                                  <b-dropdown-item
                                    @click="
                                      handleChangeCountry(
                                        'hrCreate.telephone_phone_number_id',
                                        ''
                                      )
                                    "
                                  >
                                    <span style="height: 28px" />
                                  </b-dropdown-item>
                                  <!-- VIE -->
                                  <b-dropdown-item
                                    dusk="telephone_phone_vn"
                                    @click="
                                      handleChangeCountry(
                                        'hrCreate.telephone_phone_number_id',
                                        '+84'
                                      )
                                    "
                                  >
                                    <img
                                      :src="
                                        require(`@/assets/images/icons/flag-84.png`)
                                      "
                                    >
                                    <span>+84</span>
                                  </b-dropdown-item>
                                  <!-- JA -->
                                  <b-dropdown-item
                                    dusk="telephone_phone_ja"
                                    @click="
                                      handleChangeCountry(
                                        'hrCreate.telephone_phone_number_id',
                                        '+81'
                                      )
                                    "
                                  >
                                    <img
                                      :src="
                                        require(`@/assets/images/icons/flag-81.png`)
                                      "
                                    >
                                    <span>+81</span>
                                  </b-dropdown-item>
                                </b-dropdown>

                                <img
                                  v-if="
                                    hrCreate.telephone_phone_number_id === '+84'
                                  "
                                  :src="
                                    require(`@/assets/images/icons/flag-84.png`)
                                  "
                                >
                                <img
                                  v-if="
                                    hrCreate.telephone_phone_number_id === '+81'
                                  "
                                  :src="
                                    require(`@/assets/images/icons/flag-81.png`)
                                  "
                                >
                              </div>
                            </div>
                          </div>

                          <b-form-input
                            v-model="hrCreate.telephone_phone_number"
                            max-lenght="50"
                            type="number"
                            class="form-input px-2"
                            dusk="telephone_phone"
                            :name="'mail address'"
                            :placeholder="'例) 0312345678 ハイフン無しで入力'"
                            :disabled="
                              hrCreate.telephone_phone_number_id == null ||
                                hrCreate.telephone_phone_number_id == ''
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
                    <div class="item-from-title d-flex justify-content-between bg-gray">
                      <span>携帯電話番号</span><Arbitrarily />
                    </div>

                    <div class="w-100 item-from-inputs">
                      <div
                        class="w-100 d-flex flex-column justify-space-between align-center"
                        style="gap: 0.751rem"
                      >
                        <div
                          class="w-100 d-flex justify-space-between align-center"
                          style="gap: 0.751rem"
                        >
                          <!-- Dropdown -->
                          <div class="h-100" style="height: 40px">
                            <div class="option-validate">
                              <div class="option-area-code">
                                <b-dropdown
                                  id="mobile_phone"
                                  dusk="mobile_phone_option"
                                  class="w-100 h-100"
                                  :text="hrCreate.mobile_phone_number_id"
                                >
                                  <!-- BLANK -->
                                  <b-dropdown-item
                                    @click="
                                      handleChangeCountry(
                                        'hrCreate.mobile_phone_number_id',
                                        ''
                                      )
                                    "
                                  >
                                    <span style="height: 28px" />
                                  </b-dropdown-item>
                                  <!-- VIE -->
                                  <b-dropdown-item
                                    dusk="mobile_phone_vn"
                                    @click="
                                      handleChangeCountry(
                                        'hrCreate.mobile_phone_number_id',
                                        '+84'
                                      )
                                    "
                                  >
                                    <img
                                      :src="
                                        require(`@/assets/images/icons/flag-84.png`)
                                      "
                                    >
                                    <span>+84</span>
                                  </b-dropdown-item>
                                  <!-- JA -->
                                  <b-dropdown-item
                                    dusk="mobile_phone_ja"
                                    @click="
                                      handleChangeCountry(
                                        'hrCreate.mobile_phone_number_id',
                                        '+81'
                                      )
                                    "
                                  >
                                    <img
                                      :src="
                                        require(`@/assets/images/icons/flag-81.png`)
                                      "
                                    >
                                    <span>+81</span>
                                  </b-dropdown-item>
                                </b-dropdown>
                                <img
                                  v-if="
                                    hrCreate.mobile_phone_number_id === '+84'
                                  "
                                  :src="
                                    require(`@/assets/images/icons/flag-84.png`)
                                  "
                                >
                                <img
                                  v-if="
                                    hrCreate.mobile_phone_number_id === '+81'
                                  "
                                  :src="
                                    require(`@/assets/images/icons/flag-81.png`)
                                  "
                                >
                              </div>
                            </div>
                          </div>

                          <b-form-input
                            v-model="hrCreate.mobile_phone_number"
                            max-lenght="50"
                            type="number"
                            class="form-input px-2"
                            dusk="mobile_phone"
                            :name="'mail address'"
                            :placeholder="'例) 09012345678 ハイフン無しで入力'"
                            :disabled="
                              hrCreate.mobile_phone_number_id === null ||
                                hrCreate.mobile_phone_number_id === ''
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
                    <div class="item-from-title d-flex justify-content-between bg-gray">
                      <span>メールアドレス</span><Require />
                    </div>

                    <div class="w-100 item-from-inputs">
                      <div class="full-width">
                        <b-form-input
                          v-model="hrCreate.mail_address"
                          dusk="mail_address"
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
                        <b-form-invalid-feedback :state="error.mail_address">
                          {{ $t('VALIDATE.REQUIRED_TEXT') }}
                        </b-form-invalid-feedback>
                      </div>
                    </div>
                  </div>

                  <div class="item-from-wrap border-t border-l border-r">
                    <div class="item-from-title d-flex justify-content-between bg-gray">
                      <span>現住所</span><Arbitrarily />
                    </div>

                    <div
                      class="w-100 item-from-inputs"
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
                            dusk="address_city"
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
                            dusk="address_district"
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
                            dusk="address_number"
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
                    <div class="item-from-title d-flex justify-content-between bg-gray">
                      <span>出身地住所</span><Arbitrarily />
                    </div>

                    <div
                      class="w-100 item-from-inputs"
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
                            dusk="hometown_address_city"
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
                            dusk="hometown_address_district"
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
                            dusk="hometown_address_number"
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
                <button
                  id="hr-create-cancel"
                  dusk="hr_create_cancel"
                  class="btn btn_cancel--custom cancel-btn"
                  @click="handleToggleConfirmLeavingModal"
                >
                  {{ $t('BUTTON.CANCEL') }}
                </button>
                <button
                  id="hr-create-save"
                  dusk="hr_create_save"
                  class="register-btn btn btn_save--custom"
                  @click="handleRegisterHr()"
                >
                  {{ $t('BUTTON.SAVE') }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <b-modal
        ref="confirm modal"
        v-model="statusModalConfirmLeaving"
        hide-footer
        :no-fade="false"
        no-close-on-backdrop
        centered
        size="lg"
      >
        <template #modal-title>
          <div class="modal-title">
            <span>{{ $t('HR_REGISTER.CONFIRM_LEAVING') }}</span>
          </div>
        </template>
        <template #default>
          <div class="modal-body-confirm">
            <div
              class="w-100 d-flex justify-end align-center"
              style="gap: 10px"
            >
              <button
                id="btn-go-to-back-home-"
                dusk="btn_continue_create"
                class="btn btn_cancel--custom btn-modal-confirm"
                @click="handleToggleConfirmLeavingModal"
              >
                {{ $t('BUTTON.CANCEL') }}
              </button>
              <button
                id="leaving-create-hr"
                class="btn btn_modal_accept--custom btn-modal-confirm"
                dusk="btn_accept_leaving"
                @click="handleConfirmStilConfirmLeaving"
              >
                {{ $t('OK') }}
              </button>
            </div>
          </div>
        </template>
      </b-modal>

      <!--  -->
    </div>
  </b-overlay>
</template>

<script>
import i18n from '@/lang';
import Require from '@/components/Require/Require.vue';
import Arbitrarily from '@/components/Arbitrarily/Arbitrarily.vue';

import { postHr } from '@/api/modules/hr';
import { MakeToast } from '@/utils/toastMessage';
// import { getListCompany } from '@/api/modules/company';
import { getSelectHrOrg } from '@/api/modules/hr';
import {
  getClassificationListDepartment,
  getClassificationListOccupation,
} from '@/api/modules/hr';
import {
  deparment_option_api,
  job_title_agriculture_forestry_fishery_api,
  job_title_manufacturingy_api,
} from '@/pages/Hr/common.js';
import {
  renderYears,
  renderMonth,
  renderYearNotRequire,
  renderMonthNotRequire,
  gender_option,
  work_form_option,
  provincesr_option,
  format50characters,
  renderYearsEducationTiming,
  major_classification_options_api,
  middle_classification_options_api,
} from '@/pages/Hr/common.js';

const urlAPI = {
  urlCreateHr: '/hr',
  // urlGetListCompany: '/company',
  urlGetJobList: '/job-type',
  urlGetSelectHrOrg: '/hr-org',
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

      organizationOptions: [],

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
      year_not_requied_options: renderYearNotRequire(),
      month_not_requied_options: renderMonthNotRequire(),
      day_options: [],
      work_form_option: work_form_option,
      language_requirement_options: [
        { value: { id: 1, content: 'N1' }, text: 'N1', translate: 'N1' },
        { value: { id: 2, content: 'N2' }, text: 'N2', translate: 'N2' },
        { value: { id: 3, content: 'N3' }, text: 'N3', translate: 'N3' },
        { value: { id: 4, content: 'N4' }, text: 'N4', translate: 'N4' },
        { value: { id: 5, content: 'N5' }, text: 'N5', translate: 'N5' },
        {
          value: { id: 6, content: '資格なし' },
          text: '資格なし',
          translate: 'no qualification',
        },
      ],

      final_education_timing_option: renderYearsEducationTiming(),
      final_education_classification_options: [
        {
          value: { id: 1, content: '卒業' },
          text: '卒業',
          translate: 'graduation',
        },
        {
          value: { id: 2, content: '中退' },
          text: '中退',
          translate: 'dropout',
        },
        {
          value: { id: 3, content: '卒業見込み' },
          text: '卒業見込み',
          translate: 'expected to graduate',
        },
      ],
      final_education_degree_options: [
        {
          value: { id: 1, content: '博士' },
          text: '博士',
          translate: 'Doctor',
        },
        {
          value: { id: 2, content: '修士' },
          text: '修士',
          translate: 'master s degree',
        },
        {
          value: { id: 3, content: '学士' },
          text: '学士',
          translate: 'expected to graduate',
        },
        {
          value: { id: 3, content: '短期大学卒業' },
          text: '短期大学卒業',
          translate: 'graduated from junior college',
        },
        {
          value: { id: 3, content: '専門学校卒業' },
          text: '専門学校卒業',
          translate: 'graduated from vocational school',
        },
        {
          value: { id: 3, content: '高校卒業' },
          text: '高校卒業',
          translate: 'high school graduation',
        },
      ],
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
        main_job_career_1_department: '',
        main_job_career_1_job_title: '',
        main_job_career_1_textarea: '',
        mail_address: '',
      },

      listMainJobDepartment: [],
      listSubJobDepartment: [],

      listMainJobOccupation: [],
      listSubJobOccupation1: [
        {
          value: null,
          text: '選択してください',
          sub_options: [],
          disabled: false,
        },
      ],
      listSubJobOccupation2: [
        {
          value: null,
          text: '選択してください',
          sub_options: [],
          disabled: false,
        },
      ],
      listSubJobOccupation3: [
        {
          value: null,
          text: '選択してください',
          sub_options: [],
          disabled: false,
        },
      ],
    };
  },
  computed: {
    profile() {
      return this.$store.getters.profile;
    },
    checkRoleAdmin() {
      const PROFILE = this.$store.getters.profile;
      const list_role_admin = [1, 3, 5];
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
    listMainJobOccupationNotRequied() {
      const LIST_OPTION = [
        {
          value: null,
          text: '選択してください',
          sub_options: [],
          disabled: false,
        },
      ];
      this.listMainJobOccupation.forEach((item) => {
        LIST_OPTION.push(item);
      });
      return LIST_OPTION;
    },
  },
  mounted() {},
  created() {
    this.handleInitComponent();
  },
  methods: {
    async handleInitComponent() {
      await this.handleGetSelectHrOrg();
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
        // console.log('COUNTRY: ', COUNTRY);

        this.hrCreate.hr_organization_id =
          this.profile['hr_organization']['id'];

        this.hrCreate.organization_name =
          this.profile['hr_organization']['corporate_name_en'];
        this.hrCreate.organization_japanese_name =
          this.profile['hr_organization']['corporate_name_ja'];

        if (this.profile.type === 5) {
          this.organizationOptions.push({
            value: this.profile['hr_organization']['id'],
            text_en: this.profile['hr_organization']['corporate_name_en'],
            text_ja: this.profile['hr_organization']['corporate_name_ja'],
            disabled: true,
          });
        }

        this.hrCreate.country_id = COUNTRY['value']['id'];

        // if (!this.checkRoleAdmin) {
        if (this.profile.type === 5) {
          this.hrCreate.country_name = COUNTRY['text'];
          console.log(
            'this.hrCreate.country_name: ',
            this.hrCreate.country_name
          );
        } else {
          this.hrCreate.country_name = '';
        }
      }
    },
    async handleGetSelectHrOrg() {
      const URL = `${urlAPI.urlGetSelectHrOrg}`;

      try {
        const response = await getSelectHrOrg(URL);

        if (response['code'] === 200) {
          const DATA = response['data'];

          DATA.forEach((item) => {
            this.organizationOptions.push({
              value: item['id'],
              text_en: `${item['corporate_name_en']}`,
              text_ja: `${item['corporate_name_ja']}`,
              disabled: false,
            });
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

    handleChangeCountry(type_select, countryCode) {
      // this.selectedCountry = countryCode;
      if (type_select === 'hrCreate.mobile_phone_number_id') {
        this.hrCreate.mobile_phone_number_id = countryCode;
        if (!countryCode) {
          this.hrCreate.mobile_phone_number = '';
        }
      }
      if (type_select === 'hrCreate.telephone_phone_number_id') {
        this.hrCreate.telephone_phone_number_id = countryCode;
        if (!countryCode) {
          this.hrCreate.telephone_phone_number = '';
        }
      }
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
          // let main_job_1;
          // let main_job_2;
          // let main_job_3;

          let main_job_career_date_to_1 = '';
          let main_job_career_date_to_2 = '';
          let main_job_career_date_to_3 = '';

          // Check case 1
          if (!this.hrCreate.main_job_career_1_time_now) {
            if (
              this.hrCreate.main_job_career_1_year_to &&
              this.hrCreate.main_job_career_1_month_to
            ) {
              main_job_career_date_to_1 = `${
                this.hrCreate.main_job_career_1_year_to
              }-${this.formatDateTime(
                this.hrCreate.main_job_career_1_month_to
              )}`;
            } else if (
              this.hrCreate.main_job_career_1_year_to &&
              !this.hrCreate.main_job_career_1_month_to
            ) {
              main_job_career_date_to_1 = `${this.hrCreate.main_job_career_1_year_to}-01`;
            } else if (
              !this.hrCreate.main_job_career_1_year_to &&
              this.hrCreate.main_job_career_1_month_to
            ) {
              main_job_career_date_to_1 = `-${this.formatDateTime(
                this.hrCreate.main_job_career_1_month_to
              )}`;
            } else {
              main_job_career_date_to_1 = '';
            }
          }
          // check case 2
          if (!this.hrCreate.main_job_career_2_time_now) {
            if (
              this.hrCreate.main_job_career_2_year_to &&
              this.hrCreate.main_job_career_2_month_to
            ) {
              main_job_career_date_to_2 = `${
                this.hrCreate.main_job_career_2_year_to
              }-${this.formatDateTime(
                this.hrCreate.main_job_career_2_month_to
              )}`;
            } else if (
              this.hrCreate.main_job_career_2_year_to &&
              !this.hrCreate.main_job_career_2_month_to
            ) {
              main_job_career_date_to_2 = `${this.hrCreate.main_job_career_2_year_to}-01`;
            } else if (
              !this.hrCreate.main_job_career_2_year_to &&
              this.hrCreate.main_job_career_2_month_to
            ) {
              main_job_career_date_to_2 = `-${this.formatDateTime(
                this.hrCreate.main_job_career_2_month_to
              )}`;
            } else {
              main_job_career_date_to_2 = '';
            }
          }

          // check case 3
          if (!this.hrCreate.main_job_career_3_time_now) {
            if (
              this.hrCreate.main_job_career_3_year_to &&
              this.hrCreate.main_job_career_3_month_to
            ) {
              main_job_career_date_to_3 = `${
                this.hrCreate.main_job_career_3_year_to
              }-${this.formatDateTime(
                this.hrCreate.main_job_career_3_month_to
              )}`;
            } else if (
              this.hrCreate.main_job_career_3_year_to &&
              !this.hrCreate.main_job_career_3_month_to
            ) {
              main_job_career_date_to_3 = `${this.hrCreate.main_job_career_3_year_to}-01`;
            } else if (
              !this.hrCreate.main_job_career_3_year_to &&
              this.hrCreate.main_job_career_3_month_to
            ) {
              main_job_career_date_to_3 = `-${this.formatDateTime(
                this.hrCreate.main_job_career_3_month_to
              )}`;
            } else {
              main_job_career_date_to_3 = '';
            }
          }

          // if (
          //   this.hrCreate.main_job_career_1_year_to &&
          //   this.formatDateTime(this.hrCreate.main_job_career_1_month_to)
          // ) {
          //   main_job_career_date_to_1 = `${
          //     this.hrCreate.main_job_career_1_year_to
          //   }-${this.formatDateTime(this.hrCreate.main_job_career_1_month_to)}`;
          // }

          // if (
          //   this.hrCreate.main_job_career_2_year_to &&
          //   this.formatDateTime(this.hrCreate.main_job_career_2_month_to)
          // ) {
          //   main_job_career_date_to_2 = `${
          //     this.hrCreate.main_job_career_2_year_to
          //   }-${this.formatDateTime(this.hrCreate.main_job_career_2_month_to)}`;
          // }

          // if (
          //   this.hrCreate.main_job_career_3_year_to &&
          //   this.formatDateTime(this.hrCreate.main_job_career_3_month_to)
          // ) {
          //   main_job_career_date_to_3 = `${
          //     this.hrCreate.main_job_career_3_year_to
          //   }-${this.formatDateTime(this.hrCreate.main_job_career_3_month_to)}`;
          // }

          // if (
          //   this.hrCreate.main_job_career_1_year_from &&
          //   this.hrCreate.main_job_career_1_month_from
          // ) {
          const main_job_1 = {
            main_job_career_date_from:
              this.hrCreate.main_job_career_1_year_from &&
              this.hrCreate.main_job_career_1_month_from
                ? `${
                  this.hrCreate.main_job_career_1_year_from
                }-${this.formatDateTime(
                  this.hrCreate.main_job_career_1_month_from
                )}`
                : this.hrCreate.main_job_career_1_year_from &&
                  !this.hrCreate.main_job_career_1_month_from
                  ? `${this.hrCreate.main_job_career_1_year_from}-01`
                  : !this.hrCreate.main_job_career_1_year_from &&
                  this.hrCreate.main_job_career_1_month_from
                    ? `-${this.formatDateTime(
                      this.hrCreate.main_job_career_1_month_from
                    )}`
                    : '',
            to_now: this.hrCreate.main_job_career_1_time_now ? 'yes' : 'no',
            main_job_career_date_to: main_job_career_date_to_1,
            department_id: this.hrCreate.main_job_career_1_department,
            job_id: this.hrCreate.main_job_career_1_job_title,
            detail: this.hrCreate.main_job_career_1_textarea,
          };
          // } else {
          //   main_job_1 = null;
          // }

          // if (
          //   this.hrCreate.main_job_career_2_year_from &&
          //   this.hrCreate.main_job_career_2_month_from
          // ) {
          const main_job_2 = {
            main_job_career_date_from:
              this.hrCreate.main_job_career_2_year_from &&
              this.hrCreate.main_job_career_2_month_from
                ? `${
                  this.hrCreate.main_job_career_2_year_from
                }-${this.formatDateTime(
                  this.hrCreate.main_job_career_2_month_from
                )}`
                : this.hrCreate.main_job_career_2_year_from &&
                  !this.hrCreate.main_job_career_2_month_from
                  ? `${this.hrCreate.main_job_career_2_year_from}-01`
                  : !this.hrCreate.main_job_career_2_year_from &&
                  this.hrCreate.main_job_career_2_month_from
                    ? `-${this.formatDateTime(
                      this.hrCreate.main_job_career_2_month_from
                    )}`
                    : '',
            to_now: this.hrCreate.main_job_career_2_time_now ? 'yes' : 'no',
            main_job_career_date_to: main_job_career_date_to_2,
            department_id: this.hrCreate.main_job_career_2_department,
            job_id: this.hrCreate.main_job_career_2_job_title,
            detail: this.hrCreate.main_job_career_2_textarea,
          };
          // } else {
          //   main_job_2 = null;
          // }

          // if (
          //   this.hrCreate.main_job_career_3_year_from &&
          //   this.hrCreate.main_job_career_3_month_from
          // ) {
          const main_job_3 = {
            main_job_career_date_from:
              this.hrCreate.main_job_career_3_year_from &&
              this.hrCreate.main_job_career_3_month_from
                ? `${
                  this.hrCreate.main_job_career_3_year_from
                }-${this.formatDateTime(
                  this.hrCreate.main_job_career_3_month_from
                )}`
                : this.hrCreate.main_job_career_3_year_from &&
                  !this.hrCreate.main_job_career_3_month_from
                  ? `${this.hrCreate.main_job_career_3_year_from}-01`
                  : !this.hrCreate.main_job_career_3_year_from &&
                  this.hrCreate.main_job_career_3_month_from
                    ? `-${this.formatDateTime(
                      this.hrCreate.main_job_career_3_month_from
                    )}`
                    : '',
            to_now: this.hrCreate.main_job_career_3_time_now ? 'yes' : 'no',
            main_job_career_date_to: main_job_career_date_to_3,
            department_id: this.hrCreate.main_job_career_3_department,
            job_id: this.hrCreate.main_job_career_3_job_title,
            detail: this.hrCreate.main_job_career_3_textarea,
          };
          // } else {
          //   main_job_3 = null;
          // }

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

          // const MAIN_JOBS = JSON.stringify(TEMP_ARR);
          const MAIN_JOBS = TEMP_ARR;
          console.log('MAIN_JOBS===>', MAIN_JOBS);

          const DATA = {
            hr_organization_id: this.hrCreate.hr_organization_id,
            country_id: this.hrCreate.country_id,
            full_name: this.hrCreate.full_name,
            full_name_ja: this.hrCreate.full_name_furigana,
            gender: this.hrCreate.gender_id['id'],
            date_of_birth: `${
              this.hrCreate.date_of_birth_year
            }-${this.formatDateTime(
              this.hrCreate.date_of_birth_month
            )}-${this.formatDateTime(this.hrCreate.date_of_birth_day)}`,
            work_form: this.hrCreate.work_form['id'],
            preferred_working_hours: this.hrCreate.preferred_working_hours,
            japanese_level: this.hrCreate.japanese_level['id'],

            final_education_date: `${
              this.hrCreate.final_education_timing_year
            }-${this.formatDateTime(
              this.hrCreate.final_education_timing_month
            )}`,
            final_education_classification:
              this.hrCreate.final_education_classification['id'],
            final_education_degree: this.hrCreate.final_education_degree['id'],
            major_classification_id: this.hrCreate.major_classification,
            middle_classification_id: this.hrCreate.middle_classification,

            main_jobs: MAIN_JOBS,

            personal_pr_special_notes:
              this.hrCreate.personal_pr_special_textarea,
            remarks: this.hrCreate.remarks_textarea,

            // telephone_number: this.hrCreate.telephone_phone_number,
            // mobile_phone_number: this.hrCreate.mobile_phone_number
            telephone_number: this.hrCreate?.telephone_phone_number
              ? this.hrCreate.telephone_phone_number_id +
                ' ' +
                this.hrCreate.telephone_phone_number
              : '',
            mobile_phone_number: this.hrCreate?.mobile_phone_number
              ? this.hrCreate.mobile_phone_number_id +
                ' ' +
                this.hrCreate.mobile_phone_number
              : '',
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
          // console.log('DATA===>', DATA);
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
              variant: 'danger',
              title: this.$t('DANGER'),
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
          content: this.$t('WARNING_MESS.REQUIRED_FIELD_NOT_INPUT'),
        });
      }
    },
    handleValidateFormData() {
      let result = true;

      if (this.hrCreate.hr_organization_id === '') {
        this.error.hr_organization_id = false;
        result = false;
      }
      if (this.hrCreate.full_name === '') {
        this.error.full_name = false;
        result = false;
      }
      if (this.hrCreate.full_name_furigana === '') {
        this.error.full_name_furigana = false;
        result = false;
      }
      if (this.hrCreate.date_of_birth_year === null) {
        this.error.date_of_birth_year = false;
        result = false;
      }
      if (this.hrCreate.date_of_birth_month === null) {
        this.error.date_of_birth_month = false;
        result = false;
      }
      if (this.hrCreate.date_of_birth_day === null) {
        this.error.date_of_birth_day = false;
        result = false;
      }
      if (this.hrCreate.japanese_level['id'] === null) {
        this.error.japanese_level = false;
        result = false;
      }
      if (this.hrCreate.final_education_timing_year === null) {
        this.error.final_education_timing_year = false;
        result = false;
      }
      if (this.hrCreate.final_education_timing_month === null) {
        this.error.final_education_timing_month = false;
        result = false;
      }
      if (this.hrCreate.final_education_classification['id'] === null) {
        this.error.final_education_classification = false;
        result = false;
      }
      if (this.hrCreate.final_education_degree['id'] === null) {
        this.error.final_education_degree = false;
        result = false;
      }
      if (this.hrCreate.major_classification === null) {
        this.error.major_classification = false;
        result = false;
      }
      if (this.hrCreate.middle_classification === null) {
        this.error.middle_classification = false;
        result = false;
      }
      // if (this.hrCreate.main_job_career_1_year_from === null) {
      //   this.error.main_job_career_1_year_from = false;
      //   result = false;
      // }
      // if (this.hrCreate.main_job_career_1_month_from === null) {
      //   this.error.main_job_career_1_month_from = false;
      //   result = false;
      // }
      // if (
      //   this.hrCreate.main_job_career_1_year_from !== null &&
      //   this.hrCreate.main_job_career_1_month_to !== null &&
      //   !this.hrCreate.main_job_career_1_time_now
      // ) {
      //   if (this.hrCreate.main_job_career_1_year_to === null) {
      //     this.error.main_job_career_1_year_to = false;
      //     result = false;
      //   } else if (this.hrCreate.main_job_career_1_month_to === null) {
      //     this.error.main_job_career_1_month_to = false;
      //     result = false;
      //   } else if (this.hrCreate.mail_address === '') {
      //     this.error.mail_address = false;
      //     result = false;
      //   }
      // }
      if (this.hrCreate.mail_address === '') {
        this.error.mail_address = false;
        result = false;
      }
      // if (this.hrCreate.main_job_career_1_department === null) {
      //   this.error.main_job_career_1_department = false;
      //   result = false;
      // }
      // if (this.hrCreate.main_job_career_1_job_title === null) {
      //   this.error.main_job_career_1_job_title = false;
      //   result = false;
      // }
      // if (this.hrCreate.main_job_career_1_textarea === '') {
      //   this.error.main_job_career_1_textarea = false;
      //   result = false;
      // }

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
        // case 'main_job_career_1_year_from':
        //   this.error.main_job_career_1_year_from = '';
        //   if (newValue) {
        //     this.error.main_job_career_1_year_from = true;
        //   } else {
        //     this.error.main_job_career_1_year_from = false;
        //   }
        //   break;
        // case 'main_job_career_1_month_from':
        //   this.error.main_job_career_1_month_from = '';
        //   if (newValue) {
        //     this.error.main_job_career_1_month_from = true;
        //   } else {
        //     this.error.main_job_career_1_month_from = false;
        //   }
        //   break;
        // case 'main_job_career_1_year_to':
        //   this.error.main_job_career_1_year_to = '';
        //   if (newValue) {
        //     this.error.main_job_career_1_year_to = true;
        //   } else {
        //     this.error.main_job_career_1_year_to = false;
        //   }
        //   break;
        // case 'main_job_career_1_month_to':
        //   this.error.main_job_career_1_month_to = '';
        //   if (newValue) {
        //     this.error.main_job_career_1_month_to = true;
        //   } else {
        //     this.error.main_job_career_1_month_to = false;
        //   }
        //   break;
        // case 'main_job_career_1_job_title':
        //   this.error.main_job_career_1_job_title = '';
        //   if (newValue) {
        //     this.error.main_job_career_1_job_title = true;
        //   } else {
        //     this.error.main_job_career_1_job_title = false;
        //   }
        //   break;
        // case 'main_job_career_1_textarea':
        //   this.error.main_job_career_1_textarea = '';
        //   if (newValue) {
        //     this.error.main_job_career_1_textarea = true;
        //   } else {
        //     this.error.main_job_career_1_textarea = false;
        //   }
        //   break;
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
      this.$router.push({ path: `/hr/list` }, (onAbort) => {});
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
        this.error.hr_organization_id = true;
      }
    },
    async handleGetListJobDepartment() {
      this.listMainJobDepartment = [];

      const TYPE = 2;

      const URL = `${urlAPI.urlGetJobList}?type=${TYPE}`;

      try {
        const response = await getClassificationListDepartment(URL);

        if (response['code'] === 200) {
          const DATA = response['data'];

          DATA.forEach((item) => {
            this.listMainJobDepartment.push({
              value: item['id'],
              text: item['name_ja'],
              sub_options: item['job_info'],
              disabled: false,
            });
          });
        }
      } catch (error) {
        console.log(error);
      }
    },
    async handleGetListJobOccupation() {
      this.listMainJobOccupation = [];

      const TYPE = 1;

      const URL = `${urlAPI.urlGetJobList}?type=${TYPE}`;

      try {
        const response = await getClassificationListOccupation(URL);

        if (response['code'] === 200) {
          const DATA = response['data'];

          DATA.forEach((item) => {
            this.listMainJobOccupation.push({
              value: item['id'],
              text: item['name_ja'],
              sub_options: item['job_info'],
              disabled: false,
            });
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
              if (
                item['value'] === this.hrCreate.main_job_career_1_department
              ) {
                SUB_OPTIONS = item['sub_options'];
              }
            });
            this.error.main_job_career_1_department = '';

            this.hrCreate.main_job_career_1_job_title = null;

            break;
          case 2:
            this.listMainJobOccupation.find((item) => {
              if (
                item['value'] === this.hrCreate.main_job_career_2_department
              ) {
                SUB_OPTIONS = item['sub_options'];
              }
            });

            this.hrCreate.main_job_career_2_job_title = null;

            break;
          case 3:
            this.listMainJobOccupation.find((item) => {
              if (
                item['value'] === this.hrCreate.main_job_career_3_department
              ) {
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
          this.listSubJobOccupation = [
            { value: null, text: '選択してください', disabled: true },
          ];

          if (SUB_OPTIONS.length > 0) {
            switch (index) {
              case 1:
                this.listSubJobOccupation1 = [
                  {
                    value: null,
                    text: '選択してください',
                    sub_options: [],
                    disabled: false,
                  },
                ];
                SUB_OPTIONS.forEach((item) => {
                  this.listSubJobOccupation1.push({
                    value: item['id'],
                    text: item['name_ja'],
                    disabled: false,
                  });
                });
                break;
              case 2:
                this.listSubJobOccupation2 = [
                  {
                    value: null,
                    text: '選択してください',
                    sub_options: [],
                    disabled: false,
                  },
                ];
                SUB_OPTIONS.forEach((item) => {
                  this.listSubJobOccupation2.push({
                    value: item['id'],
                    text: item['name_ja'],
                    disabled: false,
                  });
                });
                break;
              case 3:
                this.listSubJobOccupation3 = [
                  {
                    value: null,
                    text: '選択してください',
                    sub_options: [],
                    disabled: false,
                  },
                ];
                SUB_OPTIONS.forEach((item) => {
                  this.listSubJobOccupation3.push({
                    value: item['id'],
                    text: item['name_ja'],
                    disabled: false,
                  });
                });
                break;
              default:
                break;
            }
          } else {
            this.listSubJobOccupation = [
              { value: null, text: '選択してください', disabled: true },
            ];
          }
          break;
        case 2:
          this.listSubJobDepartment = [];

          if (SUB_OPTIONS.length > 0) {
            SUB_OPTIONS.forEach((item) => {
              this.listSubJobDepartment.push({
                value: item['id'],
                text: item['name_ja'],
                disabled: false,
              });
            });
          } else {
            this.listSubJobDepartment = [];
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
@import '@/pages/RegisterHrOrigin/RegisterHrOrigin.scss';
// @import '@/scss/modules/Job/job.scss';

.full-width {
  width: 100%;
}

.register-btn,
.cancel-btn {
  width: 230px !important;
  min-width: 230px !important;
  height: 40px;
  display: flex;
  margin-top: 2rem;
  margin-bottom: 2rem;
  width: -moz-fit-content;
  width: fit-content;
  border-radius: 40px;
  align-items: center;
  flex-direction: column;
  padding: 0.5rem 3.75rem;
  justify-content: center;
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
  // padding: 3.25rem 0rem;
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
      // font-weight: 700;
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
    margin-top: 2rem;
    margin-bottom: 2rem;
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

// #hr-create-cancel {
//   background: #c1c1c1;
// }

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

.modal-body-confirm {
  display: flex;
  flex-direction: row;
  justify-content: flex-end;
  align-items: center;
  font-size: 20px;
  line-height: 30px;
  gap: 0.5rem;
}

.modal-title {
  font-weight: 500;
  font-size: 20px;
  line-height: 1.5rem;
  color: $black;
  display: flex;
  flex-direction: row;
  justify-content: center;
  align-items: center;
  // gap: 0.5rem;
  & span:nth-child(2) {
    margin-left: -8px;
  }
}

.btn {
  padding: 0;
}
::v-deep .custom-select:disabled,
.form-control:disabled,
.form-control[readonly] {
  background: #ededed;
}

::v-deep .dropdown-menu {
  right: 0;
}

.bg-gray {
  background-color: #f8f8f8;
}

@media only screen and (max-width: 576px) {
  .hr-registration-wrap {
    padding: 0.5rem 0.25rem;
  }

  .item-from-title {
    width: 168px;
  }
}

.w-85 {
  width: 85%;
}

.w-15 {
  width: 15%;
}
</style>
