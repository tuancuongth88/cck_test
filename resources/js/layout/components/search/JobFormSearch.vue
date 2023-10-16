<template>
  <div class="search-with-conditions">
    <div class="search-with-conditions-wrap">
      <!-- 0 企業 Company -->
      <div v-if="hasCompany" class="item-search-vs-condition">
        <b-button
          class="px-0"
          dusk="btn_company_name"
          :class="status_company ? null : 'collapsed  btn border-none'"
          :aria-expanded="status_company"
          aria-controls="collapse-2"
          @click="status_company = !status_company"
        >
          <span :class="[formData.company_id ? 'text-blue' : '']">{{
            $t('COMPANY_LABEL')
          }}</span>
          <img
            :src="require('@/assets/images/login/chervon-right-light.png')"
            alt="collapse"
            :class="{ 'rotate-90deg': status_company }"
          >
        </b-button>

        <b-collapse id="collapse-2" v-model="status_company" class="w-100">
          <b-card>
            <b-form-select
              v-model="formData.company_id"
              dusk="company_id"
              :options="companySelectOption"
              value-field="key"
              text-field="value"
            />
          </b-card>
        </b-collapse>
      </div>

      <!-- 1 職種 - Occupation -->
      <div class="item-search-vs-condition bt-0">
        <div class="occupation-head mb-2">
          <span :class="{ 'text-blue': modalDataSelected.length > 0 }">
            {{ $t('SEARCH_JOB_LIST.OCCUPATION') }}
          </span>

          <div
            class="btn select-job-btn"
            dusk="select_job_btn"
            @click="handleOpenModalOccupation()"
          >
            <span>{{ $t('SEARCH_JOB_LIST.SELECT_OCCUPATION') }}</span>
            <img
              :src="require('@/assets/images/login/open-extend-modal.png')"
              alt="collapse"
            >
          </div>
        </div>

        <div
          v-for="item in modalDataSelected"
          :key="item.id"
          class="list-item-checked px-2"
        >
          <span class="icon-checked" @click="handleRemoveSelected(item.id)" />
          <span>{{ item.name_ja + ` (${item.childOptions.length})` }}</span>
        </div>
      </div>

      <!-- 年収/income  -->
      <div class="item-search-vs-condition">
        <b-button
          class="px-0"
          dusk="btn_income"
          :class="
            status_income
              ? null
              : 'collapsed  btn border-none btn_search--custom'
          "
          :aria-expanded="status_income"
          aria-controls="collapse-1"
          @click="status_income = !status_income"
        >
          <span
            :class="[
              formData.income_from || formData.income_to ? 'text-blue' : '',
            ]"
          >{{ $t('SEARCH_JOB_LIST.INCOME') }}</span>
          <img
            :src="require('@/assets/images/login/chervon-right-light.png')"
            alt="collapse"
            :class="{ 'rotate-90deg': status_income }"
          >
        </b-button>

        <b-collapse id="collapse-1" v-model="status_income" class="w-100">
          <b-card>
            <div class="select-two-value">
              <div>
                <b-form-select
                  v-model="formData.income_from"
                  dusk="income_from"
                  :options="incomeOption"
                  value-field="key"
                  text-field="value"
                />
                <span style="text-wrap: nowrap">{{
                  $t('JOB_SEARCH.MORE_THAN_TEN_THOUSAND_YEN')
                }}</span>
              </div>

              <div>
                <b-form-select
                  v-model="formData.income_to"
                  dusk="income_to"
                  :options="incomeOption"
                  value-field="key"
                  text-field="value"
                />
                <span style="text-wrap: nowrap">{{
                  $t('JOB_SEARCH.MORE_THAN_TEN_THOUSAND_YEN')
                }}</span>
              </div>
            </div>
          </b-card>
        </b-collapse>
      </div>

      <!-- 勤務地（都道府県） Working Palace (Tỉnh, TP) -->
      <div id="dusk_city_id" class="item-search-vs-condition">
        <b-button
          class="px-0"
          dusk="btn_working_place"
          aria-controls="collapse-2"
          :aria-expanded="status_working_place"
          :class="status_working_place ? null : 'collapsed  btn border-none'"
          @click="status_working_place = !status_working_place"
        >
          <span :class="[city_id.length > 0 ? 'text-blue' : '']">{{
            $t('SEARCH_JOB_LIST.WORKING_PLACE')
          }}</span>
          <img
            :src="require('@/assets/images/login/chervon-right-light.png')"
            alt="collapse"
            :class="{ 'rotate-90deg': status_working_place }"
          >
        </b-button>

        <b-collapse
          id="collapse-2"
          v-model="status_working_place"
          class="w-100"
        >
          <b-card>
            <multiselect
              v-model="city_id"
              label="name_ja"
              :multiple="true"
              track-by="name_ja"
              :options="cityList"
              placeholder="都道府県を選ぶ"
              :group-values="'options'"
              :group-label="'timezone'"
              :selected-label="'選択済み'"
              :select-label="''"
              :deselect-label="''"
            >
              <template slot="option" slot-scope="props">
                <span v-if="props.option.$isLabel" class="group-label">{{
                  props.option.$groupLabel
                }}</span>
                <span v-else class="option-label">{{
                  props.option.name_ja
                }}</span>
              </template>

              <template slot="noResult">
                <span>「要素が見つかりません。検索クエリを変更してください。」となります。</span>
              </template>
            </multiselect>
          </b-card>
        </b-collapse>
      </div>

      <!-- 言語条件 Language requirement -->
      <div id="dusk_japan_levels" class="item-search-vs-condition">
        <b-button
          class="px-0"
          :class="
            status_language_requirement ? null : 'collapsed  btn border-none'
          "
          dusk="btn_japan_levels"
          :aria-expanded="status_language_requirement"
          aria-controls="collapse-2"
          @click="status_language_requirement = !status_language_requirement"
        >
          <span
            :class="[
              formData.language_requirements.length > 0 ? 'text-blue' : '',
            ]"
          >
            {{ $t('SEARCH_JOB_LIST.LANGUAGE_REQUIREMENT') }}
          </span>

          <img
            :src="require('@/assets/images/login/chervon-right-light.png')"
            alt="collapse"
            :class="{ 'rotate-90deg': status_language_requirement }"
          >
        </b-button>

        <b-collapse
          id="collapse-2"
          v-model="status_language_requirement"
          class="w-100"
        >
          <b-card>
            <b-form-checkbox-group
              v-model="formData.language_requirements"
              :options="jaLevelOption"
              value-field="key"
              text-field="value"
              stacked
            />
          </b-card>
        </b-collapse>
      </div>

      <!-- 雇用形態/ FORM OF EMPLOYEE  -->
      <div id="dusk_work_forms" class="item-search-vs-condition">
        <b-button
          class="px-0"
          :class="
            status_form_of_employment ? null : 'collapsed  btn border-none'
          "
          dusk="btn_work_forms"
          :aria-expanded="status_form_of_employment"
          aria-controls="collapse-5"
          @click="status_form_of_employment = !status_form_of_employment"
        >
          <span
            :class="[formData.form_of_employment.length > 0 ? 'text-blue' : '']"
          >
            {{ $t('SEARCH_JOB_LIST.FORM_EMPLOYEE') }}
          </span>

          <img
            :src="require('@/assets/images/login/chervon-right-light.png')"
            alt="collapse"
            :class="{ 'rotate-90deg': status_form_of_employment }"
          >
        </b-button>

        <b-collapse
          id="collapse-5"
          v-model="status_form_of_employment"
          class="w-100"
        >
          <b-card>
            <b-form-checkbox-group
              v-model="formData.form_of_employment"
              :options="formEmployeeOption"
              value-field="key"
              text-field="value"
              stacked
            />
          </b-card>
        </b-collapse>
      </div>

      <!-- 特徴/PASSION -->
      <div id="dusk_passion_works" class="item-search-vs-condition">
        <b-button
          class="px-0"
          dusk="btn_passion_works"
          :class="status_passion ? null : 'collapsed  btn border-none'"
          :aria-expanded="status_passion"
          aria-controls="collapse-5"
          @click="status_passion = !status_passion"
        >
          <span :class="[formData.passion_works.length > 0 ? 'text-blue' : '']">
            {{ $t('SEARCH_JOB_LIST.PASSION') }}
          </span>

          <img
            :src="require('@/assets/images/login/chervon-right-light.png')"
            alt="collapse"
            :class="{ 'rotate-90deg': status_passion }"
          >
        </b-button>

        <b-collapse id="collapse-5" v-model="status_passion" class="w-100">
          <b-card>
            <b-form-checkbox-group
              v-model="formData.passion_works"
              :options="passionOption"
              value-field="key"
              text-field="value"
              stacked
            />
          </b-card>
        </b-collapse>
      </div>

      <!-- フリーワード - FREE WORD -->
      <div class="item-search-vs-condition">
        <span class="w-100 d-flex align-sm-start">
          <label
            :class="[formData.key_search ? 'text-blue' : '']"
            :style="{ margin: '0', fontSize: '14px' }"
            for="free-word"
          >
            {{ $t('SEARCH_JOB_LIST.FREE_WORD') }}
          </label>
        </span>

        <div class="free-word-input">
          <b-form-input
            id="free-word"
            v-model="formData.key_search"
            dusk="input_search"
            class="cck-input"
            placeholder=""
            max-length="50"
            aria-invalid=""
            style="margin-top: 8px"
          />
          <b-icon icon="search" />
        </div>

        <!-- この条件で検索する/Search with conditions -->
        <div
          class="btn btn-search-vs-conditions btn_search--custom"
          dusk="btn_search_with_conditions"
          @click="handleSearch"
        >
          <span>{{ $t('SEARCH_JOB_LIST.SEARCH_WITH_CONDITIONS') }}</span>
          <img
            :src="require('@/assets/images/login/chervon-right-white.png')"
            alt="collapse"
            :class="{ 'rotate-90deg': status_income }"
          >
        </div>

        <!-- 設定をクリア/Clear settings -->
        <div
          class="btn btn-clear-settings"
          dusk="btn_clear_settings"
          @click="handleClearSettingsFormData"
        >
          <span>{{ $t('BUTTON.CLEAR_SETTINGS') }}</span>
        </div>
      </div>
    </div>

    <!-- MODAL -->
    <ModalMultipleSelect :options="modalOptions" />
  </div>
</template>

<script>
import EventBus from '@/utils/eventBus';
import Multiselect from 'vue-multiselect';
import ModalMultipleSelect from '@/components/Modal/ModalMultipleSelect.vue';

import { MakeToast } from '@/utils/toastMessage';
import { listCompanyOption } from '@/api/company';
import { getListCountry, getListMainjob } from '@/api/job';
import {
  incomeOption,
  jaLevelOption,
  formEmployeeOption,
  passionOption,
} from '@/const/job.js';

export default {
  name: 'JobFormSearch',
  components: {
    Multiselect,
    ModalMultipleSelect,
  },
  props: {
    hasCompany: {
      type: Boolean,
      required: false,
      default: false,
    },
  },
  data() {
    return {
      status_company: false,
      status_income: false,
      status_working_place: false,
      status_language_requirement: false,
      status_form_of_employment: false,
      status_passion: false,

      companySelectOption: [],
      incomeOption: incomeOption,
      workingPlaceOption: [],
      jaLevelOption: jaLevelOption,
      formEmployeeOption: formEmployeeOption,
      passionOption: passionOption,

      modalOptions: [],
      modalDataSelected: [],

      formData: {
        company_id: '',
        occupation: '',
        income_from: '',
        income_to: '',
        key_search: '',
        language_requirements: [],
        form_of_employment: [],
        passion_works: [],
      },

      city_id: [],
      cityList: [
        {
          timezone: '北海道・東北',
          options: [
            { id: 1, name_en: 'Hokkaido', name_ja: '北海道' },
            { id: 2, name_en: 'Aomori', name_ja: '青森県' },
            { id: 3, name_en: 'Iwate', name_ja: '岩手県' },
            { id: 4, name_en: 'Miyagi', name_ja: '宮城県' },
            { id: 5, name_en: 'Akita', name_ja: '秋田県' },
            { id: 6, name_en: 'Yamagata', name_ja: '山形県' },
            { id: 7, name_en: 'Fukushima', name_ja: '福島県' },
          ],
        },
        {
          timezone: '北陸・甲信越',
          options: [
            { id: 16, name_en: 'Toyama', name_ja: '富山県' },
            { id: 17, name_en: 'Ishikawa', name_ja: '石川県' },
            { id: 18, name_en: 'Fukui', name_ja: '福井県' },
            { id: 15, name_en: 'Niigata', name_ja: '新潟県' },
            { id: 19, name_en: 'Yamanashi', name_ja: '山梨県' },
            { id: 20, name_en: 'Nagano', name_ja: '長野県' },
          ],
        },
        {
          timezone: '関東',
          options: [
            { id: 13, name_en: 'Tokyo', name_ja: '東京都' },
            { id: 14, name_en: 'Kanagawa', name_ja: '神奈川県' },
            { id: 12, name_en: 'Chiba', name_ja: '千葉県' },
            { id: 11, name_en: 'Saitama', name_ja: '埼玉県' },
            { id: 8, name_en: 'Ibaraki', name_ja: '茨城県' },
            { id: 9, name_en: 'Tochigi', name_ja: '栃木県' },
            { id: 10, name_en: 'Gunma', name_ja: '群馬県' },
          ],
        },
        {
          timezone: '東海',
          options: [
            { id: 23, name_en: 'Aichi', name_ja: '愛知県' },
            { id: 22, name_en: 'Shizuoka', name_ja: '静岡県' },
            { id: 21, name_en: 'Gifu', name_ja: '岐阜県' },
            { id: 24, name_en: 'Mie', name_ja: '三重県' },
          ],
        },
        {
          timezone: '関西',
          options: [
            { id: 27, name_en: 'Osaka', name_ja: '大阪府' },
            { id: 26, name_en: 'Kyoto', name_ja: '京都府' },
            { id: 28, name_en: 'Hyogo', name_ja: '兵庫県' },
            { id: 25, name_en: 'Shiga', name_ja: '滋賀県' },
            { id: 29, name_en: 'Nara', name_ja: '奈良県' },
            { id: 30, name_en: 'Wakayama', name_ja: '和歌山県' },
          ],
        },
        {
          timezone: '中国',
          options: [
            { id: 34, name_en: 'Hiroshima', name_ja: '広島県' },
            { id: 33, name_en: 'Okayama', name_ja: '岡山県' },
            { id: 31, name_en: 'Tottori', name_ja: '鳥取県' },
            { id: 32, name_en: 'Shimane', name_ja: '島根県' },
            { id: 35, name_en: 'Yamaguchi', name_ja: '山口県' },
          ],
        },
        {
          timezone: '四国',
          options: [
            { id: 36, name_en: 'Tokushima', name_ja: '徳島県' },
            { id: 37, name_en: 'Kagawa', name_ja: '香川県' },
            { id: 38, name_en: 'Ehime', name_ja: '愛媛県' },
            { id: 39, name_en: 'Kochi', name_ja: '高知県' },
          ],
        },
        {
          timezone: '九州・沖縄',
          options: [
            { id: 40, name_en: 'Fukuoka', name_ja: '福岡県' },
            { id: 43, name_en: 'Kumamoto', name_ja: '熊本県' },
            { id: 41, name_en: 'Saga', name_ja: '佐賀県' },
            { id: 42, name_en: 'Nagasaki', name_ja: '長崎県' },
            { id: 44, name_en: 'Ōita', name_ja: '大分県' },
            { id: 45, name_en: 'Miyazaki', name_ja: '宮崎県' },
            { id: 46, name_en: 'Kagoshima', name_ja: '鹿児島県' },
            { id: 47, name_en: 'Okinawa', name_ja: '沖縄県' },
          ],
        },
      ],
    };
  },
  created() {
    this.handleSyncJobSearchData();

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

    this.getListCountry();

    this.getListMainJob();

    if (this.hasCompany) {
      this.getListOptionCompany();
    }
  },

  methods: {
    handleSyncJobSearchData() {
      const syncData = this.$store.getters.job_search_data;

      // console.log('syncData ở JobFormSearch chung', syncData);

      if (syncData) {
        this.modalDataSelected = syncData['middle_classification_id'];

        if (this.modalDataSelected) {
          this.status_job = true;
        }

        this.city_id = syncData['city_id'];

        if (this.city_id.length > 0) {
          this.status_working_place = true;
        }

        this.formData.language_requirements = syncData['language_requirements'];

        if (this.formData.language_requirements.length > 0) {
          this.status_language_requirement = true;
        }

        this.formData.form_of_employment = syncData['form_of_employment'];

        if (this.formData.form_of_employment.length > 0) {
          this.status_form_of_employment = true;
        }

        this.formData.passion_works = syncData['passion_works'];

        if (this.formData.passion_works.length > 0) {
          this.status_passion = true;
        }

        this.formData.income_from = syncData['income_from'];
        this.formData.income_to = syncData['income_to'];

        if (this.formData.income_from || this.formData.income_to) {
          this.status_income = true;
        }

        this.formData.key_search = syncData['key_search'];
      } else {
        Object.assign(this.formData, {
          company_id: '',
          occupation: '',
          income_from: '',
          income_to: '',
          key_search: '',
          language_requirements: [],
          form_of_employment: [],
          passion_works: [],
        });
        this.status_company = false;
        this.status_income = false;
        this.status_working_place = false;
        this.status_language_requirement = false;
        this.status_form_of_employment = false;
        this.status_passion = false;
        this.key_search = false;
      }
    },

    async getListOptionCompany() {
      try {
        const response = await listCompanyOption();
        const { code, message } = response.data;
        if (code === 200) {
          const { data } = response.data;
          this.companySelectOption = data.map((item) => {
            return {
              key: item.id,
              value: item.company_name_jp,
            };
          });
        } else {
          MakeToast({
            variant: 'warning',
            title: this.$t('warning'),
            content: message,
          });
        }
      } catch (error) {
        console.log('error');
      }
    },

    async getListCountry() {
      try {
        const response = await getListCountry();
        const { code, message } = response.data;
        if (code === 200) {
          const { data } = response.data;
          this.workingPlaceOption = data.map((item) => {
            return {
              key: item.id,
              value: item.name_ja,
            };
          });
        } else {
          MakeToast({
            variant: 'warning',
            title: this.$t('warning'),
            content: message,
          });
        }
      } catch (error) {
        console.log('error');
      }
    },

    async getListMainJob() {
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

    handleOpenModalOccupation() {
      EventBus.$emit('showModalSelect', true);
    },

    handleClearSettingsFormData() {
      Object.assign(this.formData, {
        company_id: '',
        occupation: '',
        income_from: '',
        income_to: '',
        key_search: '',
        language_requirements: [],
        form_of_employment: [],
        passion_works: [],
      });
      this.modalDataSelected = [];
      this.city_id = [];
      EventBus.$emit('handleClearSettingsModal');
    },

    handleRemoveSelected(id) {
      this.modalDataSelected = this.modalDataSelected.filter(
        (item) => item.id !== Number(id)
      );
      EventBus.$emit('removeSelected', Number(id));
    },

    async handleSearch() {
      const is_getSearchStore = this.$store.getters.job_search_data;
      if (is_getSearchStore) {
        const finalFormData = {
          ...this.formData,
          middle_classification_id: this.modalDataSelected,
          city_id: this.city_id,
          per_page: 15,
          page: 1,
        };
        await this.$store.dispatch('jobSearch/setJobSearchData', finalFormData);
        this.$emit('handleSearch', finalFormData);
      } else {
        const middle_classification_id = this.modalDataSelected;

        const finalFormData = {
          ...this.formData,
          city_id: this.city_id,
          middle_classification_id,
          per_page: 15,
          page: 1,
        };

        // console.log('finalFormData==> 2', finalFormData);
        await this.$store.dispatch('jobSearch/setJobSearchData', finalFormData);
        this.$emit('handleSearch', finalFormData);
      }
    },
  },
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style lang="scss" scoped>
@import '@/scss/modules/common/common.scss';
.search-with-conditions {
  background: #ffffff;
  border: 1px solid #c6c6c6;
  border-radius: 2px;
  width: 100%;
}

.search-with-conditions-wrap {
  width: 100%;
  height: 100%;
}

.item-search-vs-condition {
  border-top: 0.5px solid #c6c6c6;
  padding: 12px;
  display: flex;
  flex-direction: column;
  justify-content: center;

  &:first-child {
    border: none;
  }

  &:nth-child(2) {
    width: 100%;
  }

  & button {
    background: none !important;
    width: 100%;
    font-style: normal;
    font-weight: 700;
    font-size: 14px;
    line-height: 21px;
    color: #333333;
    padding: 0.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
}

::v-deep .btn-secondary {
  border: none !important;
  padding: 0;

  &:focus {
    border: none !important;
    box-shadow: none !important;
  }
}
::v-deep .card-body {
  border: none !important;
  padding: 0 !important;
  display: flex;
  flex-direction: column;
}

.checkbox-layout {
  width: 100%;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: flex-start;
  margin: 0;
  font-weight: 400;
  font-size: 14px;
  line-height: 21px;
  color: #304cad;
}

.card {
  border: none;
}

.occupation-head {
  width: 100%;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 1rem;

  & span:first-child {
    font-weight: 700;
    font-size: 14px;
    line-height: 21px;
  }
}

.search-using {
  color: #4056fc !important;
}

.select-job-btn {
  border: 1px solid #919191;
  border-radius: 5px;
  background: #ffffff;
  padding: 4px 0.5rem;

  & span {
    width: 16px;
    height: 16px;
    font-weight: 700;
    font-size: 10px;
    line-height: 12px;
    color: #858585;
  }
}

.select-two-value {
  background: #ffffff;
  border-radius: 3px;
  width: 100%;
  display: flex;
  flex-direction: column;
  gap: 0.75rem;

  & > div {
    display: flex;
    justify-content: flex-start;
    align-items: center;
    gap: 0.75rem;

    & select {
      width: 76%;
      height: 43px;
    }
  }
}

.free-word-input {
  width: 100%;
  display: flex;
  justify-content: flex-start;
  align-items: center;
  position: relative;

  & input {
    border: 1px solid green;
    padding-left: 2.5rem !important;
  }

  & svg {
    position: absolute;
    top: 40%;
    left: 12px;
  }
}

::v-deep .modal-content {
  min-width: 782px;
  min-height: 512px;
  transform: translate(-18%, 25%);
}

::v-deep .modal-body {
  align-items: stretch;
}

.content-modal {
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: stretch;
  gap: 1.25rem;
}

.content-modal-options {
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: stretch;
  gap: 1.5rem;
}

.collapse-parents {
  width: 40%;
  height: 315px;
  overflow-y: auto;
}

.collapse-item {
  background: #ffffff;
  border: 1px solid #c6c6c6;
  border-top: none;
  border-radius: 0 !important;
  padding: 1.076rem;
  display: flex;
  justify-content: flex-start;
  align-items: center;
  position: relative;

  &:first-child {
    border-radius: 5px 5px 0 0 !important;
    border-top: 1px solid #c6c6c6;
  }

  & span {
    font-weight: 700;
    font-size: 14px;
    line-height: 17px;
    color: #333333;
  }

  & img:last-child {
    position: absolute;
    top: 1rem;
    right: 1rem;
  }
}

.checked {
  height: 17px;
  position: absolute;
  top: 1.1rem;
  right: 2.5rem;
}

.collapse-item--active {
  background: #304cad;
  color: #333333;

  & span {
    color: #ffffff;
  }
}

.collapse-item--selected {
  background: #304cad !important;
  color: #333333;

  & span {
    color: #ffffff;
  }
}

.content-collapse-options {
  background: #ffffff;
  border: 1px solid #c6c6c6;
  border-radius: 5px;
  width: 60%;
  padding: 0.25rem 0.5rem;
  height: 315px;
}

.head-collapse-parents-title {
  padding: 0.5rem 0.75rem;
  background: #f1f1f1;
  box-shadow: 0px 2px 4px rgba(61, 61, 61, 0.25);

  & span {
    font-weight: 700;
    font-size: 14px;
    line-height: 17px;
    color: #072470;
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

.d-none {
  visibility: hidden;
}

.text-blue {
  color: blue;
}

.list-item-checked {
  display: flex;
  align-items: center;
}

.icon-checked {
  display: inline-block;
  width: 16px;
  height: 16px;
  margin-right: 5px;
  background-color: #007bff;
  border-radius: 0.25rem;
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath fill='%23fff' d='M6.564.75l-3.59 3.612-1.538-1.55L0 4.26l2.974 2.99L8 2.193z'/%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: center;
  cursor: pointer;
}

::v-deep
  .multiselect
  > .multiselect__content-wrapper
  > .multiselect__content
  > .multiselect__element
  > .multiselect__option--highlight {
  color: #ffffff;
  background-color: #072470;

  &::after {
    background-color: #072470;
  }
}

::v-deep
  .multiselect
  > .multiselect__content-wrapper
  > .multiselect__content
  > .multiselect__element
  > .multiselect__option--selected.multiselect__option--highlight {
  color: #ffffff;
  background-color: #ff6a6a;

  &::after {
    background-color: #ff6a6a;
  }
}

::v-deep
  .multiselect
  > .multiselect__content-wrapper
  > .multiselect__content
  > .multiselect__element
  > .multiselect__option--highlight
  > .option-label {
  color: #ffffff;
}

::v-deep
  .multiselect
  > .multiselect__tags
  > .multiselect__tags-wrap
  > .multiselect__tag {
  background-color: #072470 !important;

  & > .multiselect__tag-icon {
    line-height: 20px !important;
  }

  & > .multiselect__tag-icon::after {
    color: #ffffff !important;
  }

  & > .multiselect__tag-icon:focus,
  .multiselect__tag-icon:hover {
    background-color: #ff6a6a !important;
  }
}

::v-deep .multiselect__option--group {
  font-size: 14px !important;
  color: #000000 !important;
  background-color: #ffffff !important;
}

::v-deep .option-label {
  font-size: 12px;
  margin-left: 10px;
  color: #333333;
}

::v-deep .multiselect__option::after {
  font-size: 9px !important;
}

::v-deep .multiselect__tag {
  padding: 2px 24px 4px 6px;

  & > span {
    font-size: 10px;
  }
}
</style>
