<template>
  <b-overlay
    :show="overlay.show"
    :blur="overlay.blur"
    :rounded="overlay.sm"
    :style="'min-height: 100vh; height: 100%'"
    :variant="overlay.variant"
    :opacity="overlay.opacity"
  >
    <template v-if="overlay.show" #overlay>
      <div class="text-center">
        <b-icon icon="arrow-clockwise" font-scale="3" animation="spin" />
        <p style="margin-top: 10px">{{ $t('PLEASE_WAIT') }}</p>
      </div>
    </template>

    <div v-show="!overlay.show" class="display-user-management-list">
      <div
        class="mb-4 d-flex justify-space-between align-start"
        style="gap: 1.8rem"
      >
        <b-col cols="3" class="px-0">
          <b-card class="text-center p-4">
            <b-card-text class="font-weight-bold">
              {{ formData.corporate_name_en }}
            </b-card-text>
            <b-card-text class="font-weight-bold">
              {{ formData.corporate_name_ja }}
            </b-card-text>
            <b-card-text>（ID : {{ id }}）</b-card-text>
            <div class="detail-file d-flex flex-column">
              <div class="d-flex align-items-center font-weight-bold">
                <span class="label-input-file mr-2">許可証</span>
                <!-- <b-badge
                  class="badge-required mx-2"
                  variant="light"
                >必須</b-badge> -->
                <Require />
              </div>
              <div
                class="upload-group d-flex my-2 flex-column align-items-start"
              >
                <input
                  id="file"
                  ref="upload-input"
                  type="file"
                  class="d-none input-file"
                >
                <label for="upload-certificateFile" class="btn-upload mb-1">
                  {{ $t('HR_REGISTER.SELECT_FILE') }}
                </label>
                <span class="file-name certificate-file-name-hr-org">{{
                  certificate_file_name
                }}</span>
              </div>
            </div>
            <b-form-select
              v-model="statusSelected"
              :options="optionStatus"
              class="mb-3 change-status_hr-org"
              dusk="change_status"
              :disabled="role_type === 5 || optionStatus.length === 1"
              @change="handleChangeStatus"
            />
            <!-- <b-button variant="outline-secondary" class="mt-5">{{ $t('STATUS.EXAMINATION_PENDING') }}</b-button> -->
          </b-card>
        </b-col>
        <div cols="9" style="flex: 1">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <b-col
              class="border-left-title font-weight-bold hr-org-edit-title"
            >{{ $t('TITLE.ORGANIZATION_DETAIL') }}</b-col>
            <div>
              <!--  variant="outline-dark mx-1"
                class="to-detail-hrorg" -->
              <button
                dusk="btn-cancel"
                class="btn btn_back--custom mx-1"
                @click="handleBackToDetail()"
              >
                {{ $t('BUTTON.CANCEL') }}
              </button>
              <!--
             variant="warning"
                class="text-white mx-1 save-hrorg"
               -->
              <button
                class="btn btn_save--custom mx-1"
                dusk="btn-save"
                @click="handleConfirmUpdate()"
              >
                {{ $t('BUTTON.SAVE') }}
              </button>
            </div>
          </div>
          <b-form @submit="onSubmit($event)" @reset="onReset($event)">
            <b-list-group deck>
              <!-- Corporate name -->
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray justify-content-between"
                  >
                    <span class="label-name-hrorg-en">{{
                      $t('HR_REGISTER.LABEL.CORPORATE_NAME')
                    }}</span>
                    <!-- <b-badge
                      class="badge-required mx-2"
                      variant="light"
                    >必須</b-badge> -->
                    <Require />
                  </b-col>
                  <b-col cols="9" class="my-2">
                    <b-form-input
                      v-model="formData.corporate_name_en"
                      :class="
                        error.corporate_name_en === false ? ' is-invalid' : ''
                      "
                      class="input-hrorg-name-en"
                      dusk="corporate_name_en"
                      @input="handleChangeForm($event, 'corporate_name_en')"
                    />
                    <b-form-invalid-feedback :state="error.corporate_name_en">
                      {{ $t('VALIDATE.REQUIRED_TEXT') }}
                    </b-form-invalid-feedback>
                  </b-col>
                </div>
              </b-list-group-item>
              <!-- Corporate name (Japanese) -->
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray justify-content-between"
                  >
                    <div class="d-flex flex-column">
                      <span class="label-name-hrorg-ja">{{ $t('HR_REGISTER.LABEL.CORPORATE_NAME') }}</span>
                      <span class="label-name-hrorg-ja">{{ $t('HR_REGISTER.LABEL.CORPORATE_NAME_JAPANESE') }}</span>
                    </div>
                    <!-- <b-badge
                      class="badge-required mx-2"
                      variant="light"
                    >必須</b-badge> -->
                    <Require />
                  </b-col>
                  <b-col cols="9" class="align-items-center my-2">
                    <b-form-input
                      v-model="formData.corporate_name_ja"
                      :class="
                        error.corporate_name_ja === false ? ' is-invalid' : ''
                      "
                      class="input-name-hrorg-ja"
                      dusk="corporate_name_ja"
                      @input="handleChangeForm($event, 'corporate_name_ja')"
                    />
                    <b-form-invalid-feedback :state="error.corporate_name_ja">
                      {{ $t('VALIDATE.REQUIRED_TEXT') }}
                    </b-form-invalid-feedback>
                  </b-col>
                </div>
              </b-list-group-item>
              <!-- License No. -->
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray justify-content-between"
                  ><span class="label-license-no">{{
                     $t('HR_REGISTER.LABEL.LICENSE_NO')
                   }}</span>
                    <!-- <b-badge
                      class="badge-required mx-2"
                      variant="light"
                    >必須</b-badge> -->
                    <Require />
                  </b-col>
                  <b-col cols="9" class="align-items-center my-2">
                    <b-form-input
                      v-model="formData.license_no"
                      :class="error.license_no === false ? ' is-invalid' : ''"
                      class="input-license-no"
                      dusk="license_no"
                      @input="handleChangeForm($event, 'license_no')"
                    />
                    <b-form-invalid-feedback :state="error.license_no">
                      {{ $t('VALIDATE.REQUIRED_TEXT') }}
                    </b-form-invalid-feedback>
                  </b-col>
                </div>
              </b-list-group-item>
              <!-- Account classification -->
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray justify-content-between"
                  ><span class="label-account-classification">{{
                     $t('HR_REGISTER.LABEL.ACCOUNT_CLASSIFICATION')
                   }}</span>
                    <!-- <b-badge
                      class="badge-required mx-2"
                      variant="light"
                    >必須</b-badge> -->
                    <Require />
                  </b-col>
                  <b-col cols="9" class="align-items-center my-2">
                    <b-form-select
                      v-model="formData.account_classification"
                      :options="account_classification_option"
                      value-field="key"
                      text-field="value"
                      :class="
                        error.account_classification === false
                          ? ' is-invalid'
                          : ''
                      "
                      class="input-account-classification"
                      dusk="account_classification"
                      @input="
                        handleChangeForm($event, 'account_classification')
                      "
                    />
                    <b-form-invalid-feedback
                      :state="error.account_classification"
                    >
                      {{ $t('VALIDATE.REQUIRED_TEXT') }}
                    </b-form-invalid-feedback>
                  </b-col>
                </div>
              </b-list-group-item>
              <!-- Country -->
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray justify-content-between"
                  ><span class="label-county">{{
                     $t('HR_REGISTER.LABEL.CONTRY')
                   }}</span>
                    <!-- <b-badge
                      class="badge-required mx-2"
                      variant="light"
                    >必須</b-badge> -->
                    <Require />
                  </b-col>
                  <b-col cols="9" class="align-items-center my-2">
                    <b-form-select
                      v-model="formData.country"
                      :class="error.country === false ? ' is-invalid' : ''"
                      class="select-country"
                      dusk="country"
                      :options="country_option"
                      value-field="key"
                      text-field="value"
                      @input="handleChangeForm($event, 'country')"
                    />
                    <b-form-invalid-feedback :state="error.country">
                      {{ $t('VALIDATE.REQUIRED_TEXT') }}
                    </b-form-invalid-feedback>
                  </b-col>
                </div>
              </b-list-group-item>
              <!-- Representative fullname -->
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray justify-content-between"
                  ><span class="label-representative-full-name">{{
                     $t('HR_REGISTER.LABEL.REPRESENTATIVE_FULL_NAME')
                   }}</span>
                    <!-- <b-badge
                      class="badge-required mx-2"
                      variant="light"
                    >必須</b-badge> -->
                    <Require />
                  </b-col>
                  <b-col cols="9" class="align-items-center my-2">
                    <b-form-input
                      v-model="formData.representative_full_name"
                      aria-label=""
                      :class="
                        error.representative_full_name === false
                          ? ' is-invalid'
                          : ''
                      "
                      class="input-representative-full-name"
                      dusk="representative_full_name"
                      @input="
                        handleChangeForm($event, 'representative_full_name')
                      "
                    />
                    <b-form-invalid-feedback
                      :state="error.representative_full_name"
                    >
                      {{ $t('VALIDATE.REQUIRED_TEXT') }}
                    </b-form-invalid-feedback>
                  </b-col>
                </div>
              </b-list-group-item>
              <!-- Representative full name(furigana) -->
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray justify-content-between"
                  >
                    <div class="d-flex flex-column">
                      <span class="label-representative-full-name">{{ $t('HR_REGISTER.LABEL.REPRESENTATIVE_FULL_NAME') }}</span>
                      <span class="label-representative-full-name">{{ $t('HR_REGISTER.LABEL.REPRESENTATIVE_FULL_NAME_FURIGANA_JAPANESE') }}</span>
                    </div>
                    <!-- <b-badge
                      class="badge-required mx-2"
                      variant="light"
                    >必須</b-badge> -->
                    <Require />
                  </b-col>
                  <b-col cols="9" class="align-items-center my-2">
                    <b-form-input
                      v-model="formData.representative_full_name_furigana"
                      aria-label=""
                      :class="
                        error.representative_full_name_furigana === false
                          ? ' is-invalid'
                          : ''
                      "
                      class="input-representative-full-name-furigama"
                      dusk="representative_full_name_furigana"
                      @input="
                        handleChangeForm(
                          $event,
                          'representative_full_name_furigana'
                        )
                      "
                    />
                    <b-form-invalid-feedback
                      :state="error.representative_full_name_furigana"
                    >
                      {{ $t('VALIDATE.REQUIRED_TEXT') }}
                    </b-form-invalid-feedback>
                  </b-col>
                </div>
              </b-list-group-item>
              <!-- Representative contact 代表者連絡先 not required -->
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray justify-content-between"
                  ><span class="label-representative-contact">{{
                     $t('COMPANY.REPRESENTATIVE_CONTACT')
                   }}</span>
                    <!-- <b-badge
                      class="badge-not-required mx-2"
                      variant="secondary"
                    >{{ $t('ARBITRARY') }}</b-badge> -->
                    <Arbitrarily />
                  </b-col>
                  <b-col cols="9" class="d-flex align-items-center my-2">
                    <div class="d-flex w-100 flex-wrap">
                      <!-- Dropdown -->
                      <b-col
                        cols="3"
                        class="align-items-center pl-0"
                      >
                        <b-dropdown
                          id="representative_contact"
                          class="select-country"
                          :class="'option-validate'"
                          :text="formData.representative_contact_code"
                        >
                          <template #button-content>
                            <img
                              v-if="
                                formData.representative_contact_code === '+84'
                              "
                              :src="
                                require(`@/assets/images/icons/flag-84.png`)
                              "
                            >
                            <img
                              v-if="
                                formData.representative_contact_code === '+81'
                              "
                              :src="
                                require(`@/assets/images/icons/flag-81.png`)
                              "
                            >
                            <span>{{
                              formData.representative_contact_code
                            }}</span>
                          </template>
                          <!-- BLANK -->
                          <b-dropdown-item
                            @click="
                              handleChangeCountry(
                                'representative_contact_code',
                                ''
                              );
                              handleChangeFormOption(
                                $event,
                                'representative_contact_code'
                              );
                            "
                            @change="
                              handleChangeForm(
                                $event,
                                'representative_contact_code'
                              )
                            "
                          >
                            <span style="height: 28px" />
                          </b-dropdown-item>
                          <!-- VIE -->
                          <b-dropdown-item
                            @click="
                              handleChangeCountry(
                                'representative_contact_code',
                                '+84'
                              );
                              handleChangeFormOption(
                                $event,
                                'representative_contact_code'
                              );
                            "
                            @change="
                              handleChangeForm(
                                $event,
                                'representative_contact_code'
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
                                'representative_contact_code',
                                '+81'
                              );
                              handleChangeFormOption(
                                $event,
                                'representative_contact_code'
                              );
                            "
                            @change="
                              handleChangeForm(
                                $event,
                                'representative_contact_code'
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
                        <!-- <b-form-invalid-feedback
                          id="representative_contact"
                          :state="error.representative_contact_code"
                        >
                          {{ $t('VALIDATE.REQUIRED_SELECT') }}
                        </b-form-invalid-feedback> -->
                      </b-col>
                      <b-col cols="9" class="d-flex align-items-center p-0">
                        <b-form-input
                          v-model="formData.representative_contact"
                          aria-label=""
                          type="number"
                          class="input-representative-contact"
                          :formatter="format15characters"
                          :disabled="
                            formData.representative_contact_code === ''
                          "
                          @input="
                            handleChangeForm($event, 'representative_contact')
                          "
                        />
                      </b-col>
                    </div>
                  </b-col>
                </div>
              </b-list-group-item>
              <!-- Assignee contact  担当者連絡先 : required-->
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray justify-content-between"
                  ><span class="label-assignee-contact">{{
                     $t('COMPANY.ASSIGNEE_CONTACT')
                   }}</span>
                    <!-- <b-badge
                      class="badge-required mx-2"
                      variant="light"
                    >必須</b-badge> -->
                    <Require />
                  </b-col>
                  <b-col cols="9" class="d-flex align-items-center my-2">
                    <div class="d-flex w-100 flex-wrap">
                      <b-col cols="3" class="align-items-center pl-0">
                        <div
                          :class="
                            error.assignee_contact_code === false
                              ? 'option-error'
                              : 'option-validate'
                          "
                        >
                          <b-dropdown
                            id="assignee_contact"
                            class="select-country"
                          >
                            <template #button-content>
                              <img
                                v-if="formData.assignee_contact_code === '+84'"
                                :src="
                                  require(`@/assets/images/icons/flag-84.png`)
                                "
                              >
                              <img
                                v-if="formData.assignee_contact_code === '+81'"
                                :src="
                                  require(`@/assets/images/icons/flag-81.png`)
                                "
                              >

                              <span>{{ formData.assignee_contact_code }}</span>
                            </template>
                            <!-- BLANK -->
                            <b-dropdown-item
                              @click="
                                handleChangeCountry(
                                  'assignee_contact_code',
                                  ''
                                );
                                handleChangeFormOption(
                                  $event,
                                  'assignee_contact_code'
                                );
                              "
                              @change="
                                handleChangeForm(
                                  $event,
                                  'assignee_contact_code'
                                )
                              "
                            >
                              <span style="height: 28px" />
                            </b-dropdown-item>
                            <!-- VIE -->
                            <b-dropdown-item
                              @click="
                                handleChangeCountry(
                                  'assignee_contact_code',
                                  '+84'
                                );
                                handleChangeFormOption(
                                  $event,
                                  'assignee_contact_code'
                                );
                              "
                              @change="
                                handleChangeForm(
                                  $event,
                                  'assignee_contact_code'
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
                                  'assignee_contact_code',
                                  '+81'
                                );
                                handleChangeFormOption(
                                  $event,
                                  'assignee_contact_code'
                                );
                              "
                              @change="
                                handleChangeForm(
                                  $event,
                                  'assignee_contact_code'
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
                        </div>
                        <b-form-invalid-feedback
                          id="assignee_contact"
                          :state="error.assignee_contact_code"
                        >
                          {{ $t('VALIDATE.REQUIRED_SELECT') }}
                        </b-form-invalid-feedback>
                      </b-col>
                      <b-col cols="9" class="align-items-center p-0">
                        <b-form-input
                          v-model="formData.assignee_contact"
                          aria-label=""
                          type="number"
                          :class="
                            error.assignee_contact === false
                              ? ' is-invalid'
                              : ''
                          "
                          :formatter="format15characters"
                          class="input-assignee-contact"
                          dusk="assignee_contact"
                          :disabled="formData.assignee_contact_code === ''"
                          @input="handleChangeForm($event, 'assignee_contact')"
                        />
                        <b-form-invalid-feedback
                          :state="error.assignee_contact"
                        >
                          {{ $t('VALIDATE.REQUIRED_TEXT') }}
                        </b-form-invalid-feedback>
                      </b-col>
                    </div>
                  </b-col>
                </div>
              </b-list-group-item>
              <!-- Address -->
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray justify-content-between"
                  ><span class="label-address">{{
                     $t('COMPANY.ADDRESS')
                   }}</span>
                    <!-- <b-badge
                      class="badge-required mx-2"
                      variant="light"
                    >必須</b-badge> -->
                    <Require />
                  </b-col>
                  <b-col cols="9" class="d-flex align-items-center my-2">
                    <div class="d-flex w-100 flex-wrap">
                      <b-col
                        cols="3"
                        class="d-flex align-items-center p-0"
                      ><span class="label-post-code">{{
                        $t('COMPANY.POST_CODE')
                      }}</span>
                      </b-col>
                      <b-col cols="9" class="align-items-center p-0">
                        <b-form-input
                          v-model="formData.post_code"
                          aria-label=""
                          :formatter="format7characters"
                          :class="
                            error.post_code === false ? ' is-invalid' : ''
                          "
                          class="input-post-code"
                          dusk="post_code"
                          @input="handleChangeForm($event, 'post_code')"
                        />
                        <b-form-invalid-feedback :state="error.post_code">
                          {{ $t('VALIDATE.REQUIRED_TEXT') }}
                        </b-form-invalid-feedback>
                      </b-col>
                    </div>
                  </b-col>
                </div>
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray"
                  />
                  <b-col cols="9" class="d-flex align-items-center my-2">
                    <div class="d-flex w-100 flex-wrap">
                      <b-col
                        cols="3"
                        class="d-flex align-items-center p-0"
                      ><span class="label-city">{{
                        $t('COMPANY.CITY')
                      }}</span>
                      </b-col>
                      <b-col cols="9" class="align-items-center p-0">
                        <b-form-input
                          v-model="formData.prefectures"
                          :class="
                            error.prefectures === false ? ' is-invalid' : ''
                          "
                          class="input-city"
                          dusk="prefectures"
                          @input="handleChangeForm($event, 'prefectures')"
                        />
                        <b-form-invalid-feedback :state="error.prefectures">
                          {{ $t('VALIDATE.REQUIRED_TEXT') }}
                        </b-form-invalid-feedback>
                      </b-col>
                    </div>
                  </b-col>
                </div>
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray"
                  />
                  <b-col cols="9" class="d-flex align-items-center my-2">
                    <div class="d-flex w-100 flex-wrap">
                      <b-col
                        cols="3"
                        class="d-flex align-items-center p-0"
                      ><span class="label-distinct">{{
                        $t('COMPANY.DISTINCT')
                      }}</span>
                      </b-col>
                      <b-col cols="9" class="align-items-center p-0">
                        <b-form-input
                          v-model="formData.municipality"
                          :class="
                            error.municipality === false ? ' is-invalid' : ''
                          "
                          class="input-distinct"
                          dusk="municipality"
                          @input="handleChangeForm($event, 'municipality')"
                        />
                        <b-form-invalid-feedback :state="error.municipality">
                          {{ $t('VALIDATE.REQUIRED_TEXT') }}
                        </b-form-invalid-feedback>
                      </b-col>
                    </div>
                  </b-col>
                </div>
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray"
                  />
                  <b-col cols="9" class="d-flex align-items-center my-2">
                    <div class="d-flex w-100 flex-wrap">
                      <b-col
                        cols="3"
                        class="d-flex align-items-center p-0"
                      ><span class="label-number">{{
                        $t('COMPANY.NUMBER')
                      }}</span>
                      </b-col>
                      <b-col cols="9" class="align-items-center p-0">
                        <b-form-input
                          v-model="formData.number"
                          :class="error.number === false ? ' is-invalid' : ''"
                          class="input-number"
                          dusk="number"
                          @input="handleChangeForm($event, 'number')"
                        />
                        <b-form-invalid-feedback :state="error.number">
                          {{ $t('VALIDATE.REQUIRED_TEXT') }}
                        </b-form-invalid-feedback>
                      </b-col>
                    </div>
                  </b-col>
                </div>
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray"
                  />
                  <b-col cols="9" class="d-flex align-items-center my-2">
                    <div class="d-flex w-100 flex-wrap">
                      <b-col
                        cols="3"
                        class="d-flex align-items-center p-0"
                      ><span class="label-others">{{
                        $t('COMPANY.OTHERS')
                      }}</span>
                      </b-col>
                      <b-col cols="9" class="align-items-center p-0">
                        <b-form-input
                          v-model="formData.other"
                          :class="error.other === false ? ' is-invalid' : ''"
                          class="input-others"
                          @input="handleChangeForm($event, 'other')"
                        />
                        <b-form-invalid-feedback :state="error.other">
                          {{ $t('VALIDATE.REQUIRED_TEXT') }}
                        </b-form-invalid-feedback>
                      </b-col>
                    </div>
                  </b-col>
                </div>
              </b-list-group-item>
              <!-- Mail address -->
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray justify-content-between"
                  >
                    <div class="d-flex flex-column">
                      <span class="label-mail-address">{{
                        $t('COMPANY.MAIL_ADDRESS')
                      }}</span>
                      <span>（ログインID）</span>
                    </div>
                    <!-- <b-badge
                      class="badge-required mx-2"
                      variant="light"
                    >必須</b-badge> -->
                    <Require />
                  </b-col>
                  <b-col cols="9" class="align-items-center my-2">
                    <b-form-input
                      v-model="formData.mail_address"
                      :class="error.mail_address === false ? ' is-invalid' : ''"
                      class="input-mail-address"
                      dusk="mail_address"
                      @input="handleChangeForm($event, 'mail_address')"
                    />
                    <b-form-invalid-feedback :state="error.mail_address">
                      {{ $t('VALIDATE.REQUIRED_TEXT') }}
                    </b-form-invalid-feedback>
                  </b-col>
                </div>
              </b-list-group-item>
              <!-- URL -->
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray justify-content-between"
                  >
                    <!-- {{ $t('URL') }} -->
                    <span class="label-url">URL</span>
                    <!-- <b-badge
                      class="badge-required mx-2"
                      variant="light"
                    >必須</b-badge> -->
                    <Require />
                  </b-col>
                  <b-col cols="9" class="align-items-center my-2">
                    <b-form-input
                      v-model="formData.url"
                      :class="error.url === false ? ' is-invalid' : ''"
                      class="input-url"
                      dusk="url"
                      @input="handleChangeForm($event, 'url')"
                    />
                    <b-form-invalid-feedback :state="error.url">
                      {{ $t('VALIDATE.REQUIRED_TEXT') }}
                    </b-form-invalid-feedback>
                  </b-col>
                </div>
              </b-list-group-item>
              <!-- CERTIFICATE -->
              <b-list-group-item class="p-0">
                <div class="d-flex">
                  <b-col
                    cols="3"
                    class="d-flex align-items-center bg-gray justify-content-between"
                  ><span class="label-certificate">{{
                     $t('HR_REGISTER.LABEL.CERTIFICATE')
                   }}</span>
                    <!-- <b-badge
                      class="badge-required mx-2"
                      variant="light"
                    >必須</b-badge> -->
                    <Require />
                  </b-col>
                  <b-col cols="9" class="align-items-center my-2">
                    <div
                      class="upload-group d-flex my-2 flex-row align-items-start"
                    >
                      <input
                        id="upload-certificateFile"
                        ref="CertificateFile"
                        type="file"
                        class="d-none input-certificate"
                        @change="postCentificate"
                        @input="handleChangeForm($event, 'certificate_file_id')"
                      >
                      <label
                        for="upload-certificateFile"
                        class="btn-upload mb-1"
                      >
                        {{ $t('HR_REGISTER.SELECT_FILE') }}
                      </label>
                      <span style="margin-left: 10px">{{
                        certificate_file_name
                      }}</span>
                    </div>
                    <b-form-invalid-feedback
                      id="certificate_file_id"
                      :state="error.certificate_file_id"
                    >
                      {{ $t('VALIDATE.REQUIRED_TEXT') }}
                    </b-form-invalid-feedback>
                  </b-col>
                </div>
              </b-list-group-item>
            </b-list-group>
          </b-form>
        </div>
      </div>

      <b-modal
        ref="my-modal"
        v-model="modalConfirmUpdateStatus"
        hide-header
        hide-footer
        title=""
      >
        <div class="modal-body-content">
          <div
            class="w-100 modal-content-title-del-hr d-flex justify-center align-center"
          >
            <div class="d-flex" style="padding-top: 30px">
              <span>{{ $t('COMPANY.REALLY') }} +
                {{ getStatusByCode(statusSelected) }} +
                {{ $t('COMPANY.DO_YOU_WANT_TO_CHANGE_TO') }}</span>
            </div>
          </div>
          <div class="hr-list-btns">
            <div
              id="delete-all-item-hr"
              class="btn"
              @click="handleCancelUpdateStatus"
            >
              <span> {{ $t('BUTTON.CANCEL') }}</span>
            </div>
            <!-- Cancel -->
            <div
              id="import-csv"
              class="btn accept"
              dusk="btn_accept"
              @click="handleConfirmUpdateStatus"
            >
              <span>{{ $t('OK') }}</span>
            </div>
            <!-- delete -->
          </div>
        </div>
      </b-modal>
    </div>
  </b-overlay>
</template>

<script>
import {
  getDetailHrOrganization,
  updateStatusHrOrganization,
  updateHrOrganization,
} from '@/api/hrOrganization.js';
import {
  account_classification_option,
  country_option,
} from '@/const/hrOrganization.js';
import { uploadFile } from '@/api/uploadFile';
import { validEmail } from '@/utils/validate';
import { MakeToast } from '@/utils/toastMessage';
import { LIMIT_FILE_SIZE, FILE_TYPE } from '@/const/config.js';
import Require from '@/components/Require/Require.vue';
import Arbitrarily from '@/components/Arbitrarily/Arbitrarily.vue';

const FILE_CAN_UPLOAD = [FILE_TYPE.PDF, FILE_TYPE.MP3, FILE_TYPE.MP4];
export default {
  name: 'HrOrganizationEdit',
  components: {
    Require,
    Arbitrarily,
  },
  data() {
    return {
      overlay: {
        opacity: 0,
        show: false,
        blur: '1rem',
        rounded: 'sm',
        variant: 'light',
        fixed: true,
      },

      tabIndex: 0,
      isChangeStatus: false,
      modalConfirmUpdateStatus: false,
      statusSelected: 1,
      statusHrOrg: 1,
      noSort: true,
      checkbox: false,
      listId: [],
      closeMess: true,
      showModal: false,
      selectAll: false,
      message: {
        isShowMessage: false,
        isMessage: '',
      },

      queryData: {
        page: 1,
        per_page: 20,
        total_records: 0,
        search: '',
        order_type: '',
        order_column: '',
      },

      reRender: 0,

      state: {
        email: null,
        password: null,
        confirmPassword: null,
      },
      id: this.$route.params.id,
      formData: {
        corporate_name_en: '',
        corporate_name_ja: '',
        license_no: '',
        account_classification: 0,
        country: 0,
        representative_full_name: '',
        representative_full_name_furigana: '',
        representative_contact: '',
        representative_contact_code: '',
        assignee_contact: '',
        assignee_contact_code: '',
        post_code: '',
        prefectures: '',
        municipality: '',
        number: '',
        other: '',
        mail_address: '',
        url: '',
        certificate_file_id: '',
      },
      certificate_file_name: '',

      error: {
        corporate_name_en: null,
        corporate_name_ja: null,
        license_no: null,
        account_classification: null,
        country: null,
        representative_full_name: null,
        representative_full_name_furigana: null,
        representative_contact: null,
        representative_contact_code: null,
        assignee_contact: null,
        assignee_contact_code: null,
        post_code: null,
        prefectures: null,
        municipality: null,
        number: null,
        other: null,
        mail_address: null,
        url: null,
        certificate_file_id: null,
      },

      account_classification_option: account_classification_option,
      country_option: country_option,

      error_toast_message: '',
    };
  },

  computed: {
    role_type() {
      const PROFILE = this.$store.getters.profile;
      return PROFILE.type;
    },

    listUser() {
      return this.$store.getters.listUser;
    },
    currChange() {
      return this.queryData.page;
    },
    optionStatus() {
      if (this.statusHrOrg === 1) {
        return [
          {
            value: 1,
            text: '審査待ち',
            disabled: false,
          },
          {
            value: 2,
            text: '承認',
            disabled: false,
          },
          {
            value: 3,
            text: '却下',
            disabled: false,
          },
        ];
      }
      if (this.statusHrOrg === 2) {
        return [
          {
            value: 2,
            text: '承認',
            disabled: false,
          },
          {
            value: 4,
            text: '利用停止',
            disabled: false,
          },
        ];
      }
      if (this.statusHrOrg === 3) {
        return [
          {
            value: 3,
            text: '却下',
            disabled: false,
          },
        ];
      }
      if (this.statusHrOrg === 4) {
        return [
          {
            value: 4,
            text: '利用停止',
            disabled: false,
          },
        ];
      }
      return [];
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
    this.getDetail();
  },

  methods: {
    getStatusByCode(status_code) {
      let text = '';
      switch (status_code) {
        case 1:
          text = '審査待ち';
          break;
        case 2:
          text = '承認';
          break;
        case 3:
          text = '却下';
          break;
        case 4:
          text = '利用停止';
          break;

        default:
          break;
      }

      return text;
    },

    format15characters(e) {
      return String(e).substring(0, 15);
    },
    handleChangeFormOption(event, type_dropdown) {
      switch (type_dropdown) {
        // 9
        case 'representative_contact_code':
          this.error.representative_contact_code = true;
          if (event !== '') {
            this.error.representative_contact_code = true;
          } else {
            this.error.representative_contact_code = false;
          }
          break;
        // 10
        case 'assignee_contact_code':
          this.error.assignee_contact_code = true;
          if (event !== '') {
            this.error.assignee_contact_code = true;
          } else {
            this.error.assignee_contact_code = false;
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
        case 'corporate_name_ja':
          this.error.corporate_name_ja = null;
          if (newValue) {
            this.error.corporate_name_ja = true;
          } else {
            this.error.corporate_name_ja = false;
          }
          break;
        case 'license_no':
          this.error.license_no = null;
          if (newValue) {
            this.error.license_no = true;
          } else {
            this.error.license_no = false;
          }
          break;
        case 'account_classification':
          this.error.account_classification = null;
          if (newValue) {
            this.error.account_classification = true;
          } else {
            this.error.account_classification = false;
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
        case 'representative_full_name':
          this.error.representative_full_name = null;
          if (newValue) {
            this.error.representative_full_name = true;
          } else {
            this.error.representative_full_name = false;
          }
          break;
        case 'representative_full_name_furigana':
          this.error.representative_full_name_furigana = null;
          if (newValue) {
            this.error.representative_full_name_furigana = true;
          } else {
            this.error.representative_full_name_furigana = false;
          }
          break;
        case 'assignee_contact_code':
          this.error.assignee_contact_code = null;
          if (newValue) {
            this.error.assignee_contact_code = true;
          } else {
            this.error.assignee_contact_code = false;
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
        case 'post_code':
          this.error.post_code = null;
          if (newValue) {
            this.error.post_code = true;
          } else {
            this.error.post_code = false;
          }
          break;
        case 'prefectures':
          this.error.prefectures = null;
          if (newValue) {
            this.error.prefectures = true;
          } else {
            this.error.prefectures = false;
          }
          break;
        case 'municipality':
          this.error.municipality = null;
          if (newValue) {
            this.error.municipality = true;
          } else {
            this.error.municipality = false;
          }
          break;
        case 'number':
          this.error.number = null;
          if (newValue) {
            this.error.number = true;
          } else {
            this.error.number = false;
          }
          break;
        case 'other':
          this.error.other = null;
          if (newValue) {
            this.error.other = true;
          } else {
            this.error.other = false;
          }
          break;
        case 'mail_address':
          this.error.mail_address = null;
          if (newValue) {
            this.error.mail_address = true;
          } else {
            this.error.mail_address = false;
          }
          break;
        case 'url':
          this.error.url = null;
          if (newValue) {
            this.error.url = true;
          } else {
            this.error.url = false;
          }
          break;
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
      if (this.formData.municipality === '') {
        this.error.municipality = false;
      }
      if (this.formData.other === '') {
        this.error.other = false;
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

      if (this.formData.assignee_contact_code === '') {
        // option khác
        this.error.assignee_contact_code = false;
        this.error.assignee_contact = true;
      } else if (this.formData.assignee_contact_code !== '') {
        if (!this.formData.assignee_contact) {
          this.error.assignee_contact = false;
        } else {
          this.error.assignee_contact = true;
        }
      }

      if (
        this.formData.corporate_name_ja !== '' &&
        this.formData.corporate_name_en !== '' &&
        this.formData.license_no !== '' &&
        this.formData.account_classification.content !== '' &&
        this.formData.country.content !== '' &&
        this.formData.representative_full_name !== '' &&
        this.formData.representative_full_name_furigana !== '' &&
        this.formData.assignee_contact_code !== '' &&
        this.formData.assignee_contact !== '' &&
        this.formData.post_code !== '' &&
        this.formData.prefectures !== '' &&
        this.formData.municipality !== '' &&
        this.formData.number !== '' &&
        this.formData.other !== '' &&
        this.formData.mail_address !== '' &&
        this.formData.url !== '' &&
        // this.formData.certificate_file_name_file !== '' &&
        this.formData.certificate_file_id !== '' // Quan trọng
      ) {
        return true;
      }
    },

    checkEmail() {
      if (validEmail(this.formData.mail_address)) {
        return true;
      } else {
        return false;
      }
    },

    format7characters(e) {
      const inputValue = String(e).substring(0, 7); // Giới hạn 7 ký tự

      // Loại bỏ các ký tự tiếng Nhật từ giá trị nhập vào
      const filteredValue = inputValue.replace(/[ぁ-んァ-ン一-龯。]/g, '');

      return filteredValue;
    },
    handleCancelUpdateStatus() {
      this.modalConfirmUpdateStatus = false;
      // this.handleSaveUpdate();
    },

    handleConfirmUpdate() {
      if (this.isChangeStatus) {
        this.modalConfirmUpdateStatus = true;
      } else {
        this.handleSaveUpdate();
      }
    },

    async handleConfirmUpdateStatus() {
      this.modalConfirmUpdateStatus = false;
      this.overlay.show = true;
      await this.handleUpdateStatus();
      this.handleSaveUpdate();
    },

    async handleSaveUpdate() {
      this.checkvalidate();

      const resCheckvalidate = this.checkvalidate();
      const resCheckEmail = this.checkEmail();

      if (resCheckvalidate) {
        if (!resCheckEmail) {
          MakeToast({
            variant: 'warning',
            title: this.$t('WARNING'),
            content: this.$t('PLEASE_ENTER_THE_CORRECT_EMAIL_ADDRESS_FORMAT'),
          });
        }
      } else {
        MakeToast({
          variant: 'warning',
          title: this.$t('WARNING'),
          content: this.$t('HR_REGISTER.PLEASE_ENTER_ALL_REQUIRED_INFORMATION'),
        });
      }

      if (resCheckvalidate && resCheckEmail) {
        const DATA = {
          id: this.id,
          ...this.formData,
          assignee_contact:
            this.formData?.assignee_contact_code &&
            this.formData?.assignee_contact
              ? this.formData.assignee_contact_code +
                ' ' +
                this.formData.assignee_contact
              : '',
          representative_contact:
            this.formData?.representative_contact_code &&
            this.formData?.representative_contact
              ? this.formData.representative_contact_code +
                ' ' +
                this.formData.representative_contact
              : '',
        };
        delete DATA.assignee_contact_code;
        delete DATA.representative_contact_code;

        // console.log('DATA update: ', DATA);

        try {
          const res = await updateHrOrganization(DATA);
          const { code, message } = res.data;
          if (code === 200) {
            // update success: 更新の成功
            if (this.error_toast_message) {
              MakeToast({
                variant: 'danger',
                title: this.$t('DANGER'),
                content: this.error_toast_message,
              });
            } else {
              MakeToast({
                variant: 'success',
                title: this.$t('SUCCESS'),
                content: message || this.$t('HR_ORG_COMPANY_UPDATE_SUCCESS'),
              });
            }
            this.$router.push({
              path: `/hr-organization/detail/${this.id}`,
            });
          } else {
            MakeToast({
              variant: 'danger',
              title: this.$t('DANGER'),
              content: this.error_toast_message,
            });
            MakeToast({
              variant: 'danger',
              title: this.$t('DANGER'),
              content: message,
            });
          }
          this.error_toast_message = '';
        } catch (error) {
          console.log(error);
        }
      }
      this.overlay.show = false;
    },

    async getDetail() {
      this.overlay.show = true;
      await getDetailHrOrganization(this.id).then((res) => {
        const { data } = res;
        if (data.code === 200) {
          this.formData.corporate_name_en = data.data.corporate_name_en;
          this.formData.corporate_name_ja = data.data.corporate_name_ja;
          this.formData.license_no = data.data.license_no;
          this.formData.account_classification =
            data.data.account_classification;
          this.formData.country = data.data.country;
          this.formData.representative_full_name =
            data.data.representative_full_name;
          this.formData.representative_full_name_furigana =
            data.data.representative_full_name_furigana;

          this.formData.representative_contact = this.convertContact(
            'representative_contact',
            data.data.representative_contact
          );
          this.formData.representative_contact_code = this.convertContact(
            'representative_contact_code',
            data.data.representative_contact
          );
          this.formData.assignee_contact = this.convertContact(
            'assignee_contact',
            data.data.assignee_contact
          );
          this.formData.assignee_contact_code = this.convertContact(
            'assignee_contact_code',
            data.data.assignee_contact
          );

          this.formData.post_code = data.data.post_code;
          this.formData.prefectures = data.data.prefectures;
          this.formData.municipality = data.data.municipality;
          this.formData.number = data.data.number;
          this.formData.other = data.data.other;
          this.formData.mail_address = data.data.mail_address;
          this.formData.url = data.data.url;
          this.formData.certificate_file_id = data.data.file.id;

          this.certificate_file_name = data.data.file.file_name;
          this.statusSelected = data.data.status;
          this.statusHrOrg = data.data.status;
        }
      });
      this.overlay.show = false;
    },

    convertContact(type, data) {
      if (!data) {
        return '';
      }
      if (type === 'representative_contact') {
        const contact = data.split(' ');
        return contact[1];
      }
      if (type === 'representative_contact_code') {
        const contact = data.split(' ');
        return contact[0];
      }
      if (type === 'assignee_contact') {
        const contact = data.split(' ');
        return contact[1];
      }
      if (type === 'assignee_contact_code') {
        const contact = data.split(' ');
        return contact[0];
      }
    },

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
        const { code, data, message } = res.data;
        if (code === 200) {
          this.formData.certificate_file_id = data.id;
          this.certificate_file_name = data.file_name;
        } else {
          MakeToast({
            variant: 'warning',
            title: 'Danger',
            content: message,
          });
        }
        this.overlay.show = false;
      } catch (error) {
        console.log(error);
        this.overlay.show = false;
      }
    },

    handleBackToDetail() {
      this.$router.push({
        path: `/hr-organization/detail/${this.id}`,
      });
    },

    handleChangeStatus() {
      if (this.statusSelected === this.statusHrOrg) {
        this.isChangeStatus = false;
      } else {
        this.isChangeStatus = true;
      }
    },

    async handleUpdateStatus() {
      try {
        const PARAM = {
          hr_id: this.id,
          status: this.statusSelected,
        };
        const res = await updateStatusHrOrganization(PARAM);
        const { code, message } = res.data;
        if (code === 200) {
          this.isChangeStatus = false;
          this.statusHrOrg = this.statusSelected;
          // MakeToast({
          //   variant: 'success',
          //   title: this.$t('SUCCESS'),
          //   content: message,
          // });
        } else {
          // MakeToast({
          //   variant: 'danger',
          //   title: this.$t('DANGER'),
          //   content: message,
          // });
          this.error_toast_message = message;
        }
      } catch (error) {
        console.log(error);
      }
    },

    handleChangeCountry(type_select, countryCode) {
      if (countryCode === '') {
        this.formData.assignee_contact = '';
      }
      // this.selectedCountry = countryCode;
      if (type_select === 'representative_contact_code') {
        this.formData.representative_contact_code = countryCode;
      }
      if (type_select === 'assignee_contact_code') {
        this.formData.assignee_contact_code = countryCode;
      }
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/components/Modal/ModalStyle.scss';
@import '@/pages/RegisterHrOrigin/RegisterHrOrigin.scss';

.border-left-title {
  border-left: 4px solid #314cad;
  height: 36px;
  line-height: 36px;
}

.card-body {
  padding: 0;
}

.bg-gray {
  background-color: #f8f8f8;
}

.certificate-file-name-hr-org {
  max-width: 100%;
}
</style>
