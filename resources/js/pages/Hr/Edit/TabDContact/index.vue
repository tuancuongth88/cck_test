<!-- 4 TabDContact Edit -->
<template>
  <div class="hr-content-tab">
    <!-- <button style="position: fixed; background: gray; opacity: 0.1;" class="btn" @click="changeStatus">Change</button> -->
    <!-- <div>gender_id: {{ gender_id }}</div> -->
    <!-- <div>date_of_birth_year_option: {{ date_of_birth_year_option }}</div> -->
    <!-- <div>contact_init: {{ contact_init }}</div><br> -->
    <!-- <div>mobile_phone_number_id: {{ contact_init.mobile_phone_number_id }}</div><br> -->
    <!-- <div>telephone_phone_number_id: {{ contact_init.telephone_phone_number_id }}</div> -->

    <div class="hr-content-tab-wrap">
      <!-- 1 連絡先電話番号 - Telephone number -->
      <div class="hr-content-tab-item border-t border-l border-r">
        <div id="hr-type-edit" class="hr-content-tab-item__title">
          <span>{{ $t('HR_LIST_FORM.TELEPHONE_NUMBER') }} </span><Arbitrarily v-if="type_form === 'edit'" />
        </div>
        <!-- Làm lại inpust khác vì là màn edit khác register -->
        <div class="hr-content-tab__data">
          <div v-if="type_form === 'detail'">
            <span>{{ contact_init.telephone_phone_number_input }}</span>
          </div>
          <div
            v-if="type_form === 'edit'"
            class="flex-row"
            style="gap: 1rem; justify-content: flex-start; align-items: center"
          >
            <b-form-select
              v-model="contact_init.telephone_phone_number_id.id"
              :options="phone_number_options_common"
              style="min-width: 153px; width: 28%"
              @change="
                pushAreaCode(
                  contact_init.telephone_phone_number_id,
                  'telephone_phone_number'
                )
              "
            />
            <!-- :formatter="format50characters" -->
            <b-form-input
              v-model="contact_init.telephone_phone_number_input"
              max-lenght="50"
              class="form-input px-2"
              :name="'mail address'"
              :placeholder="'例) 0312345678 ハイフン無しで入力'"
              :enabled="contact_init.telephone_phone_number_id"
              :disabled="!contact_init.telephone_phone_number_id"
              style="width: 100%"
            />
          </div>
        </div>
      </div>
      <!-- 2 携帯電話番号 - Mobile phone number -->
      <div class="hr-content-tab-item border-t border-l border-r">
        <div id="hr-type-edit" class="hr-content-tab-item__title">
          <span>{{ $t('HR_LIST_FORM.MOBILE_PHONE_NUMBER') }}</span><Arbitrarily v-if="type_form === 'edit'" />
        </div>

        <div class="hr-content-tab__data">
          <div v-if="type_form === 'detail'">
            <span>{{ contact_init.mobile_phone_number_input }}</span>
          </div>
          <div
            v-if="type_form === 'edit'"
            class="flex-row"
            style="gap: 1rem; justify-content: flex-start; align-items: center"
          >
            <b-form-select
              v-model="contact_init.mobile_phone_number_id"
              :options="phone_number_options_common"
              style="min-width: 153px; width: 28%"
              @change="
                pushAreaCode(
                  contact_init.mobile_phone_number_id,
                  'telephone_phone_number'
                )
              "
            />
            <!-- :formatter="format50characters" -->
            <b-form-input
              v-model="contact_init.telephone_phone_number_input"
              max-lenght="50"
              class="form-input px-2"
              :name="'mail address'"
              :placeholder="'例) 0312345678 ハイフン無しで入力'"
              :enabled="contact_init.mobile_phone_number_id"
              :disabled="!contact_init.mobile_phone_number_id"
              style="width: 100%"
            />
          </div>
        </div>
      </div>
      <!-- 3 メールアドレス - Mail address -->
      <div class="hr-content-tab-item border-t border-l border-r">
        <div id="hr-type-edit" class="hr-content-tab-item__title">
          <span>{{ $t('HR_LIST_FORM.MAIL_ADDRESS') }}</span><Require v-if="type_form === 'edit'" />
        </div>

        <div class="hr-content-tab__data">
          <div v-if="type_form === 'detail'">
            <span>{{ contact_init.mail_address_input }}</span>
          </div>
          <div
            v-if="type_form === 'edit'"
            class="flex-row"
            style="gap: 1rem; justify-content: flex-start; align-items: center"
          >
            <b-form-input
              v-model="contact_init.mail_address_input"
              max-lenght="50"
              :formatter="format50characters"
              class="form-input px-2"
              :name="'mail address'"
              :placeholder="''"
              enabled
              style="width: 100%"
            />
          </div>
        </div>
      </div>
      <!-- 4 現住所  - Address -->
      <div class="hr-content-tab-item border-t border-l border-r">
        <div
          id="hr-type-edit"
          class="hr-content-tab-item__title d-flex"
          style="
            justify-content: flex-start;
            align-items: flex-start;
            padding-top: 1.5rem;
          "
        >
          <span>{{ $t('HR_LIST_FORM.ADDRESS') }}</span><Arbitrarily v-if="type_form === 'edit'" />
        </div>

        <div class="hr-content-tab__data">
          <div
            v-if="type_form === 'detail'"
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
                  justify-content: flex-start;
                  align-items: center;
                  gap: 0.751rem;
                "
              >
                <span style="min-width: 28%">{{ $t('HR_LIST_FORM.CITY') }}</span><!-- City -->
                <div>{{ contact_init.address_city_input }}</div>
              </div>
              <div
                class="w-100 d-flex"
                style="
                  height: 48px;
                  justify-content: flex-start;
                  align-items: center;
                  gap: 0.751rem;
                "
              >
                <span style="min-width: 28%">{{
                  $t('HR_LIST_FORM.DISTRICT')
                }}</span><!-- district -->
                <div>{{ contact_init.address_district_input }}</div>
              </div>
              <div
                class="w-100 d-flex"
                style="
                  height: 48px;
                  justify-content: flex-start;
                  align-items: center;
                  gap: 0.751rem;
                "
              >
                <span style="min-width: 28%">{{
                  $t('HR_LIST_FORM.NUMBER')
                }}</span><!-- Number -->
                <div>{{ contact_init.address_number_input }}</div>
              </div>
              <div
                class="w-100 d-flex"
                style="
                  height: 48px;
                  justify-content: flex-start;
                  align-items: center;
                  gap: 0.751rem;
                "
              >
                <span style="min-width: 28%">{{
                  $t('HR_LIST_FORM.ORTHER')
                }}</span><!-- other -->
                <div>{{ contact_init.address_other_input }}</div>
              </div>
            </div>
          </div>
          <div
            v-if="type_form === 'edit'"
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
                <span style="min-width: 28%">{{ $t('HR_LIST_FORM.CITY') }}</span><!-- City -->
                <b-form-input
                  v-model="contact_init.address_city_input"
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
                <span style="min-width: 28%">{{
                  $t('HR_LIST_FORM.DISTRICT')
                }}</span><!-- district -->
                <b-form-input
                  v-model="contact_init.address_district_input"
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
                <span style="min-width: 28%">{{
                  $t('HR_LIST_FORM.NUMBER')
                }}</span><!-- Number -->
                <b-form-input
                  v-model="contact_init.address_number_input"
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
                <span style="min-width: 28%">{{
                  $t('HR_LIST_FORM.ORTHER')
                }}</span><!-- other -->
                <b-form-input
                  v-model="contact_init.address_other_input"
                  max-lenght="50"
                  :formatter="format50characters"
                  style="width: 80%"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- 5 出身地住所 Hometown address -->
      <div class="hr-content-tab-item border-t border-l border-r border-b">
        <div
          id="hr-type-edit"
          class="hr-content-tab-item__title d-flex"
          style="
            justify-content: flex-start;
            align-items: flex-start;
            padding-top: 1.5rem;
          "
        >
          <span>{{ $t('HR_LIST_FORM.HOMETOWN_ADDRESS') }}</span><Arbitrarily v-if="type_form === 'edit'" />
        </div>

        <div class="hr-content-tab__data">
          <div
            v-if="type_form === 'detail'"
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
                  justify-content: flex-start;
                  align-items: center;
                  gap: 0.751rem;
                "
              >
                <span style="min-width: 28%">{{ $t('HR_LIST_FORM.CITY') }}</span><!-- City -->
                <div>{{ contact_init.hometown_address_city_input }}</div>
              </div>
              <div
                class="w-100 d-flex"
                style="
                  height: 48px;
                  justify-content: flex-start;
                  align-items: center;
                  gap: 0.751rem;
                "
              >
                <span style="min-width: 28%">{{
                  $t('HR_LIST_FORM.DISTRICT')
                }}</span><!-- district -->
                <div>{{ contact_init.hometown_address_district_input }}</div>
              </div>
              <div
                class="w-100 d-flex"
                style="
                  height: 48px;
                  justify-content: flex-start;
                  align-items: center;
                  gap: 0.751rem;
                "
              >
                <span style="min-width: 28%">{{
                  $t('HR_LIST_FORM.NUMBER')
                }}</span><!-- Number -->
                <div>{{ contact_init.hometown_address_number_input }}</div>
              </div>
              <div
                class="w-100 d-flex"
                style="
                  height: 48px;
                  justify-content: flex-start;
                  align-items: center;
                  gap: 0.751rem;
                "
              >
                <span style="min-width: 28%">{{
                  $t('HR_LIST_FORM.ORTHER')
                }}</span><!-- other -->
                <div>{{ contact_init.hometown_address_other_input }}</div>
              </div>
            </div>
          </div>
          <div
            v-if="type_form === 'edit'"
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
                <span style="min-width: 28%">{{ $t('HR_LIST_FORM.CITY') }}</span><!-- City -->
                <b-form-input
                  v-model="contact_init.hometown_address_city_input"
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
                <span style="min-width: 28%">{{
                  $t('HR_LIST_FORM.DISTRICT')
                }}</span><!-- district -->
                <b-form-input
                  v-model="contact_init.hometown_address_district_input"
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
                <span style="min-width: 28%">{{
                  $t('HR_LIST_FORM.NUMBER')
                }}</span><!-- Number -->
                <b-form-input
                  v-model="contact_init.hometown_address_number_input"
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
                <span style="min-width: 28%">{{
                  $t('HR_LIST_FORM.ORTHER')
                }}</span><!-- other -->
                <b-form-input
                  v-model="contact_init.hometown_address_other_input"
                  max-lenght="50"
                  :formatter="format50characters"
                  style="width: 80%"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--  -->
    </div>
  </div>
</template>

<script>
// import { MakeToast } from '../../utils/toastMessage';
// import { obj2Path } from '@/utils/obj2Path';
// import SearchWithConditions from '../../layout/components/search/SearchWithConditions.vue';
import Require from '@/components/Require/Require.vue';
import Arbitrarily from '@/components/Arbitrarily/Arbitrarily.vue';
import { phone_number_options_common } from '@/pages/Hr/common.js';
import { contact_init } from '@/pages/Hr/dataHrForm.js';

// const urlAPI = {
//   urlGetLisUser: '/user',
//   urlDeleAll: 'user/ ',
// };
export default {
  name: 'TabDContactEdit',
  components: {
    Require,
    Arbitrarily,
  },

  data() {
    return {
      // type_form: 'detail',
      type_form: 'edit',

      contact_init: contact_init,

      // 1 連絡先電話番号
      phone_number_options_common: phone_number_options_common,
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
    // this.getListAllData();
  },

  methods: {
    changeStatus() {
      if (this.type_form === 'detail') {
        this.type_form = 'edit';
      } else if (this.type_form === 'edit') {
        this.type_form = 'detail';
      }
    },
    //
    getContentSelect(id) {
      if (id) {
        return id.content;
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
    //
    pushAreaCode(number_id_option, type_number_phone) {
      // this.mobile_phone_number_input = '+84';

      if (
        number_id_option.id === 1 &&
        type_number_phone === 'telephone_phone_number'
      ) {
        this.contact_init.telephone_phone_number_input =
          number_id_option.content;
      } else if (
        number_id_option.id === 2 &&
        type_number_phone === 'telephone_phone_number'
      ) {
        this.contact_init.telephone_phone_number_input =
          number_id_option.content;
      } else if (
        number_id_option.id === 1 &&
        type_number_phone === 'mobile_phone_number'
      ) {
        this.contact_init.mobile_phone_number_input = number_id_option.content;
      } else if (
        number_id_option.id === 2 &&
        type_number_phone === 'mobile_phone_number'
      ) {
        this.contact_init.mobile_phone_number_input = number_id_option.content;
      }
    },
  },
};
</script>

<style lang="scss" scoped>
// @import '@/scss/_variables.scss';
// @import '@/scss/modules/common/common.scss';
@import '@/pages/Hr/Detail/TabABasicInformation/TabABasicInformation.scss';
</style>
