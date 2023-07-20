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
                <span>{{ $t('TITLE_REGISTER_HR_H1') }}</span>
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
              <h4 class="text-center mt-5">送信しました。</h4>
              <h4 class="text-center">
                運営管理者の審査結果をお待ちください。
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
                  <div class="form-title">
                    <span>{{ $t('HR_REGISTER.CORPORATE_NAME') }}</span>
                    <Require />
                  </div>
                  <div class="form-inputs border-l">
                    <b-form-input
                      v-model="formData.corporate_name_en"
                      enabled
                      aria-describedby="corporate_name_en"
                      max-length="50"
                      :name="'corporate_name_en'"
                      :formatter="format50characters"
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
                  <div class="form-title">
                    <span>{{ $t('HR_REGISTER.CORPORATE_NAME_JAPAN') }}</span><Require />
                  </div>
                  <div class="form-inputs border-l">
                    <b-form-input
                      v-model="formData.corporate_name_ja"
                      enabled
                      aria-describedby="corporate_name_ja"
                      max-length="50"
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
                  <div class="form-title">
                    <span>{{ $t('HR_REGISTER.LICENSE_NO') }}</span>
                    <Require />
                  </div>
                  <div class="form-inputs border-l">
                    <b-form-input
                      v-model="formData.license_no"
                      enabled
                      aria-describedby="license_no"
                      max-length="50"
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
                  <div class="form-title">
                    <span>{{ $t('HR_REGISTER.ACCOUNT_CLASSIFICATION') }}</span><Require />
                  </div>

                  <div class="form-inputs border-l">
                    <b-form-select
                      v-model="formData.account_classification"
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
                  <div class="form-title">
                    <span>{{ $t('HR_REGISTER.COUNTRY') }}</span><Require />
                  </div>
                  <div class="form-inputs border-l">
                    <b-form-select
                      v-model="formData.country"
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
                  <div class="form-title">
                    <span>{{ $t('HR_REGISTER.REPRESENTATIVE_FULL_NAME') }}</span><Require />
                  </div>
                  <div class="form-inputs border-l">
                    <b-form-input
                      v-model="formData.representative_full_name"
                      enabled
                      aria-describedby="representative_full_name"
                      max-length="50"
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
                  <div class="form-title">
                    <div class="w-100 d-flex flex-column">
                      {{ $t('HR_REGISTER.REPRESENTATIVE_FULL_NAME_FURIGANA1') }}
                    </div>
                    <Require />
                  </div>
                  <div class="form-inputs border-l">
                    <b-form-input
                      v-model="formData.representative_full_name_furigana"
                      enabled
                      aria-describedby="representative_full_name_furigana"
                      max-length="50"
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
                        (
                        {{
                          $t('HR_REGISTER.REPRESENTATIVE_FULL_NAME_FURIGANA2')
                        }}
                        )
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
                  <div class="form-title">
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
                              :text="display_area_code.representative_contact"
                              class="w-100 h-100"
                            >
                              <!-- VIE -->
                              <b-dropdown-item
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
                          max-length="50"
                          type="number"
                          :name="'representative_contact'"
                          :formatter="format50characters"
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
                  <div class="form-title">
                    <span>{{ $t('HR_REGISTER.ASSIGNEE_CONTACT') }}</span><Require />
                  </div>
                  <div class="form-inputs border-l">
                    <div
                      class="w-100 d-flex justify-start align-center"
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
                              :text="display_area_code.assignee_contact"
                              class="w-100 h-100"
                            >
                              <!-- VIE -->
                              <b-dropdown-item
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
                          :name="'assignee_contact'"
                          :formatter="format50characters"
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
                  <div class="form-title align-start">
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
                  <div class="form-title">
                    <span>{{ $t('COMPANY_REGISTER.EMAIL_ADDRESS_LOGIN_ID') }}
                      <br>
                      (ログインID)
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
                  <div class="form-title">
                    <span style="text-transform: capitalize">URL</span><Require />
                  </div>
                  <div class="form-inputs border-l">
                    <b-form-input
                      v-model="formData.url"
                      aria-describedby="url"
                      max-length="50"
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
                  <div class="form-title">
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
                      <div class="btn hr-list-import__btn">
                        <label
                          for="upload-certificateFile"
                        >{{ $t('HR_REGISTER.SELECT_FILE') }}
                        </label>
                        <input
                          id="upload-certificateFile"
                          ref="CertificateFile"
                          :style="'display: none'"
                          type="file"
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
                      {{ $t('VALIDATE.REQUIRED_TEXT') }}
                    </b-form-invalid-feedback>
                    <!-- Uploaded file -->
                    <!-- <div
                      v-if="formData.certificate_file_name_file !== ''"
                      class="w-100 h-100 d-flex justify-start align-center"
                    >
                      <span>
                        {{ formData.certificate_file_name_file }}
                      </span>
                    </div> -->
                    <!--  -->
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
              class="hr-registration-page__btn"
            >
              <b-button
                variant="warning"
                size="lg"
                class="text-white"
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
                <div class="terms-vs-conditions-content">
                  <!-- <div>{{ Terms & Conditions }}</div> -->
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
                :value="true"
                :unchecked-value="false"
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
                @click="handleBack"
              >
                {{ $t('BUTTON.BTN_BACK_REGISTER') }}
              </b-button>
              <b-button
                :disable="status_agree_with_terms_conditions === true"
                variant="warning"
                class="text-white"
                size="lg"
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
              @click="goToLogin"
            >
              <span>{{ $t('GO_TO_LOGIN_SCREEN') }}</span>
            </b-button>
          </div>
        </div>
      </div>
    </div>
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
import { uploadFile } from '@/api/uploadFile';

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
      overlay: {
        show: false,
        variant: 'light',
        opacity: 0.2,
        blur: '1rem',
        rounded: 'sm',
      },

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
        account_classification: '',
        country: '',
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
      account_classification_option: account_classification_option,
      country_option: country_option,

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

  computed: {
    // listUser() {
    //   return this.$store.getters.listUser;
    // },
  },

  created() {
    // this.postCentificate();
  },

  methods: {
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
      if (this.formData.account_classification === '') {
        this.error.account_classification = false;
      }
      if (this.formData.country === '') {
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
        this.formData.account_classification.content !== '' &&
        this.formData.country.content !== '' &&
        this.formData.representative_full_name !== '' &&
        this.formData.representative_full_name_furigana !== '' &&
        this.formData.assignee_contact_id !== '' && //
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
      return String(e).substring(0, 7);
    },
    format50characters(e) {
      return String(e).substring(0, 50);
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
        if (resCheckEmail) {
          console.log('pass validEmail');
        } else {
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
        representative_contact:
          this.display_area_code.representative_contact +
          this.formData.representative_contact,
        assignee_contact:
          this.display_area_code.representative_contact +
          this.formData.assignee_contact,
        is_create: 1,
      };
      try {
        if (this.status_agree_with_terms_conditions) {
          const response = await addHr(previewFormData);
          const { code } = response.data;
          if (code === 200) {
            this.type_form = 'completed';
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

    // GET API
    // API Upload certificate
    async postCentificate(event) {
      const rowFileData = event.target.files[0];
      if (!rowFileData) {
        return 0;
      }
      const formDataCertificate = new FormData();
      formDataCertificate.append('file', rowFileData);
      try {
        const res = await uploadFile(formDataCertificate);
        const { code, data } = res.data;
        if (code === 200) {
          this.formData.certificate_file_id = data.id;
          this.file_name = data.file_name;
        }
      } catch (error) {
        console.log(' uploadFile error ==>', error);
      }
    },
    //
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
</style>
