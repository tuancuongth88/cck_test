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

    <div class="hr-detail">
      <div class="hr-basic-information">
        <div class="hr-basic-information-wrap">
          <div class="hr-basic-information-avata">
            <img
              :src="
                require('@/assets/images/login/hr-basic-info-avata-default.png')
              "
              alt="avata default"
            >
          </div>

          <div class="hr-basic-information-general">
            <div class="infor-first">
              <span>{{ full_name }}</span>
              <span>{{ full_name_furigana }}</span>
              <span>{{ `(ID : ${HR_ID})` }}</span>
            </div>

            <div class="infor-company">
              <span>{{ company_name_en }}</span>
              <span>{{ company_name_jp }}</span>
            </div>

            <div class="information-general-btns">
              <!-- <div :class="`btn ${cv_status === 'public' ? 'public-active' : 'public-deactive'}`" @click="handlePublicCV()"> -->
              <div
                :class="`btn ${
                  hr_status === 1
                    ? 'public-active'
                    : `${hr_status === 3 ? 'disable-btn' : 'public-deactive'}`
                }`"
                dusk="hr_public"
                @click="handlePublicCV()"
              >
                <span>{{ $t('HR_LIST.STATUS_PUBLIC') }}</span>
              </div>

              <!-- <div :class="`btn ${cv_status === 'private' ? 'private-active' : 'private-deactive'}`" @click="handlePrivateCV()"> -->
              <div
                :class="`btn ${
                  hr_status === 2
                    ? 'private-active'
                    : `${hr_status === 3 ? 'disable-btn' : 'private-deactive'}`
                }`"
                dusk="hr_private"
                @click="handlePrivateCV()"
              >
                <span>{{ $t('HR_LIST.STATUS_PRIVATE') }}</span>
              </div>

              <div
                :class="`btn ${
                  hr_status === 3 ? 'offer-active' : 'offer-disable'
                }`"
                dusk="hr_offer_confirm"
              >
                <span>{{ $t('HR_LIST.STATUS_OFFICIAL_OFFER_CONFIRM') }}</span>
              </div>
            </div>
            <b-overlay
              :show="overlay_upload.show"
              :variant="overlay_upload.variant"
              :opacity="overlay_upload.opacity"
              :blur="overlay_upload.blur"
              :rounded="overlay_upload.sm"
            >
              <template #overlay>
                <div class="text-center">
                  <b-icon
                    icon="arrow-clockwise"
                    font-scale="3"
                    animation="spin"
                  />
                  <!-- <p style="margin-top: 10px">{{ $t('PLEASE_WAIT') }}</p> -->
                </div>
              </template>
              <div class="information-general-select-file">
                <!-- 履歴書 -->
                <div class="d-flex flex-row align-items-center">
                  <span class="mr-2 label-file-cv">{{ $t('RESUME') }}</span>
                  <Require />
                </div>

                <!-- <b-link class="custom-b-link" :href="handleFileLink(cv_file_path_from_response)" target="_blank">{{ cv_file_name_from_response }}</b-link> -->

                <div
                  class="w-100 d-flex flex-row align-items-center justify-content-between"
                >
                  <b-button
                    v-if="!cv_file"
                    :class="
                      error.cv_file === false
                        ? 'file-btn is-invalid-button'
                        : 'file-btn'
                    "
                    :style="{
                      border: '1px solid #aaa !important',
                      borderColor:
                        error.cv_file === false ? 'red !important' : '',
                    }"
                    @click="handleOpenFileSelect(1)"
                  >
                    <span class="file-btn-text">{{
                      $t('HR_REGISTER.SELECT_FILE')
                    }}</span>
                  </b-button>

                  <input
                    ref="fileInputCV"
                    dusk="file_cv"
                    type="file"
                    style="display: none"
                    @change="handleFileSelect(1, $event)"
                  >

                  <span
                    :class="[
                      cv_file ? 'file-name-active' : 'ml-2 file-name',
                      'file-content',
                    ]"
                    @click="handlePreview(1)"
                  >{{ cv_file_name }}</span>

                  <i
                    v-if="cv_file"
                    class="far fa-file-minus"
                    @click="handleRemoveFile(1)"
                  />
                </div>

                <div
                  class="w-100 d-flex flex-row align-items-center justify-content-between"
                >
                  <b-form-invalid-feedback :state="error.cv_file">
                    <span
                      class="display-requied-text"
                      style="font-size: 10px"
                    >{{ $t('VALIDATE.REQUIRED_SELECT') }}</span>
                  </b-form-invalid-feedback>
                </div>
                <!-- 職務経歴書 -->
                <div class="d-flex flex-row align-items-center mt-3">
                  <span class="mr-2">{{ $t('CV') }}</span>
                  <Require />
                </div>

                <!-- <b-link class="custom-b-link" :href="handleFileLink(jd_file_path_from_response)" target="_blank">{{ jd_file_name_from_response }}</b-link> -->

                <div
                  class="w-100 d-flex flex-row align-items-center justify-content-between"
                >
                  <b-button
                    v-if="!jd_file"
                    :class="
                      error.jd_file === false
                        ? 'file-btn is-invalid-button'
                        : 'file-btn'
                    "
                    :style="{
                      border: '1px solid #aaa !important',
                      borderColor:
                        error.jd_file === false ? 'red !important' : '',
                    }"
                    @click="handleOpenFileSelect(2)"
                  >
                    <span class="file-btn-text">{{
                      $t('HR_REGISTER.SELECT_FILE')
                    }}</span>
                  </b-button>

                  <input
                    ref="fileInputJD"
                    dusk="file_JD"
                    type="file"
                    style="display: none"
                    @change="handleFileSelect(2, $event)"
                  >

                  <span
                    :class="[
                      jd_file ? 'file-name-active' : 'ml-2 file-name',
                      'file-content',
                    ]"
                    @click="handlePreview(2)"
                  >{{ jd_file_name }}</span>

                  <i
                    v-if="jd_file"
                    class="far fa-file-minus"
                    @click="handleRemoveFile(2)"
                  />
                </div>
                <div
                  class="w-100 d-flex flex-row align-items-center justify-content-between"
                >
                  <b-form-invalid-feedback :state="error.jd_file">
                    <span
                      class="display-requied-text"
                      style="font-size: 10px"
                    >{{ $t('VALIDATE.REQUIRED_SELECT') }}</span>
                  </b-form-invalid-feedback>
                </div>

                <!-- その他書類 -->
                <div class="w-100 d-flex flex-row align-items-center mt-3">
                  <span class="mr-2">{{ $t('OTHER_DOCUMENT') }}</span>
                  <Arbitrarily />
                </div>
                <div
                  class="w-100 d-flex flex-row align-items-center mt-1 button-add-more-file"
                  @click="handleAddOtherFile()"
                >
                  <span style="color: #8e8e8e" class="file-name">{{
                    $t('ADD_FILE')
                  }}</span>
                  <i class="fas fa-plus-circle ml-2" />
                </div>

                <!-- <div
                  class="d-flex flex-row align-items-center justify-content-between"
                >
                  <b-button
                    v-if="!default_other_file"
                    class="file-btn"
                    @click="handleOpenFileSelect(3)"
                  >
                    <span class="file-btn-text">{{
                  $t('HR_REGISTER.SELECT_FILE')
                }}</span>
                  </b-button>

                  <input
                    :ref="`fileInputDefaultOtherFile`"
                    type="file"
                    style="display: none"
                    @change="handleFileSelect(3, $event)"
                  >

                  <span
                    :class="[
                      default_other_file_id
                        ? 'file-name-active'
                        : 'ml-2 file-name',
                      'file-content',
                    ]"
                    @click="handlePreview(3)"
                  >{{ default_other_file_name }}</span>

                  <i
                    v-if="default_other_file_id"
                    class="far fa-file-minus"
                    @click="handleRemoveFile(3)"
                  />
                </div> -->
                <!-- Show all other file -->
                <div
                  v-for="(item, index) in other_files"
                  :key="index"
                  class="d-flex flex-row align-items-center justify-content-between"
                >
                  <b-button
                    v-if="!item['file'] && !item['file_id']"
                    class="file-btn"
                    :style="{
                      border: '1px solid #aaa !important',
                      borderColor:
                        item['file'] && item['file_id'] ? 'red !important' : '',
                    }"
                    @click="handleOpenFileSelect(4, index)"
                  >
                    <span class="file-btn-text">{{
                      $t('HR_REGISTER.SELECT_FILE')
                    }}</span>
                  </b-button>

                  <input
                    :ref="`fileInputOtherFile${index}`"
                    type="file"
                    style="display: none"
                    @change="handleFileSelect(4, $event, index)"
                  >

                  <span
                    :class="[
                      item['file_id'] ? 'file-name-active' : 'ml-2 file-name',
                      'file-content',
                    ]"
                    @click="handlePreview(4, index)"
                  >{{ item['system_file_name'] }}
                  </span>

                  <!-- <div v-if="item['file']" class="d-flex flex-row justify-content-between align-items-center"> -->
                  <!-- <div v-if="item['file_id']" class="d-flex flex-row justify-content-between align-items-center">
                  <i class="far fa-file-minus" @click="handleRemoveFile(4, index)" />
                  <i class="far fa-trash" @click="handleDeleteFileOther(index)" />
                </div> -->
                  <i
                    v-if="item['file_id']"
                    class="far fa-file-minus"
                    @click="handleDeleteFileOther(index)"
                  />
                </div>
              </div>
            </b-overlay>
          </div>
        </div>
      </div>

      <div class="hr-detail-information">
        <div class="hr-detail-information_wrap">
          <div class="hr-detail_information-head">
            <div class="hr-basic-head-title">
              <div class="line" />

              <span
                class="hr-edit-title"
              >{{ $t('HR_LIST_FORM.EDIT_FORM_TITLE') }}
              </span>
            </div>

            <div class="hr-detail-head-btns">
              <button
                class="btn btn_back--custom"
                dusk="btn_cancel"
                @click="handleCancelHrEdit"
              >
                <span>{{ $t('BUTTON.CANCEL') }}</span>
              </button>

              <button
                class="btn btn_save--custom"
                dusk="btn_save"
                @click="handleSaveHr()"
              >
                <span>{{ $t('BUTTON.SAVE') }}</span>
              </button>
            </div>
          </div>

          <div class="hr-detail_information-tabs">
            <b-card no-body>
              <b-tabs card>
                <b-tab
                  title="基本情報"
                  active
                  :title-link-class="isPassFirstTabValidation ? '' : 'error'"
                >
                  <b-card-text>
                    <div class="hr-content-tab hr-tab-basic-infomation">
                      <div class="hr-content-tab-wrap">
                        <div
                          class="hr-content-tab-item border-t border-l border-r"
                        >
                          <div
                            id="hr-type-edit"
                            class="hr-content-tab-item__title d-flex justify-content-between"
                          >
                            <span>{{ $t('HR_REGISTER.FULL_NAME') }}</span>
                            <Require />
                          </div>

                          <div
                            :class="[
                              'hr-content-tab__data d-flex flex-column',
                              error.full_name ? '' : 'mt-3',
                            ]"
                          >
                            <b-form-input
                              v-model="full_name"
                              type="text"
                              :class="
                                error.full_name === false ? ' is-invalid' : ''
                              "
                              dusk="full_name"
                              @input="
                                handleReAssignFeedback('full_name', $event)
                              "
                            />

                            <b-form-invalid-feedback :state="error.full_name">
                              <span>{{ $t('VALIDATE.REQUIRED_TEXT') }}</span>
                            </b-form-invalid-feedback>
                          </div>
                        </div>

                        <div
                          class="hr-content-tab-item border-t border-l border-r"
                        >
                          <div
                            id="hr-type-edit"
                            class="hr-content-tab-item__title d-flex justify-content-between"
                          >
                            <span>{{
                              $t('HR_REGISTER.FULL_NAMEFURIGANA')
                            }}</span>
                            <Require />
                          </div>

                          <div
                            :class="[
                              'hr-content-tab__data d-flex flex-column',
                              error.full_name_furigana ? '' : 'mt-3',
                            ]"
                          >
                            <b-form-input
                              v-model="full_name_furigana"
                              :class="
                                error.full_name_furigana === false
                                  ? ' is-invalid'
                                  : ''
                              "
                              dusk="full_name_furigana"
                              @input="
                                handleReAssignFeedback(
                                  'full_name_furigana',
                                  $event
                                )
                              "
                            />

                            <b-form-invalid-feedback
                              :state="error.full_name_furigana"
                            >
                              <span>{{ $t('VALIDATE.REQUIRED_TEXT') }}</span>
                            </b-form-invalid-feedback>
                          </div>
                        </div>

                        <div
                          class="hr-content-tab-item border-t border-l border-r"
                        >
                          <div
                            id="hr-type-edit"
                            class="hr-content-tab-item__title d-flex justify-content-between"
                          >
                            <span>{{ $t('HR_REGISTER.GENDER') }}</span>
                            <Arbitrarily />
                          </div>

                          <div class="hr-content-tab__data">
                            <b-form-select
                              v-model="gender"
                              :options="gender_option"
                              :disabled-field="'disabled'"
                            />
                          </div>
                        </div>

                        <div
                          class="hr-content-tab-item border-t border-l border-r"
                        >
                          <div
                            id="hr-type-edit"
                            class="hr-content-tab-item__title d-flex justify-content-between"
                          >
                            <span>{{ $t('HR_REGISTER.DATE_OF_BIRTH_') }}</span>
                            <Require />
                          </div>

                          <div class="hr-content-tab__data">
                            <div
                              :class="[
                                'd-flex justify-space-between flex-column',
                                error.date_of_birth_year ? '' : 'mt-3',
                              ]"
                              style="
                                width: 160px;
                                gap: 0.5rem;
                                align-items: center;
                              "
                            >
                              <div
                                class="d-flex flex-row w-100 align-items-center"
                              >
                                <b-form-select
                                  v-model="date_of_birth_year"
                                  dusk="date_of_birth_year"
                                  :options="date_of_birth_year_option"
                                  :class="
                                    error.date_of_birth_year === false
                                      ? ' is-invalid'
                                      : ''
                                  "
                                  @change="
                                    handleRenderDayInMonth(
                                      date_of_birth_year,
                                      date_of_birth_month
                                    ),
                                    handleReAssignFeedback(
                                      'date_of_birth_year',
                                      $event
                                    )
                                  "
                                />

                                <span class="d-flex ml-3">年</span>
                              </div>

                              <b-form-invalid-feedback
                                :state="error.date_of_birth_year"
                              >
                                <span>{{ $t('VALIDATE.REQUIRED_TEXT') }}</span>
                              </b-form-invalid-feedback>
                            </div>

                            <div
                              :class="[
                                'd-flex justify-space-between flex-column ml-4',
                                error.date_of_birth_month ? '' : 'mt-3',
                              ]"
                              style="
                                width: 160px;
                                gap: 0.5rem;
                                align-items: center;
                              "
                            >
                              <div
                                class="d-flex flex-row w-100 align-items-center"
                              >
                                <b-form-select
                                  v-model="date_of_birth_month"
                                  dusk="date_of_birth_month"
                                  size="10"
                                  :options="date_of_birth_month_option"
                                  :disabled="
                                    date_of_birth_year === '' ? true : false
                                  "
                                  :class="
                                    error.date_of_birth_month === false
                                      ? ' is-invalid'
                                      : ''
                                  "
                                  @change="
                                    handleRenderDayInMonth(
                                      date_of_birth_year,
                                      date_of_birth_month
                                    ),
                                    handleReAssignFeedback(
                                      'date_of_birth_month',
                                      $event
                                    )
                                  "
                                />

                                <span class="d-flex ml-3">月</span>
                              </div>

                              <b-form-invalid-feedback
                                :state="error.date_of_birth_month"
                              >
                                <span>{{ $t('VALIDATE.REQUIRED_TEXT') }}</span>
                              </b-form-invalid-feedback>
                            </div>

                            <div
                              :class="[
                                'd-flex justify-space-between flex-column ml-4',
                                error.date_of_birth_day ? '' : 'mt-3',
                              ]"
                              style="
                                width: 160px;
                                gap: 0.5rem;
                                align-items: center;
                              "
                            >
                              <div
                                class="d-flex flex-row w-100 align-items-center"
                              >
                                <b-form-select
                                  v-model="date_of_birth_day"
                                  dusk="date_of_birth_day"
                                  :options="date_of_birth_day_option"
                                  :disabled="
                                    !date_of_birth_month ? true : false
                                  "
                                  :class="
                                    error.date_of_birth_day === false
                                      ? ' is-invalid'
                                      : ''
                                  "
                                  @change="
                                    handleReAssignFeedback(
                                      'date_of_birth_day',
                                      $event
                                    )
                                  "
                                />

                                <span class="d-flex ml-3">日</span>
                              </div>

                              <b-form-invalid-feedback
                                :state="error.date_of_birth_day"
                              >
                                <span>{{ $t('VALIDATE.REQUIRED_TEXT') }}</span>
                              </b-form-invalid-feedback>
                            </div>
                          </div>
                        </div>

                        <div
                          class="hr-content-tab-item border-t border-l border-r"
                        >
                          <div
                            id="hr-type-edit"
                            class="hr-content-tab-item__title d-flex justify-content-between"
                          >
                            <span>{{ $t('HR_REGISTER.WORK_FORM') }}</span>
                            <Arbitrarily />
                          </div>

                          <div class="hr-content-tab__data" dusk="work_form">
                            <b-form-select
                              v-model="work_form"
                              :options="work_form_option"
                              :disabled-field="'disabled'"
                            />
                          </div>
                        </div>

                        <div
                          class="hr-content-tab-item border-t border-l border-r"
                        >
                          <div
                            id="hr-type-edit"
                            class="hr-content-tab-item__title d-flex justify-content-between"
                          >
                            <span>{{
                              $t('HR_REGISTER.WORK_FORM_PARTTIME')
                            }}</span>
                            <Arbitrarily />
                          </div>

                          <div class="hr-content-tab__data">
                            <div
                              class="d-flex justify-space-between"
                              style="align-items: center"
                            >
                              <span class="pr-4">週</span>
                              <b-form-input
                                v-model="work_form_part_time"
                                dusk="work_form_part_time"
                                :disabled="
                                  work_form === 1 || work_form === null
                                "
                              />
                              <span
                                class="pl-2"
                                style="min-width: 8%"
                              >時間</span>
                            </div>
                          </div>
                        </div>

                        <div
                          class="hr-content-tab-item border-t border-l border-r border-b"
                        >
                          <div
                            id="hr-type-edit"
                            class="hr-content-tab-item__title d-flex justify-content-between"
                          >
                            <span>{{ $t('HR_REGISTER.JAPANESE_LEVEL') }}</span>
                            <Require />
                          </div>

                          <div
                            :class="[
                              'hr-content-tab__data d-flex flex-column',
                              error.japanese_level ? '' : 'mt-3',
                            ]"
                          >
                            <b-form-select
                              v-model="japanese_level"
                              dusk="japanese_level"
                              :options="japanese_level_options"
                              :disabled-field="'disabled'"
                              :class="
                                error.japanese_level === false
                                  ? ' is-invalid'
                                  : ''
                              "
                              @change="
                                handleReAssignFeedback('japanese_level', $event)
                              "
                            />

                            <b-form-invalid-feedback
                              :state="error.japanese_level"
                            >
                              <span>{{ $t('VALIDATE.REQUIRED_TEXT') }}</span>
                            </b-form-invalid-feedback>
                          </div>
                        </div>
                      </div>
                    </div>
                  </b-card-text>
                </b-tab>

                <b-tab
                  title="学歴・職歴"
                  :title-link-class="isPassSecondTabValidation ? '' : 'error'"
                >
                  <b-card-text>
                    <div class="hr-content-tab hr-education-work-history">
                      <div class="hr-content-tab-wrap">
                        <div
                          class="hr-content-tab-item border-t border-l border-r"
                        >
                          <div
                            id="hr-type-edit"
                            class="hr-content-tab-item__title d-flex justify-content-between align-start"
                          >
                            <span>{{
                              $t('HR_LIST_FORM.FINAL_EDUCATION')
                            }}</span>
                            <Require />
                          </div>

                          <div class="hr-content-tab__data">
                            <div
                              class="w-100 d-flex flex-column py-3"
                              style="gap: 1.25rem"
                            >
                              <div
                                class="w-100 d-flex justify-space-center align-center"
                              >
                                <span
                                  style="
                                    width: 100%;
                                    max-width: 15%;
                                    font-size: 14px;
                                  "
                                  class="'pl-1"
                                >
                                  {{ $t('HR_LIST_FORM.GRADUATION_DATE') }}
                                </span>

                                <div
                                  class="w-100 d-flex align-center justify-content-between"
                                >
                                  <div class="d-flex flex-column">
                                    <div
                                      class="d-flex flex-row align-items-center"
                                    >
                                      <b-form-select
                                        v-model="final_education_timing_year"
                                        dusk="final_education_timing_year"
                                        class="w-100"
                                        style="min-width: 163px"
                                        :options="date_of_birth_year_option"
                                        :class="
                                          error.final_education_timing_year ===
                                            false
                                            ? ' is-invalid'
                                            : ''
                                        "
                                        @change="
                                          handleReAssignFeedback(
                                            'final_education_timing_year',
                                            $event
                                          )
                                        "
                                      />

                                      <span
                                        class="ml-3"
                                        style="font-size: 12px"
                                      >{{ $t('HR_LIST_FORM.YEAR') }}</span>
                                    </div>

                                    <b-form-invalid-feedback
                                      :state="error.final_education_timing_year"
                                    >
                                      <span>{{
                                        $t('VALIDATE.REQUIRED_TEXT')
                                      }}</span>
                                    </b-form-invalid-feedback>
                                  </div>

                                  <div class="d-flex flex-column">
                                    <div
                                      class="d-flex flex-row align-items-center"
                                    >
                                      <b-form-select
                                        v-model="final_education_timing_month"
                                        class="w-100"
                                        dusk="final_education_timing_month"
                                        :options="date_of_birth_month_option"
                                        style="min-width: 170px"
                                        :class="
                                          error.final_education_timing_month ===
                                            false
                                            ? ' is-invalid'
                                            : ''
                                        "
                                        @change="
                                          handleReAssignFeedback(
                                            'final_education_timing_month',
                                            $event
                                          )
                                        "
                                      />

                                      <span
                                        class="ml-3"
                                        style="font-size: 12px"
                                      >{{ $t('HR_LIST_FORM.MONTH') }}</span>
                                    </div>

                                    <b-form-invalid-feedback
                                      :state="
                                        error.final_education_timing_month
                                      "
                                    >
                                      <span>{{
                                        $t('VALIDATE.REQUIRED_TEXT')
                                      }}</span>
                                    </b-form-invalid-feedback>
                                  </div>
                                </div>
                              </div>

                              <div
                                class="w-100 d-flex justify-space-center align-center"
                              >
                                <span
                                  style="
                                    width: 100%;
                                    max-width: 15%;
                                    font-size: 14px;
                                  "
                                  class="pl-1"
                                >
                                  {{ $t('HR_LIST_FORM.CLASSIFICATION') }}
                                </span>

                                <div
                                  class="w-100 d-flex justify-space-between align-center"
                                >
                                  <div
                                    class="w-100 d-flex flex-column align-items-center"
                                  >
                                    <b-form-select
                                      v-model="final_education_classification"
                                      dusk="final_education_classification"
                                      :options="
                                        final_education_classification_options
                                      "
                                      :class="
                                        error.final_education_classification ===
                                          false
                                          ? ' is-invalid'
                                          : ''
                                      "
                                      @change="
                                        handleReAssignFeedback(
                                          'final_education_classification',
                                          $event
                                        )
                                      "
                                    />

                                    <b-form-invalid-feedback
                                      :state="
                                        error.final_education_classification
                                      "
                                    >
                                      <span>{{
                                        $t('VALIDATE.REQUIRED_TEXT')
                                      }}</span>
                                    </b-form-invalid-feedback>
                                  </div>
                                </div>
                              </div>

                              <div
                                class="w-100 d-flex justify-space-center align-center"
                              >
                                <span
                                  style="
                                    width: 100%;
                                    max-width: 15%;
                                    font-size: 14px;
                                  "
                                  class="pl-1"
                                >
                                  {{ $t('HR_LIST_FORM.DEGREE') }}
                                </span>

                                <div
                                  class="w-100 d-flex justify-space-between align-center"
                                >
                                  <div
                                    class="w-100 d-flex flex-column align-items-center"
                                  >
                                    <b-form-select
                                      v-model="final_education_degree"
                                      dusk="final_education_degree"
                                      :options="final_education_degree_options"
                                      :class="
                                        error.final_education_degree === false
                                          ? ' is-invalid'
                                          : ''
                                      "
                                      @change="
                                        handleReAssignFeedback(
                                          'final_education_degree',
                                          $event
                                        )
                                      "
                                    />

                                    <b-form-invalid-feedback
                                      :state="error.final_education_degree"
                                    >
                                      <span>{{
                                        $t('VALIDATE.REQUIRED_TEXT')
                                      }}</span>
                                    </b-form-invalid-feedback>
                                  </div>
                                </div>
                              </div>

                              <div
                                class="w-100 d-flex justify-space-center align-center"
                              >
                                <span
                                  style="
                                    width: 100%;
                                    max-width: 15%;
                                    font-size: 14px;
                                  "
                                  class="pl-1"
                                >
                                  {{ $t('HR_LIST_FORM.MAIN_CATEGORY') }}
                                </span>

                                <div
                                  class="w-100 d-flex justify-space-between align-center"
                                >
                                  <div
                                    class="w-100 d-flex flex-column align-items-center"
                                  >
                                    <b-form-select
                                      v-model="major_classification"
                                      dusk="major_classification"
                                      :options="
                                        major_classification_options_api
                                      "
                                      :class="
                                        error.major_classification === false
                                          ? ' is-invalid'
                                          : ''
                                      "
                                      @change="
                                        handleReAssignFeedback(
                                          'major_classification',
                                          $event
                                        )
                                      "
                                    />

                                    <b-form-invalid-feedback
                                      :state="error.major_classification"
                                    >
                                      <span>{{
                                        $t('VALIDATE.REQUIRED_TEXT')
                                      }}</span>
                                    </b-form-invalid-feedback>
                                  </div>
                                </div>
                              </div>

                              <div
                                class="w-100 d-flex justify-space-center align-center"
                              >
                                <span
                                  style="
                                    width: 100%;
                                    max-width: 15%;
                                    font-size: 14px;
                                  "
                                  class="pl-1"
                                >
                                  {{ $t('HR_LIST_FORM.DEPARTMENT') }}
                                </span>

                                <div
                                  class="w-100 d-flex justify-space-between align-center"
                                >
                                  <div
                                    class="w-100 d-flex flex-column align-items-center"
                                  >
                                    <b-form-select
                                      v-model="middle_classification"
                                      dusk="middle_classification"
                                      :options="
                                        middle_classification_options_api
                                      "
                                      :class="
                                        error.middle_classification === false
                                          ? ' is-invalid'
                                          : ''
                                      "
                                      :disabled="
                                        major_classification === null
                                          ? true
                                          : false
                                      "
                                      @change="
                                        handleReAssignFeedback(
                                          'middle_classification',
                                          $event
                                        )
                                      "
                                    />

                                    <b-form-invalid-feedback
                                      :state="error.middle_classification"
                                    >
                                      <span>{{
                                        $t('VALIDATE.REQUIRED_TEXT')
                                      }}</span>
                                    </b-form-invalid-feedback>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div
                          class="hr-content-tab-item border-t border-l border-r"
                        >
                          <div
                            id="hr-type-edit"
                            class="hr-content-tab-item__title d-flex align-start justify-content-between"
                          >
                            <span>{{
                              $t('HR_LIST_FORM.MAIN_JOB_CAREER_1')
                            }}</span>
                            <!-- <Require /> -->
                            <Arbitrarily />
                          </div>

                          <div
                            class="hr-content-tab__data"
                            dusk="main_job_career_1"
                          >
                            <div
                              class="w-100 d-flex flex-column py-3"
                              style="gap: 1.25rem"
                            >
                              <div
                                class="w-100 d-flex justify-space-center align-center"
                              >
                                <div
                                  class="w-100 d-flex justify-space-between align-center"
                                >
                                  <span
                                    style="
                                      width: 100%;
                                      max-width: 15%;
                                      font-size: 14px;
                                    "
                                    class="pl-1"
                                  >
                                    {{ $t('HR_LIST_FORM.DATE') }}
                                  </span>

                                  <div
                                    class="w-100 d-flex flex-row justify-content-center align-items-center"
                                    style="gap: 0.75rem"
                                  >
                                    <div
                                      class="w-80 d-flex justify-space-between align-center"
                                    >
                                      <div
                                        class="w-100 d-flex justify-space-between align-center"
                                        style="gap: 0.5rem"
                                      >
                                        <div
                                          class="w-100 d-flex flex-column"
                                          style="width: 54%; gap: 0.25rem"
                                        >
                                          <div
                                            class="w-100 d-flex flex-row align-items-center"
                                          >
                                            <b-form-select
                                              v-model="
                                                main_job_career_1_year_from
                                              "
                                              dusk="main_job_career_1_year_from"
                                              :options="
                                                date_of_birth_year_option_not_require
                                              "
                                              class="custome-selected"
                                              style="width: 80px"
                                              :class="
                                                error.main_job_career_1_year_from ===
                                                  false
                                                  ? 'is-invalid'
                                                  : ''
                                              "
                                              @change="
                                                handleReAssignFeedback(
                                                  'main_job_career_1_year_from',
                                                  $event
                                                )
                                              "
                                            />

                                            <span
                                              style="font-size: 12px"
                                              class="ml-2"
                                            >{{
                                              $t('HR_LIST_FORM.YEAR')
                                            }}</span>
                                          </div>

                                          <b-form-invalid-feedback
                                            :state="
                                              error.main_job_career_1_year_from
                                            "
                                          >
                                            <span style="font-size: 10px">{{
                                              $t('VALIDATE.REQUIRED_TEXT')
                                            }}</span>
                                          </b-form-invalid-feedback>
                                        </div>

                                        <div
                                          class="w-100 d-flex flex-column"
                                          style="width: 46%; gap: 0.25rem"
                                        >
                                          <div
                                            class="w-100 d-flex flex-row align-items-center"
                                          >
                                            <b-form-select
                                              v-model="
                                                main_job_career_1_month_from
                                              "
                                              dusk="main_job_career_1_month_from"
                                              :options="
                                                date_of_birth_month_option_not_require
                                              "
                                              style="width: 60px"
                                              :class="
                                                error.main_job_career_1_month_from ===
                                                  false
                                                  ? 'is-invalid'
                                                  : ''
                                              "
                                              @change="
                                                handleReAssignFeedback(
                                                  'main_job_career_1_month_from',
                                                  $event
                                                )
                                              "
                                            />

                                            <span
                                              style="font-size: 12px"
                                              class="ml-2"
                                            >{{
                                              $t('HR_LIST_FORM.MONTH')
                                            }}</span>
                                          </div>

                                          <b-form-invalid-feedback
                                            :state="
                                              error.main_job_career_1_month_from
                                            "
                                          >
                                            <span style="font-size: 10px">{{
                                              $t('VALIDATE.REQUIRED_TEXT')
                                            }}</span>
                                          </b-form-invalid-feedback>
                                        </div>
                                      </div>

                                      <span class="approximately">~</span>

                                      <div
                                        class="w-100 d-flex justify-space-between align-center"
                                        style="gap: 0.5rem"
                                      >
                                        <div
                                          class="w-100 d-flex flex-column"
                                          style="width: 48%; gap: 0.25rem"
                                        >
                                          <div
                                            class="w-100 d-flex flex-row align-items-center"
                                          >
                                            <b-form-select
                                              v-model="
                                                main_job_career_1_year_to
                                              "
                                              dusk="main_job_career_1_year_to"
                                              :options="
                                                date_of_birth_year_option_not_require
                                              "
                                              class="custome-selected"
                                              style="width: 80px"
                                              :disabled="
                                                main_job_career_1_time_now ===
                                                  true
                                              "
                                              :class="
                                                error.main_job_career_1_year_to ===
                                                  false
                                                  ? 'is-invalid'
                                                  : ''
                                              "
                                              @change="
                                                handleReAssignFeedback(
                                                  'main_job_career_1_year_to',
                                                  $event
                                                )
                                              "
                                            />

                                            <span
                                              style="font-size: 12px"
                                              class="ml-2"
                                            >{{
                                              $t('HR_LIST_FORM.YEAR')
                                            }}</span>
                                          </div>

                                          <b-form-invalid-feedback
                                            :state="
                                              error.main_job_career_1_year_to
                                            "
                                          >
                                            <span style="font-size: 10px">{{
                                              $t('VALIDATE.REQUIRED_TEXT')
                                            }}</span>
                                          </b-form-invalid-feedback>
                                        </div>

                                        <div
                                          class="w-100 d-flex flex-column"
                                          style="width: 48%; gap: 0.25rem"
                                        >
                                          <div
                                            class="w-100 d-flex flex-row align-items-center"
                                          >
                                            <b-form-select
                                              v-model="
                                                main_job_career_1_month_to
                                              "
                                              dusk="main_job_career_1_month_to"
                                              :options="
                                                date_of_birth_month_option_not_require
                                              "
                                              style="width: 60px"
                                              :disabled="
                                                main_job_career_1_time_now ===
                                                  true
                                              "
                                              :class="
                                                error.main_job_career_1_month_to ===
                                                  false
                                                  ? 'is-invalid'
                                                  : ''
                                              "
                                              @change="
                                                handleReAssignFeedback(
                                                  'main_job_career_1_month_to',
                                                  $event
                                                )
                                              "
                                            />

                                            <span
                                              style="font-size: 12px"
                                              class="ml-2"
                                            >{{
                                              $t('HR_LIST_FORM.MONTH')
                                            }}</span>
                                          </div>

                                          <b-form-invalid-feedback
                                            :state="
                                              error.main_job_career_1_month_to
                                            "
                                          >
                                            <span style="font-size: 9px">{{
                                              $t('VALIDATE.REQUIRED_TEXT')
                                            }}</span>
                                          </b-form-invalid-feedback>
                                        </div>
                                      </div>
                                    </div>

                                    <div
                                      class="w-20 d-flex justify-end align-items-center"
                                    >
                                      <b-form-checkbox
                                        id="main-job-career-1"
                                        v-model="main_job_career_1_time_now"
                                        size="sm"
                                        name="main-job-career-1"
                                        style="top: 4px"
                                        @change="
                                          clearValueWhenDisable(
                                            $event,
                                            'main-job-career-1'
                                          )
                                        "
                                      >
                                        <span class="fs-12">{{
                                          $t('CURRENT')
                                        }}</span>
                                      </b-form-checkbox>
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <div
                                class="w-100 d-flex justify-space-center align-center"
                              >
                                <span
                                  style="
                                    width: 100%;
                                    max-width: 15%;
                                    font-size: 14px;
                                  "
                                  class="pl-1"
                                >
                                  {{ $t('HR_LIST_FORM.DEPARTMENT') }}
                                </span>

                                <div
                                  class="w-100 d-flex flex-column justify-space-between align-center"
                                >
                                  <b-form-select
                                    v-model="main_job_career_1_department"
                                    dusk="main_job_career_1_department"
                                    :options="deparment_options_1"
                                    :class="
                                      error.main_job_career_1_department ===
                                        false
                                        ? 'is-invalid'
                                        : ''
                                    "
                                    @change="
                                      handleReAssignFeedback(
                                        'main_job_career_1_department',
                                        $event
                                      )
                                    "
                                  />

                                  <b-form-invalid-feedback
                                    :state="error.main_job_career_1_department"
                                  >
                                    <span>{{
                                      $t('VALIDATE.REQUIRED_TEXT')
                                    }}</span>
                                  </b-form-invalid-feedback>
                                </div>
                              </div>

                              <div
                                class="w-100 d-flex justify-space-center align-center"
                              >
                                <span
                                  style="
                                    width: 100%;
                                    max-width: 15%;
                                    font-size: 14px;
                                  "
                                  class="pl-1"
                                >
                                  {{ $t('HR_LIST_FORM.JOB_TITLE') }}
                                </span>

                                <div
                                  class="w-100 d-flex flex-column justify-space-between align-center"
                                >
                                  <b-form-select
                                    v-model="main_job_career_1_job_title"
                                    dusk="main_job_career_1_job_title"
                                    :options="job_title_manufacturingy_1"
                                    :disabled="
                                      main_job_career_1_department === null
                                    "
                                    :class="
                                      error.main_job_career_1_job_title ===
                                        false
                                        ? 'is-invalid'
                                        : ''
                                    "
                                    @change="
                                      handleReAssignFeedback(
                                        'main_job_career_1_job_title',
                                        $event
                                      )
                                    "
                                  />

                                  <b-form-invalid-feedback
                                    :state="error.main_job_career_1_job_title"
                                  >
                                    <span>{{
                                      $t('VALIDATE.REQUIRED_TEXT')
                                    }}</span>
                                  </b-form-invalid-feedback>
                                </div>
                              </div>

                              <div
                                class="w-100 d-flex justify-start align-start"
                              >
                                <span
                                  style="
                                    width: 100%;
                                    max-width: 15%;
                                    font-size: 14px;
                                  "
                                  class="pl-1"
                                >
                                  {{ $t('HR_LIST_FORM.DETAIL') }}
                                </span>

                                <div
                                  class="w-100 d-flex flex-column justify-start align-start"
                                  dusk="main_job_career_detail"
                                >
                                  <b-form-textarea
                                    id="textarea-main-job-career-1"
                                    v-model="main_job_career_1_textarea"
                                    dusk="main_job_career_1_detail"
                                    rows="8"
                                    max-rows="28"
                                    placeholder=""
                                    max-lengh="2000"
                                    :formatter="format2000characters"
                                    :class="
                                      error.main_job_career_1_textarea === false
                                        ? 'is-invalid'
                                        : ''
                                    "
                                    @input="
                                      handleReAssignFeedback(
                                        'main_job_career_1_textarea',
                                        $event
                                      )
                                    "
                                  />

                                  <b-form-invalid-feedback
                                    :state="error.main_job_career_1_textarea"
                                  >
                                    <span>{{
                                      $t('VALIDATE.REQUIRED_TEXT')
                                    }}</span>
                                  </b-form-invalid-feedback>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div
                          class="hr-content-tab-item border-t border-l border-r"
                        >
                          <div
                            id="hr-type-edit"
                            class="hr-content-tab-item__title d-flex align-start justify-content-between"
                          >
                            <span>{{
                              $t('HR_LIST_FORM.MAIN_JOB_CAREER_2')
                            }}</span>
                            <Arbitrarily />
                          </div>

                          <div class="hr-content-tab__data">
                            <div
                              class="w-100 d-flex flex-column py-3"
                              style="gap: 1.25rem"
                            >
                              <div
                                class="w-100 d-flex justify-space-center align-center"
                              >
                                <div
                                  class="w-100 d-flex justify-space-between align-center"
                                >
                                  <span
                                    style="
                                      width: 100%;
                                      max-width: 15%;
                                      font-size: 14px;
                                    "
                                    class="pl-1"
                                  >
                                    {{ $t('HR_LIST_FORM.DATE') }}
                                  </span>

                                  <div
                                    class="w-100 d-flex flex-row justify-content-center align-items-center"
                                    style="gap: 0.75rem"
                                  >
                                    <div
                                      class="w-80 d-flex justify-space-between align-center"
                                    >
                                      <div
                                        class="w-100 d-flex justify-space-between align-center"
                                        style="gap: 0.5rem"
                                      >
                                        <div
                                          class="d-flex justify-space-between align-center"
                                          style="width: 54%; gap: 0.25rem"
                                        >
                                          <div
                                            class="w-100 d-flex flex-row align-items-center"
                                          >
                                            <b-form-select
                                              v-model="
                                                main_job_career_2_year_from
                                              "
                                              :options="
                                                date_of_birth_year_option_not_require
                                              "
                                              class="custome-selected"
                                              style="width: 80px"
                                            />

                                            <span
                                              style="font-size: 12px"
                                              class="ml-2"
                                            >{{
                                              $t('HR_LIST_FORM.YEAR')
                                            }}</span>
                                          </div>
                                        </div>

                                        <div
                                          class="w-100 d-flex flex-column"
                                          style="width: 46%; gap: 0.25rem"
                                        >
                                          <div
                                            class="w-100 d-flex flex-row align-items-center"
                                          >
                                            <b-form-select
                                              v-model="
                                                main_job_career_2_month_from
                                              "
                                              :options="
                                                date_of_birth_month_option_not_require
                                              "
                                              style="width: 60px"
                                            />

                                            <span
                                              style="font-size: 12px"
                                              class="ml-2"
                                            >{{
                                              $t('HR_LIST_FORM.MONTH')
                                            }}</span>
                                          </div>
                                        </div>
                                      </div>

                                      <span class="approximately">~</span>

                                      <div
                                        class="w-100 d-flex justify-space-between align-center"
                                        style="gap: 0.5rem"
                                      >
                                        <div
                                          class="w-100 d-flex flex-column"
                                          style="width: 48%; gap: 0.25rem"
                                        >
                                          <div
                                            class="w-100 d-flex flex-row align-items-center"
                                          >
                                            <b-form-select
                                              v-model="
                                                main_job_career_2_year_to
                                              "
                                              :options="
                                                date_of_birth_year_option_not_require
                                              "
                                              class="custome-selected"
                                              style="width: 80px"
                                              :disabled="
                                                main_job_career_2_time_now
                                              "
                                            />

                                            <span
                                              style="font-size: 12px"
                                              class="ml-2"
                                            >{{
                                              $t('HR_LIST_FORM.YEAR')
                                            }}</span>
                                          </div>
                                        </div>

                                        <div
                                          class="w-100 d-flex flex-column"
                                          style="width: 48%; gap: 0.25rem"
                                        >
                                          <div
                                            class="w-100 d-flex flex-row align-items-center"
                                          >
                                            <b-form-select
                                              v-model="
                                                main_job_career_2_month_to
                                              "
                                              :options="
                                                date_of_birth_month_option_not_require
                                              "
                                              style="width: 60px"
                                              :disabled="
                                                main_job_career_2_time_now
                                              "
                                            />

                                            <span
                                              style="font-size: 12px"
                                              class="ml-2"
                                            >{{
                                              $t('HR_LIST_FORM.MONTH')
                                            }}</span>
                                          </div>
                                        </div>
                                      </div>
                                    </div>

                                    <div
                                      class="w-20 d-flex justify-end align-items-center"
                                    >
                                      <b-form-checkbox
                                        id="main-job-career-2"
                                        v-model="main_job_career_2_time_now"
                                        name="main-job-career-2"
                                        :value="true"
                                        size="sm"
                                        style="top: 4px"
                                        @change="
                                          clearValueWhenDisable(
                                            $event,
                                            'main-job-career-2'
                                          )
                                        "
                                      >
                                        <span class="fs-12">{{
                                          $t('CURRENT')
                                        }}</span>
                                      </b-form-checkbox>
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <div
                                class="w-100 d-flex justify-space-center align-center"
                              >
                                <span
                                  style="
                                    width: 100%;
                                    max-width: 15%;
                                    font-size: 14px;
                                  "
                                  class="pl-1"
                                >
                                  {{ $t('HR_LIST_FORM.DEPARTMENT') }}
                                </span>

                                <div
                                  class="w-100 d-flex flex-column justify-space-between align-center"
                                >
                                  <b-form-select
                                    v-model="main_job_career_2_department"
                                    :options="deparment_options_2"
                                    @change="
                                      handleChangeMainJobCareerDepartment(2)
                                    "
                                  />
                                </div>
                              </div>

                              <div
                                class="w-100 d-flex justify-space-center align-center"
                              >
                                <span
                                  style="
                                    width: 100%;
                                    max-width: 15%;
                                    font-size: 14px;
                                  "
                                  class="pl-1"
                                >
                                  {{ $t('HR_LIST_FORM.JOB_TITLE') }}
                                </span>

                                <div
                                  class="w-100 d-flex flex-column justify-space-between align-center"
                                >
                                  <b-form-select
                                    v-model="main_job_career_2_job_title"
                                    :options="job_title_manufacturingy_2"
                                    :disabled="
                                      main_job_career_2_department === null
                                    "
                                  />
                                </div>
                              </div>

                              <div
                                class="w-100 d-flex justify-start align-start"
                              >
                                <span
                                  style="
                                    width: 100%;
                                    max-width: 15%;
                                    font-size: 14px;
                                  "
                                  class="pl-1"
                                >
                                  {{ $t('HR_LIST_FORM.DETAIL') }}
                                </span>

                                <div
                                  class="w-100 d-flex flex-column justify-start align-start"
                                >
                                  <b-form-textarea
                                    id="textarea-main-job-career-2"
                                    v-model="main_job_career_2_textarea"
                                    placeholder=""
                                    rows="8"
                                    max-rows="28"
                                    max-lengh="2000"
                                    :formatter="format2000characters"
                                  />
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div
                          class="hr-content-tab-item border-t border-l border-r border-b"
                        >
                          <div
                            id="hr-type-edit"
                            class="hr-content-tab-item__title d-flex align-start justify-content-between"
                          >
                            <span style="font-size: 12px">{{
                              $t('HR_LIST_FORM.MAIN_JOB_CAREER_3')
                            }}</span>
                            <Arbitrarily />
                          </div>

                          <div class="hr-content-tab__data">
                            <div
                              class="w-100 d-flex flex-column py-3"
                              style="gap: 1.25rem"
                            >
                              <div
                                class="w-100 d-flex justify-space-center align-center"
                              >
                                <div
                                  class="w-100 d-flex justify-space-between align-center"
                                >
                                  <span
                                    style="
                                      width: 100%;
                                      max-width: 15%;
                                      font-size: 14px;
                                    "
                                    class="pl-1"
                                  >
                                    {{ $t('HR_LIST_FORM.DATE') }}
                                  </span>

                                  <div
                                    class="w-100 d-flex flex-row justify-content-center align-items-center"
                                    style="gap: 0.75rem"
                                  >
                                    <div
                                      class="w-80 d-flex justify-space-between align-center"
                                    >
                                      <div
                                        class="w-100 d-flex justify-space-between align-center"
                                        style="gap: 0.5rem"
                                      >
                                        <div
                                          class="w-100 d-flex flex-column"
                                          style="width: 54%; gap: 0.25rem"
                                        >
                                          <div
                                            class="w-100 d-flex flex-row align-items-center"
                                          >
                                            <b-form-select
                                              v-model="
                                                main_job_career_3_year_from
                                              "
                                              :options="
                                                date_of_birth_year_option_not_require
                                              "
                                              class="custome-selected"
                                              style="width: 80px"
                                            />

                                            <span
                                              style="font-size: 12px"
                                              class="ml-2"
                                            >{{
                                              $t('HR_LIST_FORM.YEAR')
                                            }}</span>
                                          </div>
                                        </div>

                                        <div
                                          class="w-100 d-flex flex-column"
                                          style="width: 46%; gap: 0.25rem"
                                        >
                                          <div
                                            class="w-100 d-flex flex-row align-items-center"
                                          >
                                            <b-form-select
                                              v-model="
                                                main_job_career_3_month_from
                                              "
                                              :options="
                                                date_of_birth_month_option_not_require
                                              "
                                              style="width: 60px"
                                            />

                                            <span
                                              style="font-size: 12px"
                                              class="ml-2"
                                            >{{
                                              $t('HR_LIST_FORM.MONTH')
                                            }}</span>
                                          </div>
                                        </div>
                                      </div>

                                      <span class="approximately">~</span>

                                      <div
                                        class="w-100 d-flex justify-space-between align-center"
                                        style="gap: 0.5rem"
                                      >
                                        <div
                                          class="w-100 d-flex flex-column"
                                          style="width: 48%; gap: 0.25rem"
                                        >
                                          <div
                                            class="w-100 d-flex flex-row align-items-center"
                                          >
                                            <b-form-select
                                              v-model="
                                                main_job_career_3_year_to
                                              "
                                              :options="
                                                date_of_birth_year_option_not_require
                                              "
                                              class="custome-selected"
                                              style="width: 80px"
                                              :disabled="
                                                main_job_career_3_time_now ===
                                                  true
                                              "
                                            />

                                            <span
                                              style="font-size: 12px"
                                              class="ml-2"
                                            >{{
                                              $t('HR_LIST_FORM.YEAR')
                                            }}</span>
                                          </div>
                                        </div>

                                        <div
                                          class="w-100 d-flex flex-column"
                                          style="width: 48%; gap: 0.25rem"
                                        >
                                          <div
                                            class="w-100 d-flex flex-row align-items-center"
                                          >
                                            <b-form-select
                                              v-model="
                                                main_job_career_3_month_to
                                              "
                                              :options="
                                                date_of_birth_month_option_not_require
                                              "
                                              style="width: 60px"
                                              :disabled="
                                                main_job_career_3_time_now ===
                                                  true
                                              "
                                            />

                                            <span
                                              style="font-size: 12px"
                                              class="ml-2"
                                            >{{
                                              $t('HR_LIST_FORM.MONTH')
                                            }}</span>
                                          </div>
                                        </div>
                                      </div>
                                    </div>

                                    <div
                                      class="w-20 d-flex justify-end align-items-center"
                                    >
                                      <b-form-checkbox
                                        id="main-job-career-3"
                                        v-model="main_job_career_3_time_now"
                                        name="main-job-career-3"
                                        size="sm"
                                        style="top: 4px"
                                        @change="
                                          clearValueWhenDisable(
                                            $event,
                                            'main-job-career-3'
                                          )
                                        "
                                      >
                                        <span class="fs-12">{{
                                          $t('CURRENT')
                                        }}</span>
                                      </b-form-checkbox>
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <div
                                class="w-100 d-flex justify-space-center align-center"
                              >
                                <span
                                  style="
                                    width: 100%;
                                    max-width: 15%;
                                    font-size: 14px;
                                  "
                                  class="pl-1"
                                >
                                  {{ $t('HR_LIST_FORM.DEPARTMENT') }}
                                </span>

                                <div
                                  class="w-100 d-flex flex-column justify-space-between align-center"
                                >
                                  <b-form-select
                                    v-model="main_job_career_3_department"
                                    :options="deparment_options_3"
                                    @change="
                                      handleChangeMainJobCareerDepartment(3)
                                    "
                                  />
                                </div>
                              </div>

                              <div
                                class="w-100 d-flex justify-space-center align-center"
                              >
                                <span
                                  style="
                                    width: 100%;
                                    max-width: 15%;
                                    font-size: 14px;
                                  "
                                  class="pl-1"
                                >
                                  {{ $t('HR_LIST_FORM.JOB_TITLE') }}
                                </span>

                                <div
                                  class="w-100 d-flex flex-column justify-space-between align-center"
                                >
                                  <b-form-select
                                    v-model="main_job_career_3_job_title"
                                    :options="job_title_manufacturingy_3"
                                    :disabled="
                                      main_job_career_3_department === null
                                    "
                                  />
                                </div>
                              </div>

                              <div
                                class="w-100 d-flex justify-start align-start"
                              >
                                <span
                                  style="
                                    width: 100%;
                                    max-width: 15%;
                                    font-size: 14px;
                                  "
                                  class="pl-1"
                                >
                                  {{ $t('HR_LIST_FORM.DETAIL') }}
                                </span>

                                <div
                                  class="w-100 d-flex flex-column justify-start align-start"
                                >
                                  <b-form-textarea
                                    id="textarea-main-job-career-3"
                                    v-model="main_job_career_3_textarea"
                                    placeholder=""
                                    rows="8"
                                    max-rows="28"
                                    max-lengh="2000"
                                    :formatter="format2000characters"
                                  />
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </b-card-text>
                </b-tab>

                <b-tab
                  title="自己PR・備考"
                  :title-link-class="isPassThirdTabValidation ? '' : 'error'"
                >
                  <b-card-text>
                    <div class="hr-content-tab hr-persional-pr-remarks">
                      <div class="hr-content-tab-wrap">
                        <div
                          class="hr-content-tab-item border-t border-l border-r"
                          style="min-height: 240px"
                        >
                          <div
                            class="hr-content-tab-item__title d-flex align-items-start justify-content-between"
                          >
                            <span>{{
                              $t('HR_LIST_FORM.PERSONAL_PR_SPECIAL_NOTES')
                            }}</span>
                            <Arbitrarily />
                          </div>

                          <div
                            class="hr-content-tab__data d-flex justify-content-start align-items-start py-2"
                          >
                            <div
                              class="w-100 h-100 d-flex justify-content-start align-items-stretch"
                            >
                              <b-form-textarea
                                id="textarea"
                                v-model="personal_pr_special_textarea"
                                placeholder=""
                                rows="4"
                                max-rows="28"
                                max-lengh="2000"
                                :formatter="format2000characters"
                                class="h-100"
                              />
                            </div>
                          </div>
                        </div>

                        <div
                          class="hr-content-tab-item border-t border-l border-r border-b"
                          style="min-height: 240px"
                        >
                          <div
                            class="hr-content-tab-item__title d-flex align-items-start justify-content-between"
                          >
                            <span>{{ $t('HR_LIST_FORM.REMARKS') }}</span>
                            <Arbitrarily />
                          </div>

                          <div
                            class="hr-content-tab__data d-flex justify-content-start align-items-start py-2"
                          >
                            <div
                              class="w-100 h-100 d-flex justify-content-start align-items-stretch"
                            >
                              <b-form-textarea
                                id="textarea"
                                v-model="remarks_textarea"
                                placeholder=""
                                rows="4"
                                max-rows="28"
                                max-lengh="2000"
                                :formatter="format2000characters"
                                class="h-100"
                              />
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </b-card-text>
                </b-tab>

                <b-tab
                  title="連絡先"
                  :title-link-class="isPassFourthTabValidation ? '' : 'error'"
                >
                  <b-card-text>
                    <div class="hr-content-tab hr-contact">
                      <div class="hr-content-tab-wrap">
                        <div
                          class="hr-content-tab-item border-t border-l border-r"
                        >
                          <div class="hr-content-tab-item__title d-flex justify-content-between">
                            <span>{{
                              $t('HR_LIST_FORM.TELEPHONE_NUMBER')
                            }}</span>
                            <Arbitrarily />
                          </div>

                          <div class="hr-content-tab__data">
                            <div
                              class="flex-row"
                              style="
                                gap: 1rem;
                                justify-content: flex-start;
                                align-items: center;
                              "
                            >
                              <!-- Dropdown -->
                              <div class="h-100" style="height: 40px">
                                <div class="option-validate">
                                  <div class="option-area-code">
                                    <b-dropdown
                                      id="telephone_phone"
                                      class="w-100 h-100"
                                      :text="telephone_phone_number_prefix"
                                    >
                                      <!-- BLANK -->
                                      <b-dropdown-item
                                        @click="
                                          handleChangeCountry(
                                            'telephone_phone_number_prefix',
                                            ''
                                          )
                                        "
                                      >
                                        <span style="height: 28px" />
                                      </b-dropdown-item>
                                      <!-- VIE -->
                                      <b-dropdown-item
                                        @click="
                                          handleChangeCountry(
                                            'telephone_phone_number_prefix',
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
                                        @click="
                                          handleChangeCountry(
                                            'telephone_phone_number_prefix',
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
                                        telephone_phone_number_prefix === '+84'
                                      "
                                      :src="
                                        require(`@/assets/images/icons/flag-84.png`)
                                      "
                                    >
                                    <img
                                      v-if="
                                        telephone_phone_number_prefix === '+81'
                                      "
                                      :src="
                                        require(`@/assets/images/icons/flag-81.png`)
                                      "
                                    >
                                  </div>
                                </div>
                              </div>

                              <b-form-input
                                v-model="telephone_phone_number"
                                max-lenght="50"
                                type="number"
                                class="form-input px-2"
                                :name="'mail address'"
                                :placeholder="'例) 0312345678 ハイフン無しで入力'"
                                :disabled="telephone_phone_number_prefix === ''"
                                style="width: 100%"
                              />
                            </div>
                          </div>
                        </div>

                        <div
                          class="hr-content-tab-item border-t border-l border-r"
                        >
                          <div class="hr-content-tab-item__title d-flex justify-content-between">
                            <span>{{
                              $t('HR_LIST_FORM.MOBILE_PHONE_NUMBER')
                            }}</span>
                            <Arbitrarily />
                          </div>

                          <div class="hr-content-tab__data">
                            <div
                              class="flex-row"
                              style="
                                gap: 1rem;
                                justify-content: flex-start;
                                align-items: center;
                              "
                            >
                              <!-- dropdown -->
                              <div class="h-100" style="height: 40px">
                                <div class="option-validate">
                                  <div class="option-area-code">
                                    <b-dropdown
                                      id="mobile_phone"
                                      class="w-100 h-100"
                                      :text="mobile_phone_number_prefix"
                                    >
                                      <!-- BLANK -->
                                      <b-dropdown-item
                                        @click="
                                          handleChangeCountry(
                                            'mobile_phone_number_prefix',
                                            ''
                                          )
                                        "
                                      >
                                        <span style="height: 28px" />
                                      </b-dropdown-item>
                                      <!-- VIE -->
                                      <b-dropdown-item
                                        @click="
                                          handleChangeCountry(
                                            'mobile_phone_number_prefix',
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
                                        @click="
                                          handleChangeCountry(
                                            'mobile_phone_number_prefix',
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
                                        mobile_phone_number_prefix === '+84'
                                      "
                                      :src="
                                        require(`@/assets/images/icons/flag-84.png`)
                                      "
                                    >
                                    <img
                                      v-if="
                                        mobile_phone_number_prefix === '+81'
                                      "
                                      :src="
                                        require(`@/assets/images/icons/flag-81.png`)
                                      "
                                    >
                                  </div>
                                </div>
                              </div>

                              <b-form-input
                                v-model="mobile_phone_number"
                                max-lenght="50"
                                type="number"
                                class="form-input px-2"
                                :name="'mail address'"
                                :placeholder="'例) 0312345678 ハイフン無しで入力'"
                                :disabled="mobile_phone_number_prefix === ''"
                                style="width: 100%"
                              />
                            </div>
                          </div>
                        </div>

                        <div
                          class="hr-content-tab-item border-t border-l border-r"
                        >
                          <div class="hr-content-tab-item__title d-flex justify-content-between">
                            <span>{{ $t('HR_LIST_FORM.MAIL_ADDRESS') }}</span>
                            <Require />
                          </div>

                          <div class="hr-content-tab__data">
                            <div
                              class="w-100 d-flex flex-column"
                              style="
                                justify-content: flex-start;
                                align-items: center;
                              "
                            >
                              <b-form-input
                                v-model="mail_address"
                                dusk="mail_address"
                                max-lenght="50"
                                :placeholder="''"
                                style="width: 100%"
                                :name="'mail address'"
                                class="form-input px-2"
                                :formatter="format50characters"
                                :class="
                                  !error.mail_address ? 'mt-3 is-invalid' : ''
                                "
                                @input="
                                  handleReAssignFeedback('mail_address', $event)
                                "
                              />

                              <b-form-invalid-feedback
                                :state="error.mail_address"
                              >
                                <span>{{ invalid_mail_error_text }}</span>
                              </b-form-invalid-feedback>
                            </div>
                          </div>
                        </div>

                        <div
                          class="hr-content-tab-item border-t border-l border-r"
                        >
                          <div
                            class="hr-content-tab-item__title d-flex justify-content-between"
                            style="
                              align-items: flex-start;
                              padding-top: 1.5rem;
                            "
                          >
                            <span>{{ $t('HR_LIST_FORM.ADDRESS') }}</span>
                            <Arbitrarily />
                          </div>

                          <div class="hr-content-tab__data">
                            <div
                              class="flex-row"
                              style="
                                gap: 1rem;
                                justify-content: flex-start;
                                align-items: center;
                                padding: 1rem 0;
                              "
                            >
                              <div
                                class="w-100 d-flex flex-column justify-space-between align-center"
                                style="gap: 1.751rem"
                              >
                                <div
                                  class="w-100 d-flex"
                                  style="
                                    height: 48px;
                                    justify-content: space-between;
                                    align-items: center;
                                    gap: 0.751rem;
                                  "
                                >
                                  <span style="min-width: 15%">{{
                                    $t('HR_LIST_FORM.CITY')
                                  }}</span>
                                  <b-form-input
                                    v-model="address_city"
                                    max-lenght="50"
                                    :formatter="format50characters"
                                    style="width: 80%"
                                  />
                                </div>

                                <div
                                  class="w-100 d-flex"
                                  style="
                                    height: 48px;
                                    justify-content: space-between;
                                    align-items: center;
                                    gap: 0.751rem;
                                  "
                                >
                                  <span style="min-width: 15%">{{
                                    $t('HR_LIST_FORM.DISTRICT')
                                  }}</span>
                                  <b-form-input
                                    v-model="address_district"
                                    max-lenght="50"
                                    :formatter="format50characters"
                                    style="width: 80%"
                                  />
                                </div>

                                <div
                                  class="w-100 d-flex"
                                  style="
                                    height: 48px;
                                    justify-content: space-between;
                                    align-items: center;
                                    gap: 0.751rem;
                                  "
                                >
                                  <span style="min-width: 15%">{{
                                    $t('HR_LIST_FORM.NUMBER')
                                  }}</span>
                                  <b-form-input
                                    v-model="address_number"
                                    max-lenght="50"
                                    :formatter="format50characters"
                                    style="width: 80%"
                                  />
                                </div>

                                <div
                                  class="w-100 d-flex"
                                  style="
                                    height: 48px;
                                    justify-content: space-between;
                                    align-items: center;
                                    gap: 0.751rem;
                                  "
                                >
                                  <span style="min-width: 15%">{{
                                    $t('HR_LIST_FORM.ORTHER')
                                  }}</span>
                                  <b-form-input
                                    v-model="address_other"
                                    max-lenght="50"
                                    :formatter="format50characters"
                                    style="width: 80%"
                                  />
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div
                          class="hr-content-tab-item border-t border-l border-r border-b"
                        >
                          <div
                            class="hr-content-tab-item__title d-flex justify-content-between"
                            style="
                              align-items: flex-start;
                              padding-top: 1.5rem;
                            "
                          >
                            <span>{{
                              $t('HR_LIST_FORM.HOMETOWN_ADDRESS')
                            }}</span>
                            <Arbitrarily />
                          </div>

                          <div class="hr-content-tab__data">
                            <div
                              class="flex-row"
                              style="
                                gap: 1rem;
                                justify-content: flex-start;
                                align-items: center;
                                padding: 1rem 0;
                              "
                            >
                              <div
                                class="w-100 d-flex flex-column justify-space-between align-center"
                                style="gap: 1.751rem"
                              >
                                <div
                                  class="w-100 d-flex"
                                  style="
                                    height: 48px;
                                    justify-content: space-between;
                                    align-items: center;
                                    gap: 0.751rem;
                                  "
                                >
                                  <span style="min-width: 15%">{{
                                    $t('HR_LIST_FORM.CITY')
                                  }}</span>
                                  <b-form-input
                                    v-model="hometown_address_city"
                                    max-lenght="50"
                                    :formatter="format50characters"
                                    style="width: 80%"
                                  />
                                </div>

                                <div
                                  class="w-100 d-flex"
                                  style="
                                    height: 48px;
                                    justify-content: space-between;
                                    align-items: center;
                                    gap: 0.751rem;
                                  "
                                >
                                  <span style="min-width: 15%">{{
                                    $t('HR_LIST_FORM.DISTRICT')
                                  }}</span>
                                  <b-form-input
                                    v-model="hometown_address_district"
                                    max-lenght="50"
                                    :formatter="format50characters"
                                    style="width: 80%"
                                  />
                                </div>

                                <div
                                  class="w-100 d-flex"
                                  style="
                                    height: 48px;
                                    justify-content: space-between;
                                    align-items: center;
                                    gap: 0.751rem;
                                  "
                                >
                                  <span style="min-width: 15%">{{
                                    $t('HR_LIST_FORM.NUMBER')
                                  }}</span>
                                  <b-form-input
                                    v-model="hometown_address_number"
                                    max-lenght="50"
                                    :formatter="format50characters"
                                    style="width: 80%"
                                  />
                                </div>

                                <div
                                  class="w-100 d-flex"
                                  style="
                                    height: 48px;
                                    justify-content: space-between;
                                    align-items: center;
                                    gap: 0.751rem;
                                  "
                                >
                                  <span style="min-width: 15%">{{
                                    $t('HR_LIST_FORM.ORTHER')
                                  }}</span>
                                  <b-form-input
                                    v-model="hometown_address_other"
                                    max-lenght="50"
                                    :formatter="format50characters"
                                    style="width: 80%"
                                  />
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </b-card-text>
                </b-tab>

                <b-tab
                  title="マッチング状況"
                  :title-link-class="isPassFifthTabValidation ? '' : 'error'"
                >
                  <b-card-text>
                    <div class="hr-detail-matching-situation-tab">
                      <div class="hr-detail-list-status-process">
                        <div
                          class="hr-detail-list-status-process-item border-t border-l border-r"
                        >
                          <div
                            class="hr-detail-list-status-process-item--head border-b"
                          >
                            <span>{{ $t('HR_LIST.ENTRY_APPLICATION') }}</span>
                          </div>

                          <div class="hr-detail-list-status-process--content">
                            <div class="hr-detail-list-status">
                              <div
                                v-for="(item, index) in entries"
                                :key="index"
                                class="hr-detail-list-status-item"
                              >
                                <div class="hr-detail-list-status-item__status">
                                  <div
                                    :class="item.status"
                                    class="hr-status-block"
                                  >
                                    <span class="one-line-paragraph">{{
                                      handleRenderTextByStatus(
                                        1,
                                        item.origin_status
                                      )
                                    }}</span>
                                  </div>
                                </div>

                                <div
                                  class="hr-detail-list-status-item__candidate-info"
                                >
                                  <span class="one-line-paragraph">{{
                                    item.remarks
                                  }}</span>
                                </div>

                                <div
                                  class="hr-detail-list-status-item__entry-date"
                                >
                                  <div>{{ $t('HR_LIST.ENTRY_DATE') }}：</div>
                                  <span class="one-line-paragraph">{{
                                    item.entry_date
                                  }}</span>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div
                          class="hr-detail-list-status-process-item border-t border-l border-r"
                        >
                          <div
                            class="hr-detail-list-status-process-item--head border-b"
                          >
                            <span>{{ $t('HR_LIST.OFFER_RECEIVED') }}</span>
                          </div>

                          <div class="hr-detail-list-status-process--content">
                            <div class="hr-detail-list-status">
                              <div
                                v-for="(item, index) in offers"
                                :key="index"
                                class="hr-detail-list-status-item"
                              >
                                <div class="hr-detail-list-status-item__status">
                                  <div
                                    :class="item.status"
                                    class="hr-status-block"
                                  >
                                    <span class="one-line-paragraph">{{
                                      handleRenderTextByStatus(
                                        2,
                                        item.origin_status
                                      )
                                    }}</span>
                                  </div>
                                </div>

                                <div
                                  class="hr-detail-list-status-item__candidate-info"
                                >
                                  <span class="one-line-paragraph">{{
                                    item.remarks
                                  }}</span>
                                </div>

                                <div
                                  class="hr-detail-list-status-item__entry-date"
                                >
                                  <div>{{ $t('HR_LIST.ENTRY_DATE') }}：</div>
                                  <span class="one-line-paragraph">{{
                                    item.entry_date
                                  }}</span>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div
                          class="hr-detail-list-status-process-item border-t border-l border-r"
                        >
                          <div
                            class="hr-detail-list-status-process-item--head border-b"
                          >
                            <span>{{ $t('HR_LIST.ARRINTERVIEW') }}</span>
                          </div>

                          <div class="hr-detail-list-status-process--content">
                            <div class="hr-detail-list-status">
                              <div
                                v-for="(item, index) in interviews"
                                :key="index"
                                class="hr-detail-list-status-item"
                              >
                                <div class="hr-detail-list-status-item__status">
                                  <div
                                    :class="item.status"
                                    class="hr-status-block"
                                  >
                                    <span class="one-line-paragraph">{{
                                      handleRenderTextByStatus(
                                        3,
                                        item.origin_status
                                      )
                                    }}</span>
                                  </div>
                                </div>

                                <div
                                  class="hr-detail-list-status-item__candidate-info"
                                >
                                  <span class="one-line-paragraph">{{
                                    item.remarks
                                  }}</span>
                                </div>

                                <div
                                  class="hr-detail-list-status-item__entry-date"
                                >
                                  <div>{{ $t('HR_LIST.ENTRY_DATE') }}：</div>
                                  <span class="one-line-paragraph">{{
                                    item.entry_date
                                  }}</span>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div
                          class="hr-detail-list-status-process-item border-t border-l border-r border-b"
                        >
                          <div
                            class="hr-detail-list-status-process-item--head border-b"
                          >
                            <span>{{ $t('HR_LIST.RESULT') }}</span>
                          </div>

                          <div class="hr-detail-list-status-process--content">
                            <div class="hr-detail-list-status">
                              <div
                                v-for="(item, index) in results"
                                :key="index"
                                class="hr-detail-list-status-item"
                              >
                                <div class="hr-detail-list-status-item__status">
                                  <div
                                    :class="item.status"
                                    class="hr-status-block"
                                  >
                                    <span class="one-line-paragraph">{{
                                      handleRenderTextByStatus(
                                        4,
                                        item.origin_status
                                      )
                                    }}</span>
                                  </div>
                                </div>

                                <div
                                  class="hr-detail-list-status-item__candidate-info"
                                >
                                  <span class="one-line-paragraph">{{
                                    item.remarks
                                  }}</span>
                                </div>

                                <div
                                  class="hr-detail-list-status-item__entry-date"
                                >
                                  <div>{{ $t('HR_LIST.ENTRY_DATE') }}：</div>
                                  <span class="one-line-paragraph">{{
                                    item.entry_date
                                  }}</span>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </b-card-text>
                </b-tab>
              </b-tabs>
            </b-card>
          </div>
        </div>
      </div>
    </div>
  </b-overlay>
</template>

<script>
import { MakeToast } from '@/utils/toastMessage';
import { editHr, getOneHr, uploadSingleFile } from '@/api/modules/hr';
import {
  renderYears,
  renderMonth,
  renderYearNotRequire,
  renderMonthNotRequire,
  phone_number_options_common,
  final_education_degree_options,
  final_education_classification_options,
} from '@/pages/Hr/common.js';

import ROLE from '@/const/role.js';

import { getJobTypeHr } from '@/api/hr.js';
import Require from '@/components/Require/Require.vue';
import Arbitrarily from '@/components/Arbitrarily/Arbitrarily.vue';
import { LIMIT_FILE_SIZE, FILE_TYPE } from '@/const/config.js';
const FILE_CAN_UPLOAD = [FILE_TYPE.PDF, FILE_TYPE.MP3, FILE_TYPE.MP4];
const urlAPI = {
  urlEditHr: '/hr/',
  urlGetOneHr: '/hr/',
  urlGetListCompany: '/company',
  urlUploadSingleFile: '/upload',
};

export default {
  name: 'HREdit',
  components: {
    Require,
    Arbitrarily,
  },
  data() {
    return {
      HR_ID: this.$route.params.id,
      ROLE: ROLE,
      overlay: {
        opacity: 1,
        show: false,
        blur: '1rem',
        rounded: 'sm',
        variant: 'light',
      },

      overlay_upload: {
        opacity: 0,
        show: false,
        blur: '1rem',
        rounded: 'sm',
        variant: 'light',
      },

      companyList: [],

      gender_option: [
        { value: null, text: '選択してください', disabled: false },
        { value: 1, text: '男性' },
        { value: 2, text: '女性' },
      ],

      vItems: {},
      cv_status: 'private',
      hr_status: 0,
      favorite_added: true,

      original_hr_id: '',
      country_id: '',

      company_name_en: '',
      company_name_jp: '',

      cv_file: null,
      cv_file_name: '選択されていません',
      cv_file_preview_src: null,
      cv_file_id: '',
      cv_file_path: '',

      jd_file: null,
      jd_file_name: '選択されていません',
      jd_file_preview_src: null,
      jd_file_id: '',
      jd_file_path: '',

      default_other_file: null,
      default_other_file_name: '選択されていません',
      default_other_file_preview_src: null,
      default_other_file_id: '',
      default_other_file_path: '',
      default_other_file_system_file_name: '',

      date_of_birth_year_option: renderYears(),
      date_of_birth_month_option: renderMonth(),

      date_of_birth_year_option_not_require: renderYearNotRequire(),
      date_of_birth_month_option_not_require: renderMonthNotRequire(),

      date_of_birth_day_option: [],

      work_form_option: [
        { value: null, text: '選択してください', disabled: false },
        { value: 1, text: '正社員' },
        { value: 2, text: '契約社員' },
        { value: 3, text: '派遣社員' },
        { value: 4, text: 'その他' },
      ],

      japanese_level_options: [
        { value: 1, text: 'N1' },
        { value: 2, text: 'N2' },
        { value: 3, text: 'N3' },
        { value: 4, text: 'N4' },
        { value: 5, text: 'N5' },
        { value: 6, text: '資格なし' },
      ],

      full_name: '',
      full_name_furigana: '',
      gender: null,
      date_of_birth_year: '',
      date_of_birth_month: '',
      date_of_birth_day: '',
      work_form: null,
      work_form_part_time: '',
      japanese_level: null,

      final_education_timing_year: '',
      final_education_timing_month: '',
      final_education_classification: null,
      final_education_degree: null,
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

      telephone_phone_number_prefix: null,
      telephone_phone_number: '',
      mobile_phone_number_prefix: null,
      mobile_phone_number: '',

      mail_address: '',
      invalid_mail_error_text: '',

      address_city: '',
      address_district: '',
      address_number: '',
      address_other: '',
      hometown_address_city: '',
      hometown_address_district: '',
      hometown_address_number: '',
      hometown_address_other: '',

      isPassFirstTabValidation: true,
      isPassSecondTabValidation: true,
      isPassThirdTabValidation: true,
      isPassFourthTabValidation: true,
      isPassFifthTabValidation: true,

      other_files: [],

      error: {
        full_name: true,
        full_name_furigana: true,
        date_of_birth_year: true,
        date_of_birth_month: true,
        date_of_birth_day: true,
        japanese_level: true,

        final_education_timing_year: true,
        final_education_timing_month: true,
        final_education_classification: true,
        final_education_degree: true,
        major_classification: true,
        middle_classification: true,

        main_job_career_1_year_from: null,
        main_job_career_1_month_from: null,
        main_job_career_1_year_to: null,
        main_job_career_1_month_to: null,
        main_job_career_1_department: null,
        main_job_career_1_job_title: null,

        mail_address: true,

        cv_file: null,
        jd_file: null,
      },
      major_middle_list: [],
      main_job_career_list: [],

      entries: [],

      offers: [],

      interviews: [],

      results: [],
    };
  },
  computed: {
    job_title_manufacturingy_1() {
      let ORINAL_OPTIONS = [];
      this.main_job_career_list.map((item) => {
        if (item.id === this.main_job_career_1_department) {
          ORINAL_OPTIONS = item.job_info;
        }
      });

      const JOB_TITLE_MANUFACTURINGY_API = [];

      ORINAL_OPTIONS.forEach((item) => {
        JOB_TITLE_MANUFACTURINGY_API.push({
          value: item['id'],
          text: item['name_ja'],
          disabled: false,
        });
      });

      JOB_TITLE_MANUFACTURINGY_API.unshift({
        value: null,
        text: '選択してください',
        disabled: false,
      });

      return JOB_TITLE_MANUFACTURINGY_API;
    },
    job_title_manufacturingy_2() {
      let ORINAL_OPTIONS = [];
      this.main_job_career_list.map((item) => {
        if (item.id === this.main_job_career_2_department) {
          ORINAL_OPTIONS = item.job_info;
        }
      });

      const JOB_TITLE_MANUFACTURINGY_API = [];

      ORINAL_OPTIONS.forEach((item) => {
        JOB_TITLE_MANUFACTURINGY_API.push({
          value: item['id'],
          text: item['name_ja'],
          disabled: false,
        });
      });

      JOB_TITLE_MANUFACTURINGY_API.unshift({
        value: null,
        text: '選択してください',
        disabled: false,
      });

      return JOB_TITLE_MANUFACTURINGY_API;
    },
    job_title_manufacturingy_3() {
      let ORINAL_OPTIONS = [];
      this.main_job_career_list.map((item) => {
        if (item.id === this.main_job_career_3_department) {
          ORINAL_OPTIONS = item.job_info;
        }
      });

      const JOB_TITLE_MANUFACTURINGY_API = [];

      ORINAL_OPTIONS.forEach((item) => {
        JOB_TITLE_MANUFACTURINGY_API.push({
          value: item['id'],
          text: item['name_ja'],
          disabled: false,
        });
      });

      JOB_TITLE_MANUFACTURINGY_API.unshift({
        value: null,
        text: '選択してください',
        disabled: false,
      });

      return JOB_TITLE_MANUFACTURINGY_API;
    },
    deparment_options_1() {
      const ORINAL_OPTIONS = [...this.main_job_career_list];

      const DEPARMENT_OPTION_API = [];

      ORINAL_OPTIONS.forEach((item) => {
        DEPARMENT_OPTION_API.push({
          value: item['id'],
          text: item['name_ja'],
          disabled: false,
        });
      });

      DEPARMENT_OPTION_API.unshift({
        value: null,
        text: '選択してください',
        disabled: false,
      });

      return DEPARMENT_OPTION_API;
    },
    deparment_options_2() {
      const ORINAL_OPTIONS = [...this.main_job_career_list];

      const DEPARMENT_OPTION_API = [];

      ORINAL_OPTIONS.forEach((item) => {
        DEPARMENT_OPTION_API.push({
          value: item['id'],
          text: item['name_ja'],
          disabled: false,
        });
      });

      DEPARMENT_OPTION_API.unshift({
        value: null,
        text: '選択してください',
        disabled: false,
      });

      return DEPARMENT_OPTION_API;
    },
    deparment_options_3() {
      const ORINAL_OPTIONS = [...this.main_job_career_list];

      const DEPARMENT_OPTION_API = [];

      ORINAL_OPTIONS.forEach((item) => {
        DEPARMENT_OPTION_API.push({
          value: item['id'],
          text: item['name_ja'],
          disabled: false,
        });
      });

      DEPARMENT_OPTION_API.unshift({
        value: null,
        text: '選択してください',
        disabled: false,
      });

      return DEPARMENT_OPTION_API;
    },
    middle_classification_options_api() {
      let ORINAL_OPTIONS = [];
      this.major_middle_list.map((item) => {
        if (item.id === this.major_classification) {
          ORINAL_OPTIONS = item.job_info;
        }
      });

      const MIDDLE_CLASSIFICATION_OPTIONS_API = [];

      ORINAL_OPTIONS.forEach((item) => {
        MIDDLE_CLASSIFICATION_OPTIONS_API.push({
          value: item['id'],
          text: item['name_ja'],
          disabled: false,
        });
      });

      return MIDDLE_CLASSIFICATION_OPTIONS_API;
    },
    major_classification_options_api() {
      const ORINAL_OPTIONS = this.major_middle_list;

      const MAJOR_CLASSIFICATION_OPTIONS_API = [];

      ORINAL_OPTIONS.forEach((item) => {
        MAJOR_CLASSIFICATION_OPTIONS_API.push({
          value: item['id'],
          text: item['name_ja'],
          disabled: false,
        });
      });

      return MAJOR_CLASSIFICATION_OPTIONS_API;
    },
    final_education_degree_options() {
      const ORINAL_OPTIONS = [...final_education_degree_options];
      ORINAL_OPTIONS.shift();

      const FINAL_EDUCATION_DEGREE_OPTIONS = [];

      ORINAL_OPTIONS.forEach((item) => {
        FINAL_EDUCATION_DEGREE_OPTIONS.push({
          value: item['value']['id'],
          text: item['text'],
          disabled: false,
        });
      });

      return FINAL_EDUCATION_DEGREE_OPTIONS;
    },
    final_education_classification_options() {
      const ORINAL_OPTIONS = [...final_education_classification_options];
      ORINAL_OPTIONS.shift();

      const FINAL_EDUCATION_CLASSIFICATION_OPTIONS = [];

      ORINAL_OPTIONS.forEach((item) => {
        FINAL_EDUCATION_CLASSIFICATION_OPTIONS.push({
          value: item['value']['id'],
          text: item['text'],
          disabled: false,
        });
      });

      return FINAL_EDUCATION_CLASSIFICATION_OPTIONS;
    },
    phone_number_options() {
      const ORINAL_OPTIONS = [...phone_number_options_common];
      ORINAL_OPTIONS.shift();

      const PHONE_NUMBER_OPTIONS = [];

      ORINAL_OPTIONS.forEach((item) => {
        PHONE_NUMBER_OPTIONS.push({
          value: item['value']['id'],
          text: item['text'],
          disabled: false,
        });
      });

      PHONE_NUMBER_OPTIONS.unshift({
        value: null,
        text: '選択してください',
        disabled: false,
      });

      return PHONE_NUMBER_OPTIONS;
    },
    permissionCheck() {
      return this.$store.getters.permissionCheck;
    },
  },
  created() {
    this.handleInitData();
  },
  methods: {
    async handleInitData() {
      await this.getJopListOption();
      await this.handleGetDetailInformationHr();
    },
    getJopListOption() {
      this.getJobType(1);
      this.getJobType(2);
    },
    async getJobType(id) {
      const PARAM = {
        type: id,
      };
      await getJobTypeHr(PARAM).then((res) => {
        const { code, data } = res.data;
        if (code === 200) {
          if (id === 2) {
            this.major_middle_list = data;
          }
          if (id === 1) {
            this.main_job_career_list = data;
          }
        }
      });
    },

    handleChangeCountry(type_select, countryCode) {
      // this.selectedCountry = countryCode;
      if (type_select === 'telephone_phone_number_prefix') {
        this.telephone_phone_number_prefix = countryCode;
        if (!countryCode) {
          this.telephone_phone_number = '';
        }
      }
      if (type_select === 'mobile_phone_number_prefix') {
        this.mobile_phone_number_prefix = countryCode;
        if (!countryCode) {
          this.mobile_phone_number = '';
        }
      }
    },

    async handleGetDetailInformationHr() {
      if (this.HR_ID) {
        const URL = `${urlAPI.urlGetOneHr}${this.HR_ID}`;

        try {
          this.overlay.show = true;

          const response = await getOneHr(URL);

          if (response['code'] === 200) {
            const DATA = response['data'];

            this.vItems = DATA;

            this.cv_status = DATA['status'] === 1 ? 'public' : 'private';
            this.hr_status = DATA['status'];
            this.company_name_en = DATA['hr_org']['corporate_name_en'];
            this.company_name_jp = DATA['hr_org']['corporate_name_ja'];

            this.original_hr_id = DATA['hr_organization_id'];
            this.country_id = DATA['country_id'];

            this.full_name = DATA['full_name'];
            this.full_name_furigana = DATA['full_name_ja'];
            this.gender = DATA['gender'];
            this.date_of_birth_year = this.handleProcessDoB(
              DATA['date_of_birth']
            )[0];
            this.date_of_birth_month = this.handleProcessDoB(
              DATA['date_of_birth']
            )[1];

            await this.handleRenderDayInMonth(
              this.date_of_birth_year,
              this.date_of_birth_month
            );

            this.date_of_birth_day = this.handleProcessDoB(
              DATA['date_of_birth']
            )[2];
            this.work_form = DATA['work_form'];
            this.work_form_part_time = DATA['preferred_working_hours'];
            this.japanese_level = DATA['japanese_level'];

            this.final_education_timing_year = this.handleGetMonthDayInDate(
              DATA['final_education_date']
            )[0];
            this.final_education_timing_month = this.handleGetMonthDayInDate(
              DATA['final_education_date']
            )[1];
            this.final_education_classification =
              DATA['final_education_classification'];
            this.final_education_degree = DATA['final_education_degree'];
            this.major_classification = DATA['major_classification_id'];
            this.middle_classification = DATA['middle_classification_id'];

            if (DATA['main_jobs'][0]) {
              this.main_job_career_1_year_from = this.handleGetMonthDayInDate(
                DATA['main_jobs'][0]['main_job_career_date_from']
              )[0];
              this.main_job_career_1_month_from = this.handleGetMonthDayInDate(
                DATA['main_jobs'][0]['main_job_career_date_from']
              )[1];

              // this.main_job_career_1_time_now = false;
              // this.main_job_career_1_time_now =
              //   !DATA['main_jobs'][0]['main_job_career_date_to'];
              this.main_job_career_1_time_now =
                DATA['main_jobs'][0]['to_now'] === 'yes';
              this.main_job_career_1_year_to = this.handleGetMonthDayInDate(
                DATA['main_jobs'][0]['main_job_career_date_to']
              )[0];
              this.main_job_career_1_month_to = this.handleGetMonthDayInDate(
                DATA['main_jobs'][0]['main_job_career_date_to']
              )[1];

              this.main_job_career_1_department =
                DATA['main_jobs'][0]['department_id'];
              this.main_job_career_1_job_title = DATA['main_jobs'][0]['job_id'];
              this.main_job_career_1_textarea = DATA['main_jobs'][0]['detail'];
            }

            if (DATA['main_jobs'][1] !== undefined) {
              this.main_job_career_2_year_from = this.handleGetMonthDayInDate(
                DATA['main_jobs'][1]['main_job_career_date_from']
              )[0];
              this.main_job_career_2_month_from = this.handleGetMonthDayInDate(
                DATA['main_jobs'][1]['main_job_career_date_from']
              )[1];

              this.main_job_career_2_department =
                DATA['main_jobs'][1]['department_id'];
              this.main_job_career_2_job_title = DATA['main_jobs'][1]['job_id'];
              this.main_job_career_2_textarea = DATA['main_jobs'][1]['detail'];

              // this.main_job_career_2_time_now = false;
              // this.main_job_career_2_time_now =
              //   !DATA['main_jobs'][1]['main_job_career_date_to'];
              this.main_job_career_2_time_now =
                DATA['main_jobs'][1]['to_now'] === 'yes';
              this.main_job_career_2_year_to = this.handleGetMonthDayInDate(
                DATA['main_jobs'][1]['main_job_career_date_to']
              )[0];
              this.main_job_career_2_month_to = this.handleGetMonthDayInDate(
                DATA['main_jobs'][1]['main_job_career_date_to']
              )[1];
            }

            if (DATA['main_jobs'][2] !== undefined) {
              this.main_job_career_3_year_from = this.handleGetMonthDayInDate(
                DATA['main_jobs'][2]['main_job_career_date_from']
              )[0];
              this.main_job_career_3_month_from = this.handleGetMonthDayInDate(
                DATA['main_jobs'][2]['main_job_career_date_from']
              )[1];

              // this.main_job_career_3_time_now = false;
              // this.main_job_career_3_time_now =
              //   !DATA['main_jobs'][2]['main_job_career_date_to'];
              this.main_job_career_3_time_now =
                DATA['main_jobs'][2]['to_now'] === 'yes';
              this.main_job_career_3_year_to = this.handleGetMonthDayInDate(
                DATA['main_jobs'][2]['main_job_career_date_to']
              )[0];
              this.main_job_career_3_month_to = this.handleGetMonthDayInDate(
                DATA['main_jobs'][2]['main_job_career_date_to']
              )[1];

              this.main_job_career_3_department =
                DATA['main_jobs'][2]['department_id'];
              this.main_job_career_3_job_title = DATA['main_jobs'][2]['job_id'];
              this.main_job_career_3_textarea = DATA['main_jobs'][2]['detail'];
            }

            this.personal_pr_special_textarea =
              DATA['personal_pr_special_notes'];
            this.remarks_textarea = DATA['remarks'];

            this.telephone_phone_number_prefix = this.handlePhoneNumber(
              DATA['telephone_number']
            )[0];
            this.telephone_phone_number = this.handlePhoneNumber(
              DATA['telephone_number']
            )[1];

            this.mobile_phone_number_prefix = this.handlePhoneNumber(
              DATA['mobile_phone_number']
            )[0];
            this.mobile_phone_number = this.handlePhoneNumber(
              DATA['mobile_phone_number']
            )[1];

            this.mail_address = DATA['mail_address'];
            this.address_city = DATA['address_city'];
            this.address_district = DATA['address_district'];
            this.address_number = DATA['address_number'];
            this.address_other = DATA['address_other'];
            this.hometown_address_city = DATA['hometown_city'];
            this.hometown_address_district = DATA['home_town_district'];
            this.hometown_address_number = DATA['home_town_number'];
            this.hometown_address_other = DATA['home_town_other'];

            if (DATA['fileCV']) {
              this.cv_file = DATA['fileCV']['id'];
              this.cv_file_id = DATA['fileCV']['id'];
              this.cv_file_name = DATA['fileCV']['file_name'];
              this.cv_file_preview_src = this.handleFileLink(
                DATA['fileCV']['file_path']
              );
            }

            if (DATA['fileJob']) {
              this.jd_file = DATA['fileJob']['id'];
              this.jd_file_id = DATA['fileJob']['id'];
              this.jd_file_name = DATA['fileJob']['file_name'];
              this.jd_file_preview_src = this.handleFileLink(
                DATA['fileJob']['file_path']
              );
            }
            if (DATA['file_others']) {
              this.tranferFileOthers(DATA['file_others']);
            }

            if (DATA['entries']) {
              DATA['entries'].forEach((item) => {
                this.entries.push({
                  origin_status: item['status'],
                  status: this.handleTransformStatus(1, item['status']),
                  remarks: item['remarks'],
                  entry_date: this.handleTransformTimestamp(item['created_at']),
                });
              });
            }

            if (DATA['offers']) {
              DATA['offers'].forEach((item) => {
                this.offers.push({
                  origin_status: item['status'],
                  status: this.handleTransformStatus(2, item['status']),
                  remarks: item['remarks'],
                  entry_date: this.handleTransformTimestamp(item['created_at']),
                });
              });
            }

            if (DATA['interviews']) {
              DATA['interviews'].forEach((item) => {
                this.interviews.push({
                  origin_status: item['status_selection'],
                  status: this.handleTransformStatus(
                    3,
                    item['status_selection']
                  ),
                  remarks: item['remarks'],
                  entry_date: this.handleTransformTimestamp(item['created_at']),
                });
              });
            }

            if (DATA['results']) {
              DATA['results'].forEach((item) => {
                this.results.push({
                  origin_status: item['status_selection'],
                  status: this.handleTransformStatus(
                    4,
                    item['status_selection']
                  ),
                  remarks: item['remark'],
                  entry_date: this.handleTransformTimestamp(item['created_at']),
                });
              });
            }
          } else {
            if (
              [
                ROLE.TYPE_SUPER_ADMIN,
                ROLE.TYPE_HR_MANAGER,
                ROLE.TYPE_HR,
              ].includes(this.permissionCheck)
            ) {
              this.$router.push('/hr/list');
            } else if ((ROLE.TYPE_COMPANY_ADMIN, ROLE.TYPE_COMPANY)) {
              this.$router.push('/hr-search/list');
            }
          }
        } catch (error) {
          console.log(error);
        }
      }

      this.overlay.show = false;
    },

    tranferFileOthers(FILE_OTHERS) {
      const others_file = JSON.parse(FILE_OTHERS);
      // if (others_file.length > 0) {
      //   this.default_other_file_id = others_file[0]['file_id'];
      //   this.default_other_file_path = others_file[0]['file_path'];
      //   this.default_other_file_name = others_file[0]['name'];
      //   this.default_other_file_system_file_name = others_file[0]['name'];

      //   this.default_other_file_preview_src = this.handleFileLink(
      //     others_file[0]['file_path']
      //   );
      // }

      if (others_file.length > 0) {
        let idx = 0;
        const len = others_file.length;
        while (idx < len) {
          this.other_files.push({
            file_id: others_file[idx]['file_id'],
            file_path: others_file[idx]['file_path'],
            file_name: others_file[idx]['name'],
            system_file_name: others_file[idx]['name'],
            file_preview_url: this.handleFileLink(
              others_file[idx]['file_path']
            ),
          });

          idx++;
        }
      }
    },
    handleCancelHrEdit() {
      this.$router.push({ path: `/hr/detail/${this.HR_ID}` });
    },
    async handleSaveHr() {
      if (this.handleValidateFormData()) {
        const MAIN_JOBS = [];
        // case 1
        // if (this.main_job_career_1_year_from) {
        let MAIN_JOB_CAREER_1_TO = '';
        if (!this.main_job_career_1_time_now) {
          if (
            this.main_job_career_1_year_to &&
            this.main_job_career_1_month_to
          ) {
            MAIN_JOB_CAREER_1_TO = `${
              this.main_job_career_1_year_to
            }-${this.handleFormatMonthDay(this.main_job_career_1_month_to)}`;
          } else if (
            this.main_job_career_1_year_to &&
            !this.main_job_career_1_month_to
          ) {
            MAIN_JOB_CAREER_1_TO = `${this.main_job_career_1_year_to}-01`;
          } else if (
            !this.main_job_career_1_year_to &&
            this.main_job_career_1_month_to
          ) {
            MAIN_JOB_CAREER_1_TO = `-${this.handleFormatMonthDay(
              this.main_job_career_1_month_to
            )}`;
          } else {
            MAIN_JOB_CAREER_1_TO = '';
          }
        }

        MAIN_JOBS.push({
          main_job_career_date_from:
            this.main_job_career_1_year_from &&
            this.main_job_career_1_month_from
              ? `${
                this.main_job_career_1_year_from
              }-${this.handleFormatMonthDay(
                this.main_job_career_1_month_from
              )}`
              : this.main_job_career_1_year_from &&
                !this.main_job_career_1_month_from
                ? `${this.main_job_career_1_year_from}-01`
                : !this.main_job_career_1_year_from &&
                this.main_job_career_1_month_from
                  ? `-${this.handleFormatMonthDay(
                    this.main_job_career_1_month_from
                  )}`
                  : '',
          to_now: this.main_job_career_1_time_now ? 'yes' : 'no',
          // main_job_career_date_to: `${this.main_job_career_1_year_to}-${this.handleFormatMonthDay(this.main_job_career_1_month_to)}`,
          main_job_career_date_to: MAIN_JOB_CAREER_1_TO,
          department_id: this.main_job_career_1_department,
          job_id: this.main_job_career_1_job_title,
          detail: this.main_job_career_1_textarea,
        });
        // }

        // case 2
        // if (this.main_job_career_2_year_from) {
        const DATA_2 = {};

        if (
          this.main_job_career_2_year_from &&
          this.main_job_career_2_month_from
        ) {
          DATA_2['main_job_career_date_from'] = `${
            this.main_job_career_2_year_from
          }-${this.handleFormatMonthDay(this.main_job_career_2_month_from)}`;
        } else if (
          this.main_job_career_2_year_from &&
          !this.main_job_career_2_month_from
        ) {
          DATA_2[
            'main_job_career_date_from'
          ] = `${this.main_job_career_2_year_from}-01`;
        } else if (
          !this.main_job_career_2_year_from &&
          this.main_job_career_2_month_from
        ) {
          DATA_2['main_job_career_date_from'] = `-${this.handleFormatMonthDay(
            this.main_job_career_2_month_from
          )}`;
        } else {
          DATA_2['main_job_career_date_from'] = '';
        }

        if (!this.main_job_career_2_time_now) {
          DATA_2['to_now'] = 'no';
          if (
            this.main_job_career_2_year_to &&
            this.main_job_career_2_month_to
          ) {
            DATA_2['main_job_career_date_to'] = `${
              this.main_job_career_2_year_to
            }-${this.handleFormatMonthDay(this.main_job_career_2_month_to)}`;
          } else if (
            this.main_job_career_2_year_to &&
            !this.main_job_career_2_month_to
          ) {
            DATA_2[
              'main_job_career_date_to'
            ] = `${this.main_job_career_2_year_to}-01`;
          } else if (
            !this.main_job_career_2_year_to &&
            this.main_job_career_2_month_to
          ) {
            DATA_2['main_job_career_date_to'] = `-${this.handleFormatMonthDay(
              this.main_job_career_2_month_to
            )}`;
          } else {
            DATA_2['main_job_career_date_to'] = ``;
          }
        } else {
          DATA_2['to_now'] = 'yes';
          DATA_2['main_job_career_date_to'] = '';
        }

        DATA_2['department_id'] = this.main_job_career_2_department || null;
        DATA_2['job_id'] = this.main_job_career_2_job_title || null;
        DATA_2['detail'] = this.main_job_career_2_textarea || '';
        // console.log('DATA_2===>', DATA_2);
        MAIN_JOBS.push(DATA_2);
        // }

        // if (this.main_job_career_3_year_from) {
        const DATA_3 = {};

        if (
          this.main_job_career_3_year_from &&
          this.main_job_career_3_month_from
        ) {
          DATA_3['main_job_career_date_from'] = `${
            this.main_job_career_3_year_from
          }-${this.handleFormatMonthDay(this.main_job_career_3_month_from)}`;
        } else if (
          this.main_job_career_3_year_from &&
          !this.main_job_career_3_month_from
        ) {
          DATA_3[
            'main_job_career_date_from'
          ] = `${this.main_job_career_3_year_from}-01`;
        } else if (
          !this.main_job_career_3_year_from &&
          this.main_job_career_3_month_from
        ) {
          DATA_3['main_job_career_date_from'] = `-${this.handleFormatMonthDay(
            this.main_job_career_3_month_from
          )}`;
        } else {
          DATA_3['main_job_career_date_from'] = '';
        }

        if (!this.main_job_career_3_time_now) {
          DATA_3['to_now'] = 'no';
          if (
            this.main_job_career_3_year_to &&
            this.main_job_career_3_month_to
          ) {
            DATA_3['main_job_career_date_to'] = `${
              this.main_job_career_3_year_to
            }-${this.handleFormatMonthDay(this.main_job_career_3_month_to)}`;
          } else if (
            this.main_job_career_3_year_to &&
            !this.main_job_career_3_month_to
          ) {
            DATA_3[
              'main_job_career_date_to'
            ] = `${this.main_job_career_3_year_to}-01`;
          } else if (
            !this.main_job_career_3_year_to &&
            this.main_job_career_3_month_to
          ) {
            DATA_3['main_job_career_date_to'] = `-${this.handleFormatMonthDay(
              this.main_job_career_3_month_to
            )}`;
          } else {
            DATA_3['main_job_career_date_to'] = ``;
          }
        } else {
          DATA_3['to_now'] = 'yes';
          DATA_3['main_job_career_date_to'] = '';
        }

        // if (
        //   this.main_job_career_3_year_to &&
        //   this.main_job_career_3_month_to &&
        //   !this.main_job_career_3_time_now
        // ) {
        //   DATA_3['main_job_career_date_to'] = `${
        //     this.main_job_career_3_year_to
        //   }-${this.handleFormatMonthDay(this.main_job_career_3_month_to)}`;
        // } else {
        //   DATA_3['main_job_career_date_to'] =
        //     this.main_job_career_3_year_to ?? '';
        // }

        DATA_3['department_id'] = this.main_job_career_3_department || null;
        DATA_3['job_id'] = this.main_job_career_3_job_title || null;
        DATA_3['detail'] = this.main_job_career_3_textarea || '';

        MAIN_JOBS.push(DATA_3);
        // }

        let FILE_OTHERS = [];

        // this.other_files.unshift({
        //   system_file_name: this.default_other_file_system_file_name,
        //   file_path: this.default_other_file_path,
        //   file_id: this.default_other_file_id,
        // });

        if (this.other_files.length > 0) {
          if (this.other_files.length === 1 && !this.other_files[0].file_id) {
            FILE_OTHERS = null;
          } else {
            this.other_files.forEach((item) => {
              if (item['file_id']) {
                FILE_OTHERS.push({
                  name: item['system_file_name'],
                  file_id: item['file_id'],
                  file_path: item['file_path'],
                });
              }
            });
          }
        }

        const DATA = {
          country_id: this.country_id,
          hr_organization_id: this.original_hr_id,

          full_name: this.full_name,
          full_name_ja: this.full_name_furigana,
          gender: this.gender,
          date_of_birth: `${
            this.date_of_birth_year
          }-${this.handleFormatMonthDay(
            this.date_of_birth_month
          )}-${this.handleFormatMonthDay(this.date_of_birth_day)}`,
          work_form: this.work_form,
          preferred_working_hours: this.work_form_part_time,
          japanese_level: this.japanese_level,

          final_education_date: `${
            this.final_education_timing_year
          }-${this.handleFormatMonthDay(this.final_education_timing_month)}`,
          final_education_classification: this.final_education_classification,
          final_education_degree: this.final_education_degree,
          major_classification_id: this.major_classification,
          middle_classification_id: this.middle_classification,
          main_jobs: MAIN_JOBS,

          personal_pr_special_notes: this.personal_pr_special_textarea,
          remarks: this.remarks_textarea,

          telephone_number: this.telephone_phone_number
            ? this.handleMergePhoneNumber(
              this.telephone_phone_number_prefix,
              this.telephone_phone_number
            )
            : '',
          mobile_phone_number: this.mobile_phone_number
            ? this.handleMergePhoneNumber(
              this.mobile_phone_number_prefix,
              this.mobile_phone_number
            )
            : '',
          mail_address: this.mail_address,

          address_city: this.address_city,
          address_district: this.address_district,
          address_number: this.address_number,
          address_other: this.address_other,
          hometown_city: this.hometown_address_city,
          home_town_district: this.hometown_address_district,
          home_town_number: this.hometown_address_number,
          home_town_other: this.hometown_address_other,

          // status: this.cv_status === 'private' ? 2 : 1,
          status: this.hr_status,

          file_cv_id: this.cv_file_id,
          file_job_id: this.jd_file_id,
          file_others: FILE_OTHERS,
        };

        // console.log('DATA===>', DATA);

        const URL = `${urlAPI.urlEditHr}${this.HR_ID}`;

        const response = await editHr(URL, DATA);

        if (response['code'] === 200) {
          if (response['data'] === 'success') {
            MakeToast({
              variant: 'success',
              title: this.$t('SUCCESS'),
              content: response['message'],
            });
          } else {
            MakeToast({
              variant: 'warning',
              title: this.$t('WARNING'),
              content: response['message'],
            });
          }

          this.$router.push({ path: `/hr/detail/${this.HR_ID}` });
        } else {
          MakeToast({
            variant: 'danger',
            title: this.$t('DANGER'),
            content: response['message'],
          });
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
      if (this.full_name === '') {
        this.error.full_name = false;
      }

      if (this.full_name_furigana === '') {
        this.error.full_name_furigana = false;
      }

      if (this.date_of_birth_year === '') {
        this.error.date_of_birth_year = false;
      }

      if (this.date_of_birth_month === '') {
        this.error.date_of_birth_month = false;
      }

      if (this.date_of_birth_day === '') {
        this.error.date_of_birth_day = false;
      }

      if (this.japanese_level === null) {
        this.error.japanese_level = false;
      }

      if (this.final_education_timing_year === null) {
        this.error.final_education_timing_year = false;
      }

      if (this.final_education_timing_month === null) {
        this.error.final_education_timing_month = false;
      }

      if (this.final_education_classification === null) {
        this.error.final_education_classification = false;
      }

      if (this.final_education_degree === null) {
        this.error.final_education_degree = false;
      }

      if (this.major_classification === null) {
        this.error.major_classification = false;
      }

      if (this.middle_classification === null) {
        this.error.middle_classification = false;
      }

      // if (!this.main_job_career_1_year_from) {
      //   this.error.main_job_career_1_year_from = false;
      // }

      // if (!this.main_job_career_1_month_from) {
      //   this.error.main_job_career_1_month_from = false;
      // }

      // if (!this.main_job_career_1_time_now && !this.main_job_career_1_year_to) {
      //   this.error.main_job_career_1_year_to = false;
      // }

      // if (!this.main_job_career_1_time_now && !this.main_job_career_1_month_to) {
      //   this.error.main_job_career_1_month_to = false;
      // }

      // if (!this.main_job_career_1_department) {
      //   this.error.main_job_career_1_department = false;
      // }

      // if (!this.main_job_career_1_job_title) {
      //   this.error.main_job_career_1_job_title = false;
      // }

      // if (!this.main_job_career_1_textarea) {
      //   this.error.main_job_career_1_textarea = false;
      // }

      if (this.mail_address === '') {
        this.error.mail_address = false;
        this.invalid_mail_error_text = this.$t('VALIDATE.REQUIRED_TEXT');
      }

      if (this.mail_address && !this.isValidEmail(this.mail_address)) {
        this.error.mail_address = false;
        this.invalid_mail_error_text =
          'メールアドレスの形式が正しくありませんでした。';
      }

      // if (this.cv_file === null) {
      //   this.error.cv_file = false;
      // }

      // if (this.jd_file === null) {
      //   this.error.jd_file = false;
      // }
      if (this.cv_file_name === '選択されていません') {
        this.error.cv_file = false;
      }

      if (this.jd_file_name === '選択されていません') {
        this.error.jd_file = false;
      }

      const isPassValidation = this.checkIfHasFalseValue();

      return isPassValidation;
    },
    checkIfHasFalseValue() {
      const keys = Object.keys(this.error);
      const isHasError = keys.some((key) => this.error[key] === false);

      if (isHasError) {
        return false;
      } else {
        return true;
      }
    },
    calculateAge(dob) {
      const today = new Date();
      const birthDate = new Date(dob);

      let age = today.getFullYear() - birthDate.getFullYear();
      const monthDiff = today.getMonth() - birthDate.getMonth();

      if (
        monthDiff < 0 ||
        (monthDiff === 0 && today.getDate() < birthDate.getDate())
      ) {
        age--;
      }

      return age;
    },
    handlePublicCV() {
      if (this.hr_status === 3) {
        return;
      } else {
        this.cv_status = 'public';
        this.hr_status = 1;
      }
    },
    handlePrivateCV() {
      if (this.hr_status === 3) {
        return;
      } else {
        this.cv_status = 'private';
        this.hr_status = 2;
      }
    },
    handleOpenFileSelect(type, index) {
      const fileInputRef = `fileInputOtherFile${index}`;

      switch (type) {
        case 1:
          this.$refs.fileInputCV.click();
          break;
        case 2:
          this.$refs.fileInputJD.click();
          break;
        case 3:
          this.$refs.fileInputDefaultOtherFile.click();
          break;
        case 4:
          this.$nextTick(() => {
            this.$refs[fileInputRef][0].click();
          });
          break;
        default:
          break;
      }
    },
    handleFileSelect(type, event, index) {
      const SELECTED_FILE = event.target.files[0];
      if (!SELECTED_FILE) {
        return 0;
      }

      if (
        !FILE_CAN_UPLOAD.includes(SELECTED_FILE.type) ||
        SELECTED_FILE.size > LIMIT_FILE_SIZE.NORMAL_UPLOAD_FILE
      ) {
        MakeToast({
          variant: 'warning',
          title: this.$t('WARNING'),
          content: this.$t('VALIDATE.FILE_UPLOAD_ERORR'),
        });
        return;
      }
      if (SELECTED_FILE) {
        const PREVIEW_URL = URL.createObjectURL(SELECTED_FILE);
        const FILE_NAME = `${this.shortenString(
          SELECTED_FILE['name']
        )} (${this.bytesToMB(SELECTED_FILE['size'])}MB)`;

        switch (type) {
          case 1:
            this.cv_file = SELECTED_FILE;
            this.cv_file_preview_src = PREVIEW_URL;
            this.cv_file_name = FILE_NAME;

            this.handleUploadSingleFile(1, this.cv_file, this.cv_file_name);

            this.error.cv_file = true;
            break;
          case 2:
            this.jd_file = SELECTED_FILE;
            this.jd_file_preview_src = PREVIEW_URL;
            this.jd_file_name = FILE_NAME;

            this.handleUploadSingleFile(2, this.jd_file, this.jd_file_name);

            this.error.jd_file = true;
            break;
          case 3:
            this.default_other_file = SELECTED_FILE;
            this.default_other_file_preview_src = PREVIEW_URL;
            this.default_other_file_name = FILE_NAME;

            this.handleUploadSingleFile(
              3,
              this.default_other_file,
              this.default_other_file_name
            );

            break;
          case 4:
            this.other_files[index]['file'] = SELECTED_FILE;
            this.other_files[index]['file_preview_url'] = PREVIEW_URL;
            this.other_files[index]['file_name'] = FILE_NAME;

            this.handleUploadSingleFile(
              4,
              this.other_files[index]['file'],
              this.other_files[index]['file_name'],
              index
            );
            break;
          default:
            break;
        }
      }
    },
    handlePreview(type, index) {
      const LIST_FILE = [
        this.cv_file_preview_src,
        this.jd_file_preview_src,
        this.default_other_file_preview_src,
      ];

      this.other_files.forEach((item) => {
        LIST_FILE.push(item['file_preview_url']);
      });

      if (type <= 3) {
        if (LIST_FILE[type - 1]) {
          window.open(LIST_FILE[type - 1], '_blank');
        }
      } else {
        if (LIST_FILE[index + 3]) {
          window.open(LIST_FILE[index + 3], '_blank');
        }
      }
    },
    handleRemoveFile(type, index) {
      const fileInputRef = `fileInputOtherFile${index}`;

      switch (type) {
        case 1:
          this.cv_file = null;
          this.cv_file_name = '選択されていません';
          this.cv_file_preview_src = '';
          this.cv_file_path_from_response = '';

          this.$nextTick(() => {
            this.$refs['fileInputCV']['value'] = '';
          });
          break;
        case 2:
          this.jd_file = null;
          this.jd_file_name = '選択されていません';
          this.jd_file_preview_src = '';
          this.jd_file_path_from_response = '';

          this.$nextTick(() => {
            this.$refs['fileInputJD']['value'] = '';
          });
          break;
        case 3:
          this.default_other_file = null;
          this.default_other_file_id = '';
          this.default_other_file_name = '選択されていません';
          this.default_other_file_preview_src = '';

          this.$nextTick(() => {
            this.$refs['fileInputDefaultOtherFile']['value'] = '';
          });
          break;
        case 4:
          this.other_files[index]['file'] = null;
          this.other_files[index]['file_name'] = '選択されていません';
          this.other_files[index]['file_preview_url'] = '';

          this.$nextTick(() => {
            this.$refs[fileInputRef][0]['value'] = '';
          });
          break;
        default:
          break;
      }
    },
    handleDeleteFileOther(index) {
      this.other_files.splice(index, 1);
    },
    bytesToMB(bytes) {
      const megabytes = bytes / (1024 * 1024);
      return megabytes.toFixed(2);
    },
    shortenString(string) {
      if (string.length <= 20) {
        return string;
      } else {
        return string.slice(0, 20) + '...';
      }
    },
    handleAddOtherFile() {
      this.other_files.push({
        file: null,
        file_name: '選択されていません',
        file_preview_url: '',
        file_id: '',
        file_path: '',
        system_file_name: '',
      });

      const INDEX = this.other_files.length - 1;
      this.handleOpenFileSelect(4, INDEX);
    },
    async handleUploadSingleFile(type, file, model_file, index) {
      this.overlay_upload.show = true;
      try {
        const FORM_DATA = new FormData();

        // const file_type = '';

        FORM_DATA.append('file', file);
        // FORM_DATA.append('model_file', model_file);
        // FORM_DATA.append('file_type', file_type);

        const URL = `${urlAPI.urlUploadSingleFile}`;

        const response = await uploadSingleFile(URL, FORM_DATA);

        if (response['code'] === 200) {
          switch (type) {
            case 1:
              this.cv_file_id = response['data']['id'];
              this.cv_file_path = response['data']['file_path'];
              break;
            case 2:
              this.jd_file_id = response['data']['id'];
              this.jd_file_path = response['data']['file_path'];
              break;
            case 3:
              this.default_other_file_id = response['data']['id'];
              this.default_other_file_path = response['data']['file_path'];
              this.default_other_file_system_file_name =
                response['data']['file_name'];
              break;
            case 4:
              this.other_files[index]['file_id'] = response['data']['id'];
              this.other_files[index]['file_path'] =
                response['data']['file_path'];
              this.other_files[index]['system_file_name'] =
                response['data']['file_name'];
              break;
            default:
              break;
          }
        } else {
          MakeToast({
            variant: 'warning',
            title: this.$t('WARNING'),
            content: response['message'],
          });
          switch (type) {
            case 1:
              this.cv_file_name = '選択されていません';
              this.cv_file_id = null;
              this.cv_file = null;
              break;
            case 2:
              this.jd_file_name = '選択されていません';
              this.jd_file_id = null;
              this.jd_file = null;
              break;
            case 3:
              this.default_other_file = null;
              this.default_other_file_name = '選択されていません';
              this.default_other_file_id = null;
              break;
            default:
              break;
          }
        }
      } catch (error) {
        console.log(error);
      }
      this.overlay_upload.show = false;
    },
    handleRenderDayInMonth(year, month) {
      let result;

      if (year && month) {
        const daysInMonth = new Date(year, month, 0).getDate();
        const daysArray = Array.from({ length: daysInMonth }, (_, i) => i + 1);
        result = daysArray;
      } else {
        result = [];
      }

      this.date_of_birth_day_option = result;
    },
    handleSeparateDateTime(string) {
      let result = ['', '', ''];

      if (string) {
        const YEAR = parseInt(string.split('-')[0]);
        const MONTH = parseInt(string.split('-')[1]);
        const DAY = parseInt(string.split('-')[2]);

        result = [YEAR, MONTH, DAY];
      }

      return result;
    },
    handleReAssignFeedback(field, value) {
      if (field === 'full_name') {
        if (value) {
          this.error.full_name = true;
          this.isPassFirstTabValidation = true;
        } else {
          this.error.full_name = false;
        }
      } else if (field === 'full_name_furigana') {
        if (value) {
          this.error.full_name_furigana = true;
          this.isPassFirstTabValidation = true;
        } else {
          this.error.full_name_furigana = false;
        }
      } else if (field === 'date_of_birth_year') {
        if (value) {
          this.error.date_of_birth_year = true;
          this.isPassFirstTabValidation = true;
        } else {
          this.error.date_of_birth_year = false;
        }
      } else if (field === 'date_of_birth_month') {
        if (value) {
          this.error.date_of_birth_month = true;
          this.isPassFirstTabValidation = true;
        } else {
          this.error.date_of_birth_month = false;
        }
      } else if (field === 'date_of_birth_day') {
        if (value) {
          this.error.date_of_birth_day = true;
          this.isPassFirstTabValidation = true;
        } else {
          this.error.date_of_birth_day = false;
        }
      } else if (field === 'japanese_level') {
        if (value) {
          this.error.japanese_level = true;
          this.isPassFirstTabValidation = true;
        } else {
          this.error.japanese_level = false;
        }
      }

      if (field === 'final_education_timing_year') {
        if (value) {
          this.error.final_education_timing_year = true;
          this.isPassSecondTabValidation = true;
        } else {
          this.error.final_education_timing_year = false;
        }
      } else if (field === 'final_education_timing_month') {
        if (value) {
          this.error.final_education_timing_month = true;
          this.isPassSecondTabValidation = true;
        } else {
          this.error.final_education_timing_month = false;
        }
      } else if (field === 'final_education_classification') {
        if (value) {
          this.error.final_education_classification = true;
          this.isPassSecondTabValidation = true;
        } else {
          this.error.final_education_classification = false;
        }
      } else if (field === 'final_education_degree') {
        if (value) {
          this.error.final_education_degree = true;
          this.isPassSecondTabValidation = true;
        } else {
          this.error.final_education_degree = false;
        }
      } else if (field === 'major_classification') {
        if (value) {
          this.error.major_classification = true;
          this.isPassSecondTabValidation = true;
        } else {
          this.error.major_classification = false;
        }
      } else if (field === 'middle_classification') {
        if (value) {
          this.error.middle_classification = true;
          this.isPassSecondTabValidation = true;
        } else {
          this.error.middle_classification = false;
        }
      }

      // if (field === 'main_job_career_1_year_from') {
      //   if (value) {
      //     this.error.main_job_career_1_year_from = true;
      //     this.isPassSecondTabValidation = true;
      //   } else {
      //     this.error.main_job_career_1_year_from = false;
      //   }
      // } else if (field === 'main_job_career_1_month_from') {
      //   if (value) {
      //     this.error.main_job_career_1_month_from = true;
      //     this.isPassSecondTabValidation = true;
      //   } else {
      //     this.error.main_job_career_1_month_from = false;
      //   }
      // } else if (field === 'main_job_career_1_year_to') {
      //   if (value) {
      //     this.error.main_job_career_1_year_to = true;
      //     this.isPassSecondTabValidation = true;
      //   } else {
      //     this.error.main_job_career_1_year_to = false;
      //   }
      // } else if (field === 'main_job_career_1_month_to') {
      //   if (value) {
      //     this.error.main_job_career_1_month_to = true;
      //     this.isPassSecondTabValidation = true;
      //   } else {
      //     this.error.main_job_career_1_month_to = false;
      //   }
      // } else if (field === 'main_job_career_1_department') {
      //   if (value) {
      //     this.error.main_job_career_1_department = true;
      //     this.isPassSecondTabValidation = true;
      //   } else {
      //     this.error.main_job_career_1_department = false;
      //   }
      // } else if (field === 'main_job_career_1_job_title') {
      //   if (value) {
      //     this.error.main_job_career_1_job_title = true;
      //     this.isPassSecondTabValidation = true;
      //   } else {
      //     this.error.main_job_career_1_job_title = false;
      //   }
      // } else if (field === 'main_job_career_1_textarea') {
      //   if (value) {
      //     this.error.main_job_career_1_textarea = true;
      //     this.isPassSecondTabValidation = true;
      //   } else {
      //     this.error.main_job_career_1_textarea = false;
      //   }
      // }

      if (field === 'mail_address') {
        this.error.mail_address = null;
        if (value) {
          this.error.mail_address = true;
          this.isPassFourthTabValidation = true;
        } else {
          this.error.mail_address = false;
          this.invalid_mail_error_text = '入力してください。';
        }
      }
    },
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
    clearValueWhenDisable(event, field) {
      if (field === 'main-job-career-1') {
        this.main_job_career_1_time_now = event;

        if (event) {
          this.main_job_career_1_year_to = null;
          this.main_job_career_1_month_to = null;

          this.error.main_job_career_1_year_to = true;
          this.error.main_job_career_1_month_to = true;
        }
      } else if (field === 'main-job-career-2') {
        this.main_job_career_2_time_now = event;

        if (event) {
          this.main_job_career_2_year_to = null;
          this.main_job_career_2_month_to = null;

          this.error.main_job_career_1_year_to = true;
          this.error.main_job_career_1_month_to = true;
        }
      } else if (field === 'main-job-career-3') {
        this.main_job_career_3_time_now = event;

        if (event) {
          this.main_job_career_3_year_to = null;
          this.main_job_career_3_month_to = null;

          this.error.main_job_career_1_year_to = true;
          this.error.main_job_career_1_month_to = true;
        }
      }
    },
    isValidEmail(email) {
      const emailRegex =
        /^[a-zA-Z0-9._%+-]{1,50}@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
      return emailRegex.test(email);
    },
    isValidPhoneNumber(phoneNumber) {
      const phoneRegex = /^\d{1,50}$/;
      return phoneRegex.test(phoneNumber);
    },
    handleProcessDoB(date) {
      let result = ['', '', ''];

      if (date) {
        const DATE = date.split('-');
        const YYYY = DATE[0];
        const MM = this.handleFormatMonthDay(DATE[1]);
        const DD = this.handleFormatMonthDay(DATE[2]);

        if (YYYY && MM && DD) {
          result = [parseInt(YYYY), parseInt(MM), parseInt(DD)];
        }
      }

      return result;
    },
    handleGetMonthDayInDate(date) {
      let result = ['', ''];

      if (date) {
        const DATE = date.split('-');
        const YYYY = DATE[0];
        const MM = this.handleFormatMonthDay(DATE[1]);

        if (YYYY && MM) {
          result = [parseInt(YYYY), parseInt(MM)];
        }
      }

      return result;
    },
    handleFormatMonthDay(string) {
      let result;

      if (parseInt(string) < 10) {
        result = '0' + parseInt(string);
      } else {
        result = parseInt(string);
      }

      return result;
    },
    handlePhoneNumber(string) {
      let result = ['', ''];

      if (string) {
        string = string.replace(/\s/g, '');
        const countryCode = string.slice(0, 3);
        const restOfNumber = string.slice(3);
        // let formattedCountryCode = '';

        // if (countryCode) {
        //   formattedCountryCode = this.phone_number_options.find((item) => {
        //     return item['text'] === countryCode;
        //   });
        // }

        // if (formattedCountryCode && restOfNumber) {
        //   result = [formattedCountryCode['value'], restOfNumber];
        // }
        if (countryCode && restOfNumber) {
          result = [countryCode, restOfNumber];
        }
      }

      return result;
    },
    handleRenderTextByStatus(type, status) {
      if (type === 1) {
        switch (status) {
          case 1:
            return '申請中';
          case 2:
            return '却下';
          case 3:
            return '辞退';
          case 4:
            return '他社内定';
          default:
            break;
        }
      }

      if (type === 2) {
        switch (status) {
          case 1:
            return '申請中';
          case 2:
            return '辞退';
          default:
            break;
        }
      }

      if (type === 3) {
        switch (status) {
          case 1:
            return '書類通過';
          case 2:
            return 'オファー承認';
          case 3:
            return '一次合格';
          case 4:
            return '二次合格';
          case 5:
            return '三次合格';
          case 6:
            return '四次合格';
          case 7:
            return '五次合格';
          case 8:
            return '面接中止';
          case 9:
            return '面接辞退';
          default:
            break;
        }
      }

      if (type === 4) {
        switch (status) {
          case 1:
            return '内定';
          case 2:
            return '不合格';
          case 3:
            return '内定承諾';
          case 4:
            return '内定辞退';
          default:
            break;
        }
      }
    },
    handleMergePhoneNumber(prefix, number) {
      let result = '';

      if (prefix) {
        switch (prefix) {
          case '+84':
            result = `+84 ${number}`;
            break;
          case '+81':
            result = `+81 ${number}`;
            break;
          default:
            break;
        }
      }
      return result;
    },
    handleTransformStatus(type, status) {
      if (type === 1) {
        switch (status) {
          case 1:
            return 'grey-frame';
          case 2:
            return 'red-frame';
          case 3:
            return 'red-frame';
          case 4:
            return 'grey-frame';
          case 5:
          default:
            break;
        }
      }

      if (type === 2) {
        switch (status) {
          case 1:
            return 'grey-frame';
          case 2:
            return 'red-frame';
          default:
            break;
        }
      }

      if (type === 3) {
        switch (status) {
          case 1:
            return 'blue-frame';
          case 2:
            return 'blue-frame';
          case 3:
            return 'blue-frame';
          case 4:
            return 'blue-frame';
          case 5:
            return 'blue-frame';
          case 6:
            return 'blue-frame';
          case 7:
            return 'blue-frame';
          case 8:
            return 'red-frame';
          case 9:
            return 'red-frame';
          default:
            break;
        }
      }

      if (type === 4) {
        switch (status) {
          case 1:
            return 'grey-frame';
          case 2:
            return 'grey-frame';
          case 3:
            return 'blue-frame';
          case 4:
            return 'red-frame';
          default:
            break;
        }
      }
    },
    handleTransformTimestamp(string) {
      let result;

      if (string) {
        const YMD = string.split('T')[0];
        const Y = YMD.split('-')[0];
        const M = YMD.split('-')[1];
        const D = YMD.split('-')[2];

        if (Y && M && D) {
          result = `${Y}年${M}月${D}日`;
        }
      }

      return result;
    },
    handleFileLink(path) {
      const link = `${process.env.MIX_LARAVEL_TEST_URL}${path}`;
      return link;
    },
    handleChangeMainJobCareerDepartment(type) {
      if (type === 2) {
        this.main_job_career_2_job_title = null;
      }

      if (type === 3) {
        this.main_job_career_3_job_title = null;
      }
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/pages/Hr/Detail/HRDetail.scss/';
@import '@/pages/Hr/Detail/TabABasicInformation/TabABasicInformation.scss';
@import '@/pages/RegisterHrOrigin/RegisterHrOrigin.scss';

.file-content {
  max-width: 100%;
  word-break: break-word;
}
.infor-first,
.infor-company {
  overflow-wrap: anywhere;
}

.select-country-phone-number {
  background-color: #fff;
  border: 1px solid #adadad;
  border-radius: 3px;
  padding-right: 0;

  ::v-deep button {
    background: #fff;
    border: none;
    border-radius: 3px;

    :focus {
      background: none;
    }
  }
}

::v-deep .custom-b-link {
  cursor: pointer !important;
  font-size: 12px !important;
  color: #212529 !important;
  text-decoration: underline !important;
}

.disable-btn {
  border-radius: 10px;
  border: 1px solid #999;
  background: #fff;
  color: #999 !important;
}

.offer-active {
  border-radius: 10px;
  border: 1px solid #999;
  background: #666 !important;
  color: #ffffff !important;
}

.offer-disable {
  border-radius: 10px;
  border: 1px solid #d7d7d7 !important;
  background: #fff;
  color: #d7d7d7 !important;
}

.hr-edit-title {
  font-weight: 700;
  font-size: 24px;
  line-height: 29px;
  color: #000000;
}

.w-80 {
  width: 80%;
}

.w-20 {
  width: 20%;
}

.fs-12 {
  font-size: 12px;
}

.custome-selected {
  padding: 0.35rem !important;
}
</style>
