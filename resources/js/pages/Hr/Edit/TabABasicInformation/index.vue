<template>
  <div class="hr-content-tab">
    <div class="hr-content-tab-wrap">
      <div class="hr-content-tab-item border-t border-l border-r">
        <div id="hr-type-edit" class="hr-content-tab-item__title">
          <span>{{ $t('HR_REGISTER.FULL_NAME') }}</span>
          <Require v-if="type_form === 'edit'" />
        </div>

        <div class="hr-content-tab__data">
          <div v-if="type_form === 'detail'">
            <span>{{ formData.full_name }}</span>
          </div>

          <div v-if="type_form === 'edit'">
            <b-form-input
              v-model="formData.full_name"
              type="text"
              @input="handleChangeFullName"
            />
          </div>
        </div>
      </div>

      <div class="hr-content-tab-item border-t border-l border-r">
        <div id="hr-type-edit" class="hr-content-tab-item__title">
          <span>{{ $t('HR_REGISTER.FULL_NAMEFURIGANA') }}</span>
          <Require v-if="type_form === 'edit'" />
        </div>

        <div class="hr-content-tab__data">
          <div v-if="type_form === 'detail'">
            <span>{{ formData.full_name_furigana }}</span>
          </div>

          <div v-if="type_form === 'edit'">
            <b-form-input
              v-model="formData.full_name_furigana"
              @input="handleChangeFullNameFurigana"
            />
          </div>
        </div>
      </div>

      <div class="hr-content-tab-item border-t border-l border-r">
        <div id="hr-type-edit" class="hr-content-tab-item__title">
          <span>{{ $t('HR_REGISTER.GENDER') }}</span>
          <Arbitrarily v-if="type_form === 'edit'" />
        </div>

        <div class="hr-content-tab__data">
          <div v-if="type_form === 'detail'">
            <span>{{ formData.gender }}</span>
          </div>

          <div v-if="type_form === 'edit'">
            <b-form-select
              v-model="formData.gender"
              :options="gender_option"
              :disabled-field="'disabled'"
              @input="handleChangeGender"
            />
          </div>
        </div>
      </div>

      <div class="hr-content-tab-item border-t border-l border-r">
        <div id="hr-type-edit" class="hr-content-tab-item__title">
          <span>{{ $t('HR_REGISTER.DATE_OF_BIRTH_') }}</span>
          <Require v-if="type_form === 'edit'" />
        </div>

        <div class="hr-content-tab__data">
          <div
            v-if="type_form === 'detail'"
            class="d-flex justify-space-between"
            style="gap: 0.75rem; align-items: center"
          >
            <span>{{
              `${formData.date_of_birth_year}年${formData.date_of_birth_month}月${formData.date_of_birth_day}日`
            }}</span>
            <span>{{ calculateAge(formData.date_of_birth_original) }}
              {{ $t('AGE') }}</span>
          </div>

          <div v-if="type_form === 'edit'" style="">
            <div
              class="d-flex justify-space-between"
              style="
                width: 40%;
                min-width: 152px;
                gap: 0.5rem;
                align-items: center;
              "
            >
              <b-form-select
                v-model="formData.date_of_birth_year"
                :options="date_of_birth_year_option"
                @change="
                  handleRenderDayInMonth(
                    formData.date_of_birth_year,
                    formData.date_of_birth_month
                  ),
                  handleChangeYearDoB($event)
                "
              />

              <span>{{ $t('HR_LIST_FORM.YEAR') }}</span>
            </div>

            <div
              class="d-flex justify-space-between pl-4"
              style="
                width: 30%;
                min-width: 94px;
                gap: 0.5rem;
                align-items: center;
              "
            >
              <b-form-select
                v-model="formData.date_of_birth_month"
                :options="date_of_birth_month_option"
                @change="
                  handleRenderDayInMonth(
                    formData.date_of_birth_year,
                    formData.date_of_birth_month
                  ),
                  handleChangeMonthDoB($event)
                "
              />

              <span>{{ $t('JOB_SEARCH.MONTH') }}</span>
            </div>

            <div
              class="d-flex justify-space-between pl-4"
              style="
                width: 30%;
                min-width: 94px;
                gap: 0.5rem;
                align-items: center;
              "
            >
              <b-form-select
                v-model="formData.date_of_birth_day"
                :options="date_of_birth_day_option"
                @change="handleChangeDayDoB"
              />

              <span>{{ $t('JOB_SEARCH.MONTH') }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="hr-content-tab-item border-t border-l border-r">
        <div id="hr-type-edit" class="hr-content-tab-item__title">
          <span>{{ $t('HR_REGISTER.WORK_FORM') }}</span>
          <Arbitrarily v-if="type_form === 'edit'" />
        </div>

        <div class="hr-content-tab__data">
          <div v-if="type_form === 'detail'">
            <span>{{ formData.work_form }}</span>
          </div>

          <div v-if="type_form === 'edit'">
            <b-form-select
              v-model="formData.work_form"
              :options="work_form_option"
              :disabled-field="'disabled'"
              @change="handleChangeWorkFrom"
            />
          </div>
        </div>
      </div>

      <div class="hr-content-tab-item border-t border-l border-r">
        <div id="hr-type-edit" class="hr-content-tab-item__title">
          <span>{{ $t('HR_REGISTER.WORK_FORM_PARTTIME') }}</span>
          <Arbitrarily v-if="type_form === 'edit'" />
        </div>

        <div class="hr-content-tab__data">
          <div v-if="type_form === 'detail'">
            <span>{{ formData.work_form_part_time }}</span>
          </div>

          <div
            v-if="type_form === 'edit'"
            class="d-flex justify-space-between"
            style="align-items: center"
          >
            <span class="pr-4">週</span>

            <b-form-input
              v-model="formData.work_form_part_time"
              :disabled="formData.work_form === 1"
              @change="handleChangeWorkFromPartTime"
            />

            <span class="pl-2" style="min-width: 8%">時間</span>
          </div>
        </div>
      </div>

      <div class="hr-content-tab-item border-t border-l border-r border-b">
        <div id="hr-type-edit" class="hr-content-tab-item__title">
          <span>{{ $t('HR_REGISTER.JAPANESE_LEVEL') }}</span>
          <Require v-if="type_form === 'edit'" />
        </div>

        <div class="hr-content-tab__data">
          <div
            v-if="type_form === 'detail'"
            class="d-flex justify-space-between"
            style="gap: 0.75rem; align-items: center"
          >
            <span>{{ formData.japanese_level }}</span>
          </div>

          <div v-if="type_form === 'edit'" style="">
            <b-form-select
              v-model="formData.japanese_level"
              :options="japanese_level_options"
              :disabled-field="'disabled'"
              @change="handleChangeJapaneseLevel"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Require from '@/components/Require/Require.vue';
import Arbitrarily from '@/components/Arbitrarily/Arbitrarily.vue';

import { renderYears, renderMonth } from '@/pages/Hr/common.js';

export default {
  name: 'TabABasicInformation',
  components: {
    Require,
    Arbitrarily,
  },
  props: {
    basicInformation: {
      type: Object,
      require: false,
      default: function() {
        return {};
      },
    },
    type: {
      type: String,
      require: true,
      default: function() {
        return 'edit';
      },
    },
    error: {
      type: Object,
      require: true,
      default: function() {
        return {};
      },
    },
  },
  data() {
    return {
      formData: {
        full_name: '',
        full_name_furigana: '',
        gender: null,
        date_of_birth_original: '',
        date_of_birth_year: '',
        date_of_birth_month: '',
        date_of_birth_day: '',
        work_form: null,
        work_form_part_time: '',
        japanese_level: null,
      },

      type_form: '',

      gender_option: [
        { value: null, text: '選択してください', disabled: true },
        { value: 1, text: '男' },
        { value: 2, text: '女' },
      ],

      date_of_birth_year_option: renderYears(),
      date_of_birth_month_option: renderMonth(),
      date_of_birth_day_option: [],

      work_form_option: [
        { value: null, text: '選択してください', disabled: true },
        { value: 1, text: '正社員' },
        { value: 2, text: '契約社員' },
        { value: 3, text: '派遣社員' },
        { value: 4, text: 'その他' },
      ],

      japanese_level_options: [
        { value: null, text: '選択してください', disabled: true },
        { value: 1, text: 'N1' },
        { value: 2, text: 'N2' },
        { value: 3, text: 'N3' },
        { value: 4, text: 'N4' },
        { value: 5, text: 'N5' },
        { value: 6, text: '資格なし' },
      ],

      errorFormData: {
        full_name: false,
        full_name_furigana: false,
        date_of_birth_year: false,
        date_of_birth_month: false,
        date_of_birth_day: false,
        japanese_level: false,
      },
    };
  },
  watch: {
    basicInformation: {
      handler: function(props_data) {
        if (props_data) {
          this.formData.full_name = props_data['full_name'];
          this.formData.full_name_furigana = props_data['full_name_ja'];
          this.formData.gender = props_data['gender'];
          this.formData.date_of_birth_year = this.handleSeparateDateTime(
            props_data['date_of_birth']
          )[0];
          this.formData.date_of_birth_month = this.handleSeparateDateTime(
            props_data['date_of_birth']
          )[1];

          this.handleRenderDayInMonth(
            this.formData.date_of_birth_year,
            this.formData.date_of_birth_month
          );
          this.formData.date_of_birth_original = props_data['date_of_birth'];

          this.formData.date_of_birth_day = this.handleSeparateDateTime(
            props_data['date_of_birth']
          )[2];
          this.formData.work_form = props_data['work_form'];

          if (this.formData.work_form !== 1) {
            this.formData.work_form_part_time =
              props_data['preferred_working_hours'];
          }

          this.formData.japanese_level = props_data['japanese_level'];
        }
      },
      deep: true,
    },
    formData: {
      handler: function(newValue) {
        if (newValue['work_form'] === 1) {
          this.formData.work_form_part_time = '';
        }
      },
      deep: true,
    },
    error: {
      handler: function(newValue) {
        if (newValue) {
          this.errorFormData.full_name = newValue['full_name'];
          this.errorFormData.full_name_furigana = newValue['full_name_ja'];
          this.errorFormData.date_of_birth_year = newValue['date_of_birth'];
          this.errorFormData.date_of_birth_month = newValue['date_of_birth'];
          this.errorFormData.date_of_birth_day = newValue['date_of_birth'];
          this.errorFormData.japanese_level = newValue['japanese_level'];
        }
      },
      deep: true,
    },
  },
  mounted() {
    if (this.type) {
      this.type_form = this.type;
    }
  },
  methods: {
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
    handleChangeFullName(value) {
      this.$bus.emit('fullName', value);
    },
    handleChangeFullNameFurigana(value) {
      this.$bus.emit('fullNameFurigana', value);
    },
    handleChangeGender(value) {
      this.$bus.emit('gender', value);
    },
    handleChangeYearDoB(value) {
      this.$bus.emit('yearDoB', value);
    },
    handleChangeMonthDoB(value) {
      this.$bus.emit('monthDoB', value);
    },
    handleChangeDayDoB(value) {
      this.$bus.emit('dayDoB', value);
    },
    handleChangeWorkFrom(value) {
      this.$bus.emit('workFrom', value);
    },
    handleChangeWorkFromPartTime(value) {
      this.$bus.emit('workFromPartTime', value);
    },
    handleChangeJapaneseLevel(value) {
      this.$bus.emit('japaneseLevel', value);
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/pages/Hr/Detail/TabABasicInformation/TabABasicInformation.scss';
</style>
