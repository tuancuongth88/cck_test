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
    <div class="wrraper">
      <div class="hr-registration">
        <div class="hr-registration-container">
          <div class="hr-registration-page">
            <!-- 1. TITLE -->
            <div class="hr-registration-page__head">
              <div class="head-title">
                <span>
                  {{ $t('COMPANY_REGISTER.TITLE_REGISTER_COMPANY_1') }}
                </span>
                <span>
                  {{ $t('COMPANY_REGISTER.TITLE_REGISTER_COMPANY_2') }}
                </span>
              </div>
              <div class="head-description">
                <span>{{ $t('TITLE_REGISTER_HR_H3') }}</span>
              </div>
            </div>

            <!-- 2. PROCESS -->
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
                {{ $t('HR_REGISTER.CONFIRM_INPUT_DATA') }}
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
            <div
              v-if="type_form === 'create' || type_form === 'preview'"
              class="hr-registration-page-autox"
            >
              <div class="hr-registration-page__form">
                <!-- 1 input 企業名 company name -->
                <div
                  v-if="type_form === 'create'"
                  class="row-item border-t border-l border-r"
                >
                  <div class="form-title">
                    <span>{{ $t('JOB_DETAIL.COMPANY_NAME') }}</span>
                    <Require />
                  </div>
                  <div class="form-inputs border-l">
                    <b-form-input
                      v-model="formData.company_name"
                      aria-describedby="company_name"
                      max-length="50"
                      :name="'company_name'"
                      :formatter="format50characters"
                      enabled
                      :class="error.company_name === false ? 'is-invalid' : ''"
                      @input="handleChangeForm($event, 'company_name')"
                    />
                    <b-form-invalid-feedback
                      id="company_name"
                      :state="error.company_name"
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
                    <span>{{ $t('JOB_DETAIL.COMPANY_NAME') }}</span>
                  </div>
                  <div class="form-inputs border-l">
                    {{ formData.company_name }}
                  </div>
                </div>
                <!-- 2 input 企業名（ﾌﾘｶﾞﾅ） company name (furigana) -->
                <div
                  v-if="type_form === 'create'"
                  class="row-item border-t border-l border-r"
                >
                  <div class="form-title">
                    <span>{{ $t('JOB_DETAIL.COMPANY_NAME_FURIGANA') }}</span><Require />
                  </div>
                  <div class="form-inputs border-l">
                    <b-form-input
                      v-model="formData.company_name_jp"
                      aria-describedby="company_name_jp"
                      max-length="50"
                      :name="'company_name_jp'"
                      :formatter="format50characters"
                      enabled
                      :class="
                        error.company_name_jp === false ? 'is-invalid' : ''
                      "
                      @input="handleChangeForm($event, 'company_name_jp')"
                    />
                    <b-form-invalid-feedback
                      id="company_name_jp"
                      :state="error.company_name_jp"
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
                    <span>{{ $t('JOB_DETAIL.COMPANY_NAME_FURIGANA') }}</span>
                  </div>
                  <div class="form-inputs border-l">
                    {{ formData.company_name_jp }}
                  </div>
                </div>
                <!-- 3 option 業種分野 field / Industry field-->
                <div
                  v-if="type_form === 'create'"
                  class="row-item border-t border-l border-r"
                >
                  <div class="form-title align-start">
                    <span>{{ $t('COMPANY_REGISTER.FIELD') }}</span>
                    <Require />
                  </div>
                  <div class="form-inputs border-l" style="gap: 1rem">
                    <!-- 3.1 option 大分類 major classification -->
                    <div class="w-100 d-flex justify-start align-center">
                      <div style="min-width: 176px">
                        {{ $t('COMPANY_REGISTER.MAJOR_CLASSIFICATION') }}
                      </div>
                      <div class="d-flex flex-wrap w-100">
                        <b-form-select
                          v-model="formData.major_classification"
                          :options="major_classification_options"
                          value-field="key"
                          text-field="value"
                          :class="
                            error.major_classification === false
                              ? 'is-invalid'
                              : ''
                          "
                          @change="
                            [
                              handleChangeMajor(),
                              handleChangeForm($event, 'major_classification'),
                            ]
                          "
                        />
                        <b-form-invalid-feedback
                          id="major_classification"
                          :state="error.major_classification"
                        >
                          {{ $t('VALIDATE.REQUIRED_SELECT') }}
                        </b-form-invalid-feedback>
                      </div>
                    </div>
                    <!-- 3.2 option 中分類 middle classification -->
                    <div class="w-100 d-flex justify-start align-center">
                      <div style="width: 30%; min-width: 176px">
                        {{ $t('COMPANY_REGISTER.MIDDLE_CLASSIFICATION') }}
                      </div>
                      <b-form-select
                        v-model="formData.middle_classification"
                        :options="middle_classification_options"
                        value-field="key"
                        text-field="value"
                        :enabled="formData.major_classification"
                        :disabled="!formData.major_classification"
                        :class="
                          error.middle_classification === false
                            ? 'is-invalid'
                            : ''
                        "
                      />
                      <b-form-invalid-feedback
                        id="middle_classification"
                        :state="error.middle_classification"
                      >
                        {{ $t('VALIDATE.REQUIRED_SELECT') }}
                      </b-form-invalid-feedback>
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
                    <!-- 3.1 option 大分類 major classification -->
                    <div class="w-100 d-flex justify-start align-center">
                      <div style="width: 30%; min-width: 176px">
                        {{ $t('COMPANY_REGISTER.MAJOR_CLASSIFICATION') }}
                      </div>
                      <div class="w-100 pl-5">
                        {{ getNameMajor(formData.major_classification) }}
                      </div>
                    </div>

                    <div class="w-100 d-flex justify-start align-center">
                      <div style="width: 30%; min-width: 176px">
                        {{ $t('COMPANY_REGISTER.MIDDLE_CLASSIFICATION') }}
                      </div>
                      <div class="w-100 pl-5">
                        {{ getNameMiddle(formData.middle_classification) }}
                      </div>
                    </div>
                  </div>
                </div>

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
                          :state="error.市町村区"
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

                <!-- 5 option input 電話番号 telephone number -->
                <div
                  v-if="type_form === 'create'"
                  class="row-item border-t border-l border-r"
                >
                  <div class="form-title">
                    <span>{{ $t('COMPANY_REGISTER.TELEPHONE_NUMBER') }}</span>
                    <Require />
                  </div>

                  <div class="form-inputs border-l">
                    <div
                      class="w-100 d-flex justify-start align-start"
                      style="gap: 1rem"
                    >
                      <!-- Dropdown -->
                      <div class="h-100" style="height: 40px">
                        <div
                          :class="
                            error.telephone_number_id === false
                              ? 'option-error'
                              : 'option-validate'
                          "
                        >
                          <div class="option-area-code">
                            <b-dropdown
                              id="telephone_number"
                              :text="display_area_code.telephone_number"
                              class="w-100 h-100"
                            >
                              <!-- VIE -->
                              <b-dropdown-item
                                @click="
                                  pustAreaCode('telephone_number', '+84');
                                  handleChangeFormOption(
                                    $event,
                                    'telephone_number_id'
                                  );
                                "
                                @change="
                                  handleChangeForm(
                                    $event,
                                    'telephone_number_id'
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
                                  pustAreaCode('telephone_number', '+81');
                                  handleChangeFormOption(
                                    $event,
                                    'telephone_number_id'
                                  );
                                "
                                @change="
                                  handleChangeForm(
                                    $event,
                                    'telephone_number_id'
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
                                display_area_code.telephone_number === '+84'
                              "
                              :src="
                                require(`@/assets/images/icons/flag-84.png`)
                              "
                            >
                            <img
                              v-if="
                                display_area_code.telephone_number === '+81'
                              "
                              :src="
                                require(`@/assets/images/icons/flag-81.png`)
                              "
                            >
                          </div>
                        </div>
                        <b-form-invalid-feedback
                          id="telephone_number"
                          :state="error.telephone_number_id"
                        >
                          {{ $t('VALIDATE.REQUIRED_SELECT') }}
                        </b-form-invalid-feedback>
                      </div>
                      <!-- Input -->
                      <div class="w-100">
                        <b-form-input
                          v-model="formData.telephone_number"
                          aria-describedby="telephone_number"
                          max-length="50"
                          type="number"
                          :name="'telephone_number'"
                          :formatter="format50characters"
                          :placeholder="$t('COMPANY_REGISTER.PLACEHOLDER')"
                          :class="
                            error.telephone_number === false ? 'is-invalid' : ''
                          "
                          :enabled="display_area_code.telephone_number !== ''"
                          :disabled="display_area_code.telephone_number === ''"
                          @input="handleChangeForm($event, 'telephone_number')"
                        />
                        <b-form-invalid-feedback
                          id="telephone_number"
                          :state="error.telephone_number"
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
                  <div
                    class="form-title"
                    :class="{ 'bg-type-detail': type_form === 'preview' }"
                  >
                    <span>{{ $t('COMPANY_REGISTER.TELEPHONE_NUMBER') }}</span>
                  </div>
                  <div class="form-inputs border-l">
                    <div
                      class="w-100 d-flex justify-start align-center"
                      style="gap: 0.75rem"
                    >
                      {{ display_area_code.telephone_number }}
                      {{ formData.telephone_number }}
                    </div>
                  </div>
                </div>

                <!-- 6 input メールアドレス (ログインID) Email address (login ID) -->
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
                <!-- 7 input URL -->
                <div
                  v-if="type_form === 'create'"
                  class="row-item border-t border-l border-r"
                >
                  <div class="form-title">
                    <span style="text-transform: capitalize">URL</span><Require />
                  </div>
                  <div class="form-inputs border-l">
                    <!-- aria-describedby="company_name" -->
                    <!-- id="company_name" -->
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

                <!-- 8 input 代表者 representative -->
                <div
                  v-if="type_form === 'create'"
                  class="row-item border-t border-l border-r"
                >
                  <div class="form-title align-start">
                    <span>{{ $t('COMPANY_REGISTER.REPRESENTATIVE') }}</span><Require />
                  </div>
                  <div class="form-inputs border-l" style="gap: 1rem">
                    <!-- 8.1 input 肩書 Job title -->
                    <div class="w-100 d-flex justify-start align-center">
                      <div style="width: 30%; min-width: 176px">
                        <div>
                          <span>{{ $t('COMPANY_REGISTER.JOB_TITLE') }}</span>
                        </div>
                      </div>
                      <!--  -->
                      <div class="w-100">
                        <b-form-input
                          v-model="formData.job_title"
                          enabled
                          aria-describedby="job_title"
                          max-length="50"
                          :name="'job_title'"
                          :formatter="format50characters"
                          :placeholder="''"
                          class="w-100"
                          :class="error.job_title === false ? 'is-invalid' : ''"
                          @input="handleChangeForm($event, 'job_title')"
                        />
                        <b-form-invalid-feedback
                          id="job_title"
                          :state="error.市町村区"
                        >
                          {{ $t('VALIDATE.REQUIRED_TEXT') }}
                        </b-form-invalid-feedback>
                      </div>
                    </div>
                    <!-- 8.2 input 氏名 Full name -->
                    <div class="w-100 d-flex justify-start align-center">
                      <div style="width: 30%; min-width: 176px">
                        <div>
                          <span>{{ $t('COMPANY_REGISTER.FULL_NAME') }}</span>
                        </div>
                      </div>
                      <!--  -->
                      <div class="w-100">
                        <b-form-input
                          v-model="formData.full_name"
                          enabled
                          aria-describedby="full_name"
                          max-length="50"
                          :name="'full_name'"
                          :formatter="format50characters"
                          :placeholder="''"
                          class="w-100"
                          :class="error.full_name === false ? 'is-invalid' : ''"
                          @input="handleChangeForm($event, 'full_name')"
                        />
                        <b-form-invalid-feedback
                          id="full_name"
                          :state="error.full_name"
                        >
                          {{ $t('VALIDATE.REQUIRED_TEXT') }}
                        </b-form-invalid-feedback>
                      </div>
                    </div>
                    <!-- 8.3 input 氏名 (フリガナ) Full name (furigana) -->
                    <div class="w-100 d-flex justify-start align-center">
                      <div style="width: 30%; min-width: 176px">
                        <div>
                          <span>{{
                            $t('COMPANY_REGISTER.FULL_NAME_FURIGANA')
                          }}</span>
                        </div>
                      </div>
                      <!--  -->
                      <div class="w-100">
                        <b-form-input
                          v-model="formData.full_name_furigana"
                          enabled
                          aria-describedby="full_name_furigana"
                          max-length="50"
                          :name="'full_name_furigana'"
                          :formatter="format50characters"
                          :placeholder="''"
                          class="w-100"
                          :class="
                            error.full_name_furigana === false
                              ? 'is-invalid'
                              : ''
                          "
                          @input="
                            handleChangeForm($event, 'full_name_furigana')
                          "
                        />
                        <b-form-invalid-feedback
                          id="full_name"
                          :state="error.full_name_furigana"
                        >
                          {{ $t('VALIDATE.REQUIRED_TEXT') }}
                        </b-form-invalid-feedback>
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
                    <span>{{ $t('COMPANY_REGISTER.REPRESENTATIVE') }}</span>
                  </div>
                  <div class="form-inputs border-l" style="gap: 1rem">
                    <!-- 8.1 input 肩書 Job title -->
                    <div class="w-100 d-flex justify-start align-center">
                      <div style="width: 30%; min-width: 176px">
                        <div>
                          <span>{{ $t('COMPANY_REGISTER.JOB_TITLE') }}</span>
                        </div>
                      </div>
                      <!--  -->
                      <div class="w-100 pl-5">
                        <div>
                          <span>{{ formData.job_title }}</span>
                        </div>
                      </div>
                    </div>
                    <!-- 8.2 input Full name Full name -->
                    <div class="w-100 d-flex justify-start align-center">
                      <div style="width: 30%; min-width: 176px">
                        <div>
                          <span>{{ $t('COMPANY_REGISTER.FULL_NAME') }}</span>
                        </div>
                      </div>
                      <!--  -->
                      <div class="w-100 pl-5">
                        <div>
                          <span>{{ formData.full_name }}</span>
                        </div>
                      </div>
                    </div>
                    <!-- 8.3 input 氏名 (フリガナ) Full name (furigana) -->
                    <div class="w-100 d-flex justify-start align-center">
                      <div style="width: 30%; min-width: 176px">
                        <div>
                          <span>{{
                            $t('COMPANY_REGISTER.FULL_NAME_FURIGANA')
                          }}</span>
                        </div>
                      </div>
                      <!--  -->
                      <div class="w-100 pl-5">
                        <div>
                          <span>{{ formData.full_name_furigana }}</span>
                        </div>
                      </div>
                    </div>
                    <!--  -->
                  </div>
                </div>

                <!-- 9 option input 代表者連絡先 Representative contact -->
                <div
                  v-if="type_form === 'create'"
                  class="row-item border-t border-l border-r"
                >
                  <div class="form-title">
                    <span>{{
                      $t('COMPANY_REGISTER.REPRESENTATIVE_CONTACT')
                    }}</span><Arbitrarily />
                  </div>
                  <div class="form-inputs border-l">
                    <div
                      class="w-100 d-flex justify-start align-start"
                      style="gap: 1rem"
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
                      <!-- Input -->
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
                      <!--  -->
                    </div>
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
                      $t('COMPANY_REGISTER.REPRESENTATIVE_CONTACT')
                    }}</span>
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

                <!-- 10 option input Assignee contact 担当者連絡先 -->
                <div
                  v-if="type_form === 'create'"
                  class="row-item border-t border-l border-r border-b"
                >
                  <div class="form-title">
                    <span>{{ $t('COMPANY_REGISTER.ASSIGNEE_CONTACT') }}</span><Require />
                  </div>
                  <div class="form-inputs border-l">
                    <div
                      class="w-100 d-flex justify-start align-start"
                      style="gap: 1rem"
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
                  class="row-item border-t border-l border-r border-b"
                >
                  <div
                    class="form-title"
                    :class="{ 'bg-type-detail': type_form === 'preview' }"
                  >
                    <span>{{ $t('COMPANY_REGISTER.ASSIGNEE_CONTACT') }}</span>
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
                <!-- hr-registration-page__form -->
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
                @click="registerCompanyCheckValidate"
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

            <!--  BTN 次へ next -->
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
                @click="registerCompanyPreview"
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
import Require from '@/components/Require/Require.vue';
import { MakeToast } from '@/utils/toastMessage';
import { validEmail } from '@/utils/validate';
import { companyRegister } from '@/api/company.js';
import { getListEduCourse } from '@/api/job';
import Arbitrarily from '@/components/Arbitrarily/Arbitrarily.vue';

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
      telephone_number_detail: '',
      telephone_number_put_api: '',
      assignee_contact_detail: '',
      assignee_contact_put_api: '',

      display_area_code: {
        telephone_number: '',
        representative_contact: '',
        assignee_contact: '',
      },

      formData: {
        company_name: '',
        company_name_jp: '',
        major_classification: '',
        middle_classification: '',

        post_code: '',
        prefectures: '',
        municipality: '',
        number: '',
        other: '',
        // 5
        telephone_number: '',
        mail_address: '',
        url: '',
        //
        job_title: '',
        full_name: '',
        full_name_furigana: '',
        //
        representative_contact: '',
        assignee_contact: '',
        status: 1,
        is_create: 0,
      },

      major_classification_options: [],
      middle_classification_options: [],

      error: {
        company_name: true,
        company_name_jp: true,
        major_classification: true,
        middle_classification: true,
        post_code: true,
        prefectures: true,
        municipality: true,
        number: true,
        // other: true,
        telephone_number_id: true,
        telephone_number: true,
        mail_address: true,
        url: true,
        job_title: true,
        full_name: true,
        full_name_furigana: true,
        representative_contact_id: true,
        representative_contact: true,
        assignee_contact_id: true,
        assignee_contact: true,
      },
    };
  },

  computed: {
    // listUser() {
    //   return this.$store.getters.listUser;
    // },
  },

  created() {
    this.getListEduCourse();
  },

  methods: {
    format7characters(e) {
      return String(e).substring(0, 7);
    },
    format50characters(e) {
      return String(e).substring(0, 50);
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
      if (this.formData.company_name === '') {
        this.error.company_name = false;
      }
      if (this.formData.company_name_jp === '') {
        this.error.company_name_jp = false;
      }
      if (this.formData.major_classification === '') {
        this.error.major_classification = false;
        this.error.middle_classification = true;
      } else if (this.formData.major_classification !== '') {
        if (this.formData.middle_classification === '') {
          this.error.middle_classification = false;
        } else {
          this.error.middle_classification = true;
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

      if (this.display_area_code.telephone_number === '') {
        // option khác
        this.error.telephone_number_id = false;
        this.error.telephone_number = true;
      } else if (this.display_area_code.telephone_number !== '') {
        if (!this.formData.telephone_number) {
          this.error.telephone_number = false;
        } else {
          this.error.telephone_number = true;
        }
      }

      if (this.formData.mail_address === '') {
        this.error.mail_address = false;
      }
      if (this.formData.url === '') {
        this.error.url = false;
      }

      if (this.formData.job_title === '') {
        this.error.job_title = false;
      }
      if (this.formData.full_name === '') {
        this.error.full_name = false;
      }
      if (this.formData.full_name_furigana === '') {
        this.error.full_name_furigana = false;
      }
      // 9
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
      // True && all
      if (
        this.formData.company_name !== '' &&
        this.formData.company_name_jp !== '' &&
        this.formData.major_classification !== '' &&
        this.formData.middle_classification !== '' &&
        this.formData.post_code !== '' &&
        this.formData.prefectures !== '' &&
        this.formData.municipality !== '' &&
        this.formData.number !== '' &&
        // this.formData.other !== '' &&
        this.display_area_code.telephone_number && // option khác
        this.formData.telephone_number &&
        this.formData.mail_address &&
        this.formData.url !== '' &&
        this.formData.job_title !== '' &&
        this.formData.full_name !== '' &&
        this.display_area_code.assignee_contact && // option khác
        this.formData.assignee_contact
      ) {
        return true;
      }
    },

    handleChangeFormOption(event, type_dropdown) {
      switch (type_dropdown) {
        // 5 option
        case 'telephone_number_id':
          this.error.telephone_number_id = true;
          if (event !== '') {
            this.error.telephone_number_id = true;
          } else {
            this.error.telephone_number_id = false;
          }
          break;
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
        case 'company_name':
          this.error.company_name = null;
          if (newValue) {
            this.error.company_name = true;
          } else {
            this.error.company_name = false;
          }
          break;
        // 2
        case 'company_name_jp':
          this.error.company_name_jp = null;
          if (newValue) {
            this.error.company_name_jp = true;
          } else {
            this.error.company_name_jp = false;
          }
          break;
        // 3.1
        case 'major_classification':
          this.error.major_classification = null;
          if (newValue) {
            this.error.major_classification = true;
          } else {
            this.error.major_classification = false;
          }
          break;
        // 3.2
        case 'middle_classification':
          this.error.middle_classification = null;
          if (newValue) {
            this.error.middle_classification = true;
          } else {
            this.error.middle_classification = false;
          }
          break;
        // 4.1
        case 'post_code':
          this.error.post_code = null;
          if (newValue) {
            this.error.post_code = true;
          } else {
            this.error.post_code = false;
          }
          break;
        // 4.2
        case 'prefectures':
          this.error.prefectures = null;
          if (newValue) {
            this.error.prefectures = true;
          } else {
            this.error.prefectures = false;
          }
          break;
        // 4.3
        case 'municipality':
          this.error.municipality = null;
          if (newValue) {
            this.error.municipality = true;
          } else {
            this.error.municipality = false;
          }
          break;
        // 4.4
        case 'number':
          this.error.number = null;
          if (newValue) {
            this.error.number = true;
          } else {
            this.error.number = false;
          }
          break;
          // 4.5 Ko bắt buộc

        // 5 input
        case 'telephone_number':
          this.error.telephone_number = true;
          if (newValue) {
            this.error.telephone_number = true;
            this.telephone_number_detail = this.phone_put_api(
              this.display_area_code.telephone_number,
              this.formData.telephone_number
            );
            this.telephone_number_put_api = this.phone_put_api(
              this.display_area_code.telephone_number,
              this.formData.telephone_number
            );
          } else {
            this.error.telephone_number = false;
          }
          break;
        // 6
        case 'mail_address':
          this.error.mail_address = null;
          if (newValue) {
            this.error.mail_address = true;
          } else {
            this.error.mail_address = false;
          }
          break;
        // 7
        case 'url':
          this.error.url = null;
          if (newValue) {
            this.error.url = true;
          } else {
            this.error.url = false;
          }
          break;
        // 8.1
        case 'job_title':
          this.error.job_title = null;
          if (newValue) {
            this.error.job_title = true;
          } else {
            this.error.job_title = false;
          }
          break;
        // 8.2
        case 'full_name':
          this.error.full_name = null;
          if (newValue) {
            this.error.full_name = true;
          } else {
            this.error.full_name = false;
          }
          break;
        // 8.3
        case 'full_name_furigana':
          this.error.full_name_furigana = null;
          if (newValue) {
            this.error.full_name_furigana = true;
          } else {
            this.error.full_name_furigana = false;
          }
          break;
        // 9
        // case 'representative_contact_id':
        //   this.error.representative_contact_id = null;
        //   if (newValue) {
        //     this.error.representative_contact_id = true;
        //   } else {
        //     this.error.representative_contact_id = false;
        //   }
        //   break;
        // 9 input
        // case 'representative_contact':
        //   this.error.representative_contact = null;
        //   if (newValue) {
        //     this.error.representative_contact = true;
        //     this.representative_contact_detail = this.phone_put_api(
        //       this.display_area_code.representative_contact,
        //       this.formData.representative_contact
        //     );
        //     this.representative_contact_put_api = this.phone_put_api(
        //       this.display_area_code.representative_contact,
        //       this.formData.representative_contact
        //     );
        //   } else {
        //     this.error.representative_contact = false;
        //   }
        //   break;
        // 10
        case 'assignee_contact_id':
          this.error.assignee_contact_id = null;
          if (newValue) {
            this.error.assignee_contact_id = true;
          } else {
            this.error.assignee_contact_id = false;
          }
          break;
        // 10 input
        case 'assignee_contact':
          this.error.assignee_contact = null;
          if (newValue) {
            this.error.assignee_contact = true;
            this.assignee_contact_detail = this.phone_put_api(
              this.display_area_code.assignee_contact,
              this.formData.assignee_contact
            );
            this.assignee_contact_put_api = this.phone_put_api(
              this.display_area_code.assignee_contact,
              this.formData.assignee_contact
            );
          } else {
            this.error.assignee_contact = false;
          }
          break;
        // ...
        default:
          break;
      }
    },

    handleBack() {
      this.type_form = 'create';
    },

    async getListEduCourse() {
      let arr_list_main_job = [];
      try {
        const response = await getListEduCourse();
        const { code, data, message } = response.data;
        if (code === 200) {
          arr_list_main_job = data;
        } else {
          MakeToast({
            variant: 'warning',
            title: 'warning',
            content: message,
          });
        }
      } catch (erorr) {
        console.log('erorr', erorr);
      }
      this.major_classification_options = arr_list_main_job.map((element) => {
        return {
          key: element.id,
          type: element.type,
          value: element.name_ja,
          job_info: element.job_info,
        };
      });
    },

    handleChangeMajor() {
      this.middle_classification_options = [];
      if (this.formData.major_classification) {
        const findItem = this.major_classification_options.find(
          (item) => item.key === this.formData.major_classification
        );

        if (findItem) {
          this.middle_classification_options = findItem.job_info.map(
            (element) => {
              return {
                key: element.id,
                type: element.job_type_id,
                value: element.name_ja,
              };
            }
          );
        }
      }
    },

    phone_put_api(area_code, string_phone) {
      const phone_convert = `${area_code} ${string_phone}`;
      return phone_convert;
    },

    checkEmail() {
      if (validEmail(this.formData.mail_address)) {
        return true;
      } else {
        return false;
      }
    },

    // API Create Copany Type Register is_create = 0 => check validate
    async registerCompanyCheckValidate() {
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
          telephone_number: this.phone_put_api(
            this.display_area_code.telephone_number,
            this.formData.telephone_number
          ),
          representative_contact: this.phone_put_api(
            this.display_area_code.representative_contact,
            this.formData.representative_contact
          ),
          assignee_contact: this.phone_put_api(
            this.display_area_code.assignee_contact,
            this.formData.assignee_contact
          ),
          status: 1,
          is_create: 0,
        };

        // Check validate
        try {
          const response = await companyRegister(createFormData);
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

    goToLogin() {
      this.$router.push({ path: `/login` }, (onAbort) => {});
    },

    // API Create Copany Type Preview is_create = 1 => Save DB
    async registerCompanyPreview() {
      const previewFormData = {
        ...this.formData,
        telephone_number:
          this.display_area_code.telephone_number +
          ' ' +
          this.formData.telephone_number,

        representative_contact:
          this.display_area_code.representative_contact +
          ' ' +
          this.formData.representative_contact,

        assignee_contact:
          this.display_area_code.assignee_contact +
          ' ' +
          this.formData.assignee_contact,
        is_create: 1,
      };
      try {
        if (this.status_agree_with_terms_conditions) {
          const response = await companyRegister(previewFormData);
          const { code } = response.data;
          if (code === 200) {
            this.type_form = 'completed';
          }
        }
      } catch (error) {
        console.log(error);
      }
    },

    getNameMajor(id) {
      const findItem = this.major_classification_options.find(
        (item) => item.key === id
      );
      if (findItem) {
        return findItem.value;
      }
    },
    getNameMiddle(id) {
      const findItem = this.middle_classification_options.find(
        (item) => item.key === id
      );
      if (findItem) {
        return findItem.value;
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
</style>
