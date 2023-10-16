<!-- 1BasicInformation Detail -->
<template>
  <div class="hr-content-tab hr-detail-basic-info">
    <div class="hr-content-tab-wrap">
      <!-- 1 input 氏名 - Full name -->
      <div class="hr-content-tab-item border-t border-l border-r">
        <div id="width-basic-info" class="hr-content-tab-item__title">
          <span class="full-name-hr-label">{{
            $t('HR_REGISTER.FULL_NAME')
          }}</span>
        </div>

        <div class="hr-content-tab__data">
          <div>
            <span class="full-name-hr-content">{{ basicForm.full_name }}</span>
          </div>
        </div>
      </div>
      <!-- 2 input 氏名（ﾌﾘｶﾞﾅ） - Full name(furigana) -->
      <div class="hr-content-tab-item border-t border-l border-r">
        <div id="width-basic-info" class="hr-content-tab-item__title">
          <span class="full-name-ja-label">{{
            $t('HR_REGISTER.FULL_NAMEFURIGANA')
          }}</span>
        </div>

        <div class="hr-content-tab__data">
          <div>
            <span class="full-name-ja-content">{{
              basicForm.full_name_ja
            }}</span>
          </div>
        </div>
      </div>
      <!-- 3 options 性別 - Gender -->
      <div class="hr-content-tab-item border-t border-l border-r">
        <div id="width-basic-info" class="hr-content-tab-item__title">
          <span class="gender-label">{{ $t('HR_REGISTER.GENDER') }}</span>
        </div>

        <div class="hr-content-tab__data">
          <div>
            <span class="gender-content">{{
              getTextByCode('gender', basicForm.gender)
            }}</span>
          </div>
        </div>
      </div>

      <!-- 4 options 生年月日 - date of birth -->
      <div class="hr-content-tab-item border-t border-l border-r">
        <div id="width-basic-info" class="hr-content-tab-item__title">
          <span class="date-of-birth-label">{{
            $t('HR_REGISTER.DATE_OF_BIRTH_')
          }}</span>
        </div>

        <div class="hr-content-tab__data">
          <div
            class="d-flex justify-start"
            style="gap: 0.75rem; align-items: center"
          >
            <span class="date-of-birth-content">{{
              renderDateOfBirthDetail(basicForm.date_of_birth)
            }}</span>
            <span
              class="count-age-content"
            >({{ countAge(basicForm.date_of_birth) }}{{ $t('AGE') }})</span>
          </div>
        </div>
      </div>

      <!-- 5 options 勤務形態 - Work form -->
      <div class="hr-content-tab-item border-t border-l border-r">
        <div id="width-basic-info" class="hr-content-tab-item__title">
          <span class="label-basic-work-form">{{
            $t('HR_REGISTER.WORK_FORM')
          }}</span>
        </div>

        <div class="hr-content-tab__data">
          <div>
            <span class="content-basic-work-form">{{
              getTextByCode('work_form', basicForm.work_form)
            }}</span>
          </div>
        </div>
      </div>

      <!-- 6 input 勤務形態（非常勤）- Work form(part-time) -->
      <div class="hr-content-tab-item border-t border-l border-r">
        <div id="width-basic-info" class="hr-content-tab-item__title">
          <span class="label-preferred-working-hours">{{
            $t('HR_REGISTER.WORK_FORM_PARTTIME')
          }}</span>
        </div>

        <div class="hr-content-tab__data">
          <div
            v-if="
              basicForm.work_form !== 1 && basicForm.preferred_working_hours
            "
          >
            <span
              class="content-preferred-working-hours"
            >週{{ basicForm.preferred_working_hours }}時間</span>
          </div>
        </div>
      </div>
      <!-- 7 日本語レベル - Japanese level -->
      <div class="hr-content-tab-item border-t border-l border-r border-b">
        <div id="width-basic-info" class="hr-content-tab-item__title">
          <span class="label-japan-level">{{
            $t('HR_REGISTER.JAPANESE_LEVEL')
          }}</span>
        </div>

        <div class="hr-content-tab__data">
          <div
            class="d-flex justify-space-between"
            style="gap: 0.75rem; align-items: center"
          >
            <span v-if="basicForm.japanese_level === 6">資格なし</span>
            <span
              v-else
              class="content-japan-level"
            >N{{ basicForm.japanese_level }}</span>
          </div>
        </div>
      </div>

      <!--  -->
    </div>
  </div>
</template>

<script>
// import { MakeToast } from '../../utils/toastMessage';
import { gender_option, work_form_option } from '@/pages/Hr/common.js';
import moment from 'moment';

export default {
  name: 'TabABasicInformationDetail',
  props: {
    detailData: {
      type: Object,
      require: true,
      default: function() {
        return {};
      },
    },
  },

  data() {
    return {
      basicForm: {},
      gender_option: gender_option,
      work_form_option: work_form_option,
    };
  },

  watch: {
    detailData: {
      handler: function(props_data) {
        if (props_data) {
          this.basicForm = props_data;
          // this.handleUpdatDataDetail(props_data);
        }
      },
      deep: true,
    },
  },

  created() {
    // this.getListAllData();
  },

  methods: {
    getTextByCode(type, id) {
      let content = '';
      switch (type) {
        case 'gender':
          gender_option.map((item) => {
            if (item.value.id === id) {
              content = item.value.content;
            }
          });
          break;
        case 'work_form':
          work_form_option.map((item) => {
            if (item.value.id === id) {
              content = item.value.content;
            }
          });
          break;

        default:
          break;
      }

      return content;
    },

    renderDateOfBirthDetail(date_of_birth) {
      const string_birth = moment(date_of_birth).format('YYYY年MM月DD日');
      return string_birth;
    },
    countAge(date_of_birth) {
      const birthday = new Date(date_of_birth);
      const today = new Date();

      let age = today.getFullYear() - birthday.getFullYear();

      if (
        today.getMonth() < birthday.getMonth() ||
        (today.getMonth() === birthday.getMonth() &&
          today.getDate() < birthday.getDate())
      ) {
        age--;
      }

      return age;
    },
    //
    clearInputWithOption() {},
  },
};
</script>

<style lang="scss" scoped>
// @import '@/scss/_variables.scss';
// @import '@/scss/modules/common/common.scss';
@import '@/pages/Hr/Detail/TabABasicInformation/TabABasicInformation.scss';
</style>
