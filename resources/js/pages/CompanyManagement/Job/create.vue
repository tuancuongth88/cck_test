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
            <span class="title-text">{{ $t('JOB_REGISTRATION') }} </span>
          </b-col>
        </b-col>
      </b-row>
      <b-row class="mb-4">
        <b-col cols="12">
          <b-form>
            <b-card-group deck>
              <b-card no-body :header="$t('TITLE.JOB_INFORMATION')">
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
                          dusk="company_id"
                          :class="
                            error.company_id === false ? ' is-invalid' : ''
                          "
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
                            dusk="application_period_start"
                            locale="ja"
                            label-help=""
                            offset="-278px"
                            :min="min_application_date_from"
                            :placeholder="$t('PLEASE_SELECT')"
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
                            dusk="application_period_end"
                            locale="ja"
                            label-help=""
                            offset="-278px"
                            :min="
                              handleSetMinApplicationDateTo(
                                formData.application_period_start
                              )
                            "
                            :placeholder="$t('PLEASE_SELECT')"
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
                          <span>{{
                            $t('COMPANY_REGISTER.MAJOR_CLASSIFICATION')
                          }}</span>

                          <div
                            class="d-flex w-100 flex-column justify-center align-center"
                          >
                            <b-form-select
                              v-model="formData.major_classification"
                              dusk="major_classification"
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
                        <!-- 3.2 option 中分類 middle classification -->
                        <div
                          class="form-item-row__inputs d-flex w-100 justify-center align-center mt-2"
                        >
                          <span>{{
                            $t('COMPANY_REGISTER.MIDDLE_CLASSIFICATION')
                          }}</span>

                          <div
                            class="d-flex w-100 flex-column justify-center align-center"
                          >
                            <b-form-select
                              v-model="formData.middle_classification"
                              dusk="middle_classification"
                              :options="list_middle_classification"
                              value-field="value"
                              text-field="text"
                              :enabled="formData.major_classification"
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
                          :maxlength="2000"
                          :rows="5"
                          :max-rows="20"
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
                        <span>{{ $t('JOB_EDIT.APPLICATION_CONDITION') }}</span>
                        <Require />
                      </b-col>

                      <b-col
                        cols="9"
                        class="d-flex align-items-center flex-wrap my-2"
                      >
                        <b-form-textarea
                          v-model="formData.application_condition"
                          dusk="application_condition"
                          :maxlength="2000"
                          :rows="5"
                          :max-rows="20"
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
                        <span>{{ $t('JOB_EDIT.APPLICATION_REQUIREMENT') }}</span>
                        <Require />
                      </b-col>

                      <b-col
                        cols="9"
                        class="d-flex align-items-center flex-wrap my-2"
                      >
                        <b-form-textarea
                          v-model="formData.application_requirement"
                          dusk="application_requirement"
                          :maxlength="2000"
                          :rows="5"
                          :max-rows="20"
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
                        class="d-flex flex-row align-items-center bg-gray body-item-title justify-content-between"
                      >
                        <span>{{ $t('JOB_EDIT.COUNTRY') }}</span>
                        <Require />
                      </b-col>

                      <b-col cols="9" class="my-2">
                        <b-form-select
                          v-model="formData.country"
                          dusk="country"
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
                        <span>{{ $t('JOB_EDIT.REQUIRED_LANGUAGE_CONDITION') }}</span>
                        <Require />
                      </b-col>

                      <b-col
                        cols="9"
                        class="d-flex align-items-center flex-wrap my-2"
                      >
                        <b-form-checkbox-group
                          id="language_requirement"
                          v-model="formData.language_requirement"
                          name="language_requirement"
                          dusk="language_requirement"
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
                          :maxlength="2000"
                          :rows="5"
                          :max-rows="20"
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
                          :maxlength="2000"
                          :rows="5"
                          :max-rows="20"
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
                          id="form_of_employment"
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
                          placeholder="選択してください"
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
                          :rows="5"
                          :max-rows="20"
                          :maxlength="2000"
                          :class="
                            error.working_place_detail === false
                              ? ' is-invalid'
                              : ''
                          "
                          :placeholder="$t('VALIDATE.REQUIRED_TEXT')"
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
                          :rows="5"
                          :max-rows="20"
                          :maxlength="2000"
                          dusk="treatment_welfare"
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
                          :maxlength="2000"
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
                          id="bonus_pay"
                          v-model="formData.bonus_pay"
                          dusk="bonus_pay"
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
                          id="overtime"
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
                          id="transfer"
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
                          id="passive_smoking"
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
                          id="housing_allowance"
                          v-model="housing_allowance"
                          dusk="housing_allowance"
                          button
                          size="sm"
                          class="mx-2 my-1"
                          name="check-button"
                          :button-variant="
                            housing_allowance ? 'actived' : 'deactivated'
                          "
                        >
                          <span>{{ $t('HOUSING_ALLOWANCE_AVAILABLE') }}</span>
                        </b-form-checkbox>

                        <b-form-checkbox
                          id="welfare_enhancement"
                          v-model="welfare_enhancement"
                          dusk="welfare_enhancement"
                          button
                          size="sm"
                          class="mx-2 my-1"
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
                          id="severance_pay"
                          v-model="severance_pay"
                          dusk="severance_pay"
                          button
                          size="sm"
                          class="mx-2 my-1"
                          name="check-button"
                          :button-variant="
                            severance_pay ? 'actived' : 'deactivated'
                          "
                        >
                          <span>{{
                            $t('JOB_EDIT.PASSION_CHILD_SEVERANCE_PAY')
                          }}</span>
                        </b-form-checkbox>

                        <b-form-checkbox
                          id="more_annual_holidays"
                          v-model="more_annual_holidays"
                          dusk="more_annual_holidays"
                          button
                          size="sm"
                          class="mx-2 my-1"
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
                          id="residence"
                          v-model="residence"
                          dusk="residence"
                          button
                          size="sm"
                          class="mx-2 my-1"
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
                          id="no_experience_ok"
                          v-model="no_experience_ok"
                          dusk="no_experience_ok"
                          button
                          size="sm"
                          class="mx-2 my-1"
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
                          id="foreign_managerial_staff_hired"
                          v-model="foreign_managerial_staff_hired"
                          dusk="foreign_managerial_staff_hired"
                          button
                          size="sm"
                          class="mx-2 my-1"
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
                          id="remote_work_available"
                          v-model="remote_work_available"
                          dusk="remote_work_available"
                          button
                          size="sm"
                          class="mx-2 my-1"
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
                          id="moving_allowance"
                          v-model="moving_allowance"
                          dusk="moving_allowance"
                          button
                          size="sm"
                          class="mx-2 my-1"
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
                        class="d-flex flex-row align-items-center bg-gray body-item-title justify-content-between"
                      >
                        <span>{{ $t('JOB_EDIT.INTERVIEW_FOLLOW') }}</span>
                        <Require />
                      </b-col>

                      <b-col cols="9" class="my-2">
                        <b-form-select
                          id="interview_follow"
                          v-model="formData.interview_follow"
                          dusk="interview_follow"
                          text-field="text"
                          name="some-radios"
                          value-field="value"
                          disabled-field="disabled"
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
                        class="d-flex flex-row align-items-center bg-gray body-item-titl justify-content-between"
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
      <div class="control-area">
        <b-button
          class="back-to-list-button btn_back--custom mx-1"
          @click="handleToggleConfirmLeavingModal"
        >
          {{ $t('BUTTON.CANCEL') }}
        </b-button>

        <b-button
          type="submit"
          class="save-button text-white mx-1 btn_save--custom"
          @click="onSubmit()"
        >
          {{ $t('BUTTON.SAVE') }}
        </b-button>
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
            class="w-100 d-flex justify-content-end align-center"
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
  </b-overlay>
</template>

<script>
import Multiselect from 'vue-multiselect';

import { countryOptions } from '@/const/job';
import { postOneJob } from '@/api/modules/job';
import { getListCompany } from '@/api/modules/company';
import { MakeToast } from '../../../utils/toastMessage';
import { getClassificationListOccupation } from '@/api/modules/hr';
import Require from '@/components/Require/Require.vue';
import Arbitrarily from '@/components/Arbitrarily/Arbitrarily.vue';

const urlAPI = {
  urlPostOneJob: '/work',
  urlGetJobList: '/job-type',
  urlGetListCompany: '/company-option',
};
const requiredFields = [
  'job_title',
  'company_id',
  'application_period_start',
  'application_period_end',
  'major_classification',
  'middle_classification',
  'job_description',
  'application_condition',
  'working_time',
  'expected_income',
  'working_time_from',
  'working_time_to',
  'working_place',
  'application_requirement',
  'length',
  'form_of_employment',
  'bonus_pay',
  'overtime',
  'passive_smoking',
  'transfer',
  'other_skill',
  'vacation',
  'treatment_welfare',
  'country',
  'interview_follow',
];
export default {
  name: 'JobCreate',
  components: {
    Multiselect,
    Require,
    Arbitrarily,
  },
  data() {
    return {
      statusModalConfirmLeaving: false,
      housing_allowance: false,
      welfare_enhancement: false,
      severance_pay: false,
      more_annual_holidays: false,
      residence: false,
      no_experience_ok: false,
      foreign_managerial_staff_hired: false,
      remote_work_available: false,
      moving_allowance: false,

      overlay: {
        opacity: 1,
        show: false,
        blur: '1rem',
        rounded: 'sm',
        variant: 'light',
      },

      formData: {
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

        country: null,

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

      min_application_date_from: this.handleSetMinApplicationDateFrom(),
      min_application_date_to: '',
    };
  },
  computed: {
    role() {
      return this.$store.getters.profile['type'];
    },
  },
  created() {
    this.handleInitComponentData();
  },
  methods: {
    async handleInitComponentData() {
      await this.handleSetMinApplicationDateFrom();

      if (this.role && [1, 2].includes(this.role)) {
        await this.handleGetListCompany();
      } else if (this.role === 4) {
        this.handleGetCompanyInfo();
      }

      await this.handleGetListJobOccupation();
    },
    handleSetMinApplicationDateFrom() {
      const today = new Date();
      const year = today.getFullYear().toString();
      const month = (today.getMonth() + 1).toString().padStart(2, '0');
      const day = today.getDate().toString().padStart(2, '0');
      const currentDate = `${year}-${month}-${day}`;

      return currentDate;
    },
    handleSetMinApplicationDateTo(application_period_from) {
      const date = new Date(application_period_from);
      date.setDate(date.getDate() + 1);
      const year = date.getFullYear();
      const month = (date.getMonth() + 1).toString().padStart(2, '0');
      const day = date.getDate().toString().padStart(2, '0');

      return `${year}-${month}-${day}`;
    },
    async handleGetListCompany() {
      try {
        this.company_list = [
          { value: null, text: '選択してください', disabled: true },
        ];

        const URL = `${urlAPI.urlGetListCompany}`;

        const response = await getListCompany(URL);

        if (response['code'] === 200) {
          // const DATA = response['data']['result'];
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

    handleConfirmStilConfirmLeaving() {
      this.$router.push({ path: `/job/list` }, (onAbort) => {});
    },
    handleGetCompanyInfo() {
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
      // this.list_middle_classification = [
      //   { value: null, text: '選択してください', disabled: true },
      // ];
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
    async handleCreateJob() {
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

      const URL = `${urlAPI.urlPostOneJob}`;

      this.overlay.show = true;

      try {
        const response = await postOneJob(URL, DATA);

        if (response['code'] === 200) {
          MakeToast({
            variant: 'success',
            title: this.$t('SUCCESS'),
            content: '作成が成功しました。',
          });

          this.$router.push({ path: '/job/list' });
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
          content: error.message,
        });
      }

      this.overlay.show = false;
    },

    handleValidationFormData() {
      for (const x of requiredFields) {
        this.error[x] = !(this.formData[x] === null || this.formData[x] === '');
      }
      this.error['language_requirement'] = !(
        this.formData.language_requirement.length === 0
      );

      const errors = Object.keys(this.error).filter((x) => {
        return this.error[x] === false && requiredFields.includes(x);
      });

      return !errors.length;
    },

    onSubmit() {
      if (this.handleValidationFormData()) {
        this.handleCreateJob();
      } else {
        MakeToast({
          variant: 'warning',
          title: this.$t('WARNING'),
          content: this.$t('WARNING_MESS.REQUIRED_FIELD_NOT_INPUT'),
        });
      }
    },

    handleToggleConfirmLeavingModal() {
      if (this.statusModalConfirmLeaving === true) {
        this.statusModalConfirmLeaving = false;
      } else {
        this.statusModalConfirmLeaving = true;
      }
    },
    handleChangeForm(event, field) {
      const newValue = event;
      if (field === 'company_id') {
        this.handleFillInCompanyInfo(event);
      }
      // Remove error for required field
      if (requiredFields.includes(field) || field === 'language_requirement') {
        this.error[field] = !!newValue;
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
    width: 230px;
    height: 40px;
    border: none;
    display: flex;
    outline: none;
    color: #ffffff;
    font-size: 20px;
    font-weight: 400;
    margin-right: 10px;
    align-items: center;
    border-radius: 45px;
    justify-content: center;
    background-color: #c1c1c1;
    margin-top: 2rem;
    margin-bottom: 2rem;

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
    width: 230px;
    height: 40px;
    border: none;
    display: flex;
    outline: none;
    color: #ffffff;
    font-size: 20px;
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

.body-item-title {
  font-size: 16px;
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
</style>
