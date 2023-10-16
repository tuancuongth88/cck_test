<template>
  <div class="search-with-conditions">
    <div class="search-with-conditions-wrap">
      <div class="item-search-vs-condition">
        <b-button
          :class="
            status_organization_name ? null : 'collapsed  btn border-none'
          "
          dusk="btn_organization_name"
          :aria-expanded="status_organization_name"
          aria-controls="collapse-1"
          @click="status_organization_name = !status_organization_name"
        >
          <span :class="{ 'text-blue': formData.hr_org_id }">
            {{ $t('HR_REGISTER.ORGANIZATION_NAME') }}
          </span>

          <img
            :src="require('@/assets/images/login/chervon-right-light.png')"
            alt="collapse"
            :class="{ 'rotate-90deg': status_organization_name }"
          >
        </b-button>
        <b-collapse
          id="collapse-1"
          v-model="status_organization_name"
          class="w-100"
        >
          <b-card>
            <b-form-select
              v-model="formData.hr_org_id"
              dusk="hr_org_id"
              :options="organizationOptions"
              value-field="key"
              text-field="value"
            />
          </b-card>
        </b-collapse>
      </div>
      <div id="dusk_gender" class="item-search-vs-condition">
        <b-button
          :class="status_gender_radio ? null : 'collapsed  btn border-none'"
          dusk="btn_gender"
          :aria-expanded="status_gender_radio"
          aria-controls="collapse-1"
          @click="status_gender_radio = !status_gender_radio"
        >
          <span :class="{ 'text-blue': formData.gender.length > 0 }">
            {{ $t('HR_REGISTER.GENDER') }}
          </span>

          <img
            :src="require('@/assets/images/login/chervon-right-light.png')"
            alt="collapse"
            :class="{ 'rotate-90deg': status_gender_radio }"
          >
        </b-button>
        <b-collapse id="collapse-1" v-model="status_gender_radio" class="w-100">
          <b-card>
            <b-form-checkbox-group
              id="checkbox-group-1"
              v-model="formData.gender"
              :options="genderOptions"
              value-field="key"
              text-field="value"
              stacked
            />
          </b-card>
        </b-collapse>
      </div>

      <div class="item-search-vs-condition">
        <b-button
          :class="status_age ? null : 'collapsed  btn border-none'"
          dusk="btn_age"
          :aria-expanded="status_age"
          aria-controls="collapse-1"
          @click="status_age = !status_age"
        >
          <span :class="{ 'text-blue': formData.age_from || formData.age_to }">
            {{ $t('HR_REGISTER.AGE') }}
          </span>

          <img
            :src="require('@/assets/images/login/chervon-right-light.png')"
            alt="collapse"
            :class="{ 'rotate-90deg': status_age }"
          >
        </b-button>

        <b-collapse id="collapse-1" v-model="status_age" class="w-100">
          <b-card>
            <div
              class="w-100 d-flex justify-space-between align-center"
              style="gap: 0.5rem"
            >
              <div
                class="d-flex justify-space-between align-center"
                style="width: 40%; gap: 0.5rem"
              >
                <b-form-select
                  v-model="formData.age_from"
                  dusk="age_from"
                  :options="ageOptions"
                  class="pr-1"
                />
                <span>{{ $t('AGE') }}</span>
              </div>

              <span class="px-1">~</span>

              <div
                class="d-flex justify-space-between align-center"
                style="width: 40%; gap: 0.5rem"
              >
                <b-form-select
                  v-model="formData.age_to"
                  dusk="age_to"
                  :options="ageOptions"
                  class="pr-1"
                />

                <span>{{ $t('AGE') }}</span>
              </div>
            </div>
          </b-card>
        </b-collapse>
      </div>

      <div class="item-search-vs-condition">
        <b-button
          :class="
            status_final_education_date ? null : 'collapsed  btn border-none'
          "
          dusk="btn_final_education_date"
          :aria-expanded="status_final_education_date"
          aria-controls="collapse-1"
          @click="status_final_education_date = !status_final_education_date"
        >
          <span
            :class="{
              'text-blue':
                formData.final_education_date_from_year ||
                formData.final_education_date_from_month ||
                formData.final_education_date_to_year ||
                formData.final_education_date_to_month,
            }"
          >
            {{ $t('HR_LIST_FORM.FINAL_EDUCATION_DATE') }}
          </span>

          <img
            :src="require('@/assets/images/login/chervon-right-light.png')"
            alt="collapse"
            :class="{ 'rotate-90deg': status_final_education_date }"
          >
        </b-button>

        <b-collapse
          id="collapse-1"
          v-model="status_final_education_date"
          class="w-100"
        >
          <b-card>
            <div
              class="d-flex flex-column justify-space-between align-center"
              style="gap: 0.75rem; width: 90%; position: relative"
            >
              <span
                style="
                  position: absolute;
                  top: 8%;
                  right: -2rem;
                  font-size: 20px;
                "
              >~</span>

              <div
                class="w-100 d-flex justify-space-between align-center"
                style="gap: 0.5rem; align-items: center"
              >
                <div
                  class="d-flex justify-space-between align-center"
                  style="width: 55%; gap: 0.5rem; align-items: center"
                >
                  <b-form-select
                    v-model="formData.final_education_date_from_year"
                    dusk="final_education_date_from_year"
                    :options="yearOptions"
                    class="pr-1"
                  />
                  <span>{{ $t('HR_LIST_FORM.YEAR') }}</span>
                </div>

                <div
                  class="d-flex justify-space-between align-center"
                  style="width: 45%; gap: 0.5rem; align-items: center"
                >
                  <b-form-select
                    v-model="formData.final_education_date_from_month"
                    dusk="final_education_date_from_month"
                    :options="monthOptionList"
                    class="pr-1"
                  />
                  <span>{{ $t('JOB_SEARCH.MONTH') }}</span>
                </div>
              </div>

              <div
                class="w-100 d-flex justify-space-between align-center"
                style="gap: 0.5rem; align-items: center"
              >
                <div
                  class="d-flex justify-space-between align-center"
                  style="width: 55%; gap: 0.5rem; align-items: center"
                >
                  <b-form-select
                    v-model="formData.final_education_date_to_year"
                    dusk="final_education_date_to_year"
                    :options="yearOptions"
                    class="pr-1"
                  />
                  <span>{{ $t('HR_LIST_FORM.YEAR') }}</span>
                </div>

                <div
                  class="d-flex justify-space-between align-center"
                  style="width: 45%; gap: 0.5rem; align-items: center"
                >
                  <b-form-select
                    v-model="formData.final_education_date_to_month"
                    dusk="final_education_date_to_month"
                    :options="monthOptionList"
                    class="pr-1"
                  />
                  <span>{{ $t('JOB_SEARCH.MONTH') }}</span>
                </div>
              </div>
            </div>
          </b-card>
        </b-collapse>
      </div>

      <div class="item-search-vs-condition">
        <b-button
          :class="
            status_final_educational_background_division_1
              ? null
              : 'collapsed btn border-none'
          "
          dusk="btn_edu_class"
          :aria-expanded="status_final_educational_background_division_1"
          aria-controls="collapse-1"
          @click="
            status_final_educational_background_division_1 =
              !status_final_educational_background_division_1
          "
        >
          <span
            :class="{
              'text-blue': formData.edu_class.length > 0,
            }"
          >
            {{ $t('HR_LIST_FORM.FINAL_EDUCATION_CLASSIFICATION') }}
          </span>

          <img
            :src="require('@/assets/images/login/chervon-right-light.png')"
            alt="collapse"
            :class="{
              'rotate-90deg': status_final_educational_background_division_1,
            }"
          >
        </b-button>

        <b-collapse
          id="collapse-1"
          v-model="status_final_educational_background_division_1"
          class="w-100"
        >
          <b-card>
            <b-form-checkbox-group
              id="dusk_edu_class"
              v-model="formData.edu_class"
              dusk="edu_class"
              :options="finalEductionClassification"
              value-field="key"
              text-field="value"
              stacked
            />
          </b-card>
        </b-collapse>
      </div>

      <div id="dusk_edu_degree" class="item-search-vs-condition">
        <b-button
          :class="
            status_final_educational_background_degree
              ? null
              : 'collapsed  btn border-none'
          "
          dusk="btn_edu_degree"
          :aria-expanded="status_final_educational_background_degree"
          aria-controls="collapse-1"
          @click="
            status_final_educational_background_degree =
              !status_final_educational_background_degree
          "
        >
          <span :class="{ 'text-blue': formData.edu_degree.length > 0 }">
            {{ $t('HR_LIST_FORM.FINAL_EDUCATION_DEGREE') }}
          </span>

          <img
            :src="require('@/assets/images/login/chervon-right-light.png')"
            alt="collapse"
            :class="{
              'rotate-90deg': status_final_educational_background_degree,
            }"
          >
        </b-button>

        <b-collapse
          id="collapse-1"
          v-model="status_final_educational_background_degree"
          class="w-100"
        >
          <b-card>
            <b-form-checkbox-group
              v-model="formData.edu_degree"
              dusk="edu_degree"
              :options="finalEductionDegree"
              value-field="key"
              text-field="value"
              class="w-100 d-flex flex-column justify-space-between align-start"
              disabled-field="notEnabled"
            />
          </b-card>
        </b-collapse>
      </div>

      <div class="item-search-vs-condition">
        <b-button
          :class="
            status_final_education_course_specify_btn
              ? null
              : 'collapsed  btn border-none'
          "
          dusk="btn_edu_course"
          :aria-expanded="status_final_education_course_specify_btn"
          aria-controls="collapse-1"
          @click="
            status_final_education_course_specify_btn =
              !status_final_education_course_specify_btn
          "
        >
          <span :class="{ 'text-blue': modalDataSelectedCourse.length > 0 }">
            {{ $t('HR_LIST_FORM.FINAL_EDUCATION_COURSE') }}
          </span>

          <img
            :src="require('@/assets/images/login/chervon-right-light.png')"
            alt="collapse"
            :class="{
              'rotate-90deg': status_final_education_course_specify_btn,
            }"
          >
        </b-button>

        <b-collapse
          id="collapse-1"
          v-model="status_final_education_course_specify_btn"
          class="w-100"
        >
          <b-card>
            <div class="w-100">
              <div
                class="btn specify-btn"
                dusk="btn_specify"
                @click="handleOpenModalFinalEduCourse()"
              >
                <span>＋ </span>
                <span class="font-weight-bold">{{
                  $t('HR_REGISTER.SPECIFY')
                }}</span>
              </div>
            </div>

            <div
              v-for="item in modalDataSelectedCourse"
              :key="item.id"
              class="list-item-checked px-2 mt-3"
            >
              <span
                class="icon-checked"
                @click="handleRemoveSelectedCourse(item.id)"
              />
              <span>{{ item.name_ja + ` (${item.childOptions.length})` }}</span>
            </div>
          </b-card>
        </b-collapse>
      </div>

      <div id="dusk_work_forms" class="item-search-vs-condition">
        <b-button
          :class="status_work_form ? null : 'collapsed  btn border-none'"
          dusk="btn_work_forms"
          :aria-expanded="status_work_form"
          aria-controls="collapse-1"
          @click="status_work_form = !status_work_form"
        >
          <span :class="{ 'text-blue': formData.work_forms.length > 0 }">
            {{ $t('HR_REGISTER.WORK_FORM') }}
          </span>

          <img
            :src="require('@/assets/images/login/chervon-right-light.png')"
            alt="collapse"
            :class="{ 'rotate-90deg': status_work_form }"
          >
        </b-button>

        <b-collapse id="collapse-1" v-model="status_work_form" class="w-100">
          <b-card>
            <b-form-checkbox-group
              v-model="formData.work_forms"
              :options="workFormOptions"
              value-field="key"
              text-field="value"
              stacked
            />
          </b-card>
        </b-collapse>
      </div>

      <div id="dusk_work_part_time" class="item-search-vs-condition">
        <b-button
          :class="
            status_work_form_part_time ? null : 'collapsed  btn border-none'
          "
          dusk="btn_work_part_time"
          :aria-expanded="status_work_form_part_time"
          aria-controls="collapse-1"
          @click="status_work_form_part_time = !status_work_form_part_time"
        >
          <span :class="{ 'text-blue': formData.work_hour.length > 0 }">
            {{ $t('HR_REGISTER.WORK_FORM_PARTTIME') }}
          </span>

          <img
            :src="require('@/assets/images/login/chervon-right-light.png')"
            alt="collapse"
            :class="{ 'rotate-90deg': status_work_form_part_time }"
          >
        </b-button>

        <b-collapse
          id="collapse-1"
          v-model="status_work_form_part_time"
          class="w-100"
        >
          <b-card>
            <b-form-checkbox-group
              v-model="formData.work_hour"
              :options="workFormParttimeOptions"
              value-field="key"
              text-field="value"
              stacked
            />
          </b-card>
        </b-collapse>
      </div>
      <div id="dusk_japan_levels" class="item-search-vs-condition">
        <b-button
          :class="status_japan_levels ? null : 'collapsed  btn border-none'"
          dusk="btn_japan_levels"
          :aria-expanded="status_japan_levels"
          aria-controls="collapse-1"
          @click="status_japan_levels = !status_japan_levels"
        >
          <span :class="{ 'text-blue': formData.japan_levels.length > 0 }">
            {{ $t('HR_REGISTER.JAPANESE_LEVEL') }}
          </span>

          <img
            :src="require('@/assets/images/login/chervon-right-light.png')"
            alt="collapse"
            :class="{ 'rotate-90deg': status_japan_levels }"
          >
        </b-button>

        <b-collapse id="collapse-1" v-model="status_japan_levels" class="w-100">
          <b-card>
            <b-form-checkbox-group
              v-model="formData.japan_levels"
              :options="jaLevelOption"
              value-field="key"
              text-field="value"
              stacked
            />
          </b-card>
        </b-collapse>
      </div>

      <div class="item-search-vs-condition">
        <b-button
          :class="
            status_choose_job_specify_btn ? null : 'collapsed  btn border-none'
          "
          dusk="btn_main_job"
          :aria-expanded="status_choose_job_specify_btn"
          aria-controls="collapse-1"
          @click="
            status_choose_job_specify_btn = !status_choose_job_specify_btn
          "
        >
          <span :class="{ 'text-blue': modalDataSelected.length > 0 }">
            {{ $t('HR_LIST_FORM.MAIN_JOB_CAREER') }}
          </span>

          <img
            :src="require('@/assets/images/login/chervon-right-light.png')"
            alt="collapse"
            :class="{ 'rotate-90deg': status_choose_job_specify_btn }"
          >
        </b-button>

        <b-collapse
          id="collapse-1"
          v-model="status_choose_job_specify_btn"
          class="w-100"
        >
          <b-card>
            <div class="w-100">
              <div
                class="btn specify-btn"
                dusk="btn_specify_job"
                @click="handleOpenModalOccupation()"
              >
                <span>+</span>
                <span class="font-weight-bold">{{
                  $t('HR_REGISTER.SPECIFY')
                }}</span>
              </div>
            </div>

            <div
              v-for="item in modalDataSelected"
              :key="item.id"
              class="list-item-checked px-2 mt-3"
            >
              <span
                class="icon-checked"
                @click="handleRemoveSelected(item.id)"
              />
              <span>{{ item.name_ja + ` (${item.childOptions.length})` }}</span>
            </div>
          </b-card>
        </b-collapse>
      </div>

      <div class="item-search-vs-condition">
        <span
          :class="{
            'text-blue': formData.search,
          }"
          class="w-100 d-flex justify-start align-center font-weight-bold"
        >
          <label for="free-word">{{ $t('SEARCH_JOB_LIST.FREE_WORD') }}</label>
        </span>

        <div class="free-word-input">
          <b-form-input
            id="free-word"
            v-model="formData.search"
            dusk="input_search"
            class="cck-input"
            placeholder=""
            max-length="50"
            :formatter="format50characters"
            aria-invalid=""
          />
        </div>

        <div
          class="btn btn-search-vs-conditions btn_search--custom"
          dusk="btn_search_with_conditions"
          @click="handleSearch"
        >
          <span>{{ $t('SEARCH_JOB_LIST.SEARCH_WITH_CONDITIONS') }}</span>
          <img
            :src="require('@/assets/images/login/chervon-right-white.png')"
            alt="collapse"
          >
        </div>

        <div
          class="btn btn-clear-settings"
          dusk="btn_clear_settings"
          @click="handleClearAllHrFormSearch()"
        >
          <span>{{ $t('BUTTON.CLEAR_SETTINGS') }}</span>
        </div>
      </div>
    </div>

    <ModalMultipleSelect :options="modalOptions" />

    <ModalMultipleSelectCourse :options="modalOptionsCourse" />
  </div>
</template>

<script>
import {
  jaLevelOption,
  finalEductionClassification,
  finalEductionDegree,
  workFormOptions,
  workFormParttimeOptions,
  finalEductionMonth,
  genderOptions,
  renderAge,
  renderYearsEducationTiming,
} from '@/const/hrOrganization.js';
import EventBus from '@/utils/eventBus';
import ModalMultipleSelect from '../../../components/Modal/ModalMultipleSelect.vue';
import ModalMultipleSelectCourse from '../../../components/Modal/ModalMultipleSelectCourse.vue';
import { getOptionHrOrganization } from '@/api/modules/matching.js';
import { getListMainjob, getListEduCourse } from '@/api/job';
import { MakeToast } from '../../../utils/toastMessage';
import { renderOptionRequire } from '@/utils/renderOptionRequire';

export default {
  name: 'HrFormSearch',
  components: {
    ModalMultipleSelect,
    ModalMultipleSelectCourse,
  },
  data() {
    return {
      formData: {
        hr_org_id: '',
        gender: [],
        age_from: '',
        age_to: '',
        final_education_date_from_year: '',
        final_education_date_from_month: '',
        final_education_date_to_year: '',
        final_education_date_to_month: '',
        edu_class: [],
        edu_degree: [],
        final_education_course: [],
        work_forms: [],
        work_hour: [],
        japan_levels: [],
        main_job_career: [],
        search: '',
      },

      modalOptions: [],

      modalOptionsCourse: [],

      modalDataSelected: [],

      modalDataSelectedCourse: [],

      // selected1: [],
      // allSelected1: false,
      // indeterminate: false,
      // stateChooseJobAllSelected: false,

      // arr_item_occupation_child_selected: [],

      status_organization_name: false,
      status_gender_radio: false,
      status_age: false,
      status_final_education_date: false,
      status_final_educational_background_division_1: false,
      status_final_educational_background_degree: false,
      status_final_education_course_specify_btn: false,
      status_work_form: false,
      status_work_form_part_time: false,
      status_japan_levels: false,
      status_choose_job_specify_btn: false,

      // organizationOptions: [],
      organizationOptions: renderOptionRequire([]),

      genderOptions: genderOptions,

      ageOptions: renderAge(),

      finalEductionYear: renderYearsEducationTiming(),
      finalEductionMonth: finalEductionMonth,
      finalEductionClassification: finalEductionClassification,
      finalEductionDegree: finalEductionDegree,
      workFormOptions: workFormOptions,
      workFormParttimeOptions: workFormParttimeOptions,
      jaLevelOption: jaLevelOption,
    };
  },
  computed: {
    monthOptionList() {
      const result = [
        { value: null, text: '' },
        { value: '01', text: '1' },
        { value: '02', text: '2' },
        { value: '03', text: '3' },
        { value: '04', text: '4' },
        { value: '05', text: '5' },
        { value: '06', text: '6' },
        { value: '07', text: '7' },
        { value: '08', text: '8' },
        { value: '09', text: '9' },
        { value: '10', text: '10' },
        { value: '11', text: '11' },
        { value: '12', text: '21' },
      ];
      return result;
    },
    yearOptions() {
      const result = [{ value: null, text: '' }];
      const year_from = 1960;
      const year_to = 2050;
      for (let i = year_from; i <= year_to; i++) {
        result.push(i);
      }
      return result;
    },
  },
  watch: {
    // selected1(newValue, oldValue) {
    //   if (newValue.length === 0) {
    //     this.indeterminate = false;
    //     this.allSelected1 = false;
    //   } else if (newValue.length === this.flavours.length) {
    //     this.indeterminate = false;
    //     this.allSelected1 = true;
    //   } else {
    //     this.indeterminate = true;
    //     this.allSelected1 = false;
    //   }
    // },
  },
  created() {
    this.getOptionHrOrganization();

    this.getListMainjob();
    this.getListEduCourse();

    EventBus.$on('dataModalMultiple', (data) => {
      const listParentIds = Object.keys(data);
      const listSelected = [];

      listParentIds.length > 0 &&
        listParentIds.map((id) => {
          const parent = this.modalOptions.find((x) => x.id === Number(id));
          listSelected.push({
            ...parent,
            childOptions: data[id],
          });
        });

      this.modalDataSelected = listSelected;
    });

    EventBus.$on('dataModalMultipleCourse', (data) => {
      const listParentIds = Object.keys(data);

      const listSelected = [];

      listParentIds.length > 0 &&
        listParentIds.map((id) => {
          const parent = this.modalOptionsCourse.find(
            (x) => x.id === Number(id)
          );

          listSelected.push({
            ...parent,
            childOptions: data[id],
          });
        });

      this.modalDataSelectedCourse = listSelected;
    });

    this.handleSyncHrSearchData();
    EventBus.$on('resetSearchParamsNull', () => {
      this.handleSyncHrSearchData();
    });
  },
  methods: {
    handleSyncHrSearchData() {
      const syncData = this.$store.getters.searchParams;
      // console.log('syncData HrFormSearch===>', syncData);
      if (syncData) {
        this.modalDataSelectedCourse = syncData['middle_class'];
        if (syncData['middle_class'].length > 0) {
          this.status_final_education_course_specify_btn = true;
        } else {
          this.status_final_education_course_specify_btn = false;
        }

        this.modalDataSelected = syncData['main_job_ids'];
        if (syncData['main_job_ids'].length > 0) {
          this.status_choose_job_specify_btn = true;
        } else {
          this.status_choose_job_specify_btn = false;
        }

        this.formData.final_education_date_from_year = syncData['edu_date_from']
          ? syncData['edu_date_from'].split('-')[0]
          : '';
        this.formData.final_education_date_from_month = syncData[
          'edu_date_from'
        ]
          ? syncData['edu_date_from'].split('-')[1]
          : '';
        this.formData.final_education_date_to_year = syncData['edu_date_to']
          ? syncData['edu_date_to'].split('-')[0]
          : '';
        this.formData.final_education_date_to_month = syncData['edu_date_to']
          ? syncData['edu_date_to'].split('-')[1]
          : '';

        if (
          this.formData.final_education_date_from_year ||
          this.formData.final_education_date_from_month ||
          this.formData.final_education_date_to_year ||
          this.formData.final_education_date_to_month
        ) {
          this.status_final_education_date = true;
        }

        this.formData.hr_org_id = syncData['hr_org_id'];
        if (this.formData.hr_org_id) {
          this.status_organization_name = true;
        } else {
          this.status_organization_name = false;
        }

        this.formData.gender = syncData['gender'];
        if (this.formData.gender.length > 0) {
          this.status_gender_radio = true;
        } else {
          this.status_gender_radio = false;
        }

        this.formData.age_from = syncData['age_from'];
        this.formData.age_to = syncData['age_to'];
        if (this.formData.age_from && this.formData.age_to) {
          this.status_age = true;
        } else {
          this.status_age = false;
        }

        this.formData.edu_degree = syncData['edu_degree'];
        if (this.formData.edu_degree.length > 0) {
          this.status_final_educational_background_degree = true;
        } else {
          this.status_final_educational_background_degree = false;
        }

        this.formData.edu_class = syncData['edu_class'];
        if (this.formData.edu_class.length > 0) {
          this.status_final_educational_background_division_1 = true;
        } else {
          this.status_final_educational_background_division_1 = false;
        }

        this.formData.work_forms = syncData['work_forms'];
        if (this.formData.work_forms.length > 0) {
          this.status_work_form = true;
        } else {
          this.status_work_form = false;
        }

        this.formData.work_hour = syncData['work_hour'] ? [1] : [];
        if (this.formData.work_hour.length > 0) {
          this.status_work_form_part_time = true;
        } else {
          this.status_work_form_part_time = false;
        }

        this.formData.japan_levels = syncData['japan_levels'];
        if (this.formData.japan_levels.length > 0) {
          this.status_japan_levels = true;
        } else {
          this.status_japan_levels = false;
        }

        this.formData.search = syncData['search'];
        if (this.formData.search) {
          this.status_search = true;
        } else {
          this.status_search = false;
        }
      } else {
        Object.assign(this.formData, {
          hr_org_id: '',
          gender: [],
          age_from: '',
          age_to: '',
          final_education_date_from_year: '',
          final_education_date_from_month: '',
          final_education_date_to_year: '',
          final_education_date_to_month: '',
          edu_class: [],
          edu_degree: [],
          final_education_course: [],
          work_forms: [],
          work_hour: [],
          japan_levels: [],
          main_job_career: [],
          search: '',
        });

        this.status_organization_name = false;
        this.status_gender_radio = false;
        this.status_age = false;
        this.status_final_education_date = false;
        this.status_final_educational_background_division_1 = false;
        this.status_final_educational_background_degree = false;
        this.status_final_education_course_specify_btn = false;
        this.status_work_form = false;
        this.status_work_form_part_time = false;
        this.status_japan_levels = false;
        this.status_choose_job_specify_btn = false;
      }
    },

    async getOptionHrOrganization() {
      const param = {
        per_page: -1,
      };
      await getOptionHrOrganization(param).then((response) => {
        const { code, data } = response.data;
        if (code === 200) {
          // this.formData.hr_org_id = null;
          data.map((item) => {
            this.organizationOptions.push({
              key: item.id,
              value: item.corporate_name_en,
              // status: this.genderStatus(item.status),
            });
          });

          // this.$store.dispatch('app/saveListUSer', listUser);
          // this.pagination.total_records = response.data.total;
          // // response.data.pagination.total_records;
          // this.pagination.current_page = response.data.current_page;
          // this.pagination.isDisable = false;
          // listUser.forEach((element) => {
          //   element.roles.name = this.convertRoles(element.roles.name);
          // });
        }
      });
    },

    async getListMainjob() {
      try {
        const response = await getListMainjob();
        const { code, message } = response.data;
        if (code === 200) {
          const { data } = response.data;
          this.modalOptions = data.map((item) => {
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

    async getListEduCourse() {
      try {
        const response = await getListEduCourse();
        const { code, message } = response.data;
        if (code === 200) {
          const { data } = response.data;
          this.modalOptionsCourse = data.map((item) => {
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

    format50characters(e) {
      return String(e).substring(0, 50);
    },

    async handleSearch() {
      const is_getSearchStore = this.$store.getters.searchParams;
      if (is_getSearchStore) {
        const finalFormData = {
          ...this.formData,
          main_job_ids: this.modalDataSelected,
          middle_class: this.modalDataSelectedCourse,
        };
        await this.$store.dispatch(
          'hrSearchQuery/setSearchParams',
          finalFormData
        );
        this.$emit('handleSearch', finalFormData);
      } else {
        const formData = {
          ...this.formData,
          edu_date_from: this.handleMergeYearMonth(
            this.formData.final_education_date_from_year,
            this.formData.final_education_date_from_month
          ),
          // this.formData.final_education_date_from_year +
          // '-' +
          // this.formData.final_education_date_from_month,
          edu_date_to: this.handleMergeYearMonth(
            this.formData.final_education_date_to_year,
            this.formData.final_education_date_to_month
          ),
          // this.formData.final_education_date_to_year +
          // '-' +
          // this.formData.final_education_date_to_month,
        };
        if (formData.edu_date_from === '-') {
          formData.edu_date_from = null;
        }

        if (formData.edu_date_to === '-') {
          formData.edu_date_to = null;
        }

        delete formData.final_education_date_to_month;
        delete formData.final_education_date_to_year;
        delete formData.final_education_date_from_year;
        delete formData.final_education_date_from_month;
        delete formData.final_education_course;

        // const main_job_ids = this.modalDataSelected.flatMap(
        //   (item) => item.childOptions
        // );

        // const middle_class = this.modalDataSelectedCourse.flatMap(
        //   (item) => item.childOptions
        // );

        // console.log('main_job_ids--->', main_job_ids);
        // console.log('middle_class--->', middle_class);
        // const checkedWorkHour = 1; // check chọn 週28時間以下

        const finalFormData = {
          ...formData,
          main_job_ids: this.modalDataSelected,
          middle_class: this.modalDataSelectedCourse,
          work_hour: formData.work_hour.length === 0 ? '' : true,
        };

        // console.log('finalFormData===>', finalFormData);
        await this.$store.dispatch(
          'hrSearchQuery/setSearchParams',
          finalFormData
        );
        this.$emit('handleSearch', finalFormData);
      }
    },

    handleMergeYearMonth(year, month) {
      if (year && month) {
        return year + '-' + month;
      }
    },

    handleClearAllHrFormSearch() {
      Object.assign(this.formData, {
        hr_org_id: '',
        gender: [],
        age_from: '',
        age_to: '',
        final_education_date_from_year: '',
        final_education_date_from_month: '',
        final_education_date_to_year: '',
        final_education_date_to_month: '',
        edu_class: [],
        edu_degree: [],
        final_education_course: [],
        work_forms: [],
        work_hour: [],
        japan_levels: [],
        main_job_career: [],
        search: '',
      });

      this.modalDataSelected = [];
      this.modalDataSelectedCourse = [];

      EventBus.$emit('handleClearSettingsModal');
      EventBus.$emit('handleClearSettingsModalCourse');
    },

    handleOpenModalFinalEduCourse() {
      EventBus.$emit('showModalSelectCourse', true);
    },

    handleOpenModalOccupation() {
      EventBus.$emit('showModalSelect', true);
    },

    handleRemoveSelected(id) {
      this.modalDataSelected = this.modalDataSelected.filter(
        (item) => item.id !== Number(id)
      );

      EventBus.$emit('removeSelected', Number(id));
    },

    handleRemoveSelectedCourse(id) {
      this.modalDataSelectedCourse = this.modalDataSelectedCourse.filter(
        (item) => item.id !== Number(id)
      );

      EventBus.$emit('removeSelectedCourse', Number(id));
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/common/common.scss';

.search-with-conditions {
  width: 100%;
  border-radius: 2px;
  background: #ffffff;
  border: 1px solid #c6c6c6;
}

.search-with-conditions-wrap {
  width: 100%;
  height: 100%;
}

.item-search-vs-condition {
  padding: 1rem;
  display: flex;
  align-items: center;
  flex-direction: column;
  justify-content: center;
  border-top: 0.5px solid #c6c6c6;

  &:first-child {
    border: none;
  }

  &:nth-child(2) {
    width: 100%;
  }

  & button {
    width: 100%;
    display: flex;
    color: #333333;
    font-size: 14px;
    font-weight: 700;
    line-height: 21px;
    font-style: normal;
    align-items: center;
    padding: 0.5rem 0rem;
    background: none !important;
    justify-content: space-between;
  }
}

::v-deep .btn-secondary {
  padding: 0;
  border: none !important;

  &:focus {
    border: none !important;
    box-shadow: none !important;
  }
}

::v-deep .card-body {
  display: flex;
  padding: 0 !important;
  flex-direction: column;
  border: none !important;
}

.checkbox-layout {
  margin: 0;
  width: 100%;
  display: flex;
  font-size: 14px;
  font-weight: 400;
  line-height: 21px;
  flex-direction: column;
  align-items: flex-start;
  justify-content: flex-start;
}

.card {
  border: none;
}

::v-deep .custom-control-input:checked ~ .custom-control-label::before {
  color: #fff;
  border-radius: 2px;
  background: #2b3bcb;
}

.occupation-head {
  width: 100%;
  // padding: 0 0.5rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 1rem;
  width: 100%;
  display: flex;
  padding: 0 0.5rem;
  align-items: center;
  justify-content: space-between;

  & span:first-child {
    color: #333333;
    font-size: 14px;
    font-weight: 700;
    line-height: 21px;
  }
}

.select-job-btn {
  border-radius: 5px;
  background: #ffffff;
  padding: 4px 0.5rem;
  border: 1px solid #919191;

  & span {
    width: 16px;
    height: 16px;
    color: #858585;
    font-size: 10px;
    font-weight: 700;
    line-height: 12px;
  }
}
.select-two-value {
  width: 100%;
  gap: 0.75rem;
  display: flex;
  border-radius: 3px;
  background: #ffffff;
  flex-direction: column;

  & > div {
    gap: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: flex-start;

    & select {
      width: 76%;
      height: 43px;
    }
  }
}

.free-word-input {
  width: 100%;
  display: flex;
  position: relative;
  align-items: center;
  justify-content: flex-start;

  & input {
    border: 1px solid green;
    // padding-left: 2.5rem !important;
  }

  & svg,
  img {
    top: 40%;
    left: 12px;
    width: 18px;
    position: absolute;
  }
}

.btn-search-vs-conditions {
  position: relative;
  background: #5eb236;
  border-radius: 10px;
  margin-top: 1.75rem;
  padding: 0.5rem 2.5rem;

  & span {
    color: #ffffff;
    font-size: 14px;
    font-weight: 700;
    line-height: 17px;
  }

  & svg {
    top: 40%;
    right: 1rem;
    position: absolute;
  }

  & img {
    top: 38%;
    right: 6%;
    position: absolute;
  }
}

.btn-clear-settings {
  margin-top: 0.25rem;

  & span {
    color: #969696;
    font-size: 12px;
    font-weight: 400;
    line-height: 18px;
    padding-bottom: 0px;
    border-bottom: 1px solid #969696;
  }
}

::v-deep .modal-content {
  min-width: 782px;
  min-height: 512px;
  padding: 0 !important;
  transform: translate(-18%, 25%) !important;
}

::v-deep .modal-body {
  align-items: stretch;
}

.content-modal {
  width: 100%;
  height: 100%;
  gap: 1.25rem;
  display: flex;
  align-items: stretch;
  flex-direction: column;
  justify-content: center;
}

.content-modal-options {
  width: 100%;
  gap: 1.5rem;
  display: flex;
  align-items: stretch;
  justify-content: center;
}

.collapse-parents {
  width: 40%;
  height: 316px;
  overflow-y: auto;
  border-top: 1px solid #c6c6c6;
  border-bottom: 1px solid #c6c6c6;
  border-radius: 5px 5px 0 0 !important;
}

.collapse-item {
  display: flex;
  border-top: none;
  padding: 1.076rem;
  position: relative;
  background: #ffffff;
  align-items: center;
  border: 1px solid #c6c6c6;
  border-radius: 0 !important;
  justify-content: flex-start;

  &:first-child {
    border-top: none;
    border-radius: 5px 5px 0 0 !important;
  }

  & span {
    color: #333333;
    font-size: 14px;
    font-weight: 700;
    line-height: 17px;
  }

  & img:last-child {
    top: 1rem;
    right: 1rem;
    position: absolute;
    border-bottom: none;
    border-radius: 0 0 5px 5px !important;
  }
}

.checked {
  top: 1.1rem;
  height: 17px;
  right: 2.5rem;
  position: absolute;
}

.collapse-item--active {
  color: #333333;
  background: #304cad;

  & span {
    color: #ffffff;
  }
}
.content-collapse-options {
  width: 60%;
  height: 316px;
  border-radius: 5px;
  background: #ffffff;
  padding: 0.25rem 0.5rem;
  border: 1px solid #c6c6c6;
}
::v-deep .content-collapse-options {
  .form-group {
    gap: 1.3rem;
    display: flex;
    margin-bottom: 0;
    align-items: center;
    justify-content: flex-start;

    & legend {
      height: 34px;
      gap: 0.25rem;
      display: flex;
      background: #f1f1f1;
      align-items: center;
      justify-content: flex-start;
      padding: 0 0.75rem !important;
      box-shadow: 0px 2px 4px rgba(61, 61, 61, 0.25);
    }
  }
}

.head-collapse-parents-title {
  background: #f1f1f1;
  padding: 0.5rem 0.75rem;
  box-shadow: 0px 2px 4px rgba(61, 61, 61, 0.25);

  & span {
    color: #072470;
    font-size: 14px;
    font-weight: 700;
    line-height: 17px;
  }
}

.list-check-box {
  height: 100%;
  padding: 1.25rem 0 0 2.25rem;

  & > div {
    height: 85%;
    overflow-y: auto;
  }
}

.btn-reflect-the-content {
  border-radius: 5px;
  position: relative;
  background: #304cad;
  border: 1px solid #c6c6c6;
  box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.25);

  & span {
    color: #ffffff;
    font-size: 14px;
    font-weight: 700;
    line-height: 17px;
  }

  & svg {
    top: 40%;
    right: 1rem;
    position: absolute;
  }

  & img {
    top: 38%;
    right: 6%;
    position: absolute;
  }
}

.select-job-btns {
  width: 100%;
  display: flex;
  align-items: center;
  flex-direction: column;
  justify-content: center;
}

.specify-btn {
  height: 34px;
  gap: 0.25rem;
  display: flex;
  padding: 0 1rem;
  border-radius: 5px;
  width: fit-content;
  background: #ffffff;
  align-items: center;
  justify-content: center;
  border: 1px solid #304cad;
  box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);

  & span {
    color: #304cad;
    font-size: 16px;
    font-weight: 400;
    line-height: 24px;
  }

  & span:nth-child(1) {
    line-height: 0 !important;
    font-size: 24px !important;
    transform: translateX(4px);
  }
}

.d-none {
  visibility: hidden;
}

.icon-checked {
  width: 16px;
  height: 16px;
  cursor: pointer;
  margin-right: 5px;
  display: inline-block;
  border-radius: 0.25rem;
  background-color: #007bff;
  background-position: center;
  background-repeat: no-repeat;
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath fill='%23fff' d='M6.564.75l-3.59 3.612-1.538-1.55L0 4.26l2.974 2.99L8 2.193z'/%3e%3c/svg%3e");
}

.text-blue {
  color: blue;
}
</style>
