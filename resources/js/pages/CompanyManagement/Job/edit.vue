<template>
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

    <div class="display-user-management-list">
      <b-row class="py-2 mb-2">
        <b-col
          cols="12"
          class="d-flex justify-content-between align-items-center"
        >
          <b-col class="title">
            <span class="title-bar" />
            <span class="title-text">{{ $t('JOB_EDIT.JOB_EDIT') }}</span>
          </b-col>

          <div class="control-area">
            <b-button
              class="btn_cancel--custom"
              variant="mx-1"
              @click="handleBack"
            >
              <span>{{ $t('BUTTON.CANCEL') }}</span>
            </b-button>

            <b-button
              type="submit"
              class="btn_save--custom mx-1"
              @click="onSubmit()"
            >
              <span>{{ $t('BUTTON.SAVE') }}</span>
            </b-button>
          </div>
        </b-col>
      </b-row>

      <b-row class="mb-4">
        <b-col cols="12">
          <b-form>
            <b-card-group deck>
              <b-card no-body :header="$t('JOB_EDIT.JOB_INFORMATION')">
                <b-list-group class="list-group-body">
                  <!-- Row 1 職種（タイトル） -->
                  <b-list-group-item class="p-0">
                    <div class="d-flex flex-wrap">
                      <b-col
                        cols="3"
                        class="d-flex flex-row align-items-center bg-gray body-item-title justify-content-between"
                      >
                        <span>{{ $t('JOB_EDIT.TITLE') }}</span>
                        <Require />
                      </b-col>

                      <b-col cols="9" class="my-2">
                        <b-form-input
                          v-model="formData.job_title"
                          dusk="job_title"
                          :maxlength="50"
                          :placeholder="$t('VALIDATE.REQUIRED_TEXT')"
                          :class="
                            error.job_title === false ? ' is-invalid' : ''
                          "
                          @input="handleChangeForm($event, 'job_title')"
                        />

                        <b-form-invalid-feedback :state="error.job_title">
                          <span>{{ $t('VALIDATE.REQUIRED_TEXT') }}</span>
                        </b-form-invalid-feedback>
                      </b-col>
                    </div>
                  </b-list-group-item>

                  <!-- Row 2 企業名 -->
                  <b-list-group-item class="p-0">
                    <div class="d-flex flex-wrap">
                      <b-col
                        cols="3"
                        class="d-flex flex-row align-items-center bg-gray body-item-title justify-content-between"
                      >
                        <span>{{ $t('JOB_EDIT.COMPANY_NAME') }}</span>
                        <Require />
                      </b-col>

                      <b-col v-if="[1, 2].includes(role)" cols="9" class="my-2">
                        <b-form-select
                          v-model="formData.company_id"
                          :class="
                            error.company_id === false ? ' is-invalid' : ''
                          "
                          dusk="company_list"
                          :options="company_list"
                          :value-field="'value'"
                          :text-field="'text'"
                          :disabled-field="'disabled'"
                          @change="handleChangeForm($event, 'company_id')"
                        />

                        <b-form-invalid-feedback :state="error.company_id">
                          <span>{{ $t('VALIDATE.REQUIRED_SELECT') }}</span>
                        </b-form-invalid-feedback>
                      </b-col>

                      <b-col v-else cols="9" class="my-2">
                        <b-form-input
                          v-model="formData.company_name"
                          disabled
                        />
                      </b-col>
                    </div>
                  </b-list-group-item>

                  <!-- Row 3 募集期間 -->
                  <b-list-group-item class="p-0">
                    <div class="d-flex flex-wrap">
                      <b-col
                        cols="3"
                        class="d-flex flex-row align-items-center bg-gray body-item-title justify-content-between"
                      >
                        <span>{{ $t('JOB_EDIT.APPLICATION_PERIOD') }}</span>
                        <Require />
                      </b-col>

                      <b-col cols="9" class="d-flex align-items-center my-2">
                        <b-col cols="5" class="p-0">
                          <b-form-datepicker
                            v-model="formData.application_period_start"
                            locale="ja"
                            label-help=""
                            offset="-278px"
                            dusk="application_period_start"
                            :min="min_application_date_from"
                            :placeholder="$t('VALIDATE.REQUIRED_SELECT')"
                            label-no-date-selected="日付が選択されていません。"
                            :class="
                              error.application_period_start === false
                                ? 'is-invalid'
                                : ''
                            "
                            @input="
                              handleChangeForm(
                                $event,
                                'application_period_start'
                              )
                            "
                          />

                          <b-form-invalid-feedback
                            :state="error.application_period_start"
                          >
                            <span>{{ $t('VALIDATE.REQUIRED_SELECT') }}</span>
                          </b-form-invalid-feedback>
                        </b-col>

                        <b-col
                          cols="2"
                          class="p-0 d-flex align-items-center justify-content-center"
                        >
                          <span>〜</span>
                        </b-col>

                        <b-col cols="5" class="p-0">
                          <b-form-datepicker
                            v-model="formData.application_period_end"
                            locale="ja"
                            label-help=""
                            offset="-278px"
                            dusk="application_period_end"
                            :min="minApplicationDateTo"
                            :placeholder="$t('VALIDATE.REQUIRED_SELECT')"
                            label-no-date-selected="日付が選択されていません。"
                            :class="
                              error.application_period_end === false
                                ? 'is-invalid'
                                : ''
                            "
                            @input="
                              handleChangeForm($event, 'application_period_end')
                            "
                          />

                          <b-form-invalid-feedback
                            :state="error.application_period_end"
                          >
                            <span>{{ $t('VALIDATE.REQUIRED_SELECT') }}</span>
                          </b-form-invalid-feedback>
                        </b-col>
                      </b-col>
                    </div>
                  </b-list-group-item>

                  <!-- Row 3+ 職種 occupation -->
                  <b-list-group-item class="p-0">
                    <div class="d-flex flex-wrap">
                      <b-col
                        cols="3"
                        class="d-flex flex-row align-items-center bg-gray body-item-title justify-content-between"
                      >
                        <span>{{ $t('JOB_EDIT.OCCUPATION') }}</span>
                        <Require />
                      </b-col>

                      <b-col
                        cols="9"
                        class="w-100 d-flex flex-column justify-start align-center my-2"
                      >
                        <div
                          class="form-item-row__inputs d-flex w-100 justify-center align-center mt-2"
                        >
                          <span>{{ $t('COMPANY.MAJOR_CLASSIFICATION') }}</span>

                          <div
                            class="d-flex w-100 flex-column justify-center align-center"
                          >
                            <b-form-select
                              v-model="formData.major_classification"
                              dusk="list_main_job_occupation"
                              :options="list_main_job_occupation"
                              value-field="value"
                              text-field="text"
                              :class="
                                error.major_classification === false
                                  ? 'is-invalid'
                                  : ''
                              "
                              @change="
                                [
                                  handleChangeMainJob(),
                                  handleChangeForm(
                                    $event,
                                    'major_classification'
                                  ),
                                ]
                              "
                            />

                            <b-form-invalid-feedback
                              :state="error.major_classification"
                            >
                              <span>{{ $t('VALIDATE.REQUIRED_SELECT') }}</span>
                            </b-form-invalid-feedback>
                          </div>
                        </div>

                        <div
                          class="form-item-row__inputs d-flex w-100 justify-center align-center mt-2"
                        >
                          <span>{{ $t('COMPANY.MIDDLE_CLASSIFICATION') }}</span>

                          <div
                            class="d-flex w-100 flex-column justify-center align-center"
                          >
                            <b-form-select
                              v-model="formData.middle_classification"
                              dusk="list_middle_classification"
                              :options="list_middle_classification"
                              value-field="value"
                              text-field="text"
                              :disabled="!formData.major_classification"
                              :class="
                                error.middle_classification === false
                                  ? 'is-invalid'
                                  : ''
                              "
                              @change="
                                handleChangeForm(
                                  $event,
                                  'middle_classification'
                                )
                              "
                            />

                            <b-form-invalid-feedback
                              :state="error.middle_classification"
                            >
                              <span>{{ $t('VALIDATE.REQUIRED_SELECT') }}</span>
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
                        class="d-flex flex-row align-items-center bg-gray body-item-title justify-content-between"
                      >
                        <span>{{ $t('JOB_EDIT.JOB_DESCRIPTION') }}</span>
                        <Require />
                      </b-col>

                      <b-col
                        cols="9"
                        class="d-flex align-items-center flex-wrap my-2"
                      >
                        <b-form-textarea
                          v-model="formData.job_description"
                          dusk="job_description"
                          :rows="5"
                          :max-rows="20"
                          :maxlength="2000"
                          :class="
                            error.job_description === false ? ' is-invalid' : ''
                          "
                          :placeholder="$t('VALIDATE.REQUIRED_TEXT')"
                          @input="handleChangeForm($event, 'job_description')"
                        />

                        <b-form-invalid-feedback :state="error.job_description">
                          <span>{{ $t('VALIDATE.REQUIRED_TEXT') }}</span>
                        </b-form-invalid-feedback>
                      </b-col>
                    </div>
                  </b-list-group-item>

                  <!-- Row 5 応募条件 -->
                  <b-list-group-item class="p-0">
                    <div class="d-flex">
                      <b-col
                        cols="3"
                        class="d-flex flex-row align-items-center bg-gray body-item-title justify-content-between"
                      >
                        <span>{{
                          $t('JOB_EDIT.APPLICATION_CONDITION')
                        }}</span>
                        <Require />
                      </b-col>

                      <b-col
                        cols="9"
                        class="d-flex align-items-center flex-wrap my-2"
                      >
                        <b-form-textarea
                          v-model="formData.application_condition"
                          dusk="application_condition"
                          :rows="5"
                          :max-rows="20"
                          :maxlength="2000"
                          :class="
                            error.application_condition === false
                              ? ' is-invalid'
                              : ''
                          "
                          :placeholder="$t('VALIDATE.REQUIRED_TEXT')"
                          @input="
                            handleChangeForm($event, 'application_condition')
                          "
                        />

                        <b-form-invalid-feedback
                          :state="error.application_condition"
                        >
                          <span>{{ $t('VALIDATE.REQUIRED_TEXT') }}</span>
                        </b-form-invalid-feedback>
                      </b-col>
                    </div>
                  </b-list-group-item>

                  <!-- Row 6 応募要件 -->
                  <b-list-group-item class="p-0">
                    <div class="d-flex">
                      <b-col
                        cols="3"
                        class="d-flex flex-row align-items-center bg-gray body-item-title justify-content-between"
                      >
                        <span>{{
                          $t('JOB_EDIT.APPLICATION_REQUIREMENT')
                        }}</span>
                        <Require />
                      </b-col>

                      <b-col
                        cols="9"
                        class="d-flex align-items-center flex-wrap my-2"
                      >
                        <b-form-textarea
                          v-model="formData.application_requirement"
                          dusk="application_requirement"
                          :rows="5"
                          :max-rows="20"
                          :maxlength="2000"
                          :class="
                            error.application_requirement === false
                              ? ' is-invalid'
                              : ''
                          "
                          :placeholder="$t('VALIDATE.REQUIRED_TEXT')"
                          @input="
                            handleChangeForm($event, 'application_requirement')
                          "
                        />

                        <b-form-invalid-feedback
                          :state="error.application_requirement"
                        >
                          <span>{{ $t('VALIDATE.REQUIRED_TEXT') }}</span>
                        </b-form-invalid-feedback>
                      </b-col>
                    </div>
                  </b-list-group-item>

                  <!-- Row 7 対象国 country -->
                  <b-list-group-item class="p-0">
                    <div class="d-flex flex-wrap">
                      <b-col
                        cols="3"
                        class="d-flex flex-row align-items-center bg-gray body-item-titl justify-content-between"
                      >
                        <span>{{ $t('JOB_EDIT.COUNTRY') }}</span>
                        <Require />
                      </b-col>

                      <b-col cols="9" class="my-2">
                        <b-form-select
                          v-model="formData.country"
                          dusk="country_options"
                          :options="country_options"
                          value-field="value"
                          text-field="text"
                          disabled-field="disabled"
                          :class="error.country === false ? 'is-invalid' : ''"
                          @input="handleChangeForm($event, 'country')"
                        />

                        <b-form-invalid-feedback :state="error.country">
                          <span>{{ $t('VALIDATE.REQUIRED_TEXT') }}</span>
                        </b-form-invalid-feedback>
                      </b-col>
                    </div>
                  </b-list-group-item>

                  <!-- Row 8 Language requirement -->
                  <b-list-group-item class="p-0">
                    <div class="d-flex">
                      <b-col
                        cols="3"
                        class="d-flex flex-row align-items-center bg-gray body-item-title justify-content-between"
                      >
                        <span>{{
                          $t('JOB_EDIT.REQUIRED_LANGUAGE_CONDITION')
                        }}</span>
                        <Require />
                      </b-col>

                      <b-col
                        cols="9"
                        class="d-flex align-items-center flex-wrap my-2"
                      >
                        <b-form-checkbox-group
                          v-model="formData.language_requirement"
                          :options="['N1', 'N2', 'N3', 'N4', 'N5', '問わない']"
                          dusk="language_requirement"
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
                          <span>{{ $t('VALIDATE.REQUIRED_SELECT') }}</span>
                        </b-form-invalid-feedback>
                      </b-col>
                    </div>
                  </b-list-group-item>

                  <!-- Row 9 Other skill -->
                  <b-list-group-item class="p-0">
                    <div class="d-flex">
                      <b-col
                        cols="3"
                        class="d-flex flex-row align-items-center bg-gray body-item-title justify-content-between"
                      >
                        <span>{{ $t('JOB_EDIT.OTHER_SKILL') }}</span>
                        <Require />
                      </b-col>

                      <b-col
                        cols="9"
                        class="d-flex align-items-center flex-wrap my-2"
                      >
                        <b-form-textarea
                          v-model="formData.other_skill"
                          dusk="other_skill"
                          :rows="5"
                          :max-rows="20"
                          :maxlength="2000"
                          :class="
                            error.other_skill === false ? ' is-invalid' : ''
                          "
                          :placeholder="$t('VALIDATE.REQUIRED_TEXT')"
                          @input="handleChangeForm($event, 'other_skill')"
                        />

                        <b-form-invalid-feedback :state="error.other_skill">
                          <span>{{ $t('VALIDATE.REQUIRED_TEXT') }}</span>
                        </b-form-invalid-feedback>
                      </b-col>
                    </div>
                  </b-list-group-item>

                  <!-- Row 10 Preferred Skill -->
                  <b-list-group-item class="p-0">
                    <div class="d-flex">
                      <b-col
                        cols="3"
                        class="d-flex flex-row align-items-center bg-gray body-item-title justify-content-between"
                      >
                        <span>{{ $t('JOB_EDIT.PREFERRED_SKILL') }}</span>
                        <Arbitrarily />
                      </b-col>

                      <b-col
                        cols="9"
                        class="d-flex align-items-center flex-wrap my-2"
                      >
                        <b-form-textarea
                          v-model="formData.preferred_skill"
                          dusk="preferred_skill"
                          :rows="5"
                          :max-rows="20"
                          :maxlength="2000"
                          :class="
                            error.preferred_skill === false ? ' is-invalid' : ''
                          "
                          :placeholder="$t('VALIDATE.REQUIRED_TEXT')"
                          @input="handleChangeForm($event, 'preferred_skill')"
                        />

                        <b-form-invalid-feedback :state="error.preferred_skill">
                          <span>{{ $t('VALIDATE.REQUIRED_TEXT') }}</span>
                        </b-form-invalid-feedback>
                      </b-col>
                    </div>
                  </b-list-group-item>

                  <!-- Row 11 Form of employee -->
                  <b-list-group-item class="p-0">
                    <div class="d-flex">
                      <b-col
                        cols="3"
                        class="d-flex flex-row align-items-center bg-gray body-item-title justify-content-between"
                      >
                        <span>{{ $t('JOB_EDIT.FORM_OF_EMPLOYMENT') }}</span>
                        <Require />
                      </b-col>

                      <b-col
                        cols="9"
                        class="d-flex align-items-center flex-wrap my-2"
                      >
                        <b-form-radio-group
                          v-model="formData.form_of_employment"
                          dusk="form_of_employment"
                          :options="[
                            '正社員',
                            '契約社員',
                            '派遣社員',
                            'その他',
                          ]"
                          :aria-describedby="''"
                          name="radio-options"
                          :class="
                            error.form_of_employment === false
                              ? 'form-control is-invalid'
                              : ''
                          "
                          @input="
                            handleChangeForm($event, 'form_of_employment')
                          "
                        />

                        <b-form-invalid-feedback
                          :state="error.form_of_employment"
                        >
                          <span>{{ $t('VALIDATE.REQUIRED_SELECT') }}</span>
                        </b-form-invalid-feedback>
                      </b-col>
                    </div>
                  </b-list-group-item>

                  <!-- Row 12 Working time -->
                  <b-list-group-item class="p-0">
                    <div class="d-flex flex-wrap">
                      <b-col
                        cols="3"
                        class="d-flex flex-row align-items-center bg-gray body-item-title justify-content-between"
                      >
                        <span>{{ $t('JOB_EDIT.WORKING_TIME') }}</span>
                        <Require />
                      </b-col>
                      <b-col
                        cols="9"
                        class="d-flex align-items-center my-2 my-2"
                      >
                        <b-col cols="5" class="p-0">
                          <b-form-select
                            v-model="formData.working_time_from"
                            dusk="working_time_from"
                            :options="working_time_options"
                            value-field="text"
                            text-field="text"
                            :class="
                              error.working_time_from === false
                                ? ' is-invalid'
                                : ''
                            "
                            @input="
                              handleChangeForm($event, 'working_time_from')
                            "
                          />

                          <b-form-invalid-feedback
                            :state="error.working_time_from"
                          >
                            <span>{{ $t('VALIDATE.REQUIRED_SELECT') }}</span>
                          </b-form-invalid-feedback>
                        </b-col>

                        <b-col
                          cols="2"
                          class="p-0 d-flex align-items-center justify-content-center"
                        >
                          <span>〜</span>
                        </b-col>

                        <b-col cols="5" class="p-0">
                          <b-form-select
                            v-model="formData.working_time_to"
                            dusk="working_time_to"
                            :options="working_time_options"
                            value-field="text"
                            text-field="text"
                            :class="
                              error.working_time_to === false
                                ? ' is-invalid'
                                : ''
                            "
                            @input="handleChangeForm($event, 'working_time_to')"
                          />

                          <b-form-invalid-feedback
                            :state="error.working_time_to"
                          >
                            <span>{{ $t('VALIDATE.REQUIRED_SELECT') }}</span>
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
                        class="d-flex flex-row align-items-center bg-gray body-item-title justify-content-between"
                      >
                        <span>{{ $t('JOB_EDIT.VACATION') }}</span>
                        <Require />
                      </b-col>

                      <b-col
                        cols="9"
                        class="d-flex align-items-center flex-wrap my-2"
                      >
                        <b-form-textarea
                          v-model="formData.vacation"
                          dusk="vacation"
                          :maxlength="2000"
                          :rows="5"
                          :max-rows="20"
                          :class="error.vacation === false ? ' is-invalid' : ''"
                          :placeholder="$t('VALIDATE.REQUIRED_TEXT')"
                          @input="handleChangeForm($event, 'vacation')"
                        />

                        <b-form-invalid-feedback :state="error.vacation">
                          <span>{{ $t('VALIDATE.REQUIRED_TEXT') }}</span>
                        </b-form-invalid-feedback>
                      </b-col>
                    </div>
                  </b-list-group-item>

                  <!-- Row 14 Expected income -->
                  <b-list-group-item class="p-0">
                    <div class="d-flex flex-wrap">
                      <b-col
                        cols="3"
                        class="d-flex flex-row align-items-center bg-gray body-item-title justify-content-between"
                      >
                        <span>{{ $t('JOB_EDIT.EXPECTED_INCOME') }}</span>
                        <Require />
                      </b-col>

                      <b-col cols="9" class="my-2">
                        <div class="d-flex flex-row">
                          <b-form-input
                            v-model="formData.expected_income"
                            dusk="expected_income"
                            type="number"
                            :maxlength="5"
                            :class="
                              error.expected_income === false
                                ? ' is-invalid'
                                : ''
                            "
                            :placeholder="$t('VALIDATE.REQUIRED_TEXT')"
                            @input="handleChangeForm($event, 'expected_income')"
                          />

                          <span
                            style="text-wrap: nowrap; line-height: 38px"
                            class="ml-3"
                          >{{ $t('JOB_SEARCH.TEN_THOUSAND_YEN') }}</span>
                        </div>

                        <b-form-invalid-feedback :state="error.expected_income">
                          <span>{{ $t('VALIDATE.REQUIRED_TEXT') }}</span>
                        </b-form-invalid-feedback>
                      </b-col>
                    </div>
                  </b-list-group-item>

                  <!-- Row 15 Working place -->
                  <b-list-group-item class="p-0">
                    <div class="d-flex flex-wrap">
                      <b-col
                        cols="3"
                        class="d-flex flex-row align-items-center bg-gray body-item-title justify-content-between"
                      >
                        <span>{{ $t('JOB_EDIT.WORKING_PLACE') }}</span>
                        <Require />
                      </b-col>

                      <b-col cols="9" class="my-2">
                        <multiselect
                          v-model="formData.working_place"
                          dusk="working_place"
                          label="name_ja"
                          :multiple="false"
                          track-by="name_ja"
                          :options="city_list"
                          :placeholder="$t('VALIDATE.REQUIRED_SELECT')"
                          :group-values="'options'"
                          :group-label="'timezone'"
                          :selected-label="'選択済み'"
                          :select-label="''"
                          :deselect-label="''"
                          :class="
                            error.working_place === false ? ' is-invalid' : ''
                          "
                          @input="handleChangeForm($event, 'working_place')"
                        >
                          <template slot="option" slot-scope="props">
                            <span
                              v-if="props.option.$isLabel"
                              class="group-label"
                            >{{ props.option.$groupLabel }}</span>
                            <span v-else class="option-label">{{
                              props.option.name_ja
                            }}</span>
                          </template>

                          <template slot="noResult">
                            <span>「要素が見つかりません。検索クエリを変更してください。」となります。</span>
                          </template>
                        </multiselect>

                        <b-form-invalid-feedback :state="error.working_place">
                          <span>{{ $t('VALIDATE.REQUIRED_SELECT') }}</span>
                        </b-form-invalid-feedback>
                      </b-col>
                    </div>
                  </b-list-group-item>

                  <!-- Row 16 Working place detail -->
                  <b-list-group-item class="p-0">
                    <div class="d-flex">
                      <b-col
                        cols="3"
                        class="d-flex flex-row align-items-center bg-gray body-item-title justify-content-between"
                      >
                        <span>{{ $t('JOB_EDIT.WORKING_PLACE_DETAIL') }}</span>
                        <Arbitrarily />
                      </b-col>

                      <b-col
                        cols="9"
                        class="d-flex align-items-center flex-wrap my-2"
                      >
                        <b-form-textarea
                          v-model="formData.working_place_detail"
                          dusk="working_place_detail"
                          :class="
                            error.working_place_detail === false
                              ? ' is-invalid'
                              : ''
                          "
                          :placeholder="$t('VALIDATE.REQUIRED_TEXT')"
                          :rows="5"
                          :max-rows="20"
                          @input="
                            handleChangeForm($event, 'working_place_detail')
                          "
                        />

                        <b-form-invalid-feedback
                          :state="error.working_place_detail"
                        >
                          <span>{{ $t('VALIDATE.REQUIRED_TEXT') }}</span>
                        </b-form-invalid-feedback>
                      </b-col>
                    </div>
                  </b-list-group-item>

                  <!-- Row 17 Treatment Walfare -->
                  <b-list-group-item class="p-0">
                    <div class="d-flex flex-wrap">
                      <b-col
                        cols="3"
                        class="d-flex flex-row align-items-center bg-gray body-item-title justify-content-between"
                      >
                        <span>{{ $t('JOB_EDIT.TREATMENT_WELFARE') }}</span>
                        <Require />
                      </b-col>

                      <b-col
                        cols="9"
                        class="d-flex align-items-center flex-wrap my-2"
                      >
                        <b-form-textarea
                          v-model="formData.treatment_welfare"
                          dusk="treatment_welfare"
                          :rows="5"
                          :max-rows="20"
                          :class="
                            error.treatment_welfare === false
                              ? ' is-invalid'
                              : ''
                          "
                          :placeholder="$t('VALIDATE.REQUIRED_TEXT')"
                          @input="handleChangeForm($event, 'treatment_welfare')"
                        />

                        <b-form-invalid-feedback
                          :state="error.treatment_welfare"
                        >
                          <span>{{ $t('VALIDATE.REQUIRED_TEXT') }}</span>
                        </b-form-invalid-feedback>
                      </b-col>
                    </div>
                  </b-list-group-item>

                  <!-- Row 18 Company PR APPEAL -->
                  <b-list-group-item class="p-0">
                    <div class="d-flex flex-wrap">
                      <b-col
                        cols="3"
                        class="d-flex flex-row align-items-center bg-gray body-item-title justify-content-between"
                      >
                        <span>{{ $t('JOB_EDIT.COMPANY_PR_APPEAL') }}</span>
                        <Arbitrarily />
                      </b-col>

                      <b-col
                        cols="9"
                        class="d-flex align-items-center flex-wrap my-2"
                      >
                        <b-form-textarea
                          v-model="formData.company_pr_appeal"
                          dusk="company_pr_appeal"
                          :rows="5"
                          :max-rows="20"
                          :class="
                            error.company_pr_appeal === false
                              ? ' is-invalid'
                              : ''
                          "
                          :placeholder="$t('VALIDATE.REQUIRED_TEXT')"
                          @input="handleChangeForm($event, 'company_pr_appeal')"
                        />

                        <b-form-invalid-feedback
                          :state="error.company_pr_appeal"
                        >
                          <span>{{ $t('VALIDATE.REQUIRED_TEXT') }}</span>
                        </b-form-invalid-feedback>
                      </b-col>
                    </div>
                  </b-list-group-item>

                  <!-- Row 19 Bonus pay rise -->
                  <b-list-group-item class="p-0">
                    <div class="d-flex flex-wrap">
                      <b-col
                        cols="3"
                        class="d-flex flex-row align-items-center bg-gray body-item-title justify-content-between"
                      >
                        <span>{{ $t('JOB_EDIT.BONUS_PAY_RISE') }}</span>
                        <Require />
                      </b-col>

                      <b-col
                        cols="9"
                        class="d-flex align-items-center flex-wrap my-2"
                      >
                        <b-form-radio-group
                          v-model="formData.bonus_pay"
                          :options="['有', '無']"
                          :aria-describedby="'bonus_pay'"
                          dusk="bonus_pay"
                          name="bonus_pay"
                          :class="
                            error.bonus_pay === false
                              ? 'form-control is-invalid'
                              : ''
                          "
                          @input="handleChangeForm($event, 'bonus_pay')"
                        />

                        <b-form-invalid-feedback :state="error.bonus_pay">
                          <span>{{ $t('VALIDATE.REQUIRED_SELECT') }}</span>
                        </b-form-invalid-feedback>
                      </b-col>
                    </div>
                  </b-list-group-item>

                  <!-- Row 20 Overtime -->
                  <b-list-group-item class="p-0">
                    <div class="d-flex">
                      <b-col
                        cols="3"
                        class="d-flex flex-row align-items-center bg-gray body-item-title justify-content-between"
                      >
                        <span>{{ $t('JOB_EDIT.OVER_TIME') }}</span>
                        <Require />
                      </b-col>

                      <b-col
                        cols="9"
                        class="d-flex align-items-center flex-wrap my-2"
                      >
                        <b-form-radio-group
                          v-model="formData.overtime"
                          dusk="overtime"
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
                          <span>{{ $t('VALIDATE.REQUIRED_SELECT') }}</span>
                        </b-form-invalid-feedback>
                      </b-col>
                    </div>
                  </b-list-group-item>

                  <!-- Row 21 Transfer -->
                  <b-list-group-item class="p-0">
                    <div class="d-flex">
                      <b-col
                        cols="3"
                        class="d-flex flex-row align-items-center bg-gray body-item-title justify-content-between"
                      >
                        <span>{{ $t('JOB_EDIT.TRANSFER') }}</span>
                        <Require />
                      </b-col>

                      <b-col
                        cols="9"
                        class="d-flex align-items-center flex-wrap my-2"
                      >
                        <b-form-radio-group
                          v-model="formData.transfer"
                          dusk="transfer"
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
                          <span>{{ $t('VALIDATE.REQUIRED_SELECT') }}</span>
                        </b-form-invalid-feedback>
                      </b-col>
                    </div>
                  </b-list-group-item>

                  <!-- Row 22 Passive Smoking -->
                  <b-list-group-item class="p-0">
                    <div class="d-flex">
                      <b-col
                        cols="3"
                        class="d-flex flex-row align-items-center bg-gray body-item-title justify-content-between"
                      >
                        <span>{{ $t('JOB_EDIT.PASSIVE_SMOKING') }}</span>
                        <Require />
                      </b-col>

                      <b-col
                        cols="9"
                        class="d-flex align-items-center flex-wrap my-2"
                      >
                        <b-form-radio-group
                          v-model="formData.passive_smoking"
                          dusk="passive_smoking"
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
                          <span>{{ $t('VALIDATE.REQUIRED_SELECT') }}</span>
                        </b-form-invalid-feedback>
                      </b-col>
                    </div>
                  </b-list-group-item>

                  <!-- Row 23 Passion  -->
                  <b-list-group-item class="p-0">
                    <div class="d-flex w-100">
                      <b-col
                        cols="3"
                        class="d-flex flex-row align-items-center bg-gray body-item-title justify-content-between"
                      >
                        <span>{{ $t('JOB_EDIT.PASSION') }}</span>
                        <Arbitrarily />
                      </b-col>

                      <b-col
                        cols="9"
                        class="d-flex w-100 justify-content-flex-start flex-wrap align-items-center my-2"
                      >
                        <b-form-checkbox
                          v-model="housing_allowance"
                          button
                          size="sm"
                          class="mx-2 my-1"
                          name="check-button"
                          dusk="housing_allowance"
                          :button-variant="
                            housing_allowance ? 'actived' : 'deactivated'
                          "
                        >
                          <span>{{ $t('HOUSING_ALLOWANCE_AVAILABLE') }}</span>
                        </b-form-checkbox>

                        <b-form-checkbox
                          v-model="welfare_enhancement"
                          button
                          size="sm"
                          class="mx-2 my-1"
                          dusk="welfare_enhancement"
                          name="check-button"
                          :button-variant="
                            welfare_enhancement ? 'actived' : 'deactivated'
                          "
                        >
                          <span>{{
                            $t('JOB_EDIT.PASSION_CHILD_WELFARE_ENHANCEMENT')
                          }}</span>
                        </b-form-checkbox>

                        <b-form-checkbox
                          v-model="severance_pay"
                          button
                          size="sm"
                          class="mx-2 my-1"
                          name="check-button"
                          dusk="severance_pay"
                          :button-variant="
                            severance_pay ? 'actived' : 'deactivated'
                          "
                        >
                          <span>{{
                            $t('JOB_EDIT.PASSION_CHILD_SEVERANCE_PAY')
                          }}</span>
                        </b-form-checkbox>

                        <b-form-checkbox
                          v-model="more_annual_holidays"
                          button
                          size="sm"
                          class="mx-2 my-1"
                          dusk="more_annual_holidays"
                          name="check-button"
                          :button-variant="
                            more_annual_holidays ? 'actived' : 'deactivated'
                          "
                        >
                          <span>{{
                            $t('JOB_EDIT.PASSION_CHILD_OR_MORE_ANNUAL_HOLIDAYS')
                          }}</span>
                        </b-form-checkbox>

                        <b-form-checkbox
                          v-model="residence"
                          button
                          size="sm"
                          class="mx-2 my-1"
                          dusk="residence"
                          name="check-button"
                          :button-variant="
                            residence ? 'actived' : 'deactivated'
                          "
                        >
                          <span>{{
                            $t('JOB_EDIT.PASSION_CHILD_RESIDENCE')
                          }}</span>
                        </b-form-checkbox>

                        <b-form-checkbox
                          v-model="no_experience_ok"
                          button
                          size="sm"
                          class="mx-2 my-1"
                          dusk="no_experience_ok"
                          name="check-button"
                          :button-variant="
                            no_experience_ok ? 'actived' : 'deactivated'
                          "
                        >
                          <span>{{
                            $t('JOB_DETAIL.PASSION_CHILD_NO_EXPERIENCE_OK')
                          }}</span>
                        </b-form-checkbox>

                        <b-form-checkbox
                          v-model="foreign_managerial_staff_hired"
                          button
                          size="sm"
                          class="mx-2 my-1"
                          dusk="foreign_managerial_staff_hired"
                          name="check-button"
                          :button-variant="
                            foreign_managerial_staff_hired
                              ? 'actived'
                              : 'deactivated'
                          "
                        >
                          <span>{{
                            $t('JOB_DETAIL.PASSION_CHILD_HIRING')
                          }}</span>
                        </b-form-checkbox>

                        <b-form-checkbox
                          v-model="remote_work_available"
                          button
                          size="sm"
                          class="mx-2 my-1"
                          dusk="remote_work_available"
                          name="check-button"
                          :button-variant="
                            remote_work_available ? 'actived' : 'deactivated'
                          "
                        >
                          <span>{{
                            $t('JOB_DETAIL.PASSION_CHILD_REMOTE_WORK_OK')
                          }}</span>
                        </b-form-checkbox>

                        <b-form-checkbox
                          v-model="moving_allowance"
                          button
                          size="sm"
                          class="mx-2 my-1"
                          dusk="moving_allowance"
                          name="check-button"
                          :button-variant="
                            moving_allowance ? 'actived' : 'deactivated'
                          "
                        >
                          <span>{{
                            $t('JOB_DETAIL.PASSION_CHILD_MOVING_ALLOWANCE')
                          }}</span>
                        </b-form-checkbox>
                      </b-col>
                    </div>
                  </b-list-group-item>

                  <!-- Row 24 Interview follow -->
                  <b-list-group-item class="p-0">
                    <div class="d-flex flex-wrap">
                      <b-col
                        cols="3"
                        class="d-flex flex-row align-items-center bg-gray body-item-titl justify-content-between"
                      >
                        <span>{{ $t('JOB_EDIT.INTERVIEW_FOLLOW') }}</span>
                        <Require />
                      </b-col>

                      <b-col cols="9" class="my-2">
                        <b-form-select
                          v-model="formData.interview_follow"
                          text-field="text"
                          name="some-radios"
                          value-field="value"
                          disabled-field="disabled"
                          dusk="interview_follow"
                          :options="interview_follow_options"
                          :class="
                            error.interview_follow === false
                              ? ' is-invalid'
                              : ''
                          "
                          @change="handleChangeForm($event, 'interview_follow')"
                        />

                        <b-form-invalid-feedback
                          :state="error.interview_follow"
                        >
                          <span>{{ $t('VALIDATE.REQUIRED_SELECT') }}</span>
                        </b-form-invalid-feedback>
                      </b-col>
                    </div>
                  </b-list-group-item>
                </b-list-group>
              </b-card>
            </b-card-group>

            <b-card-group deck>
              <b-card :header="$t('TITLE.COMPANY_INFORMATION')">
                <b-list-group class="list-group-body">
                  <b-list-group-item class="p-0">
                    <div class="d-flex">
                      <b-col
                        cols="3"
                        class="d-flex flex-row align-items-center bg-gray body-item-title justify-content-between"
                      >
                        <span>{{ $t('JOB_DETAIL.ESTABLISHMENT_YEAR') }}</span>
                        <Arbitrarily />
                      </b-col>

                      <b-col
                        cols="9"
                        class="d-flex align-items-center flex-wrap my-2"
                      >
                        <b-form-input
                          v-model="formData.establishment_year"
                          disabled
                          aria-label=""
                        />
                      </b-col>
                    </div>
                  </b-list-group-item>

                  <b-list-group-item class="p-0">
                    <div class="d-flex">
                      <b-col
                        cols="3"
                        class="d-flex flex-row align-items-center bg-gray body-item-title justify-content-between"
                      >
                        <span>{{ $t('JOB_DETAIL.STARTUP_YEAR') }}</span>
                        <Arbitrarily />
                      </b-col>

                      <b-col
                        cols="9"
                        class="d-flex align-items-center flex-wrap my-2"
                      >
                        <b-form-input
                          v-model="formData.startup_year"
                          disabled
                          aria-label=""
                        />
                      </b-col>
                    </div>
                  </b-list-group-item>

                  <b-list-group-item class="p-0">
                    <div class="d-flex">
                      <b-col
                        cols="3"
                        class="d-flex flex-row align-items-center bg-gray body-item-title justify-content-between"
                      >
                        <span>{{ $t('JOB_DETAIL.CAPITAL') }}</span>
                        <Arbitrarily />
                      </b-col>

                      <b-col
                        cols="9"
                        class="d-flex align-items-center flex-wrap my-2"
                      >
                        <b-form-input
                          v-model="formData.capital"
                          disabled
                          aria-label=""
                        />
                      </b-col>
                    </div>
                  </b-list-group-item>

                  <b-list-group-item class="p-0">
                    <div class="d-flex">
                      <b-col
                        cols="3"
                        class="d-flex flex-row align-items-center bg-gray body-item-title justify-content-between"
                      >
                        <span>{{ $t('JOB_DETAIL.PROCEEDS') }}</span>
                        <Arbitrarily />
                      </b-col>

                      <b-col
                        cols="9"
                        class="d-flex align-items-center flex-wrap my-2"
                      >
                        <b-form-input
                          v-model="formData.proceeds"
                          disabled
                          aria-label=""
                        />
                      </b-col>
                    </div>
                  </b-list-group-item>

                  <b-list-group-item class="p-0">
                    <div class="d-flex">
                      <b-col
                        cols="3"
                        class="d-flex flex-row align-items-center bg-gray body-item-title justify-content-between"
                      >
                        <span>{{ $t('JOB_DETAIL.NUMBER_OF_STAFFS') }}</span>
                        <Arbitrarily />
                      </b-col>

                      <b-col
                        cols="9"
                        class="d-flex align-items-center flex-wrap my-2"
                      >
                        <b-form-input
                          v-model="formData.number_of_staffs"
                          disabled
                          aria-label=""
                        />
                      </b-col>
                    </div>
                  </b-list-group-item>

                  <b-list-group-item class="p-0">
                    <div class="d-flex">
                      <b-col
                        cols="3"
                        class="d-flex flex-row align-items-center bg-gray body-item-title justify-content-between"
                      >
                        <span>{{
                          $t('JOB_DETAIL.NUMBER_OF_OPERATIONS')
                        }}</span>
                        <Arbitrarily />
                      </b-col>

                      <b-col
                        cols="9"
                        class="d-flex align-items-center flex-wrap my-2"
                      >
                        <b-form-input
                          v-model="formData.number_of_operations"
                          disabled
                          aria-label=""
                        />
                      </b-col>
                    </div>
                  </b-list-group-item>

                  <b-list-group-item class="p-0">
                    <div class="d-flex">
                      <b-col
                        cols="3"
                        class="d-flex flex-row align-items-center bg-gray body-item-title justify-content-between"
                      >
                        <span>{{ $t('JOB_DETAIL.NUMBER_OF_SHOPS') }}</span>
                        <Arbitrarily />
                      </b-col>

                      <b-col
                        cols="9"
                        class="d-flex align-items-center flex-wrap my-2"
                      >
                        <b-form-input
                          v-model="formData.number_of_shops"
                          disabled
                          aria-label=""
                        />
                      </b-col>
                    </div>
                  </b-list-group-item>

                  <b-list-group-item class="p-0">
                    <div class="d-flex">
                      <b-col
                        cols="3"
                        class="d-flex flex-row align-items-center bg-gray body-item-title justify-content-between"
                      >
                        <span>{{
                          $t('JOB_DETAIL.NUMBER_OF_FACTORIES')
                        }}</span>
                        <Arbitrarily />
                      </b-col>

                      <b-col
                        cols="9"
                        class="d-flex align-items-center flex-wrap my-2"
                      >
                        <b-form-input
                          v-model="formData.number_of_factories"
                          disabled
                          aria-label=""
                        />
                      </b-col>
                    </div>
                  </b-list-group-item>

                  <b-list-group-item class="p-0">
                    <div class="d-flex">
                      <b-col
                        cols="3"
                        class="d-flex flex-row align-items-center bg-gray body-item-title justify-content-between"
                      >
                        <span>{{ $t('JOB_DETAIL.FISCAL_YEAR') }}</span>
                        <Arbitrarily />
                      </b-col>

                      <b-col
                        cols="9"
                        class="d-flex align-items-center flex-wrap my-2"
                      >
                        <b-form-input
                          v-model="formData.fiscal_year"
                          disabled
                          aria-label=""
                        />
                      </b-col>
                    </div>
                  </b-list-group-item>
                </b-list-group>
              </b-card>
            </b-card-group>
          </b-form>
        </b-col>
      </b-row>

      <div class="text-center">
        <template v-if="status === 2 || status === 1">
          <b-button
            class="text-white"
            :variant="status === 2 ? 'warning' : 'danger'"
            @click="handlePauseJobOrContinue(status)"
          >
            {{
              status === 2
                ? $t('BUTTON.RESUME_RECRUITMENT')
                : $t('BUTTON.PAUSE_RECRUITMENT')
            }}
          </b-button>
        </template>
      </div>
    </div>
  </b-overlay>
</template>

<script>
import Multiselect from 'vue-multiselect';

import { countryOptions } from '@/const/job';
import { getOneJob } from '@/api/modules/job';
import { postOneJob } from '@/api/modules/job';
import { getListCompany } from '@/api/modules/company';
import { MakeToast } from '../../../utils/toastMessage';
import { getClassificationListOccupation } from '@/api/modules/hr';
import { updateStatusWork } from '@/api/job';
import Require from '@/components/Require/Require.vue';
import Arbitrarily from '@/components/Arbitrarily/Arbitrarily.vue';

const urlAPI = {
  urlGetOneJob: '/work',
  urlPostOneJob: '/work',
  urlGetJobList: '/job-type',
  urlGetListCompany: '/company-option',
};

export default {
  name: 'JobEdit',
  components: {
    Multiselect,
    Require,
    Arbitrarily,
  },
  data() {
    return {
      housing_allowance: false,
      welfare_enhancement: false,
      severance_pay: false,
      more_annual_holidays: false,
      residence: false,
      no_experience_ok: false,
      foreign_managerial_staff_hired: false,
      remote_work_available: false,
      moving_allowance: false,
      status: null,

      overlay: {
        opacity: 1,
        show: false,
        blur: '1rem',
        rounded: 'sm',
        variant: 'light',
      },

      formData: {
        id: '',
        job_title: '',

        company_id: null,
        company_name: '',

        application_period_start: '',
        application_period_end: '',

        major_classification: null,
        middle_classification: null,

        job_description: '',

        application_condition: '',
        application_requirement: '',

        country: 1,

        language_requirement: [],

        other_skill: '',
        preferred_skill: '',

        form_of_employment: '',

        vacation: '',

        expected_income: '',

        working_time_to: null,
        working_time_from: null,

        working_place: null,
        working_place_detail: '',

        treatment_welfare: '',

        company_pr_appeal: '',

        bonus_pay: '',

        overtime: '',

        transfer: '',

        passive_smoking: '',

        passion: '',

        interview_follow: null,

        establishment_year: '',
        startup_year: '',
        capital: '',
        proceeds: '',
        number_of_staffs: '',
        number_of_operations: '',
        number_of_shops: '',
        number_of_factories: '',
        fiscal_year: '',
      },

      error: {
        job_title: null,
        company_id: null,
        application_period_start: null,
        application_period_end: null,
        major_classification: null,
        middle_classification: null,
        job_description: null,
        application_condition: null,
        application_requirement: null,
        country: null,
        language_requirement: null,
        other_skill: null,
        form_of_employment: null,
        working_time: null,
        vacation: null,
        expected_income: null,
        working_time_from: null,
        working_time_to: null,
        working_place: null,
        treatment_welfare: null,
        bonus_pay: null,
        overtime: null,
        transfer: null,
        passive_smoking: null,
        passion: null,
        interview_follow: null,
      },

      country_options: countryOptions,

      working_time_options: [
        { value: null, text: '選択してください', disabled: true },
        { value: 1, text: '00:00', disabled: false },
        { value: 2, text: '00:30', disabled: false },
        { value: 3, text: '01:00', disabled: false },
        { value: 4, text: '01:30', disabled: false },
        { value: 5, text: '02:00', disabled: false },
        { value: 6, text: '02:30', disabled: false },
        { value: 7, text: '03:00', disabled: false },
        { value: 8, text: '03:30', disabled: false },
        { value: 9, text: '04:00', disabled: false },
        { value: 10, text: '04:30', disabled: false },
        { value: 11, text: '05:00', disabled: false },
        { value: 12, text: '05:30', disabled: false },
        { value: 13, text: '06:00', disabled: false },
        { value: 14, text: '06:30', disabled: false },
        { value: 15, text: '07:00', disabled: false },
        { value: 16, text: '07:30', disabled: false },
        { value: 17, text: '08:00', disabled: false },
        { value: 18, text: '08:30', disabled: false },
        { value: 19, text: '09:00', disabled: false },
        { value: 20, text: '09:30', disabled: false },
        { value: 21, text: '10:00', disabled: false },
        { value: 22, text: '10:30', disabled: false },
        { value: 23, text: '11:00', disabled: false },
        { value: 24, text: '11:30', disabled: false },
        { value: 25, text: '12:00', disabled: false },
        { value: 26, text: '12:30', disabled: false },
        { value: 27, text: '13:00', disabled: false },
        { value: 28, text: '13:30', disabled: false },
        { value: 29, text: '14:00', disabled: false },
        { value: 30, text: '14:30', disabled: false },
        { value: 31, text: '15:30', disabled: false },
        { value: 32, text: '15:00', disabled: false },
        { value: 33, text: '15:30', disabled: false },
        { value: 34, text: '16:00', disabled: false },
        { value: 35, text: '16:30', disabled: false },
        { value: 36, text: '17:00', disabled: false },
        { value: 37, text: '17:30', disabled: false },
        { value: 38, text: '18:00', disabled: false },
        { value: 39, text: '18:30', disabled: false },
        { value: 40, text: '19:00', disabled: false },
        { value: 41, text: '19:30', disabled: false },
        { value: 42, text: '20:00', disabled: false },
        { value: 43, text: '20:30', disabled: false },
        { value: 44, text: '21:00', disabled: false },
        { value: 45, text: '21:30', disabled: false },
        { value: 46, text: '22:00', disabled: false },
        { value: 47, text: '22:30', disabled: false },
        { value: 48, text: '23:00', disabled: false },
        { value: 49, text: '23:30', disabled: false },
      ],

      interview_follow_options: [
        { value: null, text: '選択してください', disabled: true },
        { value: 1, text: '一次面接まで', disabled: false },
        { value: 2, text: '二次面接まで', disabled: false },
        { value: 3, text: '三次面接まで', disabled: false },
        { value: 4, text: '四次面接まで', disabled: false },
        { value: 5, text: '五次面接まで', disabled: false },
      ],

      company_list: [{ value: null, text: '選択してください', disabled: true }],

      list_main_job_occupation: [
        {
          value: null,
          text: '選択してください',
          sub_options: [],
          disabled: true,
        },
      ],

      list_middle_classification: [
        { value: null, text: '選択してください', disabled: true },
      ],

      city_list: [
        {
          timezone: '北海道・東北',
          options: [
            { id: 1, name_en: 'Hokkaido', name_ja: '北海道' },
            { id: 2, name_en: 'Aomori', name_ja: '青森県' },
            { id: 3, name_en: 'Iwate', name_ja: '岩手県' },
            { id: 4, name_en: 'Miyagi', name_ja: '宮城県' },
            { id: 5, name_en: 'Akita', name_ja: '秋田県' },
            { id: 6, name_en: 'Yamagata', name_ja: '山形県' },
            { id: 7, name_en: 'Fukushima', name_ja: '福島県' },
          ],
        },
        {
          timezone: '北陸・甲信越',
          options: [
            { id: 16, name_en: 'Toyama', name_ja: '富山県' },
            { id: 17, name_en: 'Ishikawa', name_ja: '石川県' },
            { id: 18, name_en: 'Fukui', name_ja: '福井県' },
            { id: 15, name_en: 'Niigata', name_ja: '新潟県' },
            { id: 19, name_en: 'Yamanashi', name_ja: '山梨県' },
            { id: 20, name_en: 'Nagano', name_ja: '長野県' },
          ],
        },
        {
          timezone: '関東',
          options: [
            { id: 13, name_en: 'Tokyo', name_ja: '東京都' },
            { id: 14, name_en: 'Kanagawa', name_ja: '神奈川県' },
            { id: 12, name_en: 'Chiba', name_ja: '千葉県' },
            { id: 11, name_en: 'Saitama', name_ja: '埼玉県' },
            { id: 8, name_en: 'Ibaraki', name_ja: '茨城県' },
            { id: 9, name_en: 'Tochigi', name_ja: '栃木県' },
            { id: 10, name_en: 'Gunma', name_ja: '群馬県' },
          ],
        },
        {
          timezone: '東海',
          options: [
            { id: 23, name_en: 'Aichi', name_ja: '愛知県' },
            { id: 22, name_en: 'Shizuoka', name_ja: '静岡県' },
            { id: 21, name_en: 'Gifu', name_ja: '岐阜県' },
            { id: 24, name_en: 'Mie', name_ja: '三重県' },
          ],
        },
        {
          timezone: '関西',
          options: [
            { id: 27, name_en: 'Osaka', name_ja: '大阪府' },
            { id: 26, name_en: 'Kyoto', name_ja: '京都府' },
            { id: 28, name_en: 'Hyogo', name_ja: '兵庫県' },
            { id: 25, name_en: 'Shiga', name_ja: '滋賀県' },
            { id: 29, name_en: 'Nara', name_ja: '奈良県' },
            { id: 30, name_en: 'Wakayama', name_ja: '和歌山県' },
          ],
        },
        {
          timezone: '中国',
          options: [
            { id: 34, name_en: 'Hiroshima', name_ja: '広島県' },
            { id: 33, name_en: 'Okayama', name_ja: '岡山県' },
            { id: 31, name_en: 'Tottori', name_ja: '鳥取県' },
            { id: 32, name_en: 'Shimane', name_ja: '島根県' },
            { id: 35, name_en: 'Yamaguchi', name_ja: '山口県' },
          ],
        },
        {
          timezone: '四国',
          options: [
            { id: 36, name_en: 'Tokushima', name_ja: '徳島県' },
            { id: 37, name_en: 'Kagawa', name_ja: '香川県' },
            { id: 38, name_en: 'Ehime', name_ja: '愛媛県' },
            { id: 39, name_en: 'Kochi', name_ja: '高知県' },
          ],
        },
        {
          timezone: '九州・沖縄',
          options: [
            { id: 40, name_en: 'Fukuoka', name_ja: '福岡県' },
            { id: 43, name_en: 'Kumamoto', name_ja: '熊本県' },
            { id: 41, name_en: 'Saga', name_ja: '佐賀県' },
            { id: 42, name_en: 'Nagasaki', name_ja: '長崎県' },
            { id: 44, name_en: 'Ōita', name_ja: '大分県' },
            { id: 45, name_en: 'Miyazaki', name_ja: '宮崎県' },
            { id: 46, name_en: 'Kagoshima', name_ja: '鹿児島県' },
            { id: 47, name_en: 'Okinawa', name_ja: '沖縄県' },
          ],
        },
      ],

      min_application_date_from: '',
      min_application_date_to: '',
    };
  },
  computed: {
    role() {
      return this.$store.getters.profile['type'];
    },
    minApplicationDateTo() {
      const DATE = this.formData.application_period_start;
      let result = '';

      if (DATE) {
        const date = new Date(DATE);

        date.setDate(date.getDate() + 1);

        const year = date.getFullYear();
        const month = (date.getMonth() + 1).toString().padStart(2, '0');
        const day = date.getDate().toString().padStart(2, '0');

        result = `${year}-${month}-${day}`;
      }

      return result;
    },
  },
  created() {
    this.handleInitComponentData();
  },
  methods: {
    async handleInitComponentData() {
      this.overlay.show = true;

      if (this.role && [1, 2].includes(this.role)) {
        await this.handleGetListCompany();
      } else if (this.role === 4) {
        this.handleGetCompanyInfo();
      }

      await this.handleGetListJobOccupation();

      await this.handleGetJobInformation();
    },
    handleSetMinApplicationDateFrom(string) {
      if (string) {
        const date = new Date(string);
        const year = date.getFullYear().toString();
        const month = (date.getMonth() + 1).toString().padStart(2, '0');
        const day = date.getDate().toString().padStart(2, '0');

        const now = new Date();
        const now_year = now.getFullYear().toString();
        const now_month = (now.getMonth() + 1).toString().padStart(2, '0');
        const now_day = now.getDate().toString().padStart(2, '0');

        const MIN_DATE = `${year}-${month}-${day}`;
        const MIN_DATE_NOW = `${now_year}-${now_month}-${now_day}`;
        if (Number(now_year) < Number(year)) {
          this.min_application_date_from = MIN_DATE_NOW;
          return;
        } else if (Number(now_year) > Number(year)) {
          this.min_application_date_from = MIN_DATE;
          return;
        } else if (Number(now_year) === Number(year)) {
          if (Number(now_month) < Number(month)) {
            this.min_application_date_from = MIN_DATE_NOW;
            return;
          } else if (Number(now_month) > Number(month)) {
            this.min_application_date_from = MIN_DATE;
            return;
          } else {
            if (Number(now_day) < Number(day)) {
              this.min_application_date_from = MIN_DATE_NOW;
              return;
            } else if (Number(now_day) > Number(day)) {
              this.min_application_date_from = MIN_DATE;
              return;
            } else {
              this.min_application_date_from = MIN_DATE;
              return;
            }
          }
        }

        // const min_date = `${year}-${month}-${day}`;

        // this.min_application_date_from = min_date;
      }
    },
    handleSetMinApplicationDateTo(string) {
      let result = '';

      if (string) {
        const date = new Date(string);

        date.setDate(date.getDate() + 1);

        const year = date.getFullYear();
        const month = (date.getMonth() + 1).toString().padStart(2, '0');
        const day = date.getDate().toString().padStart(2, '0');

        result = `${year}-${month}-${day}`;

        this.min_application_date_to = result;
      }
    },
    async handleGetJobInformation() {
      const ID = this.$route.params['id'];

      if (ID) {
        try {
          const URL = `${urlAPI.urlGetOneJob}/${ID}`;

          const response = await getOneJob(URL);

          if (response['code'] === 200) {
            const DATA = response['data']['data'];
            this.formData.id = DATA['id'];
            this.formData.job_title = DATA['title'];
            this.status = DATA['status'];

            this.formData.company_id = DATA['company_id'];
            // this.formData.company_name = await this.handleGetCompanyName(DATA['company_id']);
            this.formData.company_name = DATA['company']['company_name'];

            await this.handleGetCompanyInfo(DATA['company_id']);

            this.formData.application_period_start =
              DATA['application_period_from'];
            await this.handleSetMinApplicationDateFrom(
              this.formData.application_period_start
            );
            this.formData.application_period_end =
              DATA['application_period_to'];
            await this.handleSetMinApplicationDateTo(
              this.formData.application_period_start
            );

            this.formData.major_classification = parseInt(
              DATA['major_classification_id']
            );
            await this.handleChangeMainJob(this.formData.major_classification);
            this.formData.middle_classification = parseInt(
              DATA['middle_classification_id']
            );

            this.formData.job_description = DATA['job_description'];

            this.formData.application_condition = DATA['application_condition'];
            this.formData.application_requirement =
              DATA['application_requirement'];

            // // this.country = DATA['']; BE NOT HANDLE THIS FIELD

            this.formData.language_requirement =
              this.handleGetLanguageRequirement(DATA['language_requirements']);

            this.formData.other_skill = DATA['other_skill'];
            this.formData.preferred_skill = DATA['preferred_skill'];

            this.formData.form_of_employment = this.handleGetFormOfEmployee(
              DATA['form_of_employment']
            );

            this.formData.vacation = DATA['vacation'];

            this.formData.expected_income =
              parseInt(DATA['expected_income']) || '';

            this.formData.working_time_from = DATA['working_time_from'];
            this.formData.working_time_to = DATA['working_time_to'];

            this.formData.working_place = this.handleGetWorkingPlace(
              DATA['city_id']
            );
            this.formData.working_place_detail = DATA['working_palace_detail'];

            this.formData.treatment_welfare = DATA['treatment_welfare'];
            this.formData.company_pr_appeal = DATA['company_pr_appeal'];

            this.formData.bonus_pay = this.handleTransformRadioOptions(
              DATA['bonus_pay_rise']
            );
            this.formData.overtime = this.handleTransformRadioOptions(
              DATA['over_time']
            );
            this.formData.transfer = this.handleTransformRadioOptions(
              DATA['transfer']
            );
            this.formData.passive_smoking = this.handleTransformRadioOptions(
              DATA['passive_smoking']
            );

            this.formData.passion = DATA['passion'];
            this.handleProcessSelectedPassions(DATA['passion']);

            this.formData.interview_follow = DATA['interview_follow'];
          } else {
            MakeToast({
              variant: 'warning',
              title: this.$t('DANGER'),
              content: response['message'] || '',
            });
            this.$router.push('/job/list');
          }
        } catch (error) {
          console.log(error);
        }
      }

      this.overlay.show = false;
    },
    async handleGetListCompany() {
      try {
        this.company_list = [
          { value: null, text: '選択してください', disabled: true },
        ];

        const URL = `${urlAPI.urlGetListCompany}`;

        const response = await getListCompany(URL);

        if (response['code'] === 200) {
          const DATA = response['data'];

          DATA.forEach((item) => {
            this.company_list.push({
              value: item['id'],
              text: item['company_name'],
              data: item,
              disabled: false,
            });
          });
        }
      } catch (error) {
        console.log(error);
      }
    },
    handleGetCompanyInfo(company_id) {
      if (company_id) {
        const DATA = this.company_list.find((item) => {
          if (item['value'] === company_id) {
            return item;
          }
        });

        if (DATA) {
          this.formData.establishment_year = DATA['data']['establishment_year'];
          this.formData.startup_year = DATA['data']['startup_year'];
          this.formData.capital = DATA['data']['capital'];
          this.formData.proceeds = DATA['data']['proceeds'];
          this.formData.number_of_staffs = DATA['data']['number_of_staffs'];
          this.formData.number_of_operations =
            DATA['data']['number_of_operations'];
          this.formData.number_of_shops = DATA['data']['number_of_shops'];
          this.formData.number_of_factories = DATA['data']['number_of_factory'];
          this.formData.fiscal_year = DATA['data']['fiscal'];
        }
      } else {
        const DATA = this.$store.getters.profile['company'];

        if (DATA) {
          this.formData.company_id = DATA['id'];
          this.formData.company_name = DATA['company_name_jp'];
          this.formData.establishment_year = DATA['establishment_year'];
          this.formData.startup_year = DATA['startup_year'];
          this.formData.capital = DATA['capital'];
          this.formData.proceeds = DATA['proceeds'];
          this.formData.number_of_staffs = DATA['number_of_staffs'];
          this.formData.number_of_operations = DATA['number_of_operations'];
          this.formData.number_of_shops = DATA['number_of_shops'];
          this.formData.number_of_factories = DATA['number_of_factory'];
          this.formData.fiscal_year = DATA['fiscal'];
        }
      }
    },
    handleGetCompanyName(company_id) {
      let result;

      if (company_id) {
        this.company_list.find((item) => {
          if (item['value'] === company_id) {
            result = item['text'];
          }
        });
      }

      return result;
    },
    async handleGetListJobOccupation() {
      this.list_main_job_occupation = [
        {
          value: null,
          text: '選択してください',
          sub_options: [],
          disabled: true,
        },
      ];

      const TYPE = 1;

      const URL = `${urlAPI.urlGetJobList}?type=${TYPE}`;

      try {
        const response = await getClassificationListOccupation(URL);

        if (response['code'] === 200) {
          const DATA = response['data'];

          DATA.forEach((item) => {
            this.list_main_job_occupation.push({
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
    handleChangeMainJob() {
      if (this.formData.major_classification) {
        this.list_middle_classification = [
          {
            value: null,
            type: null,
            text: '選択してください',
            disabled: true,
            selected: true,
          },
        ];

        const findItem = this.list_main_job_occupation.find(
          (item) => item.value === this.formData.major_classification
        );
        if (findItem) {
          this.formData.middle_classification = null;
          findItem.sub_options.forEach((item) => {
            this.list_middle_classification.push({
              value: item['id'],
              text: item['name_ja'],
              type: item['job_type_id'],
              disabled: false,
            });
          });
        }
      }
    },
    async handleUpdateJob() {
      const ID = this.$route.params.id;

      const DATA = {
        title: this.formData.job_title,
        major_classification_id: this.formData.major_classification,
        middle_classification_id: this.formData.middle_classification,
        company_id: this.formData.company_id,
        application_period_from: this.formData.application_period_start,
        application_period_to: this.formData.application_period_end,
        job_description: this.formData.job_description,
        application_condition: this.formData.application_condition,
        application_requirement: this.formData.application_requirement,
        language_requirements: `[${this.handleParseArray(
          this.formData.language_requirement
        )}]`,
        other_skill: this.formData.other_skill,
        preferred_skill: this.formData.preferred_skill,
        form_of_employment: this.convertRadioOption(
          2,
          this.formData.form_of_employment
        ),
        working_time_from: this.formData.working_time_from,
        working_time_to: this.formData.working_time_to,
        vacation: this.formData.vacation,
        expected_income: this.formData.expected_income,
        city_id: this.formData.working_place
          ? this.formData.working_place['id']
          : null,
        working_palace_detail: this.formData.working_place_detail,
        treatment_welfare: this.formData.treatment_welfare,
        company_pr_appeal: this.formData.company_pr_appeal,
        bonus_pay_rise: this.convertRadioOption(1, this.formData.bonus_pay),
        over_time: this.convertRadioOption(1, this.formData.overtime),
        transfer: this.convertRadioOption(1, this.formData.transfer),
        passive_smoking: this.convertRadioOption(
          1,
          this.formData.passive_smoking
        ),
        passions: `[${this.handFormatPassions()}]`,
        interview_follow: this.formData.interview_follow,
      };

      const URL = `${urlAPI.urlPostOneJob}/${ID}`;

      this.overlay.show = true;

      try {
        const response = await postOneJob(URL, DATA);

        if (response['code'] === 200) {
          MakeToast({
            variant: 'success',
            title: this.$t('SUCCESS'),
            content: '編集が成功しました',
          });

          this.$router.push({ path: `/job/detail/${ID}` });
        } else {
          MakeToast({
            variant: 'warning',
            title: this.$t('WARNING'),
            content: response['message'],
          });
        }
      } catch (error) {
        console.log(error);

        MakeToast({
          variant: 'warning',
          title: this.$t('WARNING'),
          content: error['message'],
        });
      }

      this.overlay.show = false;
    },
    handleValidationFormData() {
      let result;

      if (this.formData.job_title === '') {
        result = false;
        this.error.job_title = false;
      } else {
        result = true;
      }

      if (this.formData.company_id === null) {
        result = false;
        this.error.company_id = false;
      } else {
        result = true;
      }

      if (this.formData.application_period_start === '') {
        result = false;
        this.error.application_period_start = false;
      } else {
        result = true;
      }

      if (this.formData.application_period_end === '') {
        result = false;
        this.error.application_period_end = false;
      } else {
        result = true;
      }

      if (this.formData.major_classification === null) {
        result = false;
        this.error.major_classification = false;
      } else {
        result = true;
      }

      if (this.formData.middle_classification === null) {
        result = false;
        this.error.middle_classification = false;
      } else {
        result = true;
      }

      if (this.formData.job_description === '') {
        result = false;
        this.error.job_description = false;
      } else {
        result = true;
      }

      if (this.formData.application_condition === '') {
        result = false;
        this.error.application_condition = false;
      } else {
        result = true;
      }

      if (this.formData.working_time === '') {
        result = false;
        this.error.working_time = false;
      } else {
        result = true;
      }

      if (this.formData.expected_income === '') {
        result = false;
        this.error.expected_income = false;
      } else {
        result = true;
      }

      if (this.formData.working_time_from === null) {
        result = false;
        this.error.working_time_from = false;
      } else {
        result = true;
      }

      if (this.formData.working_time_to === null) {
        result = false;
        this.error.working_time_to = false;
      } else {
        result = true;
      }

      if (this.formData.working_place === null) {
        result = false;
        this.error.working_place = false;
      } else {
        result = true;
      }

      if (this.formData.application_requirement === '') {
        result = false;
        this.error.application_requirement = false;
      } else {
        result = true;
      }

      if (this.formData.language_requirement.length === 0) {
        result = false;
        this.error.language_requirement = false;
      } else {
        result = true;
      }

      if (this.formData.form_of_employment === '') {
        result = false;
        this.error.form_of_employment = false;
      } else {
        result = true;
      }

      if (this.formData.bonus_pay === '') {
        result = false;
        this.error.bonus_pay = false;
      } else {
        result = true;
      }

      if (this.formData.overtime === '') {
        result = false;
        this.error.overtime = false;
      } else {
        result = true;
      }

      if (this.formData.passive_smoking === '') {
        result = false;
        this.error.passive_smoking = false;
      } else {
        result = true;
      }

      if (this.formData.transfer === '') {
        result = false;
        this.error.transfer = false;
      } else {
        result = true;
      }

      if (this.formData.other_skill === '') {
        result = false;
        this.error.other_skill = false;
      } else {
        result = true;
      }

      if (this.formData.vacation === '') {
        result = false;
        this.error.vacation = false;
      } else {
        result = true;
      }

      if (this.formData.treatment_welfare === '') {
        result = false;
        this.error.treatment_welfare = false;
      } else {
        result = true;
      }

      if (this.formData.country === null) {
        result = false;
        this.error.country = false;
      } else {
        result = true;
      }

      if (this.formData.working_place === null) {
        result = false;
        this.error.working_place = false;
      } else {
        result = true;
      }

      if (this.formData.interview_follow === null) {
        result = false;
        this.error.interview_follow = false;
      } else {
        result = true;
      }

      return result;
    },
    onSubmit() {
      if (this.handleValidationFormData()) {
        this.handleUpdateJob();
      } else {
        MakeToast({
          variant: 'warning',
          title: this.$t('WARNING'),
          content: this.$t('WARNING_MESS.REQUIRED_FIELD_NOT_INPUT'),
        });
      }
    },
    handleChangeForm(event, field) {
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
        case 'company_id':
          this.error.company_id = null;

          this.handleFillInCompanyInfo(event);

          if (newValue) {
            this.error.company_id = true;
          } else {
            this.error.company_id = false;
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
        case 'major_classification':
          // this.handleChangeMainJob(event);

          this.error.major_classification = null;

          if (newValue) {
            this.error.major_classification = true;
          } else {
            this.error.major_classification = false;
          }
          break;
        case 'middle_classification':
          this.error.middle_classification = null;

          if (newValue) {
            this.error.middle_classification = true;
          } else {
            this.error.middle_classification = false;
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
        case 'treatment_welfare':
          this.error.treatment_welfare = null;

          if (newValue) {
            this.error.treatment_welfare = true;
          } else {
            this.error.treatment_welfare = false;
          }
          break;
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
        case 'working_place':
          this.error.working_place = null;

          if (newValue) {
            this.error.working_place = true;
          } else {
            this.error.working_place = false;
          }
          break;
        case 'country':
          this.error.country = null;

          if (newValue) {
            this.error.country = true;
          } else {
            this.error.country = false;
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
    handleFillInCompanyInfo(company_id) {
      let DATA;

      if (company_id) {
        this.company_list.forEach((item) => {
          if (item['value'] === company_id) {
            DATA = item['data'];
          }
        });

        if (DATA) {
          this.formData.establishment_year = DATA['establishment_year'];
          this.formData.startup_year = DATA['startup_year'];
          this.formData.capital = DATA['capital'];
          this.formData.proceeds = DATA['proceeds'];
          this.formData.number_of_staffs = DATA['number_of_staffs'];
          this.formData.number_of_operations = DATA['number_of_operations'];
          this.formData.number_of_shops = DATA['number_of_shops'];
          this.formData.number_of_factories = DATA['number_of_factory'];
          this.formData.fiscal_year = DATA['fiscal'];
        }
      }
    },
    handleBack() {
      this.$router.push('/job/list');
    },
    convertRadioOption(type, option) {
      let result;

      if (type === 1) {
        switch (option) {
          case '有':
            result = 1;
            break;
          case '無':
            result = 2;
            break;
          default:
            break;
        }
      } else {
        switch (option) {
          case '正社員':
            result = 1;
            break;
          case '契約社員':
            result = 2;
            break;
          case '派遣社員':
            result = 3;
            break;
          case 'その他':
            result = 4;
            break;
          default:
            break;
        }
      }

      return result;
    },
    handleParseArray(array) {
      const RESULT = [];

      array.forEach((item) => {
        if (item === 'N1') {
          RESULT.push(1);
        } else if (item === 'N2') {
          RESULT.push(2);
        } else if (item === 'N3') {
          RESULT.push(3);
        } else if (item === 'N4') {
          RESULT.push(4);
        } else if (item === 'N5') {
          RESULT.push(5);
        } else if (item === '問わない') {
          RESULT.push(6);
        }
      });

      return RESULT;
    },
    handFormatPassions() {
      const RESULT = [];

      const DATA = [
        this.housing_allowance,
        this.welfare_enhancement,
        this.severance_pay,
        this.more_annual_holidays,
        this.residence,
        this.no_experience_ok,
        this.foreign_managerial_staff_hired,
        this.remote_work_available,
        this.moving_allowance,
      ];

      DATA.forEach((item, index) => {
        if (item) {
          RESULT.push(index + 1);
        }
      });

      return RESULT;
    },
    handleGetLanguageRequirement(array) {
      const result = [];

      if (array) {
        array.forEach((item) => {
          if (item['name'] === 'Other') {
            result.push('問わない');
          } else {
            result.push(item['name']);
          }
        });
      }

      return result;
    },
    handleGetFormOfEmployee(type) {
      let result = '';

      if (type) {
        switch (type) {
          case 1:
            result = '正社員';
            break;
          case 2:
            result = '契約社員';
            break;
          case 3:
            result = '派遣社員';
            break;
          case 4:
            result = 'その他';
            break;
          default:
            break;
        }
      }

      return result;
    },
    handleGetWorkingPlace(working_place_id) {
      let result;

      if (working_place_id) {
        this.city_list.find((timezone) => {
          timezone['options'].forEach((city) => {
            if (city['id'] === working_place_id) {
              result = city;
            }
          });
        });
      }

      return result;
    },
    handleTransformRadioOptions(option) {
      let result = '';

      switch (option) {
        case 1:
          result = '有';
          break;
        case 2:
          result = '無';
          break;
        default:
          break;
      }

      return result;
    },
    handleProcessSelectedPassions(array) {
      if (array) {
        array.forEach((item) => {
          const ID = item['id'];

          switch (ID) {
            case 1:
              this.housing_allowance = true;
              break;
            case 2:
              this.welfare_enhancement = true;
              break;
            case 3:
              this.severance_pay = true;
              break;
            case 4:
              this.more_annual_holidays = true;
              break;
            case 5:
              this.residence = true;
              break;
            case 6:
              this.no_experience_ok = true;
              break;
            case 7:
              this.foreign_managerial_staff_hired = true;
              break;
            case 8:
              this.remote_work_available = true;
              break;
            case 9:
              this.moving_allowance = true;
              break;
            default:
              break;
          }
        });
      }
    },

    async handlePauseJobOrContinue(status) {
      const params = {
        id: this.formData.id,
        status: status !== 2 ? 2 : 1,
      };
      try {
        await updateStatusWork(params).then((response) => {
          const { code, message } = response.data;
          if (code === 200) {
            MakeToast({
              variant: 'success',
              title: this.$t('SUCCESS'),
              content: message,
            });
            this.$router.push({ path: '/job/list' });
          } else {
            MakeToast({
              variant: 'warning',
              title: 'warning',
              content: message,
            });
          }
        });
      } catch (error) {
        console.log(error);
      }
    },
  },
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css" />
<style lang="scss" scoped>
@import '@/scss/_variables.scss';

.title {
  height: 36px;
  display: flex;
  line-height: 36px;
  align-items: center;
  padding: 0px !important;
  justify-content: flex-start;

  & > .title-bar {
    width: 5px;
    height: 30px;
    border-radius: 6px;
    background-color: #314cad;
  }

  & > .title-text {
    font-size: 24px;
    font-weight: 400;
    margin-left: 15px;
  }
}

.bg-gray {
  background-color: #f8f8f8;
}

.p-0 {
  padding: 0 !important;
}

.badge-required {
  color: red;
  width: 28px;
  height: 13px;
  display: flex;
  font-size: 8px;
  align-items: center;
  border: 1px solid red;
  justify-content: center;
}

.badge-not-required {
  color: #999;
  width: 28px;
  height: 13px;
  display: flex;
  font-size: 8px;
  align-items: center;
  border: 1px solid #999;
  background-color: #fff;
  justify-content: center;
}

textarea.form-control {
  flex: none;
  display: flex;
}

.form-item-row__inputs {
  display: flex;
  align-items: center;
  justify-content: center;

  & > span {
    width: 20%;
    min-width: 140px;
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

.control-area {
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: center;

  .back-to-list-button {
    width: 120px;
    height: 36px;
    border: none;
    display: flex;
    outline: none;
    color: #ffffff;
    font-size: 16px;
    font-weight: 400;
    margin-right: 10px;
    align-items: center;
    border-radius: 45px;
    justify-content: center;
    background-color: #8f8f8f;

    &:hover {
      opacity: 0.6;
    }

    &:focus,
    &:active {
      border: none !important;
      outline: none !important;
      box-shadow: none !important;
    }
  }

  .save-button {
    width: 80px;
    height: 36px;
    border: none;
    display: flex;
    outline: none;
    color: #ffffff;
    font-size: 16px;
    font-weight: 400;
    align-items: center;
    border-radius: 45px;
    justify-content: center;
    background-color: #f9be00;

    &:hover {
      opacity: 0.6;
    }

    &:focus,
    &:active {
      border: none !important;
      outline: none !important;
      box-shadow: none !important;
    }
  }
}

.card-header {
  font-size: 18px;
  font-weight: 900;
  background-color: #e9e9e9 !important;
}

.list-group-body {
  margin: 10px 15px 10px 15px;
}

.list-group-item {
  min-height: 40px;
}

.body-item-title {
  font-size: 16px;
  min-height: 40px;
  color: #212529;
}

::v-deep .card {
  border-radius: 0px !important;
}

::v-deep .card-body {
  padding: 0px !important;
}

.w-90 {
  width: 90%;
}

.w-10 {
  width: 10%;
  padding-right: 35px;
}

::v-deep .custom-select {
  color: #212529 !important;
}

::v-deep .form-control {
  color: #212529 !important;
}

::v-deep .b-form-btn-label-control.form-control > .btn {
  right: 0px;
  position: absolute;
  color: #1d266a !important;
}

::v-deep .b-form-btn-label-control.form-control > .form-control {
  padding-left: 0.75rem !important;
}

::v-deep .btn-actived {
  color: #ffffff !important;
  background-color: #1d266a !important;

  &:hover {
    opacity: 0.6;
  }
}

::v-deep .btn-deactivated {
  background-color: #eeeeee !important;

  &:hover {
    opacity: 0.6;
  }
}

::v-deep .btn.focus {
  outline: none !important;
  box-shadow: none !important;
}

::v-deep
  .multiselect
  > .multiselect__content-wrapper
  > .multiselect__content
  > .multiselect__element
  > .multiselect__option--highlight {
  color: #ffffff;
  background-color: #072470;

  &::after {
    background-color: #072470;
  }
}

::v-deep
  .multiselect
  > .multiselect__content-wrapper
  > .multiselect__content
  > .multiselect__element
  > .multiselect__option--selected.multiselect__option--highlight {
  color: #ffffff;
  background-color: #ff6a6a;

  &::after {
    background-color: #ff6a6a;
  }
}

::v-deep
  .multiselect
  > .multiselect__content-wrapper
  > .multiselect__content
  > .multiselect__element
  > .multiselect__option--highlight
  > .option-label {
  color: #ffffff;
}

::v-deep
  .multiselect
  > .multiselect__tags
  > .multiselect__tags-wrap
  > .multiselect__tag {
  background-color: #072470 !important;

  & > .multiselect__tag-icon::after {
    color: #ffffff !important;
  }

  & > .multiselect__tag-icon:focus,
  .multiselect__tag-icon:hover {
    background-color: #ff6a6a !important;
  }
}

::v-deep .multiselect__option--group {
  font-size: 16px !important;
  color: #000000 !important;
  background-color: #ffffff !important;
}

::v-deep .option-label {
  font-size: 14px;
  margin-left: 10px;
  color: #333333;
}

::v-deep .multiselect {
  color: #212529 !important;
}

::v-deep .multiselect__tags {
  border: 1px solid #ced4da !important;
}

::v-deep .multiselect__placeholder {
  font-size: 1rem !important;
  font-weight: 400 !important;
  line-height: 1.5 !important;
  color: #212529 !important;
}

::v-deep .is-invalid {
  border: 1px solid #dc3545 !important;
}
</style>
