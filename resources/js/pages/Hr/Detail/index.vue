<!-- HR Detail -->
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
    <div v-show="!overlay.show" class="hr-detail">
      <!--  -->
      <HrBasicInformation
        :basic-information="detail_data"
        :hr-indentify="detail_data.id"
      />
      <!-- :status="status_select_jobs_to_offer"  -->
      <!-- <SelectJobsToOffer v-if="status_select_jobs_to_offer" /> -->

      <!--  -->
      <div class="hr-detail-information">
        <div class="hr-detail-information_wrap">
          <div class="hr-detail_information-head">
            <div class="hr-basic-head-title">
              <div class="line" />
              <div>
                <span>{{ $t('HR_LIST_FORM.DEAIL_FORM_TITLE') }}</span>
              </div>
              <!--  HR information detail -->
            </div>
            <!--  -->
            <div class="hr-detail-head-btns">
              <!-- 一覧に戻る Back to list -->
              <!-- 編集 edit -->
              <div class="btn btn-light" @click="handleBackToHrList">
                <span>{{ $t('BUTTON.BACK_TO_LIST') }}</span>
              </div>
              <div v-if="checkRole" class="btn btn-light btn-bold" @click="handleGoToEditHr">
                <span>{{ $t('BUTTON_EDIT') }}</span>
              </div>
            </div>
            <!--  -->
          </div>

          <div class="hr-detail_information-tabs">
            <b-card no-body>
              <b-tabs card>
                <!-- 1 基本情報 Basic information -->
                <b-tab title="基本情報" active>
                  <b-card-text>
                    <tab1BasicInformation :detail-data="detail_data" />
                  </b-card-text>
                </b-tab>
                <!-- 2 学歴・職歴 - education.work history-->
                <b-tab title="学歴・職歴">
                  <b-card-text>
                    <tab2EducationWorkHistory :detail-data="detail_data" />
                  </b-card-text>
                </b-tab>
                <!-- 3 自己PR・備考 - Personal PR.Remarks -->
                <b-tab title="自己PR・備考">
                  <b-card-text>
                    <tab3PersonalPrRemarks
                      :personal-pr-special-notes="detail_data.personal_pr_special_notes"
                      :remarks="detail_data.remarks"
                    />
                  </b-card-text>
                </b-tab>
                <!-- 4 連絡先 - Contact -->
                <b-tab v-if="checkRole" title="連絡先">
                  <b-card-text>
                    <tab4Contact :detail-data="detail_data" />
                  </b-card-text>
                </b-tab>
                <!-- 5 マッチング状況 - Matching situation -->
                <b-tab v-if="checkRole" title="マッチング状況">
                  <b-card-text>
                    <!-- <tab5MatchingSituation
                      :data-entries="detail_data.entries"
                      :data-offers="detail_data.offers"
                      :data-interviews="detail_data.interviews"
                      :data-results="detail_data.results"
                    /> -->
                    <tab5MatchingSituation :detail-data="detail_data" />
                  </b-card-text>
                </b-tab>
                <!--  -->
              </b-tabs>
            </b-card>
          </div>
        </div>
      </div>
      <!--  -->
    </div>
  </b-overlay>
</template>

<script>
// import { MakeToast } from '../../utils/toastMessage';
// import { obj2Path } from '@/utils/obj2Path';
import tab1BasicInformation from '@/pages/Hr/Detail/TabABasicInformation';
import tab2EducationWorkHistory from '@/pages/Hr/Detail/TabBEducationWorkHistory';
import tab3PersonalPrRemarks from '@/pages/Hr/Detail/TabCPersonalPrRemarks';
import tab4Contact from '@/pages/Hr/Detail/TabDContact';
import tab5MatchingSituation from '@/pages/Hr/Detail/TabEMatchingSituation';
import HrBasicInformation from '@/pages/Hr/HrBasicInformation';
import { getDetailsHr } from '@/api/hr.js';
// import SelectJobsToOffer from '@/pages/Hr/HrBasicInformation/SelectJobsToOffer.vue';
// import { status_select_jobs_to_offer } from '@/pages/Hr/common.js';

// const urlAPI = {
//   urlGetLisUser: '/user',
//   urlDeleAll: 'user/ ',
// };
export default {
  name: 'HrDetail',
  components: {
    tab1BasicInformation,
    tab2EducationWorkHistory,
    tab3PersonalPrRemarks,
    tab4Contact,
    tab5MatchingSituation,
    HrBasicInformation,
    // SelectJobsToOffer,
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
      // status_select_jobs_to_offer: status_select_jobs_to_offer,
      id_hr: this.$route.params.id,
      // Data child
      dataMatchingSituation: [{ feild: 'dataMatchingSituation 1' }],

      detail_data: {
        address_city: '',
        address_district: '',
        address_number: '',
        address_other: null,
        country_id: null,
        date_of_birth: '2000-01-01',
        deleted_at: null,
        entries: [],
        file_c_v: {
          id: null,
          file_name: '',
          file_path: '',
        },
        file_cv_id: null,
        file_job: {
          id: null,
          file_name: '',
          file_path: '',
        },
        file_job_id: null,
        file_others: '',
        final_education_classification: null,
        final_education_date: '2000-01-01',
        final_education_degree: null,
        full_name: '',
        full_name_ja: '',
        gender: null,
        home_town_district: '',
        home_town_number: '',
        home_town_other: null,
        hometown_city: '',
        hr_organization_id: null,
        id: null,
        interviews: [],
        is_favorite: null,
        japanese_level: null,
        mail_address: '',
        main_jobs: [],
        major_classification_id: null,
        middle_classification_id: null,
        mobile_phone_number: null,
        offers: [],
        personal_pr_special_notes: null,
        preferred_working_hours: null,
        remarks: null,
        results: [],
        status: null,
        telephone_number: null,
        user_id: '',
        work_form: null,
      },

      role_accept: [1, 3, 5],
    };
  },

  computed: {
    checkRole() {
      const profile = this.$store.getters.profile;
      const ROLE = profile.type || 0;
      return this.role_accept.includes(ROLE);
    },
  },

  created() {
    this.getDetailHR();
  },

  methods: {
    handleBackToHrList() {
      console.log('handleBackToHrList');
      this.$router.push({ path: `/hr/list` }, (onAbort) => {});
    },
    handleGoToEditHr() {
      console.log('handleGoToEditHr');
      // this.$router.push({ path: `/hr/edit` }, (onAbort) => {});
      this.$router.push({ path: `/hr/edit/${this.id_hr}` }, (onAbort) => {});
    },

    async getDetailHR() {
      this.overlay.show = true;
      console.log('id_hr: this.$route.params.id,', this.$route.params.id);
      try {
        const res = await getDetailsHr(this.$route.params.id);
        const { code, data } = res.data;
        if (code === 200) {
          console.log('DATA detail: ', data);
          this.detail_data = data;
        }
      } catch (error) {
        console.log(error);
      }
      this.overlay.show = false;
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/pages/Hr/Detail/HRDetail.scss/';
</style>
