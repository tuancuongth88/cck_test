<template>
  <div class="display-user-management-list">
    <div class="border-left-title font-weight-bold">
      <span class="title">{{ '人材検索' }}</span>
    </div>

    <div class="w-100 mb-2 outside-frame">
      <div class="inside-frame d-flex flex-column">
        <div class="d-flex flex-row">
          <div class="d-flex left-col first-left-col">
            <span>団体名</span>
          </div>

          <div class="d-flex right-col first-right-col">
            <b-form-select
              v-model="organization_name"
              :options="organizationList"
              :disabled-field="'disabled'"
            />
          </div>
        </div>

        <div class="d-flex flex-row">
          <div class="d-flex left-col">
            <span>性別</span>
          </div>

          <div class="d-flex right-col">
            <div class="d-flex flex-row" style="gap: 30px;">
              <b-form-checkbox v-model="male" name="gender-male" size="lg" @change="handleInputCheckboxGender(1)">
                <span class="checkbox-label">男性</span>
              </b-form-checkbox>

              <b-form-checkbox v-model="female" name="gender-female" size="lg" @change="handleInputCheckboxGender(2)">
                <span class="checkbox-label">女性</span>
              </b-form-checkbox>
            </div>
          </div>
        </div>

        <div class="d-flex flex-row">
          <div class="d-flex left-col">
            <span>年齢</span>
          </div>

          <div class="d-flex right-col">
            <div class="d-flex flex-row">
              <b-form-select
                v-model="age_from"
                :options="yearList"
                :disabled-field="'disabled'"
              />
              <span class="date-description">歳</span>

              <span class="date-description"> 〜 </span>

              <b-form-select
                v-model="age_to"
                :options="yearList"
                :disabled-field="'disabled'"
              />
              <span class="date-description">歳</span>
            </div>
          </div>
        </div>

        <div class="d-flex flex-row">
          <div class="d-flex left-col">
            <span>最終学歴（年月）</span>
          </div>

          <div class="d-flex right-col">
            <div class="d-flex flex-row">
              <b-form-select
                v-model="final_education_date_from_year"
                :options="yearList"
                :disabled-field="'disabled'"
              />
              <span class="date-description">年</span>

              <b-form-select
                v-model="final_education_date_from_month"
                :options="monthList"
                :disabled-field="'disabled'"
              />
              <span class="date-description">月</span>

              <span class="date-description">〜</span>

              <b-form-select
                v-model="final_education_date_to_year"
                :options="yearList"
                :disabled-field="'disabled'"
              />
              <span class="date-description">年</span>

              <b-form-select
                v-model="final_education_date_to_month"
                :options="monthList"
                :disabled-field="'disabled'"
              />
              <span class="date-description">月</span>
            </div>
          </div>
        </div>

        <div class="d-flex flex-row">
          <div class="d-flex left-col">
            <span>最終学歴（区分）</span>
          </div>

          <div class="d-flex right-col">
            <div class="d-flex flex-row" style="gap: 30px;">
              <b-form-checkbox v-model="graduation" name="graduation" size="lg" @change="handleInputCheckboxClassification(1)">
                <span class="checkbox-label">卒業</span>
              </b-form-checkbox>

              <b-form-checkbox v-model="finish" name="finish" size="lg" @change="handleInputCheckboxClassification(2)">
                <span class="checkbox-label">中退</span>
              </b-form-checkbox>

              <b-form-checkbox v-model="dropout" name="dropout" size="lg" @change="handleInputCheckboxClassification(3)">
                <span class="checkbox-label">卒業見込み</span>
              </b-form-checkbox>
            </div>
          </div>
        </div>

        <div class="d-flex flex-row">
          <div class="d-flex left-col">
            <span>最終学歴（学位）</span>
          </div>

          <div class="d-flex right-col">
            <div class="d-flex flex-row" style="gap: 30px;">
              <b-form-checkbox v-model="bachelor_master" name="degree-bachelor-master" size="lg" @change="handleInputCheckboxDegree(1)">
                <span class="checkbox-label">大学卒業以上</span>
              </b-form-checkbox>

              <b-form-checkbox v-model="human_resource" name="degree-human-resource" size="lg" @change="handleInputCheckboxDegree(2)">
                <span class="checkbox-label">大学卒業以外</span>
              </b-form-checkbox>
            </div>
          </div>
        </div>

        <div class="d-flex flex-row">
          <div class="d-flex left-col">
            <span>最終学歴（選考学科）</span>
          </div>

          <div class="d-flex right-col">
            <div class="d-flex flex-row">
              <b-button
                class="specify-button course-specify"
                @click="handleOpenModalSpecifyCourse()"
              >
                <i class="fas fa-plus" />
                <span>指定する</span>
              </b-button>
            </div>
          </div>
        </div>

        <div class="d-flex flex-row">
          <div class="d-flex left-col">
            <span>勤務形態</span>
          </div>

          <div class="d-flex right-col">
            <div class="d-flex flex-row" style="gap: 30px;">
              <b-form-checkbox v-model="work_from_1" name="work-from-1" size="lg" @change="handleInputCheckboxWorkForm(1)">
                <span class="checkbox-label">正社員</span>
              </b-form-checkbox>

              <b-form-checkbox v-model="work_from_2" name="work-from-2" size="lg" @change="handleInputCheckboxWorkForm(2)">
                <span class="checkbox-label">契約社員</span>
              </b-form-checkbox>

              <b-form-checkbox v-model="work_from_3" name="work-from-3" size="lg" @change="handleInputCheckboxWorkForm(3)">
                <span class="checkbox-label">派遣社員</span>
              </b-form-checkbox>

              <b-form-checkbox v-model="work_from_4" name="work-from-4" size="lg" @change="handleInputCheckboxWorkForm(4)">
                <span class="checkbox-label">その他</span>
              </b-form-checkbox>
            </div>
          </div>
        </div>

        <div class="d-flex flex-row">
          <div class="d-flex left-col">
            <span>勤務形態（非常勤）</span>
          </div>

          <div class="d-flex right-col">
            <div class="d-flex flex-row">
              <b-form-checkbox v-model="part_time" name="part-time" size="lg">
                <span class="checkbox-label">週28時間以下</span>
              </b-form-checkbox>
            </div>
          </div>
        </div>

        <div class="d-flex flex-row">
          <div class="d-flex left-col">
            <span>日本語レベル</span>
          </div>

          <div class="d-flex right-col">
            <div class="d-flex flex-row" style="gap: 30px;">
              <b-form-checkbox v-model="japanese_level_1" name="japanese-level-1" size="lg" @change="handleInputCheckboxJapaneseLevel(1)">
                <span class="checkbox-label">N1</span>
              </b-form-checkbox>

              <b-form-checkbox v-model="japanese_level_2" name="japanese-level-2" size="lg" @change="handleInputCheckboxJapaneseLevel(2)">
                <span class="checkbox-label">N2</span>
              </b-form-checkbox>

              <b-form-checkbox v-model="japanese_level_3" name="japanese-level-3" size="lg" @change="handleInputCheckboxJapaneseLevel(3)">
                <span class="checkbox-label">N3</span>
              </b-form-checkbox>

              <b-form-checkbox v-model="japanese_level_4" name="japanese-level-4" size="lg" @change="handleInputCheckboxJapaneseLevel(4)">
                <span class="checkbox-label">N4</span>
              </b-form-checkbox>

              <b-form-checkbox v-model="japanese_level_5" name="japanese-level-5" size="lg" @change="handleInputCheckboxJapaneseLevel(5)">
                <span class="checkbox-label">N5</span>
              </b-form-checkbox>

              <b-form-checkbox v-model="japanese_level_6" name="japanese-level-other" size="lg" @change="handleInputCheckboxJapaneseLevel(6)">
                <span class="checkbox-label">なし</span>
              </b-form-checkbox>
            </div>
          </div>
        </div>

        <div class="d-flex flex-row">
          <div class="d-flex left-col">
            <span>主な職務経歴</span>
          </div>

          <div class="d-flex right-col">
            <div class="d-flex flex-row">
              <b-button
                class="specify-button job-experience-specify"
                @click="handleOpenModalJobExperience()"
              >
                <i class="fas fa-plus" />
                <span>指定する</span>
              </b-button>
            </div>
          </div>
        </div>

        <div class="d-flex flex-row">
          <div class="d-flex left-col last-left-col">
            <span>キーワード</span>
          </div>

          <div class="d-flex right-col last-right-col">
            <div class="w-100 d-flex flex-row">
              <b-form-input v-model="key_word" type="text" />
            </div>
          </div>
        </div>
      </div>

      <div class="w-100 d-flex flex-column align-items-center footer">
        <b-button class="search-button" @click="handleProcessSearchEngine()">
          <span>この条件で検索する</span>
          <i class="fas fa-chevron-right icon" />
        </b-button>

        <span
          class="clear-setting-button"
          @click="handleClearAllSetting()"
        >設定をクリア</span>
      </div>

      <ModalMultipleSelectCourse
        :options="modalCourseOptions"
        :show-modal="isShowModalSepecifyCourse"
      />

      <ModalMultipleSelectJobExp
        :options="modalJobExpOptions"
        :show-modal="isShowModalSepecifyJobExp"
      />
    </div>
  </div>
</template>

<script>
import { renderYears, renderMonth } from '@/pages/Hr/common.js';

// import { getHr } from '@/api/modules/hr';
import { getListCompany } from '@/api/modules/company';

import ModalMultipleSelectJobExp from '@/components/Modal/ModalMultipleSelect.vue';
import ModalMultipleSelectCourse from '@/components/Modal/ModalMultipleSelectCourse.vue';

const urlAPI = {
  urlGettHr: '/hr',
  urlGetListCompany: '/company',
};

export default {
  name: 'JobSearch',
  components: {
    ModalMultipleSelectCourse,
    ModalMultipleSelectJobExp,
  },
  data() {
    return {
      organization_name: null,
      organizationList: [
        { value: null, text: '選択してください', disabled: true },
      ],

      male: '',
      female: '',

      age_from: null,
      age_to: null,

      final_education_date_from_year: null,
      final_education_date_from_month: null,

      final_education_date_to_year: null,
      final_education_date_to_month: null,

      yearList: [{ value: null, text: '選択してください', disabled: true }],

      monthList: [{ value: null, text: '選択してください', disabled: true }],

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

      modalCourseOptions: [
        {
          id: 1,
          name_ja: '経営学等',
          name_en: 'Business administration',
          childOptions: [
            {
              id: 1,
              name_ja: '会計学',
              name_en: 'Accounting',
            },
            {
              id: 2,
              name_ja: '経営学',
              name_en: 'Business Administration',
            },
            {
              id: 3,
              name_ja: 'マーケティング',
              name_en: 'Marketing',
            },
            {
              id: 4,
              name_ja: 'その他',
              name_en: 'Others',
            },
          ],
        },
        {
          id: 2,
          name_ja: '人文化学',
          name_en: 'Humanities',
          childOptions: [
            {
              id: 1,
              name_ja: '芸術',
              name_en: 'Art',
            },
            {
              id: 2,
              name_ja: '歴史学',
              name_en: 'History',
            },
            {
              id: 3,
              name_ja: '文学',
              name_en: 'Literature',
            },
            {
              id: 4,
              name_ja: 'その他',
              name_en: 'Others',
            },
          ],
        },
        {
          id: 3,
          name_ja: '言語学',
          name_en: 'Linguistics',
          childOptions: [
            {
              id: 1,
              name_ja: '日本語',
              name_en: 'Japanese',
            },
            {
              id: 2,
              name_ja: '英語',
              name_en: 'English',
            },
            {
              id: 3,
              name_ja: 'その他',
              name_en: 'Others',
            },
          ],
        },
        {
          id: 4,
          name_ja: '自然科学',
          name_en: 'Natural sciences',
          childOptions: [
            {
              id: 1,
              name_ja: '建築学',
              name_en: 'Architecture',
            },
            {
              id: 2,
              name_ja: 'コンピュータサイエンス',
              name_en: 'Computer Science',
            },
            {
              id: 3,
              name_ja: '工学',
              name_en: 'Engineering',
            },
            {
              id: 4,
              name_ja: '統計学',
              name_en: 'Statistics',
            },
            {
              id: 5,
              name_ja: 'その他',
              name_en: 'Others',
            },
          ],
        },
        {
          id: 5,
          name_ja: '社会科学 ',
          name_en: 'Social science',
          childOptions: [
            {
              id: 1,
              name_ja: '経済学',
              name_en: 'Economics',
            },
            {
              id: 2,
              name_ja: '教育学',
              name_en: ' Pedagogy',
            },
            {
              id: 3,
              name_ja: '行政学 ',
              name_en: 'Administration',
            },
            {
              id: 4,
              name_ja: '国際関係学',
              name_en: ' International Relations',
            },
            {
              id: 5,
              name_ja: '法律学 ',
              name_en: 'Legal Studies',
            },
            {
              id: 6,
              name_ja: '政治学',
              name_en: ' Political Science',
            },
            {
              id: 7,
              name_ja: 'その他',
              name_en: 'Others',
            },
          ],
        },
      ],

      modalJobExpOptions: [
        {
          id: 1,
          name_ja: '農業・林業・漁業',
          name_en: 'Agriculture/Forestry/Fisheries',
          childOptions: [
            {
              id: 1,
              name_ja: '住宅手当有',
              name_en: ' Housing allowance',
            },
            {
              id: 2,
              name_ja: '福利厚生充実 2',
              name_en: 'Welfare enhancement',
            },
            {
              id: 3,
              name_ja: '福利厚生充実 3',
              name_en: 'Welfare enhancement',
            },
            {
              id: 4,
              name_ja: '福利厚生充実 4',
              name_en: 'Welfare enhancement',
            },
          ],
        },
        {
          id: 2,
          name_ja: '建設業',
          name_en: 'Construction industry',
          childOptions: [
            {
              id: 1,
              name_ja: '建設業 1',
              name_en: ' Housing allowance',
            },
            {
              id: 2,
              name_ja: '建設業 2',
              name_en: 'Welfare enhancement',
            },
            {
              id: 3,
              name_ja: '建設業 3',
              name_en: 'Welfare enhancement',
            },
            {
              id: 4,
              name_ja: '建設業 4',
              name_en: 'Welfare enhancement',
            },
            {
              id: 5,
              name_ja: '建設業 5',
              name_en: 'Welfare enhancement',
            },
          ],
        },
        {
          id: 3,
          name_ja: '製造業',
          name_en: 'manufacturing industry',
          childOptions: [
            {
              id: 1,
              name_ja: '製造業 1',
              name_en: ' manufacturing industry',
            },
            {
              id: 2,
              name_ja: '製造業 2',
              name_en: ' manufacturing industry',
            },
            {
              id: 3,
              name_ja: '製造業 3',
              name_en: ' manufacturing industry',
            },
            {
              id: 4,
              name_ja: '製造業 4',
              name_en: ' manufacturing industry',
            },
          ],
        },
        {
          id: 4,
          name_ja: '電気・ガス・水道業',
          name_en: 'Electricity, gas, water industry',
          childOptions: [
            {
              id: 1,
              name_ja: '住宅手当有 1',
              name_en: ' Housing allowance',
            },
            {
              id: 2,
              name_ja: '住宅手当有 2',
              name_en: ' Housing allowance',
            },
            {
              id: 3,
              name_ja: '住宅手当有 3',
              name_en: ' Housing allowance',
            },
            {
              id: 4,
              name_ja: '住宅手当有 4',
              name_en: ' Housing allowance',
            },
          ],
        },
        {
          id: 5,
          name_ja: '情報通信業',
          name_en: 'Information and communication industry',
          childOptions: [
            {
              id: 1,
              name_ja: '情報通信業 1',
              name_en: ' Housing allowance',
            },
            {
              id: 2,
              name_ja: '情報通信業 2',
              name_en: ' Housing allowance',
            },
            {
              id: 3,
              name_ja: '情報通信業 3',
              name_en: ' Housing allowance',
            },
            {
              id: 4,
              name_ja: '情報通信業 4',
              name_en: ' Housing allowance',
            },
          ],
        },
        {
          id: 6,
          name_ja: '運輸業・郵便業',
          name_en: 'Transportation and postal services',
          childOptions: [
            {
              id: 1,
              name_ja: '運輸業・郵便業 1',
              name_en: ' Housing allowance',
            },
            {
              id: 2,
              name_ja: '運輸業・郵便業 2',
              name_en: 'Welfare enhancement',
            },
          ],
        },
        {
          id: 7,
          name_ja: '卸売業・小売業',
          name_en: 'Wholesale/Retail',
          childOptions: [
            {
              key: 1,
              name_ja: '卸売業・小売業 1',
              name_en: ' Housing allowance',
            },
            {
              key: 2,
              name_ja: '卸売業・小売業 2',
              name_en: 'Welfare enhancement',
            },
          ],
        },
      ],

      isShowModalSepecifyCourse: false,

      isShowModalSepecifyJobExp: false,
    };
  },
  created() {
    this.$bus.on('modalSpecifyCourseShowStatusChanged', (status) => {
      this.isShowModalSepecifyCourse = status;
    });

    this.$bus.on('modalSpecifyJobExpShowStatusChanged', (status) => {
      this.isShowModalSepecifyJobExp = status;
    });

    this.$bus.on('dataModalMultipleCourse', (data) => {
      this.selected_courses = data;
    });

    this.$bus.on('dataModalMultiple', (data) => {
      this.selected_job_experiences = data;
    });

    this.handleInitalComponentData();
  },
  destroyed() {
    this.$bus.off('modalSpecifyCourseShowStatusChanged');
    this.$bus.off('modalSpecifyJobExpShowStatusChanged');
    this.$bus.off('dataModalMultipleCourse');
    this.$bus.off('dataModalMultiple');
  },
  methods: {
    async handleInitalComponentData() {
      await this.handleGetListCompany();
      await this.handleRenderSelectOptions();
    },
    async handleGetListCompany() {
      const URL = `${urlAPI.urlGetListCompany}`;

      try {
        const response = await getListCompany(URL);

        if (response['code'] === 200) {
          const RAW_DATA = response['data']['result'];

          RAW_DATA.forEach((item) => {
            this.organizationList.push(
              { value: item['id'], text: `${item['company_name']} (${item['company_name_jp']})`, disabled: false }
            );
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

      const LIST_MONTH = renderMonth();

      LIST_MONTH.forEach((month) => {
        this.monthList.push({
          value: month,
          text: `${month}`,
          disabled: false,
        });
      });
    },
    handleOpenModalSpecifyCourse() {
      this.isShowModalSepecifyCourse = true;
    },
    handleOpenModalJobExperience() {
      this.isShowModalSepecifyJobExp = true;
    },
    async handleProcessSearchEngine() {
      const DATA = {
        organization_name: this.organization_name,
        gender: this.handleReturnCheckboxInput([this.male, this.female]),
        age_from: this.age_from,
        age_to: this.age_to,
        edu_date_from: this.handleMergeYearMonth(this.final_education_date_from_year, this.final_education_date_from_month),
        edu_date_to: this.handleMergeYearMonth(this.final_education_date_to_year, this.final_education_date_to_month),
        edu_class: this.handleReturnCheckboxInput([this.graduation, this.finish, this.dropout]),
        edu_degree: this.handleReturnCheckboxInput([this.bachelor_master, this.human_resource]),
        selected_courses: this.selected_courses,
        work_forms: this.handleReturnCheckboxInput([this.work_from_1, this.work_from_2, this.work_from_3, this.work_from_4]),
        work_hour: this.part_time,
        japan_levels: this.handleReturnCheckboxInput([this.japanese_level_1, this.japanese_level_2, this.japanese_level_3, this.japanese_level_4, this.japanese_level_5, this.japanese_level_6]),
        selected_job_experiences: this.selected_job_experiences,
        search: this.key_word,
      };

      await this.$store.dispatch('hrSearchQuery/setSearchParams', DATA).then(() => {
        this.$router.push({ path: '/hr/list' }, (onAbort) => {});
      });
    },
    handleClearAllSetting() {
      this.organization_name = null;

      this.male = '';
      this.female = '';

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
    handleInputCheckboxGender(type) {
      switch (type) {
        case 1:
          this.male = true;
          this.female = '';
          break;
        case 2:
          this.male = '';
          this.female = true;
          break;
        default:
          break;
      }
    },
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
    },
    handleFormatMonthDate(string) {
      let result;

      if (string) {
        if (parseInt(string) < 10) {
          result = `0${string}`;
        } else {
          result = string;
        }
      }

      return result;
    },
  },
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style lang="scss" scoped>
@import '@/scss/_variables.scss';
@import '@/scss/modules/HrSearch/index.scss';
</style>
