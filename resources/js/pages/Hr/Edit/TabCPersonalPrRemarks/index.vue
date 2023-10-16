<!-- 3PersonalPrRemarks Edit -->
<template>
  <div class="hr-content-tab">
    <!-- <button style="position: fixed; background: gray; opacity: 0.1;" class="btn" @click="changeStatus">Change</button> -->
    <!-- <div>gender_id: {{ gender_id }}</div> -->
    <!-- <div>date_of_birth_year_option: {{ date_of_birth_year_option }}</div> -->
    <!-- <div>work_form_id: {{ work_form_id }}</div> -->

    <div class="hr-content-tab-wrap">
      <!-- 1 input 自己PR・特記事項 - Personal PR.Special notes -->
      <div
        class="hr-content-tab-item border-t border-l border-r"
        style="min-height: 240px"
      >
        <div
          id="hr-type-edit"
          class="hr-content-tab-item__title d-flex justify-content-start align-items-start"
        >
          <span>{{ $t('HR_LIST_FORM.PERSONAL_PR_SPECIAL_NOTES') }}</span><Arbitrarily v-if="type_form === 'edit'" />
        </div>

        <div
          class="hr-content-tab__data d-flex justify-content-start align-items-start py-2"
        >
          <div v-if="type_form === 'detail'">
            <span class="text-left">{{
              personal_pr_remarks_init.personal_pr_special_textarea
            }}</span>
          </div>
          <div
            v-if="type_form === 'edit'"
            class="w-100 h-100 d-flex justify-content-start align-items-stretch"
          >
            <!-- <b-form-input v-model="personal_pr_remarks_init.personal_pr_special_textarea" /> -->
            <b-form-textarea
              id="textarea"
              v-model="personal_pr_remarks_init.personal_pr_special_textarea"
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
      <!-- 2 input 備考 - remarks -->
      <div
        class="hr-content-tab-item border-t border-l border-r border-b"
        style="min-height: 240px"
      >
        <div
          id="hr-type-edit"
          class="hr-content-tab-item__title d-flex justify-content-start align-items-start"
        >
          <span>{{ $t('HR_LIST_FORM.REMARKS') }}</span><Arbitrarily v-if="type_form === 'edit'" />
        </div>

        <div
          class="hr-content-tab__data d-flex justify-content-start align-items-start py-2"
        >
          <div v-if="type_form === 'detail'">
            <span class="text-left">{{
              personal_pr_remarks_init.remarks_textarea
            }}</span>
          </div>
          <div
            v-if="type_form === 'edit'"
            class="w-100 h-100 d-flex justify-content-start align-items-stretch"
          >
            <b-form-textarea
              id="textarea"
              v-model="personal_pr_remarks_init.remarks_textarea"
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

      <!--  -->
    </div>
  </div>
</template>

<script>
// import { MakeToast } from '../../utils/toastMessage';
// import { obj2Path } from '@/utils/obj2Path';
import Arbitrarily from '@/components/Arbitrarily/Arbitrarily.vue';
// import { } from '@/pages/Hr/common.js';
import { personal_pr_remarks_init } from '@/pages/Hr/dataHrForm.js';

// const urlAPI = {
//   urlGetLisUser: '/user',
//   urlDeleAll: 'user/ ',
// };
export default {
  name: 'TabCPersonalPrRemarksEdit',
  components: {
    Arbitrarily,
  },

  data() {
    return {
      // type_form: 'detail',
      type_form: 'edit',
      personal_pr_remarks_init: personal_pr_remarks_init,
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
  },
};
</script>

<style lang="scss" scoped>
@import '@/pages/Hr/Detail/TabABasicInformation/TabABasicInformation.scss';
</style>
