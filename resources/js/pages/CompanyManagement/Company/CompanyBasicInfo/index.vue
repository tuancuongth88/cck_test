<!-- CompanyBasicInfo -->
<template>
  <div class="hr-registration-page-autox mt-1">
    <div class="hr-registration-page__form">

      <!-- <div>major_classification_id: {{ formData.major_classification_id }}</div><br> -->
      <!-- <div>middle_classification_id: {{ formData.middle_classification_id }}</div><br> -->
      <!-- <button style="position: fixed; background: gray; opacity: 0.1;" class="btn" @click="changeStatus">Change</button> -->

      <!-- 1 input 企業名 company name -->
      <div v-if="type_form === 'edit'" class="row-item border-t border-l border-r">
        <div class="form-title">
          <span>{{ $t('JOB_DETAIL.COMPANY_NAME') }}</span><Require />
        </div>
        <div class="form-inputs border-l">
          <!-- aria-describedby="corporate_name_en" -->
          <!-- id="corporate_name_en" -->
          <b-form-input
            v-model="formData.company_name"
            aria-describedby="company_name"
            max-lenght="50"
            :name="'company_name'"
            :formatter="format50characters"
            :placeholder="''"
            enabled
            :class="error.company_name === false ? 'is-invalid' : ''"
            @input="handleChangeForm($event, 'company_name')"
          />
          <b-form-invalid-feedback id="company_name" :state="error.company_name">
            {{ $t('VALIDATE.REQUIRED_TEXT') }}
          </b-form-invalid-feedback>
        </div>
      </div>
      <div v-if="type_form === 'detail'" class="row-item border-t border-l border-r">
        <div class="form-title" :class="{ 'bg-type-detail' : type_form === 'detail' }">
          <span>{{ $t('JOB_DETAIL.COMPANY_NAME') }}</span>
        </div>
        <div class="form-inputs border-l">
          <div>
            <span>{{ formData.company_name }}</span>
          </div>
        </div>
      </div>
      <!-- 2 input 企業名（ﾌﾘｶﾞﾅ） company name (furigana) -->
      <div v-if="type_form === 'edit'" class="row-item border-t border-l border-r">
        <div class="form-title">
          <span>{{ $t('JOB_DETAIL.COMPANY_NAME_FURIGANA') }}</span><Require />
        </div>
        <div class="form-inputs border-l">
          <!-- aria-describedby="corporate_name_en" -->
          <!-- id="corporate_name_en" -->
          <b-form-input
            v-model="formData.company_name_jp"
            aria-describedby="company_name_jp"
            max-lenght="50"
            :name="'company_name_jp'"
            :formatter="format50characters"
            :placeholder="''"
            enabled
            :class="error.company_name_jp === false ? 'is-invalid' : ''"
            @input="handleChangeForm($event, 'company_name_jp')"
          />
          <b-form-invalid-feedback id="company_name_jp" :state="error.company_name_jp">
            {{ $t('VALIDATE.REQUIRED_TEXT') }}
          </b-form-invalid-feedback>
        </div>
      </div>
      <div v-if="type_form === 'detail'" class="row-item border-t border-l border-r">
        <div class="form-title" :class="{ 'bg-type-detail' : type_form === 'detail' }">
          <span>{{ $t('JOB_DETAIL.COMPANY_NAME_FURIGANA') }}</span>
        </div>
        <div class="form-inputs border-l">
          <div>
            <span>{{ formData.company_name_jp }}</span>
          </div>
        </div>
      </div>
      <!-- 3 option 業種分野 field / Industry field-->
      <div v-if="type_form === 'edit'" class="row-item border-t border-l border-r">
        <div class="form-title align-start">
          <span>{{ $t('COMPANY_REGISTER.FIELD') }}</span><Require />
        </div>
        <div class="form-inputs border-l" style="gap: 1rem">
          <!-- 3.1 option 大分類 major classification -->
          <div class="w-100 d-flex justify-start align-center">
            <div style="width: 30%; min-width: 176px">
              <div>
                <span>{{ $t('COMPANY_REGISTER.MAJOR_CLASSIFICATION') }}</span>
              </div>
            </div>
            <!--  -->
            <div class="w-100">
              <b-form-select
                v-model="formData.major_classification_id"
                :options="major_classification_options"
                value-field="id"
                text-field="name_ja"
                :class=" error.major_classification_id === false ? 'is-invalid' : '' "
                @input="handleChangeForm($event, 'major_classification_id')"
                @change="
                  renderChildOption(formData.major_classification_id)
                  selectRenderText( 'major_classification_id', $event, major_classification_options)
                "
              />
              <b-form-invalid-feedback id="major_classification_id" :state="error.major_classification_id">
                {{ $t('VALIDATE.REQUIRED_SELECT') }}
              </b-form-invalid-feedback>
            </div>
          </div>
          <!-- 3.2 option 中分類 middle classification -->
          <div class="w-100 d-flex justify-start align-center">
            <div style="width: 30%; min-width: 176px">
              <div>
                <span>{{ $t('COMPANY_REGISTER.MIDDLE_CLASSIFICATION') }}</span>
              </div>
            </div>
            <!--  -->
            <div class="w-100">
              <b-form-select
                v-model="formData.middle_classification_id"
                :options="middle_classification_options"
                value-field="id"
                text-field="name_ja"
                :enabled="formData.major_classification_id"
                :disabled="!formData.major_classification_id"
                :class=" error.middle_classification_id === false ? 'is-invalid' : '' "
                @input="handleChangeForm($event, 'middle_classification_id') "
                @change="selectRenderText( 'middle_classification_id', $event, middle_classification_options)"
              />
              <b-form-invalid-feedback id="middle_classification_id" :state="error.middle_classification_id">
                {{ $t('VALIDATE.REQUIRED_SELECT') }}
              </b-form-invalid-feedback>
            </div>
          </div>
          <!--  -->
        </div>
      </div>
      <div v-if="type_form === 'detail'" class="row-item border-t border-l border-r">
        <div class="form-title align-start" :class="{ 'bg-type-detail' : type_form === 'detail' }">
          <span>{{ $t('COMPANY_REGISTER.FIELD') }}</span>
        </div>
        <div class="form-inputs border-l" style="gap: 1rem">
          <!-- 3.1 option 大分類 major classification -->
          <div class="w-100 d-flex justify-start align-center">
            <div style="width: 30%; min-width: 176px">
              <div>
                <span>{{ $t('COMPANY_REGISTER.MAJOR_CLASSIFICATION') }}</span>
              </div>
            </div>
            <!--  -->
            <div class="w-100 pl-5">
              <div>
                <span>{{ formData.major_classification_text }}</span>
              </div>
            </div>
          </div>
          <!-- 3.2 option 中分類 middle classification -->
          <div class="w-100 d-flex justify-start align-center">
            <div style="width: 30%; min-width: 176px">
              <div>
                <span>{{ $t('COMPANY_REGISTER.MIDDLE_CLASSIFICATION') }}</span>
              </div>
            </div>
            <!--  -->
            <div class="w-100 pl-5">
              <div>
                <span>{{ formData.middle_classification_text }}</span>
              </div>
            </div>
          </div>

          <!--  -->
        </div>
      </div>

      <!-- 4 input 住所 Address -->
      <div v-if="type_form === 'edit'" class="row-item border-t border-l border-r">
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
                max-lenght="50"
                type="text"
                :name="'post_code'"
                :formatter="format7characters"
                :placeholder="''"
                class="w-100"
                :class="error.post_code === false ? 'is-invalid' : ''"
                @input="handleChangeForm($event, 'post_code')"
              />
              <b-form-invalid-feedback id="post_code" :state="error.post_code">
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
                max-lenght="50"
                :name="'prefectures'"
                :formatter="format50characters"
                :placeholder="''"
                class="w-100"
                :class=" error.prefectures === false ? 'is-invalid' : '' "
                @input="handleChangeForm($event, 'prefectures')"
              />
              <b-form-invalid-feedback id="prefectures" :state="error.prefectures">
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
                max-lenght="50"
                :name="'municipality'"
                :formatter="format50characters"
                :placeholder="''"
                class="w-100"
                :class="
                  error.municipality === false ? 'is-invalid' : ''
                "
                @input="handleChangeForm($event, 'municipality')"
              />
              <b-form-invalid-feedback id="municipality" :state="error.市町村区">
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
                max-lenght="50"
                :name="'number'"
                :formatter="format50characters"
                :placeholder="''"
                class="w-100"
                :class="error.number === false ? 'is-invalid' : ''"
                @input="handleChangeForm($event, 'number')"
              />
              <b-form-invalid-feedback id="number" :state="error.number">
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
                max-lenght="50"
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
      <div v-if="type_form === 'detail'" class="row-item border-t border-l border-r">
        <div class="form-title align-start" :class="{ 'bg-type-detail' : type_form === 'detail' }">
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
      <div v-if="type_form === 'edit'" class="row-item border-t border-l border-r">
        <div class="form-title">
          <span>{{ $t('COMPANY_REGISTER.TELEPHONE_NUMBER') }}</span><Require />
        </div>
        <div class="form-inputs border-l">
          <div class="w-100 d-flex justify-start align-start" style="gap: 1rem">
            <!-- Dropdown -->
            <div class="h-100" style="height: 40px">
              <div
                :class="error.telephone_number_id === false ? 'option-error' : 'option-validate'"
              >
                <div class="option-area-code">
                  <!-- <b-form-select
                    v-model="formData.telephone_number_id"
                    :options="area_code_option"
                    :class=" error.telephone_number_id === false ? 'is-invalid' : '' "
                    @input=" handleChangeForm($event, 'telephone_number_id') "
                    @change="pustAreaCode( 'telephone_number_id', formData.telephone_number_id )"
                  /> -->
                  <b-dropdown
                    id="telephone_number"
                    :text="display_area_code.telephone_number"
                    class="w-100 h-100"
                  >
                    <!-- <b-dropdown-item
                      class="w-100 d-flex justify-start align-center "
                      @click="
                        pustAreaCode('telephone_number','')
                        handleChangeFormOption($event, 'telephone_number_id')
                      "
                      @change="
                        handleChangeForm($event, 'telephone_number_id')
                      "
                    >
                      <img style="visibility: hidden;" :src="require(`@/assets/images/icons/flag-84.png`)">
                    </b-dropdown-item> -->
                    <!--  -->
                    <b-dropdown-item
                      @click="
                        pustAreaCode('telephone_number','+84')
                        handleChangeFormOption($event, 'telephone_number_id')
                      "
                      @change="
                        handleChangeForm($event, 'telephone_number_id')
                      "
                    >
                      <img :src="require(`@/assets/images/icons/flag-84.png`)">
                      <span>+84</span>
                    </b-dropdown-item>
                    <!--  -->
                    <b-dropdown-item
                      @click="
                        pustAreaCode('telephone_number','+81')
                        handleChangeFormOption($event, 'telephone_number_id')
                      "
                      @change="
                        handleChangeForm($event, 'telephone_number_id')
                      "
                    >
                      <img :src="require(`@/assets/images/icons/flag-81.png`)">
                      <span>+81</span>
                    </b-dropdown-item>
                  </b-dropdown>

                  <img
                    v-if="display_area_code.telephone_number === '+84'"
                    :src="require(`@/assets/images/icons/flag-84.png`)"
                  >
                  <img
                    v-if="display_area_code.telephone_number === '+81'"
                    :src="require(`@/assets/images/icons/flag-81.png`)"
                  >
                </div>
              </div>
              <b-form-invalid-feedback id="telephone_number" :state="error.telephone_number_id">
                {{ $t('VALIDATE.REQUIRED_SELECT') }}
              </b-form-invalid-feedback>
            </div>
            <!-- Input -->
            <div class="w-100">
              <b-form-input
                v-model="formData.telephone_number"
                aria-describedby="telephone_number"
                max-lenght="50"
                type="number"
                :name="'telephone_number'"
                :formatter="format50characters"
                :placeholder="$t('COMPANY_REGISTER.PLACEHOLDER')"
                :class="error.telephone_number === false ? 'is-invalid' : ''"
                :enabled="display_area_code.telephone_number"
                :disabled="!display_area_code.telephone_number"
                @input="handleChangeForm($event, 'telephone_number')"
              />
              <b-form-invalid-feedback id="telephone_number" :state="error.telephone_number">
                {{ $t('VALIDATE.REQUIRED_TEXT') }}
              </b-form-invalid-feedback>
            </div>
            <!--  -->
          </div>

        </div>
      </div>
      <div v-if="type_form === 'detail'" class="row-item border-t border-l border-r">
        <div class="form-title" :class="{ 'bg-type-detail' : type_form === 'detail' }">
          <span>{{ $t('COMPANY_REGISTER.TELEPHONE_NUMBER') }}</span>
        </div>
        <div class="form-inputs border-l">
          <div class="w-100 d-flex justify-start align-center" style="gap: 0.75rem">
            <div>
              <span>{{ display_area_code.telephone_number }} {{ formData.telephone_number }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- 6 input メールアドレス (ログインID) Email address (login ID) -->
      <div v-if="type_form === 'edit'" class="row-item border-t border-l border-r">
        <div class="form-title">
          <span>{{ $t('COMPANY_REGISTER.EMAIL_ADDRESS_LOGIN_ID') }}</span><Require />
        </div>
        <div class="form-inputs border-l">
          <!-- aria-describedby="corporate_name_en" -->
          <!-- id="corporate_name_en" -->
          <b-form-input
            v-model="formData.mail_address"
            aria-describedby="mail_address"
            max-lenght="50"
            :name="'mail_address'"
            :formatter="format50characters"
            :placeholder="''"
            enabled
            :class="error.mail_address === false ? 'is-invalid' : ''"
            @input="handleChangeForm($event, 'mail_address')"
          />
          <b-form-invalid-feedback id="mail_address" :state="error.mail_address">
            {{ $t('VALIDATE.REQUIRED_TEXT') }}
          </b-form-invalid-feedback>
        </div>
      </div>
      <div v-if="type_form === 'detail'" class="row-item border-t border-l border-r">
        <div class="form-title" :class="{ 'bg-type-detail' : type_form === 'detail' }">
          <span>{{ $t('COMPANY_REGISTER.EMAIL_ADDRESS_LOGIN_ID') }}</span>
        </div>
        <div class="form-inputs border-l">
          <div>
            <span>{{ formData.mail_address }}</span>
          </div>
        </div>
      </div>
      <!-- 7 input URL -->
      <div v-if="type_form === 'edit'" class="row-item border-t border-l border-r">
        <div class="form-title">
          <span style="text-transform: capitalize">URL</span><Require />
        </div>
        <div class="form-inputs border-l">
          <!-- aria-describedby="corporate_name_en" -->
          <!-- id="corporate_name_en" -->
          <b-form-input
            v-model="formData.url"
            aria-describedby="url"
            max-lenght="50"
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
      <div v-if="type_form === 'detail'" class="row-item border-t border-l border-r">
        <div class="form-title" :class="{ 'bg-type-detail' : type_form === 'detail' }">
          <span style="text-transform: capitalize">URL</span>
        </div>
        <div class="form-inputs border-l">
          <div>
            <span>{{ formData.url }}</span>
          </div>
        </div>
      </div>

      <!-- 8 input 代表者 representative -->
      <div v-if="type_form === 'edit'" class="row-item border-t border-l border-r">
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
                max-lenght="50"
                :name="'job_title'"
                :formatter="format50characters"
                :placeholder="''"
                class="w-100"
                :class="
                  error.job_title === false ? 'is-invalid' : ''
                "
                @input="handleChangeForm($event, 'job_title')"
              />
              <b-form-invalid-feedback id="job_title" :state="error.市町村区">
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
                max-lenght="50"
                :name="'full_name'"
                :formatter="format50characters"
                :placeholder="''"
                class="w-100"
                :class="error.full_name === false ? 'is-invalid' : ''"
                @input="handleChangeForm($event, 'full_name')"
              />
              <b-form-invalid-feedback id="full_name" :state="error.full_name">
                {{ $t('VALIDATE.REQUIRED_TEXT') }}
              </b-form-invalid-feedback>
            </div>
          </div>
          <!-- 8.3 input 氏名 (フリガナ) Full name (furigana) -->
          <div class="w-100 d-flex justify-start align-center">
            <div style="width: 30%; min-width: 176px">
              <div>
                <span>{{ $t('COMPANY_REGISTER.FULL_NAME_FURIGANA') }}</span>
              </div>
            </div>
            <!--  -->
            <div class="w-100">
              <b-form-input
                v-model="formData.full_name_furigana"
                enabled
                aria-describedby="full_name_furigana"
                max-lenght="50"
                :name="'full_name_furigana'"
                :formatter="format50characters"
                :placeholder="''"
                class="w-100"
                :class="error.full_name_furigana === false ? 'is-invalid' : ''"
                @input="handleChangeForm($event, 'full_name_furigana')"
              />
              <b-form-invalid-feedback id="full_name" :state="error.full_name_furigana">
                {{ $t('VALIDATE.REQUIRED_TEXT') }}
              </b-form-invalid-feedback>
            </div>
          </div>
          <!--  -->
        </div>
      </div>
      <div v-if="type_form === 'detail'" class="row-item border-t border-l border-r">
        <div class="form-title align-start" :class="{ 'bg-type-detail' : type_form === 'detail' }">
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
                <span>{{ $t('COMPANY_REGISTER.FULL_NAME_FURIGANA') }}</span>
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
      <div v-if="type_form === 'edit'" class="row-item border-t border-l border-r">
        <div class="form-title">
          <span>{{ $t('COMPANY_REGISTER.REPRESENTATIVE_CONTACT') }}</span><Arbitrarily />
        </div>
        <div class="form-inputs border-l">
          <div class="w-100 d-flex justify-start align-start" style="gap: 1rem">
            <!-- Dropdown -->
            <div class="h-100" style="height: 40px">
              <div
                :class="error.representative_contact_id === false ? 'option-error' : 'option-validate'"
              >
                <div class="option-area-code">
                  <b-dropdown
                    id="representative_contact"
                    :text="display_area_code.representative_contact"
                    class="w-100  h-100"
                  >
                    <!--  -->
                    <b-dropdown-item
                      @click="
                        pustAreaCode('representative_contact','+84')
                        handleChangeFormOption($event, 'representative_contact_id')"
                      @change="handleChangeForm($event, 'representative_contact_id')"
                    >
                      <img :src="require(`@/assets/images/icons/flag-84.png`)">
                      <span>+84</span>
                    </b-dropdown-item>
                    <!--  -->
                    <b-dropdown-item
                      @click="
                        pustAreaCode('representative_contact','+81')
                        handleChangeFormOption($event, 'representative_contact_id')"
                      @change="handleChangeForm($event, 'representative_contact_id')"
                    >
                      <img :src="require(`@/assets/images/icons/flag-81.png`)">
                      <span>+81</span>
                    </b-dropdown-item>
                  </b-dropdown>

                  <img
                    v-if="display_area_code.representative_contact === '+84'"
                    :src="require(`@/assets/images/icons/flag-84.png`)"
                  >
                  <img
                    v-if="display_area_code.representative_contact === '+81'"
                    :src="require(`@/assets/images/icons/flag-81.png`)"
                  >
                </div>
              </div>
            </div>
            <!-- Input -->
            <div class="w-100">
              <b-form-input
                v-model="formData.representative_contact"
                aria-describedby="representative_contact"
                max-lenght="50"
                type="number"
                :name="'representative_contact'"
                :formatter="format50characters"
                :placeholder="$t('COMPANY_REGISTER.PLACEHOLDER')"
                :enabled="display_area_code.representative_contact"
                :disabled="!display_area_code.representative_contact"
                @input="handleChangeForm($event, 'representative_contact')"
              />
            </div>
            <!--  -->
          </div>

        </div>
      </div>
      <div v-if="type_form === 'detail'" class="row-item border-t border-l border-r">
        <div class="form-title" :class="{ 'bg-type-detail' : type_form === 'detail' }">
          <span>{{ $t('COMPANY_REGISTER.REPRESENTATIVE_CONTACT') }}</span>
        </div>
        <div class="form-inputs border-l">
          <div class="w-100 d-flex justify-start align-center" style="gap: 0.75rem">
            <div>
              <span>{{ display_area_code.representative_contact }} {{ formData.representative_contact }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- 10 option input Assignee contact 担当者連絡先 -->
      <div v-if="type_form === 'edit'" class="row-item border-t border-l border-r border-b">
        <div class="form-title">
          <span>{{ $t('COMPANY_REGISTER.ASSIGNEE_CONTACT') }}</span><Require />
        </div>
        <div class="form-inputs border-l">
          <div class="w-100 d-flex justify-start align-start" style="gap: 1rem">
            <!-- Dropdown -->
            <div class="h-100" style="height: 40px">
              <div :class="error.assignee_contact_id === false ? 'option-error' : 'option-validate'">
                <div class="option-area-code">
                  <b-dropdown
                    id="assignee_contact"
                    :text="display_area_code.assignee_contact"
                    class="w-100  h-100"
                  >
                    <!--  -->
                    <b-dropdown-item
                      @click="
                        pustAreaCode('assignee_contact','+84')
                        handleChangeFormOption($event, 'assignee_contact_id')"
                      @change="handleChangeForm($event, 'assignee_contact_id')"
                    >
                      <img :src="require(`@/assets/images/icons/flag-84.png`)">
                      <span>+84</span>
                    </b-dropdown-item>
                    <!--  -->
                    <b-dropdown-item
                      @click="
                        pustAreaCode('assignee_contact','+81')
                        handleChangeFormOption($event, 'assignee_contact_id')"
                      @change="handleChangeForm($event, 'assignee_contact_id')"
                    >
                      <img :src="require(`@/assets/images/icons/flag-81.png`)">
                      <span>+81</span>
                    </b-dropdown-item>
                  </b-dropdown>

                  <img
                    v-if="display_area_code.assignee_contact === '+84'"
                    :src="require(`@/assets/images/icons/flag-84.png`)"
                  >
                  <img
                    v-if="display_area_code.assignee_contact === '+81'"
                    :src="require(`@/assets/images/icons/flag-81.png`)"
                  >
                </div>
              </div>
              <b-form-invalid-feedback id="assignee_contact" :state="error.assignee_contact_id">
                {{ $t('VALIDATE.REQUIRED_SELECT') }}
              </b-form-invalid-feedback>
            </div>
            <!-- Input -->
            <div class="w-100">
              <b-form-input
                v-model="formData.assignee_contact"
                aria-describedby="assignee_contact"
                max-lenght="50"
                type="number"
                :name="'assignee_contact'"
                :formatter="format50characters"
                :placeholder="$t('COMPANY_REGISTER.PLACEHOLDER')"
                :class="error.assignee_contact === false ? 'is-invalid' : ''"
                :enabled="display_area_code.assignee_contact"
                :disabled="!display_area_code.assignee_contact"
                @input="handleChangeForm($event, 'assignee_contact')"
              />
              <b-form-invalid-feedback id="assignee_contact" :state="error.assignee_contact">
                {{ $t('VALIDATE.REQUIRED_TEXT') }}
              </b-form-invalid-feedback>
            </div>
            <!--  -->
          </div>

        </div>
      </div>
      <div v-if="type_form === 'detail'" class="row-item border-t border-l border-r border-b">
        <div class="form-title" :class="{ 'bg-type-detail' : type_form === 'detail' }">
          <span>{{ $t('COMPANY_REGISTER.ASSIGNEE_CONTACT') }}</span>
        </div>
        <div class="form-inputs border-l">
          <div class="w-100 d-flex justify-start align-center" style="gap: 0.75rem">
            <div>
              <span>{{ display_area_code.assignee_contact }} {{ formData.assignee_contact }}</span>
            </div>
          </div>
        </div>
      </div>

      <!--  -->
      <!-- hr-registration-page__form -->
    </div>
  </div>
</template>

<script>
import { MakeToast } from '@/utils/toastMessage';
import Require from '@/components/Require/Require.vue';
import Arbitrarily from '@/components/Arbitrarily/Arbitrarily.vue';
import { account_classification_option } from '@/const/hrOrganization.js';
import { getListMainjob } from '@/api/job';

// const urlAPI = {
//   urlGetLisUser: '/user',
//   urlDeleAll: 'user/ ',
// };
export default {
  name: 'CompanyBasicInfo',
  components: {
    Require,
    Arbitrarily,
  },
  props: {
    formDataCompany: {
      type: Object,
      required: true,
    },
    errorCompany: {
      type: Object,
      required: true,
    },
    displayAreaCode: {
      type: Object,
      required: true,
    },
    // majoOptions: {
    //   type: Object,
    //   required: true,
    // },
    // middleOptions: {
    //   type: Object,
    //   required: true,
    // },

  },

  data() {
    return {
      status_agree_with_terms_conditions: false,
      overlay: {
        show: false,
        variant: 'light',
        opacity: 0.2,
        blur: '1rem',
        rounded: 'sm',
      },
      //  type_form: 'detail',
      type_form: 'edit',
      formData: this.formDataCompany,
      display_area_code: this.displayAreaCode,

      // major_classification_options: this.majoOptions,
      // middle_classification_options: this.middleOptions,
      major_classification_options: [],
      middle_classification_options: [],

      account_classification_option: account_classification_option,
      error: this.errorCompany,
      //
    };
  },

  computed: {
    // listUser() {
    //   return this.$store.getters.listUser;
    // },
    // currChange() {
    //   return this.queryData.page;
    // },
  },

  created() {
    this.getListMainjob();
    this.getPathUrl();
  },

  methods: {
    getPathUrl() {
      const path = this.$route.path;
      if (path.includes('detail')) {
        this.path_company = path;
        this.type_form = 'detail';
      }
      if (path.includes('edit')) {
        this.path_company = path;
        this.type_form = 'edit';
      }
    },
    changeStatus() {
      if (this.type_form === 'detail') {
        this.type_form = 'edit';
      } else if (this.type_form === 'edit') {
        this.type_form = 'detail';
      }
    },
    format7characters(e) {
      return String(e).substring(0, 7);
    },
    format50characters(e) {
      return String(e).substring(0, 50);
    },

    pustAreaCode(type_select, type_option) {
      this.$emit('pust-area-code', type_select, type_option);
    },

    selectRenderText(type_select, id_select, option_select){
      this.$emit('select-render-text', type_select, id_select, option_select);
    },

    phonePutApi(area_code, string_phone) {
      this.$emit('phone-put-api', area_code, string_phone);
    },

    handleChangeForm(event, field) {
      this.$emit('handle-change-form', event, field);
    },

    checkValidate() {
      this.$emit('check-validate');
    },

    checkEmail() {
      this.$emit('check-email');
    },

    handleChangeFormOption(event, type_dropdown) {
      this.$emit('handle-change-form-option', event, type_dropdown);
    },

    async getListMainjob() {
      try {
        const response = await getListMainjob();
        const { data, code, message } = response.data;
        if (code === 200) {
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
        } else {
          MakeToast({
            variant: 'warning',
            title: 'WARNING',
            content: message || '',
          });
        }
        this.renderChildOption(this.formData.major_classification_id);
        //
      } catch (erorr) {
        console.log('erorr', erorr);
      }
    },

    renderChildOption(major_id) {
      let middle_option = [];
      for (let i = 0; i < this.major_classification_options.length; i++) {
        if (this.major_classification_options[i].id === major_id) {
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
@import '@/pages/RegisterHrOrigin/RegisterHrOrigin.scss';
</style>

