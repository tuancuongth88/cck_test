<template>
  <div class="display-user-management-list">
    <b-row class="mb-2">
      <b-col
        cols="12"
        class="d-flex justify-content-between align-items-center"
      >
        <b-col class="border-left-title font-weight-bold">{{
          $t('BUTTON.NEW_REGISTER')
        }}</b-col>
        <div>
          <b-button
            variant="outline-dark mx-1"
            @click="handleBack"
          >{{ $t('BUTTON.BACK_TO_LIST') }}
          </b-button>
          <b-button
            type="submit"
            variant="warning"
            class="text-white mx-1"
            @click="onSubmit($event)"
          >{{ $t('BUTTON.SAVE') }}</b-button>
        </div>
      </b-col>
    </b-row>

    <!-- <div>formData : {{ formData }}</div> -->

    <b-row class="mb-4">
      <b-col cols="12">
        <b-form @submit="onSubmit($event)" @reset="onReset($event)">
          <b-card-group deck>
            <b-card no-body :header="$t('TITLE.JOB_INFORMATION')">
              <b-list-group>
                <!-- Row 1 職種（タイトル） -->
                <b-list-group-item class="p-0">
                  <div class="d-flex flex-wrap">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >{{ $t('JOB_EDIT.TITLE') }}
                      <b-badge
                        class="badge-required mx-2"
                        variant="light"
                      >必須</b-badge>
                    </b-col>
                    <b-col cols="9" class="my-2">
                      <b-form-input
                        v-model="formData.job_title"
                        aria-label=""
                        :class="error.job_title === false ? ' is-invalid' : ''"
                        @input="handleChangeForm($event, 'job_title')"
                      />
                      <b-form-invalid-feedback :state="error.job_title">
                        {{ $t('VALIDATE.REQUIRED_TEXT') }}
                      </b-form-invalid-feedback>
                    </b-col>
                  </div>
                </b-list-group-item>
                <!-- Row 2 企業名 -->
                <b-list-group-item class="p-0">
                  <div class="d-flex flex-wrap">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >{{ $t('JOB_EDIT.COMPANY_NAME') }}
                      <b-badge
                        class="badge-required mx-2"
                        variant="light"
                      >必須</b-badge>
                    </b-col>
                    <b-col cols="9" class="my-2">
                      <b-form-select
                        v-model="formData.company_name"
                        :class="
                          error.company_name === false ? ' is-invalid' : ''
                        "
                        placeholder="選択してください"
                        @input="handleChangeForm($event, 'company_name')"
                      >
                        <option value="1">Lựa chọn 1</option>
                        <option value="2">Lựa chọn 2</option>
                        <option value="3">Lựa chọn 3</option>
                      </b-form-select>
                      <b-form-invalid-feedback :state="error.company_name">
                        {{ $t('VALIDATE.REQUIRED_SELECT') }}
                      </b-form-invalid-feedback>
                    </b-col>
                  </div>
                </b-list-group-item>
                <!-- Row 3 募集期間 -->
                <b-list-group-item class="p-0">
                  <div class="d-flex flex-wrap">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >{{ $t('JOB_EDIT.APPLICATION_PERIOD') }}
                      <b-badge
                        class="badge-required mx-2"
                        variant="light"
                      >必須</b-badge>
                    </b-col>
                    <b-col cols="9" class="d-flex align-items-center my-2">
                      <b-col cols="5" class="p-0">
                        <b-form-input
                          v-model="formData.application_period_start"
                          aria-label=""
                          :class="
                            error.application_period_start === false
                              ? 'is-invalid'
                              : ''
                          "
                          type="date"
                          @input="
                            handleChangeForm($event, 'application_period_start')
                          "
                        />
                        <b-form-invalid-feedback
                          :state="error.application_period_start"
                        >
                          {{ $t('VALIDATE.REQUIRED_SELECT') }}
                        </b-form-invalid-feedback>
                      </b-col>
                      <b-col
                        cols="2"
                        class="p-0 d-flex align-items-center justify-content-center"
                      >
                        〜
                      </b-col>
                      <b-col cols="5" class="p-0">
                        <b-form-input
                          v-model="formData.application_period_end"
                          :class="
                            error.application_period_end === false
                              ? 'is-invalid'
                              : ''
                          "
                          type="date"
                          @input="
                            handleChangeForm($event, 'application_period_end')
                          "
                        />
                        <b-form-invalid-feedback
                          :state="error.application_period_end"
                        >
                          {{ $t('VALIDATE.REQUIRED_SELECT') }}
                        </b-form-invalid-feedback>
                      </b-col>
                    </b-col>
                  </div>
                </b-list-group-item>
                <!-- Row 3+ 職種 occupation -->
                <b-list-group-item class="p-0">
                  <div class="d-flex flex-wrap">
                    <b-col cols="3" class="d-flex align-items-center bg-gray font-weight-bold">{{ $t('JOB_EDIT.OCCUPATION') }}
                      <b-badge class="badge-required mx-2" variant="light">必須</b-badge>
                    </b-col>
                    <b-col cols="9" class="w-100 d-flex flex-column justify-start align-center my-2">
                      <!-- 大分類 -->
                      <div class="form-item-row__inputs d-flex justify-center align-center mt-2">
                        <span>大分類</span>
                        <div class=" d-flex flex-column justify-center align-center">
                          <b-form-select
                            v-model="formData.major_classification_id"
                            :options="major_classification_options"
                            value-field="id"
                            text-field="name_ja"
                            :class="error.major_classification_id === false ? 'is-invalid' : '' "
                            @input="handleChangeForm($event, 'major_classification_id')"
                            @change="renderChildOption(formData.major_classification_id)"
                          />
                          <b-form-invalid-feedback :state="error.major_classification_id">
                            {{ $t('VALIDATE.REQUIRED_SELECT') }}
                          </b-form-invalid-feedback>
                        </div>
                      </div>
                      <!-- 中分類 -->
                      <div class="form-item-row__inputs d-flex justify-center align-center mt-2">
                        <span>中分類</span>
                        <div class=" d-flex flex-column justify-center align-center">
                          <!-- <b-form-input
                            v-model="formData.occupation"
                            aria-label=""
                            :class="error.occupation === false ? ' is-invalid' : ''"
                            @input="handleChangeForm($event, 'occupation')"
                          /> -->
                          <!--  -->
                          <b-form-select
                            v-model="formData.middle_classification_id"
                            :options="middle_classification_options"
                            value-field="id"
                            text-field="name_ja"
                            :enabled="formData.major_classification_id"
                            :disabled="!formData.major_classification_id"
                            :class=" error.middle_classification_id === false ? 'is-invalid' : '' "
                            @input="handleChangeForm($event, 'middle_classification_id') "
                          />
                          <!--  -->
                          <b-form-invalid-feedback :state="error.middle_classification_id">
                            {{ $t('VALIDATE.REQUIRED_SELECT') }}
                          </b-form-invalid-feedback>
                        </div>
                      </div>

                    </b-col>
                  </div>
                </b-list-group-item>

                <!-- Row 4 仕事内容 -->
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >{{ $t('JOB_EDIT.JOB_DESCRIPTION') }}
                      <b-badge
                        class="badge-required mx-2"
                        variant="light"
                      >必須</b-badge>
                    </b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center flex-wrap my-2"
                    >
                      <b-form-textarea
                        v-model="formData.job_description"
                        :class="
                          error.job_description === false ? ' is-invalid' : ''
                        "
                        @input="handleChangeForm($event, 'job_description')"
                      />

                      <b-form-invalid-feedback :state="error.job_description">
                        {{ $t('VALIDATE.REQUIRED_TEXT') }}
                      </b-form-invalid-feedback>
                    </b-col>
                  </div>
                </b-list-group-item>
                <!-- Row 5 応募条件 -->
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >{{ $t('JOB_EDIT.APPLICATION_CONDITION') }}
                      <b-badge
                        class="badge-required mx-2"
                        variant="light"
                      >必須</b-badge>
                    </b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center flex-wrap my-2"
                    >
                      <b-form-textarea
                        v-model="formData.application_condition"
                        :class="
                          error.application_condition === false
                            ? ' is-invalid'
                            : ''
                        "
                        @input="
                          handleChangeForm($event, 'application_condition')
                        "
                      />
                      <b-form-invalid-feedback
                        :state="error.application_condition"
                      >
                        {{ $t('VALIDATE.REQUIRED_TEXT') }}
                      </b-form-invalid-feedback>
                    </b-col>
                  </div>
                </b-list-group-item>
                <!-- Row 6 応募要件 -->
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >{{ $t('JOB_EDIT.APPLICATION_REQUIREMENT') }}
                      <b-badge
                        class="badge-required mx-2"
                        variant="light"
                      >必須</b-badge>
                    </b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center flex-wrap my-2"
                    >
                      <b-form-textarea
                        v-model="formData.application_requirement"
                        :class="
                          error.application_requirement === false
                            ? ' is-invalid'
                            : ''
                        "
                        @input="
                          handleChangeForm($event, 'application_requirement')
                        "
                      />
                      <b-form-invalid-feedback
                        :state="error.application_requirement"
                      >
                        {{ $t('VALIDATE.REQUIRED_TEXT') }}
                      </b-form-invalid-feedback>
                    </b-col>
                  </div>
                </b-list-group-item>
                <!-- Row 7 対象国 country -->
                <b-list-group-item class="p-0">
                  <div class="d-flex flex-wrap">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >{{ $t('JOB_EDIT.COUNTRY') }}
                      <b-badge
                        class="badge-required mx-2"
                        variant="light"
                      >必須</b-badge>
                    </b-col>
                    <b-col cols="9" class="my-2">
                      <!-- <b-form-input
                        v-model="formData.country"
                        :class="error.country === false ? ' is-invalid' : ''"
                        aria-label=""
                        placeholder="選択してください"
                        @input="handleChangeForm($event, 'country')"
                      /> -->
                      <b-form-select
                        v-model="formData.country"
                        :options="country_options"
                        value-field="key"
                        text-field="value"
                        enabled
                        :class=" error.country === false ? 'is-invalid' : '' "
                        @input="handleChangeForm($event, 'country_id') "
                      />
                      <b-form-invalid-feedback :state="error.country">
                        {{ $t('VALIDATE.REQUIRED_TEXT') }}
                      </b-form-invalid-feedback>
                    </b-col>
                  </div>
                </b-list-group-item>
                <!-- Row 8 Language requirement -->
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >{{ $t('JOB_EDIT.REQUIRED_LANGUAGE_CONDITION') }}
                      <b-badge
                        class="badge-required mx-2"
                        variant="light"
                      >必須</b-badge>
                    </b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center flex-wrap my-2"
                    >
                      <b-form-checkbox-group
                        v-model="formData.language_requirement"
                        :options="['N1', 'N2', 'N3', 'N4', 'N5', '問わない']"
                        :aria-describedby="''"
                        :class="
                          error.language_requirement === false
                            ? 'form-control is-invalid'
                            : ''
                        "
                        @input="
                          handleChangeForm($event, 'language_requirement')
                        "
                      />
                      <b-form-invalid-feedback
                        :state="error.language_requirement"
                      >
                        {{ $t('VALIDATE.REQUIRED_SELECT') }}
                      </b-form-invalid-feedback>
                    </b-col>
                  </div>
                </b-list-group-item>
                <!-- Row 9 Other skill -->
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >{{ $t('JOB_EDIT.OTHER_SKILL') }}
                      <b-badge
                        class="badge-required mx-2"
                        variant="light"
                      >必須</b-badge>
                    </b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center flex-wrap my-2"
                    >
                      <b-form-textarea
                        v-model="formData.other_skill"
                        :class="
                          error.other_skill === false ? ' is-invalid' : ''
                        "
                        @input="handleChangeForm($event, 'other_skill')"
                      />
                      <b-form-invalid-feedback :state="error.other_skill">
                        {{ $t('VALIDATE.REQUIRED_TEXT') }}
                      </b-form-invalid-feedback>
                    </b-col>
                  </div>
                </b-list-group-item>
                <!-- Row 10 Preferred Skill -->
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >{{ $t('JOB_EDIT.PREFERRED_SKILL') }}
                      <b-badge
                        class="badge-not-required mx-2"
                        variant="secondary"
                      >任意</b-badge>
                    </b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center flex-wrap my-2"
                    >
                      <b-form-textarea
                        v-model="formData.preferred_skill"
                        :class="
                          error.preferred_skill === false ? ' is-invalid' : ''
                        "
                        @input="handleChangeForm($event, 'preferred_skill')"
                      />
                      <b-form-invalid-feedback :state="error.preferred_skill">
                        {{ $t('VALIDATE.REQUIRED_TEXT') }}
                      </b-form-invalid-feedback>
                    </b-col>
                  </div>
                </b-list-group-item>
                <!-- Row 11 Form of employee -->
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >{{ $t('JOB_EDIT.FORM_OF_EMPLOYMENT') }}
                      <b-badge
                        class="badge-required mx-2"
                        variant="light"
                      >必須</b-badge>
                    </b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center flex-wrap my-2"
                    >
                      <b-form-radio-group
                        v-model="formData.form_of_employment"
                        :options="['正社員', '契約社員', '派遣社員', 'その他']"
                        :aria-describedby="''"
                        name="radio-options"
                        :class="
                          error.form_of_employment === false
                            ? 'form-control is-invalid'
                            : ''
                        "
                        @input="handleChangeForm($event, 'form_of_employment')"
                      />

                      <b-form-invalid-feedback
                        :state="error.form_of_employment"
                      >
                        {{ $t('VALIDATE.REQUIRED_SELECT') }}
                      </b-form-invalid-feedback>
                    </b-col>
                  </div>
                </b-list-group-item>
                <!-- Row 12 Working time -->
                <b-list-group-item class="p-0">
                  <div class="d-flex flex-wrap">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >{{ $t('JOB_EDIT.WORKING_TIME') }}
                      <b-badge
                        class="badge-required mx-2"
                        variant="light"
                      >必須</b-badge>
                    </b-col>
                    <b-col cols="9" class="d-flex align-items-center my-2 my-2">
                      <b-col cols="5" class="p-0">
                        <b-form-select
                          v-model="formData.working_time_from"
                          :options="workingTimeOptions"
                          :class="
                            error.working_time_from === false
                              ? ' is-invalid'
                              : ''
                          "
                          @input="handleChangeForm($event, 'working_time_from')"
                        />
                        <b-form-invalid-feedback
                          :state="error.working_time_from"
                        >
                          {{ $t('VALIDATE.REQUIRED_SELECT') }}
                        </b-form-invalid-feedback>
                      </b-col>
                      <b-col
                        cols="2"
                        class="p-0 d-flex align-items-center justify-content-center"
                      >
                        〜
                      </b-col>
                      <b-col cols="5" class="p-0">
                        <b-form-select
                          v-model="formData.working_time_to"
                          :options="workingTimeOptions"
                          :class="
                            error.working_time_to === false ? ' is-invalid' : ''
                          "
                          @input="handleChangeForm($event, 'working_time_to')"
                        />
                        <b-form-invalid-feedback :state="error.working_time_to">
                          {{ $t('VALIDATE.REQUIRED_SELECT') }}
                        </b-form-invalid-feedback>
                      </b-col>
                    </b-col>
                  </div>
                </b-list-group-item>
                <!-- Row 13 Vacation -->
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >{{ $t('JOB_EDIT.VACATION') }}
                      <b-badge
                        class="badge-required mx-2"
                        variant="light"
                      >必須</b-badge>
                    </b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center flex-wrap my-2"
                    >
                      <b-form-textarea
                        v-model="formData.vacation"
                        :class="error.vacation === false ? ' is-invalid' : ''"
                        @input="handleChangeForm($event, 'vacation')"
                      />
                      <b-form-invalid-feedback :state="error.vacation">
                        {{ $t('VALIDATE.REQUIRED_TEXT') }}
                      </b-form-invalid-feedback>
                    </b-col>
                  </div>
                </b-list-group-item>
                <!-- Row 14 Expected income -->
                <b-list-group-item class="p-0">
                  <div class="d-flex flex-wrap">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >{{ $t('JOB_EDIT.EXPECTED_INCOME') }}
                      <b-badge
                        class="badge-required mx-2"
                        variant="light"
                      >必須</b-badge>
                    </b-col>
                    <b-col cols="9" class="my-2">
                      <b-form-input
                        v-model="formData.expected_income"
                        :class="
                          error.expected_income === false ? ' is-invalid' : ''
                        "
                        @input="handleChangeForm($event, 'expected_income')"
                      />
                      <b-form-invalid-feedback :state="error.expected_income">
                        {{ $t('VALIDATE.REQUIRED_TEXT') }}
                      </b-form-invalid-feedback>
                    </b-col>
                  </div>
                </b-list-group-item>
                <!-- Row 15 Working place -->
                <b-list-group-item class="p-0">
                  <div class="d-flex flex-wrap">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >{{ $t('JOB_EDIT.WORKING_PLACE') }}
                      <b-badge
                        class="badge-required mx-2"
                        variant="light"
                      >必須</b-badge>
                    </b-col>
                    <b-col cols="9" class="my-2">
                      <b-form-select
                        v-model="formData.working_place"
                        :options="workingPlaceOptions"
                        :class="
                          error.working_place === false ? ' is-invalid' : ''
                        "
                        @input="handleChangeForm($event, 'working_place')"
                      />
                      <b-form-invalid-feedback :state="error.working_place">
                        {{ $t('VALIDATE.REQUIRED_SELECT') }}
                      </b-form-invalid-feedback>
                    </b-col>
                  </div>
                </b-list-group-item>
                <!-- Row 16 Working place detail -->
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >{{ $t('JOB_EDIT.WORKING_PLACE_DETAIL') }}
                      <b-badge
                        class="badge-not-required mx-2"
                        variant="secondary"
                      >任意</b-badge>
                    </b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center flex-wrap my-2"
                    >
                      <b-form-textarea
                        v-model="formData.working_place_detail"
                        :class="
                          error.working_place_detail === false
                            ? ' is-invalid'
                            : ''
                        "
                        @input="
                          handleChangeForm($event, 'working_place_detail')
                        "
                      />
                      <b-form-invalid-feedback
                        :state="error.working_place_detail"
                      >
                        {{ $t('VALIDATE.REQUIRED_TEXT') }}
                      </b-form-invalid-feedback>
                    </b-col>
                  </div>
                </b-list-group-item>
                <!-- Row 17 Treatment Walfare -->
                <b-list-group-item class="p-0">
                  <div class="d-flex flex-wrap">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >{{ $t('JOB_EDIT.TREATMENT_WELFARE') }}
                      <b-badge
                        class="badge-required mx-2"
                        variant="light"
                      >必須</b-badge>
                    </b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center flex-wrap my-2"
                    >
                      <b-form-textarea
                        v-model="formData.treatment_welfare"
                        :class="
                          error.treatment_welfare === false ? ' is-invalid' : ''
                        "
                        @input="handleChangeForm($event, 'treatment_welfare')"
                      />
                      <b-form-invalid-feedback :state="error.treatment_welfare">
                        {{ $t('VALIDATE.REQUIRED_TEXT') }}
                      </b-form-invalid-feedback>
                    </b-col>
                  </div>
                </b-list-group-item>
                <!-- Row 18 Company PR APPEAL -->
                <b-list-group-item class="p-0">
                  <div class="d-flex flex-wrap">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >{{ $t('JOB_EDIT.COMPANY_PR_APPEAL') }}
                      <b-badge
                        class="badge-not-required mx-2"
                        variant="secondary"
                      >任意</b-badge>
                    </b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center flex-wrap my-2"
                    >
                      <b-form-textarea
                        v-model="formData.company_pr_appeal"
                        :class="
                          error.company_pr_appeal === false ? ' is-invalid' : ''
                        "
                        @input="handleChangeForm($event, 'company_pr_appeal')"
                      />
                      <b-form-invalid-feedback :state="error.company_pr_appeal">
                        {{ $t('VALIDATE.REQUIRED_TEXT') }}
                      </b-form-invalid-feedback>
                    </b-col>
                  </div>
                </b-list-group-item>
                <!-- Row 19 Bonus pay rise -->
                <b-list-group-item class="p-0">
                  <div class="d-flex flex-wrap">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >{{ $t('JOB_EDIT.BONUS_PAY_RISE') }}
                      <b-badge
                        class="badge-required mx-2"
                        variant="light"
                      >必須</b-badge>
                    </b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center flex-wrap my-2"
                    >
                      <b-form-radio-group
                        v-model="formData.bonus_pay"
                        :options="['有', '無']"
                        :aria-describedby="'bonus_pay'"
                        name="bonus_pay"
                        :class="
                          error.bonus_pay === false
                            ? 'form-control is-invalid'
                            : ''
                        "
                        @input="handleChangeForm($event, 'bonus_pay')"
                      />
                      <b-form-invalid-feedback :state="error.bonus_pay">
                        {{ $t('VALIDATE.REQUIRED_SELECT') }}
                      </b-form-invalid-feedback>
                    </b-col>
                  </div>
                </b-list-group-item>
                <!-- Row 20 Overtime -->
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >{{ $t('JOB_EDIT.OVER_TIME') }}
                      <b-badge
                        class="badge-required mx-2"
                        variant="light"
                      >必須</b-badge>
                    </b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center flex-wrap my-2"
                    >
                      <b-form-radio-group
                        v-model="formData.overtime"
                        :options="['有', '無']"
                        :aria-describedby="'overtime'"
                        name="overtime"
                        :class="
                          error.overtime === false
                            ? 'form-control is-invalid'
                            : ''
                        "
                        @input="handleChangeForm($event, 'overtime')"
                      />
                      <b-form-invalid-feedback :state="error.overtime">
                        {{ $t('VALIDATE.REQUIRED_SELECT') }}
                      </b-form-invalid-feedback>
                    </b-col>
                  </div>
                </b-list-group-item>
                <!-- Row 21 Transfer -->
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >{{ $t('JOB_EDIT.TRANSFER') }}
                      <b-badge
                        class="badge-required mx-2"
                        variant="light"
                      >必須</b-badge>
                    </b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center flex-wrap my-2"
                    >
                      <b-form-radio-group
                        v-model="formData.transfer"
                        :options="['有', '無']"
                        :aria-describedby="'transfer'"
                        name="transfer"
                        :class="
                          error.transfer === false
                            ? 'form-control is-invalid'
                            : ''
                        "
                        @input="handleChangeForm($event, 'transfer')"
                      />
                      <b-form-invalid-feedback :state="error.transfer">
                        {{ $t('VALIDATE.REQUIRED_SELECT') }}
                      </b-form-invalid-feedback>
                    </b-col>
                  </div>
                </b-list-group-item>
                <!-- Row 22 Passive Smoking -->
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >{{ $t('JOB_EDIT.PASSIVE_SMOKING') }}
                      <b-badge
                        class="badge-required mx-2"
                        variant="light"
                      >必須</b-badge>
                    </b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center flex-wrap my-2"
                    >
                      <b-form-radio-group
                        v-model="formData.passive_smoking"
                        :options="['有', '無']"
                        :aria-describedby="'passive_smoking'"
                        name="passive_smoking"
                        :class="
                          error.passive_smoking === false
                            ? 'form-control is-invalid'
                            : ''
                        "
                        @input="handleChangeForm($event, 'passive_smoking')"
                      />
                      <b-form-invalid-feedback :state="error.passive_smoking">
                        {{ $t('VALIDATE.REQUIRED_SELECT') }}
                      </b-form-invalid-feedback>
                    </b-col>
                  </div>
                </b-list-group-item>
                <!-- Row 23 Passion  -->
                <b-list-group-item class="p-0">
                  <div class="d-flex w-100 justify-content-between">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >{{ $t('JOB_EDIT.PASSION') }}
                      <b-badge
                        class="badge-not-required mx-2"
                        variant="secondary"
                      >任意</b-badge>
                    </b-col>
                    <b-col
                      cols="9"
                      class="d-flex flex-wrap align-items-center my-2"
                    >
                      <b-form-checkbox
                        v-model="checked1"
                        name="check-button"
                        button
                        :button-variant="checked1 ? 'info' : 'secondary'"
                        class="mx-2 my-1"
                        size="sm"
                      >
                        {{ $t('JOB_EDIT.PASSION_CHILD_WELFARE_ENHANCEMENT') }}
                      </b-form-checkbox>
                      <b-form-checkbox
                        v-model="checked2"
                        name="check-button"
                        button
                        :button-variant="checked2 ? 'info' : 'secondary'"
                        class="mx-2 my-1"
                        size="sm"
                      >
                        {{ $t('JOB_EDIT.PASSION_CHILD_SEVERANCE_PAY') }}
                      </b-form-checkbox>

                      <b-form-checkbox
                        v-model="checked3"
                        name="check-button"
                        button
                        :button-variant="checked3 ? 'info' : 'secondary'"
                        class="mx-2 my-1"
                        size="sm"
                      >
                        {{
                          $t('JOB_EDIT.PASSION_CHILD_OR_MORE_ANNUAL_HOLIDAYS')
                        }}
                      </b-form-checkbox>

                      <b-form-checkbox
                        v-model="checked4"
                        name="check-button"
                        button
                        :button-variant="checked4 ? 'info' : 'secondary'"
                        class="mx-2 my-1"
                        size="sm"
                      >
                        {{ $t('JOB_EDIT.PASSION_CHILD_RESIDENCE') }}
                      </b-form-checkbox>

                      <b-form-checkbox
                        v-model="checked5"
                        name="check-button"
                        button
                        :button-variant="checked5 ? 'info' : 'secondary'"
                        class="mx-2 my-1"
                        size="sm"
                      >
                        {{ $t('JOB_EDIT.PASSION_CHILD_NO_EXPERIENCE_OK') }}
                      </b-form-checkbox>

                      <b-form-checkbox
                        v-model="checked6"
                        name="check-button"
                        button
                        :button-variant="checked6 ? 'info' : 'secondary'"
                        class="mx-2 my-1"
                        size="sm"
                      >
                        {{ $t('JOB_EDIT.PASSION_CHILD_HIRING') }}
                      </b-form-checkbox>

                      <b-form-checkbox
                        v-model="checked7"
                        name="check-button"
                        button
                        :button-variant="checked7 ? 'info' : 'secondary'"
                        class="mx-2 my-1"
                        size="sm"
                      >
                        {{ $t('JOB_EDIT.PASSION_CHILD_REMOTE_WORK_OK') }}
                      </b-form-checkbox>

                      <b-form-checkbox
                        v-model="checked8"
                        name="check-button"
                        button
                        :button-variant="checked8 ? 'info' : 'secondary'"
                        class="mx-2 my-1"
                        size="sm"
                      >
                        {{ $t('JOB_EDIT.PASSION_CHILD_MOVING_ALLOWANCE') }}
                      </b-form-checkbox>
                      <b-form-checkbox
                        v-model="checked9"
                        name="check-button"
                        button
                        :button-variant="checked9 ? 'info' : 'secondary'"
                        class="mx-2 my-1"
                        size="sm"
                      >
                        {{ '週28時間以下OK' }}
                      </b-form-checkbox>
                    </b-col>
                  </div>
                </b-list-group-item>
                <!-- Row 24 Interview follow -->
                <b-list-group-item class="p-0">
                  <div class="d-flex flex-wrap">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >{{ $t('JOB_EDIT.INTERVIEW_FOLLOW') }}
                      <b-badge
                        class="badge-required mx-2"
                        variant="light"
                      >必須</b-badge>
                    </b-col>
                    <b-col cols="9" class="my-2">
                      <b-form-select
                        v-model="formData.interview_follow"
                        :options="interviewFollowOptions"
                        :class="
                          error.interview_follow === false ? ' is-invalid' : ''
                        "
                        name="some-radios"
                        value="A"
                        @input="handleChangeForm($event, 'interview_follow')"
                      />
                      <b-form-invalid-feedback :state="error.interview_follow">
                        {{ $t('VALIDATE.REQUIRED_SELECT') }}
                      </b-form-invalid-feedback>
                    </b-col>
                  </div>
                </b-list-group-item>
              </b-list-group>
            </b-card>
          </b-card-group>

          <b-card-group deck>
            <b-card :header="$t('TITLE.COMPANY_INFORMATION')">
              <b-list-group>
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >{{ $t('JOB_DETAIL.ESTABLISHMENT_YEAR') }}
                    </b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center flex-wrap my-2"
                    >
                      <b-form-input disabled value="2010年" aria-label="" />
                    </b-col>
                  </div>
                </b-list-group-item>
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >{{ $t('JOB_DETAIL.STARTUP_YEAR') }}
                    </b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center flex-wrap my-2"
                    >
                      <b-form-input disabled value="815億円" aria-label="" />
                    </b-col>
                  </div>
                </b-list-group-item>
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >{{ $t('JOB_DETAIL.CAPITAL') }}
                    </b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center flex-wrap my-2"
                    >
                      <b-form-input disabled value="1千万円" aria-label="" />
                    </b-col>
                  </div>
                </b-list-group-item>
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >
                      {{ $t('JOB_DETAIL.PROCEEDS') }}
                    </b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center flex-wrap my-2"
                    >
                      <b-form-input disabled value="815億円" aria-label="" />
                    </b-col>
                  </div>
                </b-list-group-item>
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >
                      {{ $t('JOB_DETAIL.NUMBER_OF_STAFFS') }}
                    </b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center flex-wrap my-2"
                    >
                      <b-form-input disabled value="7,218名" aria-label="" />
                    </b-col>
                  </div>
                </b-list-group-item>
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >
                      {{ $t('JOB_DETAIL.NUMBER_OF_OPERATIONS') }}
                    </b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center flex-wrap my-2"
                    >
                      <b-form-input disabled value="20" aria-label="" />
                    </b-col>
                  </div>
                </b-list-group-item>
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >
                      {{ $t('JOB_DETAIL.NUMBER_OF_SHOPS') }}
                    </b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center flex-wrap my-2"
                    >
                      <b-form-input disabled value="50" aria-label="" />
                    </b-col>
                  </div>
                </b-list-group-item>
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >
                      {{ $t('JOB_DETAIL.NUMBER_OF_FACTORIES') }}
                    </b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center flex-wrap my-2"
                    >
                      <b-form-input disabled value="50" aria-label="" />
                    </b-col>
                  </div>
                </b-list-group-item>
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray font-weight-bold"
                    >
                      {{ $t('JOB_DETAIL.FISCAL_YEAR') }}
                    </b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center flex-wrap my-2"
                    >
                      <b-form-input disabled value="12月" aria-label="" />
                    </b-col>
                  </div>
                </b-list-group-item>
              </b-list-group>
            </b-card>
          </b-card-group>
        </b-form>
      </b-col>
    </b-row>
  </div>
</template>

<script>
// import {
//   getAllUserManagement,
//   deleteUserManagement,
//   deleteAllUserManagement,
// } from '@/api/modules/userManagement';
// import { MakeToast } from '../../utils/toastMessage';
// import { obj2Path } from '@/utils/obj2Path';
import { validEmptyOrWhiteSpace } from '@/utils/validate';
import { countryOptions } from '@/const/job.js';
import { getListEduCourse } from '@/api/job';

// const urlAPI = {
//   urlGetLisUser: '/user',
//   urlDeleAll: 'user/ ',
// };
export default {
  name: 'JobCreate',
  components: {},

  data() {
    return {
      noSort: true,
      checkbox: false,
      checked1: false,
      checked2: false,
      checked3: false,
      checked4: false,
      checked5: false,
      checked6: false,
      checked7: false,
      checked8: false,
      checked9: false,
      listId: [],
      closeMess: true,
      showModal: false,
      selectAll: false,
      message: {
        isShowMessage: false,
        isMessage: '',
      },

      overlay: {
        show: false,
        variant: 'light',
        opacity: 1,
        blur: '1rem',
        rounded: 'sm',
      },

      queryData: {
        page: 1,
        per_page: 20,
        total_records: 0,
        search: '',
        order_type: '',
        order_column: '',
      },

      major_classification_options: [],
      middle_classification_options: [],

      formData: {
        job_title: '',
        company_name: '',
        application_period_start: '',
        application_period_end: '',
        major_classification_id: null,
        middle_classification_id: null,

        job_description: '',
        application_condition: '',
        application_requirement: '',
        country_id: null,
        language_requirement: [],
        other_skill: '',
        preferred_skill: '',
        form_of_employment: '',
        vacation: '',
        expected_income: '',
        working_time_to: '',
        working_time_from: '',
        working_place: '',
        working_place_detail: '',
        treatment_welfare: '',
        company_pr_appeal: '',
        bonus_pay: '',
        overtime: '',
        transfer: '',
        passive_smoking: '',
        passion: '',
        interview_follow: '',
      },
      formDataText: {
        major_classification_text: '',
        middle_classification_text: '',
      },

      error: {
        job_title: null,
        company_name: null,
        application_period_start: null,
        application_period_end: null,
        major_classification_id: null,
        middle_classification_id: null,
        job_description: null,
        application_condition: null,
        application_requirement: null,
        country_id: null,
        language_requirement: null,
        other_skill: null,
        // preferred_skill: null,
        form_of_employment: null,
        working_time: null,
        vacation: null,
        expected_income: null,
        working_time_from: null,
        working_time_to: null,
        working_place: null,
        // working_place_detail: null,
        treatment_welfare: null,
        // company_pr_appeal: null,
        bonus_pay: null,
        overtime: null,
        transfer: null,
        passive_smoking: null,
        passion: null,
        interview_follow: null,
      },

      reRender: 0,
      fields: [
        { key: 'choose', sortable: false, label: '', class: 'choose' },
        { key: 'id', sortable: true, label: 'ID', class: 'id' },
        { key: 'hr_name', sortable: true, label: 'HR NAME', class: 'hr_name' },
        {
          key: 'company_name',
          sortable: true,
          label: 'COMPANY NAME',
          class: 'company_name',
        },
        { key: 'status', sortable: false, label: 'STATUS', class: 'status' },
        {
          key: 'updating_date',
          sortable: false,
          label: 'UPDATE DATE',
          class: 'updating_date',
        },
        { key: 'detail', sortable: false, label: 'DETAIL', class: 'detail' },
      ],
      items: [
        {
          id: '0000001',
          hr_name: '電気設計エンジニア',
          company_name: '電気設計エンジニア',
          updating_date: '20230217',
          status: '募集中',
          selected: false,
          detail: '募集中',
        },
        {
          id: '0000002',
          hr_name: '電気設計エンジニア',
          company_name: '電気設計エンジニア',
          updating_date: '20230217',
          status: '一時停止中',
          selected: false,
          detail: '一時停止中',
        },
        {
          id: '0000003',
          hr_name: '電気設計エンジニア',
          company_name: '電気設計エンジニア',
          updating_date: '20230217',
          status: '募集期間外',
          selected: false,
          detail: '募集期間外',
        },
        {
          id: '0000004',
          hr_name: '電気設計エンジニア',
          company_name: '電気設計エンジニア',
          updating_date: '20230217',
          status: '募集期間外',
          selected: false,
          detail: '募集期間外',
        },
        {
          id: '0000005',
          hr_name: '電気設計エンジニア',
          company_name: '電気設計エンジニア',
          updating_date: '20230217',
          status: '募集期間外',
          selected: false,
          detail: '募集期間外',
        },
        {
          id: '0000006',
          hr_name: '電気設計エンジニア',
          company_name: '電気設計エンジニア',
          updating_date: '20230217',
          status: '募集期間外',
          selected: false,
          detail: '募集期間外',
        },
        {
          id: '0000007',
          hr_name: '電気設計エンジニア',
          company_name: '電気設計エンジニア',
          updating_date: '20230217',
          status: '募集期間外',
          selected: false,
          detail: '募集期間外',
        },
      ],

      country_options: countryOptions,

      workingTimeOptions: [
        '0:00',
        '0:30',
        '1:00',
        '1:30',
        '2:00',
        '2:30',
        '3:00',
        '3:30',
        '4:00',
        '4:30',
        '5:00',
        '5:30',
        '6:00',
        '6:30',
        '7:00',
        '7:30',
        '8:00',
        '8:30',
        '9:00',
        '9:30',
        '10:00',
        '10:30',
        '11:00',
        '11:30',
        '12:00',
        '12:30',
        '13:00',
        '13:30',
        '14:00',
        '14:30',
        '15:30',
        '15:00',
        '15:30',
        '16:00',
        '16:30',
        '17:00',
        '17:30',
        '18:00',
        '18:30',
        '19:00',
        '19:30',
        '20:00',
        '20:30',
        '21:00',
        '21:30',
        '22:00',
        '22:30',
        '23:00',
        '23:30',
      ],
      workingPlaceOptions: [
        '北海道',
        '青森県',
        '岩手県',
        '宮城県',
        '北海道 ',
        '青森県',
        '岩手県',
        '宮城県',
      ],
      interviewFollowOptions: [
        '一次面接まで',
        '二次面接まで',
        '三次面接まで',
        '四次面接まで',
        '五次面接まで',
      ],
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
      this.getListAllData();
    },

    items: {
      handler(newVal, oldVal) {
        // Update selectAll based on items selected status
        const allSelected = newVal.every((item) => item.selected);
        if (allSelected !== this.selectAll) {
          this.selectAll = allSelected;
        }
      },
      deep: true,
    },
  },

  created() {
    this.getListAllData();
    this.HandleGetListMainJob();
  },

  methods: {
    async getListAllData(e) {
      // const Search = {
      //   search: this.queryData.search,
      //   order_column: this.queryData.order_column,
      //   order_type: this.queryData.order_type,
      //   page: this.queryData.page,
      //   per_page: this.queryData.per_page,
      // };
      // await getAllUserManagement(`${urlAPI.urlGetLisUser}?${obj2Path(Search)}`)
      //   .then((res) => {
      //     if (res.data.data) {
      //       const listUser = res.data.data;
      //       this.queryData.total_records = res.data.total;
      //       this.$store.dispatch('userManagement/saveListUser', listUser);
      //     }
      //   })
      //   .catch((error) => {
      //     MakeToast({
      //       variant: 'warning',
      //       title: this.$t('DANGER'),
      //       content: error.message,
      //     });
      //     this.overlay.show = false;
      //   });
    },

    fillterListUser($event) {
      this.overlay.show = true;
      this.getListAllData($event);
    },

    selectAllItem() {
      console.log('selectAllItem');
    },

    selectItem(id) {
      if (this.listId.includes(id) && this.listId.length > 0) {
        this.listId.splice(this.listId.indexOf(id), 1);
      } else {
        this.listId.push(id);
      }
    },

    showDelete() {
      if (this.listId.length > 0) {
        this.showModal = true;
      } else {
        this.closeMess = true;
        this.message.isShowMessage = true;
        this.checkbox = true;
        this.getListAllData();
      }
    },

    createNewUser() {
      this.$router.push('/usermanagement/create');
    },

    goToEditScreen(id) {
      this.$router.push(
        { path: `/usermanagement/edit/${id}` },
        (onAbort) => {}
      );
    },

    async confirmationForm(id, name) {
      this.$bvModal
        .msgBoxConfirm(
          this.$t('MODAL_MESSAGE_CONFIRM_DELETE', { name: name }),
          {
            title: this.$t('MODAL_CONFIRM_DELETE'),
            okVariant: 'danger',
            okTitle: this.$t('MODAL_BUTTON_DELETE'),
            cancelVariant: 'secondary ',
            cancelTitle: this.$t('MODAL_BUTTON_CANCEL'),
            centered: true,
            size: 'lg',
          }
        )
        .then((value) => {
          this.confirmStatus = value;
          if (this.confirmStatus === true) {
            // deleteUserManagement(`${urlAPI.urlGetLisUser}/${id}`).then(() => {
            //   MakeToast({
            //     variant: 'success',
            //     title: this.$t('SUCCESS'),
            //     content: this.$t('CONTENT_SUSS'),
            //   });
            //   this.reRender++;
            //   this.getListAllData();
            // });
          }
          this.getListAllData();
        });
    },

    ConfirmClose() {
      this.checkbox = false;
      this.closeMess = false;
      this.getListAllData();
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

    async DeleteAll() {
      // await deleteAllUserManagement(urlAPI.urlDeleAll, {
      //   id: this.listId,
      // }).then(() => {
      //   MakeToast({
      //     variant: 'success',
      //     title: this.$t('SUCCESS'),
      //     content: this.$t('CONTENT_SUSS'),
      //   });
      //   this.listId.length = 0;
      //   this.reRender++;
      //   this.showModal = false;
      //   this.getListAllData();
      // });
    },

    handleCreate() {
      if (this.handleValidate(this.formData)) {
        console.log('xong');
      }
    },

    handleValidate(formData) {
      for (const x in formData) {
        if (!validEmptyOrWhiteSpace(formData[x])) {
          return true;
        }
      }

      // MakeToast({
      //   variant: 'warning',
      //   title: this.$t('WARNING'),
      //   content: 'Trường 1 cần required',
      // });

      return false;
    },

    checkvalidate() {
      if (this.formData.job_title === '') {
        this.error.job_title = false;
      }
      if (this.formData.company_name === '') {
        this.error.company_name = false;
      }

      if (this.formData.application_period_start === '') {
        this.error.application_period_start = false;
      }

      if (this.formData.application_period_end === '') {
        this.error.application_period_end = false;
      }
      if (this.formData.major_classification_id === null) {
        this.error.major_classification_id = false;
      }
      if (this.formData.middle_classification_id === null) {
        this.error.middle_classification_id = false;
      }

      if (this.formData.job_description === '') {
        this.error.job_description = false;
      }

      if (this.formData.application_condition === '') {
        this.error.application_condition = false;
      }

      if (this.formData.working_time === '') {
        this.error.working_time = false;
      }

      if (this.formData.expected_income === '') {
        this.error.expected_income = false;
      }

      if (this.formData.working_time_from === '') {
        this.error.working_time_from = false;
      }

      if (this.formData.working_time_to === '') {
        this.error.working_time_to = false;
      }

      if (this.formData.application_requirement === '') {
        this.error.application_requirement = false;
      }

      // if (this.formData.preferred_skill === '') {
      //   this.error.preferred_skill = false;
      // }

      if (this.formData.language_requirement === '') {
        this.error.language_requirement = false;
      }

      if (this.formData.form_of_employment === '') {
        this.error.form_of_employment = false;
      }

      if (this.formData.bonus_pay === '') {
        this.error.bonus_pay = false;
      }

      if (this.formData.overtime === '') {
        this.error.overtime = false;
      }

      if (this.formData.passive_smoking === '') {
        this.error.passive_smoking = false;
      }

      if (this.formData.transfer === '') {
        this.error.transfer = false;
      }

      if (this.formData.other_skill === '') {
        this.error.other_skill = false;
      }

      if (this.formData.vacation === '') {
        this.error.vacation = false;
      }

      // if (this.formData.working_place_detail === '') {
      //   this.error.working_place_detail = false;
      // }

      if (this.formData.treatment_welfare === '') {
        this.error.treatment_welfare = false;
      }

      // if (this.formData.company_pr_appeal === '') {
      //   this.error.company_pr_appeal = false;
      // }

      if (this.formData.country_id === null) {
        this.error.country_id = false;
      }

      if (this.formData.working_place === '') {
        this.error.working_place = false;
      }

      if (this.formData.interview_follow === '') {
        this.error.interview_follow = false;
      }

      // if (!this.formData.email || !this.checkEmail(this.formData.email)) {
      //   this.error.email = false;
      // }
      // if (!this.formData.password || this.formData.password.length < 8) {
      //   this.error.password = false;
      // }
      // if (this.formData.role_id === '' && this.roleId === this.headQuarter) {
      //   this.error.role_id = false;
      // }
      // if (
      //   this.formData.department_id === '' &&
      //   this.roleId === this.headQuarter &&
      //   this.formData.role_id === this.department
      // ) {
      //   this.error.department_id = false;
      // }
      // if (
      //   this.formData.department_id === '' &&
      //   this.formData.role_id === this.headQuarter
      // ) {
      //   this.error.department_id = null;
      // }
      // if (this.roleId !== this.headQuarter) {
      //   delete this.error['role_id'];
      //   delete this.error['department_id'];
      // }
      // if (this.formData.role_id === this.headQuarter && !this.formData.department_id) {
      //   delete this.error['department_id'];
      // }
      // if (Object.keys(this.error).every(k => this.error[k] === false)) {
      //   return false;
      // }
      // if (
      //   Object.keys(this.error).every(k => this.error[k] === null) &&
      //   this.roleId === this.headQuarter
      // ) {
      //   return false;
      // }
      // if (Object.keys(this.error).every(k => this.error[k] === true)) {
      //   return true;
      // }
    },

    onSubmit(e) {
      e.preventDefault();
      this.checkvalidate();
      if (this.checkvalidate() === true) {
        console.log('Vào đây');
      } else {
        e.stopPropagation();
      }
    },

    handleChangeForm(event, field) {
      console.log('event, field', event, field);
      const newValue = event;
      switch (field) {
        case 'job_title':
          this.error.job_title = null;
          if (newValue) {
            this.error.job_title = true;
          } else {
            this.error.job_title = false;
          }
          break;

        case 'company_name':
          this.error.company_name = null;
          if (newValue) {
            this.error.company_name = true;
          } else {
            this.error.company_name = false;
          }
          break;

        case 'application_period_start':
          this.error.application_period_start = null;
          if (newValue) {
            this.error.application_period_start = true;
          } else {
            this.error.application_period_start = false;
          }
          break;

        case 'application_period_end':
          this.error.application_period_end = null;
          if (newValue) {
            this.error.application_period_end = true;
          } else {
            this.error.application_period_end = false;
          }
          break;
        case 'major_classification_id':
          this.error.major_classification_id = null;
          if (newValue) {
            this.error.major_classification_id = true;
          } else {
            this.error.major_classification_id = false;
          }
          break;
        case 'middle_classification_id':
          this.error.middle_classification_id = null;
          if (newValue) {
            this.error.middle_classification_id = true;
          } else {
            this.error.middle_classification_id = false;
          }
          break;

        case 'job_description':
          this.error.job_description = null;
          if (newValue) {
            this.error.job_description = true;
          } else {
            this.error.job_description = false;
          }
          break;

        case 'application_condition':
          this.error.application_condition = null;
          if (newValue) {
            this.error.application_condition = true;
          } else {
            this.error.application_condition = false;
          }
          break;

        case 'application_requirement':
          this.error.application_requirement = null;
          if (newValue) {
            this.error.application_requirement = true;
          } else {
            this.error.application_requirement = false;
          }
          break;

        case 'other_skill':
          this.error.other_skill = null;
          if (newValue) {
            this.error.other_skill = true;
          } else {
            this.error.other_skill = false;
          }
          break;

          // case 'preferred_skill':
          //   this.error.preferred_skill = null;
          //   if (newValue) {
          //     this.error.preferred_skill = true;
          //   } else {
          //     this.error.preferred_skill = false;
          //   }
          //   break;

        case 'language_requirement':
          this.error.language_requirement = null;
          if (newValue) {
            this.error.language_requirement = true;
          } else {
            this.error.language_requirement = false;
          }
          break;

        case 'form_of_employment':
          this.error.form_of_employment = null;
          if (newValue) {
            this.error.form_of_employment = true;
          } else {
            this.error.form_of_employment = false;
          }
          break;

        case 'bonus_pay':
          this.error.bonus_pay = null;
          if (newValue) {
            this.error.bonus_pay = true;
          } else {
            this.error.bonus_pay = false;
          }
          break;

        case 'overtime':
          this.error.overtime = null;
          if (newValue) {
            this.error.overtime = true;
          } else {
            this.error.overtime = false;
          }
          break;

        case 'passive_smoking':
          this.error.passive_smoking = null;
          if (newValue) {
            this.error.passive_smoking = true;
          } else {
            this.error.passive_smoking = false;
          }
          break;

        case 'transfer':
          this.error.transfer = null;
          if (newValue) {
            this.error.transfer = true;
          } else {
            this.error.transfer = false;
          }
          break;

        case 'vacation':
          this.error.vacation = null;
          if (newValue) {
            this.error.vacation = true;
          } else {
            this.error.vacation = false;
          }
          break;

          // case 'working_place_detail':
          //   this.error.working_place_detail = null;
          //   if (newValue) {
          //     this.error.working_place_detail = true;
          //   } else {
          //     this.error.working_place_detail = false;
          //   }
          //   break;

        case 'treatment_welfare':
          this.error.treatment_welfare = null;
          if (newValue) {
            this.error.treatment_welfare = true;
          } else {
            this.error.treatment_welfare = false;
          }
          break;

          // case 'company_pr_appeal':
          //   this.error.company_pr_appeal = null;
          //   if (newValue) {
          //     this.error.company_pr_appeal = true;
          //   } else {
          //     this.error.company_pr_appeal = false;
          //   }
          //   break;

        case 'expected_income':
          this.error.expected_income = null;
          if (newValue) {
            this.error.expected_income = true;
          } else {
            this.error.expected_income = false;
          }
          break;

        case 'working_time_from':
          this.error.working_time_from = null;
          if (newValue) {
            this.error.working_time_from = true;
          } else {
            this.error.working_time_from = false;
          }
          break;

        case 'working_time_to':
          this.error.working_time_to = null;
          if (newValue) {
            this.error.working_time_to = true;
          } else {
            this.error.working_time_to = false;
          }
          break;

          // case 'working_time':
          //   this.error.working_time = null;
          //   if (newValue) {
          //     this.error.working_time = true;
          //   } else {
          //     this.error.working_time = false;
          //   }
          //   break;

        case 'working_place':
          this.error.working_place = null;
          if (newValue) {
            this.error.working_place = true;
          } else {
            this.error.working_place = false;
          }
          break;

        case 'country_id':
          this.error.country_id = null;
          if (newValue) {
            this.error.country_id = true;
          } else {
            this.error.country_id = false;
          }
          break;

        case 'interview_follow':
          this.error.interview_follow = null;
          if (newValue) {
            this.error.interview_follow = true;
          } else {
            this.error.interview_follow = false;
          }
          break;

        default:
          break;
      }
    },

    handleBack() {
      this.$router.push('/job/list');
    },

    async HandleGetListMainJob() {
      try {
        const response = await getListEduCourse();
        const code = response.code;
        const codeData = response.data.code;
        const data = response.data.data;

        if (code === 200 || codeData === 200) {
          // console.log('getListEduCourse data: ', data);

          data.map(item => {
            if (this.formData.major_classification_id === item.id) {
              this.formData.major_classification_text = item.name_ja;
            }
          });
          this.major_classification_options = []; // Reset arr
          this.major_classification_options = data;
          const id_major = this.formData.major_classification_id;

          let middle_option = [];
          for (let i = 0; i < this.major_classification_options.length; i++) {
            if (this.major_classification_options[i].id === id_major) {
              middle_option = this.major_classification_options[i].job_info;
            }
          }
          this.middle_classification_options = [];
          this.middle_classification_options = middle_option;

          middle_option.map(item => {
            if (this.formData.middle_classification_id === item.id) {
              this.formData.middle_classification_text = item.name_ja;
            }
          });
        }
        this.renderChildOption(this.formData.major_classification_id);
        //
      } catch (erorr) {
        console.log('erorr', erorr);
      }
    },

    renderChildOption(major_classification_id) {
      // this.$emit('get-list-main-job', major_classification_id);
      let middle_option = [];
      for (let i = 0; i < this.major_classification_options.length; i++) {
        if (this.major_classification_options[i].id === major_classification_id) {
          middle_option = this.major_classification_options[i].job_info;
        }
      }
      this.middle_classification_options = middle_option;
      return middle_option;
    },

    //
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';

.border-left-title {
  border-left: 4px solid #314cad;
  height: 36px;
  line-height: 36px;
}

.bg-gray {
  background-color: #f8f8f8;
}

.p-0 {
  padding: 0 !important;
}

.badge-required {
  color: red;
  border: 1px solid red;
  width: 28px;
  font-size: 8px;
  height: 13px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.badge-not-required {
  color: #999;
  border: 1px solid #999;
  width: 28px;
  font-size: 8px;
  height: 13px;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #fff;
}

textarea.form-control {
  display: flex;
  flex: none;
}

.form-item-row__inputs {
	display: flex;
	// padding: 0.75rem 1.25rem;
	align-items:  center;
	justify-content: center;
  & > span {
  //   border: 1px solid red;
    min-width: 140px;
    width: 20%;
  }

	& > div {
    width: 77%;
		height: 100%;
		display: flex;
		font-size: 14px;
		font-weight: 400;
		line-height: 21px;
		align-items: center;
		justify-content: flex-start;
	}
}
</style>
