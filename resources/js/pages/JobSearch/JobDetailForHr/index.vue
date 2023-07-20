<!-- JobDetailForHr -->
<!-- /job-detail-for-hr/:id -->
<!-- http://localhost:8000/login#/job/job-detail-for-hr/1 -->
<template>
  <div class="job-detail-for-hr">
    <div class="job-detail-for-hr-container">
      <div class="job-detail-for-hr-form">
        <!-- 求人情報 詳細 -->
        <div class="job-detail-for-hr-form__head">
          <div class="h-100 d-flex justify-start align-stretch">
            <div class="line" />
            <div class="job-detail-for-hr-form__head-title pl-4">
              <span>{{ $t('JOB_ENTRY.TITLE') }}</span>
            </div>
          </div>
          <!-- 戻る Back -->
          <div class="job-detail-for-hr-form__head-btn">
            <div class="btn btn-back" @click="goToBack">
              <span>{{ $t('RETURN') }}</span>
            </div>
          </div>
        </div>

        <div class="job-detail-for-hr-form-autox">
          <div class="job-detail-for-hr-form-page">
            <!-- 1 求人情報 Job information -->
            <div class="form-page-area">
              <div class="form-page-area__head">
                <span>{{ $t('TITLE.JOB_INFORMATION') }} </span>
              </div>

              <div class="form-page-area__content">
                <div class="form-page-area-content-wrap">
                  <!-- 1.1 職種（タイトル） Occupation (Title) -->
                  <div class="form-item-row border-b">
                    <div class="form-item-row__title">
                      <div>
                        <span>{{ $t('JOB_DETAIL.TITLE') }}</span>
                      </div>
                    </div>
                    <div class="form-item-row__inputs">
                      <div>
                        <span>{{ formData.occupation_title }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- 1.2 企業名 Company name -->
                  <div class="form-item-row border-b">
                    <div class="form-item-row__title">
                      <div>
                        <span>{{ $t('JOB_DETAIL.COMPANY_NAME') }}</span>
                      </div>
                    </div>
                    <div class="form-item-row__inputs">
                      <div>
                        <span>{{ formData.company_name }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- 1.3 募集期間 Application period -->
                  <div class="form-item-row border-b">
                    <div class="form-item-row__title">
                      <div>
                        <span>{{ $t('JOB_DETAIL.APPLICATION_PERIOD') }}</span>
                      </div>
                    </div>
                    <div class="form-item-row__inputs">
                      <div>
                        <span>{{ formData.application_period }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- 1.4 仕事内容 Job description -->
                  <div class="form-item-row border-b">
                    <div class="form-item-row__title">
                      <div>
                        <span>{{ $t('JOB_DETAIL.JOB_DESCRIPTION') }}</span>
                      </div>
                    </div>
                    <div class="form-item-row__inputs">
                      <div>
                        <span>{{ formData.job__description }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- 1.5 応募条件 Application condition -->
                  <div class="form-item-row border-b">
                    <div class="form-item-row__title">
                      <div>
                        <span>{{
                          $t('JOB_DETAIL.APPLICATION_CONDITION')
                        }}</span>
                      </div>
                    </div>
                    <div class="form-item-row__inputs">
                      <div>
                        <span>{{ formData.application_condition }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- 1.6 応募要件 Application requirement -->
                  <div class="form-item-row border-b">
                    <div class="form-item-row__title">
                      <div>
                        <span>{{
                          $t('JOB_DETAIL.APPLICATION_REQUIREMENT')
                        }}</span>
                      </div>
                    </div>
                    <div class="form-item-row__inputs">
                      <div>
                        <span>{{ formData.application_requirement }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- 1.7 対象国 Target country -->
                  <div class="form-item-row border-b">
                    <div class="form-item-row__title">
                      <div>
                        <span>{{ $t('JOB_DETAIL.COUNTRY') }}</span>
                      </div>
                    </div>
                    <div class="form-item-row__inputs">
                      <div>
                        <span>{{ formData.target_country }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- 1.8 必須語学条件 Required language condition -->
                  <div class="form-item-row border-b">
                    <div class="form-item-row__title">
                      <div>
                        <span>{{
                          $t('JOB_DETAIL.REQUIRED_LANGUAGE_CONDITION')
                        }}</span>
                      </div>
                    </div>
                    <div class="form-item-row__inputs">
                      <div>
                        <span>{{ formData.required_language_condition }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- 1.9 歓迎スキル Preferred Skill -->
                  <div class="form-item-row border-b">
                    <div class="form-item-row__title">
                      <div>
                        <span>{{ $t('JOB_DETAIL.PREFERRED_SKILL') }}</span>
                      </div>
                    </div>
                    <div class="form-item-row__inputs">
                      <div>
                        <span>{{ formData.preferred_skill }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- 1.10 雇用形態 form of employment -->
                  <div class="form-item-row border-b">
                    <div class="form-item-row__title">
                      <div>
                        <span>{{ $t('JOB_DETAIL.FORM_OF_EMPLOYMENT') }}</span>
                      </div>
                    </div>
                    <div class="form-item-row__inputs">
                      <div>
                        <span>{{ formData.form_of_employment }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- 1.11 勤務時間 Working time -->
                  <div class="form-item-row border-b">
                    <div class="form-item-row__title">
                      <div>
                        <span>{{ $t('JOB_DETAIL.WORKING_TIME') }}</span>
                      </div>
                    </div>
                    <div class="form-item-row__inputs">
                      <div>
                        <span>{{ formData.working_time }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- 1.12 休日・休暇 Vacation -->
                  <div class="form-item-row border-b">
                    <div class="form-item-row__title">
                      <div>
                        <span>{{ $t('JOB_DETAIL.VACATION') }}</span>
                      </div>
                    </div>
                    <div class="form-item-row__inputs">
                      <div>
                        <span>{{ formData.vacation }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- 1.13 想定年収 expected income -->
                  <div class="form-item-row border-b">
                    <div class="form-item-row__title">
                      <div>
                        <span>{{ $t('JOB_DETAIL.EXPECTED_INCOME') }}</span>
                      </div>
                    </div>
                    <div class="form-item-row__inputs">
                      <div>
                        <span>{{ formData.expected_income }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- 1.14 勤務地（都道府県） working palace (tỉnh,TP) -->
                  <div class="form-item-row border-b">
                    <div class="form-item-row__title">
                      <div>
                        <span>{{ $t('JOB_DETAIL.WORKING_PLACE') }}</span>
                      </div>
                    </div>
                    <div class="form-item-row__inputs">
                      <div>
                        <span>{{ formData.working_palace }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- 1.15 勤務地 詳細 Working palace detail -->
                  <div class="form-item-row border-b">
                    <div class="form-item-row__title">
                      <div>
                        <span>{{ $t('JOB_DETAIL.WORKING_PLACE_DETAIL') }}</span>
                      </div>
                    </div>
                    <div class="form-item-row__inputs">
                      <div>
                        <span>{{ formData.working_palace_detail }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- 1.16 待遇・福利厚生 Treatment.Welfare -->
                  <div class="form-item-row border-b">
                    <div class="form-item-row__title">
                      <div>
                        <span>{{ $t('JOB_DETAIL.TREATMENT_WELFARE') }}</span>
                      </div>
                    </div>
                    <div class="form-item-row__inputs">
                      <div>
                        <span>{{ formData.treatment_welfare }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- 1.17 会社PR・魅力 Company PR.Appeal -->
                  <div class="form-item-row border-b">
                    <div class="form-item-row__title">
                      <div>
                        <span>{{ $t('JOB_DETAIL.COMPANY_PR_APPEAL') }}</span>
                      </div>
                    </div>
                    <div class="form-item-row__inputs">
                      <div>
                        <span>{{ formData.company_pr_appeal }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- 1.18 賞与・昇給の有無 Bonus.Pay rise -->
                  <div class="form-item-row border-b">
                    <div class="form-item-row__title">
                      <div>
                        <span>{{ $t('JOB_DETAIL.BONUS_PAY_RISE') }}</span>
                      </div>
                    </div>
                    <div class="form-item-row__inputs">
                      <div>
                        <span>{{ formData.bonus_pay_rise }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- 1.19 時間外労働の有無 Over time -->
                  <div class="form-item-row border-b">
                    <div class="form-item-row__title">
                      <div>
                        <span>{{ $t('JOB_DETAIL.OVER_TIME') }}</span>
                      </div>
                    </div>
                    <div class="form-item-row__inputs">
                      <div>
                        <span>{{ formData.over_time }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- 1.20 転勤の有無 Transfer -->
                  <div class="form-item-row border-b">
                    <div class="form-item-row__title">
                      <div>
                        <span>{{ $t('JOB_DETAIL.TRANSFER') }}</span>
                      </div>
                    </div>
                    <div class="form-item-row__inputs">
                      <div>
                        <span>{{ formData.interview_flow }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- 1.21 受動喫煙の有無 Passive smoking -->
                  <div class="form-item-row border-b">
                    <div class="form-item-row__title">
                      <div>
                        <span>{{ $t('JOB_DETAIL.PASSIVE_SMOKING') }}</span>
                      </div>
                    </div>
                    <div class="form-item-row__inputs">
                      <div>
                        <span>{{ formData.passive_smoking }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- 1.22 特徴 Passion -->
                  <div class="form-item-row border-b">
                    <div class="form-item-row__title">
                      <div>
                        <span>{{ $t('JOB_DETAIL.PASSION') }}</span>
                      </div>
                    </div>
                    <div class="form-item-row__inputs">
                      <div>
                        <span>{{ formData.passion }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- 1.23 面接フロー  Interview flow -->
                  <div class="form-item-row" style="flex: 1">
                    <div class="form-item-row__title">
                      <div>
                        <span>{{ $t('JOB_DETAIL.INTERVIEW_FOLLOW') }}</span>
                      </div>
                    </div>
                    <div class="form-item-row__inputs">
                      <div>
                        <span>{{ formData.interview_flow }}</span>
                      </div>
                    </div>
                  </div>

                  <!--  -->
                </div>
              </div>
            </div>
            <!-- 2 企業情報 Company information -->
            <div class="form-page-area">
              <div class="form-page-area__head">
                <span>{{ $t('TITLE.COMPANY_INFORMATION') }} </span>
              </div>

              <div class="form-page-area__content">
                <div class="form-page-area-content-wrap">
                  <!-- 2.1 設立年度 establishment year -->
                  <div class="form-item-row border-b">
                    <div class="form-item-row__title">
                      <div>
                        <span>{{ $t('JOB_DETAIL.ESTABLISHMENT_YEAR') }}</span>
                      </div>
                    </div>
                    <div class="form-item-row__inputs">
                      <div>
                        <span>{{ formData.establishment_year }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- 2.2 創業年度 startup year -->
                  <div class="form-item-row border-b">
                    <div class="form-item-row__title">
                      <div>
                        <span>{{ $t('JOB_DETAIL.STARTUP_YEAR') }}</span>
                      </div>
                    </div>
                    <div class="form-item-row__inputs">
                      <div>
                        <span>{{ formData.startup_year }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- 2.3 資本金等 capital -->
                  <div class="form-item-row border-b">
                    <div class="form-item-row__title">
                      <div>
                        <span>{{ $t('JOB_DETAIL.CAPITAL') }}</span>
                      </div>
                    </div>
                    <div class="form-item-row__inputs">
                      <div>
                        <span>{{ formData.capital }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- 2.4 売上金 proceeds -->
                  <div class="form-item-row border-b">
                    <div class="form-item-row__title">
                      <div>
                        <span>{{ $t('JOB_DETAIL.PROCEEDS') }}</span>
                      </div>
                    </div>
                    <div class="form-item-row__inputs">
                      <div>
                        <span>{{ formData.proceeds }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- 2.5 従業員数 number of staffs -->
                  <div class="form-item-row border-b">
                    <div class="form-item-row__title">
                      <div>
                        <span>{{ $t('JOB_DETAIL.NUMBER_OF_STAFFS') }}</span>
                      </div>
                    </div>
                    <div class="form-item-row__inputs">
                      <div>
                        <span>{{ formData.number_of_staffs }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- 2.6 事務所数 number of operations -->
                  <div class="form-item-row border-b">
                    <div class="form-item-row__title">
                      <div>
                        <span>{{ $t('JOB_DETAIL.NUMBER_OF_OPERATIONS') }}</span>
                      </div>
                    </div>
                    <div class="form-item-row__inputs">
                      <div>
                        <span>{{ formData.number_of_operations }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- 2.7 店舗数 number of shops -->
                  <div class="form-item-row border-b">
                    <div class="form-item-row__title">
                      <div>
                        <span>{{ $t('JOB_DETAIL.NUMBER_OF_SHOPS') }}</span>
                      </div>
                    </div>
                    <div class="form-item-row__inputs">
                      <div>
                        <span>{{ formData.number_of_shops }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- 2.8 工場数 number of factories -->
                  <div class="form-item-row border-b">
                    <div class="form-item-row__title">
                      <div>
                        <span>{{ $t('JOB_DETAIL.NUMBER_OF_FACTORIES') }}</span>
                      </div>
                    </div>
                    <div class="form-item-row__inputs">
                      <div>
                        <span>{{ formData.number_of_factories }}</span>
                      </div>
                    </div>
                  </div>

                  <!-- 2.9 決算月 fiscal year -->
                  <div class="form-item-row" style="flex: 1">
                    <div class="form-item-row__title">
                      <div>
                        <span>{{ $t('JOB_DETAIL.FISCAL_YEAR') }}</span>
                      </div>
                    </div>
                    <div class="form-item-row__inputs">
                      <div>
                        <span>{{ formData.fiscal_year }}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!--  -->
          </div>
          <!--  -->
        </div>
        <!-- BTN エントリーに進む Move to Entry -->
        <div class="job-detail-for-hr-form-btn">
          <div
            :disable="allow_btn"
            style="width: 360px"
            @click="goToMoveToEntry"
          >
            <BtnMoveNext :min-width="'360px'" :direction="'next'" />
          </div>
        </div>
        <!--  -->
      </div>
    </div>
  </div>
</template>

<script>
// import { MakeToast } from '../../utils/toastMessage';
// import { obj2Path } from '@/utils/obj2Path';
// import SearchWithConditions from '../../layout/components/search/SearchWithConditions.vue';
import { dataDetailHr } from '@/pages/JobSearch/JobDetailForHr/dataJobDetail.js';
import BtnMoveNext from '@/components/BtnMoveNext';

// const urlAPI = {
//   urlGetLisUser: '/user',
//   urlDeleAll: 'user/ ',
// };
export default {
  name: 'JobDetailForHr',
  components: {
    BtnMoveNext,
  },

  data() {
    return {
      allow_btn: true,
      formData: dataDetailHr,
      role: 'HrOrganizationOversea',
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
    this.getRole();
  },

  methods: {
    goToBack() {
      console.log('goToBack');
    },
    goToMoveToEntry() {
      console.log('goToMoveToEntry');
      this.$router.push('/job-search/job-entry');
    },
    getRole() {
      console.log('getRole');
      this.allow_btn = true;
    },
    //
  },
};
</script>

<style lang="scss" scoped>
@import '@/pages/JobSearch/JobDetailForHr/JobDetailForHr.scss';
</style>
