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
      </div>
    </template>
    <div class="display-user-management-list">
      <div class="border-left-title font-weight-bold">
        <span class="title">{{ $t('TAB_HR_SEARCH') }} </span>
      </div>

      <div class="w-100 mb-2 outside-frame">
        <div class="inside-frame d-flex flex-column">
          <!-- 団体名 -->
          <div class="d-flex flex-row hr-org-search">
            <div class="d-flex left-col first-left-col">
              <span>{{ $t('HR_REGISTER.ORGANIZATION_NAME') }}</span>
            </div>

            <div class="d-flex right-col first-right-col">
              <b-form-select
                v-model="organization_name"
                dusk="hr_org_id"
                :options="organizationList"
                :disabled-field="'disabled'"
              />
            </div>
          </div>
          <!-- 性別 -->
          <div id="dusk_gender" class="d-flex flex-row gender-search">
            <div class="d-flex left-col">
              <span>{{ $t('HR_REGISTER.GENDER') }}</span>
            </div>

            <!-- <div class="d-flex right-col">
            <div class="d-flex flex-row" style="gap: 30px">
              <b-form-checkbox
                v-model="male"
                name="gender-male"
                size="lg"
                @change="handleInputCheckboxGender(1)"
              >
                <span class="checkbox-label">男性</span>
              </b-form-checkbox>

              <b-form-checkbox
                v-model="female"
                name="gender-female"
                size="lg"
                @change="handleInputCheckboxGender(2)"
              >
                <span class="checkbox-label">女性</span>
              </b-form-checkbox>
            </div>
          </div> -->
            <div class="d-flex right-col">
              <div class="d-flex flex-row" style="gap: 30px">
                <b-form-checkbox-group
                  id="checkbox-group-1"
                  v-model="GENDER"
                  :options="genderOptions"
                  value-field="key"
                  text-field="value"
                />
              </div>
            </div>
          </div>
          <!-- 年齢 -->
          <div class="d-flex flex-row age-search">
            <div class="d-flex left-col">
              <span>{{ $t('HR_REGISTER.AGE') }}</span>
            </div>

            <div class="d-flex right-col">
              <div class="d-flex flex-row">
                <b-form-select
                  v-model="age_from"
                  dusk="age_from"
                  :options="ageOptions"
                  :disabled-field="'disabled'"
                />
                <span class="date-description">{{ $t('AGE') }}</span>

                <span class="date-description"> 〜 </span>

                <b-form-select
                  v-model="age_to"
                  dusk="age_to"
                  :options="ageOptions"
                  :disabled-field="'disabled'"
                />
                <span class="date-description">{{ $t('AGE') }}</span>
              </div>
            </div>
          </div>
          <!-- 最終学歴（年月） -->
          <div class="d-flex flex-row final-education-search">
            <div class="d-flex left-col">
              <span>{{ $t('FINAL_ACADEMIC_BACKGROUND') }}</span>
            </div>

            <div class="d-flex right-col">
              <div class="d-flex flex-row">
                <b-form-select
                  v-model="final_education_date_from_year"
                  dusk="final_education_date_from_year"
                  :options="yearList"
                  :disabled-field="'disabled'"
                />
                <span class="date-description">{{
                  $t('HR_LIST_FORM.YEAR')
                }}</span>

                <b-form-select
                  v-model="final_education_date_from_month"
                  dusk="final_education_date_from_month"
                  :options="monthList"
                  :disabled-field="'disabled'"
                />
                <span class="date-description">月</span>

                <span class="date-description">〜</span>

                <b-form-select
                  v-model="final_education_date_to_year"
                  dusk="final_education_date_to_year"
                  :options="yearList"
                  :disabled-field="'disabled'"
                />
                <span class="date-description">{{
                  $t('HR_LIST_FORM.YEAR')
                }}</span>

                <b-form-select
                  v-model="final_education_date_to_month"
                  dusk="final_education_date_to_month"
                  :options="monthList"
                  :disabled-field="'disabled'"
                />
                <span class="date-description">月</span>
              </div>
            </div>
          </div>
          <!-- 最終学歴（区分） -->
          <div class="d-flex flex-row final-education-department-search">
            <div class="d-flex left-col">
              <span>{{
                $t('HR_LIST_FORM.FINAL_EDUCATION_CLASSIFICATION')
              }}</span>
            </div>

            <!-- <div class="d-flex right-col">
            <div class="d-flex flex-row" style="gap: 30px">
              <b-form-checkbox
                v-model="graduation"
                name="graduation"
                size="lg"
                @change="handleInputCheckboxClassification(1)"
              >
                <span class="checkbox-label">卒業</span>
              </b-form-checkbox>

              <b-form-checkbox
                v-model="finish"
                name="finish"
                size="lg"
                @change="handleInputCheckboxClassification(2)"
              >
                <span class="checkbox-label">中退</span>
              </b-form-checkbox>

              <b-form-checkbox
                v-model="dropout"
                name="dropout"
                size="lg"
                @change="handleInputCheckboxClassification(3)"
              >
                <span class="checkbox-label">卒業見込み</span>
              </b-form-checkbox>
            </div>
          </div> -->
            <div id="dusk_edu_class" class="d-flex right-col">
              <b-form-checkbox-group
                v-model="edu_class"
                :options="finalEductionClassification"
                value-field="key"
                text-field="value"
              />
            </div>
          </div>
          <!-- 最終学歴（学位） -->
          <div id="dusk_edu_degree" class="d-flex flex-row degree-search">
            <div class="d-flex left-col">
              <span>{{ $t('HR_LIST_FORM.FINAL_EDUCATION_DEGREE') }}</span>
            </div>

            <div class="d-flex right-col">
              <!-- <div class="d-flex flex-row" style="gap: 30px">
              <b-form-checkbox
                v-model="bachelor_master"
                name="degree-bachelor-master"
                size="lg"
                @change="handleInputCheckboxDegree(1)"
              >
                <span class="checkbox-label">大学卒業以上</span>
              </b-form-checkbox>

              <b-form-checkbox
                v-model="human_resource"
                name="degree-human-resource"
                size="lg"
                @change="handleInputCheckboxDegree(2)"
              >
                <span class="checkbox-label">大学卒業以外</span>
              </b-form-checkbox>
            </div> -->
              <b-form-checkbox-group
                v-model="edu_degree"
                :options="finalEductionDegree"
                value-field="key"
                text-field="value"
                disabled-field="notEnabled"
              />
            </div>
          </div>

          <!-- 最終学歴（選考学科） -->
          <div
            class="d-flex flex-row final-education-select-department-seaarch"
          >
            <div class="d-flex left-col">
              <span>{{ $t('HR_LIST_FORM.FINAL_ACADEMIC') }}</span>
            </div>

            <div class="d-flex flex-column right-col align-items-start">
              <div class="d-flex flex-row">
                <b-button
                  class="specify-button course-specify"
                  dusk="btn_edu_course"
                  @click="handleOpenModalSpecifyCourse()"
                >
                  <i class="fas fa-plus" />
                  <span>{{ $t('HR_REGISTER.SPECIFY') }}</span>
                </b-button>
              </div>
              <div
                v-for="item in selected_courses"
                :key="item.id"
                class="d-flex align-items-center px-2 mt-3"
              >
                <span
                  class="icon-checked"
                  @click="handleRemoveSelectedCourse(item.id)"
                />
                <span>{{
                  item.name_ja + ` (${item.childOptions.length})`
                }}</span>
              </div>
            </div>
          </div>

          <!-- 勤務形態 -->
          <div id="dusk_work_forms" class="d-flex flex-row work-style-search">
            <div class="d-flex left-col">
              <span>{{ $t('HR_REGISTER.WORK_FORM') }}</span>
            </div>

            <div class="d-flex right-col">
              <!-- <div class="d-flex flex-row" style="gap: 30px">
              <b-form-checkbox
                v-model="work_from_1"
                name="work-from-1"
                size="lg"
                @change="handleInputCheckboxWorkForm(1)"
              >
                <span class="checkbox-label">正社員</span>
              </b-form-checkbox>

              <b-form-checkbox
                v-model="work_from_2"
                name="work-from-2"
                size="lg"
                @change="handleInputCheckboxWorkForm(2)"
              >
                <span class="checkbox-label">契約社員</span>
              </b-form-checkbox>

              <b-form-checkbox
                v-model="work_from_3"
                name="work-from-3"
                size="lg"
                @change="handleInputCheckboxWorkForm(3)"
              >
                <span class="checkbox-label">派遣社員</span>
              </b-form-checkbox>

              <b-form-checkbox
                v-model="work_from_4"
                name="work-from-4"
                size="lg"
                @change="handleInputCheckboxWorkForm(4)"
              >
                <span class="checkbox-label">その他</span>
              </b-form-checkbox>
            </div> -->
              <b-form-checkbox-group
                v-model="work_forms"
                :options="workFormOptions"
                value-field="key"
                text-field="value"
              />
            </div>
          </div>
          <!-- 勤務形態（非常勤） -->
          <div class="d-flex flex-row part-time-search">
            <div class="d-flex left-col">
              <span>{{ $t('HR_REGISTER.WORK_FORM_PARTTIME') }}</span>
            </div>

            <div class="d-flex right-col">
              <div id="dusk_work_part_time" class="d-flex flex-row">
                <!-- <b-form-checkbox v-model="part_time" name="part-time" size="lg">
                <span class="checkbox-label">週28時間以下</span>
              </b-form-checkbox> -->
                <b-form-checkbox-group
                  v-model="work_hour"
                  :options="workFormParttimeOptions"
                  value-field="key"
                  text-field="value"
                />
              </div>
            </div>
          </div>
          <!-- 日本語レベル -->
          <div class="d-flex flex-row japan-level-search">
            <div class="d-flex left-col">
              <span>{{ $t('HR_REGISTER.JAPANESE_LEVEL') }}</span>
            </div>

            <div class="d-flex right-col">
              <div
                id="dusk_japan_levels"
                class="d-flex flex-row"
                style="gap: 30px"
              >
                <!-- <b-form-checkbox
                v-model="japanese_level_1"
                name="japanese-level-1"
                size="lg"
                @change="handleInputCheckboxJapaneseLevel(1)"
              >
                <span class="checkbox-label">N1</span>
              </b-form-checkbox>

              <b-form-checkbox
                v-model="japanese_level_2"
                name="japanese-level-2"
                size="lg"
                @change="handleInputCheckboxJapaneseLevel(2)"
              >
                <span class="checkbox-label">N2</span>
              </b-form-checkbox>

              <b-form-checkbox
                v-model="japanese_level_3"
                name="japanese-level-3"
                size="lg"
                @change="handleInputCheckboxJapaneseLevel(3)"
              >
                <span class="checkbox-label">N3</span>
              </b-form-checkbox>

              <b-form-checkbox
                v-model="japanese_level_4"
                name="japanese-level-4"
                size="lg"
                @change="handleInputCheckboxJapaneseLevel(4)"
              >
                <span class="checkbox-label">N4</span>
              </b-form-checkbox>

              <b-form-checkbox
                v-model="japanese_level_5"
                name="japanese-level-5"
                size="lg"
                @change="handleInputCheckboxJapaneseLevel(5)"
              >
                <span class="checkbox-label">N5</span>
              </b-form-checkbox>

              <b-form-checkbox
                v-model="japanese_level_6"
                name="japanese-level-other"
                size="lg"
                @change="handleInputCheckboxJapaneseLevel(6)"
              >
                <span class="checkbox-label">資格なし</span>
              </b-form-checkbox> -->
                <b-form-checkbox-group
                  v-model="japan_levels"
                  :options="jaLevelOption"
                  value-field="key"
                  text-field="value"
                />
              </div>
            </div>
          </div>
          <!-- 主な職務経歴 -->
          <div class="d-flex flex-row job-experience-search">
            <div class="d-flex left-col">
              <span>{{ $t('HR_LIST_FORM.MAIN_JOB_CAREER') }}</span>
            </div>

            <div class="d-flex flex-column right-col align-items-start">
              <div class="d-flex flex-row">
                <b-button
                  class="specify-button job-experience-specify"
                  dusk="btn_main_job"
                  @click="handleOpenModalJobExperience()"
                >
                  <i class="fas fa-plus" />
                  <span>{{ $t('HR_REGISTER.SPECIFY') }}</span>
                </b-button>
              </div>
              <div
                v-for="item in selected_job_experiences"
                :key="item.id"
                class="d-flex align-items-center px-2 mt-3"
              >
                <span
                  class="icon-checked"
                  @click="handleRemoveSelected(item.id)"
                />
                <span>{{
                  item.name_ja + ` (${item.childOptions.length})`
                }}</span>
              </div>
            </div>
          </div>
          <!-- キーワード -->
          <div class="d-flex flex-row free-text-search">
            <div class="d-flex left-col last-left-col">
              <span>{{ $t('KEY_WORD') }}</span>
            </div>

            <div class="d-flex right-col last-right-col">
              <div class="w-100 d-flex flex-row">
                <b-form-input
                  v-model="key_word"
                  dusk="input_search"
                  type="text"
                />
              </div>
            </div>
          </div>
        </div>

        <div class="w-100 d-flex flex-column align-items-center footer">
          <b-button
            class="search-button btn_search--custom"
            dusk="btn_search_with_conditions"
            @click="handleProcessSearchEngine()"
          >
            <span>{{ $t('SEARCH_WITH_CONDITIONS') }}</span>
            <i class="fas fa-chevron-right icon" />
          </b-button>

          <span
            class="clear-setting-button"
            dusk="btn_clear_settings"
            @click="handleClearAllSetting()"
          >{{ $t('BUTTON.CLEAR_SETTINGS') }}</span>
        </div>
      </div>
      <ModalMultipleSelectCourse :options="modalCourseOptions" />

      <ModalMultipleSelectJobExp :options="modalJobExpOptions" />
    </div>
  </b-overlay>
</template>

<script>
import { renderYears } from '@/pages/Hr/common.js';
import { getOptionHrOrganization } from '@/api/modules/matching.js';
import {
  jaLevelOption,
  finalEductionClassification,
  finalEductionDegree,
  workFormOptions,
  workFormParttimeOptions,
  finalEductionMonth,
  genderOptions,
  renderAge,
  // renderYearsEducationTiming,
} from '@/const/hrOrganization.js';

import EventBus from '@/utils/eventBus';
import ModalMultipleSelectJobExp from '@/components/Modal/ModalMultipleSelect.vue';
import ModalMultipleSelectCourse from '@/components/Modal/ModalMultipleSelectCourse.vue';
import { getListMainjob, getListEduCourse } from '@/api/job';
import { MakeToast } from '../../utils/toastMessage';

export default {
  name: 'JobSearch',
  components: {
    ModalMultipleSelectCourse,
    ModalMultipleSelectJobExp,
  },
  data() {
    return {
      overlay: {
        show: false,
        variant: 'light',
        opacity: 0,
        blur: '1rem',
        rounded: 'sm',
      },
      organization_name: null,
      organizationList: [
        { value: null, text: '選択してください', disabled: true },
      ],
      ageOptions: renderAge(),

      // male: '',
      // female: '',
      finalEductionClassification: finalEductionClassification,
      finalEductionDegree: finalEductionDegree,
      genderOptions: genderOptions,
      workFormOptions: workFormOptions,
      workFormParttimeOptions: workFormParttimeOptions,
      jaLevelOption: jaLevelOption,
      modalDataSelectedCourse: [],
      GENDER: [],
      work_forms: [],
      edu_class: [],
      edu_degree: [],
      work_hour: null,
      japan_levels: [],
      age_from: null,
      age_to: null,

      final_education_date_from_year: null,
      final_education_date_from_month: null,

      final_education_date_to_year: null,
      final_education_date_to_month: null,

      yearList: [{ value: null, text: '選択してください', disabled: false }],

      monthList: [{ value: null, text: '選択してください', disabled: false }],

      graduation: '',
      finish: '',
      dropout: '',

      bachelor_master: '',
      human_resource: '',

      selected_courses: [],

      work_from_1: '',
      work_from_2: '',
      work_from_3: '',
      work_from_4: '',

      part_time: null,

      japanese_level_1: '',
      japanese_level_2: '',
      japanese_level_3: '',
      japanese_level_4: '',
      japanese_level_5: '',
      japanese_level_6: '',

      selected_job_experiences: [],

      key_word: '',

      modalCourseOptions: [],
      modalJobExpOptions: [],

      isShowModalSepecifyCourse: false,

      isShowModalSepecifyJobExp: false,
    };
  },
  created() {
    this.handleInitalComponentData();
    // EventBus.$on('modalSpecifyCourseShowStatusChanged', (status) => {
    //   this.isShowModalSepecifyCourse = status;
    // });

    // EventBus.$on('modalSpecifyJobExpShowStatusChanged', (status) => {
    //   this.isShowModalSepecifyJobExp = status;
    //   // modalJobExpOptions
    // });

    EventBus.$on('dataModalMultipleCourse', (data) => {
      // this.selected_courses = data;
      const listParentIds = Object.keys(data);

      const listSelected = [];

      listParentIds.length > 0 &&
        listParentIds.map((id) => {
          const parent = this.modalCourseOptions.find(
            (x) => x.id === Number(id)
          );

          listSelected.push({
            ...parent,
            childOptions: data[id],
          });
        });

      this.selected_courses = listSelected;
    });

    EventBus.$on('dataModalMultiple', (data) => {
      // this.selected_job_experiences = data;
      const listParentIds = Object.keys(data);
      const listSelected = [];

      listParentIds.length > 0 &&
        listParentIds.map((id) => {
          const parent = this.modalJobExpOptions.find(
            (x) => x.id === Number(id)
          );
          listSelected.push({
            ...parent,
            childOptions: data[id],
          });
        });

      this.selected_job_experiences = listSelected;
    });
  },
  // destroyed() {
  //   // EventBus.$off('modalSpecifyCourseShowStatusChanged');
  //   // EventBus.$off('modalSpecifyJobExpShowStatusChanged');
  //   // EventBus.$off('dataModalMultipleCourse');
  //   // EventBus.$off('dataModalMultiple');
  // },
  methods: {
    async handleInitalComponentData() {
      await this.handleGetListOptionHrOrg();
      await this.handleRenderSelectOptions();
      await this.getListMainjob();
      await this.getListEduCourse();
      // await this.handleSyncHrSearchData();
    },
    async handleGetListOptionHrOrg() {
      const PARAM = {
        per_page: -1,
      };

      try {
        const response = await getOptionHrOrganization(PARAM);

        const { code, data } = response.data;

        if (code === 200) {
          const RAW_DATA = data;

          RAW_DATA.forEach((item) => {
            this.organizationList.push({
              value: item['id'],
              text: `${item['corporate_name_en']}`,
              disabled: false,
            });
          });
        }
      } catch (error) {
        console.log(error);
      }
    },
    handleRenderSelectOptions() {
      const LIST_YEAR = renderYears();

      LIST_YEAR.forEach((year) => {
        this.yearList.push({ value: year, text: `${year}`, disabled: false });
      });

      // const LIST_MONTH = renderMonth();
      const LIST_MONTH = finalEductionMonth;

      LIST_MONTH.forEach((month) => {
        this.monthList.push({
          value: month.key,
          // text: `${month}`,
          text: month.value,
          disabled: false,
        });
      });
    },
    handleOpenModalSpecifyCourse() {
      // this.isShowModalSepecifyCourse = true;
      EventBus.$emit('showModalSelectCourse', true);
    },
    handleOpenModalJobExperience() {
      // this.isShowModalSepecifyJobExp = true;
      EventBus.$emit('showModalSelect', true);
    },
    async handleProcessSearchEngine() {
      // const GENDER = [];
      // if (this.male) {
      //   GENDER.push(1);
      // }
      // if (this.female) {
      //   GENDER.push(2);
      // }
      // const checkedWorkHour = 1; // check chọn 週28時間以下
      const DATA = {
        hr_org_id: this.organization_name,
        // gender: this.handleReturnCheckboxInput([this.male, this.female]),
        gender: this.GENDER,
        age_from: this.age_from,
        age_to: this.age_to,
        edu_date_from: this.handleMergeYearMonth(
          this.final_education_date_from_year,
          this.final_education_date_from_month
        ),
        edu_date_to: this.handleMergeYearMonth(
          this.final_education_date_to_year,
          this.final_education_date_to_month
        ),
        // edu_class: this.handleReturnCheckboxInput([
        //   this.graduation,
        //   this.finish,
        //   this.dropout,
        // ]),
        edu_class: this.edu_class,
        // edu_degree: this.handleReturnCheckboxInput([
        //   this.bachelor_master,
        //   this.human_resource,
        // ]),
        edu_degree: this.edu_degree,

        middle_class: this.selected_courses,
        // work_forms: this.handleReturnCheckboxInput([
        //   this.work_from_1,
        //   this.work_from_2,
        //   this.work_from_3,
        //   this.work_from_4,
        // ]),
        work_forms: this.work_forms,
        // work_hour:
        //   this.work_hour.length === 0
        //     ? ''
        //     : this.work_hour.includes(checkedWorkHour),
        work_hour: this.work_hour && this.work_hour.length > 0,
        // japan_levels: this.handleReturnCheckboxInput([
        //   this.japanese_level_1,
        //   this.japanese_level_2,
        //   this.japanese_level_3,
        //   this.japanese_level_4,
        //   this.japanese_level_5,
        //   this.japanese_level_6,
        // ]),
        japan_levels: this.japan_levels,
        main_job_ids: this.selected_job_experiences,
        search: this.key_word,
      };
      if (DATA.edu_date_to === 'null-' || DATA.edu_date_to === '-') {
        delete DATA.edu_date_to;
      }
      if (DATA.edu_date_from === 'null-' || DATA.edu_date_from === '-') {
        delete DATA.edu_date_from;
      }
      // console.log('DATA===>', DATA);

      await this.$store.dispatch('hrSearchQuery/setSearchParams', DATA);

      this.$router.push({ path: '/hr-search/list' }, (onAbort) => {});
    },
    handleClearAllSetting() {
      this.organization_name = null;

      // this.male = '';
      // this.female = '';
      this.GENDER = [];
      this.work_forms = [];
      this.edu_class = [];
      this.edu_degree = [];
      this.work_hour = [];
      this.japan_levels = [];
      this.selected_courses = [];
      this.selected_job_experiences = [];

      EventBus.$emit('handleClearSettingsModal');
      EventBus.$emit('handleClearSettingsModalCourse');

      this.age_from = null;
      this.age_to = null;

      this.final_education_date_from_year = null;
      this.final_education_date_from_month = null;

      this.final_education_date_to_year = null;
      this.final_education_date_to_month = null;

      this.graduation = '';
      this.finish = '';
      this.dropout = '';

      this.bachelor_master = '';
      this.human_resource = '';

      this.work_from_1 = '';
      this.work_from_2 = '';
      this.work_from_3 = '';
      this.work_from_4 = '';

      this.part_time = null;

      this.japanese_level_1 = '';
      this.japanese_level_2 = '';
      this.japanese_level_3 = '';
      this.japanese_level_4 = '';
      this.japanese_level_5 = '';
      this.japanese_level_6 = '';

      this.key_word = '';
    },
    // handleInputCheckboxGender(type) {
    //   switch (type) {
    //     case 1:
    //       this.male = true;
    //       // this.female = '';
    //       break;
    //     case 2:
    //       // this.male = '';
    //       this.female = true;
    //       break;
    //     default:
    //       break;
    //   }
    // },
    handleInputCheckboxClassification(type) {
      switch (type) {
        case 1:
          this.graduation = true;
          this.finish = '';
          this.dropout = '';
          break;
        case 2:
          this.graduation = '';
          this.finish = true;
          this.dropout = '';
          break;
        case 3:
          this.graduation = '';
          this.finish = '';
          this.dropout = true;
          break;
        default:
          break;
      }
    },
    handleInputCheckboxDegree(type) {
      switch (type) {
        case 1:
          this.bachelor_master = true;
          this.human_resource = '';
          break;
        case 2:
          this.bachelor_master = '';
          this.human_resource = true;
          break;
        default:
          break;
      }
    },
    handleInputCheckboxWorkForm(type) {
      switch (type) {
        case 1:
          this.work_from_1 = true;
          this.work_from_2 = '';
          this.work_from_3 = '';
          this.work_from_4 = '';
          break;
        case 2:
          this.work_from_1 = '';
          this.work_from_2 = true;
          this.work_from_3 = '';
          this.work_from_4 = '';
          break;
        case 3:
          this.work_from_1 = '';
          this.work_from_2 = '';
          this.work_from_3 = true;
          this.work_from_4 = '';
          break;
        case 4:
          this.work_from_1 = '';
          this.work_from_2 = '';
          this.work_from_3 = '';
          this.work_from_4 = true;
          break;
        default:
          break;
      }
    },
    handleInputCheckboxJapaneseLevel(type) {
      switch (type) {
        case 1:
          this.japanese_level_1 = true;
          this.japanese_level_2 = '';
          this.japanese_level_3 = '';
          this.japanese_level_4 = '';
          this.japanese_level_5 = '';
          this.japanese_level_6 = '';
          break;
        case 2:
          this.japanese_level_1 = '';
          this.japanese_level_2 = true;
          this.japanese_level_3 = '';
          this.japanese_level_4 = '';
          this.japanese_level_5 = '';
          this.japanese_level_6 = '';
          break;
        case 3:
          this.japanese_level_1 = '';
          this.japanese_level_2 = '';
          this.japanese_level_3 = true;
          this.japanese_level_4 = '';
          this.japanese_level_5 = '';
          this.japanese_level_6 = '';
          break;
        case 4:
          this.japanese_level_1 = '';
          this.japanese_level_2 = '';
          this.japanese_level_3 = '';
          this.japanese_level_4 = true;
          this.japanese_level_5 = '';
          this.japanese_level_6 = '';
          break;
        case 5:
          this.japanese_level_1 = '';
          this.japanese_level_2 = '';
          this.japanese_level_3 = '';
          this.japanese_level_4 = '';
          this.japanese_level_5 = true;
          this.japanese_level_6 = '';
          break;
        case 6:
          this.japanese_level_1 = '';
          this.japanese_level_2 = '';
          this.japanese_level_3 = '';
          this.japanese_level_4 = '';
          this.japanese_level_5 = '';
          this.japanese_level_6 = true;
          break;
        default:
          break;
      }
    },
    handleReturnCheckboxInput(array = []) {
      let result;

      array.forEach((item, index) => {
        if (item) {
          result = index + 1;
        }
      });

      return result;
    },
    handleMergeYearMonth(year, month) {
      let result;

      if (year && month) {
        result = `${year}-${this.handleFormatMonthDate(month)}`;
      }

      return result;
      // return `${year}-${this.handleFormatMonthDate(month)}`;
    },
    handleFormatMonthDate(string) {
      // let result;

      // if (string) {
      //   if (parseInt(string) < 10) {
      //     result = `0${string}`;
      //   } else {
      //     result = string;
      //   }
      // }

      // return result;
      if (string) {
        if (parseInt(string) < 10) {
          return `${string}`;
        } else {
          return string;
        }
      } else {
        return '';
      }
    },

    handleRemoveSelectedCourse(id) {
      this.selected_courses = this.selected_courses.filter(
        (item) => item.id !== Number(id)
      );

      EventBus.$emit('removeSelectedCourse', Number(id));
    },

    handleRemoveSelected(id) {
      this.selected_job_experiences = this.selected_job_experiences.filter(
        (item) => item.id !== Number(id)
      );

      EventBus.$emit('removeSelected', Number(id));
    },

    async getListEduCourse() {
      try {
        const response = await getListEduCourse();
        const { code, message } = response.data;
        if (code === 200) {
          const { data } = response.data;
          this.modalCourseOptions = data.map((item) => {
            return {
              ...item,
              childOptions: item.job_info,
            };
          });
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
    },

    async getListMainjob() {
      try {
        const response = await getListMainjob();
        const { code, message } = response.data;
        if (code === 200) {
          const { data } = response.data;
          this.modalJobExpOptions = data.map((item) => {
            return {
              ...item,
              childOptions: item.job_info,
            };
          });
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
    },
  },
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/HrSearch/index.scss';
</style>
