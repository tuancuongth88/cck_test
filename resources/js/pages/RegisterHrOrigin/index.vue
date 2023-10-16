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
        <p style="margin-top: 10px">
          {{ $t('PLEASE_WAIT') }}
        </p>
      </div>
    </template>
    <!--  -->
    <div class="wrraper">
      <div class="hr-registration">
        <div class="hr-registration-container">
          <div class="hr-registration-page">
            <!-- 1 -->
            <div class="hr-registration-page__head">
              <div class="head-title">
                <!-- <span>{{ $t('TITLE_REGISTER_HR_H1') }}</span> -->
                <img
                  :src="require('@/assets/images/login/bd-logo.png')"
                  alt="bd-logo"
                  class="bd-logo"
                >
                <span>{{ $t('TITLE_REGISTER_HR_H2') }}</span>
              </div>
              <div class="head-description">
                <span>{{ $t('TITLE_REGISTER_HR_H3') }}</span>
              </div>
            </div>

            <!-- 2 PROCESS -->
            <div class="hr-registration-page__process">
              <span class="line-process" />
              <!-- Process 1: 基本情報の登録 basic information registration -->
              <div
                :class="
                  type_form === 'create'
                    ? 'status-active'
                    : 'status-none-active'
                "
              >
                {{ $t('HR_REGISTER.BASIC_INFORMATION_REGISTRATION') }}
              </div>
              <!-- Process 2: 入力内容の確認 confirm input data -->
              <div
                :class="
                  type_form === 'preview'
                    ? 'status-active'
                    : 'status-none-active'
                "
              >
                <span>{{ $t('HR_REGISTER.CONFIRM_INPUT_DATA') }}</span>
              </div>
              <!-- Process 3: 申請完了 application completed -->
              <div
                :class="
                  type_form === 'completed'
                    ? 'status-active'
                    : 'status-none-active'
                "
              >
                {{ $t('HR_REGISTER.APPLICATION_COMPLETED') }}
              </div>
            </div>

            <template v-if="type_form === 'completed'">
              <h4 class="text-center mt-5">{{ $t('SENT') }}</h4>
              <h4 class="text-center">
                {{ $t('WATING') }}
              </h4>
            </template>

            <!-- 3. FORM -->
            <div class="hr-registration-page-autox">
              <div class="hr-registration-page__form">
                <!-- 1 input 法人名 corporate name -->
                <div
                  v-if="type_form === 'create'"
                  class="row-item border-t border-l border-r"
                >
                  <div class="form-title d-flex justify-content-between">
                    <span>{{ $t('HR_REGISTER.CORPORATE_NAME') }} </span>
                    <Require />
                  </div>
                  <div class="form-inputs border-l">
                    <b-form-input
                      v-model="formData.corporate_name_en"
                      enabled
                      aria-describedby="corporate_name_en"
                      max-length="15"
                      dusk="corporate_name_en"
                      :name="'corporate_name_en'"
                      :formatter="format15characters"
                      :class="
                        error.corporate_name_en === false ? 'is-invalid' : ''
                      "
                      @input="handleChangeForm($event, 'corporate_name_en')"
                    />
                    <b-form-invalid-feedback
                      id="corporate_name_en"
                      :state="error.corporate_name_en"
                    >
                      {{ $t('VALIDATE.REQUIRED_TEXT') }}
                    </b-form-invalid-feedback>
                  </div>
                </div>
                <div
                  v-if="type_form === 'preview'"
                  class="row-item border-t border-l border-r"
                >
                  <div class="form-title">
                    {{ $t('HR_REGISTER.CORPORATE_NAME') }}
                  </div>
                  <div class="form-inputs border-l">
                    {{ formData.corporate_name_en }}
                  </div>
                </div>
                <!-- 2 input 法人名（日本語） corporate name(Japanese) -->
                <div
                  v-if="type_form === 'create'"
                  class="row-item border-t border-l border-r"
                >
                  <div class="form-title d-flex justify-content-between">
                    <span>{{ $t('HR_REGISTER.CORPORATE_NAME_JAPAN') }}</span><Require />
                  </div>
                  <div class="form-inputs border-l">
                    <b-form-input
                      v-model="formData.corporate_name_ja"
                      enabled
                      aria-describedby="corporate_name_ja"
                      max-length="50"
                      dusk="corporate_name_ja"
                      :name="'corporate_name_ja'"
                      :formatter="format50characters"
                      :class="
                        error.corporate_name_ja === false ? 'is-invalid' : ''
                      "
                      @input="handleChangeForm($event, 'corporate_name_ja')"
                    />
                    <b-form-invalid-feedback
                      id="corporate_name_ja"
                      :state="error.corporate_name_ja"
                    >
                      {{ $t('VALIDATE.REQUIRED_TEXT') }}
                    </b-form-invalid-feedback>
                  </div>
                </div>
                <div
                  v-if="type_form === 'preview'"
                  class="row-item border-t border-l border-r"
                >
                  <div class="form-title">
                    <span>{{ $t('HR_REGISTER.CORPORATE_NAME_JAPAN') }}</span>
                  </div>
                  <div class="form-inputs border-l">
                    {{ formData.corporate_name_ja }}
                  </div>
                </div>
                <!-- 3 input  ライセンス番号 License No. -->
                <div
                  v-if="type_form === 'create'"
                  class="row-item border-t border-l border-r"
                >
                  <div class="form-title d-flex justify-content-between">
                    <span>{{ $t('HR_REGISTER.LICENSE_NO') }}</span>
                    <Require />
                  </div>
                  <div class="form-inputs border-l">
                    <b-form-input
                      v-model="formData.license_no"
                      enabled
                      aria-describedby="license_no"
                      max-length="50"
                      dusk="license_no"
                      :name="'license_no'"
                      :formatter="format50characters"
                      :class="error.license_no === false ? 'is-invalid' : ''"
                      @input="handleChangeForm($event, 'license_no')"
                    />
                    <b-form-invalid-feedback
                      id="license_no"
                      :state="error.license_no"
                    >
                      {{ $t('VALIDATE.REQUIRED_TEXT') }}
                    </b-form-invalid-feedback>
                  </div>
                </div>
                <div
                  v-if="type_form === 'preview'"
                  class="row-item border-t border-l border-r"
                >
                  <div class="form-title">
                    <span>{{ $t('HR_REGISTER.LICENSE_NO') }}</span>
                  </div>
                  <div class="form-inputs border-l">
                    {{ formData.license_no }}
                  </div>
                </div>
                <!-- 4 option アカウント区分 Account classification  -->
                <div
                  v-if="type_form === 'create'"
                  class="row-item border-t border-l border-r"
                >
                  <div class="form-title d-flex justify-content-between">
                    <span>{{ $t('HR_REGISTER.ACCOUNT_CLASSIFICATION') }}</span><Require />
                  </div>
                  <div class="form-inputs border-l">
                    <b-form-select
                      v-model="formData.account_classification"
                      class="form-input px-2"
                      dusk="account_classification_option"
                      :options="account_classification_option"
                      value-field="key"
                      text-field="value"
                      :class="
                        error.account_classification === false
                          ? 'is-invalid'
                          : ''
                      "
                      @change="
                        handleChangeForm($event, 'account_classification')
                      "
                    />
                    <b-form-invalid-feedback
                      id="account_classification"
                      :state="error.account_classification"
                    >
                      {{ $t('VALIDATE.REQUIRED_SELECT') }}
                    </b-form-invalid-feedback>
                  </div>
                </div>
                <div
                  v-if="type_form === 'preview'"
                  class="row-item border-t border-l border-r"
                >
                  <div class="form-title">
                    {{ $t('HR_REGISTER.ACCOUNT_CLASSIFICATION') }}
                  </div>
                  <div class="form-inputs border-l">
                    {{
                      renderNameAccountClassification(
                        formData.account_classification
                      )
                    }}
                  </div>
                </div>
                <!-- 5 option 国 Country -->
                <div
                  v-if="type_form === 'create'"
                  class="row-item border-t border-l border-r"
                >
                  <div class="form-title d-flex justify-content-between">
                    <span>{{ $t('HR_REGISTER.COUNTRY') }}</span><Require />
                  </div>
                  <div class="form-inputs border-l">
                    <b-form-select
                      v-model="formData.country"
                      dusk="country_option"
                      :options="country_option"
                      value-field="key"
                      text-field="value"
                      :class="error.country === false ? 'is-invalid' : ''"
                      @change="handleChangeForm($event, 'country')"
                    />
                    <b-form-invalid-feedback
                      id="country"
                      :state="error.country"
                    >
                      {{ $t('VALIDATE.REQUIRED_SELECT') }}
                    </b-form-invalid-feedback>
                  </div>
                </div>
                <div
                  v-if="type_form === 'preview'"
                  class="row-item border-t border-l border-r"
                >
                  <div class="form-title">
                    {{ $t('HR_REGISTER.COUNTRY') }}
                  </div>
                  <div class="form-inputs border-l">
                    {{ renderNameCountry(formData.country) }}
                  </div>
                </div>
                <!-- 6 input 代表者氏名 Representative full name -->
                <div
                  v-if="type_form === 'create'"
                  class="row-item border-t border-l border-r"
                >
                  <div class="form-title d-flex justify-content-between">
                    <span>{{ $t('HR_REGISTER.REPRESENTATIVE_FULL_NAME') }}</span><Require />
                  </div>
                  <div class="form-inputs border-l">
                    <b-form-input
                      v-model="formData.representative_full_name"
                      enabled
                      aria-describedby="representative_full_name"
                      max-length="50"
                      dusk="representative_full_name"
                      :name="'representative_full_name'"
                      :formatter="format50characters"
                      :placeholder="''"
                      :class="
                        error.representative_full_name === false
                          ? 'is-invalid'
                          : ''
                      "
                      @input="
                        handleChangeForm($event, 'representative_full_name')
                      "
                    />
                    <b-form-invalid-feedback
                      id="representative_full_name"
                      :state="error.representative_full_name"
                    >
                      {{ $t('VALIDATE.REQUIRED_TEXT') }}
                    </b-form-invalid-feedback>
                  </div>
                </div>
                <div
                  v-if="type_form === 'preview'"
                  class="row-item border-t border-l border-r"
                >
                  <div class="form-title">
                    {{ $t('HR_REGISTER.REPRESENTATIVE_FULL_NAME') }}
                  </div>
                  <div class="form-inputs border-l">
                    {{ formData.representative_full_name }}
                  </div>
                </div>

                <!-- 7 input 代表者氏名（フリガナ）  Representative full name(furigana) -->
                <div
                  v-if="type_form === 'create'"
                  class="row-item border-t border-l border-r"
                >
                  <div class="form-title d-flex justify-content-between">
                    <div class="w-100 d-flex flex-column">
                      {{ $t('HR_REGISTER.REPRESENTATIVE_FULL_NAME_FURIGANA1') }}
                      <br>
                      {{ $t('HR_REGISTER.REPRESENTATIVE_FULL_NAME_FURIGANA2') }}
                    </div>
                    <Require />
                  </div>
                  <div class="form-inputs border-l">
                    <b-form-input
                      v-model="formData.representative_full_name_furigana"
                      enabled
                      aria-describedby="representative_full_name_furigana"
                      max-length="50"
                      dusk="representative_full_name_furigana"
                      :name="'representative_full_name_furigana'"
                      :formatter="format50characters"
                      :placeholder="''"
                      :class="
                        error.representative_full_name_furigana === false
                          ? 'is-invalid'
                          : ''
                      "
                      @input="
                        handleChangeForm(
                          $event,
                          'representative_full_name_furigana'
                        )
                      "
                    />
                    <b-form-invalid-feedback
                      id="representative_full_name_furigana"
                      :state="error.representative_full_name_furigana"
                    >
                      {{ $t('VALIDATE.REQUIRED_TEXT') }}
                    </b-form-invalid-feedback>
                  </div>
                </div>
                <div
                  v-if="type_form === 'preview'"
                  class="row-item border-t border-l border-r"
                >
                  <div class="form-title">
                    <div class="w-100 d-flex flex-column">
                      <span>
                        {{
                          $t('HR_REGISTER.REPRESENTATIVE_FULL_NAME_FURIGANA1')
                        }}
                      </span>
                      <span>
                        {{
                          $t('HR_REGISTER.REPRESENTATIVE_FULL_NAME_FURIGANA2')
                        }}
                      </span>
                    </div>
                  </div>
                  <div class="form-inputs border-l">
                    {{ formData.representative_full_name_furigana }}
                  </div>
                </div>
                <!-- 8 option input  代表者連絡先 Representative contact -->
                <div
                  v-if="type_form === 'create'"
                  class="row-item border-t border-l border-r"
                >
                  <div class="form-title d-flex justify-content-between">
                    <span>{{ $t('HR_REGISTER.REPRESENTATIVE_CONTACT') }}</span><Arbitrarily />
                  </div>
                  <div class="form-inputs border-l">
                    <div
                      class="w-100 d-flex justify-start align-center"
                      style="gap: 0.75rem"
                    >
                      <!-- Dropdown -->
                      <div class="h-100" style="height: 40px">
                        <div :class="'option-validate'">
                          <div class="option-area-code">
                            <b-dropdown
                              id="representative_contact"
                              dusk="representative_contact_option"
                              :text="display_area_code.representative_contact"
                              class="w-100 h-100"
                            >
                              <!-- BLANK -->
                              <b-dropdown-item
                                @click="
                                  pustAreaCode('representative_contact', '');
                                  handleChangeFormOption(
                                    $event,
                                    'representative_contact_id'
                                  );
                                "
                                @change="
                                  handleChangeForm(
                                    $event,
                                    'representative_contact_id'
                                  )
                                "
                              >
                                <span style="height: 28px" />
                              </b-dropdown-item>
                              <!-- VIE -->
                              <b-dropdown-item
                                dusk="representative_contact_vn"
                                @click="
                                  pustAreaCode('representative_contact', '+84');
                                  handleChangeFormOption(
                                    $event,
                                    'representative_contact_id'
                                  );
                                "
                                @change="
                                  handleChangeForm(
                                    $event,
                                    'representative_contact_id'
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
                                dusk="representative_contact_ja"
                                @click="
                                  pustAreaCode('representative_contact', '+81');
                                  handleChangeFormOption(
                                    $event,
                                    'representative_contact_id'
                                  );
                                "
                                @change="
                                  handleChangeForm(
                                    $event,
                                    'representative_contact_id'
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
                                display_area_code.representative_contact ===
                                  '+84'
                              "
                              :src="
                                require(`@/assets/images/icons/flag-84.png`)
                              "
                            >
                            <img
                              v-if="
                                display_area_code.representative_contact ===
                                  '+81'
                              "
                              :src="
                                require(`@/assets/images/icons/flag-81.png`)
                              "
                            >
                          </div>
                        </div>
                        <!-- <b-form-invalid-feedback
                          id="representative_contact"
                          :state="error.representative_contact_id"
                        >
                          {{ $t('VALIDATE.REQUIRED_SELECT') }}
                        </b-form-invalid-feedback> -->
                      </div>

                      <div class="w-100">
                        <b-form-input
                          v-model="formData.representative_contact"
                          aria-describedby="representative_contact"
                          type="number"
                          dusk="representative_contact"
                          :name="'representative_contact'"
                          :formatter="format15characters"
                          :placeholder="$t('COMPANY_REGISTER.PLACEHOLDER')"
                          :enabled="
                            display_area_code.representative_contact !== ''
                          "
                          :disabled="
                            display_area_code.representative_contact === ''
                          "
                          @input="
                            handleChangeForm($event, 'representative_contact')
                          "
                        />
                        <!-- <b-form-invalid-feedback
                          id="representative_contact"
                          :state="error.representative_contact"
                        >
                          {{ $t('VALIDATE.REQUIRED_TEXT') }}
                        </b-form-invalid-feedback> -->
                      </div>
                    </div>
                  </div>
                </div>
                <div
                  v-if="type_form === 'preview'"
                  class="row-item border-t border-l border-r"
                >
                  <div class="form-title">
                    <span>{{ $t('HR_REGISTER.REPRESENTATIVE_CONTACT') }}</span>
                  </div>
                  <div class="form-inputs border-l">
                    <div
                      class="w-100 d-flex justify-start align-center"
                      style="gap: 0.75rem"
                    >
                      {{ display_area_code.representative_contact }}
                      {{ formData.representative_contact }}
                    </div>
                  </div>
                </div>
                <!-- 9 option input 担当者連絡先 Assignee contact -->
                <div
                  v-if="type_form === 'create'"
                  class="row-item border-t border-l border-r"
                >
                  <div class="form-title d-flex justify-content-between">
                    <span>{{ $t('HR_REGISTER.ASSIGNEE_CONTACT') }}</span><Require />
                  </div>
                  <div class="form-inputs border-l">
                    <div
                      class="w-100 d-flex justify-start align-start"
                      style="gap: 0.75rem"
                    >
                      <!-- Dropdown -->
                      <div class="h-100" style="height: 40px">
                        <div
                          :class="
                            error.assignee_contact_id === false
                              ? 'option-error'
                              : 'option-validate'
                          "
                        >
                          <div class="option-area-code">
                            <b-dropdown
                              id="assignee_contact"
                              dusk="assignee_contact_option"
                              :text="display_area_code.assignee_contact"
                              class="w-100 h-100"
                            >
                              <!-- BLANK -->
                              <b-dropdown-item
                                @click="
                                  pustAreaCode('assignee_contact', '');
                                  handleChangeFormOption(
                                    $event,
                                    'assignee_contact_id'
                                  );
                                "
                                @change="
                                  handleChangeForm(
                                    $event,
                                    'assignee_contact_id'
                                  )
                                "
                              >
                                <span style="height: 28px" />
                              </b-dropdown-item>
                              <!-- VIE -->
                              <b-dropdown-item
                                dusk="assignee_contact_vn"
                                @click="
                                  pustAreaCode('assignee_contact', '+84');
                                  handleChangeFormOption(
                                    $event,
                                    'assignee_contact_id'
                                  );
                                "
                                @change="
                                  handleChangeForm(
                                    $event,
                                    'assignee_contact_id'
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
                                dusk="assignee_contact_ja"
                                @click="
                                  pustAreaCode('assignee_contact', '+81');
                                  handleChangeFormOption(
                                    $event,
                                    'assignee_contact_id'
                                  );
                                "
                                @change="
                                  handleChangeForm(
                                    $event,
                                    'assignee_contact_id'
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
                                display_area_code.assignee_contact === '+84'
                              "
                              :src="
                                require(`@/assets/images/icons/flag-84.png`)
                              "
                            >
                            <img
                              v-if="
                                display_area_code.assignee_contact === '+81'
                              "
                              :src="
                                require(`@/assets/images/icons/flag-81.png`)
                              "
                            >
                          </div>
                        </div>
                        <b-form-invalid-feedback
                          id="assignee_contact"
                          :state="error.assignee_contact_id"
                        >
                          {{ $t('VALIDATE.REQUIRED_SELECT') }}
                        </b-form-invalid-feedback>
                      </div>
                      <!-- Input -->
                      <div class="w-100">
                        <b-form-input
                          v-model="formData.assignee_contact"
                          aria-describedby="assignee_contact"
                          max-length="50"
                          type="number"
                          dusk="assignee_contact"
                          :name="'assignee_contact'"
                          :formatter="format15characters"
                          :placeholder="$t('COMPANY_REGISTER.PLACEHOLDER')"
                          :class="
                            error.assignee_contact === false ? 'is-invalid' : ''
                          "
                          :enabled="display_area_code.assignee_contact !== ''"
                          :disabled="display_area_code.assignee_contact === ''"
                          @input="handleChangeForm($event, 'assignee_contact')"
                        />
                        <b-form-invalid-feedback
                          id="assignee_contact"
                          :state="error.assignee_contact"
                        >
                          {{ $t('VALIDATE.REQUIRED_TEXT') }}
                        </b-form-invalid-feedback>
                      </div>
                    </div>
                  </div>
                </div>
                <div
                  v-if="type_form === 'preview'"
                  class="row-item border-t border-l border-r"
                >
                  <div class="form-title">
                    <span>{{ $t('HR_REGISTER.ASSIGNEE_CONTACT') }}</span>
                  </div>
                  <div class="form-inputs border-l">
                    <div
                      class="w-100 d-flex justify-start align-center"
                      style="gap: 0.75rem"
                    >
                      {{ display_area_code.assignee_contact }}
                      {{ formData.assignee_contact }}
                    </div>
                  </div>
                </div>

                <!-- 10 title-input 住所 Address -->
                <!-- 4 input 住所 Address -->
                <div
                  v-if="type_form === 'create'"
                  class="row-item border-t border-l border-r"
                >
                  <div class="form-title align-start d-flex justify-content-between">
                    <span>{{ $t('COMPANY_REGISTER.ADDRESS') }}</span><Require />
                  </div>
                  <div class="form-inputs border-l" style="gap: 1rem">
                    <!-- 4.1 input 郵便番号 post code -->
                    <div class="w-100 d-flex justify-start align-center">
                      <div style="width: 30%; min-width: 176px">
                        <div>
                          <span>{{ $t('COMPANY_REGISTER.POST_CODE') }}</span>
                        </div>
                      </div>
                      <!--  -->
                      <div class="w-100">
                        <b-form-input
                          v-model="formData.post_code"
                          enabled
                          aria-describedby="post_code"
                          max-length="50"
                          type="text"
                          dusk="post_code"
                          :name="'post_code'"
                          :formatter="format7characters"
                          :placeholder="''"
                          class="w-100"
                          :class="error.post_code === false ? 'is-invalid' : ''"
                          @input="handleChangeForm($event, 'post_code')"
                        />
                        <b-form-invalid-feedback
                          id="post_code"
                          :state="error.post_code"
                        >
                          {{ $t('VALIDATE.REQUIRED_TEXT') }}
                        </b-form-invalid-feedback>
                      </div>
                    </div>
                    <!-- 4.2 input 都道府県 prefectures -->
                    <div class="w-100 d-flex justify-start align-center">
                      <div style="width: 30%; min-width: 176px">
                        <div>
                          <span>{{ $t('COMPANY_REGISTER.PREFECTURES') }}</span>
                        </div>
                      </div>
                      <!--  -->
                      <div class="w-100">
                        <b-form-input
                          v-model="formData.prefectures"
                          enabled
                          aria-describedby="prefectures"
                          max-length="50"
                          dusk="prefectures"
                          :name="'prefectures'"
                          :formatter="format50characters"
                          :placeholder="''"
                          class="w-100"
                          :class="
                            error.prefectures === false ? 'is-invalid' : ''
                          "
                          @input="handleChangeForm($event, 'prefectures')"
                        />
                        <b-form-invalid-feedback
                          id="prefectures"
                          :state="error.prefectures"
                        >
                          {{ $t('VALIDATE.REQUIRED_TEXT') }}
                        </b-form-invalid-feedback>
                      </div>
                    </div>

                    <!-- 4.3 input 市区町村 municipality -->
                    <div class="w-100 d-flex justify-start align-center">
                      <div style="width: 30%; min-width: 176px">
                        <div>
                          <span>{{ $t('COMPANY_REGISTER.MUNICIPALITY') }}</span>
                        </div>
                      </div>
                      <!--  -->
                      <div class="w-100">
                        <b-form-input
                          v-model="formData.municipality"
                          enabled
                          aria-describedby="municipality"
                          max-length="50"
                          dusk="municipality"
                          :name="'municipality'"
                          :formatter="format50characters"
                          :placeholder="''"
                          class="w-100"
                          :class="
                            error.municipality === false ? 'is-invalid' : ''
                          "
                          @input="handleChangeForm($event, 'municipality')"
                        />
                        <b-form-invalid-feedback
                          id="municipality"
                          :state="error.municipality"
                        >
                          {{ $t('VALIDATE.REQUIRED_TEXT') }}
                        </b-form-invalid-feedback>
                      </div>
                    </div>

                    <!-- 4.4 input 番地 number -->
                    <div class="w-100 d-flex justify-start align-center">
                      <div style="width: 30%; min-width: 176px">
                        <div>
                          <span>{{ $t('COMPANY_REGISTER.NUMBER') }}</span>
                        </div>
                      </div>
                      <!--  -->
                      <div class="w-100">
                        <b-form-input
                          v-model="formData.number"
                          enabled
                          aria-describedby="number"
                          max-length="50"
                          dusk="number"
                          :name="'number'"
                          :formatter="format50characters"
                          :placeholder="''"
                          class="w-100"
                          :class="error.number === false ? 'is-invalid' : ''"
                          @input="handleChangeForm($event, 'number')"
                        />
                        <b-form-invalid-feedback
                          id="number"
                          :state="error.number"
                        >
                          {{ $t('VALIDATE.REQUIRED_TEXT') }}
                        </b-form-invalid-feedback>
                      </div>
                    </div>
                    <!-- 4.5 input その他 other -->
                    <div class="w-100 d-flex justify-start align-center">
                      <div style="width: 30%; min-width: 176px">
                        <div>
                          <span>{{ $t('COMPANY_REGISTER.OTHER') }}</span>
                        </div>
                      </div>
                      <!--  -->
                      <div class="w-100">
                        <b-form-input
                          v-model="formData.other"
                          enabled
                          aria-describedby="other"
                          max-length="50"
                          dusk="other"
                          :name="'other'"
                          :formatter="format50characters"
                          :placeholder="''"
                          class="w-100"
                          @input="handleChangeForm($event, 'other')"
                        />
                      </div>
                    </div>
                    <!--  -->
                  </div>
                </div>
                <div
                  v-if="type_form === 'preview'"
                  class="row-item border-t border-l border-r"
                >
                  <div
                    class="form-title align-start"
                    :class="{ 'bg-type-detail': type_form === 'preview' }"
                  >
                    <span>{{ $t('COMPANY_REGISTER.ADDRESS') }}</span>
                  </div>
                  <div class="form-inputs border-l" style="gap: 1rem">
                    <!-- 4.1 input 郵便番号 post code -->
                    <div class="w-100 d-flex justify-start align-center">
                      <div style="width: 30%; min-width: 176px">
                        <div>
                          <span>{{ $t('COMPANY_REGISTER.POST_CODE') }}</span>
                        </div>
                      </div>
                      <!--  -->
                      <div class="w-100 pl-5">
                        <div>
                          <span>{{ formData.post_code }}</span>
                        </div>
                      </div>
                    </div>
                    <!-- 4.2 input 都道府県 prefectures -->
                    <div class="w-100 d-flex justify-start align-center">
                      <div style="width: 30%; min-width: 176px">
                        <div>
                          <span>{{ $t('COMPANY_REGISTER.PREFECTURES') }}</span>
                        </div>
                      </div>
                      <!--  -->
                      <div class="w-100 pl-5">
                        <div>
                          <span>{{ formData.prefectures }}</span>
                        </div>
                      </div>
                    </div>

                    <!-- 4.3 input 市区町村 municipality -->
                    <div class="w-100 d-flex justify-start align-center">
                      <div style="width: 30%; min-width: 176px">
                        <div>
                          <span>{{ $t('COMPANY_REGISTER.MUNICIPALITY') }}</span>
                        </div>
                      </div>
                      <!--  -->
                      <div class="w-100 pl-5">
                        <div>
                          <span>{{ formData.municipality }}</span>
                        </div>
                      </div>
                    </div>

                    <!-- 4.4 input 番地 number -->
                    <div class="w-100 d-flex justify-start align-center">
                      <div style="width: 30%; min-width: 176px">
                        <div>
                          <span>{{ $t('COMPANY_REGISTER.NUMBER') }}</span>
                        </div>
                      </div>
                      <!--  -->
                      <div class="w-100 pl-5">
                        <div>
                          <span>{{ formData.number }}</span>
                        </div>
                      </div>
                    </div>
                    <!-- 4.5 input その他 other -->
                    <div class="w-100 d-flex justify-start align-center">
                      <div style="width: 30%; min-width: 176px">
                        <div>
                          <span>{{ $t('COMPANY_REGISTER.OTHER') }}</span>
                        </div>
                      </div>
                      <!--  -->
                      <div class="w-100 pl-5">
                        <div>
                          <span>{{ formData.other }}</span>
                        </div>
                      </div>
                    </div>
                    <!--  -->
                  </div>
                </div>

                <!-- 11 input メールアドレス  （ログインID） Mail address(Login ID) -->
                <div
                  v-if="type_form === 'create'"
                  class="row-item border-t border-l border-r"
                >
                  <div class="form-title d-flex justify-content-between">
                    <span>{{ $t('COMPANY_REGISTER.EMAIL_ADDRESS_LOGIN_ID') }}
                      <br>
                      {{ $t('HR_REGISTER.MAIL_ADDRESS2') }}
                    </span>
                    <Require />
                  </div>
                  <div class="form-inputs border-l">
                    <!-- aria-describedby="company_name" -->
                    <!-- id="company_name" -->
                    <b-form-input
                      v-model="formData.mail_address"
                      aria-describedby="mail_address"
                      max-length="50"
                      dusk="mail_address"
                      :name="'mail_address'"
                      :formatter="format50characters"
                      enabled
                      :class="error.mail_address === false ? 'is-invalid' : ''"
                      @input="handleChangeForm($event, 'mail_address')"
                    />
                    <b-form-invalid-feedback
                      id="mail_address"
                      :state="error.mail_address"
                    >
                      {{ $t('VALIDATE.REQUIRED_TEXT') }}
                    </b-form-invalid-feedback>
                  </div>
                </div>
                <div
                  v-if="type_form === 'preview'"
                  class="row-item border-t border-l border-r"
                >
                  <div
                    class="form-title"
                    :class="{ 'bg-type-detail': type_form === 'preview' }"
                  >
                    <span>{{
                      $t('COMPANY_REGISTER.EMAIL_ADDRESS_LOGIN_ID')
                    }}</span>
                  </div>
                  <div class="form-inputs border-l">
                    <div>
                      <span>{{ formData.mail_address }}</span>
                    </div>
                  </div>
                </div>

                <!-- 12 input URL -->
                <div
                  v-if="type_form === 'create'"
                  class="row-item border-t border-l border-r"
                >
                  <div class="form-title d-flex justify-content-between">
                    <span style="text-transform: capitalize">URL</span><Require />
                  </div>
                  <div class="form-inputs border-l">
                    <b-form-input
                      v-model="formData.url"
                      aria-describedby="url"
                      max-length="50"
                      dusk="url"
                      :name="'url'"
                      :formatter="format50characters"
                      :placeholder="''"
                      enabled
                      :class="error.url === false ? 'is-invalid' : ''"
                      @input="handleChangeForm($event, 'url')"
                    />
                    <b-form-invalid-feedback id="url" :state="error.url">
                      {{ $t('VALIDATE.REQUIRED_TEXT') }}
                    </b-form-invalid-feedback>
                  </div>
                </div>
                <div
                  v-if="type_form === 'preview'"
                  class="row-item border-t border-l border-r"
                >
                  <div
                    class="form-title"
                    :class="{ 'bg-type-detail': type_form === 'preview' }"
                  >
                    <span style="text-transform: capitalize">URL</span>
                  </div>
                  <div class="form-inputs border-l">
                    <div>
                      <span>{{ formData.url }}</span>
                    </div>
                  </div>
                </div>

                <!-- 13 input-upload 許可証 Certificate -->
                <div
                  v-if="type_form === 'create'"
                  class="row-item border-t border-l border-r border-b"
                >
                  <div class="form-title d-flex justify-content-between">
                    <span>{{ $t('HR_REGISTER.CERTIFICATE') }}</span><Require />
                  </div>
                  <!-- Status not yet import -->
                  <div
                    class="form-inputs flex-column justify-start align-start border-l"
                  >
                    <div
                      class="w-100 d-flex justify-start align-center"
                      style="gap: 0.5rem"
                    >
                      <div
                        class="btn hr-list-import__btn"
                        :class="!error.certificate_file_id ? 'border-red' : ''"
                      >
                        <label
                          for="upload-certificateFile"
                        >{{ $t('HR_REGISTER.SELECT_FILE') }}
                        </label>
                        <input
                          id="upload-certificateFile"
                          ref="CertificateFile"
                          dusk="upload-certificateFile"
                          :style="'display: none'"
                          type="file"
                          autocomplete="off"
                          @change="postCentificate"
                          @input="
                            handleChangeForm($event, 'certificate_file_id')
                          "
                        >
                      </div>
                      <!-- ファイル未選択 File not selected -->
                      <div class="noti-import-file">
                        {{ file_name ?? $t('HR_REGISTER.FILE_NOT_SELECTED') }}
                      </div>
                    </div>
                    <b-form-invalid-feedback
                      id="certificate_file_id"
                      :state="error.certificate_file_id"
                    >
                      {{ $t('VALIDATE.REQUIRED_SELECT') }}
                    </b-form-invalid-feedback>
                  </div>
                </div>
                <div
                  v-if="type_form === 'preview'"
                  class="row-item border-t border-l border-r border-b"
                >
                  <div class="form-title">
                    <span>{{ $t('HR_REGISTER.CERTIFICATE') }}</span>
                  </div>
                  <div
                    class="form-inputs flex-row justify-start align-center border-l"
                    style="gap: 0.5rem"
                  >
                    <div>
                      <span>{{ file_name }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div
              v-if="type_form === 'create'"
              ref="scrollTarget"
              class="hr-registration-page__btn"
            >
              <b-button
                id="btn-back"
                variant="secondary"
                size="lg"
                class="text-white"
                @click="handleToggleConfirmLeavingModal"
              >
                {{ $t('RETURN') }}
              </b-button>
              <b-button
                id="btn-next"
                variant="warning"
                size="lg"
                class="text-white"
                dusk="btn-next"
                @click="registerHRCheckValidate"
              >
                {{ $t('NEXT_BTN') }}
              </b-button>
            </div>

            <!-- 利用規約 Terms & Conditions -->
            <div
              v-if="type_form === 'preview'"
              class="hr-registration-page__form"
              style="margin-top: 2.25rem"
            >
              <div class="terms-vs-conditions">
                <div class="terms-vs-conditions-head">
                  <span>{{ $t('HR_REGISTER.TERMS_VS_CONDITIONS') }}</span>
                </div>
                <div
                  id="myDIV"
                  class="terms-vs-conditions-content"
                  @scroll="checkScroll"
                >
                  <!-- <div>{{ Terms & Conditions }}</div> -->
                  この利用規約（以下，「本規約」といいます。）は，シティコンピュータ株式会社（以下，「当社」といいます。）がこのウェブサイト上で提供するサービス（以下，「本サービス」といいます。）の利用条件を定めるものです。登録ユーザーの皆さま（以下，「ユーザー」といいます。）には，本規約に従って，本サービスをご利用いただきます。<br><br>
                  第1条（適用）<br>
                  １．本規約は，ユーザーと当社との間の本サービスの利用に関わる一切の関係に適用されるものとします。<br>
                  ２．当社は本サービスに関し，本規約のほか，ご利用にあたってのルール等，各種の定め（以下，「個別規定」といいます。）をすることがあります。これら個別規定はその名称のいかんに関わらず，本規約の一部を構成するものとします。<br>
                  ３．本規約の規定が個別規定の規定と矛盾する場合には，個別規定において特段の定めなき限り，個別規定の規定が優先されるものとします。<br>
                  <br>
                  第2条（利用登録）<br>
                  １．本サービスにおいては，登録希望者が本規約に同意の上，当社の定める方法によって利用登録を申請し，当社がこれを承認することによって，利用登録が完了するものとします。<br>
                  ２．当社は，利用登録の申請者に以下の事由があると判断した場合，利用登録の申請を承認しないことがあり，その理由については一切の開示義務を負わないものとします。<br>
                  ⑴利用登録の申請に際して虚偽の事項を届け出た場合<br>
                  ⑵本規約に違反したことがある者からの申請である場合<br>
                  ⑶その他，当社が利用登録を相当でないと判断した場合<br>
                  <br>
                  第3条（ユーザーIDおよびパスワードの管理）<br>
                  １．ユーザーは，自己の責任において，本サービスのユーザーIDおよびパスワードを適切に管理するものとします。<br>
                  ２．ユーザーは，いかなる場合にも，ユーザーIDおよびパスワードを第三者に譲渡または貸与し，もしくは第三者と共用することはできません。当社は，ユーザーIDとパスワードの組み合わせが登録情報と一致してログインされた場合には，そのユーザーIDを登録しているユーザー自身による利用とみなします。<br>
                  ３．ユーザーID及びパスワードが第三者によって使用されたことによって生じた損害は，当社に故意又は重大な過失がある場合を除き，当社は一切の責任を負わないものとします。<br><br>
                  第4条（利用料金および支払方法）<br>
                  １．ユーザーは，本サービスの有料部分の対価として，当社が別途定め，本ウェブサイトに表示する利用料金を，当社が指定する方法により支払うものとします。<br>
                  ２．ユーザーが利用料金の支払を遅滞した場合には，ユーザーは年14．6％の割合による遅延損害金を支払うものとします。<br><br>
                  第5条（禁止事項）<br>
                  ユーザーは，本サービスの利用にあたり，以下の行為をしてはなりません。<br>
                  ⑴法令または公序良俗に違反する行為 ⑵犯罪行為に関連する行為<br>
                  ⑶本サービスの内容等，本サービスに含まれる著作権，商標権ほか知的財産権を侵害する行為<br>
                  ⑷当社，ほかのユーザー，またはその他第三者のサーバーまたはネットワークの機能を破壊したり，妨害したりする行為<br>
                  ⑸本サービスによって得られた情報を商業的に利用する行為<br>
                  ⑹当社のサービスの運営を妨害するおそれのある行為<br>
                  ⑺不正アクセスをし，またはこれを試みる行為<br>
                  ⑻他のユーザーに関する個人情報等を収集または蓄積する行為<br>
                  ⑼不正な目的を持って本サービスを利用する行為<br>
                  ⑽本サービスの他のユーザーまたはその他の第三者に不利益，損害，不快感を与える行為<br>
                  ⑾他のユーザーに成りすます行為<br>
                  ⑿当社が許諾しない本サービス上での宣伝，広告，勧誘，または営業行為<br>
                  ⒀面識のない異性との出会いを目的とした行為<br>
                  ⒁当社のサービスに関連して，反社会的勢力に対して直接または間接に利益を供与する行為<br>
                  ⒂その他，当社が不適切と判断する行為<br><br>
                  第6条（本サービスの提供の停止等）<br>
                  １．当社は，以下のいずれかの事由があると判断した場合，ユーザーに事前に通知することなく本サービスの全部または一部の提供を停止または中断することができるものとします。<br>
                  ⑴本サービスにかかるコンピュータシステムの保守点検または更新を行う場合<br>
                  ⑵地震，落雷，火災，停電または天災などの不可抗力により，本サービスの提供が困難となった場合<br>
                  ⑶コンピュータまたは通信回線等が事故により停止した場合<br>
                  ⑷その他，当社が本サービスの提供が困難と判断した場合<br>
                  ２．当社は，本サービスの提供の停止または中断により，ユーザーまたは第三者が被ったいかなる不利益または損害についても，一切の責任を負わないものとします。<br><br>
                  第7条（利用制限および登録抹消）<br>
                  １．当社は，ユーザーが以下のいずれかに該当する場合には，事前の通知なく，ユーザーに対して，本サービスの全部もしくは一部の利用を制限し，またはユーザーとしての登録を抹消することができるものとします。<br>
                  ⑴本規約のいずれかの条項に違反した場合<br>
                  ⑵登録事項に虚偽の事実があることが判明した場合<br>
                  ⑶料金等の支払債務の不履行があった場合<br>
                  ⑷当社からの連絡に対し，一定期間返答がない場合<br>
                  ⑸本サービスについて，最終の利用から一定期間利用がない場合<br>
                  ⑹その他，当社が本サービスの利用を適当でないと判断した場合<br>
                  ２．当社は，本条に基づき当社が行った行為によりユーザーに生じた損害について，一切の責任を負いません。<br><br>
                  第8条（退会）<br>
                  ユーザーは，当社の定める退会手続により，本サービスから退会できるものとします。<br><br>
                  第9条（保証の否認および免責事項）<br>
                  １．当社は，本サービスに事実上または法律上の瑕疵（安全性，信頼性，正確性，完全性，有効性，特定の目的への適合性，セキュリティなどに関する欠陥，エラーやバグ，権利侵害などを含みます。）がないことを明示的にも黙示的にも保証しておりません。<br>
                  ２．当社は，本サービスに起因してユーザーに生じたあらゆる損害について、当社の故意又は重過失による場合を除き、一切の責任を負いません。ただし，本サービスに関する当社とユーザーとの間の契約（本規約を含みます。）が消費者契約法に定める消費者契約となる場合，この免責規定は適用されません。<br>
                  ３．前項ただし書に定める場合であっても，当社は，当社の過失（重過失を除きます。）による債務不履行または不法行為によりユーザーに生じた損害のうち特別な事情から生じた損害（当社またはユーザーが損害発生につき予見し，または予見し得た場合を含みます。）について一切の責任を負いません。また，当社の過失（重過失を除きます。）による債務不履行または不法行為によりユーザーに生じた損害の賠償は，ユーザーから当該損害が発生した月に受領した利用料の額を上限とします。<br>
                  ４．当社は，本サービスに関して，ユーザーと他のユーザーまたは第三者との間において生じた取引，連絡または紛争等について一切責任を負いません。<br><br>
                  第10条（サービス内容の変更等）<br>
                  当社は，ユーザーへの事前の告知をもって、本サービスの内容を変更、追加または廃止することがあり、ユーザーはこれを承諾するものとします。<br><br>
                  第11条（利用規約の変更）<br>
                  １．当社は以下の場合には、ユーザーの個別の同意を要せず、本規約を変更することができるものとします。<br>
                  ⑴本規約の変更がユーザーの一般の利益に適合するとき。<br>
                  ⑵本規約の変更が本サービス利用契約の目的に反せず、かつ、変更の必要性、変更後の内容の相当性その他の変更に係る事情に照らして合理的なものであるとき。<br>
                  ２．当社はユーザーに対し、前項による本規約の変更にあたり、事前に、本規約を変更する旨及び変更後の本規約の内容並びにその効力発生時期を通知します。<br><br>
                  第12条（個人情報の取扱い）<br>
                  当社は，本サービスの利用によって取得する個人情報については，当社「プライバシーポリシー」に従い適切に取り扱うものとします。<br><br>
                  第13条（通知または連絡）<br>
                  ユーザーと当社との間の通知または連絡は，当社の定める方法によって行うものとします。当社は,ユーザーから,当社が別途定める方式に従った変更届け出がない限り,現在登録されている連絡先が有効なものとみなして当該連絡先へ通知または連絡を行い,これらは,発信時にユーザーへ到達したものとみなします。<br><br>
                  第14条（権利義務の譲渡の禁止）<br>
                  ユーザーは，当社の書面による事前の承諾なく，利用契約上の地位または本規約に基づく権利もしくは義務を第三者に譲渡し，または担保に供することはできません。<br><br>
                  第15条（準拠法・裁判管轄）<br>
                  本規約の解釈にあたっては、日本法を準拠法とします。<br>
                  本サービスに関して紛争が生じた場合には，当社の本店所在地を管轄する裁判所を専属的合意管轄とします。<br><br>
                  以上<br>
                </div>
              </div>
            </div>

            <!-- 利用規約に同意する Agree with the Terms & Conditions -->
            <div
              v-if="type_form === 'preview'"
              class="agree-with-terms-conditions"
            >
              <b-form-checkbox
                id="checkbox-1"
                v-model="status_agree_with_terms_conditions"
                name="checkbox-1"
                dusk="checkbox-1"
                :value="true"
                :unchecked-value="false"
                :disabled="!isCheckboxEnabled"
              />
              <span>{{ $t('HR_REGISTER.AGREE_WITH_TERMS_CONDITIONS') }}</span>
            </div>

            <!-- 4 BTN 次へ next -->
            <div
              v-if="type_form === 'preview'"
              class="hr-registration-page__btn"
            >
              <b-button
                class="text-white btn-custome"
                size="lg"
                dusk="btn-back"
                @click="handleBack"
              >
                {{ $t('BUTTON.BTN_BACK_REGISTER') }}
              </b-button>
              <b-button
                id="btn-next-register"
                :disabled="!status_agree_with_terms_conditions"
                variant="warning"
                class="text-white"
                size="lg"
                dusk="btn-register"
                @click="registerHrPreview"
              >
                {{ $t('BUTTON.BTN_NEXT_REGISTER') }}
              </b-button>
            </div>

            <b-button
              v-if="type_form === 'completed'"
              variant=""
              size="lg"
              class="text-white mt-5 btn-custome"
              dusk="go_to_login"
              @click="goToLogin"
            >
              <span>{{ $t('GO_TO_LOGIN_SCREEN') }}</span>
            </b-button>
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
          <span>{{ $t('TITLE_QUESTION1') }}</span>
        </div>
      </template>
      <template #default>
        <div class="modal-body-confirm">
          <div class="w-100 d-flex justify-end align-center" style="gap: 10px">
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
import {
  account_classification_option,
  country_option,
} from '@/const/hrOrganization.js';
import { addHr } from '@/api/hr.js';
import { validEmail } from '@/utils/validate';
import Require from '@/components/Require/Require.vue';
import Arbitrarily from '@/components/Arbitrarily/Arbitrarily.vue';
import { MakeToast } from '@/utils/toastMessage';
import { renderOptionRequire } from '@/utils/renderOptionRequire';
import { uploadFile } from '@/api/uploadFile';
import { LIMIT_FILE_SIZE, FILE_TYPE } from '@/const/config.js';
const FILE_CAN_UPLOAD = [FILE_TYPE.PDF, FILE_TYPE.MP3, FILE_TYPE.MP4];
export default {
  name: 'HROrganizationUserRegistration',
  components: {
    Require,
    Arbitrarily,
  },

  data() {
    return {
      type_form: 'create',
      status_agree_with_terms_conditions: false,
      isCheckboxEnabled: false,
      scrollContainer: null,
      overlay: {
        show: false,
        variant: 'light',
        opacity: 0.2,
        blur: '1rem',
        rounded: 'sm',
      },
      statusModalConfirmLeaving: false,

      representative_contact_detail: '',
      representative_contact_put_api: '',

      display_area_code: {
        telephone_number: '',
        representative_contact: '',
        assignee_contact: '',
      },
      file_name: '',

      formData: {
        corporate_name_en: '',
        corporate_name_ja: '',
        license_no: '',
        account_classification: null,
        country: null,
        representative_full_name: '',
        representative_full_name_furigana: '',
        representative_contact: '',
        assignee_contact: '',
        post_code: '',
        prefectures: '',
        municipality: '',
        mail_address: '',
        number: '',
        other: '',
        url: '',
        certificate_file_id: '',
      },
      account_classification_option: renderOptionRequire(
        account_classification_option
      ),
      country_option: renderOptionRequire(country_option),

      error: {
        corporate_name_en: true,
        corporate_name_ja: true,
        license_no: true,
        account_classification: true,
        country: true,
        representative_full_name: true,
        representative_full_name_furigana: true,
        representative_contact: true,
        assignee_contact_id: true,
        assignee_contact: true,
        post_code: true,
        prefectures: true,
        municipality: true,
        number: true,
        mail_address: true,
        representative_contact_id: true,
        // other: true,
        url: true,
        certificate_file_id: true,
      },
      //
    };
  },

  created() {
    // this.postCentificate();
  },

  methods: {
    checkScroll() {
      var elmnt = document.getElementById('myDIV');
      // var x = elmnt.scrollLeft;
      var y = elmnt.scrollTop;
      // document.getElementById('demo').innerHTML =
      //   'Horizontally: ' + x + 'px<br>Vertically: ' + y + 'px';

      if (y > 2200) {
        this.isCheckboxEnabled = true;
      }
    },

    selectRenderText(type_select, id_select, option_select) {
      switch (type_select) {
        case 'account_classification':
          if (id_select) {
            let text = '';
            option_select.map((item) => {
              if (item.id === id_select) {
                text = item.text;
              }
            });
            this.formData.account_classification_text = text;
            return text;
          }
          break;
        // 2
        case 'country':
          if (id_select) {
            let text = '';
            option_select.map((item) => {
              if (item.id === id_select) {
                text = item.name_ja;
              }
            });
            this.formData.country_text = text;
            return text;
          }
          break;
        //
        default:
          break;
      }
    },

    checkEmail() {
      if (validEmail(this.formData.mail_address)) {
        return true;
      } else {
        return false;
      }
    },

    goToLogin() {
      this.$router.push({ path: `/login` }, (onAbort) => {});
    },

    handleBack() {
      this.type_form = 'create';
    },

    pustAreaCode(type_select, type_option) {
      switch (type_select) {
        case 'telephone_number':
          if (type_option) {
            this.display_area_code.telephone_number = String(type_option);
            this.telephone_number_put_api = String(type_option);
          } else {
            // Xóa các input
            this.display_area_code.telephone_number = '';
            this.formData.telephone_number = '';
            this.telephone_number_put_api = '';
            // Tắt validate
            this.error.telephone_number = true;
          }
          break;
        //
        case 'representative_contact':
          if (type_option) {
            this.display_area_code.representative_contact = String(type_option);
            this.representative_contact_put_api = String(type_option);
          } else {
            this.display_area_code.representative_contact = '';
            this.formData.representative_contact = '';
            this.representative_contact_put_api = '';
            // Tắt validate
            this.error.representative_contact = true;
          }
          break;
        //
        case 'assignee_contact':
          if (type_option) {
            this.display_area_code.assignee_contact = String(type_option);
            this.assignee_contact_put_api = String(type_option);
          } else {
            this.display_area_code.assignee_contact = '';
            this.formData.assignee_contact = '';
            this.assignee_contact_put_api = '';
            // Tắt validate
            this.error.assignee_contact = true;
          }
          break;
        //
        default:
          break;
      }
    },

    checkvalidate() {
      if (this.formData.corporate_name_en === '') {
        this.error.corporate_name_en = false;
      }
      if (this.formData.corporate_name_ja === '') {
        this.error.corporate_name_ja = false;
      }
      if (this.formData.license_no === '') {
        this.error.license_no = false;
      }
      if (
        this.formData.account_classification === '' ||
        this.formData.account_classification === null
      ) {
        this.error.account_classification = false;
      }
      if (this.formData.country === '' || this.formData.country === null) {
        this.error.country = false;
      }
      if (this.formData.representative_full_name === '') {
        this.error.representative_full_name = false;
      }
      if (this.formData.representative_full_name_furigana === '') {
        this.error.representative_full_name_furigana = false;
      }
      if (this.display_area_code.representative_contact === '') {
        // option khác
        this.error.representative_contact_id = false;
        this.error.representative_contact = true;
      } else if (this.display_area_code.representative_contact !== '') {
        if (!this.formData.representative_contact) {
          this.error.representative_contact = false;
        } else {
          this.error.representative_contact = true;
        }
      }
      // 10
      if (this.display_area_code.assignee_contact === '') {
        // option khác
        this.error.assignee_contact_id = false;
        this.error.assignee_contact = true;
      } else if (this.display_area_code.assignee_contact !== '') {
        if (!this.formData.assignee_contact) {
          this.error.assignee_contact = false;
        } else {
          this.error.assignee_contact = true;
        }
      }
      if (this.formData.post_code === '') {
        this.error.post_code = false;
      }
      if (this.formData.prefectures === '') {
        this.error.prefectures = false;
      }
      if (this.formData.municipality === '') {
        this.error.municipality = false;
      }
      if (this.formData.number === '') {
        this.error.number = false;
      }
      if (this.formData.mail_address === '') {
        this.error.mail_address = false;
      }
      if (this.formData.url === '') {
        this.error.url = false;
      }
      if (this.formData.certificate_file_id === '') {
        this.error.certificate_file_id = false;
      }
      if (this.formData.certificate_file_name_file === '') {
        this.error.certificate_file_id = false;
      }
      // True && all
      if (
        this.formData.corporate_name_ja !== '' &&
        this.formData.corporate_name_en !== '' &&
        this.formData.license_no !== '' &&
        this.formData.account_classification !== '' &&
        this.formData.country !== '' &&
        this.formData.representative_full_name !== '' &&
        this.formData.representative_full_name_furigana !== '' &&
        this.formData.assignee_contact_id !== '' &&
        this.formData.assignee_contact !== '' &&
        this.formData.post_code !== '' &&
        this.formData.prefectures !== '' &&
        this.formData.municipality !== '' &&
        this.formData.number !== '' &&
        // this.formData.other !== '' &&
        this.formData.mail_address !== '' &&
        this.formData.url !== '' &&
        this.formData.certificate_file_name_file !== '' &&
        this.formData.certificate_file_id !== '' // Quan trọng
      ) {
        return true;
      }
    },

    phone_put_api(area_code, string_phone) {
      const phone_convert = `${area_code} ${string_phone}`;
      return phone_convert;
    },

    format7characters(e) {
      const inputValue = String(e).substring(0, 7); // Giới hạn 7 ký tự

      // Loại bỏ các ký tự tiếng Nhật từ giá trị nhập vào
      const filteredValue = inputValue.replace(/[ぁ-んァ-ン一-龯。]/g, '');

      return filteredValue;
    },
    format50characters(e) {
      return String(e).substring(0, 50);
    },

    format15characters(e) {
      return String(e).substring(0, 15);
    },

    handleChangeFormOption(event, type_dropdown) {
      switch (type_dropdown) {
        // 9
        case 'representative_contact_id':
          this.error.representative_contact_id = true;
          if (event !== '') {
            this.error.representative_contact_id = true;
          } else {
            this.error.representative_contact_id = false;
          }
          break;
        // 10
        case 'assignee_contact_id':
          this.error.assignee_contact_id = true;
          if (event !== '') {
            this.error.assignee_contact_id = true;
          } else {
            this.error.assignee_contact_id = false;
          }
          break;
        default:
          break;
      }
    },

    handleChangeForm(event, field) {
      const newValue = event;
      switch (field) {
        case 'corporate_name_en':
          this.error.corporate_name_en = null;
          if (newValue) {
            this.error.corporate_name_en = true;
          } else {
            this.error.corporate_name_en = false;
          }
          break;
        // 2
        case 'corporate_name_ja':
          this.error.corporate_name_ja = null;
          if (newValue) {
            this.error.corporate_name_ja = true;
          } else {
            this.error.corporate_name_ja = false;
          }
          break;
        // 3
        case 'license_no':
          this.error.license_no = null;
          if (newValue) {
            this.error.license_no = true;
          } else {
            this.error.license_no = false;
          }
          break;
        // 4
        case 'account_classification':
          this.error.account_classification = null;
          if (newValue) {
            this.error.account_classification = true;
          } else {
            this.error.account_classification = false;
          }
          break;
        // 5
        case 'country':
          this.error.country = null;
          if (newValue) {
            this.error.country = true;
          } else {
            this.error.country = false;
          }
          break;
        // 6
        case 'representative_full_name':
          this.error.representative_full_name = null;
          if (newValue) {
            this.error.representative_full_name = true;
          } else {
            this.error.representative_full_name = false;
          }
          break;
        // 7
        case 'representative_full_name_furigana':
          this.error.representative_full_name_furigana = null;
          if (newValue) {
            this.error.representative_full_name_furigana = true;
          } else {
            this.error.representative_full_name_furigana = false;
          }
          break;
        // 8
        // case 'representative_contact':
        //   this.error.representative_contact = null;
        //   if (newValue) {
        //     this.error.representative_contact = true;
        //   } else {
        //     this.error.representative_contact = false;
        //   }
        //   break;
        // 9
        case 'assignee_contact_id':
          this.error.assignee_contact_id = null;
          if (newValue) {
            this.error.assignee_contact_id = true;
          } else {
            this.error.assignee_contact_id = false;
          }
          break;
        case 'assignee_contact':
          this.error.assignee_contact = null;
          if (newValue) {
            this.error.assignee_contact = true;
          } else {
            this.error.assignee_contact = false;
          }
          break;
        // 10
        case 'post_code':
          this.error.post_code = null;
          if (newValue) {
            this.error.post_code = true;
          } else {
            this.error.post_code = false;
          }
          break;
        // 11
        case 'prefectures':
          this.error.prefectures = null;
          if (newValue) {
            this.error.prefectures = true;
          } else {
            this.error.prefectures = false;
          }
          break;
        // 12
        case 'municipality':
          this.error.municipality = null;
          if (newValue) {
            this.error.municipality = true;
          } else {
            this.error.municipality = false;
          }
          break;
        // 13
        case 'number':
          this.error.number = null;
          if (newValue) {
            this.error.number = true;
          } else {
            this.error.number = false;
          }
          break;
        // 14 Not Require
        // case 'other':
        //   this.error.other = null;
        //   if (newValue) {
        //     this.error.other = true;
        //   } else {
        //     this.error.other = false;
        //   }
        //   break;
        // 15
        case 'mail_address':
          this.error.mail_address = null;
          if (newValue) {
            this.error.mail_address = true;
          } else {
            this.error.mail_address = false;
          }
          break;
        // 16
        case 'url':
          this.error.url = null;
          if (newValue) {
            this.error.url = true;
          } else {
            this.error.url = false;
          }
          break;
        // 17
        case 'certificate_file_id':
          this.error.certificate_file_id = null;
          if (newValue) {
            this.error.certificate_file_id = true;
          } else {
            this.error.certificate_file_id = false;
          }
          break;
        default:
          break;
      }
    },

    renderNameAccountClassification(id) {
      const findItem = this.account_classification_option.find(
        (item) => item.key === id
      );
      if (findItem) {
        return findItem.value;
      }
    },

    async registerHRCheckValidate() {
      this.checkvalidate();
      const resCheckvalidate = this.checkvalidate();
      const resCheckEmail = this.checkEmail();

      if (resCheckvalidate) {
        if (!resCheckEmail) {
          MakeToast({
            variant: 'warning',
            title: 'warning',
            content: this.$t('PLEASE_ENTER_THE_CORRECT_EMAIL_ADDRESS_FORMAT'),
          });
        }
      } else {
        MakeToast({
          variant: 'warning',
          title: 'warning',
          content: this.$t('HR_REGISTER.PLEASE_ENTER_ALL_REQUIRED_INFORMATION'),
        });
      }

      if (resCheckvalidate && resCheckEmail) {
        const createFormData = {
          ...this.formData,
          representative_contact: this.phone_put_api(
            this.display_area_code.representative_contact,
            this.formData.representative_contact
          ),
          assignee_contact: this.phone_put_api(
            this.display_area_code.assignee_contact,
            this.formData.assignee_contact
          ),
          is_create: 0,
        };

        // Check validate
        try {
          const response = await addHr(createFormData);
          const { code, message } = response.data;

          if (code === 200) {
            this.type_form = 'preview';
          } else {
            MakeToast({
              variant: 'warning',
              title: 'warning',
              content: message,
            });
          }
        } catch (error) {
          console.log(error);
        }
      }
    },

    async registerHrPreview() {
      const previewFormData = {
        ...this.formData,
        representative_contact: this.phone_put_api(
          this.display_area_code.representative_contact,
          this.formData.representative_contact
        ),
        assignee_contact: this.phone_put_api(
          this.display_area_code.assignee_contact,
          this.formData.assignee_contact
        ),
        is_create: 1,
      };
      try {
        if (this.status_agree_with_terms_conditions) {
          const response = await addHr(previewFormData);
          const { code, message } = response.data;
          if (code === 200) {
            this.type_form = 'completed';
          } else {
            MakeToast({
              variant: 'warning',
              title: 'warning',
              content: message,
            });
          }
        }
      } catch (error) {
        console.log(error);
      }
    },

    renderNameCountry(id) {
      const findItem = this.country_option.find((item) => item.key === id);
      if (findItem) {
        return findItem.value;
      }
    },

    // async addCertificateFile(event) {
    //   // const data = event.target.value;
    //   // const name_file = data.slice(12);
    //   // if (name_file) {
    //   //   this.formData.certificate_file_name_file = name_file;
    //   // }

    //   await this.postCentificate(event);
    // },

    // API Upload certificate
    async postCentificate(event) {
      const rowFileData = event.target.files[0];
      if (!rowFileData) {
        return 0;
      }
      if (
        !FILE_CAN_UPLOAD.includes(rowFileData.type) ||
        rowFileData.size > LIMIT_FILE_SIZE.NORMAL_UPLOAD_FILE
      ) {
        MakeToast({
          variant: 'warning',
          title: this.$t('WARNING'),
          content: this.$t('VALIDATE.FILE_UPLOAD_ERORR'),
        });
        return;
      }
      const formDataCertificate = new FormData();
      formDataCertificate.append('file', rowFileData);
      try {
        this.overlay.show = true;
        const res = await uploadFile(formDataCertificate);
        const { code, message, data } = res.data;
        if (code === 200) {
          this.formData.certificate_file_id = data.id;
          this.file_name = data.file_name;
          const scrollTarget = this.$refs.scrollTarget;
          scrollTarget.scrollIntoView({ behavior: 'smooth', block: 'end' });
        } else {
          MakeToast({
            variant: 'warning',
            title: 'warning',
            content: message,
          });
          this.formData.certificate_file_id = '';
          this.file_name = '';
        }
        this.overlay.show = false;
      } catch (error) {
        console.log(error);
        this.overlay.show = false;
      }
    },

    handleConfirmStilConfirmLeaving() {
      this.$router.push({ path: `/login` }, (onAbort) => {});
    },

    handleToggleConfirmLeavingModal() {
      if (this.statusModalConfirmLeaving === true) {
        this.statusModalConfirmLeaving = false;
      } else {
        this.statusModalConfirmLeaving = true;
      }
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/pages/RegisterHrOrigin/RegisterHrOrigin.scss';
.btn-custome {
  background-color: #6c757d !important;
  color: white !important;

  &:hover {
    background-color: #6c757d !important;
    color: white !important;
  }
}

.bd-logo {
  width: 250px;
  margin-bottom: 2rem;
}

.border-red {
  border-color: red;
}

#btn-back {
  background: #a5a5a5 !important;
  color: #fff !important;
}
</style>
