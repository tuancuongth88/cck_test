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
            <span class="title-text">{{ $t('JOB_DETAIL.JOB_DETAIL') }}</span>
          </b-col>

          <div class="control-area">
            <b-button class="mx-1 btn_back--custom" @click="handleBackList()">{{
              $t('BUTTON.BACK_TO_LIST')
            }}</b-button>
            <b-button class="btn_save--custom mx-1" @click="handleGoToEdit()">{{
              $t('EDIT')
            }}</b-button>
          </div>
        </b-col>
      </b-row>

      <b-row class="mb-4">
        <b-col cols="12">
          <b-card-group deck>
            <b-card :header="$t('TITLE.JOB_INFORMATION')">
              <b-list-group class="list-group-body">
                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray body-item-title"
                    >{{ $t('JOB_DETAIL.TITLE') }}</b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center my-2 body-item-text"
                    >{{ job_title }}</b-col>
                  </div>
                </b-list-group-item>

                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray body-item-title"
                    >{{ $t('JOB_DETAIL.COMPANY_NAME') }}</b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center my-2 body-item-text"
                    >{{ company_name }}</b-col>
                  </div>
                </b-list-group-item>

                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray body-item-title"
                    >{{ $t('JOB_DETAIL.APPLICATION_PERIOD') }}</b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center my-2 body-item-text"
                    >{{ formatDateTime(application_period_start) }} 〜
                      {{ formatDateTime(application_period_end) }}</b-col>
                  </div>
                </b-list-group-item>

                <!-- Row 3+ 職種 occupation -->
                <b-list-group-item class="p-0">
                  <div class="d-flex flex-wrap">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray body-item-title"
                    >
                      {{ $t('JOB_EDIT.OCCUPATION') }}
                    </b-col>

                    <b-col
                      cols="9"
                      class="w-100 d-flex flex-column justify-start align-center my-2"
                    >
                      <div
                        class="form-item-row__inputs d-flex justify-center align-center mt-2"
                      >
                        <span class="body-item-text">{{
                          $t('COMPANY.MAJOR_CLASSIFICATION')
                        }}</span>
                        <div class="d-flex justify-start align-center">
                          <span class="body-item-text">{{
                            major_classification
                          }}</span>
                        </div>
                      </div>

                      <div
                        class="form-item-row__inputs d-flex justify-center align-center mt-2"
                      >
                        <span class="body-item-text">{{
                          $t('COMPANY.MIDDLE_CLASSIFICATION')
                        }}</span>
                        <div class="d-flex justify-start align-center">
                          <span class="body-item-text">{{
                            middle_classification
                          }}</span>
                        </div>
                      </div>
                    </b-col>
                  </div>
                </b-list-group-item>

                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray body-item-title"
                    >
                      {{ $t('JOB_DETAIL.JOB_DESCRIPTION') }}
                    </b-col>

                    <b-col
                      cols="9"
                      class="d-flex flex-column my-2 body-item-text"
                    >
                      <span
                        v-for="(line, lineIndex) in job_description"
                        :key="lineIndex"
                        class="d-flex"
                      >
                        {{ line }}<br>
                      </span>
                    </b-col>
                  </div>
                </b-list-group-item>

                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray body-item-title"
                    >
                      {{ $t('JOB_DETAIL.APPLICATION_CONDITION') }}
                    </b-col>

                    <b-col
                      cols="9"
                      class="d-flex flex-column my-2 body-item-text"
                    >
                      <span
                        v-for="(line, lineIndex) in application_condition"
                        :key="lineIndex"
                        class="d-flex"
                      >
                        {{ line }}<br>
                      </span>
                    </b-col>
                  </div>
                </b-list-group-item>

                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray body-item-title"
                    >
                      {{ $t('JOB_DETAIL.APPLICATION_REQUIREMENT') }}
                    </b-col>

                    <b-col
                      cols="9"
                      class="d-flex flex-column my-2 body-item-text"
                    >
                      <span
                        v-for="(line, lineIndex) in application_requirement"
                        :key="lineIndex"
                        class="d-flex"
                      >
                        {{ line }}<br>
                      </span>
                    </b-col>
                  </div>
                </b-list-group-item>

                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray body-item-title"
                    >{{ $t('JOB_DETAIL.COUNTRY') }}</b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center my-2 body-item-text"
                    >{{ country }}</b-col>
                  </div>
                </b-list-group-item>

                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray body-item-title"
                    >{{ $t('JOB_DETAIL.REQUIRED_LANGUAGE_CONDITION') }}</b-col>

                    <b-col
                      cols="9"
                      class="d-flex align-items-center flex-row my-2 body-item-text"
                    >
                      <span
                        v-for="(item, index) in language_requirement_id"
                        :key="index"
                        class="d-flex mr-3"
                      >{{ genderLanguageJa(item) }}</span>
                    </b-col>
                  </div>
                </b-list-group-item>

                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray body-item-title"
                    >
                      {{ $t('JOB_DETAIL.OTHER_SKILL') }}
                    </b-col>

                    <b-col
                      cols="9"
                      class="d-flex flex-column my-2 body-item-text"
                    >
                      <span
                        v-for="(line, lineIndex) in preferred_skill"
                        :key="lineIndex"
                        class="d-flex"
                      >
                        {{ line }}<br>
                      </span>
                    </b-col>
                  </div>
                </b-list-group-item>

                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray body-item-title"
                    >
                      {{ $t('JOB_DETAIL.PREFERRED_SKILL') }}
                    </b-col>

                    <b-col
                      cols="9"
                      class="d-flex flex-column my-2 body-item-text"
                    >
                      <span
                        v-for="(line, lineIndex) in other_skill"
                        :key="lineIndex"
                        class="d-flex"
                      >
                        {{ line }}<br>
                      </span>
                    </b-col>
                  </div>
                </b-list-group-item>

                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray body-item-title"
                    >{{ $t('JOB_DETAIL.FORM_OF_EMPLOYMENT') }}</b-col>

                    <b-col
                      cols="9"
                      class="d-flex align-items-center my-2 body-item-text"
                    >{{ form_of_employment }}</b-col>
                  </div>
                </b-list-group-item>

                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray body-item-title"
                    >{{ $t('JOB_DETAIL.WORKING_TIME') }}</b-col>

                    <b-col
                      cols="9"
                      class="d-flex align-items-center my-2 body-item-text"
                    >
                      {{ working_time_from }} 〜 {{ working_time_to }}</b-col>
                  </div>
                </b-list-group-item>

                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray body-item-title"
                    >
                      {{ $t('JOB_DETAIL.VACATION') }}
                    </b-col>

                    <b-col
                      cols="9"
                      class="d-flex flex-column my-2 body-item-text"
                    >
                      <span
                        v-for="(line, lineIndex) in vacation"
                        :key="lineIndex"
                        class="d-flex"
                      >
                        {{ line }}<br>
                      </span>
                    </b-col>
                  </div>
                </b-list-group-item>

                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray body-item-title"
                    >{{ $t('JOB_DETAIL.EXPECTED_INCOME') }}</b-col>

                    <b-col
                      cols="9"
                      class="d-flex align-items-center my-2 body-item-text"
                    >
                      <span>{{ expected_income }}</span>
                      <span v-if="expected_income">{{
                        $t('JOB_SEARCH.TEN_THOUSAND_YEN')
                      }}</span>
                    </b-col>
                  </div>
                </b-list-group-item>

                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray body-item-title"
                    >{{ $t('JOB_DETAIL.WORKING_PLACE') }}</b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center my-2 body-item-text"
                    >{{ working_place }}</b-col>
                  </div>
                </b-list-group-item>

                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray body-item-title"
                    >
                      {{ $t('JOB_DETAIL.WORKING_PLACE_DETAIL') }}
                    </b-col>

                    <b-col
                      cols="9"
                      class="d-flex flex-column my-2 body-item-text"
                    >
                      <span
                        v-for="(line, lineIndex) in working_place_detail"
                        :key="lineIndex"
                        class="d-flex"
                      >
                        {{ line }}<br>
                      </span>
                    </b-col>
                  </div>
                </b-list-group-item>

                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray body-item-title"
                    >
                      {{ $t('JOB_DETAIL.TREATMENT_WELFARE') }}
                    </b-col>

                    <b-col
                      cols="9"
                      class="d-flex flex-column my-2 body-item-text"
                    >
                      <span
                        v-for="(line, lineIndex) in treatment_welfare"
                        :key="lineIndex"
                        class="d-flex"
                      >
                        {{ line }}<br>
                      </span>
                    </b-col>
                  </div>
                </b-list-group-item>

                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray body-item-title"
                    >{{ $t('JOB_DETAIL.COMPANY_PR_APPEAL') }}</b-col>

                    <b-col
                      cols="9"
                      class="d-flex flex-column my-2 body-item-text"
                    >
                      <span
                        v-for="(line, lineIndex) in company_pr_appeal"
                        :key="lineIndex"
                        class="d-flex"
                      >
                        {{ line }}<br>
                      </span>
                    </b-col>
                  </div>
                </b-list-group-item>

                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray body-item-title"
                    >{{ $t('JOB_DETAIL.BONUS_PAY_RISE') }}</b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center my-2 body-item-text"
                    >{{ bonus_pay }}</b-col>
                  </div>
                </b-list-group-item>

                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray body-item-title"
                    >{{ $t('JOB_DETAIL.OVER_TIME') }}</b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center my-2 body-item-text"
                    >{{ overtime }}</b-col>
                  </div>
                </b-list-group-item>

                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray body-item-title"
                    >{{ $t('JOB_DETAIL.TRANSFER') }}</b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center my-2 body-item-text"
                    >{{ transfer }}</b-col>
                  </div>
                </b-list-group-item>

                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray body-item-title"
                    >{{ $t('JOB_DETAIL.PASSIVE_SMOKING') }}</b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center my-2 body-item-text"
                    >{{ passive_smoking }}</b-col>
                  </div>
                </b-list-group-item>

                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray body-item-title"
                    >{{ $t('JOB_DETAIL.PASSION') }}</b-col>

                    <b-col
                      cols="9"
                      class="d-flex flex-wrap align-items-center my-2"
                    >
                      <b-form-checkbox
                        v-model="housing_allowance"
                        button
                        size="sm"
                        class="mx-2 my-1"
                        name="check-button"
                        disabled
                        :button-variant="
                          housing_allowance ? 'actived' : 'deactivated'
                        "
                      >
                        <span>{{
                          $t('JOB_DETAIL.PASSION_CHILD_HOUSING_ALLOWANCE')
                        }}</span>
                      </b-form-checkbox>

                      <b-form-checkbox
                        v-model="welfare_enhancement"
                        button
                        size="sm"
                        class="mx-2 my-1"
                        name="check-button"
                        disabled
                        :button-variant="
                          welfare_enhancement ? 'actived' : 'deactivated'
                        "
                      >
                        <span>{{
                          $t('JOB_DETAIL.PASSION_CHILD_WELFARE_ENHANCEMENT')
                        }}</span>
                      </b-form-checkbox>

                      <b-form-checkbox
                        v-model="severance_pay"
                        button
                        size="sm"
                        class="mx-2 my-1"
                        name="check-button"
                        disabled
                        :button-variant="
                          severance_pay ? 'actived' : 'deactivated'
                        "
                      >
                        <span>{{
                          $t('JOB_DETAIL.PASSION_CHILD_SEVERANCE_PAY')
                        }}</span>
                      </b-form-checkbox>

                      <b-form-checkbox
                        v-model="more_annual_holidays"
                        button
                        size="sm"
                        class="mx-2 my-1"
                        name="check-button"
                        disabled
                        :button-variant="
                          more_annual_holidays ? 'actived' : 'deactivated'
                        "
                      >
                        <span>{{
                          $t('JOB_DETAIL.PASSION_CHILD_OR_MORE_ANNUAL_HOLIDAYS')
                        }}</span>
                      </b-form-checkbox>

                      <b-form-checkbox
                        v-model="residence"
                        button
                        size="sm"
                        class="mx-2 my-1"
                        name="check-button"
                        disabled
                        :button-variant="residence ? 'actived' : 'deactivated'"
                      >
                        <span>{{
                          $t('JOB_DETAIL.PASSION_CHILD_RESIDENCE')
                        }}</span>
                      </b-form-checkbox>

                      <b-form-checkbox
                        v-model="no_experience_ok"
                        button
                        size="sm"
                        class="mx-2 my-1"
                        name="check-button"
                        disabled
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
                        name="check-button"
                        disabled
                        :button-variant="
                          foreign_managerial_staff_hired
                            ? 'actived'
                            : 'deactivated'
                        "
                      >
                        <span>{{ $t('JOB_DETAIL.PASSION_CHILD_HIRING') }}</span>
                      </b-form-checkbox>

                      <b-form-checkbox
                        v-model="remote_work_available"
                        button
                        size="sm"
                        class="mx-2 my-1"
                        name="check-button"
                        disabled
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
                        name="check-button"
                        disabled
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

                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray body-item-title"
                    >{{ $t('JOB_DETAIL.INTERVIEW_FOLLOW') }}</b-col>
                    <b-col
                      cols="9"
                      class="d-flex align-items-center my-2 body-item-text"
                    >{{ interview_follow }}</b-col>
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
                      class="d-flex align-items-center bg-gray body-item-title"
                    >{{ $t('JOB_DETAIL.ESTABLISHMENT_YEAR') }}
                    </b-col>

                    <b-col
                      cols="9"
                      class="d-flex align-items-center my-2 body-item-text"
                    >
                      <span v-if="establishment_year">{{
                        establishment_year
                      }}</span>
                    </b-col>
                  </div>
                </b-list-group-item>

                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray body-item-title"
                    >{{ $t('JOB_DETAIL.STARTUP_YEAR') }}
                    </b-col>

                    <b-col
                      cols="9"
                      class="d-flex align-items-center my-2 body-item-text"
                    >
                      <span v-if="startup_year">{{ startup_year }}</span>
                    </b-col>
                  </div>
                </b-list-group-item>

                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray body-item-title"
                    >{{ $t('JOB_DETAIL.CAPITAL') }}
                    </b-col>

                    <b-col
                      cols="9"
                      class="d-flex align-items-center my-2 body-item-text"
                    >
                      <span v-if="capital">{{ capital }}</span>
                    </b-col>
                  </div>
                </b-list-group-item>

                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray body-item-title"
                    >{{ $t('JOB_DETAIL.PROCEEDS') }}
                    </b-col>

                    <b-col
                      cols="9"
                      class="d-flex align-items-center my-2 body-item-text"
                    >
                      <span v-if="capital">{{ proceeds }}</span>
                    </b-col>
                  </div>
                </b-list-group-item>

                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray body-item-title"
                    >{{ $t('JOB_DETAIL.NUMBER_OF_STAFFS') }}
                    </b-col>

                    <b-col
                      cols="9"
                      class="d-flex align-items-center my-2 body-item-text"
                    >
                      <span v-if="number_of_staffs">{{
                        number_of_staffs
                      }}</span>
                    </b-col>
                  </div>
                </b-list-group-item>

                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray body-item-title"
                    >{{ $t('JOB_DETAIL.NUMBER_OF_OPERATIONS') }}
                    </b-col>

                    <b-col
                      cols="9"
                      class="d-flex align-items-center my-2 body-item-text"
                    >
                      <span v-if="number_of_operations">{{
                        number_of_operations
                      }}</span>
                    </b-col>
                  </div>
                </b-list-group-item>

                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray body-item-title"
                    >{{ $t('JOB_DETAIL.NUMBER_OF_SHOPS') }}
                    </b-col>

                    <b-col
                      cols="9"
                      class="d-flex align-items-center my-2 body-item-text"
                    >
                      <span v-if="number_of_shops">{{ number_of_shops }}</span>
                    </b-col>
                  </div>
                </b-list-group-item>

                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray body-item-title"
                    >{{ $t('JOB_DETAIL.NUMBER_OF_FACTORIES') }}
                    </b-col>

                    <b-col
                      cols="9"
                      class="d-flex align-items-center my-2 body-item-text"
                    >
                      <span v-if="number_of_factories">{{
                        number_of_factories
                      }}</span>
                    </b-col>
                  </div>
                </b-list-group-item>

                <b-list-group-item class="p-0">
                  <div class="d-flex">
                    <b-col
                      cols="3"
                      class="d-flex align-items-center bg-gray body-item-title"
                    >{{ $t('JOB_DETAIL.FISCAL_YEAR') }}
                    </b-col>

                    <b-col
                      cols="9"
                      class="d-flex align-items-center my-2 body-item-text"
                    >
                      <span v-if="fiscal_year">{{ fiscal_year }}</span>
                    </b-col>
                  </div>
                </b-list-group-item>
              </b-list-group>
            </b-card>
          </b-card-group>
        </b-col>
      </b-row>
    </div>
  </b-overlay>
</template>

<script>
import { getOneJob } from '@/api/modules/job';
import { getListCompany } from '@/api/modules/company';
import { getClassificationListOccupation } from '@/api/modules/hr';
import { jaLevelOption } from '@/const/job';
import { MakeToast } from '../../../utils/toastMessage';
import ROLE from '@/const/role.js';

const urlAPI = {
  urlGetOneJob: '/work',
  urlGetJobList: '/job-type',
  urlGetListCompany: '/company-option',
};

export default {
  name: 'JobDetail',
  components: {},
  data() {
    return {
      statusJob: '',
      ROLE: ROLE,

      overlay: {
        opacity: 1,
        show: false,
        blur: '1rem',
        rounded: 'sm',
        variant: 'light',
      },

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

      country: 'ベトナム',

      language_requirement_id: [],
      jaLevelOption: jaLevelOption,

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

      housing_allowance: false,
      welfare_enhancement: false,
      severance_pay: false,
      more_annual_holidays: false,
      residence: false,
      no_experience_ok: false,
      foreign_managerial_staff_hired: false,
      remote_work_available: false,
      moving_allowance: false,

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

      company_list: [],

      list_main_job_occupation: [],

      city_list: [
        { id: 1, name_en: 'Hokkaido', name_ja: '北海道' },
        { id: 2, name_en: 'Aomori', name_ja: '青森県' },
        { id: 3, name_en: 'Iwate', name_ja: '岩手県' },
        { id: 4, name_en: 'Miyagi', name_ja: '宮城県' },
        { id: 5, name_en: 'Akita', name_ja: '秋田県' },
        { id: 6, name_en: 'Yamagata', name_ja: '山形県' },
        { id: 7, name_en: 'Fukushima', name_ja: '福島県' },
        { id: 16, name_en: 'Toyama', name_ja: '富山県' },
        { id: 17, name_en: 'Ishikawa', name_ja: '石川県' },
        { id: 18, name_en: 'Fukui', name_ja: '福井県' },
        { id: 15, name_en: 'Niigata', name_ja: '新潟県' },
        { id: 19, name_en: 'Yamanashi', name_ja: '山梨県' },
        { id: 20, name_en: 'Nagano', name_ja: '長野県' },
        { id: 13, name_en: 'Tokyo', name_ja: '東京都' },
        { id: 14, name_en: 'Kanagawa', name_ja: '神奈川県' },
        { id: 12, name_en: 'Chiba', name_ja: '千葉県' },
        { id: 11, name_en: 'Saitama', name_ja: '埼玉県' },
        { id: 8, name_en: 'Ibaraki', name_ja: '茨城県' },
        { id: 9, name_en: 'Tochigi', name_ja: '栃木県' },
        { id: 10, name_en: 'Gunma', name_ja: '群馬県' },
        { id: 23, name_en: 'Aichi', name_ja: '愛知県' },
        { id: 22, name_en: 'Shizuoka', name_ja: '静岡県' },
        { id: 21, name_en: 'Gifu', name_ja: '岐阜県' },
        { id: 24, name_en: 'Mie', name_ja: '三重県' },
        { id: 27, name_en: 'Osaka', name_ja: '大阪府' },
        { id: 26, name_en: 'Kyoto', name_ja: '京都府' },
        { id: 28, name_en: 'Hyogo', name_ja: '兵庫県' },
        { id: 25, name_en: 'Shiga', name_ja: '滋賀県' },
        { id: 29, name_en: 'Nara', name_ja: '奈良県' },
        { id: 30, name_en: 'Wakayama', name_ja: '和歌山県' },
        { id: 34, name_en: 'Hiroshima', name_ja: '広島県' },
        { id: 33, name_en: 'Okayama', name_ja: '岡山県' },
        { id: 31, name_en: 'Tottori', name_ja: '鳥取県' },
        { id: 32, name_en: 'Shimane', name_ja: '島根県' },
        { id: 35, name_en: 'Yamaguchi', name_ja: '山口県' },
        { id: 36, name_en: 'Tokushima', name_ja: '徳島県' },
        { id: 37, name_en: 'Kagawa', name_ja: '香川県' },
        { id: 38, name_en: 'Ehime', name_ja: '愛媛県' },
        { id: 39, name_en: 'Kochi', name_ja: '高知県' },
        { id: 40, name_en: 'Fukuoka', name_ja: '福岡県' },
        { id: 43, name_en: 'Kumamoto', name_ja: '熊本県' },
        { id: 41, name_en: 'Saga', name_ja: '佐賀県' },
        { id: 42, name_en: 'Nagasaki', name_ja: '長崎県' },
        { id: 44, name_en: 'Ōita', name_ja: '大分県' },
        { id: 45, name_en: 'Miyazaki', name_ja: '宮崎県' },
        { id: 46, name_en: 'Kagoshima', name_ja: '鹿児島県' },
        { id: 47, name_en: 'Okinawa', name_ja: '沖縄県' },
      ],

      interview_follow_steps: [
        '一次面接',
        '二次面接',
        '三次面接',
        '四次面接',
        '五次面接',
      ],
    };
  },
  created() {
    this.handleInitComponentData();
  },
  methods: {
    async handleInitComponentData() {
      this.statusJob = this.$route.query.status;

      this.overlay.show = true;

      await this.handleGetListCompany();
      await this.handleGetListJobOccupation();
      await this.handleGetJobInformation();
    },
    async handleGetJobInformation() {
      const ID = this.$route.params['id'];

      if (ID) {
        try {
          const URL = `${urlAPI.urlGetOneJob}/${ID}`;

          const response = await getOneJob(URL);

          if (response['code'] === 200) {
            const DATA = response['data']['data'];

            this.job_title = DATA['title'];

            this.company_id = DATA['company']['company_id'];
            this.company_name =
              this.handleGetCompanyName(DATA['company']['company_id']) ||
              DATA['company']['company_name'];

            this.handleGetCompanyInfo(DATA['company_id']);

            this.application_period_start = DATA['application_period_from'];
            this.application_period_end = DATA['application_period_to'];

            this.major_classification = this.handleGetClassificaitions(
              DATA['major_classification_id'],
              DATA['middle_classification_id']
            )[0];
            this.middle_classification = this.handleGetClassificaitions(
              DATA['major_classification_id'],
              DATA['middle_classification_id']
            )[1];

            this.job_description = this.handleBreakLine(
              DATA['job_description']
            );

            this.application_condition = this.handleBreakLine(
              DATA['application_condition']
            );

            this.application_requirement = this.handleBreakLine(
              DATA['application_requirement']
            );

            // this.country = DATA['']; BE NOT HANDLE THIS FIELD

            this.language_requirement_id = DATA['language_requirements']
              .map((item) => item.id)
              .sort();

            this.other_skill = this.handleBreakLine(DATA['other_skill']);

            this.preferred_skill = this.handleBreakLine(
              DATA['preferred_skill']
            );

            this.form_of_employment = this.handleGetFormOfEmployee(
              DATA['form_of_employment']
            );

            this.vacation = this.handleBreakLine(DATA['vacation']);

            this.expected_income = DATA['expected_income'];

            this.working_time_from = DATA['working_time_from'];
            this.working_time_to = DATA['working_time_to'];

            this.working_place = this.handleGetWorkingPlace(DATA['city_id']);
            this.working_place_detail = this.handleBreakLine(
              DATA['working_palace_detail']
            );

            this.treatment_welfare = this.handleBreakLine(
              DATA['treatment_welfare']
            );

            this.company_pr_appeal = this.handleBreakLine(
              DATA['company_pr_appeal']
            );

            this.bonus_pay = this.handleTransformRadioOptions(
              DATA['bonus_pay_rise']
            );
            this.overtime = this.handleTransformRadioOptions(DATA['over_time']);
            this.transfer = this.handleTransformRadioOptions(DATA['transfer']);
            this.passive_smoking = this.handleTransformRadioOptions(
              DATA['passive_smoking']
            );

            this.passion = DATA['passion'];
            this.handleProcessSelectedPassions(DATA['passion']);

            this.interview_follow = this.handleRenderInterviewFlow(
              DATA['interview_follow']
            );
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
              text: item['company_name_jp'],
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
          this.establishment_year = DATA['data']['establishment_year'];
          this.startup_year = DATA['data']['startup_year'];
          this.capital = DATA['data']['capital'];
          this.proceeds = DATA['data']['proceeds'];
          this.number_of_staffs = DATA['data']['number_of_staffs'];
          this.number_of_operations = DATA['data']['number_of_operations'];
          this.number_of_shops = DATA['data']['number_of_shops'];
          this.number_of_factories = DATA['data']['number_of_factory'];
          this.fiscal_year = DATA['data']['fiscal'];
        }
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
    handleGoToEdit() {
      this.$router.push({
        path: `/job/edit/${this.$route.params.id}`,
      });
    },
    handleBackList() {
      const REVERT_ROUTER = this.$store.getters.revertRouter;
      if (REVERT_ROUTER) {
        this.$router.push({ path: REVERT_ROUTER.path });
      } else {
        this.$router.push({ path: `/job/list` });
      }
    },
    formatDateTime(date) {
      if (date) {
        const YYYY = date.split('-')[0];
        const MM = date.split('-')[1];
        const DD = date.split('-')[2];

        if (YYYY && MM && DD) {
          return `${YYYY}年${MM}月${DD}日`;
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
    handleGetClassificaitions(major_id, middle_id) {
      const result = ['', ''];

      if (major_id && middle_id) {
        this.list_main_job_occupation.find((item) => {
          if (item['value'] === parseInt(major_id)) {
            result[0] = item['text'];

            item['sub_options'].find((sub_item) => {
              if (sub_item['id'] === parseInt(middle_id)) {
                result[1] = sub_item['name_ja'];
              }
            });
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
      let result = '';

      if (working_place_id) {
        this.city_list.find((item) => {
          if (item['id'] === working_place_id) {
            result = item['name_ja'];
          }
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
    handleRenderInterviewFlow(step) {
      let result = '';

      if (step) {
        let index = 0;

        while (index < step) {
          result += this.interview_follow_steps[index] + '→';
          index++;
        }
      }

      return `${result}内定`;
    },

    genderLanguageJa(id) {
      const item = this.jaLevelOption.find((item) => item.key === id);
      if (item) {
        return item.value;
      } else {
        return '';
      }
    },

    handleBreakLine(paragraph) {
      let result;

      if (paragraph) {
        result = paragraph.split('\n');
      }

      return result;
    },
  },
};
</script>

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

.form-item-row__inputs {
  display: flex;
  align-items: center;
  justify-content: center;

  & > span {
    width: 10%;
    min-width: 60px;
  }

  & > div {
    width: 90%;
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

.body-item-text {
  font-size: 16px;
  color: #212529;
}

::v-deep .custom-control-label {
  line-height: 24px !important;
}

::v-deep .custom-control-input:checked ~ .custom-control-label::before {
  color: #212529 !important;
  border-color: #dddddd !important;
  background-color: #dddddd !important;
}

::v-deep
  .custom-checkbox
  .custom-control-input:disabled:checked
  ~ .custom-control-label::before {
  background-color: #dddddd !important;
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

.symbol {
  display: inline-block;
}
</style>
