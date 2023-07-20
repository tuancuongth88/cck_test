<template>
  <div class="search-with-conditions">
    <div class="search-with-conditions-wrap">
      <!-- 0 企業 Company -->
      <div v-if="hasCompany" class="item-search-vs-condition">
        <b-button
          class="px-0"
          :class="status_company ? null : 'collapsed  btn border-none'"
          :aria-expanded="status_company"
          aria-controls="collapse-2"
          @click="status_company = !status_company"
        >
          <span :class="[formData.company ? 'text-blue' : '']">{{
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
              v-model="formData.company"
              :options="companyOption"
              value-field="value"
              text-field="text"
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
          <div class="btn select-job-btn" @click="handleOpenModalOccupation()">
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
          :class="status_income ? null : 'collapsed  btn border-none'"
          :aria-expanded="status_income"
          aria-controls="collapse-1"
          @click="status_income = !status_income"
        >
          <span
            :class="[
              formData.income_from && formData.income_to ? 'text-blue' : '',
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
                  :options="incomeOption"
                  value-field="key"
                  text-field="value"
                />
                <span>以上</span>
              </div>
              <div>
                <b-form-select
                  v-model="formData.income_to"
                  :options="incomeOption"
                  value-field="key"
                  text-field="value"
                />
                <span>以上</span>
              </div>
            </div>
          </b-card>
        </b-collapse>
      </div>
      <!-- 勤務地（都道府県） working palace (tỉnh,TP) -->
      <div class="item-search-vs-condition">
        <b-button
          class="px-0"
          :class="status_working_place ? null : 'collapsed  btn border-none'"
          :aria-expanded="status_working_place"
          aria-controls="collapse-2"
          @click="status_working_place = !status_working_place"
        >
          <span :class="[formData.city_id ? 'text-blue' : '']">{{
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
            <b-form-select
              v-model="formData.city_id"
              :options="workingPlaceOption"
              value-field="key"
              text-field="value"
            />
          </b-card>
        </b-collapse>
      </div>
      <!-- 言語条件 Language requirement -->
      <div class="item-search-vs-condition">
        <b-button
          class="px-0"
          :class="
            status_language_requirement ? null : 'collapsed  btn border-none'
          "
          :aria-expanded="status_language_requirement"
          aria-controls="collapse-2"
          @click="status_language_requirement = !status_language_requirement"
        >
          <span
            :class="[
              formData.language_requirements.length > 0 ? 'text-blue' : '',
            ]"
          >{{ $t('SEARCH_JOB_LIST.LANGUAGE_REQUIREMENT') }}</span>
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
      <div class="item-search-vs-condition">
        <b-button
          class="px-0"
          :class="
            status_form_of_employment ? null : 'collapsed  btn border-none'
          "
          :aria-expanded="status_form_of_employment"
          aria-controls="collapse-5"
          @click="status_form_of_employment = !status_form_of_employment"
        >
          <span
            :class="[formData.form_of_employment.length > 0 ? 'text-blue' : '']"
          >{{ $t('SEARCH_JOB_LIST.FORM_EMPLOYEE') }}</span>
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
      <div class="item-search-vs-condition">
        <b-button
          class="px-0"
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
            class="cck-input"
            placeholder=""
            max-length="50"
            aria-invalid=""
            style="margin-top: 8px"
          />
          <b-icon icon="search" />
        </div>

        <!-- この条件で検索する/Search with conditions -->
        <div class="btn btn-search-vs-conditions" @click="handleSearch">
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
import {
  incomeOption,
  jaLevelOption,
  formEmployeeOption,
  passionOption,
} from '@/const/job.js';
import ModalMultipleSelect from '@/components/Modal/ModalMultipleSelect.vue';
import { getListCountry, getListMainjob } from '@/api/job';
import { MakeToast } from '@/utils/toastMessage';

export default {
  name: 'JobFormSearch',
  components: {
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
      // status for collapse
      status_company: false,
      status_income: false,
      status_working_place: false,
      status_language_requirement: false,
      status_form_of_employment: false,
      status_passion: false,

      // Fake data
      companyOption: [
        { value: null, text: ' 選択してください', translate: 'please select' },
        { value: 'Company 1', text: 'Company 1', translate: 'Company 1' },
        { value: 'Company 2', text: 'Company 2', translate: 'Company 2' },
        { value: 'Company 3', text: 'Company 3', translate: 'Company 3' },
        { value: 'Company 4', text: 'Company 4', translate: 'Company 4' },
      ],
      incomeOption: incomeOption,
      workingPlaceOption: [],
      jaLevelOption: jaLevelOption,
      formEmployeeOption: formEmployeeOption,
      passionOption: passionOption,

      // Modal
      modalOptions: [],
      modalDataSelected: [],

      formData: {
        // company: '',
        occupation: '',
        income_from: '',
        income_to: '',
        key_search: '',
        city_id: '',
        language_requirements: [],
        form_of_employment: [],
        passion_works: [],
      },
    };
  },
  watch: {
    // selectedOptions(newValue, oldValue) {
    //   // Handle changes in individual flavour checkboxes
    //   // if (newValue.length === 0) {
    //   //   this.allChildSelected = false;
    //   // } else if (newValue.length === this.childOption.length) {
    //   //   this.allChildSelected = true;
    //   // } else {
    //   //   this.allChildSelected = false;
    //   // }
    // },
  },
  created() {
    this.$bus.on('dataModalMultiple', (data) => {
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
  },

  methods: {
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
        console.log('error', error);
      }
    },

    handleOpenModalOccupation() {
      this.$bus.emit('showModalSelect', true);
    },

    handleClearSettingsFormData() {
      Object.assign(this.formData, {
        company: '',
        occupation: '',
        income_from: '',
        income_to: '',
        key_search: '',
        city_id: '',
        language_requirements: [],
        form_of_employment: [],
        passion_works: [],
      });
      this.modalDataSelected = [];
      this.$bus.emit('handleClearSettingsModal');
    },

    handleRemoveSelected(id) {
      this.modalDataSelected = this.modalDataSelected.filter(
        (item) => item.id !== Number(id)
      );
      this.$bus.emit('removeSelected', Number(id));
    },

    handleSearch() {
      const middle_classification_id = this.modalDataSelected.flatMap(
        (item) => item.childOptions
      );
      const finalFormData = {
        ...this.formData,
        middle_classification_id,
      };

      console.log('finalFormData===>', finalFormData);
      this.$emit('handleSearch', finalFormData);
    },
  },
};
</script>

<style lang="scss" scoped>
// @import '@/scss/_variables.scss';
@import '@/scss/modules/common/common.scss';
.search-with-conditions {
  background: #ffffff;
  border: 1px solid #c6c6c6;
  border-radius: 2px;
  width: 100%;
  // min-height: 100vh;
}

.search-with-conditions-wrap {
  // border: 1px solid red;
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
// Css ghi đè collapse
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
//
.checkbox-layout {
  // border: 1px solid red;
  width: 100%;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: flex-start;
  margin: 0;
  // margin-top: 1.5rem;
  font-weight: 400;
  font-size: 14px;
  line-height: 21px;
  color: #304cad;
}
.card {
  border: none;
}
//
.occupation-head {
  width: 100%;
  // padding: 0 0.5rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 1rem;
  & span:first-child {
    font-weight: 700;
    font-size: 14px;
    line-height: 21px;
    // color: #333333;
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
  // border: 1px solid #CDCDCD;
  border-radius: 3px;
  // border: none;
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
// Modal
::v-deep .modal-content {
  // border: 1px solid red !important;
  min-width: 782px;
  min-height: 512px;
  transform: translate(-18%, 25%);
}
::v-deep .modal-body {
  align-items: stretch;
}
.content-modal {
  // border: 1px solid red !important;
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: stretch;
  gap: 1.25rem;
}
.content-modal-options {
  // border: 1px solid blue !important;
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
  // & img {
  //   width: 20px;
  //   height: 20px;
  // }
  & img:last-child {
    // border: 1px solid blue;
    position: absolute;
    top: 1rem;
    right: 1rem;
  }
}
.checked {
  // border: 1px solid red;
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
</style>
